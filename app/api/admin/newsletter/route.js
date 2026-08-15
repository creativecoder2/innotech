import { NextResponse } from 'next/server';
import connectToDatabase from '@/lib/db';
import Subscriber from '@/models/Subscriber';
import { getLocalStore, saveLocalStore } from '@/lib/storage';
import { fallbackSubscribers } from '@/lib/data';

export async function GET() {
  try {
    const localStore = getLocalStore();
    let subscribers = Array.isArray(localStore.subscribers) && localStore.subscribers.length > 0
      ? localStore.subscribers
      : fallbackSubscribers;

    // Try MongoDB sync
    try {
      const conn = await connectToDatabase();
      if (conn) {
        const dbSubs = await Subscriber.find().sort({ createdAt: -1 }).lean();
        if (dbSubs && dbSubs.length > 0) {
          subscribers = dbSubs.map((s) => ({
            ...s,
            _id: s._id.toString(),
            createdAt: s.createdAt ? new Date(s.createdAt).toISOString() : new Date().toISOString(),
          }));
        }
      }
    } catch (e) {}

    const counts = {
      total: subscribers.length,
      active: subscribers.filter((s) => s.status === 'active').length,
    };

    return NextResponse.json({
      success: true,
      data: subscribers,
      counts,
    });
  } catch (error) {
    console.error('Admin newsletter GET error:', error);
    return NextResponse.json(
      { success: false, message: error.message || 'Error fetching subscribers' },
      { status: 500 }
    );
  }
}

export async function DELETE(req) {
  try {
    const body = await req.json();
    const { ids } = body;

    if (!ids || !Array.isArray(ids) || ids.length === 0) {
      return NextResponse.json(
        { success: false, message: 'Subscriber IDs array required' },
        { status: 400 }
      );
    }

    const localStore = getLocalStore();
    const current = Array.isArray(localStore.subscribers) ? localStore.subscribers : fallbackSubscribers;

    const remaining = current.filter((s) => !ids.includes(s._id));
    saveLocalStore({ subscribers: remaining });

    // Delete in MongoDB
    try {
      const conn = await connectToDatabase();
      if (conn) {
        await Subscriber.deleteMany({ _id: { $in: ids } });
      }
    } catch (e) {}

    return NextResponse.json({
      success: true,
      message: `Deleted ${ids.length} subscriber(s) successfully!`,
      data: remaining,
    });
  } catch (error) {
    console.error('Admin newsletter DELETE error:', error);
    return NextResponse.json(
      { success: false, message: error.message || 'Error deleting subscribers' },
      { status: 500 }
    );
  }
}
