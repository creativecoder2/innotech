import { NextResponse } from 'next/server';
import connectToDatabase from '@/lib/db';
import ChatSession from '@/models/ChatSession';
import { getLocalStore, saveLocalStore } from '@/lib/storage';

export const dynamic = 'force-dynamic';
export const revalidate = 0;

export async function POST(req) {
  try {
    const body = await req.json();
    const { sessionId, deleteCompletely = false } = body;

    if (!sessionId) {
      return NextResponse.json({ success: false, message: 'Session ID is required' }, { status: 400 });
    }

    const local = getLocalStore();
    const sessions = Array.isArray(local.chatSessions) ? [...local.chatSessions] : [];

    if (deleteCompletely) {
      const filtered = sessions.filter((s) => s.sessionId !== sessionId);
      saveLocalStore({ chatSessions: filtered });

      try {
        const conn = await connectToDatabase();
        if (conn) {
          await ChatSession.deleteOne({ sessionId });
        }
      } catch (e) {}
    } else {
      // Mark as closed/reset
      const sessionIndex = sessions.findIndex((s) => s.sessionId === sessionId);
      if (sessionIndex >= 0) {
        sessions[sessionIndex] = {
          ...sessions[sessionIndex],
          status: 'closed',
          unreadAdminCount: 0,
          unreadUserCount: 0,
          updatedAt: new Date(),
        };
        saveLocalStore({ chatSessions: sessions });
      }

      try {
        const conn = await connectToDatabase();
        if (conn) {
          await ChatSession.updateOne(
            { sessionId },
            { $set: { status: 'closed', unreadAdminCount: 0, unreadUserCount: 0 } }
          );
        }
      } catch (e) {}
    }

    return NextResponse.json({
      success: true,
      message: 'Chat session reset successfully',
    });
  } catch (error) {
    console.error('Error resetting chat:', error);
    return NextResponse.json(
      { success: false, message: error.message || 'Error resetting chat' },
      { status: 500 }
    );
  }
}
