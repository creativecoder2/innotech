import { NextResponse } from 'next/server';
import connectToDatabase from '@/lib/db';
import { fallbackContactPage } from '@/lib/data';
import { getLocalStore, saveLocalStore } from '@/lib/storage';

export async function GET() {
  try {
    const local = getLocalStore();
    const data = local?.contactPage || fallbackContactPage;
    return NextResponse.json({ success: true, data });
  } catch (error) {
    return NextResponse.json({ success: true, data: fallbackContactPage });
  }
}

export async function POST(req) {
  try {
    const body = await req.json();

    // 1. Save to local persistent store
    saveLocalStore({ contactPage: body });

    return NextResponse.json({
      success: true,
      message: 'Contact Us page settings saved and updated live!',
      data: body,
    });
  } catch (error) {
    return NextResponse.json(
      { success: false, message: error.message || 'Error updating Contact Us page' },
      { status: 500 }
    );
  }
}
