import mongoose from 'mongoose';

const TeamSchema = new mongoose.Schema(
  {
    name: {
      type: String,
      required: true,
      trim: true,
    },
    position: {
      type: String,
      required: true,
      trim: true,
    },
    bio: {
      type: String,
      required: true,
    },
    image: {
      type: String,
      required: true,
    },
    socialLinks: {
      youtube: { type: String, default: '#' },
      twitter: { type: String, default: '#' },
      facebook: { type: String, default: '#' },
      skype: { type: String, default: '#' },
    },
    order: {
      type: Number,
      default: 0,
    },
  },
  { timestamps: true }
);

export default mongoose.models.Team || mongoose.model('Team', TeamSchema);
