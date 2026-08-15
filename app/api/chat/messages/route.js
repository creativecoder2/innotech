import { NextResponse } from 'next/server';
import connectToDatabase from '@/lib/db';
import ChatSession from '@/models/ChatSession';
import { getLocalStore, saveLocalStore } from '@/lib/storage';

export const dynamic = 'force-dynamic';
export const revalidate = 0;

export async function POST(req) {
  try {
    const body = await req.json();
    const { sessionId, text, sender = 'user', senderName } = body;

    if (!sessionId) {
      return NextResponse.json({ success: false, message: 'Session ID is required' }, { status: 400 });
    }
    if (!text || !text.trim()) {
      return NextResponse.json({ success: false, message: 'Message text is required' }, { status: 400 });
    }

    const now = new Date();
    const messageId = 'msg_' + Date.now() + '_' + Math.random().toString(36).substring(2, 7);

    const newMessage = {
      id: messageId,
      sender: sender === 'admin' ? 'admin' : 'user',
      senderName: senderName || (sender === 'admin' ? 'Innotech Support' : 'Visitor'),
      text: text.trim(),
      timestamp: now,
      read: false,
    };

    // 1. Update in local store
    const local = getLocalStore();
    const siteConfig = local?.config || fallbackSiteConfig;
    const chatConfig = siteConfig?.chatWidget || fallbackSiteConfig.chatWidget;

    const sessions = Array.isArray(local.chatSessions) ? [...local.chatSessions] : [];
    const sessionIndex = sessions.findIndex((s) => s.sessionId === sessionId);

    let updatedSession = null;
    let autoReplyMessage = null;

    if (sessionIndex >= 0) {
      const current = sessions[sessionIndex];
      const shouldAutoReply =
        sender === 'user' &&
        !current.autoReplySent &&
        chatConfig?.autoReplyEnabled !== false &&
        Boolean(chatConfig?.autoReplyMessage);

      let messagesToAdd = [newMessage];

      if (shouldAutoReply) {
        autoReplyMessage = {
          id: 'msg_' + (Date.now() + 100) + '_autoreply',
          sender: 'admin',
          senderName: chatConfig?.agentName || 'Innotech Support Team',
          text: chatConfig.autoReplyMessage,
          timestamp: new Date(Date.now() + 100),
          read: true,
        };
        messagesToAdd.push(autoReplyMessage);
      }

      const messages = Array.isArray(current.messages)
        ? [...current.messages, ...messagesToAdd]
        : messagesToAdd;

      const unreadAdmin = sender === 'user' ? (current.unreadAdminCount || 0) + 1 : current.unreadAdminCount || 0;
      const unreadUser = sender === 'admin' ? (current.unreadUserCount || 0) + 1 : current.unreadUserCount || 0;

      updatedSession = {
        ...current,
        messages,
        lastMessage: text.trim(),
        lastMessageAt: now,
        unreadAdminCount: unreadAdmin,
        unreadUserCount: unreadUser,
        autoReplySent: shouldAutoReply ? true : current.autoReplySent || false,
        status: current.status === 'closed' ? 'active' : current.status || 'active',
        updatedAt: now,
      };

      sessions[sessionIndex] = updatedSession;
      saveLocalStore({ chatSessions: sessions });
    }

    // 2. Update in MongoDB
    try {
      const conn = await connectToDatabase();
      if (conn) {
        const incFields = {};
        if (sender === 'user') incFields.unreadAdminCount = 1;
        if (sender === 'admin') incFields.unreadUserCount = 1;

        const messagesToPush = autoReplyMessage ? [newMessage, autoReplyMessage] : [newMessage];

        const setFields = {
          lastMessage: text.trim(),
          lastMessageAt: now,
          status: 'active',
        };
        if (autoReplyMessage) {
          setFields.autoReplySent = true;
        }

        const dbRes = await ChatSession.findOneAndUpdate(
          { sessionId },
          {
            $push: { messages: { $each: messagesToPush } },
            $set: setFields,
            ...(Object.keys(incFields).length > 0 ? { $inc: incFields } : {}),
          },
          { new: true }
        ).lean();

        if (dbRes) {
          updatedSession = dbRes;
        }
      }
    } catch (dbErr) {
      console.warn('MongoDB error saving chat message:', dbErr.message);
    }

    if (!updatedSession && sessionIndex === -1) {
      return NextResponse.json({ success: false, message: 'Chat session not found' }, { status: 404 });
    }

    return NextResponse.json({
      success: true,
      message: 'Message sent successfully',
      newMessage,
      autoReplyMessage,
      session: updatedSession,
    });
  } catch (error) {
    console.error('Error sending message:', error);
    return NextResponse.json(
      { success: false, message: error.message || 'Failed to send message' },
      { status: 500 }
    );
  }
}

export async function GET(req) {
  try {
    const { searchParams } = new URL(req.url);
    const sessionId = searchParams.get('sessionId');

    if (!sessionId) {
      return NextResponse.json({ success: false, message: 'Session ID is required' }, { status: 400 });
    }

    // Check MongoDB first
    try {
      const conn = await connectToDatabase();
      if (conn) {
        const session = await ChatSession.findOne({ sessionId }).lean();
        if (session) {
          return NextResponse.json({
            success: true,
            messages: session.messages || [],
            status: session.status,
            session,
          });
        }
      }
    } catch (e) {}

    // Fallback to local store
    const local = getLocalStore();
    const sessions = Array.isArray(local.chatSessions) ? local.chatSessions : [];
    const session = sessions.find((s) => s.sessionId === sessionId);

    if (!session) {
      return NextResponse.json({ success: false, message: 'Session not found' }, { status: 404 });
    }

    return NextResponse.json({
      success: true,
      messages: session.messages || [],
      status: session.status,
      session,
    });
  } catch (error) {
    return NextResponse.json(
      { success: false, message: error.message || 'Error fetching messages' },
      { status: 500 }
    );
  }
}
