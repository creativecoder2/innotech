'use client';

import React from 'react';
import Link from 'next/link';

export default function NotFound() {
  return (
    <div
      style={{
        minHeight: '70vh',
        display: 'flex',
        alignItems: 'center',
        justifyContent: 'center',
        padding: '60px 20px',
        textAlign: 'center',
        fontFamily: "'Archivo', sans-serif",
      }}
    >
      <div style={{ maxWidth: '520px' }}>
        <div style={{ fontSize: '72px', fontWeight: '800', color: '#0E63FF', lineHeight: 1, marginBottom: '16px' }}>
          404
        </div>
        <h2 style={{ fontSize: '26px', fontWeight: '700', color: '#171151', marginBottom: '12px' }}>
          Page Not Found
        </h2>
        <p style={{ color: '#64748b', fontSize: '15px', lineHeight: '1.6', marginBottom: '28px' }}>
          The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.
        </p>
        <Link
          href="/"
          style={{
            display: 'inline-block',
            padding: '12px 28px',
            backgroundColor: '#0E63FF',
            color: '#ffffff',
            borderRadius: '8px',
            fontWeight: '700',
            fontSize: '14px',
            textDecoration: 'none',
            boxShadow: '0 4px 14px rgba(14, 99, 255, 0.3)',
          }}
        >
          Return to Home
        </Link>
      </div>
    </div>
  );
}
