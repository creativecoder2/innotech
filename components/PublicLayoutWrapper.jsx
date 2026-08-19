'use client';

import React from 'react';
import { usePathname } from 'next/navigation';
import Header from '@/components/Header';
import Footer from '@/components/Footer';
import TemplateScripts from '@/components/TemplateScripts';
import AnalyticsTracker from '@/components/AnalyticsTracker';
import LiveChatWidget from '@/components/LiveChatWidget';
import PageLoader from '@/components/PageLoader';

export default function PublicLayoutWrapper({ children }) {
  const pathname = usePathname() || '';
  const isAdmin = pathname.startsWith('/admin');

  if (isAdmin) {
    // Admin routes do not load public headers, footers or template scripts
    return <>{children}</>;
  }

  return (
    <>
      {/* Page Transition Splash Screen */}
      <PageLoader />

      <AnalyticsTracker />

      <Header />
      <main>{children}</main>
      <Footer />

      {/* Floating Website Live Chat Support & WhatsApp Widget */}
      <LiveChatWidget />

      {/* Initialize WOW.js, Swipers, CounterUp, Magnific Popup */}
      <TemplateScripts />

      <style jsx global>{`
        .scroll-top,
        .scroll-to-target,
        #scroll {
          left: 24px !important;
          right: auto !important;
          z-index: 9990 !important;
        }
        .scroll-top.open,
        .scroll-to-target.open {
          bottom: 24px !important;
          left: 24px !important;
          right: auto !important;
        }

        /* Equal Height Service Cards */
        .service-active .swiper-wrapper,
        .services-slider .swiper-wrapper {
          display: flex !important;
          align-items: stretch !important;
        }

        .service-active .swiper-slide,
        .services-slider .swiper-slide {
          height: auto !important;
          display: flex !important;
        }

        .services-item {
          display: flex !important;
          flex-direction: column !important;
          justify-content: space-between !important;
          height: 100% !important;
          width: 100% !important;
        }

        .services-item__content {
          display: flex !important;
          flex-direction: column !important;
          flex-grow: 1 !important;
          justify-content: space-between !important;
        }

        .services-item__tp-title {
          min-height: 52px !important;
          display: flex !important;
          align-items: center !important;
        }

        .services-item__btn {
          margin-top: auto !important;
        }
      `}</style>
    </>
  );
}
