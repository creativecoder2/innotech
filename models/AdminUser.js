import mongoose from 'mongoose';

const AdminUserSchema = new mongoose.Schema(
  {
    name: {
      type: String,
      default: 'Innotech Administrator',
    },
    email: {
      type: String,
      required: true,
      unique: true,
      lowercase: true,
      trim: true,
      default: 'admin@innotech.com',
    },
    password: {
      type: String,
      required: true,
      default: 'admin123', // In production or seeded, can be updated from change-password tab
    },
    role: {
      type: String,
      default: 'superadmin',
    },
  },
  { timestamps: true }
);

export default mongoose.models.AdminUser || mongoose.model('AdminUser', AdminUserSchema);
