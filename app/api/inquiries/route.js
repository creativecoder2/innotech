import { NextResponse } from 'next/server';
import connectToDatabase from '@/lib/db';
import Inquiry from '@/models/Inquiry';
import { getLocalStore, saveLocalStore } from '@/lib/storage';

function isValidEmail(email) {
  const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return re.test(String(email).toLowerCase());
}

function sanitize(str) {
  if (typeof str !== 'string') return '';
  return str.replace(/<[^>]*>?/gm, '').trim();
}

export async function POST(req) {
  try {
    const body = await req.json();

    // 1. Anti-spam Honeypot Check
    if (body._honeypot || body._gotcha) {
      // Bot detected - return silent fake success
      return NextResponse.json({
        success: true,
        message: 'Inquiry received successfully!',
      });
    }

    // 2. Data Sanitization
    const name = sanitize(body.name);
    const email = sanitize(body.email).toLowerCase();
    const phone = sanitize(body.phone);
    const subject = sanitize(body.subject) || 'Medical Equipment Inquiry';
    const message = sanitize(body.message);
    const source = sanitize(body.source) || 'Website Contact';

    // 3. Validation Rules
    if (!name || name.length < 2) {
      return NextResponse.json(
        { success: false, message: 'Please enter a valid full name (minimum 2 characters).' },
        { status: 400 }
      );
    }

    if (!email || !isValidEmail(email)) {
      return NextResponse.json(
        { success: false, message: 'Please enter a valid email address (e.g. name@domain.com).' },
        { status: 400 }
      );
    }

    if (!phone || phone.replace(/\D/g, '').length < 7) {
      return NextResponse.json(
        { success: false, message: 'Please enter a valid phone/mobile number (at least 7 digits).' },
        { status: 400 }
      );
    }

    if (!message || message.length < 5) {
      return NextResponse.json(
        { success: false, message: 'Please provide a detailed inquiry message (minimum 5 characters).' },
        { status: 400 }
      );
    }

    const ipAddress = req.headers.get('x-forwarded-for') || req.headers.get('x-real-ip') || 'Localhost';
    const userAgent = req.headers.get('user-agent') || 'Browser';

    const newInquiry = {
      _id: `inq-${Date.now()}-${Math.random().toString(36).substring(2, 7)}`,
      source,
      name,
      email,
      phone,
      subject,
      message,
      status: 'unread',
      replies: [],
      createdAt: new Date().toISOString(),
      ipAddress,
      userAgent,
    };

    // 4. Save to Local JSON Storage
    const localStore = getLocalStore();
    const existing = Array.isArray(localStore.inquiries) ? localStore.inquiries : [];
    const updatedInquiries = [newInquiry, ...existing];
    saveLocalStore({ inquiries: updatedInquiries });

    // 5. Save to MongoDB if connected
    try {
      const conn = await connectToDatabase();
      if (conn) {
        await Inquiry.create({
          source,
          name,
          email,
          phone,
          subject,
          message,
          status: 'unread',
          ipAddress,
          userAgent,
        });
      }
    } catch (dbErr) {
      console.warn('MongoDB Inquiry sync note:', dbErr.message);
    }

    return NextResponse.json({
      success: true,
      message: 'Thank you! Your inquiry has been securely submitted. Our biomedical engineering team will contact you shortly.',
      data: {
        id: newInquiry._id,
        name: newInquiry.name,
        email: newInquiry.email,
      },
    });
  } catch (error) {
    console.error('Inquiry Submission Error:', error);
    return NextResponse.json(
      { success: false, message: 'Server error processing inquiry. Please try again later.' },
      { status: 500 }
    );
  }
}
