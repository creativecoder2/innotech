import { NextResponse } from 'next/server';
import connectToDatabase from '@/lib/db';
import Inquiry from '@/models/Inquiry';
import { getLocalStore, saveLocalStore } from '@/lib/storage';
import { fallbackInquiries } from '@/lib/data';

export async function GET() {
  try {
    const local = getLocalStore();
    let inquiries = Array.isArray(local.inquiries) && local.inquiries.length > 0
      ? local.inquiries
      : fallbackInquiries;

    // Optional sync from MongoDB
    try {
      const conn = await connectToDatabase();
      if (conn) {
        const dbItems = await Inquiry.find().sort({ createdAt: -1 }).lean();
        if (dbItems && dbItems.length > 0) {
          inquiries = dbItems.map((item) => ({
            ...item,
            _id: item._id.toString(),
            createdAt: item.createdAt ? new Date(item.createdAt).toISOString() : new Date().toISOString(),
          }));
        }
      }
    } catch (e) {
      // Local fallback used
    }

    const counts = {
      total: inquiries.length,
      unread: inquiries.filter((i) => i.status === 'unread').length,
      replied: inquiries.filter((i) => i.status === 'replied').length,
      archived: inquiries.filter((i) => i.status === 'archived').length,
    };

    return NextResponse.json({
      success: true,
      data: inquiries,
      counts,
    });
  } catch (error) {
    return NextResponse.json(
      { success: false, message: error.message || 'Error fetching inquiries' },
      { status: 500 }
    );
  }
}

export async function PATCH(req) {
  try {
    const body = await req.json();
    const { ids, status } = body;

    if (!ids || !Array.isArray(ids) || ids.length === 0 || !status) {
      return NextResponse.json(
        { success: false, message: 'Invalid payload: ids array and status are required' },
        { status: 400 }
      );
    }

    const local = getLocalStore();
    const current = Array.isArray(local.inquiries) ? local.inquiries : fallbackInquiries;

    const updated = current.map((item) => {
      if (ids.includes(item._id)) {
        return { ...item, status };
      }
      return item;
    });

    saveLocalStore({ inquiries: updated });

    // Update MongoDB
    try {
      const conn = await connectToDatabase();
      if (conn) {
        await Inquiry.updateMany({ _id: { $in: ids } }, { $set: { status } });
      }
    } catch (e) {}

    return NextResponse.json({
      success: true,
      message: `Updated status to "${status}" for ${ids.length} inquiry(s).`,
      data: updated,
    });
  } catch (error) {
    return NextResponse.json(
      { success: false, message: error.message || 'Error updating status' },
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
        { success: false, message: 'Array of inquiry IDs required for deletion' },
        { status: 400 }
      );
    }

    const local = getLocalStore();
    const current = Array.isArray(local.inquiries) ? local.inquiries : fallbackInquiries;

    const remaining = current.filter((item) => !ids.includes(item._id));
    saveLocalStore({ inquiries: remaining });

    // Delete in MongoDB
    try {
      const conn = await connectToDatabase();
      if (conn) {
        await Inquiry.deleteMany({ _id: { $in: ids } });
      }
    } catch (e) {}

    return NextResponse.json({
      success: true,
      message: `Successfully deleted ${ids.length} inquiry(s).`,
      data: remaining,
    });
  } catch (error) {
    return NextResponse.json(
      { success: false, message: error.message || 'Error deleting inquiries' },
      { status: 500 }
    );
  }
}
