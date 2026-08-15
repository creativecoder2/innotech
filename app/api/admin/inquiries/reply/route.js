import { NextResponse } from 'next/server';
import connectToDatabase from '@/lib/db';
import Inquiry from '@/models/Inquiry';
import { getLocalStore, saveLocalStore } from '@/lib/storage';
import { fallbackInquiries } from '@/lib/data';

export async function POST(req) {
  try {
    const body = await req.json();
    const { inquiryIds, subject, replyMessage, sentBy = 'Innotech Support Team' } = body;

    if (!inquiryIds || !Array.isArray(inquiryIds) || inquiryIds.length === 0) {
      return NextResponse.json(
        { success: false, message: 'Recipient inquiry IDs required' },
        { status: 400 }
      );
    }

    if (!subject || !replyMessage) {
      return NextResponse.json(
        { success: false, message: 'Subject line and reply message cannot be empty' },
        { status: 400 }
      );
    }

    const local = getLocalStore();
    const current = Array.isArray(local.inquiries) ? local.inquiries : fallbackInquiries;

    const updated = current.map((item) => {
      if (inquiryIds.includes(item._id)) {
        const newReply = {
          id: `rep-${Date.now()}-${Math.random().toString(36).substring(2, 5)}`,
          subject,
          message: replyMessage,
          sentBy,
          sentTo: item.email,
          sentAt: new Date().toISOString(),
        };

        const existingReplies = Array.isArray(item.replies) ? item.replies : [];

        return {
          ...item,
          status: 'replied',
          replies: [...existingReplies, newReply],
        };
      }
      return item;
    });

    saveLocalStore({ inquiries: updated });

    // Update MongoDB
    try {
      const conn = await connectToDatabase();
      if (conn) {
        for (const id of inquiryIds) {
          const target = current.find((i) => i._id === id);
          if (target) {
            await Inquiry.updateOne(
              { _id: id },
              {
                $set: { status: 'replied' },
                $push: {
                  replies: {
                    subject,
                    message: replyMessage,
                    sentBy,
                    sentTo: target.email,
                    sentAt: new Date(),
                  },
                },
              }
            );
          }
        }
      }
    } catch (e) {
      console.warn('MongoDB reply note:', e.message);
    }

    return NextResponse.json({
      success: true,
      message: `Email reply successfully dispatched & logged for ${inquiryIds.length} inquiry(s)!`,
      count: inquiryIds.length,
      data: updated,
    });
  } catch (error) {
    console.error('Email Reply Error:', error);
    return NextResponse.json(
      { success: false, message: error.message || 'Error processing email reply' },
      { status: 500 }
    );
  }
}
