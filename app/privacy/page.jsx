import React from 'react';
import Link from 'next/link';
import { fallbackPrivacyPage } from '@/lib/data';
import { getLocalStore } from '@/lib/storage';

export const dynamic = 'force-dynamic';
export const revalidate = 0;

export const metadata = {
  title: 'Privacy Policy - INNOTECH MEDICAL PVT LTD',
  description:
    'Privacy policy and institutional data protection guidelines at Innotech Medical Pvt Ltd.',
};

async function getPrivacyData() {
  try {
    const local = getLocalStore();
    return local?.privacyPage || fallbackPrivacyPage;
  } catch (error) {
    return fallbackPrivacyPage;
  }
}

export default async function PrivacyPage() {
  const privacy = await getPrivacyData();
  const banner = privacy.banner || fallbackPrivacyPage.banner;
  const sections = privacy.sections && privacy.sections.length > 0 ? privacy.sections : fallbackPrivacyPage.sections;

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
                  <h2 className="tp-breadcrumb__title">{banner.title || 'Privacy Policy'}</h2>
                  <p style={{ color: '#E2E8F0', marginTop: '12px', fontSize: '16px', fontWeight: '500' }}>
                    {banner.subTitle || 'Your Data Protection & Confidentiality at Innotech Medical'}
                  </p>
                </div>
              </div>
            </div>
          </div>
        </section>
      )}

      {/* 2. Main Content Section */}
      <section className="privacy-area pt-100 pb-120" style={{ backgroundColor: '#F8FAFC' }}>
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
                      backgroundColor: '#E7FAF6',
                      color: '#0B9748',
                      display: 'flex',
                      alignItems: 'center',
                      justifyContent: 'center',
                      fontSize: '18px',
                    }}
                  >
                    <i className="fa-solid fa-shield-halved"></i>
                  </div>
                  <div>
                    <h5 style={{ margin: 0, fontSize: '16px', fontWeight: '700', color: '#171151' }}>
                      Privacy & Data Trust
                    </h5>
                    <span style={{ fontSize: '12px', color: '#64748B' }}>
                      Last Updated: {privacy.lastUpdated || 'August 2026'}
                    </span>
                  </div>
                </div>

                <div style={{ borderTop: '1px solid #ECEEF3', paddingTop: '16px', marginBottom: '20px' }}>
                  <h6 style={{ fontSize: '13px', textTransform: 'uppercase', color: '#94A3B8', fontWeight: '700', marginBottom: '12px' }}>
                    Policy Sections
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
                          <i className="fa-solid fa-chevron-right" style={{ fontSize: '10px', color: '#0B9748' }}></i>
                          <span>{sec.title}</span>
                        </a>
                      </li>
                    ))}
                  </ul>
                </div>

                <div
                  style={{
                    backgroundColor: '#F8FAFC',
                    borderRadius: '8px',
                    padding: '16px',
                    border: '1px solid #E2E8F0',
                  }}
                >
                  <p style={{ fontSize: '13px', color: '#475569', margin: '0 0 10px', fontWeight: '600' }}>
                    Need to exercise your data rights?
                  </p>
                  <Link
                    href="/contact"
                    style={{
                      display: 'inline-flex',
                      alignItems: 'center',
                      gap: '6px',
                      fontSize: '13px',
                      color: '#0B9748',
                      fontWeight: '700',
                      textDecoration: 'none',
                    }}
                  >
                    <span>Contact Privacy Officer</span>
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
                    borderLeft: '4px solid #0B9748',
                    marginBottom: '40px',
                  }}
                >
                  <h4 style={{ fontSize: '18px', fontWeight: '700', color: '#171151', marginBottom: '10px' }}>
                    {privacy.companyName || 'Innotech Medical Pvt Ltd'} Privacy Commitment
                  </h4>
                  <p style={{ margin: 0, fontSize: '14px', lineHeight: '1.7', color: '#475569' }}>
                    {privacy.introduction ||
                      'Innotech Medical Pvt Ltd is dedicated to protecting the privacy, confidentiality, and security of our healthcare partners, client hospitals, laboratories, clinicians, and website visitors.'}
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
                    Data Protection & Compliance Office
                  </h5>
                  <p style={{ fontSize: '14px', color: '#64748B', lineHeight: '1.6', margin: '0 0 10px' }}>
                    If you have questions regarding our data protection policies, or wish to update or delete your contact records, contact us directly:
                  </p>
                  <div style={{ display: 'flex', flexDirection: 'column', gap: '6px', fontSize: '14px', color: '#171151' }}>
                    <div>
                      <strong>Email: </strong>
                      <a href={`mailto:${privacy.contactEmail || 'info@innotecmedical.org'}`} style={{ color: '#0B9748' }}>
                        {privacy.contactEmail || 'info@innotecmedical.org'}
                      </a>
                    </div>
                    <div>
                      <strong>Direct Line: </strong>
                      <a href={`tel:${privacy.contactPhone || '+923316699992'}`} style={{ color: '#0B9748' }}>
                        {privacy.contactPhone || '+92 331 6699992'}
                      </a>
                    </div>
                    <div>
                      <strong>Head Office: </strong>
                      <span>
                        {privacy.contactAddress ||
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
