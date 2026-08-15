import mongoose from 'mongoose';

const TestimonialSchema = new mongoose.Schema(
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
    review: {
      type: String,
      required: true,
    },
    avatar: {
      type: String,
      default: '/assets/img/icon/testi-ava-01.jpg',
    },
    rating: {
      type: Number,
      default: 5,
    },
    order: {
      type: Number,
      default: 0,
    },
  },
  { timestamps: true }
);

export default mongoose.models.Testimonial || mongoose.model('Testimonial', TestimonialSchema);
