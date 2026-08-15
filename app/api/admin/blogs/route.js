import { NextResponse } from 'next/server';
import { fallbackBlogPage, fallbackSiteConfig } from '@/lib/data';
import { getLocalStore, saveLocalStore } from '@/lib/storage';
import connectToDatabase from '@/lib/db';
import Blog from '@/models/Blog';
import SiteConfig from '@/models/SiteConfig';

export async function GET() {
  try {
    const local = getLocalStore();
    const data = local?.blogPage || fallbackBlogPage;
    return NextResponse.json({ success: true, data });
  } catch (error) {
    return NextResponse.json({ success: true, data: fallbackBlogPage });
  }
}

export async function POST(req) {
  try {
    const body = await req.json();
    const local = getLocalStore();
    const currentConfig = local?.config || fallbackSiteConfig;

    // 1. Keep siteConfig.blogSection in sync
    const updatedConfig = {
      ...currentConfig,
      blogSection: {
        ...(currentConfig.blogSection || {}),
        items: body.items || [],
      },
    };

    // 2. Save to local storage
    saveLocalStore({
      blogPage: body,
      blogs: body.items || [],
      config: updatedConfig,
    });

    // 3. Save to MongoDB if connected
    try {
      const conn = await connectToDatabase();
      if (conn) {
        let siteConf = await SiteConfig.findOne();
        if (siteConf) {
          siteConf.set({
            ...siteConf.toObject(),
            blogSection: {
              ...(siteConf.blogSection || {}),
              items: body.items || [],
            },
          });
          await siteConf.save();
        }
      }
    } catch (dbErr) {
      console.warn('MongoDB sync notice in /api/admin/blogs:', dbErr.message);
    }

    return NextResponse.json({
      success: true,
      message: 'Blog & Articles updated live across admin and home page!',
      data: body,
    });
  } catch (error) {
    return NextResponse.json(
      { success: false, message: error.message || 'Error updating Blog page' },
      { status: 500 }
    );
  }
}
