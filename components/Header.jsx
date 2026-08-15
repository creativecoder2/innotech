'use client';

import React, { useState, useEffect } from 'react';
import Link from 'next/link';
import { usePathname } from 'next/navigation';
import { fallbackHeaderConfig } from '@/lib/data';

export default function Header() {
  const pathname = usePathname() || '';
  const [isSticky, setIsSticky] = useState(false);
  const [isSidebarOpen, setIsSidebarOpen] = useState(false);
  const [openSubmenuId, setOpenSubmenuId] = useState(null);
  const [showScrollTop, setShowScrollTop] = useState(false);
  const [headerData, setHeaderData] = useState(fallbackHeaderConfig);
  const [socialLinks, setSocialLinks] = useState({
    facebook: 'https://facebook.com/innotechmedical',
    twitter: 'https://twitter.com/innotechmedical',
    linkedin: 'https://linkedin.com/company/innotech-medical',
    instagram: '',
    youtube: '',
    skype: '',
  });
  const [contactInfo, setContactInfo] = useState({
    phone: '+92 331 6699992',
    email: 'info@innotechmedical.org',
    address: 'Karachi, Pakistan',
  });
  const [siteLogos, setSiteLogos] = useState({
    mainLogo: '/assets/img/logo/logo.png',
    whiteLogo: '/assets/img/logo/white-logo.png',
    siteName: 'INNOTECH MEDICAL PVT LTD',
  });
  const [email, setEmail] = useState('');
  const [isSubscribed, setIsSubscribed] = useState(false);

  // All inner pages get pure white solid header; Only home page gets transparent hero header
  const isHomePage = pathname === '/';
  const isWhiteHeader = !isHomePage;

  useEffect(() => {
    fetchHeaderConfig();

    const handleScroll = () => {
      if (window.scrollY > 100) {
        setIsSticky(true);
        setShowScrollTop(true);
      } else {
        setIsSticky(false);
        setShowScrollTop(false);
      }
    };

    window.addEventListener('scroll', handleScroll);
    return () => window.removeEventListener('scroll', handleScroll);
  }, []);

  const fetchHeaderConfig = async () => {
    try {
      const res = await fetch('/api/admin/header');
      const data = await res.json();
      if (data.data) {
        setHeaderData(data.data);
      }

      const siteRes = await fetch('/api/admin/site-config');
      const siteData = await siteRes.json();
      if (siteData.data?.footer?.socialLinks) {
        setSocialLinks(siteData.data.footer.socialLinks);
      }
      if (siteData.data?.footer?.contactInfo) {
        setContactInfo(siteData.data.footer.contactInfo);
      }
      if (siteData.data?.generalSettings) {
        const gen = siteData.data.generalSettings;
        setSiteLogos({
          mainLogo: gen.mainLogo || '/assets/img/logo/logo.png',
          whiteLogo: gen.whiteLogo || '/assets/img/logo/white-logo.png',
          siteName: gen.siteName || 'INNOTECH MEDICAL PVT LTD',
        });
      }
    } catch (e) {
      console.error('Error loading header config:', e);
    }
  };


  const scrollToTop = () => {
    window.scrollTo({ top: 0, behavior: 'smooth' });
  };

  const handleNewsletter = async (e) => {
    e.preventDefault();
    if (!email.trim()) return;

    try {
      await fetch('/api/newsletter', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          email: email.trim(),
          source: 'Header Sidebar',
        }),
      });
      setIsSubscribed(true);
      setEmail('');
      setTimeout(() => setIsSubscribed(false), 4000);
    } catch (err) {
      console.error(err);
    }
  };

  const allMenuItems = headerData.menuItems || fallbackHeaderConfig.menuItems;

  const activeMenuItems = allMenuItems.filter((item) => item.enabled !== false);

  // Primary Header (Home page transparent header)
  const homeMenuItems = allMenuItems.filter((item) => {
    if (item.enabled === false) return false;
    if (typeof item.showOnHome !== 'undefined') return item.showOnHome;
    return true;
  });

  // Secondary Header (Inner pages & sticky header)
  const innerMenuItems = allMenuItems.filter((item) => {
    if (item.enabled === false) return false;
    if (typeof item.showOnInner !== 'undefined') return item.showOnInner;
    return true;
  });

  const helpPhone = headerData.helpDeskPhone || '+92 331 6699992';

  return (
    <>
      {/* Scroll-top Button */}
      <button
        onClick={scrollToTop}
        className={`scroll-top scroll-to-target ${showScrollTop ? 'open' : ''}`}
        aria-label="Scroll to top"
      >
        <i className="fas fa-angle-up"></i>
      </button>

      {/* Desktop Header */}
      {isWhiteHeader ? (
        <header className="d-none d-xl-block">
          <div
            className={`header-custom ${isSticky ? 'header-sticky' : ''}`}
            id="header-sticky"
            style={
              isSticky
                ? {
                  position: 'fixed',
                  width: '100%',
                  top: 0,
                  zIndex: 9999,
                  backgroundColor: '#ffffff',
                  boxShadow: '0 4px 20px rgba(0,0,0,0.06)',
                }
                : undefined
            }
          >
            <div className="header-logo-box">
              <Link href="/">
                <img src={siteLogos.mainLogo || '/assets/img/logo/logo.png'} alt={siteLogos.siteName || 'Innotech Medical'} />
              </Link>
            </div>
            <div className="header-menu-box">
              <div className="header-menu-top">
                <div className="row align-items-center">
                  <div className="col-lg-4">
                    <div className="header-top-mob">
                      <svg width="14" height="19" viewBox="0 0 14 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="2" cy="2" r="2" fill="#0E63FF" />
                        <circle cx="7" cy="2" r="2" fill="#0E63FF" />
                        <circle cx="12" cy="2" r="2" fill="#0E63FF" />
                        <circle cx="12" cy="7" r="2" fill="#0E63FF" />
                        <circle cx="12" cy="12" r="2" fill="#0E63FF" />
                        <circle cx="7" cy="7" r="2" fill="#0E63FF" />
                        <circle cx="7" cy="12" r="2" fill="#0E63FF" />
                        <circle cx="7" cy="17" r="2" fill="#0E63FF" />
                        <circle cx="2" cy="7" r="2" fill="#0E63FF" />
                        <circle cx="2" cy="12" r="2" fill="#0E63FF" />
                      </svg>
                      <span>Help Desk :</span>
                      <a href={`tel:${helpPhone.replace(/\s+/g, '')}`}> {helpPhone} </a>
                    </div>
                  </div>
                  <div className="col-lg-8">
                    <div className="header-time">
                      <span><i className="fa-regular fa-clock"></i> Monday - Friday  09:00 am - 05:00 Pm</span>
                      <span>Monday - Friday  09:00 am - 05:00 Pm</span>
                    </div>
                  </div>
                </div>
              </div>
              <div className="header-menu-bottom">
                <div className="row align-items-center">
                  <div className="col-lg-7">
                    <div className="main-menu main-menu-second">
                      <nav id="mobile-menu">
                        <ul>
                          {innerMenuItems.map((item) => {
                            const isCurrent =
                              item.link === '/'
                                ? pathname === '/'
                                : pathname === item.link || pathname?.startsWith(item.link);

                            if (item.hasDropdown && item.subItems && item.subItems.length > 0) {
                              const activeSub = item.subItems.filter((s) => s.enabled !== false);
                              return (
                                <li key={item.id} className="has-dropdown">
                                  <Link className={isCurrent ? 'active' : ''} href={item.link}>
                                    {item.label}
                                  </Link>
                                  {activeSub.length > 0 && (
                                    <ul className="sub-menu">
                                      {activeSub.map((sub, sIdx) => (
                                        <li key={sub.id || sIdx}>
                                          <Link href={sub.link}>{sub.label}</Link>
                                        </li>
                                      ))}
                                    </ul>
                                  )}
                                </li>
                              );
                            }

                            return (
                              <li key={item.id}>
                                <Link className={isCurrent ? 'active' : ''} href={item.link}>
                                  {item.label}
                                </Link>
                              </li>
                            );
                          })}
                        </ul>
                      </nav>
                    </div>
                  </div>
                  <div className="col-lg-5">
                    <div className="header-cart-order d-flex align-items-center justify-content-end">
                      <div className="header-cart-list d-flex align-items-center justify-content-end mr-50">
                        <button
                          type="button"
                          className="tp-menu-toggle mr-40"
                          onClick={() => setIsSidebarOpen(true)}
                          aria-label="Open Side Menu"
                          style={{
                            background: 'transparent',
                            border: 'none',
                            cursor: 'pointer',
                            padding: 0,
                          }}
                        >
                          <i className="fa-solid fa-list"></i>
                        </button>
                      </div>
                      <Link href="/contact" className="header-bottom-btn">
                        Book Appointment
                      </Link>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </header>
      ) : (
        /* Transparent Home Page Header */
        <header className="d-none d-xl-block">
          <div
            className={`header__area tp-home-one ${isSticky ? 'header-sticky' : ''}`}
            id="header-sticky"
          >
            <div className="container-fluid">
              <div className="row align-items-center">
                {/* Logo */}
                <div className="col-xxl-2 col-lg-3">
                  <div className="logo">
                    <Link href="/">
                      <img src={siteLogos.mainLogo || '/assets/img/logo/logo.png'} alt={siteLogos.siteName || 'Innotech Medical'} />
                    </Link>
                  </div>
                </div>

                {/* Dynamic Navigation Menu (Primary / Home Page Header) */}
                <div className="col-xxl-7 col-lg-6">
                  <div className="main-menu">
                    <nav id="mobile-menu">
                      <ul>
                        {homeMenuItems.map((item) => {
                          const isCurrent =
                            item.link === '/'
                              ? pathname === '/'
                              : pathname === item.link || pathname?.startsWith(item.link);

                          if (item.hasDropdown && item.subItems && item.subItems.length > 0) {
                            const activeSub = item.subItems.filter((s) => s.enabled !== false);
                            return (
                              <li key={item.id} className="has-dropdown">
                                <Link className={isCurrent ? 'active' : ''} href={item.link}>
                                  {item.label}
                                </Link>
                                {activeSub.length > 0 && (
                                  <ul className="sub-menu">
                                    {activeSub.map((sub, sIdx) => (
                                      <li key={sub.id || sIdx}>
                                        <Link href={sub.link}>{sub.label}</Link>
                                      </li>
                                    ))}
                                  </ul>
                                )}
                              </li>
                            );
                          }

                          return (
                            <li key={item.id}>
                              <Link className={isCurrent ? 'active' : ''} href={item.link}>
                                {item.label}
                              </Link>
                            </li>
                          );
                        })}
                      </ul>
                    </nav>
                  </div>
                </div>

                {/* Help Desk CTA - Exact Template Single-Line Design */}
                <div className="col-xxl-3 col-lg-3 d-flex align-items-center justify-content-end">
                  <div className="tp-bt-btn-banner">
                    <a
                      className="tp-bt-btn"
                      href={`tel:${helpPhone.replace(/\s+/g, '')}`}
                    >
                      <svg
                        width="14"
                        height="19"
                        viewBox="0 0 14 19"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                      >
                        <circle cx="2" cy="2" r="2" fill="#0E63FF" />
                        <circle cx="7" cy="2" r="2" fill="#0E63FF" />
                        <circle cx="12" cy="2" r="2" fill="#0E63FF" />
                        <circle cx="12" cy="7" r="2" fill="#0E63FF" />
                        <circle cx="12" cy="12" r="2" fill="#0E63FF" />
                        <circle cx="7" cy="7" r="2" fill="#0E63FF" />
                        <circle cx="7" cy="12" r="2" fill="#0E63FF" />
                        <circle cx="7" cy="17" r="2" fill="#0E63FF" />
                        <circle cx="2" cy="7" r="2" fill="#0E63FF" />
                        <circle cx="2" cy="12" r="2" fill="#0E63FF" />
                      </svg>
                      <span>Help Desk :</span> {helpPhone}
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </header>
      )}

      {/* Mobile Header */}
      <div
        id="header-mob-sticky"
        className={`tp-mobile-header-area ${!isWhiteHeader ? 'tp-home-lg-banner' : ''} pt-15 pb-15 d-xl-none ${isSticky ? 'header-sticky' : ''
          }`}
      >
        <div className="container">
          <div className="row align-items-center">
            <div className="col-4">
              <div className="tp-mob-logo">
                <Link href="/">
                  <img src={siteLogos.mainLogo || '/assets/img/logo/logo.png'} alt={siteLogos.siteName || 'Innotech Medical'} />
                </Link>
              </div>
            </div>
            <div className="col-8">
              <div className="tp-mobile-bar d-flex align-items-center justify-content-end">
                <div className="tp-bt-btn-banner d-none d-md-block d-xl-none mr-30">
                  <a className="tp-bt-btn" href={`tel:${helpPhone.replace(/\s+/g, '')}`}>
                    <svg
                      width="14"
                      height="19"
                      viewBox="0 0 14 19"
                      fill="none"
                      xmlns="http://www.w3.org/2000/svg"
                    >
                      <circle cx="2" cy="2" r="2" fill="#0E63FF" />
                      <circle cx="7" cy="2" r="2" fill="#0E63FF" />
                      <circle cx="12" cy="2" r="2" fill="#0E63FF" />
                      <circle cx="12" cy="7" r="2" fill="#0E63FF" />
                      <circle cx="12" cy="12" r="2" fill="#0E63FF" />
                      <circle cx="7" cy="7" r="2" fill="#0E63FF" />
                      <circle cx="7" cy="12" r="2" fill="#0E63FF" />
                      <circle cx="7" cy="17" r="2" fill="#0E63FF" />
                      <circle cx="2" cy="7" r="2" fill="#0E63FF" />
                      <circle cx="2" cy="12" r="2" fill="#0E63FF" />
                    </svg>
                    <span>Help Desk :</span> {helpPhone}
                  </a>
                </div>
                <button
                  className="tp-menu-toggle"
                  onClick={() => setIsSidebarOpen(true)}
                  aria-label="Open Mobile Menu"
                >
                  <i className="far fa-bars"></i>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

      {/* Side Info / Mobile Drawer */}
      <div className={`tpsideinfo tp-side-info-area ${isSidebarOpen ? 'tp-sidebar-opened' : ''}`}>
        <button
          className="tpsideinfo__close"
          onClick={() => setIsSidebarOpen(false)}
          aria-label="Close Mobile Menu"
        >
          <i className="fal fa-times"></i>
        </button>
        <div className="tpsideinfo__logo mb-40">
          <Link href="/" onClick={() => setIsSidebarOpen(false)}>
            <img src={siteLogos.whiteLogo || siteLogos.mainLogo || '/assets/img/logo/white-logo.png'} alt={siteLogos.siteName || 'Innotech Medical'} />
          </Link>
        </div>

        <div className="mobile-menu mean-container">
          <div className="mean-bar">
            <nav className="mean-nav">
              <ul>
                {activeMenuItems.map((item) => {
                  const hasDropdown = item.hasDropdown && item.subItems && item.subItems.length > 0;
                  const isOpen = openSubmenuId === item.id;
                  const activeSub = hasDropdown ? item.subItems.filter((s) => s.enabled !== false) : [];

                  return (
                    <li key={item.id} className={hasDropdown ? 'has-dropdown' : ''}>
                      <Link
                        href={item.link}
                        onClick={() => setIsSidebarOpen(false)}
                      >
                        {item.label}
                      </Link>
                      {hasDropdown && activeSub.length > 0 && (
                        <>
                          <a
                            className={`mean-expand ${isOpen ? 'mean-clicked' : ''}`}
                            href="#"
                            onClick={(e) => {
                              e.preventDefault();
                              e.stopPropagation();
                              setOpenSubmenuId(isOpen ? null : item.id);
                            }}
                            aria-label={`Toggle ${item.label} submenu`}
                          >
                            <i className="fa-solid fa-plus"></i>
                          </a>
                          <ul
                            className="sub-menu"
                            style={{
                              display: isOpen ? 'block' : 'none',
                            }}
                          >
                            {activeSub.map((sub, sIdx) => (
                              <li key={sub.id || sIdx}>
                                <Link
                                  href={sub.link}
                                  onClick={() => setIsSidebarOpen(false)}
                                >
                                  {sub.label}
                                </Link>
                              </li>
                            ))}
                          </ul>
                        </>
                      )}
                    </li>
                  );
                })}
              </ul>
            </nav>
          </div>
        </div>

        <div className="tpsideinfo__content mb-60 mt-40">
          <p className="d-none d-xl-block">
            {headerData?.sidebarText || 'Innotech Medical Pvt Ltd provides cutting-edge biomedical equipment, clinical technologies, and laboratory devices across Pakistan.'}
          </p>
          <span>Contact Us</span>
          {contactInfo.address && (
            <a href="#">
              <i className="fa-solid fa-location-dot"></i> {contactInfo.address}
            </a>
          )}
          {contactInfo.phone && (
            <a href={`tel:${contactInfo.phone.replace(/\s+/g, '')}`}>
              <i className="fa-solid fa-phone"></i> {contactInfo.phone}
            </a>
          )}
          {contactInfo.email && (
            <a href={`mailto:${contactInfo.email}`}>
              <i className="fa-solid fa-envelope"></i> {contactInfo.email}
            </a>
          )}
        </div>

        <div className="tpsideinfo__socialicon">
          {socialLinks.facebook && (
            <a href={socialLinks.facebook} target="_blank" rel="noreferrer" aria-label="Facebook">
              <i className="fa-brands fa-facebook-f"></i>
            </a>
          )}
          {socialLinks.twitter && (
            <a href={socialLinks.twitter} target="_blank" rel="noreferrer" aria-label="Twitter">
              <i className="fa-brands fa-twitter"></i>
            </a>
          )}
          {socialLinks.linkedin && (
            <a href={socialLinks.linkedin} target="_blank" rel="noreferrer" aria-label="LinkedIn">
              <i className="fa-brands fa-linkedin-in"></i>
            </a>
          )}
        </div>
      </div>

      {/* Background Overlay */}
      {isSidebarOpen && (
        <div
          className="body-overlay apply"
          onClick={() => setIsSidebarOpen(false)}
          style={{ display: 'block' }}
        ></div>
      )}
    </>
  );
}
