import mongoose from 'mongoose';

const SubscriberSchema = new mongoose.Schema(
  {
    email: {
      type: String,
      required: [true, 'Email address is required'],
      unique: true,
      trim: true,
      lowercase: true,
    },
    source: {
      type: String,
      default: 'Footer Newsletter',
    },
    status: {
      type: String,
      enum: ['active', 'unsubscribed'],
      default: 'active',
    },
  },
  { timestamps: true }
);

export default mongoose.models.Subscriber || mongoose.model('Subscriber', SubscriberSchema);
