'use client';

import React, { useState, useEffect } from 'react';
import Link from 'next/link';
import { fallbackSiteConfig } from '@/lib/data';

export default function Footer({ config }) {
  const [footerConfig, setFooterConfig] = useState(config?.footer || fallbackSiteConfig.footer);
  const [footerLogo, setFooterLogo] = useState('/assets/img/logo/logo.png');
  const [siteName, setSiteName] = useState('INNOTECH MEDICAL PVT LTD');
  const [newsletterEmail, setNewsletterEmail] = useState('');
  const [subscribing, setSubscribing] = useState(false);
  const [subscribeStatus, setSubscribeStatus] = useState({ type: '', msg: '', alreadySubscribed: false });

  useEffect(() => {
    if (config?.footer) {
      setFooterConfig(config.footer);
    }
    if (config?.generalSettings) {
      setFooterLogo(config.generalSettings.mainLogo || config.generalSettings.whiteLogo || '/assets/img/logo/logo.png');
      setSiteName(config.generalSettings.siteName || 'INNOTECH MEDICAL PVT LTD');
    }
    fetch('/api/admin/site-config')
      .then((res) => res.json())
      .then((data) => {
        if (data.data?.footer) {
          setFooterConfig(data.data.footer);
        }
        if (data.data?.generalSettings) {
          setFooterLogo(data.data.generalSettings.mainLogo || data.data.generalSettings.whiteLogo || '/assets/img/logo/logo.png');
          setSiteName(data.data.generalSettings.siteName || 'INNOTECH MEDICAL PVT LTD');
        }
      })
      .catch((e) => console.error(e));
  }, [config]);


  const social = footerConfig?.socialLinks || {};
  const facebookUrl = social.facebook && social.facebook !== '#' ? social.facebook : 'https://facebook.com/innotechmedical';
  const twitterUrl = social.twitter && social.twitter !== '#' ? social.twitter : 'https://twitter.com/innotechmedical';
  const linkedinUrl = social.linkedin && social.linkedin !== '#' ? social.linkedin : 'https://linkedin.com/company/innotech-medical';
  const instagramUrl = social.instagram && social.instagram !== '#' ? social.instagram : null;
  const youtubeUrl = social.youtube && social.youtube !== '#' ? social.youtube : null;
  const skypeUrl = social.skype && social.skype !== '#' ? social.skype : null;

  const handleSubscribe = async (e) => {
    e.preventDefault();
    if (!newsletterEmail.trim()) return;

    setSubscribing(true);
    setSubscribeStatus({ type: '', msg: '', alreadySubscribed: false });

    try {
      const res = await fetch('/api/newsletter', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          email: newsletterEmail.trim(),
          source: 'Footer Newsletter',
        }),
      });
      const data = await res.json();
      if (res.ok && data.success) {
        setSubscribeStatus({
          type: 'success',
          msg: data.message || 'Thank you for subscribing to our newsletter!',
          alreadySubscribed: false,
        });
        setNewsletterEmail('');
        setTimeout(() => setSubscribeStatus({ type: '', msg: '', alreadySubscribed: false }), 7000);
      } else {
        setSubscribeStatus({
          type: 'error',
          msg:
            data.message ||
            'This email is already subscribed to our newsletter. If you have an inquiry, please submit it via our Contact Us page.',
          alreadySubscribed: data.alreadySubscribed || res.status === 409 || false,
        });
      }
    } catch (err) {
      setSubscribeStatus({
        type: 'error',
        msg: 'Connection error. Please try again.',
        alreadySubscribed: false,
      });
    } finally {
      setSubscribing(false);
    }
  };

  return (
    <footer>
      <div className="footer-area theme-bg-3 pt-100 pb-50">
        <div className="container">
          <div className="row">
            {/* Widget 1 */}
            <div className="col-xl-3 col-lg-4 col-md-6">
              <div className="footer-widget footer-col-1 mb-50 wow fadeInUp" data-wow-delay=".2s">
                <h4 className="footer-widget__title mb-30">
                  <Link href="/">
                    <img src={footerLogo || '/assets/img/logo/logo.png'} alt={siteName || 'Innotech Medical Logo'} style={{ maxHeight: '60px', maxWidth: '220px', objectFit: 'contain' }} />
                  </Link>
                </h4>

                <p>
                  {footerConfig?.description ||
                    'Innotech Medical Pvt Ltd is Growing distributor of top-quality medical equipment across Pakistan At Innotech Medical.'}
                </p>
                <div className="footer-widget__social">
                  {youtubeUrl && (
                    <a className="tp-f-youtube" href={youtubeUrl} target="_blank" rel="noreferrer" aria-label="YouTube">
                      <i className="fa-brands fa-youtube"></i>
                    </a>
                  )}
                  {twitterUrl && (
                    <a className="tp-f-twitter" href={twitterUrl} target="_blank" rel="noreferrer" aria-label="Twitter">
                      <i className="fa-brands fa-twitter"></i>
                    </a>
                  )}
                  {facebookUrl && (
                    <a className="tp-f-fb" href={facebookUrl} target="_blank" rel="noreferrer" aria-label="Facebook">
                      <i className="fa-brands fa-facebook-f"></i>
                    </a>
                  )}
                  {linkedinUrl && (
                    <a className="tp-f-skype" href={linkedinUrl} target="_blank" rel="noreferrer" aria-label="LinkedIn">
                      <i className="fa-brands fa-linkedin-in"></i>
                    </a>
                  )}
                  {instagramUrl && (
                    <a className="tp-f-twitter" href={instagramUrl} target="_blank" rel="noreferrer" aria-label="Instagram">
                      <i className="fa-brands fa-instagram"></i>
                    </a>
                  )}
                  {skypeUrl && (
                    <a className="tp-f-skype" href={skypeUrl} target="_blank" rel="noreferrer" aria-label="Skype">
                      <i className="fa-brands fa-skype"></i>
                    </a>
                  )}
                </div>
              </div>
            </div>

            {/* Widget 2 */}
            <div className="col-xl-3 col-lg-4 col-md-6">
              <div className="footer-widget footer-col-2 mb-50 wow fadeInUp" data-wow-delay=".4s">
                <h4 className="footer-widget__title mb-20">Useful links</h4>
                <div className="footer-widget__links">
                  <ul>
                    <li>
                      <Link href="/contact">Contact us</Link>
                    </li>
                    <li>
                      <Link href="/about">Help & About us</Link>
                    </li>
                    <li>
                      <Link href="/about">About us</Link>
                    </li>
                    <li>
                      <Link href="/services">Services</Link>
                    </li>
                    <li>
                      <Link href="/terms">Terms & Conditions</Link>
                    </li>
                    <li>
                      <Link href="/privacy">Privacy Policy</Link>
                    </li>
                  </ul>
                </div>
              </div>
            </div>

            {/* Widget 3 */}
            <div className="col-xl-3 col-lg-4 col-md-6">
              <div className="footer-widget footer-col-3 mb-50 wow fadeInUp" data-wow-delay=".6s">
                <h4 className="footer-widget__title mb-20">Contact info</h4>
                <div className="footer-widget__info">
                  <ul>
                    {footerConfig?.address && (
                      <li>
                        <a href="#">
                          {footerConfig.address}
                        </a>
                      </li>
                    )}
                    {footerConfig?.phone && (
                      <li>
                        <a href={`tel:${footerConfig.phone.replace(/\s+/g, '')}`}>{footerConfig.phone}</a>
                      </li>
                    )}
                    {footerConfig?.email && (
                      <li>
                        <a href={`mailto:${footerConfig.email}`}>{footerConfig.email}</a>
                      </li>
                    )}
                    {footerConfig?.officeHours && (
                      <li>
                        {footerConfig.officeHours.toLowerCase().startsWith('office')
                          ? footerConfig.officeHours
                          : `Office Hours: ${footerConfig.officeHours}`}
                      </li>
                    )}
                    {footerConfig?.weekendText && <li>{footerConfig.weekendText}</li>}
                  </ul>
                </div>
              </div>
            </div>

            {/* Widget 4 */}
            <div className="col-xl-3 col-lg-6 col-md-6">
              <div className="footer-widget footer-col-4 mb-50 wow fadeInUp" data-wow-delay=".8s">
                <h4 className="footer-widget__title mb-20">
                  {footerConfig?.newsletterTitle || 'Subscribe Newslatter'}
                </h4>
                <p>
                  {footerConfig?.newsletterDesc ||
                    'Stay updated with the latest biomedical innovations, equipment releases, and healthcare technology insights across Pakistan.'}
                </p>
                <div className="footer-widget__newsletter p-relative" style={{ position: 'relative', marginTop: '16px' }}>
                  <form onSubmit={handleSubscribe} style={{ position: 'relative', width: '100%' }}>
                    <input
                      type="email"
                      placeholder="Enter Mail"
                      value={newsletterEmail}
                      onChange={(e) => setNewsletterEmail(e.target.value)}
                      required
                      style={{
                        width: '100%',
                        height: '52px',
                        lineHeight: 'normal',
                        padding: '0 54px 0 16px',
                        backgroundColor: '#ffffff',
                        border: '1px solid #D1D6E0',
                        borderRadius: '8px',
                        fontSize: '14px',
                        color: '#171151',
                        boxSizing: 'border-box',
                        outline: 'none',
                        display: 'block',
                      }}
                    />
                    <button
                      className="footer-widget__fw-news-btn"
                      type="submit"
                      aria-label="Subscribe"
                      disabled={subscribing}
                      style={{
                        position: 'absolute',
                        right: '5px',
                        top: '50%',
                        transform: 'translateY(-50%)',
                        width: '42px',
                        height: '42px',
                        backgroundColor: '#0B9748',
                        color: '#ffffff',
                        border: 'none',
                        borderRadius: '6px',
                        display: 'flex',
                        alignItems: 'center',
                        justifyContent: 'center',
                        cursor: subscribing ? 'not-allowed' : 'pointer',
                        padding: 0,
                      }}
                    >
                      <i className="fa-solid fa-paper-plane" style={{ fontSize: '15px' }}></i>
                    </button>
                  </form>

                  {subscribeStatus.msg && (
                    <div
                      style={{
                        marginTop: '12px',
                        padding: '12px 14px',
                        borderRadius: '8px',
                        fontSize: '13px',
                        lineHeight: '1.5',
                        backgroundColor: subscribeStatus.type === 'success' ? '#E7FAF6' : '#FEEAF1',
                        color: subscribeStatus.type === 'success' ? '#0B9748' : '#D92D20',
                        border: `1px solid ${subscribeStatus.type === 'success' ? '#A3EAD8' : '#FDA29B'}`,
                        fontWeight: '500',
                      }}
                    >
                      <div>{subscribeStatus.msg}</div>
                      {subscribeStatus.alreadySubscribed && (
                        <div style={{ marginTop: '8px', paddingTop: '6px', borderTop: '1px solid rgba(217, 45, 32, 0.15)' }}>
                          <Link
                            href="/contact"
                            style={{
                              color: '#0E63FF',
                              fontWeight: '700',
                              textDecoration: 'none',
                              display: 'inline-flex',
                              alignItems: 'center',
                              gap: '4px',
                            }}
                          >
                            <span>Go to Contact Us Page</span>
                            <i className="fa-solid fa-arrow-right" style={{ fontSize: '11px' }}></i>
                          </Link>
                        </div>
                      )}
                    </div>
                  )}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      {/* Footer Bottom */}
      <div className="footer-area-bottom theme-bg-4">
        <div className="container">
          <div className="row">
            <div className="col-xl-6 col-lg-6 col-md-12 col-12">
              <div className="footer-widget__copyright">
                <span>
                  {footerConfig?.copyrightText ? (
                    footerConfig.copyrightText.includes(siteName || 'INNOTECH MEDICAL Pvt Ltd') ? (
                      <>
                        {footerConfig.copyrightText.split(siteName || 'INNOTECH MEDICAL Pvt Ltd')[0]}
                        <Link href="/">{siteName || 'INNOTECH MEDICAL Pvt Ltd'}</Link>
                        {footerConfig.copyrightText.split(siteName || 'INNOTECH MEDICAL Pvt Ltd')[1]}
                      </>
                    ) : (
                      footerConfig.copyrightText
                    )
                  ) : (
                    <>
                      © Copyright ©{new Date().getFullYear()} - {new Date().getFullYear() + 1}{' '}
                      <Link href="/">{siteName || 'INNOTECH MEDICAL Pvt Ltd'}</Link>.{' '}
                      <i>All Rights Reserved</i>
                    </>
                  )}
                </span>
              </div>
            </div>
            <div className="col-xl-6 col-lg-6 col-md-12 col-12">
              <div className="footer-widget__copyright-info info-direction">
                <ul className="d-flex align-items-center">
                  <li>
                    <Link href="/terms">Terms and conditions</Link>
                  </li>
                  <li>
                    <Link href="/privacy">Privacy policy</Link>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </footer>
  );
}
