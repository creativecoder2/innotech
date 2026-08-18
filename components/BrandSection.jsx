'use client';

import React from 'react';
import Link from 'next/link';
import { Swiper, SwiperSlide } from 'swiper/react';
import { Autoplay } from 'swiper/modules';

import 'swiper/css';
import 'swiper/css/autoplay';

export default function BrandSection({ config = {}, brands = [] }) {
  const brandsConfig = config.brandsSection || {};
  const items =
    brandsConfig.items && brandsConfig.items.length > 0 ? brandsConfig.items : brands;

  const activeBrands = items.filter((b) => b && b.enabled !== false && (b.image || b.src));

  if (!activeBrands || activeBrands.length === 0) {
    return null;
  }

  const subTitle = brandsConfig.subTitle;
  const title = brandsConfig.title;

  return (
    <section className="brand-area pt-70 pb-70" style={{ backgroundColor: '#f8fafc' }}>
      <div className="container">
        {(subTitle || title) && (
          <div className="row mb-35 wow fadeInUp" data-wow-delay=".2s">
            <div className="col-lg-12">
              <div className="tp-section text-center">
                {subTitle && (
                  <span className="tp-section__sub-title left-line right-line mb-15">
                    {subTitle}
                  </span>
                )}
                {title && <h3 className="tp-section__title mb-0">{title}</h3>}
              </div>
            </div>
          </div>
        )}

        <div className="brand-active p-relative">
          <Swiper
            modules={[Autoplay]}
            spaceBetween={24}
            slidesPerView={2}
            loop={activeBrands.length >= 3}
            autoplay={{
              delay: 2500,
              disableOnInteraction: false,
              pauseOnMouseEnter: true,
            }}
            breakpoints={{
              0: {
                slidesPerView: 2,
                spaceBetween: 16,
              },
              576: {
                slidesPerView: 3,
                spaceBetween: 20,
              },
              768: {
                slidesPerView: 4,
                spaceBetween: 24,
              },
              992: {
                slidesPerView: 5,
                spaceBetween: 24,
              },
              1200: {
                slidesPerView: 5,
                spaceBetween: 30,
              },
            }}
            style={{ padding: '10px 4px' }}
          >
            {activeBrands.map((brand, idx) => {
              const imgUrl = brand.image || brand.src;
              const altText = brand.alt || brand.title || brand.name || 'Brand Logo';

              return (
                <SwiperSlide key={brand.id || brand._id || idx}>
                  <div
                    className="brand-item text-center"
                    style={{
                      height: '110px',
                      display: 'flex',
                      alignItems: 'center',
                      justifyContent: 'center',
                      backgroundColor: '#ffffff',
                      borderRadius: '12px',
                      border: '1px solid #e2e8f0',
                      padding: '16px 20px',
                      boxShadow: '0 2px 8px rgba(0, 0, 0, 0.04)',
                      transition: 'all 0.3s ease',
                      overflow: 'hidden',
                    }}
                    onMouseEnter={(e) => {
                      e.currentTarget.style.transform = 'translateY(-4px)';
                      e.currentTarget.style.boxShadow = '0 12px 24px -6px rgba(0, 0, 0, 0.1)';
                      e.currentTarget.style.borderColor = '#cbd5e1';
                    }}
                    onMouseLeave={(e) => {
                      e.currentTarget.style.transform = 'translateY(0)';
                      e.currentTarget.style.boxShadow = '0 2px 8px rgba(0, 0, 0, 0.04)';
                      e.currentTarget.style.borderColor = '#e2e8f0';
                    }}
                  >
                    <Link
                      href="/partners"
                      style={{
                        display: 'flex',
                        alignItems: 'center',
                        justifyContent: 'center',
                        width: '100%',
                        height: '100%',
                      }}
                    >
                      <img
                        src={imgUrl}
                        alt={altText}
                        style={{
                          maxHeight: '65px',
                          maxWidth: '100%',
                          width: 'auto',
                          height: 'auto',
                          objectFit: 'contain',
                          display: 'block',
                          margin: '0 auto',
                          transition: 'transform 0.3s ease',
                        }}
                        onMouseEnter={(e) => {
                          e.currentTarget.style.transform = 'scale(1.05)';
                        }}
                        onMouseLeave={(e) => {
                          e.currentTarget.style.transform = 'scale(1)';
                        }}
                      />
                    </Link>
                  </div>
                </SwiperSlide>
              );
            })}
          </Swiper>
        </div>
      </div>
    </section>
  );
}


