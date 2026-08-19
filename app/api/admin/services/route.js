import { NextResponse } from 'next/server';
import connectToDatabase from '@/lib/db';
import Service from '@/models/Service';
import { fallbackServices } from '@/lib/data';
import { getLocalStore, saveLocalStore } from '@/lib/storage';

export async function GET() {
  try {
    const local = getLocalStore();
    const data = local?.services && local.services.length > 0 ? local.services : fallbackServices;

    // Background non-blocking sync
    connectToDatabase().then(async (conn) => {
      if (!conn) return;
      try {
        const services = await Service.find().sort({ order: 1 }).lean();
        if (services && services.length > 0) saveLocalStore({ services });
      } catch (_) {}
    }).catch(() => {});

    return NextResponse.json({ success: true, data });
  } catch (error) {
    const local = getLocalStore();
    const data = local?.services && local.services.length > 0 ? local.services : fallbackServices;
    return NextResponse.json({ success: true, data });
  }
}

export async function POST(req) {
  try {
    const body = await req.json();
    const { title, description } = body;

    if (!title || !description) {
      return NextResponse.json(
        { success: false, message: 'Page title and short description are required' },
        { status: 400 }
      );
    }

    const slug =
      body.slug && body.slug.trim()
        ? body.slug.trim().toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '')
        : title.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '');

    const newServiceObj = {
      _id: Date.now().toString(),
      title,
      slug,
      category: body.category || 'Medical Equipment & Devices',
      iconClass: body.iconClass || 'flaticon-hemoglobin-test-meter',
      iconTheme: body.iconTheme || 'blue',
      description,
      bannerImage: body.bannerImage || '/assets/img/banner/breadcrumb-01.jpg',
      bannerSubTitle: body.bannerSubTitle || 'Precision Medical Equipment & Healthcare Solutions',
      image1: body.image1 || '/assets/img/services/services-thumb-07.jpg',
      image2: body.image2 || '/assets/img/services/services-thumb-08.jpg',
      showcaseBanner: body.showcaseBanner || '/assets/img/services/services-thumb-09.jpg',
      processTitle: body.processTitle || 'Technical Overview & Clinical Integration',
      processText: body.processText || description,
      processPoints: body.processPoints && body.processPoints.length > 0 ? body.processPoints : [
        'FDA, CE, and ISO certified medical equipment compliant with international standards.',
        'Seamless integration with hospital information systems and ICU workflows.',
        'Precision calibration, preventive maintenance, and genuine replacement components.',
        '24/7 dedicated biomedical engineering support and rapid technical dispatch.',
      ],
      stepsTitle: body.stepsTitle || '4 Simple Deployment Steps',
      stepsText: body.stepsText || 'Our streamlined turnkey approach ensures minimal equipment downtime and rapid clinical handover.',
      stepPoints1: body.stepPoints1 || ['Needs Assessment & Planning', 'Biomedical Specification Review'],
      stepPoints2: body.stepPoints2 || ['Turnkey Procurement', 'On-Site Mechanical & Electrical Setup'],
      stepPoints3: body.stepPoints3 || ['Clinical Staff Training', '24/7 Emergency Support'],
      specsTable: body.specsTable || [],
      fullContent: body.fullContent || '',
      faq: body.faq || [],
      order: body.order || 0,
      enabled: body.enabled !== false,
      isActive: body.isActive !== false,
      showInHeader: body.showInHeader !== false,
    };

    // 1. Sync to local JSON store
    const local = getLocalStore();
    const existing = local.services || fallbackServices;
    const updatedServices = [...existing, newServiceObj];
    saveLocalStore({ services: updatedServices });

    // 2. Sync to MongoDB if connected
    const conn = await connectToDatabase();
    if (conn) {
      try {
        const dbService = await Service.create(newServiceObj);
        return NextResponse.json({
          success: true,
          message: `Product / Service page "${title}" created successfully!`,
          data: dbService,
        });
      } catch (e) {
        console.warn('MongoDB service save note:', e.message);
      }
    }

    return NextResponse.json({
      success: true,
      message: `Product / Service page "${title}" created successfully!`,
      data: newServiceObj,
    });
  } catch (error) {
    return NextResponse.json({ success: false, message: error.message }, { status: 500 });
  }
}

export async function PUT(req) {
  try {
    const body = await req.json();
    const { _id, title, description } = body;

    if (!_id) {
      return NextResponse.json({ success: false, message: 'Service ID is required' }, { status: 400 });
    }

    // 1. Update in local store
    const local = getLocalStore();
    const existing = local.services || fallbackServices;
    const updatedServices = existing.map((s) => (s._id === _id ? { ...s, ...body } : s));
    saveLocalStore({ services: updatedServices });

    // 2. Update in MongoDB if connected
    const conn = await connectToDatabase();
    if (conn) {
      try {
        const updated = await Service.findByIdAndUpdate(
          _id,
          { ...body },
          { new: true }
        );
        if (updated) {
          return NextResponse.json({ success: true, message: `Page "${title || 'Service'}" updated successfully!`, data: updated });
        }
      } catch (e) {}
    }

    return NextResponse.json({ success: true, message: `Page "${title || 'Service'}" updated successfully!`, data: body });
  } catch (error) {
    return NextResponse.json({ success: false, message: error.message }, { status: 500 });
  }
}

export async function DELETE(req) {
  try {
    const { searchParams } = new URL(req.url);
    const id = searchParams.get('id');

    if (!id) {
      return NextResponse.json({ success: false, message: 'Service ID is required' }, { status: 400 });
    }

    // 1. Remove from local store
    const local = getLocalStore();
    const existing = local.services || fallbackServices;
    const updatedServices = existing.filter((s) => s._id !== id);
    saveLocalStore({ services: updatedServices });

    // 2. Remove from MongoDB
    const conn = await connectToDatabase();
    if (conn) {
      try {
        await Service.findByIdAndDelete(id);
      } catch (e) {}
    }

    return NextResponse.json({ success: true, message: 'Service page deleted successfully!' });
  } catch (error) {
    return NextResponse.json({ success: false, message: error.message }, { status: 500 });
  }
}
