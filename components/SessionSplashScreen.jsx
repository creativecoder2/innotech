'use client';

import { useState, useEffect } from 'react';

export default function SessionSplashScreen() {
  const [show, setShow] = useState(true);
  const [fading, setFading] = useState(false);
  const [progress, setProgress] = useState(0);

  useEffect(() => {
    try {
      const hasSeen = sessionStorage.getItem('innotech_initial_splash_done');
      if (hasSeen) {
        setShow(false);
        return;
      }

      sessionStorage.setItem('innotech_initial_splash_done', 'true');

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

      const removeTimer = setTimeout(() => {
        setShow(false);
      }, 1800);

      return () => {
        clearInterval(progressInterval);
        clearTimeout(removeTimer);
      };
    } catch (_) {
      setShow(false);
    }
  }, []);

  if (!show) return null;

  return (
    <div
      style={{
        position: 'fixed',
        top: 0,
        left: 0,
        right: 0,
        bottom: 0,
        width: '100vw',
        height: '100vh',
        zIndex: 99999999,
        background: 'radial-gradient(circle at 50% 40%, #ffffff 0%, #f3f7ff 55%, #e9f1fe 100%)',
        display: 'flex',
        flexDirection: 'column',
        alignItems: 'center',
        justifyContent: 'center',
        opacity: fading ? 0 : 1,
        transform: fading ? 'scale(1.025)' : 'scale(1)',
        transition: 'opacity 0.45s cubic-bezier(0.4, 0, 0.2, 1), transform 0.45s cubic-bezier(0.4, 0, 0.2, 1)',
        pointerEvents: fading ? 'none' : 'all',
        userSelect: 'none',
        boxSizing: 'border-box',
      }}
      role="dialog"
      aria-label="Loading Innotech Medical..."
    >
      <div
        style={{
          display: 'flex',
          flexDirection: 'column',
          alignItems: 'center',
          textAlign: 'center',
          padding: '38px 42px 34px',
          background: 'rgba(255, 255, 255, 0.95)',
          backdropFilter: 'blur(20px)',
          WebkitBackdropFilter: 'blur(20px)',
          borderRadius: '24px',
          boxShadow: '0 30px 60px -15px rgba(14, 99, 255, 0.12), 0 0 0 1px rgba(14, 99, 255, 0.08)',
          maxWidth: '440px',
          width: '88%',
          boxSizing: 'border-box',
        }}
      >
        {/* Logo Container */}
        <div
          style={{
            position: 'relative',
            width: '82px',
            height: '82px',
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'center',
            marginBottom: '20px',
          }}
        >
          <div
            style={{
              position: 'absolute',
              top: '-6px',
              left: '-6px',
              right: '-6px',
              bottom: '-6px',
              borderRadius: '50%',
              border: '3.5px solid transparent',
              borderTopColor: '#0e63ff',
              borderRightColor: '#10d0a1',
              animation: 'tp-splash-spin-ring 1s linear infinite',
            }}
          />
          <img
            src="/assets/img/logo/favicon.png"
            alt="Innotech Medical"
            style={{
              width: '64px',
              height: '64px',
              objectFit: 'contain',
            }}
          />
        </div>

        <h2
          style={{
            fontFamily: "'Days One', 'Archivo', sans-serif",
            fontSize: '19px',
            fontWeight: '800',
            color: '#171151',
            letterSpacing: '0.8px',
            margin: '0 0 4px',
            textTransform: 'uppercase',
          }}
        >
          INNOTECH MEDICAL
        </h2>

        <p
          style={{
            fontFamily: "'Archivo', sans-serif",
            fontSize: '12.5px',
            fontWeight: '500',
            color: '#64748b',
            margin: '0 0 24px',
            letterSpacing: '0.2px',
          }}
        >
          Innovating Healthcare with Advance Technologies
        </p>

        {/* Progress Bar Container */}
        <div
          style={{
            width: '100%',
            height: '6px',
            background: '#e2e8f0',
            borderRadius: '6px',
            overflow: 'hidden',
            position: 'relative',
            marginBottom: '10px',
          }}
        >
          <div
            style={{
              height: '100%',
              width: `${progress}%`,
              background: 'linear-gradient(90deg, #0e63ff 0%, #10d0a1 100%)',
              borderRadius: '6px',
              transition: 'width 0.04s linear',
              boxShadow: '0 0 10px rgba(14, 99, 255, 0.4)',
            }}
          />
        </div>

        <span
          style={{
            fontFamily: "'Archivo', sans-serif",
            fontSize: '13px',
            fontWeight: '700',
            color: '#0e63ff',
            letterSpacing: '0.5px',
          }}
        >
          {progress}%
        </span>
      </div>

      <style jsx global>{`
        @keyframes tp-splash-spin-ring {
          0%   { transform: rotate(0deg); }
          100% { transform: rotate(360deg); }
        }
      `}</style>
    </div>
  );
}
