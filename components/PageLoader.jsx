'use client';

import { useState, useEffect } from 'react';
import { usePathname } from 'next/navigation';

export default function PageLoader() {
  const pathname = usePathname();
  const [loading, setLoading] = useState(false);

  // 1. Instant trigger on ANY link click (Header, Sidebar, Cards, Footer, Admin menu)
  useEffect(() => {
    const handleAnchorClick = (e) => {
      // Find closest anchor tag
      const anchor = e.target.closest('a');
      if (!anchor) return;

      const href = anchor.getAttribute('href');
      if (!href) return;

      // Skip non-navigation links
      if (
        href.startsWith('#') ||
        href.startsWith('mailto:') ||
        href.startsWith('tel:') ||
        href.startsWith('javascript:') ||
        anchor.getAttribute('target') === '_blank' ||
        anchor.getAttribute('download') !== null ||
        e.ctrlKey ||
        e.metaKey ||
        e.shiftKey
      ) {
        return;
      }

      // If clicking same page anchor, don't show
      const currentFull = window.location.pathname + window.location.search;
      if (href === currentFull || href === pathname) {
        return;
      }

      // Trigger splash immediately
      setLoading(true);
    };

    document.addEventListener('click', handleAnchorClick, { capture: true });
    return () => document.removeEventListener('click', handleAnchorClick, { capture: true });
  }, [pathname]);

  // 2. Hide loader when navigation completes (pathname changes)
  useEffect(() => {
    const timer = setTimeout(() => {
      setLoading(false);
    }, 450);

    return () => clearTimeout(timer);
  }, [pathname]);

  // 3. Safety auto-dismiss timeout (max 4s in case of aborted nav)
  useEffect(() => {
    if (loading) {
      const safetyTimer = setTimeout(() => {
        setLoading(false);
      }, 4000);
      return () => clearTimeout(safetyTimer);
    }
  }, [loading]);

  if (!loading) return null;

  return (
    <>
      <style jsx global>{`
        @keyframes tp-spin-smooth {
          0%   { transform: rotate(0deg); }
          100% { transform: rotate(360deg); }
        }
        @keyframes tp-pulse-glow {
          0%, 100% { opacity: 1; transform: scale(1); filter: drop-shadow(0 0 10px rgba(14, 99, 255, 0.2)); }
          50%      { opacity: 0.7; transform: scale(0.95); filter: drop-shadow(0 0 20px rgba(16, 208, 161, 0.3)); }
        }
        @keyframes tp-progress-slide {
          0%   { width: 0%; }
          30%  { width: 45%; }
          70%  { width: 85%; }
          100% { width: 100%; }
        }
        @keyframes tp-splash-fadein {
          from { opacity: 0; backdrop-filter: blur(0px); }
          to   { opacity: 1; backdrop-filter: blur(10px); }
        }

        .tp-splash-overlay {
          position: fixed;
          inset: 0;
          z-index: 9999999;
          background: rgba(255, 255, 255, 0.94);
          backdrop-filter: blur(10px);
          -webkit-backdrop-filter: blur(10px);
          display: flex;
          flex-direction: column;
          align-items: center;
          justify-content: center;
          animation: tp-splash-fadein 0.12s cubic-bezier(0.4, 0, 0.2, 1);
          pointer-events: all;
          user-select: none;
        }

        .tp-splash-bar {
          position: absolute;
          top: 0;
          left: 0;
          height: 3.5px;
          background: linear-gradient(90deg, #0e63ff 0%, #10d0a1 50%, #0e63ff 100%);
          background-size: 200% 100%;
          animation: tp-progress-slide 0.5s ease-out forwards;
          border-radius: 0 4px 4px 0;
          box-shadow: 0 0 12px rgba(14, 99, 255, 0.5);
        }

        .tp-splash-center {
          display: flex;
          flex-direction: column;
          align-items: center;
          justify-content: center;
          padding: 32px;
          background: #ffffff;
          border-radius: 20px;
          box-shadow: 0 20px 40px -15px rgba(23, 17, 81, 0.08), 0 0 1px 1px rgba(0,0,0,0.04);
          border: 1px solid rgba(226, 232, 240, 0.8);
        }

        .tp-splash-logo {
          animation: tp-pulse-glow 1.4s ease-in-out infinite;
          margin-bottom: 22px;
          display: flex;
          align-items: center;
          justify-content: center;
        }

        .tp-splash-logo img {
          max-height: 46px;
          width: auto;
          object-fit: contain;
        }

        .tp-splash-ring-container {
          position: relative;
          width: 44px;
          height: 44px;
          display: flex;
          align-items: center;
          justify-content: center;
        }

        .tp-splash-ring {
          width: 42px;
          height: 42px;
          border: 3.5px solid #edf2f7;
          border-top-color: #0e63ff;
          border-right-color: #10d0a1;
          border-radius: 50%;
          animation: tp-spin-smooth 0.65s linear infinite;
        }

        .tp-splash-status {
          margin-top: 16px;
          font-size: 13px;
          font-weight: 600;
          color: #475569;
          font-family: 'Archivo', sans-serif;
          letter-spacing: 0.3px;
        }
      `}</style>

      <div className="tp-splash-overlay" role="status" aria-label="Loading page...">
        {/* Top Progress Line */}
        <div className="tp-splash-bar" />

        {/* Center Glass Card */}
        <div className="tp-splash-center">
          <div className="tp-splash-logo">
            <img src="/assets/img/logo/logo.png" alt="INNOTECH Medical" />
          </div>

          <div className="tp-splash-ring-container">
            <div className="tp-splash-ring" />
          </div>

          <span className="tp-splash-status">Loading page...</span>
        </div>
      </div>
    </>
  );
}
