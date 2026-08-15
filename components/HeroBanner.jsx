'use client';

import React, { useState } from 'react';
import Link from 'next/link';

export default function HeroBanner({ config = {} }) {
  const [isVideoOpen, setIsVideoOpen] = useState(false);

  const hero = config.hero || {};
  const subTitle = hero.subTitle || 'Welcome to Innotech Medical Pvt Ltd';
  const title = hero.title || 'Innovating Health Care with Advance Technologies';
  const description =
    hero.description ||
    'Innotech Medical Pvt Ltd is Growing distributor of top-quality medical equipment across Pakistan At Innotech Medical, we are dedicated to bridging the gap between world-class medical innovation and Pakistan’s healthcare sector. As a leading provider of advanced biomedical technologies, diagnostic systems, and specialized clinical equipment, we empower healthcare institutions to deliver accurate diagnoses and superior patient care.';
  const btn1Text = hero.btn1Text || 'Contact with Us';
  const btn1Link = hero.btn1Link || '/contact';
  const btn2Text = hero.btn2Text || 'About us';
  const btn2Link = hero.btn2Link || '/about';
  const badge1Text = hero.badge1Text || '100% Customer Satisfaction';
  const badge2Text = hero.badge2Text || 'Help and Acess is Our Mission';
  const badge3Text = hero.badge3Text || '100% Quality Laboratory service';
  const heroImage = hero.image || hero.bannerImage || '/assets/img/banner/banner-01.png';

  const rawVideoUrl = hero.videoUrl || 'https://www.youtube.com/embed/d8w5SICzzxc';

  // Helper to determine if video is a direct file or YouTube
  const isDirectVideoFile =
    rawVideoUrl.startsWith('/uploads/') ||
    rawVideoUrl.startsWith('blob:') ||
    /\.(mp4|webm|mov|ogg|mkv)(\?.*)?$/i.test(rawVideoUrl);

  // Format YouTube URL to embed format if needed
  const getEmbedUrl = (url) => {
    if (!url) return 'https://www.youtube.com/embed/d8w5SICzzxc?autoplay=1';
    if (url.includes('youtube.com/embed/')) {
      return url.includes('autoplay=1') ? url : `${url}${url.includes('?') ? '&' : '?'}autoplay=1`;
    }
    const watchMatch = url.match(/(?:youtube\.com\/watch\?v=|youtu\.be\/)([\w-]+)/);
    if (watchMatch && watchMatch[1]) {
      return `https://www.youtube.com/embed/${watchMatch[1]}?autoplay=1`;
    }
    return url;
  };

  const scrollToAbout = (e) => {
    e.preventDefault();
    const elem = document.getElementById('tp-about-scroll');
    if (elem) {
      elem.scrollIntoView({ behavior: 'smooth' });
    }
  };

  return (
    <>
      <section className="banner-area p-relative pt-90">
        <div className="container">
          <div className="row">
            <div className="col-xl-8">
              <div className="banner__content pt-145 mb-135">
                <span className="banner__sub-title mb-20">{subTitle}</span>
                <h2 className="banner__title mb-30">{title}</h2>
                <p>{description}</p>
                <div className="banner__btn">
                  <Link className="tp-btn" href={btn1Link}>
                    {btn1Text}
                  </Link>
                  <Link className="tp-btn-second ml-25" href={btn2Link}>
                    {btn2Text}
                  </Link>
                </div>
              </div>
              <div className="banner__box-item">
                <div className="row">
                  <div className="col-xl-4 col-lg-4 col-md-6">
                    <div
                      className="banner__item d-flex align-items-center mb-30 wow fadeInUp"
                      data-wow-delay=".2s"
                    >
                      <div className="banner__item-icon">
                        <i className="flaticon-rating"></i>
                      </div>
                      <div className="banner__item-content">
                        <span>{badge1Text}</span>
                      </div>
                    </div>
                  </div>
                  <div className="col-xl-4 col-lg-4 col-md-6">
                    <div
                      className="banner__item pink-border d-flex align-items-center mb-30 wow fadeInUp"
                      data-wow-delay=".4s"
                    >
                      <div className="banner__item-icon pink-icon">
                        <i className="flaticon-target"></i>
                      </div>
                      <div className="banner__item-content">
                        <span>{badge2Text}</span>
                      </div>
                    </div>
                  </div>
                  <div className="col-xl-4 col-lg-4 col-md-6">
                    <div
                      className="banner__item green-border d-flex align-items-center mb-30 wow fadeInUp"
                      data-wow-delay=".6s"
                    >
                      <div className="banner__item-icon green-icon">
                        <i className="flaticon-premium-badge"></i>
                      </div>
                      <div className="banner__item-content">
                        <span>{badge3Text}</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div className="bannerscroll d-none d-xl-block">
          <div className="banner-scroll-btn">
            <a className="bannerscroll-icon" href="#tp-about-scroll" onClick={scrollToAbout}>
              <i className="fa-light fa-computer-mouse"></i>
              <span>Scroll Down</span>
            </a>
          </div>
        </div>

        <div className="banner__shape d-none d-lg-block">
          <img src={heroImage} alt="banner-img" />
          <div className="banner__video-btn">
            <button
              type="button"
              className="banner__video-icon popup-video"
              onClick={() => setIsVideoOpen(true)}
              aria-label="Play presentation video"
              style={{ cursor: 'pointer' }}
            >
              <i className="fa-solid fa-play"></i>
            </button>
          </div>
        </div>
      </section>

      {/* Video Popup Modal (Supports YouTube Embed & Uploaded Video Files) */}
      {isVideoOpen && (
        <div
          style={{
            position: 'fixed',
            inset: 0,
            zIndex: 99999,
            backgroundColor: 'rgba(0,0,0,0.85)',
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'center',
            padding: '20px',
            backdropFilter: 'blur(6px)',
          }}
          onClick={() => setIsVideoOpen(false)}
        >
          <div
            style={{
              position: 'relative',
              width: '100%',
              maxWidth: '880px',
              aspectRatio: '16/9',
              background: '#000',
              borderRadius: '12px',
              overflow: 'hidden',
              boxShadow: '0 25px 50px -12px rgba(0, 0, 0, 0.5)',
            }}
            onClick={(e) => e.stopPropagation()}
          >
            <button
              onClick={() => setIsVideoOpen(false)}
              style={{
                position: 'absolute',
                top: '12px',
                right: '16px',
                color: '#fff',
                fontSize: '28px',
                background: 'rgba(0,0,0,0.6)',
                borderRadius: '50%',
                width: '40px',
                height: '40px',
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'center',
                border: 'none',
                cursor: 'pointer',
                zIndex: 20,
              }}
              aria-label="Close video"
            >
              &times;
            </button>

            {isDirectVideoFile ? (
              <video
                src={rawVideoUrl}
                controls
                autoPlay
                style={{ width: '100%', height: '100%', objectFit: 'contain' }}
              />
            ) : (
              <iframe
                width="100%"
                height="100%"
                src={getEmbedUrl(rawVideoUrl)}
                title="Innotech Medical Presentation"
                frameBorder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                allowFullScreen
              ></iframe>
            )}
          </div>
        </div>
      )}
    </>
  );
}
