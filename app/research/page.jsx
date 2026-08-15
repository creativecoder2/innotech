import React from 'react';
import Link from 'next/link';
import { fallbackResearchPage } from '@/lib/data';
import { getLocalStore } from '@/lib/storage';

export const dynamic = 'force-dynamic';
export const revalidate = 0;

export const metadata = {
  title: 'Research & Clinical Innovations - INNOTECH MEDICAL PVT LTD',
  description: 'Advancing healthcare technology, precision diagnostics, and biomedical projects across Pakistan.',
};

async function getResearchData() {
  try {
    const local = getLocalStore();
    return local.researchPage || fallbackResearchPage;
  } catch (e) {
    return fallbackResearchPage;
  }
}

export default async function ResearchPage() {
  const researchData = await getResearchData();
  const banner = researchData.banner || {};
  const items = (researchData.items || []).filter((i) => i.enabled !== false);

  return (
    <>
      {/* 1. Breadcrumb Banner */}
      {banner.enabled !== false && (
        <section
          className="breadcrumb__area pt-100 pb-120 breadcrumb__overlay"
          style={{
            backgroundImage: `url(${banner.bgImage || '/assets/img/banner/breadcrumb-01.jpg'})`,
            backgroundSize: 'cover',
            backgroundPosition: 'center',
          }}
        >
          <div className="container">
            <div className="row align-items-center">
              <div className="col-lg-7 col-md-12 col-12">
                <div className="tp-breadcrumb">
                  <h2 className="tp-breadcrumb__title">{banner.title || 'Research & Projects'}</h2>
                  {banner.subTitle && (
                    <p style={{ color: '#E2E8F0', marginTop: '10px', fontSize: '16px', fontWeight: '500' }}>
                      {banner.subTitle}
                    </p>
                  )}
                </div>
              </div>
            </div>
          </div>
        </section>
      )}

      {/* 2. Research Projects Grid */}
      <section className="research-area pt-130 pb-130">
        <div className="container">
          <div className="row">
            {items.map((project, idx) => {
              const themeClass =
                idx % 4 === 1 ? 'tp-pink' : idx % 4 === 2 ? 'tp-green' : idx % 4 === 3 ? 'tp-sky' : '';

              return (
                <div key={project.id || idx} className="col-lg-4 col-md-6">
                  <div
                    className={`research-item ${themeClass} mb-50 wow fadeInUp`}
                    data-wow-delay={`.${(idx % 3) * 2 + 2}s`}
                    style={{
                      backgroundColor: '#ffffff',
                      borderRadius: '12px',
                      overflow: 'hidden',
                      border: '1px solid #ECEEF3',
                      boxShadow: '0 8px 24px rgba(0,0,0,0.04)',
                      height: 'calc(100% - 50px)',
                      display: 'flex',
                      flexDirection: 'column',
                    }}
                  >
                    <div className="research-item__thum fix mb-20" style={{ height: '220px', overflow: 'hidden' }}>
                      <img
                        src={project.image || '/assets/img/research/research-thumb-01.jpg'}
                        alt={project.title}
                        style={{ width: '100%', height: '100%', objectFit: 'cover' }}
                      />
                    </div>
                    <div className="research-item__content" style={{ padding: '0 25px 25px', flex: 1, display: 'flex', flexDirection: 'column' }}>
                      <span style={{ fontSize: '13px', fontWeight: '700', color: '#0E63FF', textTransform: 'uppercase', letterSpacing: '0.5px' }}>
                        {project.category}
                      </span>
                      <h4 className="research-item__title mb-15 mt-5">
                        <Link href="/services" style={{ textDecoration: 'none' }}>
                          {project.title}
                        </Link>
                      </h4>
                      <p style={{ color: '#6b6b6b', fontSize: '14px', lineHeight: '1.6', flex: 1 }}>
                        {project.description}
                      </p>
                      <Link href="/contact" className="research-item__btn" style={{ marginTop: '15px' }}>
                        Inquire Project <i className="fa-solid fa-arrow-right ml-5"></i>
                      </Link>
                    </div>
                  </div>
                </div>
              );
            })}
          </div>

          {/* Quick Contact CTA Footer inside Research */}
          <div className="row text-center mt-20">
            <div className="col-lg-12">
              <div
                style={{
                  backgroundColor: '#F8FAFC',
                  padding: '30px',
                  borderRadius: '12px',
                  border: '1px solid #E2E8F0',
                }}
              >
                <span style={{ fontSize: '16px', color: '#171151', fontWeight: '600' }}>
                  Need customized turnkey medical technology development?{' '}
                  <Link href="/contact" style={{ color: '#0E63FF', fontWeight: '700', marginLeft: '6px' }}>
                    Speak with our Biomedical Team <i className="fa-solid fa-arrow-right"></i>
                  </Link>
                </span>
              </div>
            </div>
          </div>
        </div>
      </section>
    </>
  );
}
