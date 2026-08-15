import mongoose from 'mongoose';

const ChatMessageSchema = new mongoose.Schema(
  {
    id: { type: String, required: true },
    sender: {
      type: String,
      enum: ['user', 'admin', 'system'],
      default: 'user',
    },
    senderName: { type: String, default: 'Visitor' },
    text: { type: String, required: true, trim: true },
    timestamp: { type: Date, default: Date.now },
    read: { type: Boolean, default: false },
  },
  { _id: false }
);

const ChatSessionSchema = new mongoose.Schema(
  {
    sessionId: {
      type: String,
      required: true,
      unique: true,
      index: true,
    },
    userName: {
      type: String,
      required: [true, 'User full name is required'],
      trim: true,
      maxlength: 120,
    },
    userPhone: {
      type: String,
      required: [true, 'Phone number is required'],
      trim: true,
      maxlength: 30,
    },
    userCity: {
      type: String,
      required: [true, 'City is required'],
      trim: true,
      maxlength: 80,
    },
    status: {
      type: String,
      enum: ['active', 'resolved', 'closed'],
      default: 'active',
      index: true,
    },
    unreadAdminCount: {
      type: Number,
      default: 0,
    },
    unreadUserCount: {
      type: Number,
      default: 0,
    },
    lastMessage: {
      type: String,
      default: '',
    },
    lastMessageAt: {
      type: Date,
      default: Date.now,
      index: true,
    },
    messages: [ChatMessageSchema],
    autoReplySent: {
      type: Boolean,
      default: false,
    },
    ipAddress: { type: String },
    userAgent: { type: String },
  },
  {
    timestamps: true,
  }
);

export default mongoose.models.ChatSession || mongoose.model('ChatSession', ChatSessionSchema);
