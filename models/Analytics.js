import mongoose from 'mongoose';

const PageViewSchema = new mongoose.Schema(
  {
    visitorId: {
      type: String,
      required: true,
      index: true,
    },
    path: {
      type: String,
      required: true,
      index: true,
    },
    pageTitle: {
      type: String,
      default: '',
    },
    duration: {
      type: Number,
      default: 0, // seconds spent on page
    },
    isBlog: {
      type: Boolean,
      default: false,
    },
    blogSlug: {
      type: String,
      default: '',
      index: true,
    },
    date: {
      type: String,
      required: true,
      index: true, // YYYY-MM-DD
    },
  },
  { timestamps: true }
);

export default mongoose.models.PageView || mongoose.model('PageView', PageViewSchema);
