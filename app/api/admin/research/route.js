import { NextResponse } from 'next/server';
import { fallbackResearchPage } from '@/lib/data';
import { getLocalStore, saveLocalStore } from '@/lib/storage';

export async function GET() {
  try {
    const local = getLocalStore();
    const data = local?.researchPage || fallbackResearchPage;
    return NextResponse.json({ success: true, data });
  } catch (error) {
    return NextResponse.json({ success: true, data: fallbackResearchPage });
  }
}

export async function POST(req) {
  try {
    const body = await req.json();
    saveLocalStore({ researchPage: body });

    return NextResponse.json({
      success: true,
      message: 'Research page projects and banner updated live!',
      data: body,
    });
  } catch (error) {
    return NextResponse.json(
      { success: false, message: error.message || 'Error updating Research page' },
      { status: 500 }
    );
  }
}
