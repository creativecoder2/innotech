'use client';

import React, { useState } from 'react';
import { KeyRound, ShieldAlert, CheckCircle2 } from 'lucide-react';

export default function ChangePasswordPage() {
  const [currentPassword, setCurrentPassword] = useState('');
  const [newPassword, setNewPassword] = useState('');
  const [confirmPassword, setConfirmPassword] = useState('');
  const [loading, setLoading] = useState(false);
  const [status, setStatus] = useState({ type: '', msg: '' });

  const handleSubmit = async (e) => {
    e.preventDefault();
    setStatus({ type: '', msg: '' });

    if (newPassword !== confirmPassword) {
      setStatus({ type: 'error', msg: 'New password and confirm password do not match.' });
      return;
    }

    if (newPassword.length < 6) {
      setStatus({ type: 'error', msg: 'New password must be at least 6 characters.' });
      return;
    }

    setLoading(true);

    try {
      const res = await fetch('/api/admin/auth/change-password', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ currentPassword, newPassword }),
      });

      const data = await res.json();

      if (res.ok && data.success) {
        setStatus({ type: 'success', msg: data.message || 'Password changed successfully!' });
        setCurrentPassword('');
        setNewPassword('');
        setConfirmPassword('');
      } else {
        setStatus({ type: 'error', msg: data.message || 'Failed to update password.' });
      }
    } catch (err) {
      setStatus({ type: 'error', msg: 'Network error updating password.' });
    } finally {
      setLoading(false);
    }
  };

  return (
    <div style={{ maxWidth: '600px' }}>
      <div style={{ marginBottom: '24px' }}>
        <h1 style={{ fontSize: '24px', fontWeight: '800', color: '#171151', margin: '0 0 6px' }}>
          Change Administrator Password
        </h1>
        <p style={{ fontSize: '14px', color: '#6b6b6b', margin: 0 }}>
          Ensure your admin account is protected by using a strong, unique password.
        </p>
      </div>

      {status.msg && (
        <div
          style={{
            padding: '14px 18px',
            borderRadius: '8px',
            marginBottom: '20px',
            backgroundColor: status.type === 'success' ? '#E7FAF6' : '#FEEAF1',
            color: status.type === 'success' ? '#0b9748' : '#F72A75',
            fontWeight: '600',
            fontSize: '14px',
            border: `1px solid ${status.type === 'success' ? '#a7f3d0' : '#fecdd3'}`,
            display: 'flex',
            alignItems: 'center',
            gap: '8px',
          }}
        >
          {status.type === 'success' ? <CheckCircle2 size={18} /> : <ShieldAlert size={18} />}
          {status.msg}
        </div>
      )}

      <div
        style={{
          backgroundColor: '#ffffff',
          borderRadius: '12px',
          padding: '28px',
          border: '1px solid #ECEEF3',
          boxShadow: '0 4px 12px rgba(0,0,0,0.02)',
        }}
      >
        <form onSubmit={handleSubmit}>
          <div style={{ marginBottom: '20px' }}>
            <label
              style={{
                display: 'block',
                fontSize: '13px',
                fontWeight: '600',
                color: '#171151',
                marginBottom: '6px',
              }}
            >
              Current Password
            </label>
            <input
              type="password"
              placeholder="••••••••"
              value={currentPassword}
              onChange={(e) => setCurrentPassword(e.target.value)}
              required
              style={{
                width: '100%',
                padding: '10px 14px',
                borderRadius: '8px',
                border: '1px solid #D1D6E0',
                fontSize: '14px',
                boxSizing: 'border-box',
              }}
            />
          </div>

          <div style={{ marginBottom: '20px' }}>
            <label
              style={{
                display: 'block',
                fontSize: '13px',
                fontWeight: '600',
                color: '#171151',
                marginBottom: '6px',
              }}
            >
              New Password (min 6 characters)
            </label>
            <input
              type="password"
              placeholder="••••••••"
              value={newPassword}
              onChange={(e) => setNewPassword(e.target.value)}
              required
              style={{
                width: '100%',
                padding: '10px 14px',
                borderRadius: '8px',
                border: '1px solid #D1D6E0',
                fontSize: '14px',
                boxSizing: 'border-box',
              }}
            />
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
              Confirm New Password
            </label>
            <input
              type="password"
              placeholder="••••••••"
              value={confirmPassword}
              onChange={(e) => setConfirmPassword(e.target.value)}
              required
              style={{
                width: '100%',
                padding: '10px 14px',
                borderRadius: '8px',
                border: '1px solid #D1D6E0',
                fontSize: '14px',
                boxSizing: 'border-box',
              }}
            />
          </div>

          <button
            type="submit"
            disabled={loading}
            style={{
              padding: '12px 24px',
              backgroundColor: '#0E63FF',
              color: '#ffffff',
              borderRadius: '8px',
              border: 'none',
              fontWeight: '600',
              fontSize: '14px',
              cursor: 'pointer',
              display: 'inline-flex',
              alignItems: 'center',
              gap: '8px',
            }}
          >
            <KeyRound size={16} />
            <span>{loading ? 'Updating Password...' : 'Save New Password'}</span>
          </button>
        </form>
      </div>
    </div>
  );
}
