import mongoose from 'mongoose';

const CommentSchema = new mongoose.Schema(
  {
    blogSlug: {
      type: String,
      required: true,
      index: true,
    },
    blogTitle: {
      type: String,
      default: '',
    },
    name: {
      type: String,
      required: [true, 'Author name is required'],
      trim: true,
    },
    email: {
      type: String,
      required: [true, 'Author email is required'],
      trim: true,
      lowercase: true,
    },
    comment: {
      type: String,
      required: [true, 'Comment text is required'],
    },
    status: {
      type: String,
      enum: ['pending', 'approved', 'rejected'],
      default: 'pending',
      index: true,
    },
  },
  { timestamps: true }
);

export default mongoose.models.Comment || mongoose.model('Comment', CommentSchema);
