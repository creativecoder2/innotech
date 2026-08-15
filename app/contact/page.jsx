'use client';

import React, { useState, useEffect } from 'react';
import Link from 'next/link';
import { fallbackContactPage } from '@/lib/data';

export default function ContactPage() {
  const [contactData, setContactData] = useState(fallbackContactPage);
  const [formData, setFormData] = useState({
    name: '',
    email: '',
    phone: '',
    subject: '',
    message: '',
  });
  const [submitting, setSubmitting] = useState(false);
  const [submitResult, setSubmitResult] = useState({ type: '', msg: '' });

  useEffect(() => {
    fetchContactConfig();
  }, []);

  const fetchContactConfig = async () => {
    try {
      const res = await fetch('/api/admin/contact');
      const data = await res.json();
      if (data.data) {
        setContactData(data.data);
      }
    } catch (e) {
      console.error('Error loading contact configuration:', e);
    }
  };

  const handleInputChange = (e) => {
    const { name, value } = e.target;
    if (name === 'phone') {
      const numericValue = value.replace(/[^0-9+]/g, '');
      setFormData({ ...formData, phone: numericValue });
    } else {
      setFormData({ ...formData, [name]: value });
    }
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    if (!formData.name.trim() || !formData.phone.trim()) {
      setSubmitResult({ type: 'error', msg: 'Please provide at least your full name and phone number.' });
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
          subject: formData.subject.trim() || 'General Contact Inquiry',
          message: formData.message.trim() || 'General contact inquiry submitted from website contact page.',
          source: 'Contact Page',
        }),
      });

      const data = await res.json();
      if (res.ok && data.success) {
        setSubmitResult({
          type: 'success',
          msg: data.message || 'Thank you! Your inquiry has been received. Our biomedical team will contact you shortly.',
        });
        setFormData({ name: '', email: '', phone: '', subject: '', message: '' });
      } else {
        setSubmitResult({
          type: 'error',
          msg: data.message || 'Error submitting inquiry. Please call our hotline or try again.',
        });
      }
    } catch (err) {
      console.error('Contact form submission error:', err);
      setSubmitResult({
        type: 'error',
        msg: 'Connection error. Please check your network or call our direct helpline.',
      });
    } finally {
      setSubmitting(false);
    }
  };

  const banner = contactData.banner || {};
  const info = contactData.info || {};
  const formSection = contactData.formSection || {};
  const map = contactData.map || {};

  return (
    <>
      {/* 1. Breadcrumb Banner */}
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
              <div className="col-lg-6 col-md-7 col-12">
                <div className="tp-breadcrumb">
                  <h2 className="tp-breadcrumb__title">{banner.title || 'Contact us'}</h2>
                  {banner.subTitle && (
                    <p style={{ color: '#E2E8F0', marginTop: '10px', fontSize: '16px', fontWeight: '500' }}>
                      {banner.subTitle}
                    </p>
                  )}
                </div>
              </div>
            </div>
          </div>
        </section>
      )}

      {/* 2. Contact Information Cards & Interactive Form */}
      <section className="contact-area pt-130 pb-115">
        <div className="container">
          <div className="row">
            {/* Left Column: Official Info Cards */}
            {info.enabled !== false && (
              <div className="col-lg-4 col-md-5 col-12 wow fadeInLeft" data-wow-delay=".3s">
                {/* Address Card */}
                <div className="tpcontact mr-30 mb-40 wow fadeInUp" data-wow-delay=".2s">
                  <div className="tpcontact__item text-center">
                    <div className="tpcontact__icon mb-20">
                      <img src="/assets/img/icon/contact-01.svg" alt="Address Icon" />
                    </div>
                    <div className="tpcontact__address">
                      <h4 className="tpcontact__title mb-15">{info.addressTitle || 'Head Office Address'}</h4>
                      <span>
                        <a href="#map-section">{info.address}</a>
                      </span>
                    </div>
                  </div>
                </div>

                {/* Phone Card */}
                <div className="tpcontact mr-30 mb-40 wow fadeInUp" data-wow-delay=".4s">
                  <div className="tpcontact__item text-center">
                    <div className="tpcontact__icon mb-20">
                      <img src="/assets/img/icon/contact-02.svg" alt="Phone Icon" />
                    </div>
                    <div className="tpcontact__address">
                      <h4 className="tpcontact__title mb-15">{info.phoneTitle || 'Phone Number'}</h4>
                      <span>
                        <a href={`tel:${info.phone}`}>{info.phone}</a>
                      </span>
                      {info.phoneSub && (
                        <span style={{ display: 'block', fontSize: '13px', color: '#0E63FF', marginTop: '4px' }}>
                          {info.phoneSub}
                        </span>
                      )}
                    </div>
                  </div>
                </div>

                {/* Email & Hours Card */}
                <div className="tpcontact mr-30 mb-40 wow fadeInUp" data-wow-delay=".6s">
                  <div className="tpcontact__item text-center">
                    <div className="tpcontact__icon mb-20">
                      <img src="/assets/img/icon/contact-03.svg" alt="Email Icon" />
                    </div>
                    <div className="tpcontact__address">
                      <h4 className="tpcontact__title mb-15">{info.emailTitle || 'Official Email'}</h4>
                      <span>
                        <a href={`mailto:${info.email}`}>{info.email}</a>
                      </span>
                      <div style={{ marginTop: '10px', fontSize: '13px', color: '#6b6b6b' }}>
                        <strong>{info.officeHoursTitle || 'Hours'}:</strong> {info.officeHours}
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            )}

            {/* Right Column: Inquiry Form & Map */}
            <div className={info.enabled !== false ? 'col-lg-8 col-md-7 col-12' : 'col-lg-12'}>
              {formSection.enabled !== false && (
                <div className="contactform wow fadeInRight" data-wow-delay=".4s">
                  {formSection.subTitle && (
                    <span style={{ fontSize: '13px', fontWeight: '700', color: '#0E63FF', letterSpacing: '1px' }}>
                      {formSection.subTitle}
                    </span>
                  )}
                  <h4 className="contactform__title mb-15">
                    {formSection.title || 'Send us a Message :'}
                  </h4>
                  {formSection.description && (
                    <p style={{ color: '#6b6b6b', marginBottom: '30px', fontSize: '14px' }}>
                      {formSection.description}
                    </p>
                  )}

                  {/* Submission Alert */}
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
                    <form onSubmit={handleSubmit}>
                      <div className="row">
                        <div className="col-lg-6">
                          <div className="contactform__input mb-25">
                            <input
                              name="name"
                              type="text"
                              placeholder="Enter your full name"
                              value={formData.name}
                              onChange={handleInputChange}
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
                              onChange={handleInputChange}
                            />
                          </div>
                        </div>
                        <div className="col-lg-6">
                          <div className="contactform__input mb-25">
                            <input
                              name="phone"
                              type="tel"
                              inputMode="numeric"
                              placeholder="Enter phone / mobile number"
                              value={formData.phone}
                              onChange={handleInputChange}
                              required
                            />
                          </div>
                        </div>
                        <div className="col-lg-6">
                          <div className="contactform__input mb-25">
                            <input
                              name="subject"
                              type="text"
                              placeholder="Equipment inquiry / Organization"
                              value={formData.subject}
                              onChange={handleInputChange}
                            />
                          </div>
                        </div>
                        <div className="col-lg-12">
                          <div className="contactform__input mb-25">
                            <textarea
                              name="message"
                              placeholder="How can our biomedical team assist your facility?"
                              value={formData.message}
                              onChange={handleInputChange}
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
                              style={{ cursor: submitting ? 'not-allowed' : 'pointer' }}
                            >
                              {submitting ? 'Sending...' : 'Send Message'}
                            </button>
                          </div>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
              )}

              {/* 3. Google Maps Section */}
              {map.enabled !== false && (
                <div id="map-section" className="row">
                  <div className="col-lg-12">
                    <div
                      className="tpcontactmap"
                      style={{
                        borderRadius: '12px',
                        overflow: 'hidden',
                        boxShadow: '0 8px 24px rgba(0,0,0,0.06)',
                        border: '1px solid #ECEEF3',
                      }}
                    >
                      <iframe
                        src={
                          map.mapUrl ||
                          'https://maps.google.com/maps?q=1st%20Floor,%20Plot:%20A-301,%20Sardar%20Ali%20Sabri%20Road,%20Block-2,%20Gulshan%20e%20Iqbal,%20Karachi,%20Pakistan&t=&z=16&ie=UTF8&iwloc=&output=embed'
                        }
                        width="100%"
                        height="400"
                        style={{ border: 0 }}
                        allowFullScreen=""
                        loading="lazy"
                        referrerPolicy="no-referrer-when-downgrade"
                        title="Innotech Medical Location Map"
                      ></iframe>
                    </div>
                  </div>
                </div>
              )}
            </div>
          </div>
        </div>
      </section>
    </>
  );
}
