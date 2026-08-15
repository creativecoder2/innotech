'use client';

import React from 'react';

export default function AboutPageCompanySection({ config = {} }) {
  const about = config.about || {};
  const experienceYears = about.experienceYears || '7';
  const experienceText = about.experienceText || 'Years of Experience';
  const title = about.title || 'Innovating Healthcare with Advance Technologies';
  const linkText = about.linkText || 'Read our MIssion & Vission';
  const linkUrl = about.linkUrl || '#process-mission-tabs';
  const description = about.description || '';
  const imageMain = about.imageMain || '/assets/img/about/about-bg-04.jpg';
  const shape1 = about.shape1 || '/assets/img/about/about-bg-05.jpg';
  const shape2 = about.shape2 || '/assets/img/about/about-bg-06.jpg';

  return (
    <section className="about-area pt-130 pb-70">
      <div className="container">
        <div className="row">
          <div className="col-xl-6 col-lg-4 col-12">
            <div className="tp-about-thumb mb-60 wow fadeInLeft" data-wow-delay=".3s">
              <div className="tp-ab-img d-flex">
                <div className="tp-ab-main-img p-relative">
                  <img src={imageMain} alt="about-thumb" />
                  <div className="about__exprience tp-ab-counter">
                    <h3 className="counter">{experienceYears}</h3>
                    <i>
                      {experienceText.includes('Years of') ? (
                        <>
                          Years of <br />
                          {experienceText.replace('Years of', '').trim() || 'Experience'}
                        </>
                      ) : (
                        experienceText
                      )}
                    </i>
                  </div>
                </div>
                <div className="tp-ab-shape d-none d-md-block d-lg-none d-xl-block">
                  <img className="ab-shape-one" src={shape1} alt="about-shape" />
                  <img className="ab-shape-two" src={shape2} alt="about-shape-two" />
                </div>
              </div>
            </div>
          </div>
          <div className="col-xl-6 col-lg-8 col-12">
            <div className="about-content about-align mb-60 wow fadeInRight" data-wow-delay=".3s">
              <div className="tp-section">
                <h3 className="tp-section__title ab-title mb-25">{title}</h3>
                <a className="tp-section__link" href={linkUrl}>
                  {linkText} <i className="fa-solid fa-arrow-right"></i>
                </a>
                <div className="mr-20 mb-40" style={{ color: '#64748B', lineHeight: '1.7', fontSize: '15px' }}>
                  {description ? (
                    description.split('\n\n').map((paragraph, pIdx) => (
                      <p key={pIdx} className="mb-20">
                        {paragraph}
                      </p>
                    ))
                  ) : (
                    <>
                      <p className="mb-20">
                        At Innotech Medical, we are dedicated to bridging the gap between world-class medical innovation and Pakistan’s healthcare sector. As a leading provider of advanced biomedical technologies, diagnostic systems, and specialized clinical equipment, we empower healthcare institutions to deliver accurate diagnoses and superior patient care.
                      </p>
                      <p className="mb-20">
                        From state-of-the-art imaging and laboratory solutions to comprehensive turnkey hospital projects, our commitment goes beyond equipment distribution. We provide end-to-end technical support, regulatory compliance, and seamless integration, ensuring that healthcare providers across the nation have access to reliable, cutting-edge medical technologies.
                      </p>
                    </>
                  )}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  );
}
