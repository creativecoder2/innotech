import { NextResponse } from 'next/server';
import connectToDatabase from '@/lib/db';
import Service from '@/models/Service';
import { fallbackServices } from '@/lib/data';

import { getLocalStore } from '@/lib/storage';

export async function GET() {
  try {
    const local = getLocalStore();
    const data = local?.services && local.services.length > 0 ? local.services : fallbackServices;
    return NextResponse.json({ success: true, source: 'local-store', data });
  } catch (error) {
    return NextResponse.json({ success: true, source: 'fallback', data: fallbackServices });
  }
}
