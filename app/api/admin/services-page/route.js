import { NextResponse } from 'next/server';
import { fallbackServicesPage } from '@/lib/data';
import { getLocalStore, saveLocalStore } from '@/lib/storage';

function mergeServicesPageWithFallback(savedData) {
  if (!savedData) return fallbackServicesPage;

  const base = { ...fallbackServicesPage, ...savedData };

  const rawItems = savedData.servicesSection?.items || fallbackServicesPage.servicesSection.items;
  const mergedItems = rawItems.map((item, idx) => {
    const fallbackItem =
      fallbackServicesPage.servicesSection.items.find(
        (fb) =>
          (fb.slug && fb.slug === item.slug) ||
          fb.id === item.id ||
          (fb.title && item.title && fb.title.toLowerCase() === item.title.toLowerCase())
      ) || fallbackServicesPage.servicesSection.items[idx] || {};

    const slug =
      item.slug ||
      fallbackItem.slug ||
      (item.title || '').toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, '');

    return {
      ...fallbackItem,
      ...item,
      slug,
      link: item.link && item.link !== '/services' ? item.link : `/services/${slug}`,
      bannerImage: item.bannerImage || fallbackItem.bannerImage || '/assets/img/banner/breadcrumb-01.jpg',
      bannerSubTitle: item.bannerSubTitle || fallbackItem.bannerSubTitle || 'Automated Clinical Diagnostic Systems',
      image1: item.image1 || fallbackItem.image1 || '/assets/img/services/services-thumb-07.jpg',
      image2: item.image2 || fallbackItem.image2 || '/assets/img/services/services-thumb-08.jpg',
      showcaseBanner: item.showcaseBanner || fallbackItem.showcaseBanner || '/assets/img/services/services-thumb-09.jpg',
      processTitle: item.processTitle || fallbackItem.processTitle || `${item.title || 'Clinical'} Process`,
      processText: item.processText || fallbackItem.processText || 'Must explain to you how all this mistaken idea of denouncing works pleasure and praising its pain was born.',
      processText2: item.processText2 || fallbackItem.processText2 || 'Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit.',
      processPoints: (item.processPoints && item.processPoints.length > 0) ? item.processPoints : (fallbackItem.processPoints || ['Precision analytical testing and quality control.', 'High-throughput automated sampling and diagnostic accuracy.', 'Fully compliant with clinical laboratory standards.']),
      stepsTitle: item.stepsTitle || fallbackItem.stepsTitle || '4 Simple Steps',
      stepsText: item.stepsText || fallbackItem.stepsText || 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium.',
      stepsCol1: (item.stepsCol1 && item.stepsCol1.length > 0) ? item.stepsCol1 : (fallbackItem.stepsCol1 || ['Extramural Funding', 'Bacteria Markers', 'Nam nec mi euismod']),
      stepsCol2: (item.stepsCol2 && item.stepsCol2.length > 0) ? item.stepsCol2 : (fallbackItem.stepsCol2 || ['Sample Preparation', 'Optical Detection', 'Quality Control Verification']),
      stepsCol3: (item.stepsCol3 && item.stepsCol3.length > 0) ? item.stepsCol3 : (fallbackItem.stepsCol3 || ['LIS Data Sync', 'Report Validation', 'Digital Archiving']),
      researchTitle: item.researchTitle || fallbackItem.researchTitle || 'Our Research',
      researchText: item.researchText || fallbackItem.researchText || 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam.',
      bottomLinkText: item.bottomLinkText || fallbackItem.bottomLinkText || 'Our Project',
      bottomLink: item.bottomLink || fallbackItem.bottomLink || '/contact',
    };
  });

  return {
    ...base,
    servicesSection: {
      ...(base.servicesSection || fallbackServicesPage.servicesSection),
      items: mergedItems,
    },
  };
}

export async function GET() {
  try {
    const local = getLocalStore();
    const result = mergeServicesPageWithFallback(local?.servicesPage);
    return NextResponse.json({
      success: true,
      data: result,
    });
  } catch (error) {
    return NextResponse.json({
      success: true,
      data: fallbackServicesPage,
    });
  }
}

export async function POST(req) {
  try {
    const body = await req.json();
    saveLocalStore({ servicesPage: body });

    return NextResponse.json({
      success: true,
      message: 'Services page successfully saved and published live!',
      data: body,
    });
  } catch (error) {
    return NextResponse.json(
      { success: false, message: error.message || 'Error saving services page' },
      { status: 500 }
    );
  }
}
