import { NextResponse } from 'next/server';
import connectToDatabase from '@/lib/db';
import ChatSession from '@/models/ChatSession';
import { getLocalStore, saveLocalStore } from '@/lib/storage';

export const dynamic = 'force-dynamic';
export const revalidate = 0;

export async function GET(req) {
  try {
    const { searchParams } = new URL(req.url);
    const sessionId = searchParams.get('sessionId');
    const status = searchParams.get('status'); // 'all', 'active', 'resolved', 'unread'
    const search = searchParams.get('search'); // text search

    // 1. If fetching a specific session
    if (sessionId) {
      let session = null;
      try {
        const conn = await connectToDatabase();
        if (conn) {
          session = await ChatSession.findOne({ sessionId }).lean();
        }
      } catch (e) {}

      if (!session) {
        const local = getLocalStore();
        const sessions = Array.isArray(local.chatSessions) ? local.chatSessions : [];
        session = sessions.find((s) => s.sessionId === sessionId);
      }

      if (!session) {
        return NextResponse.json({ success: false, message: 'Chat session not found' }, { status: 404 });
      }

      return NextResponse.json({ success: true, session });
    }

    // 2. Fetching list of all sessions
    let sessionsList = [];

    try {
      const conn = await connectToDatabase();
      if (conn) {
        const query = {};
        if (status && status !== 'all') {
          if (status === 'unread') {
            query.unreadAdminCount = { $gt: 0 };
          } else {
            query.status = status;
          }
        }
        if (search && search.trim()) {
          const regex = new RegExp(search.trim(), 'i');
          query.$or = [
            { userName: regex },
            { userPhone: regex },
            { userCity: regex },
            { lastMessage: regex },
          ];
        }

        sessionsList = await ChatSession.find(query).sort({ lastMessageAt: -1 }).lean();
      }
    } catch (dbErr) {
      console.warn('MongoDB error fetching chat sessions list:', dbErr.message);
    }

    // If MongoDB didn't return or was empty, check local store
    if (!sessionsList || sessionsList.length === 0) {
      const local = getLocalStore();
      let localSessions = Array.isArray(local.chatSessions) ? [...local.chatSessions] : [];

      if (status && status !== 'all') {
        if (status === 'unread') {
          localSessions = localSessions.filter((s) => (s.unreadAdminCount || 0) > 0);
        } else {
          localSessions = localSessions.filter((s) => s.status === status);
        }
      }

      if (search && search.trim()) {
        const q = search.trim().toLowerCase();
        localSessions = localSessions.filter(
          (s) =>
            (s.userName && s.userName.toLowerCase().includes(q)) ||
            (s.userPhone && s.userPhone.toLowerCase().includes(q)) ||
            (s.userCity && s.userCity.toLowerCase().includes(q)) ||
            (s.lastMessage && s.lastMessage.toLowerCase().includes(q))
        );
      }

      localSessions.sort((a, b) => new Date(b.lastMessageAt || 0) - new Date(a.lastMessageAt || 0));
      sessionsList = localSessions;
    }

    // Calculate total stats
    const totalCount = sessionsList.length;
    const unreadCount = sessionsList.reduce((acc, s) => acc + (s.unreadAdminCount || 0), 0);
    const activeCount = sessionsList.filter((s) => s.status === 'active').length;
    const resolvedCount = sessionsList.filter((s) => s.status === 'resolved').length;

    return NextResponse.json({
      success: true,
      sessions: sessionsList,
      stats: {
        total: totalCount,
        unread: unreadCount,
        active: activeCount,
        resolved: resolvedCount,
      },
    });
  } catch (error) {
    console.error('Error fetching admin chat sessions:', error);
    return NextResponse.json(
      { success: false, message: error.message || 'Error fetching chat sessions' },
      { status: 500 }
    );
  }
}

export async function POST(req) {
  try {
    const body = await req.json();
    const { action, sessionId, messageText, senderName = 'Innotech Support', newStatus } = body;

    if (!sessionId) {
      return NextResponse.json({ success: false, message: 'Session ID is required' }, { status: 400 });
    }

    const local = getLocalStore();
    const sessions = Array.isArray(local.chatSessions) ? [...local.chatSessions] : [];
    const sessionIndex = sessions.findIndex((s) => s.sessionId === sessionId);

    // 1. Action: Reply from Admin
    if (action === 'reply') {
      if (!messageText || !messageText.trim()) {
        return NextResponse.json({ success: false, message: 'Message text is required' }, { status: 400 });
      }

      const now = new Date();
      const newMsg = {
        id: 'msg_' + Date.now() + '_' + Math.random().toString(36).substring(2, 7),
        sender: 'admin',
        senderName: senderName || 'Innotech Support',
        text: messageText.trim(),
        timestamp: now,
        read: false,
      };

      if (sessionIndex >= 0) {
        const current = sessions[sessionIndex];
        const messages = Array.isArray(current.messages) ? [...current.messages, newMsg] : [newMsg];
        sessions[sessionIndex] = {
          ...current,
          messages,
          lastMessage: messageText.trim(),
          lastMessageAt: now,
          unreadAdminCount: 0, // Admin is actively replying, so unread count for admin is 0
          unreadUserCount: (current.unreadUserCount || 0) + 1,
          updatedAt: now,
        };
        saveLocalStore({ chatSessions: sessions });
      }

      try {
        const conn = await connectToDatabase();
        if (conn) {
          await ChatSession.findOneAndUpdate(
            { sessionId },
            {
              $push: { messages: newMsg },
              $set: {
                lastMessage: messageText.trim(),
                lastMessageAt: now,
                unreadAdminCount: 0,
              },
              $inc: { unreadUserCount: 1 },
            },
            { new: true }
          );
        }
      } catch (e) {}

      return NextResponse.json({
        success: true,
        message: 'Reply sent',
        newMessage: newMsg,
      });
    }

    // 2. Action: Mark conversation as read by Admin
    if (action === 'mark_read') {
      if (sessionIndex >= 0) {
        const current = sessions[sessionIndex];
        if (Array.isArray(current.messages)) {
          current.messages.forEach((m) => {
            if (m.sender === 'user') m.read = true;
          });
        }
        sessions[sessionIndex] = {
          ...current,
          unreadAdminCount: 0,
        };
        saveLocalStore({ chatSessions: sessions });
      }

      try {
        const conn = await connectToDatabase();
        if (conn) {
          await ChatSession.updateOne(
            { sessionId },
            {
              $set: {
                unreadAdminCount: 0,
                'messages.$[elem].read': true,
              },
            },
            {
              arrayFilters: [{ 'elem.sender': 'user', 'elem.read': false }],
            }
          );
        }
      } catch (e) {}

      return NextResponse.json({ success: true, message: 'Marked as read' });
    }

    // 3. Action: Change status (active, resolved, closed)
    if (action === 'set_status') {
      if (!newStatus || !['active', 'resolved', 'closed'].includes(newStatus)) {
        return NextResponse.json({ success: false, message: 'Invalid status' }, { status: 400 });
      }

      if (sessionIndex >= 0) {
        sessions[sessionIndex] = {
          ...sessions[sessionIndex],
          status: newStatus,
          updatedAt: new Date(),
        };
        saveLocalStore({ chatSessions: sessions });
      }

      try {
        const conn = await connectToDatabase();
        if (conn) {
          await ChatSession.updateOne({ sessionId }, { $set: { status: newStatus } });
        }
      } catch (e) {}

      return NextResponse.json({ success: true, message: `Status updated to ${newStatus}` });
    }

    return NextResponse.json({ success: false, message: 'Invalid action' }, { status: 400 });
  } catch (error) {
    console.error('Error handling admin chat action:', error);
    return NextResponse.json(
      { success: false, message: error.message || 'Error processing request' },
      { status: 500 }
    );
  }
}

export async function DELETE(req) {
  try {
    const { searchParams } = new URL(req.url);
    const sessionId = searchParams.get('sessionId');

    if (!sessionId) {
      return NextResponse.json({ success: false, message: 'Session ID is required' }, { status: 400 });
    }

    const local = getLocalStore();
    const sessions = Array.isArray(local.chatSessions) ? local.chatSessions : [];
    const filtered = sessions.filter((s) => s.sessionId !== sessionId);
    saveLocalStore({ chatSessions: filtered });

    try {
      const conn = await connectToDatabase();
      if (conn) {
        await ChatSession.deleteOne({ sessionId });
      }
    } catch (e) {}

    return NextResponse.json({ success: true, message: 'Chat conversation deleted permanently' });
  } catch (error) {
    console.error('Error deleting chat session:', error);
    return NextResponse.json(
      { success: false, message: error.message || 'Error deleting session' },
      { status: 500 }
    );
  }
}
