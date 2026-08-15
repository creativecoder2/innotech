import { NextResponse } from 'next/server';
import connectToDatabase from '@/lib/db';
import Appointment from '@/models/Appointment';
import Service from '@/models/Service';
import Blog from '@/models/Blog';
import Team from '@/models/Team';

export async function GET() {
  try {
    const conn = await connectToDatabase();
    if (conn) {
      const [appointmentsCount, servicesCount, blogsCount, teamCount, recentAppointments] =
        await Promise.all([
          Appointment.countDocuments(),
          Service.countDocuments(),
          Blog.countDocuments(),
          Team.countDocuments(),
          Appointment.find().sort({ createdAt: -1 }).limit(5).lean(),
        ]);

      return NextResponse.json({
        success: true,
        data: {
          appointmentsCount,
          servicesCount,
          blogsCount,
          teamCount,
          recentAppointments,
        },
      });
    }

    return NextResponse.json({
      success: true,
      data: {
        appointmentsCount: 12,
        servicesCount: 4,
        blogsCount: 4,
        teamCount: 4,
        recentAppointments: [
          {
            _id: 'demo-1',
            name: 'Dr. Tariq Mahmood',
            email: 'tariq@hospital.com.pk',
            phone: '+92 300 1234567',
            subject: 'ICU Ventilator Quotation',
            status: 'pending',
            createdAt: new Date().toISOString(),
          },
          {
            _id: 'demo-2',
            name: 'Ayesha Khan',
            email: 'ayesha@diagnostics.pk',
            phone: '+92 321 7654321',
            subject: 'Biochemistry Analyzer Maintenance',
            status: 'contacted',
            createdAt: new Date().toISOString(),
          },
        ],
      },
    });
  } catch (error) {
    return NextResponse.json({ success: false, message: error.message }, { status: 500 });
  }
}
