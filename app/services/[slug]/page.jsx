import React from 'react';
import Link from 'next/link';
import { notFound } from 'next/navigation';
import connectToDatabase from '@/lib/db';
import Service from '@/models/Service';
import { fallbackServices, fallbackServicesPage } from '@/lib/data';
import { getLocalStore } from '@/lib/storage';
import ServiceSupportForm from '@/components/ServiceSupportForm';

export const revalidate = 60;

async function getService(slug) {
  // 1. Check servicesPage from LocalStore
  try {
    const local = getLocalStore();
    if (local?.servicesPage?.servicesSection?.items) {
      const match = local.servicesPage.servicesSection.items.find(
        (s) =>
          (s.slug && s.slug.toLowerCase() === slug.toLowerCase()) ||
          ((s.title || '')
            .toLowerCase()
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/(^-|-$)/g, '') === slug.toLowerCase()) ||
          s.id === slug ||
          (s.link && s.link.endsWith(`/${slug}`))
      );
      if (match) return match;
    }

    if (local?.services) {
      const match = local.services.find(
        (s) => (s.slug && s.slug.toLowerCase() === slug.toLowerCase()) || s._id === slug
      );
      if (match) return match;
    }
  } catch (e) {
    console.error('Error fetching service from LocalStore:', e);
  }

  // 2. Check fallbackServicesPage
  if (fallbackServicesPage?.servicesSection?.items) {
    const match = fallbackServicesPage.servicesSection.items.find(
      (s) =>
        (s.slug && s.slug.toLowerCase() === slug.toLowerCase()) ||
        ((s.title || '')
          .toLowerCase()
          .replace(/[^a-z0-9]+/g, '-')
          .replace(/(^-|-$)/g, '') === slug.toLowerCase()) ||
        s.id === slug ||
        (s.link && s.link.endsWith(`/${slug}`))
    );
    if (match) return match;
  }

  // 3. Try DB
  try {
    const conn = await connectToDatabase();
    if (conn) {
      const dbService = await Service.findOne({
        $or: [{ slug: slug }, { _id: slug.length === 24 ? slug : null }],
      }).lean();
      if (dbService) return JSON.parse(JSON.stringify(dbService));
    }
  } catch (e) {
    console.error('Error fetching service from DB:', e);
  }

  // 4. Try Fallback Data
  const fallback = fallbackServices.find(
    (s) => (s.slug && s.slug.toLowerCase() === slug.toLowerCase()) || s._id === slug
  );
  if (fallback) return fallback;

  return null;
}

export async function generateMetadata({ params }) {
  const { slug } = await params;
  const service = await getService(slug);

  if (!service) {
    return {
      title: 'Service Not Found | INNOTECH MEDICAL PVT LTD',
    };
  }

  return {
    title: `${service.title} - Services Details | INNOTECH MEDICAL PVT LTD`,
    description:
      service.description ||
      service.processText ||
      'High-performance biomedical and clinical laboratory equipment.',
    openGraph: {
      title: `${service.title} | Innotech Medical`,
      description: service.description || service.processText,
      images: [service.bannerImage || '/assets/img/banner/breadcrumb-01.jpg'],
    },
  };
}

export default async function ServiceDetailPage({ params }) {
  const { slug } = await params;
  const service = await getService(slug);

  if (!service) {
    notFound();
  }

  const bannerImg = service.bannerImage || '/assets/img/banner/breadcrumb-01.jpg';
  const img1 = service.image1 || '/assets/img/services/services-thumb-07.jpg';
  const img2 = service.image2 || '/assets/img/services/services-thumb-08.jpg';
  const showcaseBg = service.showcaseBanner || '/assets/img/services/services-thumb-09.jpg';

  const processPoints = service.processPoints || [
    'Consectetur, adipisci velit, sed quia non numquam eius modi',
    'Perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque',
    'Ut enim ad minima veniam, quis nostrum exercitationem',
    'Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet.',
  ];

  const stepPoints1 = service.stepsCol1 || service.stepPoints1 || [
    'Extramural Funding',
    'Bacteria Markers',
    'Nam nec mi euismod euismod',
    'In aliquet dui nec lectus',
  ];
  const stepPoints2 = service.stepsCol2 || service.stepPoints2 || [
    'Extramural Funding',
    'Bacteria Markers',
    'Nam nec mi euismod euismod',
    'In aliquet dui nec lectus',
  ];
  const stepPoints3 = service.stepsCol3 || service.stepPoints3 || [
    'Extramural Funding',
    'Bacteria Markers',
    'Nam nec mi euismod euismod',
    'In aliquet dui nec lectus',
  ];

  return (
    <main>
      {/* ── 1. BREADCRUMB HERO BANNER ── */}
      <section
        className="breadcrumb__area pt-100 pb-120 breadcrumb__overlay"
        style={{
          backgroundImage: `url(${bannerImg})`,
          backgroundPosition: 'center',
          backgroundSize: 'cover',
          backgroundRepeat: 'no-repeat',
        }}
      >
        <div className="container">
          <div className="row align-items-center">
            <div className="col-xl-6 col-lg-7 col-md-12 col-12">
              <div className="tp-breadcrumb">
                <h2 className="tp-breadcrumb__title">{service.title || 'Services-Details'}</h2>
              </div>
            </div>
            <div className="col-xl-6 col-lg-5 col-md-12 col-12">
              <div className="tp-breadcrumb__link serv-md d-flex">
                <span>
                  Bioxlab : <Link href="/services">Services</Link> &gt;{' '}
                  <span style={{ color: '#ffffff', fontWeight: '600' }}>{service.title}</span>
                </span>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* ── 2. SERVICES DETAILS AREA ── */}
      <section className="services-details pt-130 pb-120">
        <div className="container">
          {/* Top Images */}
          <div className="row">
            <div className="col-lg-6 col-md-12">
              <div className="services-thumb-img mb-50 wow fadeInLeft" data-wow-delay=".4s">
                <img src={img1} alt={service.title} style={{ width: '100%', borderRadius: '6px' }} />
              </div>
            </div>
            <div className="col-lg-6 col-md-12">
              <div className="services-thumb-img mb-50 wow fadeInRight" data-wow-delay=".4s">
                <img src={img2} alt={service.title} style={{ width: '100%', borderRadius: '6px' }} />
              </div>
            </div>
          </div>

          {/* Process Section */}
          <div className="row">
            <div className="col-lg-12">
              <div className="tp-srv-process mb-50">
                <h4 className="tp-srv-process__title mb-30">
                  {service.processTitle || `${service.title} Process`}
                </h4>
                <p className="mb-20">
                  {service.processText ||
                    'Must explain to you how all this mistaken idea of denouncing works pleasure and praising its pain was born and I will gives you a itself completed account of the system, and sed expounds the actual teachings of that greater sed explores truth.'}
                </p>
                <p className="mb-40">
                  {service.processText2 ||
                    service.description ||
                    'Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem.'}
                </p>
                <div className="tp-srv-process__list">
                  <ul>
                    {processPoints.map((point, pIdx) => (
                      <li key={pIdx}>
                        <i className="fa-solid fa-check"></i>
                        {point}
                      </li>
                    ))}
                  </ul>
                </div>
              </div>
            </div>
          </div>

          {/* 4 Simple Steps Section */}
          <div className="row">
            <div className="col-lg-12">
              <div className="tp-srv-stap mb-40">
                <h4 className="tp-srv-stap__title mb-25">
                  {service.stepsTitle || '4 Simple Steps'}
                </h4>
                <p>
                  {service.stepsText ||
                    'Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.'}
                </p>
              </div>
            </div>
            <div className="col-xl-3 col-lg-4 col-md-6">
              <div className="tp-srv-stap__list mb-30 wow fadeInUp" data-wow-delay=".2s">
                <ul>
                  {stepPoints1.map((pt, idx) => (
                    <li key={idx}>
                      <i className="fa-solid fa-check"></i>
                      {pt}
                    </li>
                  ))}
                </ul>
              </div>
            </div>
            <div className="col-xl-4 col-lg-4 col-md-6">
              <div className="tp-srv-stap__list ml-65 mb-30 wow fadeInUp" data-wow-delay=".4s">
                <ul>
                  {stepPoints2.map((pt, idx) => (
                    <li key={idx}>
                      <i className="fa-solid fa-check"></i>
                      {pt}
                    </li>
                  ))}
                </ul>
              </div>
            </div>
            <div className="col-xl-5 col-lg-4 col-md-6">
              <div className="tp-srv-stap__list ml-65 ml-20 mb-30 wow fadeInUp" data-wow-delay=".6s">
                <ul>
                  {stepPoints3.map((pt, idx) => (
                    <li key={idx}>
                      <i className="fa-solid fa-check"></i>
                      {pt}
                    </li>
                  ))}
                </ul>
              </div>
            </div>
          </div>

          {/* Research Section */}
          <div className="row">
            <div className="col-lg-12">
              <div className="tp-srv-research mb-50">
                <h4 className="tp-srv-research__title mb-25">
                  {service.researchTitle || 'Our Research'}
                </h4>
                <p>
                  {service.researchText ||
                    'Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit.'}
                </p>
              </div>
            </div>
          </div>

          {/* Bottom Showcase Banner & Link */}
          {showcaseBg && (
            <div className="row">
              <div className="col-md-12">
                <div className="tp-srv-bg mb-70">
                  <img
                    src={showcaseBg}
                    alt={service.title}
                    style={{ width: '100%', borderRadius: '8px' }}
                  />
                </div>
                <div className="services-link tp-srv-link">
                  <span>
                    Laboratories Used For Scientific Research :{' '}
                    <Link href={service.bottomLink || '/contact'}>
                      {service.bottomLinkText || 'Our Project'}{' '}
                      <i className="fa-solid fa-arrow-right"></i>
                    </Link>
                  </span>
                </div>
              </div>
            </div>
          )}
        </div>
      </section>

      {/* ── 3. SUPPORT AREA / DIRECT CONTACT FORM ── */}
      <section className="support-area grey-bg pt-125 pb-130">
        <div className="container">
          <div className="row text-center">
            <div className="col-lg-12 col-md-12 col-12">
              <div className="tp-section">
                <span className="tp-section__sub-title left-line right-line mb-20">Get in touch</span>
                <h3 className="tp-section__title mb-70">Need Any Help</h3>
              </div>
            </div>
          </div>

          <div className="row justify-content-center">
            <div className="col-xl-10 col-lg-12 col-md-12 col-12">
              <ServiceSupportForm
                tagline="Derect Contact with us"
                btnText="Send Massage"
              />
            </div>
          </div>
        </div>
      </section>
    </main>
  );
}
