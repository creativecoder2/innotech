import mongoose from 'mongoose';

const AppointmentSchema = new mongoose.Schema(
  {
    name: {
      type: String,
      required: [true, 'Please provide your name'],
      trim: true,
    },
    email: {
      type: String,
      required: [true, 'Please provide your email'],
      trim: true,
      lowercase: true,
    },
    subject: {
      type: String,
      default: 'General Inquiry / Equipment Booking',
      trim: true,
    },
    phone: {
      type: String,
      required: [true, 'Please provide your contact number'],
      trim: true,
    },
    message: {
      type: String,
      default: '',
    },
    status: {
      type: String,
      enum: ['pending', 'contacted', 'completed', 'cancelled'],
      default: 'pending',
    },
  },
  { timestamps: true }
);

export default mongoose.models.Appointment || mongoose.model('Appointment', AppointmentSchema);
