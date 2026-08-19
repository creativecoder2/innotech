import { NextResponse } from 'next/server';
import connectToDatabase from '@/lib/db';
import SiteConfig from '@/models/SiteConfig';
import { fallbackSiteConfig } from '@/lib/data';
import { getLocalStore, saveLocalStore } from '@/lib/storage';

export async function GET() {
  try {
    // Always serve from local store first — instant response, no DB wait
    const local = getLocalStore();
    const data = local?.config || fallbackSiteConfig;

    // Background sync from MongoDB (non-blocking)
    connectToDatabase().then(async (conn) => {
      if (!conn) return;
      try {
        const config = await SiteConfig.findOne().sort({ createdAt: -1 }).lean();
        if (config) saveLocalStore({ config });
      } catch (_) {}
    }).catch(() => {});

    return NextResponse.json({ success: true, source: 'local-store', data });
  } catch (error) {
    return NextResponse.json({ success: true, source: 'fallback', data: fallbackSiteConfig });
  }
}

export async function POST(req) {
  try {
    const body = await req.json();

    // 1. Always save to local persistent JSON store for instant 100% guarantee
    const local = getLocalStore();
    const currentBlogPage = local?.blogPage || fallbackBlogPage;
    const updatedBlogPage = body.blogSection?.items
      ? {
          ...currentBlogPage,
          items: body.blogSection.items,
        }
      : currentBlogPage;

    saveLocalStore({
      config: body,
      gallery: body.gallerySection?.items,
      brands: body.brandsSection?.items,
      team: body.teamSection?.members,
      testimonials: body.testimonialSection?.items,
      blogs: body.blogSection?.items,
      services: body.services,
      blogPage: updatedBlogPage,
    });

    // 2. Save to MongoDB if available
    const conn = await connectToDatabase();
    if (conn) {
      let config = await SiteConfig.findOne();
      if (config) {
        config.set(body);
        await config.save();
      } else {
        config = await SiteConfig.create(body);
      }
      return NextResponse.json({
        success: true,
        message: 'Home page configuration saved to database & live site!',
        data: config,
      });
    }

    return NextResponse.json({
      success: true,
      message: 'Home page configuration saved & live on website!',
      data: body,
    });
  } catch (error) {
    return NextResponse.json(
      { success: false, message: error.message || 'Error updating site config' },
      { status: 500 }
    );
  }
}
