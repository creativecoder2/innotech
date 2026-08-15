import { NextResponse } from 'next/server';
import connectToDatabase from '@/lib/db';
import Appointment from '@/models/Appointment';

export async function POST(req) {
  try {
    const body = await req.json();
    const { name, email, subject, phone, message } = body;

    if (!name || !email || !phone) {
      return NextResponse.json(
        { success: false, message: 'Name, Email, and Phone number are required.' },
        { status: 400 }
      );
    }

    const conn = await connectToDatabase();
    let savedAppointment = null;

    if (conn) {
      savedAppointment = await Appointment.create({
        name,
        email,
        subject: subject || 'General Inquiry / Equipment Booking',
        phone,
        message: message || '',
      });
    }

    // Sync to inquiries local storage
    try {
      const { getLocalStore, saveLocalStore } = await import('@/lib/storage');
      const localStore = getLocalStore();
      const existing = Array.isArray(localStore.inquiries) ? localStore.inquiries : [];
      const newInq = {
        _id: `inq-${Date.now()}-${Math.random().toString(36).substring(2, 7)}`,
        source: 'Home Page Consultation',
        name,
        email,
        phone,
        subject: subject || 'General Consultation / Equipment Booking',
        message: message || 'Appointment inquiry from home page.',
        status: 'unread',
        replies: [],
        createdAt: new Date().toISOString(),
      };
      saveLocalStore({ inquiries: [newInq, ...existing] });
    } catch (storeErr) {
      console.warn('Appointment store sync note:', storeErr.message);
    }

    return NextResponse.json({
      success: true,
      message: 'Thank you! Your inquiry / appointment request has been successfully received. Our medical specialist will contact you shortly.',
      data: savedAppointment || { name, email, subject, phone, message, createdAt: new Date() },
    });
  } catch (error) {
    console.error('Error creating appointment:', error);
    return NextResponse.json(
      { success: false, message: error.message || 'Internal Server Error' },
      { status: 500 }
    );
  }
}

export async function GET() {
  try {
    const conn = await connectToDatabase();
    if (!conn) {
      return NextResponse.json({ success: true, count: 0, data: [] });
    }
    const appointments = await Appointment.find().sort({ createdAt: -1 });
    return NextResponse.json({ success: true, count: appointments.length, data: appointments });
  } catch (error) {
    return NextResponse.json({ success: false, message: error.message }, { status: 500 });
  }
}
