'use client';

import React, { useEffect } from 'react';
import Link from 'next/link';

export default function GlobalError({ error, reset }) {
  useEffect(() => {
    console.error('App Error Boundary caught:', error);
  }, [error]);

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
        <div style={{ fontSize: '48px', marginBottom: '16px' }}>⚠️</div>
        <h2 style={{ fontSize: '24px', fontWeight: '700', color: '#171151', marginBottom: '12px' }}>
          Something went wrong!
        </h2>
        <p style={{ color: '#64748b', fontSize: '14px', lineHeight: '1.6', marginBottom: '24px' }}>
          An unexpected issue occurred while rendering this page.
        </p>
        <div style={{ display: 'flex', gap: '12px', justifyContent: 'center' }}>
          <button
            onClick={() => reset()}
            style={{
              padding: '10px 22px',
              backgroundColor: '#0E63FF',
              color: '#ffffff',
              borderRadius: '6px',
              fontWeight: '700',
              fontSize: '13px',
              border: 'none',
              cursor: 'pointer',
            }}
          >
            Try Again
          </button>
          <Link
            href="/"
            style={{
              padding: '10px 22px',
              backgroundColor: '#F1F5F9',
              color: '#171151',
              borderRadius: '6px',
              fontWeight: '700',
              fontSize: '13px',
              textDecoration: 'none',
            }}
          >
            Go to Home
          </Link>
        </div>
      </div>
    </div>
  );
}
