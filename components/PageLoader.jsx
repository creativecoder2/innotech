'use client';

import { useState, useEffect } from 'react';
import { usePathname } from 'next/navigation';

export default function PageLoader() {
  const pathname = usePathname();
  const [loading, setLoading] = useState(false);
  const [prevPath, setPrevPath] = useState(pathname);

  useEffect(() => {
    if (pathname !== prevPath) {
      // New page navigation started — show loader
      setLoading(true);
      setPrevPath(pathname);

      // Hide after page content has mounted
      const timer = setTimeout(() => {
        setLoading(false);
      }, 600);

      return () => clearTimeout(timer);
    }
  }, [pathname]);

  if (!loading) return null;

  return (
    <>
      <style jsx global>{`
        @keyframes tp-loader-spin {
          0%   { transform: rotate(0deg); }
          100% { transform: rotate(360deg); }
        }
        @keyframes tp-loader-pulse {
          0%, 100% { opacity: 1; transform: scale(1); }
          50%       { opacity: 0.6; transform: scale(0.92); }
        }
        @keyframes tp-loader-bar {
          0%   { width: 0%; }
          30%  { width: 40%; }
          60%  { width: 70%; }
          100% { width: 100%; }
        }
        @keyframes tp-loader-fadein {
          from { opacity: 0; }
          to   { opacity: 1; }
        }

        .tp-page-loader {
          position: fixed;
          inset: 0;
          z-index: 999999;
          background: rgba(255, 255, 255, 0.96);
          backdrop-filter: blur(8px);
          display: flex;
          flex-direction: column;
          align-items: center;
          justify-content: center;
          animation: tp-loader-fadein 0.15s ease;
        }

        .tp-page-loader__bar {
          position: absolute;
          top: 0;
          left: 0;
          height: 3px;
          background: linear-gradient(90deg, #0e63ff, #10d0a1);
          animation: tp-loader-bar 0.6s ease-out forwards;
          border-radius: 0 2px 2px 0;
        }

        .tp-page-loader__logo {
          animation: tp-loader-pulse 1.2s ease-in-out infinite;
          margin-bottom: 28px;
        }

        .tp-page-loader__logo img {
          width: auto;
          height: 52px;
          object-fit: contain;
        }

        .tp-page-loader__spinner {
          width: 40px;
          height: 40px;
          border: 3px solid #e8eef8;
          border-top-color: #0e63ff;
          border-radius: 50%;
          animation: tp-loader-spin 0.75s linear infinite;
        }

        .tp-page-loader__text {
          margin-top: 16px;
          font-size: 12.5px;
          font-weight: 500;
          color: #6b7a99;
          letter-spacing: 0.5px;
          font-family: 'Archivo', sans-serif;
        }
      `}</style>

      <div className="tp-page-loader" role="status" aria-label="Loading page...">
        {/* Top progress bar */}
        <div className="tp-page-loader__bar" />

        {/* Logo */}
        <div className="tp-page-loader__logo">
          <img src="/assets/img/logo/logo.png" alt="INNOTECH Medical" />
        </div>

        {/* Spinner */}
        <div className="tp-page-loader__spinner" />

        {/* Text */}
        <p className="tp-page-loader__text">Loading...</p>
      </div>
    </>
  );
}
