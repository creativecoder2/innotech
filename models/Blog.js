import mongoose from 'mongoose';

const BlogSchema = new mongoose.Schema(
  {
    title: {
      type: String,
      required: [true, 'Blog title is required'],
      trim: true,
    },
    slug: {
      type: String,
      required: true,
      unique: true,
    },
    category: {
      type: String,
      default: 'Medicine',
    },
    dateDay: {
      type: String,
      default: '26',
    },
    dateMonth: {
      type: String,
      default: 'Dec',
    },
    excerpt: {
      type: String,
      required: true,
    },
    content: {
      type: String,
    },
    image: {
      type: String,
      default: '/assets/img/blog/blog-thumb-01.jpg',
    },
    author: {
      type: String,
      default: 'Innotech Editorial',
    },
    views: {
      type: Number,
      default: 0,
    },
    viewedVisitors: {
      type: [String],
      default: [],
    },
    order: {
      type: Number,
      default: 0,
    },
  },
  { timestamps: true }
);

export default mongoose.models.Blog || mongoose.model('Blog', BlogSchema);
