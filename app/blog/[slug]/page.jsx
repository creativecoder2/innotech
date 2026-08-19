import React from 'react';
import Link from 'next/link';
import { notFound } from 'next/navigation';
import { fallbackBlogPage, fallbackBlogList } from '@/lib/data';
import { getLocalStore } from '@/lib/storage';
import BlogCommentsSection from '@/components/BlogCommentsSection';

export const revalidate = 60;

export async function generateStaticParams() {
  try {
    const local = getLocalStore();
    const items = local?.blogPage?.items?.length ? local.blogPage.items : fallbackBlogList;
    return items.map((b) => ({ slug: b.slug || b._id }));
  } catch (e) {
    return fallbackBlogList.map((b) => ({ slug: b.slug }));
  }
}

export async function generateMetadata({ params }) {
  const { slug } = await params;
  const blog = await getBlogBySlug(slug);
  if (!blog) {
    return { title: 'Article Not Found - INNOTECH MEDICAL' };
  }
  return {
    title: `${blog.title} - INNOTECH MEDICAL PVT LTD`,
    description: blog.excerpt || blog.title,
  };
}

async function getBlogBySlug(slug) {
  try {
    const local = getLocalStore();
    const items = local.blogPage?.items?.length ? local.blogPage.items : fallbackBlogList;
    const found = items.find((b) => b.slug === slug || b._id === slug);
    if (found) return found;
    return items[0] || null;
  } catch (e) {
    return fallbackBlogList.find((b) => b.slug === slug) || fallbackBlogList[0];
  }
}

async function getAllBlogs() {
  try {
    const local = getLocalStore();
    return local.blogPage?.items?.length ? local.blogPage.items : fallbackBlogList;
  } catch (e) {
    return fallbackBlogList;
  }
}

export default async function BlogDetailsPage({ params }) {
  const { slug } = await params;
  const blog = await getBlogBySlug(slug);
  const allBlogs = await getAllBlogs();
  const activeBlogs = allBlogs.filter((b) => b.enabled !== false);
  const recentBlogs = activeBlogs.filter((b) => b.slug !== slug).slice(0, 3);
  const categories = Array.from(new Set(activeBlogs.map((b) => b.category).filter(Boolean)));

  if (!blog) {
    notFound();
  }

  const tagsArray = Array.isArray(blog.tags)
    ? blog.tags
    : typeof blog.tags === 'string'
    ? blog.tags.split(',').map((t) => t.trim())
    : ['Biomedical', 'Healthcare'];

  return (
    <>
      {/* 1. Breadcrumb Banner */}
      <section
        className="breadcrumb__area pt-100 pb-120 breadcrumb__overlay"
        style={{
          backgroundImage: `url('/assets/img/banner/breadcrumb-01.jpg')`,
          backgroundSize: 'cover',
          backgroundPosition: 'center',
        }}
      >
        <div className="container">
          <div className="row align-items-center">
            <div className="col-xl-8 col-lg-7 col-md-7 col-12">
              <div className="tp-breadcrumb">
                <span style={{ color: '#0E63FF', fontWeight: '700', textTransform: 'uppercase', letterSpacing: '1px' }}>
                  {blog.category}
                </span>
                <h2 className="tp-breadcrumb__title" style={{ fontSize: '32px', marginTop: '10px' }}>
                  {blog.title}
                </h2>
              </div>
            </div>
            <div className="col-xl-4 col-lg-5 col-md-5 col-12">
              <div className="tp-breadcrumb__link d-flex align-items-center justify-content-md-end mt-20 mt-md-0">
                <span style={{ color: '#E2E8F0', fontSize: '14px' }}>
                  <Link href="/" style={{ color: '#fff' }}>Home</Link> &gt; <Link href="/blog" style={{ color: '#fff' }}>Blog</Link> &gt; Details
                </span>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* 2. Main Postbox Article Details */}
      <div className="postbox-area pt-120 pb-100">
        <div className="container">
          <div className="row">
            {/* Left Column: Full Article Content */}
            <div className="col-xxl-8 col-xl-8 col-lg-7 col-md-12">
              <div className="postbox__wrapper pr-20">
                <article
                  className="postbox__item format-image mb-50 transition-3"
                  style={{
                    backgroundColor: '#ffffff',
                    borderRadius: '16px',
                    padding: '36px',
                    border: '1px solid #ECEEF3',
                    boxShadow: '0 10px 30px rgba(0,0,0,0.04)',
                  }}
                >
                  {/* Article Hero Image */}
                  <div className="postbox__thumb w-img mb-35">
                    <img
                      src={blog.image || '/assets/img/blog/blog-in-01.jpg'}
                      alt={blog.title}
                      style={{ width: '100%', maxHeight: '480px', objectFit: 'cover', borderRadius: '10px' }}
                    />
                  </div>

                  {/* Meta Bar */}
                  <div className="postbox__meta mb-25">
                    <span>
                      <i className="fa-regular fa-user"></i> By {blog.author || 'Innotech Editorial'}
                    </span>
                    <span>
                      <i className="fa-regular fa-calendar-days"></i> {blog.dateDay || '14'} {blog.dateMonth || 'Aug'}, {blog.dateYear || '2026'}
                    </span>
                    <span>
                      <i className="fa-regular fa-tag"></i> {blog.category || 'Medicine'}
                    </span>
                    {blog.views && (
                      <span>
                        <i className="fa-regular fa-eye"></i> {blog.views} views
                      </span>
                    )}
                  </div>

                  {/* Main Title */}
                  <h2 className="postbox__title mb-30" style={{ fontSize: '32px', fontWeight: '700' }}>
                    {blog.title}
                  </h2>

                  {/* Summary / Excerpt Highlight */}
                  <div
                    style={{
                      backgroundColor: 'var(--tp-icon-blue-light)',
                      borderLeft: '4px solid var(--tp-theme-blue)',
                      padding: '20px 24px',
                      borderRadius: '0 8px 8px 0',
                      marginBottom: '30px',
                      fontSize: '16px',
                      fontStyle: 'italic',
                      color: 'var(--tp-heading-primary)',
                      lineHeight: '1.7',
                    }}
                  >
                    {blog.excerpt}
                  </div>

                  {/* Full Article Content */}
                  <div className="postbox__text mb-40" style={{ whiteSpace: 'pre-line' }}>
                    <p style={{ fontSize: '16px', lineHeight: '1.8' }}>
                      {blog.content || blog.excerpt}
                    </p>
                  </div>

                  {/* Tags and Social Share Bar */}
                  <div
                    className="postbox__tag-border"
                    style={{
                      paddingTop: '25px',
                      borderTop: '1px solid #ECEEF3',
                      display: 'flex',
                      justifyContent: 'space-between',
                      alignItems: 'center',
                      flexWrap: 'wrap',
                      gap: '15px',
                    }}
                  >
                    {/* Tags List */}
                    <div style={{ display: 'flex', alignItems: 'center', gap: '8px', flexWrap: 'wrap' }}>
                      <span style={{ fontSize: '14px', fontWeight: '700', color: '#171151' }}>Tags:</span>
                      {tagsArray.map((tag, tIdx) => (
                        <span
                          key={tIdx}
                          style={{
                            padding: '4px 12px',
                            borderRadius: '20px',
                            backgroundColor: '#F1F5F9',
                            color: '#475569',
                            fontSize: '13px',
                            fontWeight: '600',
                          }}
                        >
                          #{tag}
                        </span>
                      ))}
                    </div>

                    {/* Social Share Icons */}
                    <div style={{ display: 'flex', alignItems: 'center', gap: '8px' }}>
                      <span style={{ fontSize: '14px', fontWeight: '700', color: '#171151', marginRight: '4px' }}>Share:</span>
                      {(() => {
                        const customSocial = blog.socialLinks || {};
                        const pageUrl = `http://localhost:3000/blog/${blog.slug}`;
                        const pageTitle = encodeURIComponent(blog.title || 'Innotech Medical Article');

                        const linkedinUrl = customSocial.linkedin || `https://www.linkedin.com/shareArticle?mini=true&url=${encodeURIComponent(pageUrl)}`;
                        const twitterUrl = customSocial.twitter || `https://twitter.com/intent/tweet?text=${pageTitle}&url=${encodeURIComponent(pageUrl)}`;
                        const facebookUrl = customSocial.facebook || `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(pageUrl)}`;
                        const instagramUrl = customSocial.instagram;
                        const youtubeUrl = customSocial.youtube;

                        const iconBtnStyle = {
                          width: '36px',
                          height: '36px',
                          borderRadius: '50%',
                          backgroundColor: '#EFF6FF',
                          color: '#0E63FF',
                          display: 'inline-flex',
                          alignItems: 'center',
                          justifyContent: 'center',
                          textDecoration: 'none',
                          fontSize: '14px',
                          transition: 'all 0.2s',
                        };

                        return (
                          <>
                            {linkedinUrl && (
                              <a
                                href={linkedinUrl}
                                target="_blank"
                                rel="noreferrer"
                                aria-label="LinkedIn"
                                title="LinkedIn"
                                style={iconBtnStyle}
                              >
                                <i className="fa-brands fa-linkedin-in"></i>
                              </a>
                            )}
                            {twitterUrl && (
                              <a
                                href={twitterUrl}
                                target="_blank"
                                rel="noreferrer"
                                aria-label="Twitter / X"
                                title="Twitter / X"
                                style={iconBtnStyle}
                              >
                                <i className="fa-brands fa-twitter"></i>
                              </a>
                            )}
                            {facebookUrl && (
                              <a
                                href={facebookUrl}
                                target="_blank"
                                rel="noreferrer"
                                aria-label="Facebook"
                                title="Facebook"
                                style={iconBtnStyle}
                              >
                                <i className="fa-brands fa-facebook-f"></i>
                              </a>
                            )}
                            {instagramUrl && (
                              <a
                                href={instagramUrl}
                                target="_blank"
                                rel="noreferrer"
                                aria-label="Instagram"
                                title="Instagram"
                                style={iconBtnStyle}
                              >
                                <i className="fa-brands fa-instagram"></i>
                              </a>
                            )}
                            {youtubeUrl && (
                              <a
                                href={youtubeUrl}
                                target="_blank"
                                rel="noreferrer"
                                aria-label="YouTube"
                                title="YouTube"
                                style={iconBtnStyle}
                              >
                                <i className="fa-brands fa-youtube"></i>
                              </a>
                            )}
                          </>
                        );
                      })()}
                    </div>
                  </div>
                </article>

                {/* Approved Comments and Comment Submission Form */}
                <BlogCommentsSection blogSlug={blog.slug} blogTitle={blog.title} />
              </div>
            </div>

            {/* Right Column: Theme Native Sidebar */}
            <div className="col-xxl-4 col-xl-4 col-lg-5 col-md-12">
              <div className="sidebar__wrapper">
                {/* 1. Recent Articles Widget */}
                {recentBlogs.length > 0 && (
                  <div className="sidebar__widget mb-40">
                    <h3 className="sidebar__widget-title mb-25">Recent Articles</h3>
                    <div className="sidebar__widget-content">
                      <div className="rc__post-wrapper">
                        {recentBlogs.map((recent, rIdx) => {
                          const rLink = recent.slug ? `/blog/${recent.slug}` : `/blog/${recent._id || ''}`;
                          return (
                            <div key={rIdx} className="rc__post d-flex align-items-center mb-20">
                              <div className="rc__post-thumb">
                                <Link href={rLink}>
                                  <img
                                    src={recent.image || '/assets/img/blog/blog-in-01.jpg'}
                                    alt={recent.title}
                                    style={{ width: '75px', height: '75px', objectFit: 'cover', borderRadius: '6px' }}
                                  />
                                </Link>
                              </div>
                              <div className="rc__content">
                                <div className="rc__meta">
                                  <span>
                                    {recent.dateDay || '14'} {recent.dateMonth || 'Aug'}, {recent.dateYear || '2026'}
                                  </span>
                                </div>
                                <h3 className="rc__post-title">
                                  <Link href={rLink}>{recent.title}</Link>
                                </h3>
                              </div>
                            </div>
                          );
                        })}
                      </div>
                    </div>
                  </div>
                )}

                {/* 2. Categories Widget */}
                <div className="sidebar__widget mb-40">
                  <h3 className="sidebar__widget-title mb-25">Categories</h3>
                  <div className="sidebar__widget-content">
                    <ul>
                      {categories.map((cat, i) => {
                        const count = activeBlogs.filter((b) => b.category?.toLowerCase() === cat.toLowerCase()).length;
                        return (
                          <li key={i}>
                            <Link href="/blog">
                              {cat} <span>({count})</span>
                            </Link>
                          </li>
                        );
                      })}
                    </ul>
                  </div>
                </div>

                {/* 3. Inquiry CTA Card */}
                <div
                  className="sidebar__widget text-center"
                  style={{
                    backgroundColor: 'var(--tp-heading-primary)',
                    borderRadius: '7px',
                    padding: '40px 25px',
                    color: 'var(--tp-common-white)',
                  }}
                >
                  <span
                    style={{
                      display: 'inline-block',
                      height: '60px',
                      width: '60px',
                      lineHeight: '60px',
                      borderRadius: '50%',
                      backgroundColor: 'rgba(255,255,255,0.1)',
                      color: 'var(--tp-theme-blue)',
                      fontSize: '24px',
                      marginBottom: '20px',
                    }}
                  >
                    <i className="fa-regular fa-phone-volume"></i>
                  </span>
                  <h4 style={{ color: 'var(--tp-common-white)', fontSize: '20px', fontWeight: '700', marginBottom: '10px' }}>
                    Contact Medical Specialist
                  </h4>
                  <p style={{ color: 'rgba(255,255,255,0.7)', fontSize: '14px', marginBottom: '25px' }}>
                    Need formal quotation or biomedical equipment integration support?
                  </p>
                  <Link href="/contact" className="tp-btn">
                    Inquire Online
                  </Link>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </>
  );
}
