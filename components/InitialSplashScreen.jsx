'use client';

import { useState, useEffect } from 'react';

export default function InitialSplashScreen() {
  const [showSplash, setShowSplash] = useState(false);
  const [fading, setFading] = useState(false);

  useEffect(() => {
    // Only show on the very FIRST visit of the browser session
    try {
      const hasSeenSplash = sessionStorage.getItem('innotech_splash_seen');
      if (!hasSeenSplash) {
        setShowSplash(true);
        sessionStorage.setItem('innotech_splash_seen', 'true');

        // Start smooth fade-out after 1.4s
        const fadeTimer = setTimeout(() => {
          setFading(true);
        }, 1400);

        // Remove from DOM after transition completes (1.8s)
        const closeTimer = setTimeout(() => {
          setShowSplash(false);
        }, 1850);

        return () => {
          clearTimeout(fadeTimer);
          clearTimeout(closeTimer);
        };
      }
    } catch (_) {
      // Fallback if sessionStorage is disabled
    }
  }, []);

  if (!showSplash) return null;

  return (
    <>
      <style jsx global>{`
        @keyframes tp-init-pulse {
          0%, 100% {
            transform: scale(1);
            filter: drop-shadow(0 0 15px rgba(14, 99, 255, 0.25));
          }
          50% {
            transform: scale(1.04);
            filter: drop-shadow(0 0 30px rgba(16, 208, 161, 0.4));
          }
        }

        @keyframes tp-init-ring {
          0% {
            transform: rotate(0deg);
          }
          100% {
            transform: rotate(360deg);
          }
        }

        @keyframes tp-init-progress {
          0%   { width: 0%; }
          50%  { width: 70%; }
          100% { width: 100%; }
        }

        @keyframes tp-init-fadein {
          from {
            opacity: 0;
            transform: scale(0.96);
          }
          to {
            opacity: 1;
            transform: scale(1);
          }
        }

        .tp-first-splash {
          position: fixed;
          inset: 0;
          z-index: 99999999;
          background: radial-gradient(circle at 50% 45%, #ffffff 0%, #f0f5ff 60%, #e8f0fe 100%);
          display: flex;
          flex-direction: column;
          align-items: center;
          justify-content: center;
          transition: opacity 0.45s cubic-bezier(0.4, 0, 0.2, 1), transform 0.45s cubic-bezier(0.4, 0, 0.2, 1);
          pointer-events: all;
          user-select: none;
        }

        .tp-first-splash.tp-splash-fadeout {
          opacity: 0;
          transform: scale(1.03);
          pointer-events: none;
        }

        .tp-splash-content-box {
          display: flex;
          flex-direction: column;
          align-items: center;
          text-align: center;
          padding: 40px 36px 32px;
          background: rgba(255, 255, 255, 0.85);
          backdrop-filter: blur(16px);
          -webkit-backdrop-filter: blur(16px);
          border-radius: 24px;
          box-shadow: 0 25px 50px -12px rgba(23, 17, 81, 0.12), 0 0 0 1px rgba(14, 99, 255, 0.08);
          max-width: 440px;
          width: 90%;
          animation: tp-init-fadein 0.35s ease-out;
        }

        .tp-splash-logo-wrap {
          position: relative;
          width: 86px;
          height: 86px;
          display: flex;
          align-items: center;
          justify-content: center;
          margin-bottom: 22px;
        }

        .tp-splash-spinner-ring {
          position: absolute;
          inset: -6px;
          border-radius: 50%;
          border: 3px solid transparent;
          border-top-color: #0e63ff;
          border-right-color: #10d0a1;
          animation: tp-init-ring 1.1s linear infinite;
        }

        .tp-splash-logo-img {
          width: 68px;
          height: 68px;
          object-fit: contain;
          animation: tp-init-pulse 1.6s ease-in-out infinite;
        }

        .tp-splash-brand-title {
          font-family: 'Days One', 'Archivo', sans-serif;
          font-size: 20px;
          font-weight: 800;
          color: #171151;
          letter-spacing: 0.8px;
          margin: 0 0 6px;
          text-transform: uppercase;
        }

        .tp-splash-tagline {
          font-family: 'Archivo', sans-serif;
          font-size: 12.5px;
          font-weight: 500;
          color: #64748b;
          margin: 0 0 26px;
          letter-spacing: 0.3px;
        }

        .tp-splash-loader-line {
          width: 100%;
          height: 4px;
          background: #e2e8f0;
          border-radius: 4px;
          overflow: hidden;
          position: relative;
        }

        .tp-splash-loader-fill {
          position: absolute;
          left: 0;
          top: 0;
          bottom: 0;
          background: linear-gradient(90deg, #0e63ff 0%, #10d0a1 100%);
          border-radius: 4px;
          animation: tp-init-progress 1.35s cubic-bezier(0.4, 0, 0.2, 1) forwards;
          box-shadow: 0 0 10px rgba(14, 99, 255, 0.5);
        }
      `}</style>

      <div className={`tp-first-splash ${fading ? 'tp-splash-fadeout' : ''}`} role="dialog" aria-label="Loading Innotech Medical...">
        <div className="tp-splash-content-box">
          <div className="tp-splash-logo-wrap">
            <div className="tp-splash-spinner-ring" />
            <img
              src="/assets/img/logo/favicon.png"
              alt="Innotech Medical"
              className="tp-splash-logo-img"
            />
          </div>

          <h2 className="tp-splash-brand-title">INNOTECH MEDICAL</h2>
          <p className="tp-splash-tagline">Innovating Healthcare with Advance Technologies</p>

          <div className="tp-splash-loader-line">
            <div className="tp-splash-loader-fill" />
          </div>
        </div>
      </div>
    </>
  );
}
