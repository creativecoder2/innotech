'use client';

import React, { useRef } from 'react';
import Link from 'next/link';
import { Swiper, SwiperSlide } from 'swiper/react';
import { Autoplay, Navigation } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/navigation';
import { fallbackTeam } from '@/lib/data';

export default function TeamSection({ config = {}, teamMembers = [] }) {
  const swiperRef = useRef(null);

  const teamConfig = config.teamSection || {};
  const subTitle = teamConfig.subTitle || 'Our Team';
  const title = teamConfig.title || 'Meet Specialist';

  const rawTeam = teamMembers && teamMembers.length > 0 ? teamMembers : fallbackTeam;
  const activeTeam = rawTeam.filter((m) => m.enabled !== false);

  return (
    <section
      className="team-area grey-bg pt-120 pb-80"
      style={{ backgroundImage: `url('/assets/img/shape/shape-bg-01.png')` }}
      data-background="assets/img/shape/shape-bg-01.png"
    >
      <div className="container">
        <div className="row align-items-center">
          <div className="col-lg-8 col-md-8 col-12">
            <div className="tp-section">
              <span className="tp-section__sub-title left-line mb-25">{subTitle}</span>
              <h3 className="tp-section__title mb-75">{title}</h3>
            </div>
          </div>
          <div className="col-lg-4 col-md-4 col-12">
            <div className="tp-team-arrow d-flex align-items-center justify-content-md-end mb-40">
              <div
                className="team-p"
                onClick={() => swiperRef.current?.slidePrev()}
                role="button"
                tabIndex={0}
                aria-label="Previous specialist"
                style={{ cursor: 'pointer' }}
              >
                <i className="fa-regular fa-arrow-left"></i>
              </div>
              <div
                className="team-n"
                onClick={() => swiperRef.current?.slideNext()}
                role="button"
                tabIndex={0}
                aria-label="Next specialist"
                style={{ cursor: 'pointer', marginLeft: '15px' }}
              >
                <i className="fa-regular fa-arrow-right"></i>
              </div>
            </div>
          </div>
        </div>

        <div className="team-active wow fadeInUp" data-wow-delay=".3s">
          <Swiper
            onSwiper={(swiper) => {
              swiperRef.current = swiper;
            }}
            modules={[Autoplay, Navigation]}
            spaceBetween={30}
            slidesPerView={1}
            loop={activeTeam.length > 4}
            autoplay={{
              delay: 4500,
              disableOnInteraction: false,
              pauseOnMouseEnter: true,
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
                spaceBetween: 25,
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
          >
            {activeTeam.map((member, index) => {
              const memberSlug =
                member.slug ||
                member._id ||
                member.name?.toLowerCase().trim().replace(/[^a-z0-9\s-]/g, '').replace(/\s+/g, '-');
              const memberUrl = `/team/${memberSlug}`;
              const social = member.socialLinks || {};

              return (
                <SwiperSlide key={member._id || index}>
                  <div className="tp-team mb-50" style={{ height: '100%', display: 'flex', flexDirection: 'column', backgroundColor: '#ffffff', borderRadius: '8px', overflow: 'hidden', boxShadow: '0 4px 20px rgba(0,0,0,0.05)' }}>
                    <div className="tp-team__thumb fix" style={{ height: '280px', width: '100%', overflow: 'hidden' }}>
                      <Link href={memberUrl} style={{ display: 'block', width: '100%', height: '100%' }}>
                        <img
                          src={member.image}
                          alt={member.name}
                          style={{ width: '100%', height: '100%', objectFit: 'cover' }}
                        />
                      </Link>
                    </div>
                    <div className="tp-team__content" style={{ flex: 1, display: 'flex', flexDirection: 'column', padding: '30px 24px 35px' }}>
                      <h4 className="tp-team__title mb-10" style={{ fontSize: '20px', fontWeight: '700' }}>
                        <Link href={memberUrl}>{member.name}</Link>
                      </h4>
                      <span className="tp-team__position mb-20" style={{ fontSize: '13px', fontWeight: '700', color: '#0E63FF', textTransform: 'uppercase' }}>
                        {member.position}
                      </span>
                      <p style={{ fontSize: '14px', color: '#64748B', lineHeight: '1.6', marginBottom: '24px', flex: 1 }}>
                        {member.bio}
                      </p>
                      <div className="tp-team__social">
                        {social.youtube && social.youtube !== '#' ? (
                          <a className="tp-youtube" href={social.youtube} target="_blank" rel="noreferrer" aria-label="YouTube">
                            <i className="fa-brands fa-youtube"></i>
                          </a>
                        ) : (
                          <Link className="tp-youtube" href={memberUrl} aria-label="YouTube">
                            <i className="fa-brands fa-youtube"></i>
                          </Link>
                        )}
                        {social.twitter && social.twitter !== '#' ? (
                          <a className="tp-twitter" href={social.twitter} target="_blank" rel="noreferrer" aria-label="Twitter">
                            <i className="fa-brands fa-twitter"></i>
                          </a>
                        ) : (
                          <Link className="tp-twitter" href={memberUrl} aria-label="Twitter">
                            <i className="fa-brands fa-twitter"></i>
                          </Link>
                        )}
                        {social.facebook && social.facebook !== '#' ? (
                          <a className="tp-fb" href={social.facebook} target="_blank" rel="noreferrer" aria-label="Facebook">
                            <i className="fa-brands fa-facebook-f"></i>
                          </a>
                        ) : (
                          <Link className="tp-fb" href={memberUrl} aria-label="Facebook">
                            <i className="fa-brands fa-facebook-f"></i>
                          </Link>
                        )}
                        {social.linkedin && social.linkedin !== '#' ? (
                          <a className="tp-skype" href={social.linkedin} target="_blank" rel="noreferrer" aria-label="LinkedIn">
                            <i className="fa-brands fa-linkedin-in"></i>
                          </a>
                        ) : social.skype && social.skype !== '#' ? (
                          <a className="tp-skype" href={social.skype} target="_blank" rel="noreferrer" aria-label="Skype">
                            <i className="fa-brands fa-skype"></i>
                          </a>
                        ) : (
                          <Link className="tp-skype" href={memberUrl} aria-label="View Profile">
                            <i className="fa-brands fa-linkedin-in"></i>
                          </Link>
                        )}
                      </div>
                    </div>
                  </div>
                </SwiperSlide>
              );
            })}
          </Swiper>
        </div>

        {/* Explore More Button linking to /allteams */}
        <div className="row mt-40">
          <div className="col-12 text-center">
            <Link href="/allteams" className="tp-btn">
              EXPLORE MORE
            </Link>
          </div>
        </div>
      </div>
    </section>
  );
}


