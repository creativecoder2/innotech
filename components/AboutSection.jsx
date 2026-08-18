import React from 'react';
import Link from 'next/link';

export default function AboutSection({ config = {} }) {
  const about = config.about || {};
  const experienceYears = about.experienceYears || '7';
  const subTitle = about.subTitle || 'Welcome to Innotech Medical Pvt Ltd';
  const title = about.title || 'Innovating Healthcare with Advance Technologies';
  const tagline =
    about.tagline ||
    '—Empowering hospitals, diagnostic labs, and surgical suites with world-class technology and end-to-end engineering support.';
  const description =
    about.description ||
    'Innotech Medical Pvt Ltd is Established & Reputable distributor of top-quality medical equipment across Pakistan. From state-of-the-art Medical Devices and Surgical Disposable solutions to comprehensive turnkey hospital projects, our commitment goes beyond equipment distribution. We provide end-to-end technical support, regulatory compliance, and seamless integration, ensuring that healthcare providers across the nation have access to reliable, cutting-edge medical technologies.';
  const imageMain =
    about.imageMain || about.image || '/assets/img/about/about-bg-01.png';
  const points = about.points || [
    'Critical Care & ICU Equipment',
    'Advanced Diagnostic & Lab Instruments',
    'Operating Room & General Medical Solutions',
    'Turnkey Projects & Technical Support',
  ];

  return (
    <section id="tp-about-scroll" className="about-area pb-70">
      <div className="container">
        <div className="row">
          <div className="col-xl-6 col-lg-12">
            <div className="about__thumb mb-60 wow fadeInLeft" data-wow-delay=".4s">
              <div className="about__img">
                <img src={imageMain} alt={title || "Innotech Medical About"} />
                <div className="about__exprience">
                  <h3 className="counter">{experienceYears}</h3>
                  <i>
                    Years of <br />
                    Experience
                  </i>
                </div>
              </div>
            </div>
          </div>
          <div className="col-xl-6 col-lg-12">
            <div
              className="tp-about__content pt-125 ml-60 mb-50 wow fadeInRight"
              data-wow-delay=".4s"
            >
              <div className="tp-section">
                <span className="tp-section__sub-title left-line mb-25">{subTitle}</span>
                <h3 className="tp-section__title tp-ab-sm-title mb-45">{title}</h3>
                <i>{tagline}</i>
                <p className="mr-20 mb-45">{description}</p>
              </div>
              <div className="tp-about__info-list mb-55">
                <ul>
                  {points.map((pt, i) => (
                    <li key={i}>
                      <i className="fa-solid fa-check"></i>
                      {pt}
                    </li>
                  ))}
                </ul>
              </div>
              <div className="tp-about__btn">
                <Link className="tp-btn" href="/about">
                  Our HIstory
                </Link>
                <Link className="tp-btn-second ml-25" href="/about">
                  About us
                </Link>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  );
}
