'use client';

import React, { useState } from 'react';
import { useRouter } from 'next/navigation';
import Link from 'next/link';
import { Lock, Mail, ArrowRight, ShieldCheck } from 'lucide-react';

export default function AdminLoginPage() {
  const router = useRouter();
  const [email, setEmail] = useState('admin@innotech.com');
  const [password, setPassword] = useState('admin123');
  const [loading, setLoading] = useState(false);
  const [errorMsg, setErrorMsg] = useState('');

  const handleLogin = async (e) => {
    e.preventDefault();
    setLoading(true);
    setErrorMsg('');

    try {
      const res = await fetch('/api/admin/auth/login', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ email, password }),
      });

      const data = await res.json();

      if (res.ok && data.success) {
        localStorage.setItem('innotech_admin_user', JSON.stringify(data.user));
        router.push('/admin');
      } else {
        setErrorMsg(data.message || 'Invalid credentials. Please try again.');
      }
    } catch (err) {
      setErrorMsg('Failed to connect to authentication server.');
    } finally {
      setLoading(false);
    }
  };

  return (
    <div
      style={{
        minHeight: '100vh',
        display: 'flex',
        alignItems: 'center',
        justifyContent: 'center',
        background: 'linear-gradient(135deg, #171151 0%, #0E63FF 100%)',
        padding: '20px',
        fontFamily: "'Archivo', sans-serif",
      }}
    >
      <div
        style={{
          width: '100%',
          maxWidth: '440px',
          backgroundColor: '#ffffff',
          borderRadius: '16px',
          padding: '40px 32px',
          boxShadow: '0 20px 40px rgba(0,0,0,0.25)',
        }}
      >
        {/* Header */}
        <div style={{ textAlign: 'center', marginBottom: '30px' }}>
          <Link href="/">
            <img
              src="/assets/img/logo/logo.png"
              alt="Innotech Logo"
              style={{ maxHeight: '42px', marginBottom: '15px' }}
            />
          </Link>
          <h2 style={{ fontSize: '22px', fontWeight: '700', color: '#171151', margin: '0 0 6px' }}>
            Admin Portal Login
          </h2>
          <p style={{ fontSize: '13px', color: '#6b6b6b', margin: 0 }}>
            Enter your administrative credentials to manage dynamic content
          </p>
        </div>

        {/* Error Notification */}
        {errorMsg && (
          <div
            style={{
              padding: '12px 14px',
              backgroundColor: '#feeaf1',
              color: '#F72A75',
              borderRadius: '8px',
              fontSize: '13px',
              marginBottom: '20px',
              border: '1px solid #fecdd3',
            }}
          >
            {errorMsg}
          </div>
        )}

        {/* Login Form */}
        <form onSubmit={handleLogin}>
          <div style={{ marginBottom: '18px' }}>
            <label
              style={{
                display: 'block',
                fontSize: '13px',
                fontWeight: '600',
                color: '#171151',
                marginBottom: '6px',
              }}
            >
              Email Address
            </label>
            <div style={{ position: 'relative' }}>
              <Mail
                size={18}
                style={{
                  position: 'absolute',
                  left: '14px',
                  top: '12px',
                  color: '#A9B7D1',
                }}
              />
              <input
                type="email"
                value={email}
                onChange={(e) => setEmail(e.target.value)}
                placeholder="admin@innotech.com"
                required
                style={{
                  width: '100%',
                  padding: '10px 14px 10px 42px',
                  borderRadius: '8px',
                  border: '1px solid #D1D6E0',
                  fontSize: '14px',
                  color: '#171151',
                  outline: 'none',
                  boxSizing: 'border-box',
                }}
              />
            </div>
          </div>

          <div style={{ marginBottom: '24px' }}>
            <label
              style={{
                display: 'block',
                fontSize: '13px',
                fontWeight: '600',
                color: '#171151',
                marginBottom: '6px',
              }}
            >
              Password
            </label>
            <div style={{ position: 'relative' }}>
              <Lock
                size={18}
                style={{
                  position: 'absolute',
                  left: '14px',
                  top: '12px',
                  color: '#A9B7D1',
                }}
              />
              <input
                type="password"
                value={password}
                onChange={(e) => setPassword(e.target.value)}
                placeholder="••••••••"
                required
                style={{
                  width: '100%',
                  padding: '10px 14px 10px 42px',
                  borderRadius: '8px',
                  border: '1px solid #D1D6E0',
                  fontSize: '14px',
                  color: '#171151',
                  outline: 'none',
                  boxSizing: 'border-box',
                }}
              />
            </div>
          </div>

          <button
            type="submit"
            disabled={loading}
            style={{
              width: '100%',
              padding: '12px',
              backgroundColor: '#0E63FF',
              color: '#ffffff',
              border: 'none',
              borderRadius: '8px',
              fontSize: '15px',
              fontWeight: '600',
              cursor: 'pointer',
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'center',
              gap: '8px',
              transition: 'background 0.2s',
            }}
          >
            <span>{loading ? 'Authenticating...' : 'Sign In to Dashboard'}</span>
            <ArrowRight size={16} />
          </button>
        </form>

        {/* Demo Credentials Box */}
        <div
          style={{
            marginTop: '25px',
            padding: '14px',
            backgroundColor: '#F2F5FA',
            borderRadius: '8px',
            border: '1px dashed #D1D6E0',
            fontSize: '12px',
            color: '#6b6b6b',
          }}
        >
          <div
            style={{
              fontWeight: '600',
              color: '#171151',
              marginBottom: '4px',
              display: 'flex',
              alignItems: 'center',
              gap: '6px',
            }}
          >
            <ShieldCheck size={14} color="#0b9748" /> Default Access Credentials:
          </div>
          <div>
            <strong>Email:</strong> admin@innotech.com
          </div>
          <div>
            <strong>Password:</strong> admin123
          </div>
        </div>

        <div style={{ marginTop: '20px', textAlign: 'center' }}>
          <Link
            href="/"
            style={{
              fontSize: '13px',
              color: '#0E63FF',
              textDecoration: 'none',
              fontWeight: '500',
            }}
          >
            ← Back to Public Website
          </Link>
        </div>
      </div>
    </div>
  );
}
