'use client';

import React from 'react';
import Link from 'next/link';
import { Swiper, SwiperSlide } from 'swiper/react';
import { Autoplay } from 'swiper/modules';

export default function BrandSection({ config = {}, brands = [] }) {
  const brandsConfig = config.brandsSection || {};
  const items =
    brandsConfig.items && brandsConfig.items.length > 0 ? brandsConfig.items : brands;

  const activeBrands = items.filter((b) => b.enabled !== false);

  return (
    <div className="brand-area pt-130 pb-130">
      <div className="container">
        <div className="brand-active">
          <Swiper
            modules={[Autoplay]}
            spaceBetween={30}
            slidesPerView={2}
            loop={activeBrands.length >= 4}
            autoplay={{
              delay: 2500,
              disableOnInteraction: false,
              pauseOnMouseEnter: true,
            }}
            breakpoints={{
              0: {
                slidesPerView: 2,
                spaceBetween: 20,
              },
              576: {
                slidesPerView: 3,
                spaceBetween: 20,
              },
              768: {
                slidesPerView: 3,
                spaceBetween: 30,
              },
              992: {
                slidesPerView: 4,
                spaceBetween: 30,
              },
              1200: {
                slidesPerView: 5,
                spaceBetween: 30,
              },
            }}
          >
            {activeBrands.map((brand, idx) => (
              <SwiperSlide key={brand.id || idx}>
                <div className="brand-item text-center">
                  <Link href="/partners">
                    <img src={brand.image} alt={brand.alt || 'brand'} />
                  </Link>
                </div>
              </SwiperSlide>
            ))}
          </Swiper>
        </div>
      </div>
    </div>
  );
}

