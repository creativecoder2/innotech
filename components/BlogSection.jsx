'use client';

import React, { useRef } from 'react';
import Link from 'next/link';
import { Swiper, SwiperSlide } from 'swiper/react';
import { Autoplay, Navigation } from 'swiper/modules';
import { fallbackBlogList, fallbackBlogs } from '@/lib/data';

export default function BlogSection({ config = {}, blogs = [] }) {
  const swiperRef = useRef(null);

  const blogConfig = config.blogSection || {};
  const subTitle = blogConfig.subTitle || 'Waht’s New';
  const title = blogConfig.title || 'Blog & Article';

  const defaultList = fallbackBlogList && fallbackBlogList.length > 0 ? fallbackBlogList : fallbackBlogs;
  const rawBlogs = blogs && blogs.length > 0 ? blogs : defaultList;
  const activeBlogs = rawBlogs.filter((b) => b.enabled !== false);
  const displayBlogs = activeBlogs.length > 0 ? activeBlogs : defaultList;

  return (
    <section
      className="blog-area grey-bg pt-95 pb-80"
      style={{ backgroundImage: `url('/assets/img/shape/shape-bg-01.png')` }}
      data-background="assets/img/shape/shape-bg-01.png"
    >
      <div className="container">
        {/* Section Header with Arrows */}
        <div className="row align-items-center">
          <div className="col-lg-8 col-md-8 col-12">
            <div className="tp-section">
              <span className="tp-section__sub-title left-line mb-20">{subTitle}</span>
              <h3 className="tp-section__title mb-45">{title}</h3>
            </div>
          </div>
          <div className="col-lg-4 col-md-4 col-12">
            <div className="tp-team-arrow d-flex align-items-center justify-content-md-end mb-30">
              <div
                className="team-p"
                onClick={() => swiperRef.current?.slidePrev()}
                role="button"
                tabIndex={0}
                aria-label="Previous blog"
                style={{ cursor: 'pointer' }}
              >
                <i className="fa-regular fa-arrow-left"></i>
              </div>
              <div
                className="team-n"
                onClick={() => swiperRef.current?.slideNext()}
                role="button"
                tabIndex={0}
                aria-label="Next blog"
                style={{ cursor: 'pointer' }}
              >
                <i className="fa-regular fa-arrow-right"></i>
              </div>
            </div>
          </div>
        </div>

        {/* Swiper Slider with Compact, Sleek Box Heights */}
        <div className="blog-active wow fadeInUp" data-wow-delay=".3s">
          <Swiper
            onSwiper={(swiper) => {
              swiperRef.current = swiper;
            }}
            modules={[Autoplay, Navigation]}
            spaceBetween={25}
            slidesPerView={1}
            loop={displayBlogs.length >= 3}
            autoplay={{
              delay: 4500,
              disableOnInteraction: false,
              pauseOnMouseEnter: true,
            }}
            navigation={{
              prevEl: '.team-p',
              nextEl: '.team-n',
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
                slidesPerView: 2,
                spaceBetween: 25,
              },
              1200: {
                slidesPerView: 3,
                spaceBetween: 25,
              },
            }}
            style={{ alignItems: 'stretch' }}
          >
            {displayBlogs.map((blog, idx) => {
              const blogLink = blog.slug ? `/blog/${blog.slug}` : `/blog/${blog._id || ''}`;
              return (
                <SwiperSlide key={blog._id || blog.slug || idx} style={{ height: 'auto', display: 'flex' }}>
                  <div
                    className="tp-blog mb-20"
                    style={{
                      display: 'flex',
                      flexDirection: 'column',
                      width: '100%',
                      height: '100%',
                      backgroundColor: '#ffffff',
                      borderRadius: '5px',
                      overflow: 'hidden',
                      border: '1px solid #ECEEF3',
                      boxShadow: '0 4px 15px rgba(0,0,0,0.03)',
                    }}
                  >
                    {/* Compact Height Cover Thumbnail with Date Badge */}
                    <div
                      className="tp-blog__thumb p-relative fix"
                      style={{ height: '165px', minHeight: '165px', overflow: 'hidden', flexShrink: 0 }}
                    >
                      <Link href={blogLink} style={{ display: 'block', width: '100%', height: '100%' }}>
                        <img
                          src={blog.image || '/assets/img/blog/blog-in-01.jpg'}
                          alt={blog.title}
                          style={{ width: '100%', height: '100%', objectFit: 'cover' }}
                        />
                      </Link>
                      <div className="tp-blog__date text-center">
                        <h4>
                          {blog.dateDay || '26'}
                          <span>{blog.dateMonth || 'Dec'}</span>
                        </h4>
                      </div>
                    </div>

                    {/* Compact Flexbox Body */}
                    <div
                      className="tp-blog__content"
                      style={{
                        padding: '16px 20px 16px',
                        backgroundColor: '#ffffff',
                        display: 'flex',
                        flexDirection: 'column',
                        flexGrow: 1,
                        justifyContent: 'space-between',
                      }}
                    >
                      <div>
                        <div style={{ marginBottom: '8px' }}>
                          <span className="tp-blog__category" style={{ padding: '6px 14px', fontSize: '11px' }}>
                            <Link href="/blog">{blog.category || 'Medicine'}</Link>
                          </span>
                        </div>

                        <h5
                          className="tp-blog__title mb-8"
                          style={{
                            minHeight: '38px',
                            display: '-webkit-box',
                            WebkitLineClamp: 2,
                            WebkitBoxOrient: 'vertical',
                            overflow: 'hidden',
                            lineHeight: '1.3',
                            fontSize: '16px',
                            fontWeight: '700',
                            marginBottom: '6px',
                          }}
                        >
                          <Link href={blogLink}>{blog.title}</Link>
                        </h5>

                        <p
                          style={{
                            minHeight: '36px',
                            display: '-webkit-box',
                            WebkitLineClamp: 2,
                            WebkitBoxOrient: 'vertical',
                            overflow: 'hidden',
                            fontSize: '13px',
                            lineHeight: '1.45',
                            color: 'var(--tp-text-2)',
                            marginBottom: '10px',
                          }}
                        >
                          {blog.excerpt}
                        </p>
                      </div>

                      <div className="tp-blog__btn" style={{ marginTop: 'auto', paddingTop: '2px' }}>
                        <Link href={blogLink} style={{ padding: '6px 16px', fontSize: '12px' }}>
                          Read moRe
                        </Link>
                      </div>
                    </div>
                  </div>
                </SwiperSlide>
              );
            })}
          </Swiper>
        </div>

        {/* Explore All Articles Button with generous bottom spacing */}
        <div className="row mt-50">
          <div className="col-12 text-center">
            <Link href="/blog" className="tp-btn">
              Explore All Articles
            </Link>
          </div>
        </div>
      </div>
    </section>
  );
}



