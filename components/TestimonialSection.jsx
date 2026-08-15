'use client';

import React, { useRef } from 'react';
import { Swiper, SwiperSlide } from 'swiper/react';
import { Autoplay, Navigation } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/navigation';
import { fallbackTestimonials } from '@/lib/data';

export default function TestimonialSection({ config = {}, testimonials = [] }) {
  const swiperRef = useRef(null);

  const testiConfig = config.testimonialSection || {};
  const subTitle = testiConfig.subTitle || 'Testimonial';
  const title = testiConfig.title || 'Customer Feedback';

  const rawTestimonials =
    testimonials && testimonials.length > 0 ? testimonials : fallbackTestimonials;
  const activeTestimonials = rawTestimonials.filter((t) => t.enabled !== false);

  return (
    <section
      className="testimonial-area testimonial-bg pt-125 pb-130"
      style={{ backgroundImage: `url('/assets/img/shape/shape-bg-02.png')` }}
      data-background="assets/img/shape/shape-bg-02.png"
    >
      <div className="container">
        <div className="row wow fadeInUp" data-wow-delay=".3s">
          <div className="col-lg-12">
            <div className="tp-section text-center">
              <span className="tp-section__sub-title sub-title-white left-line-white right-line-white mb-25">
                {subTitle}
              </span>
              <h3 className="tp-section__title title-white mb-70">{title}</h3>
            </div>
          </div>
        </div>

        <div className="tp-test-active" style={{ paddingTop: '50px' }}>
          <Swiper
            onSwiper={(swiper) => {
              swiperRef.current = swiper;
            }}
            modules={[Autoplay, Navigation]}
            spaceBetween={30}
            slidesPerView={1}
            loop={activeTestimonials.length >= 3}
            autoplay={{
              delay: 4000,
              disableOnInteraction: false,
              pauseOnMouseEnter: true,
            }}
            style={{ overflow: 'visible', paddingTop: '45px', marginTop: '-45px' }}
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
                slidesPerView: 2,
                spaceBetween: 30,
              },
              1200: {
                slidesPerView: 3,
                spaceBetween: 30,
              },
            }}
          >
            {activeTestimonials.map((item, idx) => (
              <SwiperSlide key={item._id || idx}>
                <div className="tp-testi p-relative mb-70" style={{ position: 'relative', borderRadius: '8px', marginTop: '10px' }}>
                  <div
                    className="tp-testi__avata"
                    style={{
                      position: 'absolute',
                      top: '-40px',
                      left: '50%',
                      transform: 'translateX(-50%)',
                      zIndex: 10,
                      width: '80px',
                      height: '80px',
                    }}
                  >
                    <img
                      src={item.avatar}
                      alt={item.name || 'testimonial-avata'}
                      style={{
                        width: '80px',
                        height: '80px',
                        borderRadius: '50%',
                        objectFit: 'cover',
                        display: 'block',
                        boxShadow: '0 8px 20px rgba(0,0,0,0.15)',
                        border: '3px solid #ffffff',
                      }}
                    />
                  </div>
                  <div className="tp-testi__content text-center">
                    <p>{item.review}</p>
                    <h5 className="tp-testi__avata-title">{item.name}</h5>
                    <span className="tp-testi__ava-position">{item.position}</span>
                  </div>
                </div>
              </SwiperSlide>
            ))}
          </Swiper>
        </div>

        <div className="row text-center">
          <div className="col-lg-12">
            <div className="tp-test-arrow d-flex align-items-center justify-content-center">
              <div
                className="tp-test-prv"
                onClick={() => swiperRef.current?.slidePrev()}
                role="button"
                tabIndex={0}
                aria-label="Previous testimonial"
                style={{ cursor: 'pointer' }}
              >
                <i className="fa-regular fa-arrow-left"></i>
              </div>
              <div
                className="tp-test-nxt ml-20"
                onClick={() => swiperRef.current?.slideNext()}
                role="button"
                tabIndex={0}
                aria-label="Next testimonial"
                style={{ cursor: 'pointer' }}
              >
                <i className="fa-regular fa-arrow-right"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  );
}

