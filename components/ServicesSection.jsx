'use client';

import React, { useState, useRef } from 'react';
import Link from 'next/link';
import { Swiper, SwiperSlide } from 'swiper/react';
import { Autoplay, Navigation } from 'swiper/modules';
import { fallbackServices } from '@/lib/data';

export default function ServicesSection({ config = {}, initialServices = [] }) {
  const [searchTerm, setSearchTerm] = useState('');
  const swiperRef = useRef(null);

  const servicesConfig = config.servicesSection || {};
  const subTitle = servicesConfig.subTitle || 'our Services';
  const title = servicesConfig.title || 'Service Area';
  const searchPlaceholder = servicesConfig.searchPlaceholder || 'What are you looking for?';

  const rawServices =
    initialServices && initialServices.length > 0 ? initialServices : fallbackServices;
  const activeServices = rawServices.filter((s) => s.enabled !== false && s.isActive !== false);

  const filteredServices = activeServices.filter((service) => {
    const term = searchTerm.toLowerCase();
    return (
      service.title?.toLowerCase().includes(term) ||
      service.description?.toLowerCase().includes(term)
    );
  });

  return (
    <section
      className="services-area pt-95 pb-90 grey-bg mt-60 fix"
      style={{ backgroundImage: `url('/assets/img/shape/shape-bg-01.png')` }}
      data-background="assets/img/shape/shape-bg-01.png"
    >
      <div className="container">
        <div className="row mb-125">
          <div className="col-lg-12">
            <div className="search-form">
              <form onSubmit={(e) => e.preventDefault()}>
                <input
                  type="text"
                  placeholder={searchPlaceholder}
                  value={searchTerm}
                  onChange={(e) => setSearchTerm(e.target.value)}
                />
                <button className="tp-btn search-btn" type="button">
                  Search Here <i className="fa-light fa-magnifying-glass ml-5"></i>
                </button>
              </form>
            </div>
          </div>
        </div>
        <div className="row align-items-center">
          <div className="col-lg-8 col-md-8 col-12">
            <div className="tp-section">
              <span className="tp-section__sub-title left-line mb-20">{subTitle}</span>
              <h3 className="tp-section__title mb-50">{title}</h3>
            </div>
          </div>
          <div className="col-lg-4 col-md-4 col-12">
            <div className="tp-services d-flex align-items-center justify-content-md-end mb-40">
              <div
                className="services-p"
                onClick={() => swiperRef.current?.slidePrev()}
                role="button"
                tabIndex={0}
                aria-label="Previous service"
                style={{ cursor: 'pointer' }}
              >
                <i className="fa-regular fa-arrow-left"></i>
              </div>
              <div
                className="services-n"
                onClick={() => swiperRef.current?.slideNext()}
                role="button"
                tabIndex={0}
                aria-label="Next service"
                style={{ cursor: 'pointer' }}
              >
                <i className="fa-regular fa-arrow-right"></i>
              </div>
            </div>
          </div>
        </div>
        <div className="services-slider wow fadeInUp" data-wow-delay=".3s">
          {filteredServices.length > 0 ? (
            <Swiper
              onSwiper={(swiper) => {
                swiperRef.current = swiper;
              }}
              modules={[Autoplay, Navigation]}
              spaceBetween={30}
              slidesPerView={1}
              loop={filteredServices.length >= 4}
              autoplay={{
                delay: 4000,
                disableOnInteraction: false,
                pauseOnMouseEnter: true,
              }}
              navigation={{
                prevEl: '.services-p',
                nextEl: '.services-n',
              }}
              breakpoints={{
                0: {
                  slidesPerView: 1,
                  spaceBetween: 20,
                },
                576: {
                  slidesPerView: 1,
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
                1200: {
                  slidesPerView: 4,
                  spaceBetween: 30,
                },
              }}
              className="service-active"
            >
              {filteredServices.map((service, index) => {
                const iconColorClass =
                  service.iconTheme === 'pink'
                    ? 'pink-icon'
                    : service.iconTheme === 'green'
                    ? 'green-icon'
                    : service.iconTheme === 'sky'
                    ? 'sky-icon'
                    : '';

                const btnColorClass =
                  service.iconTheme === 'pink'
                    ? 'pink-hexa'
                    : service.iconTheme === 'green'
                    ? 'green-hexa'
                    : service.iconTheme === 'sky'
                    ? 'sky-hexa'
                    : '';

                return (
                  <SwiperSlide key={service._id || service.slug || index} style={{ height: 'auto', display: 'flex' }}>
                    <div
                      className="services-item mb-40"
                      style={{
                        height: '100%',
                        width: '100%',
                        display: 'flex',
                        flexDirection: 'column',
                        justifyContent: 'space-between',
                      }}
                    >
                      <div>
                        <div className={`services-item__icon ${iconColorClass} mb-30`}>
                          <i className={service.iconClass || 'flaticon-hemoglobin-test-meter'}></i>
                        </div>
                        <div className="services-item__content">
                          <h4
                            className="services-item__tp-title mb-30"
                            style={{ minHeight: '52px', display: 'flex', alignItems: 'center' }}
                          >
                            <Link href={service.link || `/services/${service.slug || 'hemoglobin-test'}`} prefetch={true}>
                              {service.title}
                            </Link>
                          </h4>
                          <p style={{ marginBottom: '20px' }}>{service.description}</p>
                        </div>
                      </div>
                      <div className="services-item__btn" style={{ marginTop: 'auto' }}>
                        <Link
                          className={`btn-hexa ${btnColorClass}`}
                          href={service.link || `/services/${service.slug || 'hemoglobin-test'}`}
                          prefetch={true}
                        >
                          <i></i>Read More
                        </Link>
                      </div>
                    </div>
                  </SwiperSlide>
                );
              })}
            </Swiper>
          ) : (
            <div className="col-12 py-4 text-center">
              <p>No services found matching &quot;{searchTerm}&quot;</p>
            </div>
          )}
        </div>
      </div>
    </section>
  );
}

