import React from 'react';
import Link from 'next/link';
import { fallbackTermsPage } from '@/lib/data';
import { getLocalStore } from '@/lib/storage';

export const revalidate = 60;

export const metadata = {
  title: 'Terms and Conditions - INNOTECH MEDICAL PVT LTD',
  description:
    'Legal terms and conditions for medical equipment distribution, turnkey hospital engineering, and clinical supplies by Innotech Medical Pvt Ltd.',
};

async function getTermsData() {
  try {
    const local = getLocalStore();
    return local?.termsPage || fallbackTermsPage;
  } catch (error) {
    return fallbackTermsPage;
  }
}

export default async function TermsPage() {
  const terms = await getTermsData();
  const banner = terms.banner || fallbackTermsPage.banner;
  const sections = terms.sections && terms.sections.length > 0 ? terms.sections : fallbackTermsPage.sections;

  return (
    <>
      {/* 1. Hero Breadcrumb Banner */}
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
              <div className="col-xl-8 col-lg-12 col-md-12 col-12">
                <div className="tp-breadcrumb">
                  <h2 className="tp-breadcrumb__title">{banner.title || 'Terms & Conditions'}</h2>
                  <p style={{ color: '#E2E8F0', marginTop: '12px', fontSize: '16px', fontWeight: '500' }}>
                    {banner.subTitle || 'Legal Terms & Healthcare Equipment Procurement Agreement'}
                  </p>
                </div>
              </div>
            </div>
          </div>
        </section>
      )}

      {/* 2. Main Content Section */}
      <section className="terms-area pt-100 pb-120" style={{ backgroundColor: '#F8FAFC' }}>
        <div className="container">
          <div className="row">
            {/* Sidebar Navigation */}
            <div className="col-xl-4 col-lg-4 mb-40">
              <div
                style={{
                  backgroundColor: '#ffffff',
                  borderRadius: '12px',
                  padding: '30px',
                  boxShadow: '0 4px 20px rgba(0,0,0,0.04)',
                  border: '1px solid #ECEEF3',
                  position: 'sticky',
                  top: '100px',
                }}
              >
                <div style={{ display: 'flex', alignItems: 'center', gap: '10px', marginBottom: '20px' }}>
                  <div
                    style={{
                      width: '40px',
                      height: '40px',
                      borderRadius: '8px',
                      backgroundColor: '#EFF6FF',
                      color: '#0E63FF',
                      display: 'flex',
                      alignItems: 'center',
                      justifyContent: 'center',
                      fontSize: '18px',
                    }}
                  >
                    <i className="fa-solid fa-scale-balanced"></i>
                  </div>
                  <div>
                    <h5 style={{ margin: 0, fontSize: '16px', fontWeight: '700', color: '#171151' }}>
                      Policy Overview
                    </h5>
                    <span style={{ fontSize: '12px', color: '#64748B' }}>
                      Last Updated: {terms.lastUpdated || 'August 2026'}
                    </span>
                  </div>
                </div>

                <div style={{ borderTop: '1px solid #ECEEF3', paddingTop: '16px', marginBottom: '20px' }}>
                  <h6 style={{ fontSize: '13px', textTransform: 'uppercase', color: '#94A3B8', fontWeight: '700', marginBottom: '12px' }}>
                    Quick Navigation
                  </h6>
                  <ul style={{ listStyle: 'none', padding: 0, margin: 0 }}>
                    {sections.map((sec, idx) => (
                      <li key={sec.id || idx} style={{ marginBottom: '10px' }}>
                        <a
                          href={`#sec-${idx}`}
                          style={{
                            fontSize: '14px',
                            color: '#475569',
                            textDecoration: 'none',
                            display: 'flex',
                            alignItems: 'center',
                            gap: '8px',
                            transition: 'all 0.2s',
                          }}
                        >
                          <i className="fa-solid fa-chevron-right" style={{ fontSize: '10px', color: '#0E63FF' }}></i>
                          <span>{sec.title}</span>
                        </a>
                      </li>
                    ))}
                  </ul>
                </div>

                <div
                  style={{
                    backgroundColor: '#EFF6FF',
                    borderRadius: '8px',
                    padding: '16px',
                    border: '1px solid #BFDBFE',
                  }}
                >
                  <p style={{ fontSize: '13px', color: '#1E40AF', margin: '0 0 10px', fontWeight: '600' }}>
                    Have legal or procurement questions?
                  </p>
                  <Link
                    href="/contact"
                    style={{
                      display: 'inline-flex',
                      alignItems: 'center',
                      gap: '6px',
                      fontSize: '13px',
                      color: '#0E63FF',
                      fontWeight: '700',
                      textDecoration: 'none',
                    }}
                  >
                    <span>Contact Legal Support</span>
                    <i className="fa-solid fa-arrow-right" style={{ fontSize: '11px' }}></i>
                  </Link>
                </div>
              </div>
            </div>

            {/* Main Policy Content */}
            <div className="col-xl-8 col-lg-8">
              <div
                style={{
                  backgroundColor: '#ffffff',
                  borderRadius: '12px',
                  padding: '45px 40px',
                  boxShadow: '0 4px 20px rgba(0,0,0,0.04)',
                  border: '1px solid #ECEEF3',
                }}
              >
                {/* Introduction Box */}
                <div
                  style={{
                    padding: '24px 28px',
                    borderRadius: '10px',
                    backgroundColor: '#F8FAFC',
                    borderLeft: '4px solid #0E63FF',
                    marginBottom: '40px',
                  }}
                >
                  <h4 style={{ fontSize: '18px', fontWeight: '700', color: '#171151', marginBottom: '10px' }}>
                    {terms.companyName || 'Innotech Medical Pvt Ltd'} Terms & Conditions
                  </h4>
                  <p style={{ margin: 0, fontSize: '14px', lineHeight: '1.7', color: '#475569' }}>
                    {terms.introduction ||
                      'Welcome to Innotech Medical Pvt Ltd. By accessing our website, purchasing our biomedical equipment, or utilizing our engineering services, you agree to comply with and be bound by the following terms and conditions.'}
                  </p>
                </div>

                {/* Sections List */}
                <div style={{ display: 'flex', flexDirection: 'column', gap: '35px' }}>
                  {sections.map((sec, idx) => (
                    <div key={sec.id || idx} id={`sec-${idx}`} style={{ scrollMarginTop: '120px' }}>
                      <h4
                        style={{
                          fontSize: '19px',
                          fontWeight: '700',
                          color: '#171151',
                          marginBottom: '14px',
                          display: 'flex',
                          alignItems: 'center',
                          gap: '10px',
                        }}
                      >
                        <span>{sec.title}</span>
                      </h4>
                      <p style={{ fontSize: '15px', color: '#64748B', lineHeight: '1.8', margin: 0 }}>
                        {sec.content}
                      </p>
                    </div>
                  ))}
                </div>

                {/* Contact Card */}
                <div
                  style={{
                    marginTop: '50px',
                    paddingTop: '30px',
                    borderTop: '1px solid #ECEEF3',
                  }}
                >
                  <h5 style={{ fontSize: '16px', fontWeight: '700', color: '#171151', marginBottom: '12px' }}>
                    Official Contact & Legal Registry
                  </h5>
                  <p style={{ fontSize: '14px', color: '#64748B', lineHeight: '1.6', margin: '0 0 10px' }}>
                    For any questions regarding these terms, equipment service level agreements, or formal distribution terms, please reach out to our legal department:
                  </p>
                  <div style={{ display: 'flex', flexDirection: 'column', gap: '6px', fontSize: '14px', color: '#171151' }}>
                    <div>
                      <strong>Email: </strong>
                      <a href={`mailto:${terms.contactEmail || 'info@innotecmedical.org'}`} style={{ color: '#0E63FF' }}>
                        {terms.contactEmail || 'info@innotecmedical.org'}
                      </a>
                    </div>
                    <div>
                      <strong>Direct Phone: </strong>
                      <a href={`tel:${terms.contactPhone || '+923316699992'}`} style={{ color: '#0E63FF' }}>
                        {terms.contactPhone || '+92 331 6699992'}
                      </a>
                    </div>
                    <div>
                      <strong>Registered Address: </strong>
                      <span>
                        {terms.contactAddress ||
                          '1st Floor, Plot: A-301, Sardar Ali Sabri Road, Block-2, Gulshan e Iqbal, Karachi, Sindh, Pakistan.'}
                      </span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </>
  );
}
