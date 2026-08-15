import { NextResponse } from 'next/server';
import connectToDatabase from '@/lib/db';
import Blog from '@/models/Blog';
import { fallbackBlogList } from '@/lib/data';
import { getLocalStore } from '@/lib/storage';

export async function GET() {
  try {
    const local = getLocalStore();
    if (local?.blogPage?.items?.length) {
      return NextResponse.json({ success: true, source: 'local', data: local.blogPage.items });
    }

    const conn = await connectToDatabase();
    if (conn) {
      const blogs = await Blog.find().sort({ createdAt: -1 });
      if (blogs && blogs.length > 0) {
        return NextResponse.json({ success: true, source: 'mongodb', data: blogs });
      }
    }
    return NextResponse.json({ success: true, source: 'fallback', data: fallbackBlogList });
  } catch (error) {
    return NextResponse.json({ success: true, source: 'fallback', data: fallbackBlogList });
  }
}
