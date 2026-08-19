'use client';

import React, { useState, useEffect, useCallback, useRef } from 'react';
import Link from 'next/link';
import { Swiper, SwiperSlide } from 'swiper/react';
import { Autoplay, Navigation } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/navigation';
import { fallbackGallery } from '@/lib/data';

export default function GallerySection({ config = {}, galleryItems = [] }) {
  const [lightboxIndex, setLightboxIndex] = useState(null);
  const swiperRef = useRef(null);

  const galleryConfig = config.gallerySection || {};
  const subTitle = galleryConfig.subTitle || 'Work Gallery';
  const title = galleryConfig.title || 'INNOTECH Gallery';
  const btnText = galleryConfig.btnText || 'Explore More';
  const btnLink = galleryConfig.btnLink && galleryConfig.btnLink !== '/services' ? galleryConfig.btnLink : '/gallery';

  const rawItems =
    galleryConfig.items && galleryConfig.items.length > 0
      ? galleryConfig.items
      : galleryItems && galleryItems.length > 0
      ? galleryItems
      : fallbackGallery;

  const itemsToRender = rawItems.filter((i) => i.enabled !== false);

  const handlePrev = useCallback(() => {
    setLightboxIndex((prev) => {
      if (prev === null) return null;
      return prev === 0 ? itemsToRender.length - 1 : prev - 1;
    });
  }, [itemsToRender.length]);

  const handleNext = useCallback(() => {
    setLightboxIndex((prev) => {
      if (prev === null) return null;
      return prev === itemsToRender.length - 1 ? 0 : prev + 1;
    });
  }, [itemsToRender.length]);

  const handleClose = useCallback(() => {
    setLightboxIndex(null);
  }, []);

  // Keyboard navigation for Lightbox
  useEffect(() => {
    if (lightboxIndex === null) return;

    const handleKeyDown = (e) => {
      if (e.key === 'Escape') {
        handleClose();
      } else if (e.key === 'ArrowLeft') {
        handlePrev();
      } else if (e.key === 'ArrowRight') {
        handleNext();
      }
    };

    window.addEventListener('keydown', handleKeyDown);
    return () => window.removeEventListener('keydown', handleKeyDown);
  }, [lightboxIndex, handleClose, handlePrev, handleNext]);

  const currentItem =
    lightboxIndex !== null && itemsToRender[lightboxIndex]
      ? itemsToRender[lightboxIndex]
      : null;

  return (
    <>
      <style jsx global>{`
        .gall-active .swiper {
          overflow: hidden !important;
        }
        .gall-active .swiper-wrapper {
          display: flex !important;
          flex-wrap: nowrap !important;
        }
        .gall-active .swiper-slide {
          flex-shrink: 0 !important;
          width: 100% !important;
        }
        @media (min-width: 576px) {
          .gall-active .swiper-slide {
            width: 50% !important;
          }
        }
        @media (min-width: 992px) {
          .gall-active .swiper-slide {
            width: 33.333% !important;
          }
        }
        @media (min-width: 1400px) {
          .gall-active .swiper-slide {
            width: 25% !important;
          }
        }
        .tp-gallery__img {
          border-radius: 10px !important;
          overflow: hidden !important;
          height: 280px !important;
          background: #e2e8f0 !important;
        }
        .tp-gallery__img img {
          width: 100% !important;
          height: 100% !important;
          object-fit: cover !important;
        }
        .gallery-nav-prev {
          width: 46px;
          height: 46px;
          border-radius: 50%;
          background: #ffffff;
          border: 1.5px solid #dbe2ea;
          display: inline-flex;
          align-items: center;
          justify-content: center;
          color: #171151;
          transition: all 0.25s ease;
          cursor: pointer;
          user-select: none;
        }
        .gallery-nav-prev:hover {
          border-color: #171151;
          transform: translateY(-2px);
          box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }
        .gallery-nav-next {
          width: 46px;
          height: 46px;
          border-radius: 50%;
          background: #1e2124;
          border: 1.5px solid #1e2124;
          display: inline-flex;
          align-items: center;
          justify-content: center;
          color: #ffffff;
          transition: all 0.25s ease;
          cursor: pointer;
          user-select: none;
        }
        .gallery-nav-next:hover {
          background: #0e63ff;
          border-color: #0e63ff;
          color: #ffffff;
          transform: translateY(-2px);
          box-shadow: 0 6px 16px rgba(14, 99, 255, 0.35);
        }
      `}</style>
      <section
        className="gallery-area grey-bg pt-120 pb-130"
        style={{ backgroundImage: `url('/assets/img/shape/shape-bg-01.png')` }}
      >
        <div className="container">
          <div className="row align-items-center mb-50">
            <div className="col-lg-8 col-md-8 col-12">
              <div className="tp-section">
                <span className="tp-section__sub-title left-line mb-20">{subTitle}</span>
                <h3 className="tp-section__title mb-0">{title}</h3>
              </div>
            </div>
            <div className="col-lg-4 col-md-4 col-12">
              <div className="d-flex align-items-center justify-content-md-end mt-20 mt-md-0" style={{ gap: '14px' }}>
                <div
                  className="gallery-prev gallery-nav-prev"
                  onClick={() => swiperRef.current?.slidePrev()}
                  role="button"
                  tabIndex={0}
                  aria-label="Previous gallery item"
                >
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                    <path d="M19 12H5M12 19l-7-7 7-7"/>
                  </svg>
                </div>
                <div
                  className="gallery-next gallery-nav-next"
                  onClick={() => swiperRef.current?.slideNext()}
                  role="button"
                  tabIndex={0}
                  aria-label="Next gallery item"
                >
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
                    <path d="M5 12h14M12 5l7 7-7 7"/>
                  </svg>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div className="container-fluid">
          <div className="tp-gallery ml-15 mr-15">
            <div className="gall-active">
              <Swiper
                onSwiper={(swiper) => {
                  swiperRef.current = swiper;
                }}
                modules={[Autoplay, Navigation]}
                spaceBetween={30}
                slidesPerView={1}
                loop={itemsToRender.length >= 4}
                autoplay={{
                  delay: 4500,
                  disableOnInteraction: false,
                  pauseOnMouseEnter: true,
                }}
                navigation={{
                  prevEl: '.gallery-prev',
                  nextEl: '.gallery-next',
                }}
                breakpoints={{
                  0: {
                    slidesPerView: 1,
                    spaceBetween: 20,
                  },
                  576: {
                    slidesPerView: 2,
                    spaceBetween: 20,
                  },
                  768: {
                    slidesPerView: 2,
                    spaceBetween: 30,
                  },
                  992: {
                    slidesPerView: 3,
                    spaceBetween: 30,
                  },
                  1400: {
                    slidesPerView: 4,
                    spaceBetween: 30,
                  },
                }}
              >
                {itemsToRender.map((item, idx) => (
                  <SwiperSlide key={item.id || idx}>
                    <div className="tp-gallery__item p-relative mb-70">
                      <div className="tp-gallery__img p-relative">
                        <img src={item.image} alt={item.title} loading="eager" decoding="async" />
                        <div className="tp-gallery__info">
                          <a
                            className="popup-image"
                            href={item.image}
                            onClick={(e) => {
                              e.preventDefault();
                              setLightboxIndex(idx);
                            }}
                            aria-label={`View larger ${item.title}`}
                          >
                            <i className="fa-solid fa-plus"></i>
                          </a>
                        </div>
                      </div>
                      <div className="tp-gallery__content">
                        <h4 className="tp-gallery__title">
                          <Link href={item.link || '/services'}>{item.title}</Link>
                        </h4>
                        <span>
                          <i className="fa-solid fa-tag"></i>
                          <Link href={item.link || '/services'}>{item.tag}</Link>
                        </span>
                      </div>
                    </div>
                  </SwiperSlide>
                ))}
              </Swiper>
            </div>
          </div>
        </div>
        <div className="container">
          <div className="row text-center">
            <div className="col-lg-12">
              <Link className="tp-btn-second" href={btnLink}>
                {btnText}
              </Link>
            </div>
          </div>
        </div>
      </section>

      {/* Interactive Gallery Lightbox Slider Modal */}
      {currentItem && (
        <div
          style={{
            position: 'fixed',
            inset: 0,
            zIndex: 999999,
            backgroundColor: 'rgba(11, 15, 25, 0.94)',
            backdropFilter: 'blur(6px)',
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'center',
            padding: '20px',
            animation: 'fadeIn 0.25s ease',
            userSelect: 'none',
          }}
          onClick={handleClose}
        >
          {/* Top Close Button */}
          <button
            onClick={handleClose}
            style={{
              position: 'fixed',
              top: '20px',
              right: '25px',
              color: '#ffffff',
              fontSize: '38px',
              background: 'rgba(255, 255, 255, 0.1)',
              border: 'none',
              borderRadius: '50%',
              width: '48px',
              height: '48px',
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'center',
              cursor: 'pointer',
              lineHeight: 1,
              transition: 'background 0.2s',
              zIndex: 1000000,
            }}
            aria-label="Close image popup"
            onMouseEnter={(e) => (e.currentTarget.style.background = 'rgba(255, 255, 255, 0.25)')}
            onMouseLeave={(e) => (e.currentTarget.style.background = 'rgba(255, 255, 255, 0.1)')}
          >
            &times;
          </button>

          {/* Left Arrow Button */}
          <button
            onClick={(e) => {
              e.stopPropagation();
              handlePrev();
            }}
            style={{
              position: 'fixed',
              left: '20px',
              top: '50%',
              transform: 'translateY(-50%)',
              width: '56px',
              height: '56px',
              borderRadius: '50%',
              backgroundColor: 'rgba(255, 255, 255, 0.15)',
              border: '1px solid rgba(255, 255, 255, 0.3)',
              color: '#ffffff',
              fontSize: '22px',
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'center',
              cursor: 'pointer',
              transition: 'all 0.2s ease',
              zIndex: 1000000,
            }}
            aria-label="Previous image"
            onMouseEnter={(e) => {
              e.currentTarget.style.backgroundColor = '#0e63ff';
              e.currentTarget.style.borderColor = '#0e63ff';
              e.currentTarget.style.transform = 'translateY(-50%) scale(1.1)';
            }}
            onMouseLeave={(e) => {
              e.currentTarget.style.backgroundColor = 'rgba(255, 255, 255, 0.15)';
              e.currentTarget.style.borderColor = 'rgba(255, 255, 255, 0.3)';
              e.currentTarget.style.transform = 'translateY(-50%) scale(1)';
            }}
          >
            <i className="fa-solid fa-chevron-left"></i>
          </button>

          {/* Right Arrow Button */}
          <button
            onClick={(e) => {
              e.stopPropagation();
              handleNext();
            }}
            style={{
              position: 'fixed',
              right: '20px',
              top: '50%',
              transform: 'translateY(-50%)',
              width: '56px',
              height: '56px',
              borderRadius: '50%',
              backgroundColor: 'rgba(255, 255, 255, 0.15)',
              border: '1px solid rgba(255, 255, 255, 0.3)',
              color: '#ffffff',
              fontSize: '22px',
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'center',
              cursor: 'pointer',
              transition: 'all 0.2s ease',
              zIndex: 1000000,
            }}
            aria-label="Next image"
            onMouseEnter={(e) => {
              e.currentTarget.style.backgroundColor = '#0e63ff';
              e.currentTarget.style.borderColor = '#0e63ff';
              e.currentTarget.style.transform = 'translateY(-50%) scale(1.1)';
            }}
            onMouseLeave={(e) => {
              e.currentTarget.style.backgroundColor = 'rgba(255, 255, 255, 0.15)';
              e.currentTarget.style.borderColor = 'rgba(255, 255, 255, 0.3)';
              e.currentTarget.style.transform = 'translateY(-50%) scale(1)';
            }}
          >
            <i className="fa-solid fa-chevron-right"></i>
          </button>

          {/* Main Image Container */}
          <div
            style={{
              position: 'relative',
              maxWidth: '85vw',
              maxHeight: '88vh',
              display: 'flex',
              flexDirection: 'column',
              alignItems: 'center',
            }}
            onClick={(e) => e.stopPropagation()}
          >
            <img
              key={currentItem.image}
              src={currentItem.image}
              alt={currentItem.title || 'Gallery Preview'}
              style={{
                maxWidth: '100%',
                maxHeight: '78vh',
                objectFit: 'contain',
                borderRadius: '8px',
                boxShadow: '0 25px 60px rgba(0, 0, 0, 0.8)',
                display: 'block',
                animation: 'fadeIn 0.2s ease',
              }}
            />

            {/* Bottom Caption & Counter bar */}
            <div
              style={{
                width: '100%',
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'space-between',
                marginTop: '14px',
                color: '#e2e8f0',
                fontSize: '15px',
                fontWeight: '500',
              }}
            >
              <div>
                <span style={{ color: '#ffffff', fontWeight: '600' }}>{currentItem.title}</span>
                {currentItem.tag && (
                  <span style={{ color: '#94a3b8', marginLeft: '10px' }}>({currentItem.tag})</span>
                )}
              </div>
              <div
                style={{
                  background: 'rgba(255, 255, 255, 0.12)',
                  padding: '4px 12px',
                  borderRadius: '20px',
                  fontSize: '13px',
                  color: '#cbd5e1',
                  letterSpacing: '0.5px',
                }}
              >
                {lightboxIndex + 1} of {itemsToRender.length}
              </div>
            </div>
          </div>
        </div>
      )}
    </>
  );
}

