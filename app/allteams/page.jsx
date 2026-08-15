'use client';

import React, { useState, useEffect } from 'react';
import Link from 'next/link';
import { fallbackTeam } from '@/lib/data';

const DEFAULT_BANNER = '/assets/img/banner/breadcrumb-01.jpg';

export default function AllTeamsPage() {
  const [teamMembers, setTeamMembers] = useState(fallbackTeam);
  const [loading, setLoading] = useState(true);
  const [pageConfig, setPageConfig] = useState({
    bannerImage: DEFAULT_BANNER,
    bannerTitle: 'Meet Specialist',
    bannerBreadcrumb: 'Our Team',
    sectionSubTitle: 'OUR TEAM',
    sectionTitle: 'Our Specialist',
  });

  const [formData, setFormData] = useState({ name: '', email: '', phone: '', subject: '', message: '' });
  const [submitting, setSubmitting] = useState(false);
  const [submitResult, setSubmitResult] = useState({ type: '', msg: '' });

  useEffect(() => {
    fetchTeamData();
  }, []);

  const fetchTeamData = async () => {
    try {
      const res = await fetch('/api/admin/site-config');
      const data = await res.json();
      if (data.data?.teamSection?.members?.length > 0) {
        setTeamMembers(data.data.teamSection.members);
      }
      // Load teamPages banner config
      if (data.data?.teamPages?.allTeams) {
        const ap = data.data.teamPages.allTeams;
        setPageConfig({
          bannerImage: ap.bannerImage || DEFAULT_BANNER,
          bannerTitle: ap.bannerTitle || 'Meet Specialist',
          bannerBreadcrumb: ap.bannerBreadcrumb || 'Our Team',
          sectionSubTitle: ap.sectionSubTitle || 'OUR TEAM',
          sectionTitle: ap.sectionTitle || 'Our Specialist',
        });
      }
    } catch (e) {
      console.error('Error fetching team:', e);
    } finally {
      setLoading(false);
    }
  };


  const handleInputChange = (e) => {
    const { name, value } = e.target;
    if (name === 'phone') {
      setFormData({ ...formData, phone: value.replace(/[^0-9+]/g, '') });
    } else {
      setFormData({ ...formData, [name]: value });
    }
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    if (!formData.name.trim() || !formData.phone.trim()) {
      setSubmitResult({ type: 'error', msg: 'Please provide your full name and phone number.' });
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
          subject: formData.subject.trim() || 'Specialist Consultation Inquiry',
          message: formData.message.trim() || 'Contact request from specialists page.',
          source: 'All Specialists Page',
        }),
      });
      const data = await res.json();
      if (res.ok && data.success) {
        setSubmitResult({ type: 'success', msg: 'Message sent! Our team will contact you shortly.' });
        setFormData({ name: '', email: '', phone: '', subject: '', message: '' });
      } else {
        setSubmitResult({ type: 'error', msg: data.message || 'Failed to send. Please try again.' });
      }
    } catch {
      setSubmitResult({ type: 'error', msg: 'Connection error. Please try again.' });
    } finally {
      setSubmitting(false);
    }
  };

  const activeTeam = teamMembers.filter((m) => m.enabled !== false);

  return (
    <>
      {/* ── breadcrumb-area (exact team-01.html structure) ── */}
      <section
        className="breadcrumb__area pt-100 pb-120 breadcrumb__overlay"
        style={{ backgroundImage: `url('${pageConfig.bannerImage}')`, backgroundSize: 'cover', backgroundPosition: 'center' }}
      >
        <div className="container">
          <div className="row align-items-center">
            <div className="col-xl-7 col-lg-12 col-md-12 col-12">
              <div className="tp-breadcrumb">
                <h2 className="tp-breadcrumb__title">{pageConfig.bannerTitle}</h2>
              </div>
            </div>
            <div className="col-xl-5 col-lg-12 col-md-12 col-12">
              <div className="tp-breadcrumb__link text-xl-end">
                <span>
                  Innotech : <Link href="/">{pageConfig.bannerBreadcrumb}</Link>
                </span>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* ── team-area (exact team-01.html structure) ── */}
      <section className="team-area pt-125 pb-70">
        <div className="container">
          <div className="row">
            <div className="col-lg-12">
              <div className="tp-section text-center">
                <span className="tp-section__sub-title left-line right-line mb-25">{pageConfig.sectionSubTitle}</span>
                <h3 className="tp-section__title mb-70">{pageConfig.sectionTitle}</h3>
              </div>
            </div>
          </div>

          <div className="row">
            {activeTeam.map((member, index) => {
              const memberSlug =
                member.slug ||
                member._id ||
                member.name?.toLowerCase().trim().replace(/[^a-z0-9\s-]/g, '').replace(/\s+/g, '-');
              const memberUrl = `/team/${memberSlug}`;
              const social = member.socialLinks || {};

              return (
                <div
                  key={member._id || index}
                  className="col-xl-3 col-lg-4 col-md-6"
                >
                  {/* team-item — exact class from team-01.html */}
                  <div className="team-item mb-35">
                    <div className="team-item__thumb mb-40">
                      <Link href={memberUrl}>
                        <img
                          src={member.image || '/assets/img/team/team-thumb-05.png'}
                          alt={member.name}
                        />
                      </Link>
                    </div>
                    <div className="team-item__content">
                      <h5 className="team-item__title mb-15">
                        <Link href={memberUrl}>{member.name}</Link>
                      </h5>
                      <span>{member.position}</span>

                      {/* Social icons — hidden by default, fade-in on hover (CSS-driven) */}
                      <div className="team-item__social-info">
                        {social.facebook && social.facebook !== '#' ? (
                          <a href={social.facebook} target="_blank" rel="noreferrer" aria-label="Facebook">
                            <i className="fa-brands fa-facebook-f"></i>
                          </a>
                        ) : (
                          <Link href={memberUrl} aria-label="Profile">
                            <i className="fa-brands fa-facebook-f"></i>
                          </Link>
                        )}
                        {social.twitter && social.twitter !== '#' ? (
                          <a href={social.twitter} target="_blank" rel="noreferrer" aria-label="Twitter">
                            <i className="fa-brands fa-twitter"></i>
                          </a>
                        ) : (
                          <Link href={memberUrl} aria-label="Profile">
                            <i className="fa-brands fa-twitter"></i>
                          </Link>
                        )}
                        {social.instagram && social.instagram !== '#' ? (
                          <a href={social.instagram} target="_blank" rel="noreferrer" aria-label="Instagram">
                            <i className="fa-brands fa-instagram"></i>
                          </a>
                        ) : (
                          <Link href={memberUrl} aria-label="Profile">
                            <i className="fa-brands fa-instagram"></i>
                          </Link>
                        )}
                        {social.linkedin && social.linkedin !== '#' ? (
                          <a href={social.linkedin} target="_blank" rel="noreferrer" aria-label="LinkedIn">
                            <i className="fa-brands fa-linkedin-in"></i>
                          </a>
                        ) : (
                          <Link href={memberUrl} aria-label="Profile">
                            <i className="fa-brands fa-linkedin-in"></i>
                          </Link>
                        )}
                      </div>
                    </div>
                  </div>
                </div>
              );
            })}
          </div>
        </div>
      </section>

      {/* ── Full Contact Form Section (same as contact page) ── */}
      <section className="support-area grey-bg pt-125 pb-130">
        <div className="container">
          <div className="row text-center">
            <div className="col-lg-12 col-md-12 col-12">
              <div className="tp-section">
                <span className="tp-section__sub-title left-line right-line mb-20">Get in touch</span>
                <h3 className="tp-section__title mb-70">Need Any Help</h3>
              </div>
            </div>
          </div>

          <div className="row justify-content-center">
            <div className="col-xl-10 col-lg-12 col-md-12 col-12">
              <div className="contactform">
                <span style={{ fontSize: '13px', fontWeight: '700', color: '#0E63FF', letterSpacing: '1px', textTransform: 'uppercase' }}>
                  Direct Contact With Us
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
                  <form onSubmit={handleSubmit}>
                    <div className="row">
                      <div className="col-lg-6">
                        <div className="contactform__input mb-25">
                          <input
                            name="name"
                            type="text"
                            placeholder="Enter your full name *"
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
                            placeholder="Phone / WhatsApp number *"
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
                            placeholder="Subject / Department"
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

    </>
  );
}
