import { NextResponse } from 'next/server';
import connectToDatabase from '@/lib/db';
import Service from '@/models/Service';
import { fallbackServices } from '@/lib/data';

export async function GET() {
  try {
    const conn = await connectToDatabase();
    if (conn) {
      const services = await Service.find({ isActive: true }).sort({ order: 1 });
      if (services && services.length > 0) {
        return NextResponse.json({ success: true, source: 'mongodb', data: services });
      }
    }
    return NextResponse.json({ success: true, source: 'fallback', data: fallbackServices });
  } catch (error) {
    return NextResponse.json({ success: true, source: 'fallback', data: fallbackServices });
  }
}
