import { NextResponse } from 'next/server';
import connectToDatabase from '@/lib/db';
import AdminUser from '@/models/AdminUser';

export async function POST(req) {
  try {
    const { email, password } = await req.json();

    if (!email || !password) {
      return NextResponse.json(
        { success: false, message: 'Email and password are required' },
        { status: 400 }
      );
    }

    const conn = await connectToDatabase();

    if (conn) {
      let admin = await AdminUser.findOne({ email: email.toLowerCase().trim() });
      if (!admin) {
        // Initialize default admin if none exists
        if (email.toLowerCase() === 'admin@innotech.com' && password === 'admin123') {
          admin = await AdminUser.create({
            email: 'admin@innotech.com',
            password: 'admin123',
            name: 'Innotech Administrator',
            role: 'superadmin',
          });
        } else {
          return NextResponse.json(
            { success: false, message: 'Invalid email or password' },
            { status: 401 }
          );
        }
      }

      if (admin.password !== password) {
        return NextResponse.json(
          { success: false, message: 'Invalid email or password' },
          { status: 401 }
        );
      }

      return NextResponse.json({
        success: true,
        message: 'Login successful',
        user: { name: admin.name, email: admin.email, role: admin.role },
      });
    }

    // Fallback authentication if MongoDB is not connected yet
    if (email.toLowerCase() === 'admin@innotech.com' && password === 'admin123') {
      return NextResponse.json({
        success: true,
        message: 'Login successful (Offline/Demo Mode)',
        user: { name: 'Innotech Administrator', email: 'admin@innotech.com', role: 'superadmin' },
      });
    }

    return NextResponse.json(
      { success: false, message: 'Invalid email or password' },
      { status: 401 }
    );
  } catch (error) {
    return NextResponse.json(
      { success: false, message: error.message || 'Login error' },
      { status: 500 }
    );
  }
}
