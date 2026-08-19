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

    // Compute from local store — instant, no DB wait
    const unreadInquiries = inquiriesList.filter(
      (i) => i.status === 'unread' || (!i.status && i.status !== 'read' && i.status !== 'replied' && i.status !== 'archived')
    ).length;
    const pendingComments = commentsList.filter((c) => c.status === 'pending').length;
    const newSubscribers = subscribersList.filter((s) => s.viewed !== true && s.read !== true && s.isNew !== false).length;
    const unreadChats = chatSessionsList.reduce((acc, s) => acc + (s.unreadAdminCount || 0), 0);

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
