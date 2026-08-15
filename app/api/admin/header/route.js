import { NextResponse } from 'next/server';
import { fallbackHeaderConfig } from '@/lib/data';
import { getLocalStore, saveLocalStore } from '@/lib/storage';
import connectToDatabase from '@/lib/db';
import SiteConfig from '@/models/SiteConfig';

export async function GET() {
  try {
    const conn = await connectToDatabase();
    if (conn) {
      const siteDoc = await SiteConfig.findOne().sort({ createdAt: -1 }).lean();
      if (siteDoc?.header) {
        saveLocalStore({ headerConfig: siteDoc.header });
        return NextResponse.json({ success: true, data: siteDoc.header });
      }
    }
    const local = getLocalStore();
    const data = local?.headerConfig || fallbackHeaderConfig;
    return NextResponse.json({ success: true, data });
  } catch (error) {
    const local = getLocalStore();
    const data = local?.headerConfig || fallbackHeaderConfig;
    return NextResponse.json({ success: true, data });
  }
}

export async function POST(req) {
  try {
    const body = await req.json();
    saveLocalStore({ headerConfig: body });

    try {
      const conn = await connectToDatabase();
      if (conn) {
        let siteDoc = await SiteConfig.findOne();
        if (siteDoc) {
          siteDoc.header = body;
          await siteDoc.save();
        }
      }
    } catch (dbErr) {
      console.warn('MongoDB header update notice:', dbErr.message);
    }

    return NextResponse.json({
      success: true,
      message: 'Header navigation menu updated live!',
      data: body,
    });
  } catch (error) {
    return NextResponse.json(
      { success: false, message: error.message || 'Error updating header menu' },
      { status: 500 }
    );
  }
}
