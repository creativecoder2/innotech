import { NextResponse } from 'next/server';
import { fallbackTermsPage, fallbackPrivacyPage } from '@/lib/data';
import { getLocalStore, saveLocalStore } from '@/lib/storage';

export async function GET() {
  try {
    const local = getLocalStore();
    return NextResponse.json({
      success: true,
      data: {
        termsPage: local?.termsPage || fallbackTermsPage,
        privacyPage: local?.privacyPage || fallbackPrivacyPage,
      },
    });
  } catch (error) {
    return NextResponse.json({
      success: true,
      data: {
        termsPage: fallbackTermsPage,
        privacyPage: fallbackPrivacyPage,
      },
    });
  }
}

export async function POST(req) {
  try {
    const body = await req.json();
    const { termsPage, privacyPage } = body;

    const updates = {};
    if (termsPage) updates.termsPage = termsPage;
    if (privacyPage) updates.privacyPage = privacyPage;

    saveLocalStore(updates);

    return NextResponse.json({
      success: true,
      message: 'Legal policy pages successfully updated and published!',
      data: updates,
    });
  } catch (error) {
    return NextResponse.json(
      { success: false, message: error.message || 'Error saving legal policies' },
      { status: 500 }
    );
  }
}
