import { NextResponse } from 'next/server';
import connectToDatabase from '@/lib/db';
import Comment from '@/models/Comment';
import { getLocalStore, saveLocalStore } from '@/lib/storage';
import { fallbackComments } from '@/lib/data';

// GET all comments for admin moderation
export async function GET() {
  try {
    const localStore = getLocalStore();
    let comments = Array.isArray(localStore.comments) && localStore.comments.length > 0
      ? localStore.comments
      : fallbackComments;

    // Try MongoDB sync
    try {
      const conn = await connectToDatabase();
      if (conn) {
        const dbComments = await Comment.find().sort({ createdAt: -1 }).lean();
        if (dbComments && dbComments.length > 0) {
          comments = dbComments.map((c) => ({
            ...c,
            _id: c._id.toString(),
            createdAt: c.createdAt ? new Date(c.createdAt).toISOString() : new Date().toISOString(),
          }));
        }
      }
    } catch (e) {}

    const counts = {
      total: comments.length,
      pending: comments.filter((c) => c.status === 'pending').length,
      approved: comments.filter((c) => c.status === 'approved').length,
      rejected: comments.filter((c) => c.status === 'rejected').length,
    };

    return NextResponse.json({
      success: true,
      data: comments,
      counts,
    });
  } catch (error) {
    console.error('Admin comments GET error:', error);
    return NextResponse.json(
      { success: false, message: error.message || 'Error fetching comments' },
      { status: 500 }
    );
  }
}

// PATCH comment moderation status (e.g. approved / rejected / pending)
export async function PATCH(req) {
  try {
    const body = await req.json();
    const { id, status } = body;

    if (!id || !status || !['approved', 'rejected', 'pending'].includes(status)) {
      return NextResponse.json(
        { success: false, message: 'Comment ID and valid status (approved/rejected/pending) are required' },
        { status: 400 }
      );
    }

    const localStore = getLocalStore();
    const current = Array.isArray(localStore.comments) ? localStore.comments : fallbackComments;

    const updated = current.map((c) => (c._id === id ? { ...c, status } : c));
    saveLocalStore({ comments: updated });

    // Update in MongoDB
    try {
      const conn = await connectToDatabase();
      if (conn) {
        await Comment.updateOne({ _id: id }, { $set: { status } });
      }
    } catch (e) {}

    return NextResponse.json({
      success: true,
      message: `Comment status updated to "${status}"!`,
      data: updated,
    });
  } catch (error) {
    console.error('Admin comments PATCH error:', error);
    return NextResponse.json(
      { success: false, message: error.message || 'Error updating comment status' },
      { status: 500 }
    );
  }
}

// DELETE comment
export async function DELETE(req) {
  try {
    const body = await req.json();
    const { id } = body;

    if (!id) {
      return NextResponse.json(
        { success: false, message: 'Comment ID is required for deletion' },
        { status: 400 }
      );
    }

    const localStore = getLocalStore();
    const current = Array.isArray(localStore.comments) ? localStore.comments : fallbackComments;

    const remaining = current.filter((c) => c._id !== id);
    saveLocalStore({ comments: remaining });

    // Delete in MongoDB
    try {
      const conn = await connectToDatabase();
      if (conn) {
        await Comment.deleteOne({ _id: id });
      }
    } catch (e) {}

    return NextResponse.json({
      success: true,
      message: 'Comment deleted successfully!',
      data: remaining,
    });
  } catch (error) {
    console.error('Admin comments DELETE error:', error);
    return NextResponse.json(
      { success: false, message: error.message || 'Error deleting comment' },
      { status: 500 }
    );
  }
}
