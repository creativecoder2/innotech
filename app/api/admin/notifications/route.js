import { NextResponse } from 'next/server';
import connectToDatabase from '@/lib/db';
import Inquiry from '@/models/Inquiry';
import Comment from '@/models/Comment';
import Subscriber from '@/models/Subscriber';
import ChatSession from '@/models/ChatSession';
import { getLocalStore, saveLocalStore } from '@/lib/storage';
import { fallbackInquiries, fallbackComments, fallbackSubscribers } from '@/lib/data';

export const dynamic = 'force-dynamic';
export const revalidate = 0;

export async function GET() {
  try {
    const localStore = getLocalStore();

    const inquiriesList = Array.isArray(localStore.inquiries) ? localStore.inquiries : fallbackInquiries;
    const commentsList = Array.isArray(localStore.comments) ? localStore.comments : fallbackComments;
    const subscribersList = Array.isArray(localStore.subscribers) ? localStore.subscribers : fallbackSubscribers;
    const chatSessionsList = Array.isArray(localStore.chatSessions) ? localStore.chatSessions : [];

    // 1. Unread Inquiries
    let unreadInquiries = inquiriesList.filter(
      (i) => i.status === 'unread' || (!i.status && i.status !== 'read' && i.status !== 'replied' && i.status !== 'archived')
    ).length;

    // 2. Pending Comments (awaiting moderation)
    let pendingComments = commentsList.filter((c) => c.status === 'pending').length;

    // 3. New / Unseen Subscribers
    let newSubscribers = subscribersList.filter((s) => s.viewed !== true && s.read !== true && s.isNew !== false).length;

    // 4. Unread Chat Messages
    let unreadChats = chatSessionsList.reduce((acc, s) => acc + (s.unreadAdminCount || 0), 0);

    // Try MongoDB sync if connected
    try {
      const conn = await connectToDatabase();
      if (conn) {
        const [dbUnreadInquiries, dbPendingComments, dbNewSubscribers, dbChatSessions] = await Promise.all([
          Inquiry.countDocuments({ status: 'unread' }),
          Comment.countDocuments({ status: 'pending' }),
          Subscriber.countDocuments({ $or: [{ viewed: false }, { viewed: { $exists: false } }] }),
          ChatSession.find({ unreadAdminCount: { $gt: 0 } }).select('unreadAdminCount').lean(),
        ]);

        if (dbUnreadInquiries !== undefined) unreadInquiries = dbUnreadInquiries;
        if (dbPendingComments !== undefined) pendingComments = dbPendingComments;
        if (dbNewSubscribers !== undefined) newSubscribers = dbNewSubscribers;
        if (dbChatSessions && Array.isArray(dbChatSessions)) {
          unreadChats = dbChatSessions.reduce((acc, s) => acc + (s.unreadAdminCount || 0), 0);
        }
      }
    } catch (dbErr) {}

    return NextResponse.json({
      success: true,
      counts: {
        inquiries: unreadInquiries,
        pendingComments: pendingComments,
        subscribers: newSubscribers,
        chats: unreadChats,
      },
    });
  } catch (error) {
    console.error('Error fetching admin notifications:', error);
    return NextResponse.json({
      success: true,
      counts: {
        inquiries: 0,
        pendingComments: 0,
        subscribers: 0,
        chats: 0,
      },
    });
  }
}

// Mark notifications as read / seen
export async function POST(req) {
  try {
    const body = await req.json();
    const { type, sessionId } = body; // 'inquiries' | 'comments' | 'subscribers' | 'chats'

    const localStore = getLocalStore();

    if (type === 'subscribers') {
      const current = Array.isArray(localStore.subscribers) ? localStore.subscribers : fallbackSubscribers;
      const updated = current.map((s) => ({ ...s, viewed: true, isNew: false, read: true }));
      saveLocalStore({ subscribers: updated });

      try {
        const conn = await connectToDatabase();
        if (conn) {
          await Subscriber.updateMany({}, { $set: { viewed: true, isNew: false, read: true } });
        }
      } catch (e) {}
    }

    if (type === 'inquiries') {
      const current = Array.isArray(localStore.inquiries) ? localStore.inquiries : fallbackInquiries;
      const updated = current.map((i) => (i.status === 'unread' ? { ...i, status: 'read' } : i));
      saveLocalStore({ inquiries: updated });

      try {
        const conn = await connectToDatabase();
        if (conn) {
          await Inquiry.updateMany({ status: 'unread' }, { $set: { status: 'read' } });
        }
      } catch (e) {}
    }

    if (type === 'chats') {
      const current = Array.isArray(localStore.chatSessions) ? localStore.chatSessions : [];
      const updated = current.map((s) => {
        if (!sessionId || s.sessionId === sessionId) {
          return { ...s, unreadAdminCount: 0 };
        }
        return s;
      });
      saveLocalStore({ chatSessions: updated });

      try {
        const conn = await connectToDatabase();
        if (conn) {
          if (sessionId) {
            await ChatSession.updateOne({ sessionId }, { $set: { unreadAdminCount: 0 } });
          } else {
            await ChatSession.updateMany({}, { $set: { unreadAdminCount: 0 } });
          }
        }
      } catch (e) {}
    }

    return NextResponse.json({ success: true, message: `Marked ${type} as read` });
  } catch (error) {
    return NextResponse.json({ success: false, message: error.message }, { status: 500 });
  }
}
