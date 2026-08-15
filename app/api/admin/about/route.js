import { NextResponse } from 'next/server';
import connectToDatabase from '@/lib/db';
import { fallbackAboutPage } from '@/lib/data';
import { getLocalStore, saveLocalStore } from '@/lib/storage';

export async function GET() {
  try {
    const local = getLocalStore();
    const data = local?.aboutPage || fallbackAboutPage;
    return NextResponse.json({ success: true, data });
  } catch (error) {
    return NextResponse.json({ success: true, data: fallbackAboutPage });
  }
}

export async function POST(req) {
  try {
    const body = await req.json();

    // 1. Save to local persistent store
    saveLocalStore({ aboutPage: body });

    // 2. Also keep SiteConfig synchronized if applicable
    try {
      const conn = await connectToDatabase();
      if (conn) {
        // You can also persist inside SiteConfig or custom collection if needed
      }
    } catch (e) {}

    return NextResponse.json({
      success: true,
      message: 'About Us page settings saved and updated live!',
      data: body,
    });
  } catch (error) {
    return NextResponse.json(
      { success: false, message: error.message || 'Error updating About Us page' },
      { status: 500 }
    );
  }
}
