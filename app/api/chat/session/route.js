import { NextResponse } from 'next/server';
import connectToDatabase from '@/lib/db';
import ChatSession from '@/models/ChatSession';
import SiteConfig from '@/models/SiteConfig';
import { getLocalStore, saveLocalStore } from '@/lib/storage';
import { fallbackSiteConfig } from '@/lib/data';

export const dynamic = 'force-dynamic';
export const revalidate = 0;

export async function POST(req) {
  try {
    const body = await req.json();
    let { sessionId, userName, userPhone, userCity, initialMessage } = body;

    if (!userName || !userName.trim()) {
      return NextResponse.json({ success: false, message: 'Full name is required' }, { status: 400 });
    }
    if (!userPhone || !userPhone.trim()) {
      return NextResponse.json({ success: false, message: 'Phone number is required' }, { status: 400 });
    }
    if (!userCity || !userCity.trim()) {
      return NextResponse.json({ success: false, message: 'City is required' }, { status: 400 });
    }

    if (!sessionId) {
      sessionId = 'chat_' + Date.now() + '_' + Math.random().toString(36).substring(2, 8);
    }

    // Get site configuration for greeting message & agent name
    const local = getLocalStore();
    const siteConfig = local?.config || fallbackSiteConfig;
    const chatConfig = siteConfig?.chatWidget || fallbackSiteConfig.chatWidget;

    const agentName = chatConfig?.agentName || 'Innotech Support Team';
    const welcomeText =
      chatConfig?.welcomeMessage ||
      `Thank you ${userName.trim()}! Welcome to Innotech Medical Support. Our support representative will assist you shortly. How can we help you today?`;

    const now = new Date();

    const welcomeMsg = {
      id: 'msg_' + Date.now() + '_welcome',
      sender: 'admin',
      senderName: agentName,
      text: welcomeText,
      timestamp: now,
      read: true,
    };

    const initialMessages = [welcomeMsg];
    let lastMsgText = welcomeText;
    let unreadAdmin = 0;
    let autoReplySent = false;

    if (initialMessage && initialMessage.trim()) {
      const userMsg = {
        id: 'msg_' + (Date.now() + 1) + '_user',
        sender: 'user',
        senderName: userName.trim(),
        text: initialMessage.trim(),
        timestamp: new Date(Date.now() + 100),
        read: false,
      };
      initialMessages.push(userMsg);
      lastMsgText = initialMessage.trim();
      unreadAdmin = 1;

      // If auto-reply is enabled, append auto-reply right after the user message
      if (chatConfig?.autoReplyEnabled !== false && chatConfig?.autoReplyMessage) {
        const autoReplyMsg = {
          id: 'msg_' + (Date.now() + 200) + '_autoreply',
          sender: 'admin',
          senderName: agentName,
          text: chatConfig.autoReplyMessage,
          timestamp: new Date(Date.now() + 200),
          read: true,
        };
        initialMessages.push(autoReplyMsg);
        autoReplySent = true;
      }
    }

    const sessionData = {
      sessionId,
      userName: userName.trim(),
      userPhone: userPhone.trim(),
      userCity: userCity.trim(),
      status: 'active',
      unreadAdminCount: unreadAdmin,
      unreadUserCount: 0,
      lastMessage: lastMsgText,
      lastMessageAt: now,
      messages: initialMessages,
      autoReplySent,
      createdAt: now,
      updatedAt: now,
    };

    // 1. Save to local JSON store
    const currentSessions = Array.isArray(local.chatSessions) ? local.chatSessions : [];
    const existingIndex = currentSessions.findIndex((s) => s.sessionId === sessionId);
    let updatedSessions;

    if (existingIndex >= 0) {
      updatedSessions = [...currentSessions];
      updatedSessions[existingIndex] = {
        ...updatedSessions[existingIndex],
        ...sessionData,
      };
    } else {
      updatedSessions = [sessionData, ...currentSessions];
    }
    saveLocalStore({ chatSessions: updatedSessions });

    // 2. Save to MongoDB if connected
    try {
      const conn = await connectToDatabase();
      if (conn) {
        await ChatSession.findOneAndUpdate(
          { sessionId },
          { $set: sessionData },
          { upsert: true, new: true }
        );
      }
    } catch (dbErr) {
      console.warn('MongoDB error saving chat session:', dbErr.message);
    }

    return NextResponse.json({
      success: true,
      message: 'Chat session initialized',
      session: sessionData,
      sessionId,
    });
  } catch (error) {
    console.error('Error starting chat session:', error);
    return NextResponse.json(
      { success: false, message: error.message || 'Failed to start chat session' },
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

    // Read from local store immediately
    const local = getLocalStore();
    const sessions = Array.isArray(local.chatSessions) ? local.chatSessions : [];
    let session = sessions.find((s) => s.sessionId === sessionId);

    if (session) {
      session.unreadUserCount = 0;
      if (Array.isArray(session.messages)) {
        session.messages.forEach((m) => {
          if (m.sender === 'admin' || m.sender === 'system') m.read = true;
        });
      }
      saveLocalStore({ chatSessions: sessions });
    }

    if (!session) {
      return NextResponse.json({ success: false, message: 'Session not found' }, { status: 404 });
    }

    return NextResponse.json({
      success: true,
      session,
    });
  } catch (error) {
    return NextResponse.json(
      { success: false, message: error.message || 'Error fetching chat session' },
      { status: 500 }
    );
  }
}
