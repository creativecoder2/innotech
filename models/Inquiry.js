import mongoose from 'mongoose';

const ReplySchema = new mongoose.Schema({
  subject: { type: String, required: true },
  message: { type: String, required: true },
  sentBy: { type: String, default: 'Innotech Support' },
  sentTo: { type: String, required: true },
  sentAt: { type: Date, default: Date.now },
});

const InquirySchema = new mongoose.Schema(
  {
    source: {
      type: String,
      default: 'Website Contact',
      enum: ['Contact Page', 'Home Page Consultation', 'Website Contact', 'General Inquiry'],
    },
    name: {
      type: String,
      required: [true, 'Name is required'],
      trim: true,
      maxlength: 120,
    },
    email: {
      type: String,
      required: [true, 'Email is required'],
      trim: true,
      lowercase: true,
    },
    phone: {
      type: String,
      required: [true, 'Phone number is required'],
      trim: true,
    },
    subject: {
      type: String,
      default: 'General Inquiry',
      trim: true,
    },
    message: {
      type: String,
      required: [true, 'Message content is required'],
      trim: true,
    },
    status: {
      type: String,
      enum: ['unread', 'read', 'replied', 'archived'],
      default: 'unread',
    },
    replies: [ReplySchema],
    ipAddress: { type: String },
    userAgent: { type: String },
  },
  {
    timestamps: true,
  }
);

export default mongoose.models.Inquiry || mongoose.model('Inquiry', InquirySchema);
