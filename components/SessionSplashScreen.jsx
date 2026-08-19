'use client';

import { useState, useEffect } from 'react';

export default function SessionSplashScreen() {
  const [show, setShow] = useState(true);

  useEffect(() => {
    try {
      const hasSeen = sessionStorage.getItem('innotech_initial_splash_done');
      if (hasSeen) {
        setShow(false);
        return;
      }
      sessionStorage.setItem('innotech_initial_splash_done', 'true');

      // Unmount component from DOM after CSS animation completes (2.6s total)
      const timer = setTimeout(() => {
        setShow(false);
      }, 2600);

      return () => clearTimeout(timer);
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
        backgroundColor: '#0a192f',
        backgroundImage: `linear-gradient(135deg, rgba(10, 25, 47, 0.85) 0%, rgba(15, 23, 42, 0.9) 100%), url('/assets/img/splash-bg.jpg')`,
        backgroundSize: 'cover',
        backgroundPosition: 'center',
        display: 'flex',
        flexDirection: 'column',
        alignItems: 'center',
        justifyContent: 'center',
        animation: 'tp-splash-master-fade 2.6s cubic-bezier(0.4, 0, 0.2, 1) forwards',
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
          padding: '40px 44px 36px',
          background: 'rgba(255, 255, 255, 0.96)',
          backdropFilter: 'blur(24px)',
          WebkitBackdropFilter: 'blur(24px)',
          borderRadius: '24px',
          boxShadow: '0 35px 70px -15px rgba(0, 0, 0, 0.4), 0 0 0 1px rgba(255, 255, 255, 0.4)',
          maxWidth: '450px',
          width: '88%',
          boxSizing: 'border-box',
          animation: 'tp-splash-card-pop 0.35s ease-out',
        }}
      >
        {/* Logo Container */}
        <div
          style={{
            position: 'relative',
            width: '86px',
            height: '86px',
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
              animation: 'tp-splash-spin-ring 0.9s linear infinite',
            }}
          />
          <img
            src="/assets/img/logo/favicon.png"
            alt="Innotech Medical"
            style={{
              width: '68px',
              height: '68px',
              objectFit: 'contain',
            }}
          />
        </div>

        <h2
          style={{
            fontFamily: "'Days One', 'Archivo', sans-serif",
            fontSize: '20px',
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

        {/* Pure GPU CSS Animated Progress Bar (Starts 0th Millisecond Instant) */}
        <div
          style={{
            width: '100%',
            height: '7px',
            background: '#e2e8f0',
            borderRadius: '6px',
            overflow: 'hidden',
            position: 'relative',
            marginBottom: '14px',
          }}
        >
          <div
            style={{
              height: '100%',
              background: 'linear-gradient(90deg, #0e63ff 0%, #10d0a1 50%, #0e63ff 100%)',
              backgroundSize: '200% 100%',
              borderRadius: '6px',
              animation: 'tp-splash-bar-grow 2.2s cubic-bezier(0.2, 0.8, 0.2, 1) forwards',
              boxShadow: '0 0 12px rgba(14, 99, 255, 0.6)',
            }}
          />
        </div>

        <span
          style={{
            fontFamily: "'Archivo', sans-serif",
            fontSize: '12.5px',
            fontWeight: '700',
            color: '#0e63ff',
            letterSpacing: '0.6px',
            textTransform: 'uppercase',
          }}
        >
          Loading Healthcare Systems...
        </span>
      </div>

      <style jsx global>{`
        @keyframes tp-splash-bar-grow {
          0%   { width: 0%; }
          50%  { width: 65%; }
          85%  { width: 92%; }
          100% { width: 100%; }
        }

        @keyframes tp-splash-spin-ring {
          0%   { transform: rotate(0deg); }
          100% { transform: rotate(360deg); }
        }

        @keyframes tp-splash-card-pop {
          from { transform: scale(0.94); opacity: 0; }
          to   { transform: scale(1); opacity: 1; }
        }

        @keyframes tp-splash-master-fade {
          0%   { opacity: 1; pointer-events: all; }
          82%  { opacity: 1; pointer-events: all; transform: scale(1); }
          100% { opacity: 0; pointer-events: none; transform: scale(1.03); visibility: hidden; }
        }
      `}</style>
    </div>
  );
}
