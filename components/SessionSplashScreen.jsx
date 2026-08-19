'use client';

import { useState, useEffect } from 'react';

export default function SessionSplashScreen() {
  const [show, setShow] = useState(false);
  const [fading, setFading] = useState(false);
  const [progress, setProgress] = useState(0);

  useEffect(() => {
    try {
      const hasSeen = sessionStorage.getItem('innotech_initial_splash_done');
      if (!hasSeen) {
        setShow(true);
        sessionStorage.setItem('innotech_initial_splash_done', 'true');

        // Progress counter animation from 0 to 100%
        const startTime = Date.now();
        const duration = 1350; // 1.35s duration

        const progressInterval = setInterval(() => {
          const elapsed = Date.now() - startTime;
          const currentPercent = Math.min(Math.round((elapsed / duration) * 100), 100);
          setProgress(currentPercent);

          if (currentPercent >= 100) {
            clearInterval(progressInterval);
            setFading(true);
          }
        }, 30);

        // Remove from DOM after fade-out transition finishes
        const removeTimer = setTimeout(() => {
          setShow(false);
        }, 1800);

        return () => {
          clearInterval(progressInterval);
          clearTimeout(removeTimer);
        };
      }
    } catch (_) {
      // Fallback
    }
  }, []);

  if (!show) return null;

  return (
    <>
      <style jsx global>{`
        @keyframes tp-splash-pulse-logo {
          0%, 100% {
            transform: scale(1);
            filter: drop-shadow(0 0 16px rgba(14, 99, 255, 0.25));
          }
          50% {
            transform: scale(1.05);
            filter: drop-shadow(0 0 32px rgba(16, 208, 161, 0.45));
          }
        }

        @keyframes tp-splash-spin-ring {
          0%   { transform: rotate(0deg); }
          100% { transform: rotate(360deg); }
        }

        .tp-session-splash-overlay {
          position: fixed;
          inset: 0;
          z-index: 99999999;
          background: radial-gradient(circle at 50% 40%, #ffffff 0%, #f3f7ff 55%, #e9f1fe 100%);
          display: flex;
          flex-direction: column;
          align-items: center;
          justify-content: center;
          transition: opacity 0.45s cubic-bezier(0.4, 0, 0.2, 1), transform 0.45s cubic-bezier(0.4, 0, 0.2, 1);
          pointer-events: all;
          user-select: none;
        }

        .tp-session-splash-overlay.tp-splash-fading {
          opacity: 0;
          transform: scale(1.025);
          pointer-events: none;
        }

        .tp-session-splash-card {
          display: flex;
          flex-direction: column;
          align-items: center;
          text-align: center;
          padding: 38px 42px 34px;
          background: rgba(255, 255, 255, 0.9);
          backdrop-filter: blur(20px);
          -webkit-backdrop-filter: blur(20px);
          border-radius: 24px;
          box-shadow: 0 30px 60px -15px rgba(14, 99, 255, 0.12), 0 0 0 1px rgba(14, 99, 255, 0.08);
          max-width: 440px;
          width: 88%;
        }

        .tp-splash-logo-container {
          position: relative;
          width: 82px;
          height: 82px;
          display: flex;
          align-items: center;
          justify-content: center;
          margin-bottom: 20px;
        }

        .tp-splash-ring-outer {
          position: absolute;
          inset: -6px;
          border-radius: 50%;
          border: 3.5px solid transparent;
          border-top-color: #0e63ff;
          border-right-color: #10d0a1;
          animation: tp-splash-spin-ring 1s linear infinite;
        }

        .tp-splash-logo-img {
          width: 64px;
          height: 64px;
          object-fit: contain;
          animation: tp-splash-pulse-logo 1.5s ease-in-out infinite;
        }

        .tp-splash-title {
          font-family: 'Days One', 'Archivo', sans-serif;
          font-size: 19px;
          font-weight: 800;
          color: #171151;
          letter-spacing: 0.8px;
          margin: 0 0 4px;
          text-transform: uppercase;
        }

        .tp-splash-sub {
          font-family: 'Archivo', sans-serif;
          font-size: 12.5px;
          font-weight: 500;
          color: #64748b;
          margin: 0 0 24px;
          letter-spacing: 0.2px;
        }

        .tp-splash-track {
          width: 100%;
          height: 5px;
          background: #e2e8f0;
          border-radius: 6px;
          overflow: hidden;
          position: relative;
          margin-bottom: 12px;
        }

        .tp-splash-bar-fill {
          height: 100%;
          background: linear-gradient(90deg, #0e63ff 0%, #10d0a1 100%);
          border-radius: 6px;
          transition: width 0.05s linear;
          box-shadow: 0 0 10px rgba(14, 99, 255, 0.4);
        }

        .tp-splash-percent {
          font-family: 'Archivo', sans-serif;
          font-size: 12px;
          font-weight: 700;
          color: #0e63ff;
          letter-spacing: 0.5px;
        }
      `}</style>

      <div className={`tp-session-splash-overlay ${fading ? 'tp-splash-fading' : ''}`} role="dialog">
        <div className="tp-session-splash-card">
          <div className="tp-splash-logo-container">
            <div className="tp-splash-ring-outer" />
            <img
              src="/assets/img/logo/favicon.png"
              alt="Innotech Medical"
              className="tp-splash-logo-img"
            />
          </div>

          <h2 className="tp-splash-title">INNOTECH MEDICAL</h2>
          <p className="tp-splash-sub">Innovating Healthcare with Advance Technologies</p>

          <div className="tp-splash-track">
            <div className="tp-splash-bar-fill" style={{ width: `${progress}%` }} />
          </div>

          <span className="tp-splash-percent">{progress}%</span>
        </div>
      </div>
    </>
  );
}
