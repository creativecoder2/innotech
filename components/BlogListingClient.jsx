'use client';

import React, { useState, useRef, useMemo } from 'react';
import Link from 'next/link';

export default function BlogListingClient({ banner = {}, initialBlogs = [], categories = [] }) {
  const [searchTerm, setSearchTerm] = useState('');
  const [selectedCategory, setSelectedCategory] = useState('All');
  const [selectedTag, setSelectedTag] = useState('');
  const [currentPage, setCurrentPage] = useState(1);
  const itemsPerPage = 3;
  const articlesTopRef = useRef(null);

  const activeBlogs = useMemo(() => {
    return (initialBlogs || []).filter((b) => b.enabled !== false);
  }, [initialBlogs]);

  // Extract all unique tags dynamically
  const allTags = useMemo(() => {
    const tagsSet = new Set();
    activeBlogs.forEach((blog) => {
      if (Array.isArray(blog.tags)) {
        blog.tags.forEach((t) => t && tagsSet.add(t.trim()));
      } else if (typeof blog.tags === 'string') {
        blog.tags.split(',').forEach((t) => t && tagsSet.add(t.trim()));
      }
    });
    return Array.from(tagsSet);
  }, [activeBlogs]);

  // Dynamic filter for search term, category, and tag
  const filteredBlogs = useMemo(() => {
    return activeBlogs.filter((blog) => {
      const q = searchTerm.toLowerCase().trim();
      const matchesSearch =
        !q ||
        blog.title?.toLowerCase().includes(q) ||
        blog.excerpt?.toLowerCase().includes(q) ||
        blog.content?.toLowerCase().includes(q) ||
        blog.author?.toLowerCase().includes(q) ||
        blog.category?.toLowerCase().includes(q) ||
        (Array.isArray(blog.tags) && blog.tags.some((t) => t.toLowerCase().includes(q)));

      const matchesCategory =
        selectedCategory === 'All' ||
        blog.category?.toLowerCase() === selectedCategory.toLowerCase();

      const matchesTag =
        !selectedTag ||
        (Array.isArray(blog.tags) &&
          blog.tags.some((t) => t.toLowerCase() === selectedTag.toLowerCase())) ||
        (typeof blog.tags === 'string' &&
          blog.tags.toLowerCase().includes(selectedTag.toLowerCase()));

      return matchesSearch && matchesCategory && matchesTag;
    });
  }, [activeBlogs, searchTerm, selectedCategory, selectedTag]);

  // Pagination calculation
  const totalItems = filteredBlogs.length;
  const totalPages = Math.max(1, Math.ceil(totalItems / itemsPerPage));
  const validCurrentPage = Math.min(currentPage, totalPages);
  const startIndex = (validCurrentPage - 1) * itemsPerPage;
  const paginatedBlogs = filteredBlogs.slice(startIndex, startIndex + itemsPerPage);

  const handlePageChange = (newPage) => {
    if (newPage < 1 || newPage > totalPages) return;
    setCurrentPage(newPage);
    if (articlesTopRef.current) {
      articlesTopRef.current.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
  };

  const handleCategorySelect = (cat) => {
    setSelectedCategory(cat);
    setSelectedTag('');
    setCurrentPage(1);
    if (articlesTopRef.current) {
      articlesTopRef.current.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
  };

  const handleTagSelect = (tag) => {
    setSelectedTag((prev) => (prev === tag ? '' : tag));
    setCurrentPage(1);
    if (articlesTopRef.current) {
      articlesTopRef.current.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
  };

  const handleSearchChange = (e) => {
    setSearchTerm(e.target.value);
    setCurrentPage(1);
  };

  const handleResetFilters = () => {
    setSearchTerm('');
    setSelectedCategory('All');
    setSelectedTag('');
    setCurrentPage(1);
  };

  const recentBlogs = activeBlogs.slice(0, 3);

  return (
    <>
      {/* 1. Breadcrumb Banner with Theme Structure */}
      {banner.enabled !== false && (
        <section
          className="breadcrumb__area pt-100 pb-120 breadcrumb__overlay"
          style={{
            backgroundImage: `url(${banner.bgImage || '/assets/img/banner/breadcrumb-01.jpg'})`,
            backgroundSize: 'cover',
            backgroundPosition: 'center',
          }}
        >
          <div className="container">
            <div className="row align-items-center">
              <div className="col-xl-7 col-lg-7 col-md-12 col-12">
                <div className="tp-breadcrumb">
                  <h2 className="tp-breadcrumb__title">{banner.title || 'Blog & Articles'}</h2>
                  {banner.subTitle && (
                    <p style={{ color: 'rgba(255,255,255,0.85)', marginTop: '10px', fontSize: '16px', fontWeight: '500' }}>
                      {banner.subTitle}
                    </p>
                  )}
                </div>
              </div>
              <div className="col-xl-5 col-lg-5 col-md-12 col-12">
                <div className="tp-breadcrumb__link d-flex align-items-center justify-content-lg-end mt-20 mt-lg-0">
                  <span style={{ color: 'rgba(255,255,255,0.8)', fontSize: '15px' }}>
                    <Link href="/" style={{ color: '#ffffff' }}>Home</Link> &gt; Blog & News
                  </span>
                </div>
              </div>
            </div>
          </div>
        </section>
      )}

      {/* 2. Main Postbox Articles Area */}
      <div className="postbox-area pt-120 pb-100" ref={articlesTopRef}>
        <div className="container">
          <div className="row">
            {/* Left Column: Postbox Feed & Dynamic Pagination */}
            <div className="col-xxl-8 col-xl-8 col-lg-7 col-md-12">
              <div className="postbox__wrapper pr-20">
                {/* Active Filter Status Bar */}
                <div
                  className="d-flex align-items-center justify-content-between flex-wrap mb-40 p-3"
                  style={{
                    backgroundColor: 'var(--tp-grey-1)',
                    borderRadius: '7px',
                    border: '1px solid var(--tp-border-primary)',
                  }}
                >
                  <div style={{ fontSize: '14px', color: 'var(--tp-text-body)', fontWeight: '600' }}>
                    Showing <span style={{ color: 'var(--tp-theme-blue)' }}>{totalItems === 0 ? 0 : startIndex + 1}</span> -{' '}
                    <span style={{ color: 'var(--tp-theme-blue)' }}>{Math.min(startIndex + itemsPerPage, totalItems)}</span> of{' '}
                    <span style={{ color: 'var(--tp-theme-blue)' }}>{totalItems}</span> Articles
                    {selectedCategory !== 'All' && <span> in <strong>"{selectedCategory}"</strong></span>}
                    {selectedTag && <span> tagged with <strong>"#{selectedTag}"</strong></span>}
                    {searchTerm && <span> matching <strong>"{searchTerm}"</strong></span>}
                  </div>

                  {(selectedCategory !== 'All' || selectedTag || searchTerm) && (
                    <button
                      onClick={handleResetFilters}
                      type="button"
                      style={{
                        background: 'none',
                        border: 'none',
                        color: 'var(--tp-theme-pink)',
                        fontSize: '13px',
                        fontWeight: '700',
                        cursor: 'pointer',
                      }}
                    >
                      Clear All Filters ✕
                    </button>
                  )}
                </div>

                {/* Articles List */}
                {paginatedBlogs.length > 0 ? (
                  paginatedBlogs.map((blog, idx) => {
                    const blogLink = blog.slug ? `/blog/${blog.slug}` : `/blog/${blog._id || ''}`;
                    return (
                      <article key={blog._id || blog.slug || idx} className="postbox__item format-image mb-50 transition-3">
                        <div className="postbox__thumb w-img mb-30">
                          <Link href={blogLink}>
                            <img
                              src={blog.image || '/assets/img/blog/blog-in-01.jpg'}
                              alt={blog.title}
                              style={{ width: '100%', maxHeight: '400px', objectFit: 'cover', borderRadius: '10px' }}
                            />
                          </Link>
                        </div>
                        <div className="postbox__content">
                          <div className="postbox__meta mb-15">
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
                          <h3 className="postbox__title mb-20">
                            <Link href={blogLink}>{blog.title}</Link>
                          </h3>
                          <div className="postbox__text mb-30">
                            <p>{blog.excerpt}</p>
                          </div>
                          <div className="postbox__read-more">
                            <Link href={blogLink} className="tp-btn">
                              Read More <i className="fa-regular fa-arrow-right ml-10"></i>
                            </Link>
                          </div>
                        </div>
                      </article>
                    );
                  })
                ) : (
                  <div
                    className="text-center py-5 px-3 mb-50"
                    style={{
                      backgroundColor: 'var(--tp-grey-1)',
                      borderRadius: '10px',
                      border: '1px solid var(--tp-border-primary)',
                    }}
                  >
                    <i
                      className="fa-light fa-magnifying-glass mb-20"
                      style={{ fontSize: '42px', color: 'var(--tp-text-2)', display: 'block' }}
                    ></i>
                    <h4 style={{ color: 'var(--tp-heading-primary)', fontSize: '20px', fontWeight: '700', marginBottom: '8px' }}>
                      No Matching Articles Found
                    </h4>
                    <p style={{ color: 'var(--tp-text-2)', fontSize: '14px', marginBottom: '20px' }}>
                      Try adjusting your keywords, selecting a different category, or resetting all filters.
                    </p>
                    <button onClick={handleResetFilters} className="tp-btn" type="button">
                      Reset All Filters
                    </button>
                  </div>
                )}

                {/* 3. Theme Native Basic Pagination */}
                {totalItems > itemsPerPage && (
                  <div className="basic-pagination text-center mt-30">
                    <ul>
                      {/* Prev Button */}
                      <li>
                        <button
                          onClick={() => handlePageChange(validCurrentPage - 1)}
                          disabled={validCurrentPage === 1}
                          style={{
                            cursor: validCurrentPage === 1 ? 'not-allowed' : 'pointer',
                            opacity: validCurrentPage === 1 ? 0.5 : 1,
                          }}
                          aria-label="Previous Page"
                        >
                          <i className="fa-light fa-arrow-left-long"></i>
                        </button>
                      </li>

                      {/* Page Numbers */}
                      {Array.from({ length: totalPages }, (_, i) => i + 1).map((pageNum) => (
                        <li key={pageNum}>
                          <button
                            onClick={() => handlePageChange(pageNum)}
                            className={pageNum === validCurrentPage ? 'current' : ''}
                          >
                            {pageNum < 10 ? `0${pageNum}` : pageNum}
                          </button>
                        </li>
                      ))}

                      {/* Next Button */}
                      <li>
                        <button
                          onClick={() => handlePageChange(validCurrentPage + 1)}
                          disabled={validCurrentPage === totalPages}
                          style={{
                            cursor: validCurrentPage === totalPages ? 'not-allowed' : 'pointer',
                            opacity: validCurrentPage === totalPages ? 0.5 : 1,
                          }}
                          aria-label="Next Page"
                        >
                          <i className="fa-light fa-arrow-right-long"></i>
                        </button>
                      </li>
                    </ul>
                  </div>
                )}
              </div>
            </div>

            {/* Right Column: Theme Native Sidebar */}
            <div className="col-xxl-4 col-xl-4 col-lg-5 col-md-12">
              <div className="sidebar__wrapper">
                {/* 1. Search Widget */}
                <div className="sidebar__widget mb-40">
                  <h3 className="sidebar__widget-title mb-25">Search Articles</h3>
                  <div className="sidebar__search">
                    <form onSubmit={(e) => e.preventDefault()}>
                      <div className="sidebar__search-input-2 p-relative">
                        <input
                          type="text"
                          placeholder="Search Here"
                          value={searchTerm}
                          onChange={handleSearchChange}
                        />
                        <button type="button" aria-label="Search">
                          <i className="fa-solid fa-magnifying-glass"></i>
                        </button>
                      </div>
                    </form>
                  </div>
                </div>

                {/* 2. Categories Widget */}
                <div className="sidebar__widget mb-40">
                  <h3 className="sidebar__widget-title mb-25">Categories</h3>
                  <div className="sidebar__widget-content">
                    <ul>
                      <li>
                        <a
                          href="#categories"
                          onClick={(e) => {
                            e.preventDefault();
                            handleCategorySelect('All');
                          }}
                          style={{
                            backgroundColor: selectedCategory === 'All' ? 'var(--tp-icon-blue-light)' : 'transparent',
                            color: selectedCategory === 'All' ? 'var(--tp-theme-blue)' : 'var(--tp-text-2)',
                            borderColor: selectedCategory === 'All' ? 'var(--tp-theme-blue)' : '#F2F5FA',
                          }}
                        >
                          All Categories <span>({activeBlogs.length})</span>
                        </a>
                      </li>
                      {categories.map((cat, i) => {
                        const count = activeBlogs.filter((b) => b.category?.toLowerCase() === cat.toLowerCase()).length;
                        const isSelected = selectedCategory.toLowerCase() === cat.toLowerCase();
                        return (
                          <li key={i}>
                            <a
                              href="#categories"
                              onClick={(e) => {
                                e.preventDefault();
                                handleCategorySelect(cat);
                              }}
                              style={{
                                backgroundColor: isSelected ? 'var(--tp-icon-blue-light)' : 'transparent',
                                color: isSelected ? 'var(--tp-theme-blue)' : 'var(--tp-text-2)',
                                borderColor: isSelected ? 'var(--tp-theme-blue)' : '#F2F5FA',
                              }}
                            >
                              {cat} <span>({count})</span>
                            </a>
                          </li>
                        );
                      })}
                    </ul>
                  </div>
                </div>

                {/* 3. Recent Articles Widget */}
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

                {/* 4. Popular Tags Widget */}
                {allTags.length > 0 && (
                  <div className="sidebar__widget mb-40">
                    <h3 className="sidebar__widget-title mb-25">Popular Tags</h3>
                    <div className="sidebar__widget-content">
                      <div className="tagcloud">
                        {allTags.map((tag, tIdx) => {
                          const isTagSelected = selectedTag.toLowerCase() === tag.toLowerCase();
                          return (
                            <a
                              key={tIdx}
                              href="#tags"
                              onClick={(e) => {
                                e.preventDefault();
                                handleTagSelect(tag);
                              }}
                              style={{
                                backgroundColor: isTagSelected ? 'var(--tp-theme-secondary)' : 'transparent',
                                color: isTagSelected ? 'var(--tp-common-white)' : 'var(--tp-text-2)',
                                borderColor: isTagSelected ? 'var(--tp-theme-secondary)' : '#F2F4F6',
                              }}
                            >
                              {tag}
                            </a>
                          );
                        })}
                      </div>
                    </div>
                  </div>
                )}

                {/* 5. Helpline Callout Widget in Theme Style */}
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
                    Need Medical Assistance?
                  </h4>
                  <p style={{ color: 'rgba(255,255,255,0.7)', fontSize: '14px', marginBottom: '25px' }}>
                    Contact our 24/7 biomedical engineering desk for rapid equipment support.
                  </p>
                  <a href="tel:+923316699992" className="tp-btn">
                    +92 331 6699992
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </>
  );
}

