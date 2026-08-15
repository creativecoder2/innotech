'use client';

import React, { useState, useEffect, useCallback, useMemo } from 'react';
import Link from 'next/link';
import { fallbackSiteConfig } from '@/lib/data';

const DEFAULT_GALLERY_ITEMS = [
  {
    id: 1,
    title: 'COVID ANALYSIS & DIAGNOSTICS',
    tag: 'Radiologist',
    image: '/assets/img/gallery/gal-thum-01.jpg',
    link: '/services',
    enabled: true,
    description: 'High-throughput automated molecular diagnostics and real-time PCR testing workstations.',
  },
  {
    id: 2,
    title: 'HIV ANALYSIS & TESTING SUITE',
    tag: 'Anaesthetist',
    image: '/assets/img/gallery/gal-thum-02.jpg',
    link: '/services',
    enabled: true,
    description: 'Advanced immunology and automated chemiluminescence analyzers for precision diagnostics.',
  },
  {
    id: 3,
    title: 'ZYRTEC ALLERGY & CELL ANALYSIS',
    tag: 'Gynaecologist',
    image: '/assets/img/gallery/gal-thum-03.jpg',
    link: '/services',
    enabled: true,
    description: 'Comprehensive flow cytometry and automated cell counting systems for clinical pathology.',
  },
  {
    id: 4,
    title: 'ASTHMA & PULMONARY APPARATUS',
    tag: 'Genetics',
    image: '/assets/img/gallery/gal-thum-04.jpg',
    link: '/services',
    enabled: true,
    description: 'State-of-the-art respiratory ventilators, spirometry stations, and ICU monitoring systems.',
  },
  {
    id: 5,
    title: 'NEUROLOGICAL & BRAIN ANALYSIS',
    tag: 'Forensic',
    image: '/assets/img/gallery/gal-thum-05.jpg',
    link: '/services',
    enabled: true,
    description: 'Diagnostic EEG, neuro-imaging stations, and digital clinical calibration equipment.',
  },
  {
    id: 6,
    title: 'ADVANCED BIOMEDICAL MICROSCOPY',
    tag: 'Pathology',
    image: '/assets/img/gallery/project-01.jpg',
    link: '/services',
    enabled: true,
    description: 'Ultra-resolution optical and fluorescence microscopes with digital image acquisition.',
  },
  {
    id: 7,
    title: 'TURNKEY OT & SURGICAL INTEGRATION',
    tag: 'Operating Room',
    image: '/assets/img/gallery/project-02.jpg',
    link: '/services',
    enabled: true,
    description: 'Integrated surgical pendant systems, shadowless LED surgical lights, and modular setups.',
  },
  {
    id: 8,
    title: 'AUTOMATED HEMATOLOGY PIPELINE',
    tag: 'Laboratory',
    image: '/assets/img/gallery/gallery-thumb-bg-1.jpg',
    link: '/services',
    enabled: true,
    description: '5-part differential hematology analyzers delivering rapid, laboratory-grade blood profiling.',
  },
];

export default function GalleryPage() {
  const [galleryItems, setGalleryItems] = useState(DEFAULT_GALLERY_ITEMS);
  const [galleryConfig, setGalleryConfig] = useState({
    title: 'INNOTECH Gallery',
    subTitle: 'WORK GALLERY',
    description: 'Explore our advanced medical equipment installations, diagnostic technologies, and turnkey hospital projects across Pakistan.',
    bannerImage: '/assets/img/banner/breadcrumb-01.jpg',
    bannerTitle: 'INNOTECH Gallery',
    bannerSubTitle: 'Explore our advanced medical equipment installations, diagnostic technologies, and turnkey hospital projects across Pakistan.',
    bannerBreadcrumb: 'Gallery',
    pageSectionSubTitle: 'WORK GALLERY',
    pageSectionTitle: 'Precision Medical & Laboratory Works',
    pageDescription: 'Discover our portfolio of FDA, CE, and ISO certified biomedical device deployments, hospital turnkey setups, and laboratory automation.',
  });
  const [loading, setLoading] = useState(true);
  const [activeCategory, setActiveCategory] = useState('ALL');
  const [lightboxIndex, setLightboxIndex] = useState(null);

  // Quick Inquiry Form State
  const [formData, setFormData] = useState({ name: '', email: '', phone: '', subject: '', message: '' });
  const [submitting, setSubmitting] = useState(false);
  const [submitResult, setSubmitResult] = useState({ type: '', msg: '' });

  useEffect(() => {
    fetchGalleryData();
  }, []);

  const fetchGalleryData = async () => {
    try {
      const res = await fetch('/api/admin/site-config');
      const data = await res.json();
      if (data.data?.gallerySection) {
        const gs = data.data.gallerySection;
        setGalleryConfig({
          title: gs.title || 'INNOTECH Gallery',
          subTitle: gs.subTitle || 'WORK GALLERY',
          description: gs.description || 'Explore our advanced medical equipment installations, diagnostic technologies, and turnkey hospital projects across Pakistan.',
          bannerImage: gs.bannerImage || '/assets/img/banner/breadcrumb-01.jpg',
          bannerTitle: gs.bannerTitle || gs.title || 'INNOTECH Gallery',
          bannerSubTitle: gs.bannerSubTitle || gs.description || 'Explore our advanced medical equipment installations, diagnostic technologies, and turnkey hospital projects across Pakistan.',
          bannerBreadcrumb: gs.bannerBreadcrumb || 'Gallery',
          pageSectionSubTitle: gs.pageSectionSubTitle || gs.subTitle || 'WORK GALLERY',
          pageSectionTitle: gs.pageSectionTitle || 'Precision Medical & Laboratory Works',
          pageDescription: gs.pageDescription || 'Discover our portfolio of FDA, CE, and ISO certified biomedical device deployments, hospital turnkey setups, and laboratory automation.',
        });

        if (Array.isArray(gs.items) && gs.items.length > 0) {
          const enabledItems = gs.items.filter((i) => i.enabled !== false);
          if (enabledItems.length > 0) {
            setGalleryItems(enabledItems);
          }
        }
      }
    } catch (err) {
      console.error('Error loading gallery data:', err);
    } finally {
      setLoading(false);
    }
  };

  // Extract unique categories for filter tabs
  const categories = useMemo(() => {
    const tags = new Set();
    galleryItems.forEach((item) => {
      if (item.tag && item.tag.trim()) {
        tags.add(item.tag.trim());
      }
    });
    return ['ALL', ...Array.from(tags)];
  }, [galleryItems]);

  // Filter items based on active tab
  const filteredItems = useMemo(() => {
    if (activeCategory === 'ALL') return galleryItems;
    return galleryItems.filter(
      (item) => item.tag && item.tag.toLowerCase() === activeCategory.toLowerCase()
    );
  }, [galleryItems, activeCategory]);

  // Lightbox handlers
  const handlePrev = useCallback(() => {
    setLightboxIndex((prev) => {
      if (prev === null) return null;
      return prev === 0 ? filteredItems.length - 1 : prev - 1;
    });
  }, [filteredItems.length]);

  const handleNext = useCallback(() => {
    setLightboxIndex((prev) => {
      if (prev === null) return null;
      return prev === filteredItems.length - 1 ? 0 : prev + 1;
    });
  }, [filteredItems.length]);

  const handleClose = useCallback(() => {
    setLightboxIndex(null);
  }, []);

  // Keyboard navigation for Lightbox
  useEffect(() => {
    if (lightboxIndex === null) return;

    const handleKeyDown = (e) => {
      if (e.key === 'Escape') handleClose();
      else if (e.key === 'ArrowLeft') handlePrev();
      else if (e.key === 'ArrowRight') handleNext();
    };

    window.addEventListener('keydown', handleKeyDown);
    return () => window.removeEventListener('keydown', handleKeyDown);
  }, [lightboxIndex, handleClose, handlePrev, handleNext]);

  const currentItem =
    lightboxIndex !== null && filteredItems[lightboxIndex]
      ? filteredItems[lightboxIndex]
      : null;

  // Handle Inquiry Submit
  const handleFormChange = (e) => {
    const { name, value } = e.target;
    if (name === 'phone') {
      setFormData({ ...formData, phone: value.replace(/[^0-9+]/g, '') });
    } else {
      setFormData({ ...formData, [name]: value });
    }
  };

  const handleFormSubmit = async (e) => {
    e.preventDefault();
    if (!formData.name.trim() || !formData.phone.trim()) {
      setSubmitResult({ type: 'error', msg: 'Please enter your name and phone number.' });
      return;
    }
    setSubmitting(true);
    setSubmitResult({ type: '', msg: '' });

    try {
      const res = await fetch('/api/inquiries', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          name: formData.name.trim(),
          phone: formData.phone.trim(),
          email: formData.email.trim() || 'Not Provided',
          subject: formData.subject.trim() || 'Gallery / Equipment Project Inquiry',
          message: formData.message.trim() || 'Inquiry submitted from Gallery Page.',
          source: 'Gallery Page',
        }),
      });
      const data = await res.json();
      if (res.ok && data.success) {
        setSubmitResult({ type: 'success', msg: 'Thank you! Your inquiry has been sent to our biomedical engineering team.' });
        setFormData({ name: '', email: '', phone: '', subject: '', message: '' });
      } else {
        setSubmitResult({ type: 'error', msg: data.message || 'Failed to send message. Please try again.' });
      }
    } catch {
      setSubmitResult({ type: 'error', msg: 'Connection error. Please try again.' });
    } finally {
      setSubmitting(false);
    }
  };

  return (
    <>
      {/* ── 1. Breadcrumb Banner ── */}
      <section
        className="breadcrumb__area pt-100 pb-120 breadcrumb__overlay"
        style={{
          backgroundImage: `url('${galleryConfig.bannerImage}')`,
          backgroundSize: 'cover',
          backgroundPosition: 'center',
        }}
      >
        <div className="container">
          <div className="row align-items-center">
            <div className="col-xl-7 col-lg-12 col-md-12 col-12">
              <div className="tp-breadcrumb">
                <h2 className="tp-breadcrumb__title">{galleryConfig.bannerTitle}</h2>
                {galleryConfig.bannerSubTitle && (
                  <p style={{ color: '#E2E8F0', marginTop: '8px', fontSize: '15px', fontWeight: '500' }}>
                    {galleryConfig.bannerSubTitle}
                  </p>
                )}
              </div>
            </div>
            <div className="col-xl-5 col-lg-12 col-md-12 col-12">
              <div className="tp-breadcrumb__link text-xl-end">
                <span>
                  Innotech : <Link href="/">Home</Link> &gt; <span style={{ color: '#0E63FF', fontWeight: '700' }}>{galleryConfig.bannerBreadcrumb}</span>
                </span>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* ── 2. Gallery Header & Interactive Filter Bar ── */}
      <section
        className="gallery-area grey-bg pt-110 pb-120"
        style={{ backgroundImage: `url('/assets/img/shape/shape-bg-01.png')`, backgroundRepeat: 'repeat' }}
      >
        <div className="container">
          {/* Section Header */}
          <div className="row">
            <div className="col-lg-12">
              <div className="tp-section text-center mb-50">
                <span className="tp-section__sub-title left-line right-line mb-20">
                  {galleryConfig.pageSectionSubTitle}
                </span>
                <h3 className="tp-section__title mb-20">
                  {galleryConfig.pageSectionTitle}
                </h3>
                {galleryConfig.pageDescription && (
                  <p style={{ maxWidth: '680px', margin: '0 auto', color: '#6b6b6b', fontSize: '15px', lineHeight: '1.7' }}>
                    {galleryConfig.pageDescription}
                  </p>
                )}
              </div>
            </div>
          </div>


          {/* Interactive Category Filter Pills */}
          {categories.length > 2 && (
            <div className="row mb-50">
              <div className="col-lg-12">
                <div
                  className="d-flex align-items-center justify-content-center flex-wrap"
                  style={{ gap: '10px' }}
                >
                  {categories.map((cat) => {
                    const isActive = activeCategory === cat;
                    const count =
                      cat === 'ALL'
                        ? galleryItems.length
                        : galleryItems.filter((i) => i.tag && i.tag.toLowerCase() === cat.toLowerCase()).length;

                    return (
                      <button
                        key={cat}
                        onClick={() => setActiveCategory(cat)}
                        style={{
                          border: isActive ? '2px solid #0E63FF' : '1px solid #E2E8F0',
                          backgroundColor: isActive ? '#0E63FF' : '#ffffff',
                          color: isActive ? '#ffffff' : '#171151',
                          padding: '10px 22px',
                          borderRadius: '30px',
                          fontSize: '13px',
                          fontWeight: '700',
                          textTransform: 'uppercase',
                          letterSpacing: '0.6px',
                          cursor: 'pointer',
                          transition: 'all 0.3s ease',
                          boxShadow: isActive
                            ? '0 6px 18px rgba(14, 99, 255, 0.28)'
                            : '0 2px 6px rgba(0,0,0,0.03)',
                          display: 'inline-flex',
                          alignItems: 'center',
                          gap: '8px',
                        }}
                        onMouseEnter={(e) => {
                          if (!isActive) {
                            e.currentTarget.style.backgroundColor = '#EFF6FF';
                            e.currentTarget.style.borderColor = '#0E63FF';
                            e.currentTarget.style.color = '#0E63FF';
                          }
                        }}
                        onMouseLeave={(e) => {
                          if (!isActive) {
                            e.currentTarget.style.backgroundColor = '#ffffff';
                            e.currentTarget.style.borderColor = '#E2E8F0';
                            e.currentTarget.style.color = '#171151';
                          }
                        }}
                      >
                        <span>{cat === 'ALL' ? 'All Works' : cat}</span>
                        <span
                          style={{
                            fontSize: '11px',
                            padding: '2px 7px',
                            borderRadius: '10px',
                            backgroundColor: isActive ? 'rgba(255, 255, 255, 0.25)' : '#F1F5F9',
                            color: isActive ? '#ffffff' : '#64748B',
                            fontWeight: '800',
                          }}
                        >
                          {count}
                        </span>
                      </button>
                    );
                  })}
                </div>
              </div>
            </div>
          )}

          {/* ── 3. VIP Gallery Cards Grid ── */}
          <div className="row">
            {filteredItems.map((item, idx) => (
              <div
                key={item.id || idx}
                className="col-xl-4 col-lg-4 col-md-6 col-12 mb-35"
              >
                {/* Theme standard tp-gallery__item with rounded corners & shadow */}
                <div
                  className="tp-gallery__item p-relative"
                  style={{
                    borderRadius: '10px',
                    overflow: 'hidden',
                    backgroundColor: '#171151',
                    boxShadow: '0 10px 30px rgba(0,0,0,0.08)',
                    height: '380px',
                  }}
                >
                  <div
                    className="tp-gallery__img p-relative"
                    style={{ height: '100%', overflow: 'hidden' }}
                  >
                    <img
                      src={item.image}
                      alt={item.title}
                      style={{
                        width: '100%',
                        height: '100%',
                        objectFit: 'cover',
                        transition: 'transform 0.6s ease',
                      }}
                    />

                    {/* Hexagonal + Icon (theme popup-image) */}
                    <div className="tp-gallery__info">
                      <a
                        className="popup-image"
                        href={item.image}
                        onClick={(e) => {
                          e.preventDefault();
                          setLightboxIndex(idx);
                        }}
                        aria-label={`View ${item.title}`}
                      >
                        <i className="fa-solid fa-plus"></i>
                      </a>
                    </div>
                  </div>

                  {/* Card Bottom Content (Title & Tag) */}
                  <div className="tp-gallery__content">
                    <h4 className="tp-gallery__title" style={{ fontSize: '20px' }}>
                      <a
                        href={item.image}
                        onClick={(e) => {
                          e.preventDefault();
                          setLightboxIndex(idx);
                        }}
                      >
                        {item.title}
                      </a>
                    </h4>
                    <span>
                      <i className="fa-solid fa-tag"></i>
                      <a
                        href="#"
                        onClick={(e) => {
                          e.preventDefault();
                          if (item.tag) setActiveCategory(item.tag);
                        }}
                      >
                        {item.tag || 'Biomedical'}
                      </a>
                    </span>
                  </div>
                </div>
              </div>
            ))}
          </div>

          {filteredItems.length === 0 && (
            <div className="row">
              <div className="col-12 text-center py-5">
                <h4 style={{ color: '#64748B', fontWeight: '600' }}>No gallery works found in this category.</h4>
                <button
                  className="tp-btn mt-20"
                  onClick={() => setActiveCategory('ALL')}
                >
                  View All Works
                </button>
              </div>
            </div>
          )}

          {/* ── 4. Key Highlights Banner ── */}
          <div
            className="mt-60 mb-20"
            style={{
              background: 'linear-gradient(135deg, #171151 0%, #0E63FF 100%)',
              borderRadius: '16px',
              padding: '45px 40px',
              color: '#ffffff',
              boxShadow: '0 20px 40px rgba(14, 99, 255, 0.18)',
            }}
          >
            <div className="row align-items-center">
              <div className="col-lg-8 col-md-12 mb-30 mb-lg-0">
                <span
                  style={{
                    backgroundColor: 'rgba(255, 255, 255, 0.15)',
                    padding: '6px 14px',
                    borderRadius: '20px',
                    fontSize: '12px',
                    fontWeight: '700',
                    textTransform: 'uppercase',
                    letterSpacing: '1px',
                    display: 'inline-block',
                    marginBottom: '14px',
                  }}
                >
                  Certified Medical Solutions
                </span>
                <h3 style={{ fontSize: '28px', fontWeight: '800', color: '#ffffff', marginBottom: '10px' }}>
                  Planning a New Hospital, OT, or Diagnostic Lab?
                </h3>
                <p style={{ color: '#E2E8F0', fontSize: '15px', margin: 0, maxWidth: '600px' }}>
                  Our biomedical engineering experts provide end-to-end turnkey solutions, device procurement, regulatory compliance, and 24/7 technical maintenance.
                </p>
              </div>
              <div className="col-lg-4 col-md-12 text-lg-end">
                <Link
                  href="/contact"
                  className="tp-btn"
                  style={{
                    backgroundColor: '#ffffff',
                    color: '#0E63FF',
                    padding: '14px 34px',
                    fontSize: '14px',
                    fontWeight: '700',
                  }}
                >
                  Book Free Consultation
                </Link>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* ── 5. Full Direct Contact & Support Form (matching theme) ── */}
      <section className="support-area grey-bg pt-125 pb-130" style={{ borderTop: '1px solid #ECEEF3' }}>
        <div className="container">
          <div className="row text-center">
            <div className="col-lg-12 col-md-12 col-12">
              <div className="tp-section">
                <span className="tp-section__sub-title left-line right-line mb-20">Get in touch</span>
                <h3 className="tp-section__title mb-70">Need Technical Assistance?</h3>
              </div>
            </div>
          </div>

          <div className="row justify-content-center">
            <div className="col-xl-10 col-lg-12 col-md-12 col-12">
              <div className="contactform">
                <span
                  style={{
                    fontSize: '13px',
                    fontWeight: '700',
                    color: '#0E63FF',
                    letterSpacing: '1px',
                    textTransform: 'uppercase',
                  }}
                >
                  Direct Inquiry & Project Booking
                </span>
                <h4 className="contactform__title mb-15" style={{ marginTop: '8px' }}>
                  Send us a Message
                </h4>

                {submitResult.msg && (
                  <div
                    style={{
                      padding: '12px 18px',
                      marginBottom: '24px',
                      borderRadius: '8px',
                      fontSize: '14px',
                      fontWeight: '600',
                      backgroundColor: submitResult.type === 'error' ? '#FEEAF1' : '#E7FAF6',
                      color: submitResult.type === 'error' ? '#F72A75' : '#0B9748',
                      border: `1px solid ${submitResult.type === 'error' ? '#FECDD3' : '#A3EAD8'}`,
                    }}
                  >
                    {submitResult.msg}
                  </div>
                )}

                <div className="contactform__list mb-50">
                  <form onSubmit={handleFormSubmit}>
                    <div className="row">
                      <div className="col-lg-6">
                        <div className="contactform__input mb-25">
                          <input
                            name="name"
                            type="text"
                            placeholder="Enter your full name *"
                            value={formData.name}
                            onChange={handleFormChange}
                            required
                          />
                        </div>
                      </div>
                      <div className="col-lg-6">
                        <div className="contactform__input mb-25">
                          <input
                            name="email"
                            type="email"
                            placeholder="Enter your email"
                            value={formData.email}
                            onChange={handleFormChange}
                          />
                        </div>
                      </div>
                      <div className="col-lg-6">
                        <div className="contactform__input mb-25">
                          <input
                            name="phone"
                            type="tel"
                            inputMode="numeric"
                            placeholder="Phone / WhatsApp number *"
                            value={formData.phone}
                            onChange={handleFormChange}
                            required
                          />
                        </div>
                      </div>
                      <div className="col-lg-6">
                        <div className="contactform__input mb-25">
                          <input
                            name="subject"
                            type="text"
                            placeholder="Equipment / Facility name"
                            value={formData.subject}
                            onChange={handleFormChange}
                          />
                        </div>
                      </div>
                      <div className="col-lg-12">
                        <div className="contactform__input mb-25">
                          <textarea
                            name="message"
                            placeholder="Describe your equipment requirement, project scope, or technical request..."
                            value={formData.message}
                            onChange={handleFormChange}
                            rows={4}
                          ></textarea>
                        </div>
                      </div>
                      <div className="col-lg-12">
                        <div className="contactform__input mb-30-btn">
                          <button
                            type="submit"
                            className="tp-btn"
                            disabled={submitting}
                            style={{ cursor: submitting ? 'not-allowed' : 'pointer', opacity: submitting ? 0.7 : 1 }}
                          >
                            {submitting ? 'Sending...' : 'Send Message'}
                          </button>
                        </div>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* ── 6. VIP Interactive Lightbox Modal ── */}
      {currentItem && (
        <div
          style={{
            position: 'fixed',
            inset: 0,
            zIndex: 999999,
            backgroundColor: 'rgba(11, 15, 25, 0.95)',
            backdropFilter: 'blur(8px)',
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'center',
            padding: '20px',
            animation: 'fadeIn 0.25s ease',
            userSelect: 'none',
          }}
          onClick={handleClose}
        >
          {/* Close Button */}
          <button
            onClick={handleClose}
            style={{
              position: 'fixed',
              top: '20px',
              right: '25px',
              color: '#ffffff',
              fontSize: '36px',
              background: 'rgba(255, 255, 255, 0.12)',
              border: 'none',
              borderRadius: '50%',
              width: '48px',
              height: '48px',
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'center',
              cursor: 'pointer',
              lineHeight: 1,
              transition: 'all 0.2s',
              zIndex: 1000000,
            }}
            aria-label="Close image popup"
            onMouseEnter={(e) => (e.currentTarget.style.background = 'rgba(255, 255, 255, 0.3)')}
            onMouseLeave={(e) => (e.currentTarget.style.background = 'rgba(255, 255, 255, 0.12)')}
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
              e.currentTarget.style.backgroundColor = '#0E63FF';
              e.currentTarget.style.borderColor = '#0E63FF';
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
              e.currentTarget.style.backgroundColor = '#0E63FF';
              e.currentTarget.style.borderColor = '#0E63FF';
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

          {/* Main Modal Card */}
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
                maxHeight: '75vh',
                objectFit: 'contain',
                borderRadius: '8px',
                boxShadow: '0 25px 60px rgba(0, 0, 0, 0.85)',
                display: 'block',
              }}
            />

            {/* Bottom Caption & Counter bar */}
            <div
              style={{
                width: '100%',
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'space-between',
                marginTop: '16px',
                color: '#e2e8f0',
                fontSize: '15px',
                fontWeight: '500',
              }}
            >
              <div>
                <span style={{ color: '#ffffff', fontWeight: '700', fontSize: '17px' }}>
                  {currentItem.title}
                </span>
                {currentItem.tag && (
                  <span
                    style={{
                      backgroundColor: '#0E63FF',
                      color: '#ffffff',
                      fontSize: '12px',
                      fontWeight: '700',
                      padding: '3px 10px',
                      borderRadius: '12px',
                      marginLeft: '12px',
                      textTransform: 'uppercase',
                      letterSpacing: '0.5px',
                    }}
                  >
                    {currentItem.tag}
                  </span>
                )}
              </div>
              <div
                style={{
                  background: 'rgba(255, 255, 255, 0.15)',
                  padding: '5px 14px',
                  borderRadius: '20px',
                  fontSize: '13px',
                  color: '#ffffff',
                  fontWeight: '700',
                  letterSpacing: '0.5px',
                }}
              >
                {lightboxIndex + 1} / {filteredItems.length}
              </div>
            </div>
          </div>
        </div>
      )}
    </>
  );
}
