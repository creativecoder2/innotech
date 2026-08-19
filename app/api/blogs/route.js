import { NextResponse } from 'next/server';
import connectToDatabase from '@/lib/db';
import Blog from '@/models/Blog';
import { fallbackBlogList } from '@/lib/data';
import { getLocalStore } from '@/lib/storage';

export async function GET() {
  try {
    const local = getLocalStore();
    const data = local?.blogPage?.items?.length ? local.blogPage.items : fallbackBlogList;
    return NextResponse.json({ success: true, source: 'local', data });
  } catch (error) {
    return NextResponse.json({ success: true, source: 'fallback', data: fallbackBlogList });
  }
}
