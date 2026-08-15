import { NextResponse } from 'next/server';
import connectToDatabase from '@/lib/db';
import Subscriber from '@/models/Subscriber';
import { getLocalStore, saveLocalStore } from '@/lib/storage';
import { fallbackSubscribers } from '@/lib/data';

function sanitize(str) {
  if (typeof str !== 'string') return '';
  return str.replace(/<[^>]*>?/gm, '').trim();
}

function isValidEmail(email) {
  const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return re.test(String(email).toLowerCase());
}

export async function POST(req) {
  try {
    const body = await req.json();
    const email = sanitize(body.email).toLowerCase();
    const source = sanitize(body.source) || 'Footer Newsletter';

    if (!email || !isValidEmail(email)) {
      return NextResponse.json(
        { success: false, message: 'Please provide a valid email address.' },
        { status: 400 }
      );
    }

    const localStore = getLocalStore();
    const current = Array.isArray(localStore.subscribers) ? localStore.subscribers : fallbackSubscribers;

    // Check duplicate in local store
    const exists = current.some((s) => s.email.toLowerCase() === email);
    if (exists) {
      return NextResponse.json(
        {
          success: false,
          alreadySubscribed: true,
          message:
            'This email is already subscribed to our newsletter. If you have an equipment inquiry or require assistance, please submit your request through our Contact Us page.',
        },
        { status: 409 }
      );
    }

    // Check duplicate in MongoDB
    try {
      const conn = await connectToDatabase();
      if (conn) {
        const dbExists = await Subscriber.findOne({ email });
        if (dbExists) {
          return NextResponse.json(
            {
              success: false,
              alreadySubscribed: true,
              message:
                'This email is already subscribed to our newsletter. If you have an equipment inquiry or require assistance, please submit your request through our Contact Us page.',
            },
            { status: 409 }
          );
        }
      }
    } catch (e) {}

    const newSub = {
      _id: `sub-${Date.now()}-${Math.random().toString(36).substring(2, 6)}`,
      email,
      source,
      status: 'active',
      createdAt: new Date().toISOString(),
    };

    saveLocalStore({ subscribers: [newSub, ...current] });

    // Sync to MongoDB
    try {
      const conn = await connectToDatabase();
      if (conn) {
        await Subscriber.findOneAndUpdate(
          { email },
          { email, source, status: 'active' },
          { upsert: true, new: true }
        );
      }
    } catch (dbErr) {
      console.warn('MongoDB subscriber sync note:', dbErr.message);
    }

    return NextResponse.json({
      success: true,
      message: 'Thank you for subscribing! You will receive our latest biomedical equipment releases and news.',
      data: newSub,
    });
  } catch (error) {
    console.error('Newsletter Subscribe Error:', error);
    return NextResponse.json(
      { success: false, message: error.message || 'Error processing newsletter subscription' },
      { status: 500 }
    );
  }
}
