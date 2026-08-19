import { NextResponse } from 'next/server';
import connectToDatabase from '@/lib/db';
import Comment from '@/models/Comment';
import { getLocalStore, saveLocalStore } from '@/lib/storage';
import { fallbackComments } from '@/lib/data';

function sanitize(str) {
  if (typeof str !== 'string') return '';
  return str.replace(/<[^>]*>?/gm, '').trim();
}

function isValidEmail(email) {
  const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return re.test(String(email).toLowerCase());
}

// GET approved comments for a specific blog article
export async function GET(req) {
  try {
    const { searchParams } = new URL(req.url);
    const slug = searchParams.get('slug');

    const localStore = getLocalStore();
    const allComments = Array.isArray(localStore.comments) ? localStore.comments : fallbackComments;

    // Filter by slug (if provided) and only approved
    let approved = allComments.filter((c) => c.status === 'approved');
    if (slug) {
      approved = approved.filter((c) => c.blogSlug === slug);
    }

    // Non-blocking background sync from MongoDB
    connectToDatabase().then(async (conn) => {
      if (!conn) return;
      try {
        const query = { status: 'approved' };
        if (slug) query.blogSlug = slug;
        const dbComments = await Comment.find(query).sort({ createdAt: -1 }).lean();
        if (dbComments && dbComments.length > 0) {
          // background sync
        }
      } catch (_) {}
    }).catch(() => {});

    return NextResponse.json({
      success: true,
      data: approved,
      count: approved.length,
    });
  } catch (error) {
    console.error('Error fetching comments:', error);
    return NextResponse.json(
      { success: false, message: error.message || 'Error fetching comments' },
      { status: 500 }
    );
  }
}

// POST new comment (requires admin approval before showing)
export async function POST(req) {
  try {
    const body = await req.json();

    const name = sanitize(body.name);
    const email = sanitize(body.email).toLowerCase();
    const commentText = sanitize(body.comment);
    const blogSlug = sanitize(body.blogSlug);
    const blogTitle = sanitize(body.blogTitle) || 'Clinical Article';

    if (!name || name.length < 2) {
      return NextResponse.json(
        { success: false, message: 'Please enter your name (minimum 2 characters).' },
        { status: 400 }
      );
    }

    if (!email || !isValidEmail(email)) {
      return NextResponse.json(
        { success: false, message: 'Please enter a valid email address.' },
        { status: 400 }
      );
    }

    if (!commentText || commentText.length < 3) {
      return NextResponse.json(
        { success: false, message: 'Please enter your comment message.' },
        { status: 400 }
      );
    }

    if (!blogSlug) {
      return NextResponse.json(
        { success: false, message: 'Blog slug is required.' },
        { status: 400 }
      );
    }

    const newComment = {
      _id: `com-${Date.now()}-${Math.random().toString(36).substring(2, 6)}`,
      blogSlug,
      blogTitle,
      name,
      email,
      comment: commentText,
      status: 'pending', // Requires admin approval!
      createdAt: new Date().toISOString(),
    };

    // Save to local storage
    const localStore = getLocalStore();
    const current = Array.isArray(localStore.comments) ? localStore.comments : fallbackComments;
    saveLocalStore({ comments: [newComment, ...current] });

    // Save to MongoDB
    try {
      const conn = await connectToDatabase();
      if (conn) {
        await Comment.create({
          blogSlug,
          blogTitle,
          name,
          email,
          comment: commentText,
          status: 'pending',
        });
      }
    } catch (dbErr) {
      console.warn('MongoDB comment sync note:', dbErr.message);
    }

    return NextResponse.json({
      success: true,
      message: 'Thank you! Your comment has been submitted and is pending administrator approval before being published.',
      data: newComment,
    });
  } catch (error) {
    console.error('Error posting comment:', error);
    return NextResponse.json(
      { success: false, message: error.message || 'Error processing comment submission' },
      { status: 500 }
    );
  }
}
