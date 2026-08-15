'use client';

import React, { useState, useEffect } from 'react';
import {
  Mail,
  MailCheck,
  Search,
  Trash2,
  Copy,
  Download,
  RefreshCw,
  CheckCircle2,
  AlertTriangle,
  Calendar,
  Layers,
  Check,
  ChevronLeft,
  ChevronRight,
} from 'lucide-react';
import { fallbackSubscribers } from '@/lib/data';

export default function AdminNewsletterPage() {
  const [subscribers, setSubscribers] = useState(fallbackSubscribers);
  const [loading, setLoading] = useState(false);
  const [search, setSearch] = useState('');
  const [sourceFilter, setSourceFilter] = useState('all');
  const [toast, setToast] = useState({ type: '', msg: '' });
  const [copied, setCopied] = useState(false);

  // Pagination state
  const [currentPage, setCurrentPage] = useState(1);
  const pageSize = 10;

  // Custom Delete Modal Popup
  const [deleteModal, setDeleteModal] = useState({ open: false, id: null, email: '' });
  const [deleting, setDeleting] = useState(false);

  useEffect(() => {
    fetchSubscribers();
  }, []);

  const showToast = (msg, type = 'success') => {
    setToast({ type, msg });
    setTimeout(() => {
      setToast((prev) => (prev.msg === msg ? { type: '', msg: '' } : prev));
    }, 4000);
  };

  const fetchSubscribers = async () => {
    setLoading(true);
    try {
      const res = await fetch('/api/admin/newsletter');
      const data = await res.json();
      if (data.success && Array.isArray(data.data)) {
        setSubscribers(data.data);
      }
    } catch (e) {
      console.error('Error fetching subscribers:', e);
    } finally {
      setLoading(false);
    }
  };

  const handleConfirmDelete = async () => {
    if (!deleteModal.id) return;
    setDeleting(true);

    try {
      const res = await fetch('/api/admin/newsletter', {
        method: 'DELETE',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ ids: [deleteModal.id] }),
      });
      const data = await res.json();
      if (res.ok && data.success) {
        showToast(`Subscriber "${deleteModal.email}" removed successfully!`);
        setDeleteModal({ open: false, id: null, email: '' });
        if (typeof window !== 'undefined') {
          window.dispatchEvent(new Event('innotech_notifications_updated'));
        }
        fetchSubscribers();
      } else {
        showToast(data.message || 'Error deleting subscriber', 'error');
      }
    } catch (err) {
      showToast('Network error deleting subscriber', 'error');
    } finally {
      setDeleting(false);
    }
  };

  const handleCopyAllEmails = () => {
    const emailList = subscribers.map((s) => s.email).join(', ');
    navigator.clipboard.writeText(emailList);
    setCopied(true);
    showToast(`Copied ${subscribers.length} email address(es) to clipboard!`);
    setTimeout(() => setCopied(false), 3000);
  };

  const handleExportCSV = () => {
    const headers = 'ID,Email,Source,Status,SubscribedAt\n';
    const rows = subscribers
      .map(
        (s) =>
          `"${s._id}","${s.email}","${s.source || 'Footer Newsletter'}","${s.status || 'active'}","${
            s.createdAt || ''
          }"`
      )
      .join('\n');
    const blob = new Blob([headers + rows], { type: 'text/csv;charset=utf-8;' });
    const url = URL.createObjectURL(blob);
    const link = document.createElement('a');
    link.setAttribute('href', url);
    link.setAttribute('download', `innotech_newsletter_subscribers_${new Date().toISOString().split('T')[0]}.csv`);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    showToast('Exported CSV file successfully!');
  };

  const filtered = subscribers.filter((s) => {
    const matchSearch =
      search === '' ||
      (s.email && s.email.toLowerCase().includes(search.toLowerCase())) ||
      (s.source && s.source.toLowerCase().includes(search.toLowerCase()));
    const matchSource =
      sourceFilter === 'all'
        ? true
        : sourceFilter === 'footer'
        ? s.source?.toLowerCase().includes('footer')
        : s.source?.toLowerCase().includes('header');
    return matchSearch && matchSource;
  });

  return (
    <div style={{ maxWidth: '1400px', margin: '0 auto', paddingBottom: '60px' }}>
      {/* Header Bar */}
      <div
        style={{
          display: 'flex',
          justifyContent: 'space-between',
          alignItems: 'center',
          flexWrap: 'wrap',
          gap: '15px',
          marginBottom: '24px',
          backgroundColor: '#ffffff',
          padding: '20px 24px',
          borderRadius: '12px',
          border: '1px solid #ECEEF3',
          boxShadow: '0 2px 8px rgba(0,0,0,0.04)',
        }}
      >
        <div>
          <h1
            style={{
              fontSize: '24px',
              fontWeight: '800',
              color: '#171151',
              margin: '0 0 6px',
              display: 'flex',
              alignItems: 'center',
              gap: '10px',
            }}
          >
            <span>Newsletter Subscribers</span>
            <span
              style={{
                fontSize: '12px',
                fontWeight: '600',
                backgroundColor: '#E7FAF6',
                color: '#0b9748',
                padding: '4px 10px',
                borderRadius: '20px',
              }}
            >
              {subscribers.length} Subscribers
            </span>
          </h1>
          <p style={{ fontSize: '14px', color: '#6b6b6b', margin: 0 }}>
            Manage email subscribers captured from website footer newsletter and header side navigation.
          </p>
        </div>

        <div style={{ display: 'flex', gap: '10px', flexWrap: 'wrap' }}>
          <button
            onClick={handleCopyAllEmails}
            style={{
              display: 'inline-flex',
              alignItems: 'center',
              gap: '8px',
              padding: '10px 18px',
              backgroundColor: '#EFF6FF',
              color: '#0E63FF',
              borderRadius: '8px',
              border: '1px solid #BFDBFE',
              fontWeight: '600',
              fontSize: '13px',
              cursor: 'pointer',
            }}
          >
            {copied ? <Check size={16} /> : <Copy size={16} />}
            <span>{copied ? 'Copied!' : 'Copy All Emails'}</span>
          </button>

          <button
            onClick={handleExportCSV}
            style={{
              display: 'inline-flex',
              alignItems: 'center',
              gap: '8px',
              padding: '10px 18px',
              backgroundColor: '#0b9748',
              color: '#ffffff',
              borderRadius: '8px',
              border: 'none',
              fontWeight: '600',
              fontSize: '13px',
              cursor: 'pointer',
              boxShadow: '0 4px 12px rgba(11, 151, 72, 0.2)',
            }}
          >
            <Download size={16} />
            <span>Export CSV</span>
          </button>
        </div>
      </div>

      {/* Toast Notification */}
      {toast.msg && (
        <div
          style={{
            padding: '14px 20px',
            marginBottom: '20px',
            borderRadius: '10px',
            display: 'flex',
            alignItems: 'center',
            gap: '12px',
            fontSize: '14px',
            fontWeight: '700',
            backgroundColor: toast.type === 'error' ? '#FEEAF1' : '#E7FAF6',
            color: toast.type === 'error' ? '#F72A75' : '#0b9748',
            border: `1.5px solid ${toast.type === 'error' ? '#FDCAD9' : '#A3EAD8'}`,
          }}
        >
          {toast.type === 'error' ? <AlertTriangle size={20} /> : <CheckCircle2 size={20} />}
          <span>{toast.msg}</span>
        </div>
      )}

      {/* Metrics Row */}
      <div
        style={{
          display: 'grid',
          gridTemplateColumns: 'repeat(auto-fit, minmax(220px, 1fr))',
          gap: '16px',
          marginBottom: '24px',
        }}
      >
        <div
          style={{
            backgroundColor: '#ffffff',
            padding: '20px',
            borderRadius: '10px',
            border: '1px solid #ECEEF3',
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'space-between',
          }}
        >
          <div>
            <div style={{ fontSize: '13px', color: '#64748B', fontWeight: '600', marginBottom: '4px' }}>
              Total Subscribers
            </div>
            <div style={{ fontSize: '26px', fontWeight: '800', color: '#171151' }}>{subscribers.length}</div>
          </div>
          <div
            style={{
              width: '46px',
              height: '46px',
              borderRadius: '10px',
              backgroundColor: '#EFF6FF',
              color: '#0E63FF',
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'center',
            }}
          >
            <Mail size={22} />
          </div>
        </div>

        <div
          style={{
            backgroundColor: '#ffffff',
            padding: '20px',
            borderRadius: '10px',
            border: '1px solid #ECEEF3',
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'space-between',
          }}
        >
          <div>
            <div style={{ fontSize: '13px', color: '#64748B', fontWeight: '600', marginBottom: '4px' }}>
              Footer Newsletter
            </div>
            <div style={{ fontSize: '26px', fontWeight: '800', color: '#0b9748' }}>
              {subscribers.filter((s) => s.source?.toLowerCase().includes('footer')).length}
            </div>
          </div>
          <div
            style={{
              width: '46px',
              height: '46px',
              borderRadius: '10px',
              backgroundColor: '#E7FAF6',
              color: '#0b9748',
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'center',
            }}
          >
            <MailCheck size={22} />
          </div>
        </div>

        <div
          style={{
            backgroundColor: '#ffffff',
            padding: '20px',
            borderRadius: '10px',
            border: '1px solid #ECEEF3',
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'space-between',
          }}
        >
          <div>
            <div style={{ fontSize: '13px', color: '#64748B', fontWeight: '600', marginBottom: '4px' }}>
              Sidebar & Header
            </div>
            <div style={{ fontSize: '26px', fontWeight: '800', color: '#8B5CF6' }}>
              {subscribers.filter((s) => s.source?.toLowerCase().includes('header') || s.source?.toLowerCase().includes('sidebar')).length}
            </div>
          </div>
          <div
            style={{
              width: '46px',
              height: '46px',
              borderRadius: '10px',
              backgroundColor: '#F5F3FF',
              color: '#8B5CF6',
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'center',
            }}
          >
            <Layers size={22} />
          </div>
        </div>
      </div>

      {/* Main Table Container */}
      <div
        style={{
          backgroundColor: '#ffffff',
          borderRadius: '12px',
          border: '1px solid #ECEEF3',
          padding: '24px',
          boxShadow: '0 2px 8px rgba(0,0,0,0.02)',
        }}
      >
        {/* Controls Bar */}
        <div
          style={{
            display: 'flex',
            justifyContent: 'space-between',
            alignItems: 'center',
            flexWrap: 'wrap',
            gap: '15px',
            marginBottom: '20px',
          }}
        >
          {/* Source Tabs */}
          <div style={{ display: 'flex', gap: '8px', flexWrap: 'wrap' }}>
            {[
              { id: 'all', label: 'All Sources' },
              { id: 'footer', label: 'Footer Newsletter' },
              { id: 'header', label: 'Header Sidebar' },
            ].map((tab) => (
              <button
                key={tab.id}
                onClick={() => setSourceFilter(tab.id)}
                style={{
                  padding: '8px 16px',
                  borderRadius: '20px',
                  border: sourceFilter === tab.id ? '2px solid #0E63FF' : '1px solid #E2E8F0',
                  backgroundColor: sourceFilter === tab.id ? '#EFF6FF' : '#ffffff',
                  color: sourceFilter === tab.id ? '#0E63FF' : '#64748B',
                  fontWeight: sourceFilter === tab.id ? '700' : '500',
                  fontSize: '13px',
                  cursor: 'pointer',
                }}
              >
                {tab.label}
              </button>
            ))}
          </div>

          {/* Search Bar & Refresh */}
          <div style={{ display: 'flex', gap: '10px', alignItems: 'center', flex: '1', maxWidth: '380px' }}>
            <div style={{ position: 'relative', width: '100%' }}>
              <Search
                size={16}
                style={{
                  position: 'absolute',
                  left: '12px',
                  top: '50%',
                  transform: 'translateY(-50%)',
                  color: '#94A3B8',
                }}
              />
              <input
                type="text"
                placeholder="Search email..."
                value={search}
                onChange={(e) => setSearch(e.target.value)}
                style={{
                  width: '100%',
                  padding: '9px 12px 9px 36px',
                  borderRadius: '8px',
                  border: '1px solid #CBD5E1',
                  fontSize: '13px',
                  boxSizing: 'border-box',
                }}
              />
            </div>

            <button
              onClick={fetchSubscribers}
              disabled={loading}
              style={{
                display: 'inline-flex',
                alignItems: 'center',
                gap: '6px',
                padding: '9px 14px',
                borderRadius: '8px',
                backgroundColor: '#F8FAFC',
                border: '1px solid #CBD5E1',
                color: '#475569',
                cursor: 'pointer',
                fontSize: '13px',
              }}
              title="Refresh"
            >
              <RefreshCw size={14} className={loading ? 'animate-spin' : ''} />
            </button>
          </div>
        </div>

        {/* Table */}
        {loading && subscribers.length === 0 ? (
          <div style={{ padding: '60px 20px', textAlign: 'center', color: '#64748B' }}>
            <RefreshCw size={30} className="animate-spin" style={{ margin: '0 auto 10px', color: '#0E63FF' }} />
            <p style={{ fontWeight: '600' }}>Loading subscribers...</p>
          </div>
        ) : filtered.length === 0 ? (
          <div
            style={{
              padding: '60px 20px',
              textAlign: 'center',
              backgroundColor: '#F8FAFC',
              borderRadius: '10px',
              border: '1px dashed #CBD5E1',
            }}
          >
            <Mail size={40} style={{ margin: '0 auto 12px', color: '#94A3B8' }} />
            <h4 style={{ fontSize: '16px', fontWeight: '700', color: '#171151', margin: '0 0 6px' }}>
              No subscribers found
            </h4>
            <p style={{ fontSize: '13px', color: '#64748B', margin: 0 }}>
              {search ? 'No emails match your search.' : 'There are no newsletter subscribers yet.'}
            </p>
          </div>
        ) : (
          <div style={{ overflowX: 'auto' }}>
            <table style={{ width: '100%', borderCollapse: 'collapse', fontSize: '14px' }}>
              <thead>
                <tr style={{ color: '#64748B', borderBottom: '1.5px solid #ECEEF3', textAlign: 'left' }}>
                  <th style={{ padding: '12px 16px' }}>#</th>
                  <th style={{ padding: '12px 16px' }}>Subscriber Email</th>
                  <th style={{ padding: '12px 16px' }}>Capture Source</th>
                  <th style={{ padding: '12px 16px' }}>Date Subscribed</th>
                  <th style={{ padding: '12px 16px' }}>Status</th>
                  <th style={{ padding: '12px 16px', textAlign: 'right' }}>Actions</th>
                </tr>
              </thead>
              <tbody>
                {filtered.slice((currentPage - 1) * pageSize, currentPage * pageSize).map((item, idx) => {
                  const globalIndex = (currentPage - 1) * pageSize + idx + 1;
                  return (
                    <tr
                      key={item._id || idx}
                      style={{
                        borderBottom: '1px solid #F1F5F9',
                        transition: 'background-color 0.15s ease',
                      }}
                    >
                      <td style={{ padding: '14px 16px', color: '#94A3B8', fontWeight: '600' }}>{globalIndex}</td>
                      <td style={{ padding: '14px 16px', fontWeight: '700', color: '#171151' }}>
                        <div style={{ display: 'flex', alignItems: 'center', gap: '8px' }}>
                          <Mail size={16} color="#0E63FF" />
                          <a href={`mailto:${item.email}`} style={{ color: '#171151', textDecoration: 'none' }}>
                            {item.email}
                          </a>
                        </div>
                      </td>
                      <td style={{ padding: '14px 16px', color: '#475569' }}>
                        <span
                          style={{
                            display: 'inline-block',
                            padding: '3px 10px',
                            borderRadius: '12px',
                            fontSize: '12px',
                            fontWeight: '600',
                            backgroundColor: item.source?.includes('Header') ? '#F5F3FF' : '#EFF6FF',
                            color: item.source?.includes('Header') ? '#8B5CF6' : '#0E63FF',
                          }}
                        >
                          {item.source || 'Footer Newsletter'}
                        </span>
                      </td>
                      <td style={{ padding: '14px 16px', color: '#64748B', fontSize: '13px' }}>
                        <div style={{ display: 'flex', alignItems: 'center', gap: '6px' }}>
                          <Calendar size={14} color="#94A3B8" />
                          <span>{item.createdAt ? new Date(item.createdAt).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' }) : 'Recent'}</span>
                        </div>
                      </td>
                      <td style={{ padding: '14px 16px' }}>
                        <span
                          style={{
                            display: 'inline-flex',
                            alignItems: 'center',
                            gap: '4px',
                            padding: '3px 8px',
                            borderRadius: '4px',
                            fontSize: '12px',
                            fontWeight: '700',
                            backgroundColor: '#E7FAF6',
                            color: '#0b9748',
                          }}
                        >
                          <CheckCircle2 size={12} />
                          Active
                        </span>
                      </td>
                      <td style={{ padding: '14px 16px', textAlign: 'right' }}>
                        <button
                          onClick={() => setDeleteModal({ open: true, id: item._id, email: item.email })}
                          style={{
                            display: 'inline-flex',
                            alignItems: 'center',
                            gap: '4px',
                            padding: '6px 12px',
                            borderRadius: '6px',
                            backgroundColor: '#FFF1F2',
                            color: '#E11D48',
                            border: '1px solid #FDA4AF',
                            fontSize: '12px',
                            fontWeight: '600',
                            cursor: 'pointer',
                          }}
                        >
                          <Trash2 size={13} />
                          <span>Remove</span>
                        </button>
                      </td>
                    </tr>
                  );
                })}
              </tbody>
            </table>
          </div>
        )}

        {/* Pagination Bar */}
        {filtered.length > pageSize && (
          <div
            style={{
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'space-between',
              padding: '16px 24px',
              borderTop: '1px solid #F1F5F9',
              flexWrap: 'wrap',
              gap: '12px',
            }}
          >
            <span style={{ fontSize: '13px', color: '#64748B' }}>
              Showing {(currentPage - 1) * pageSize + 1} to {Math.min(currentPage * pageSize, filtered.length)} of {filtered.length} subscribers
            </span>
            <div style={{ display: 'flex', alignItems: 'center', gap: '6px' }}>
              <button
                type="button"
                disabled={currentPage === 1}
                onClick={() => setCurrentPage((p) => Math.max(p - 1, 1))}
                style={{
                  padding: '6px 12px',
                  borderRadius: '6px',
                  border: '1px solid #D1D6E0',
                  backgroundColor: currentPage === 1 ? '#F8FAFC' : '#ffffff',
                  color: currentPage === 1 ? '#CBD5E1' : '#171151',
                  cursor: currentPage === 1 ? 'not-allowed' : 'pointer',
                  display: 'flex',
                  alignItems: 'center',
                  gap: '4px',
                  fontSize: '12px',
                  fontWeight: '600',
                }}
              >
                <ChevronLeft size={14} />
                <span>Previous</span>
              </button>
              <span style={{ fontSize: '13px', fontWeight: '700', padding: '0 8px', color: '#171151' }}>
                Page {currentPage} of {Math.ceil(filtered.length / pageSize) || 1}
              </span>
              <button
                type="button"
                disabled={currentPage >= Math.ceil(filtered.length / pageSize)}
                onClick={() => setCurrentPage((p) => Math.min(p + 1, Math.ceil(filtered.length / pageSize)))}
                style={{
                  padding: '6px 12px',
                  borderRadius: '6px',
                  border: '1px solid #D1D6E0',
                  backgroundColor: currentPage >= Math.ceil(filtered.length / pageSize) ? '#F8FAFC' : '#ffffff',
                  color: currentPage >= Math.ceil(filtered.length / pageSize) ? '#CBD5E1' : '#171151',
                  cursor: currentPage >= Math.ceil(filtered.length / pageSize) ? 'not-allowed' : 'pointer',
                  display: 'flex',
                  alignItems: 'center',
                  gap: '4px',
                  fontSize: '12px',
                  fontWeight: '600',
                }}
              >
                <span>Next</span>
                <ChevronRight size={14} />
              </button>
            </div>
          </div>
        )}
      </div>

      {/* Custom Delete Confirmation Modal Popup */}
      {deleteModal.open && (
        <div
          style={{
            position: 'fixed',
            inset: 0,
            zIndex: 999999,
            backgroundColor: 'rgba(15, 23, 42, 0.65)',
            backdropFilter: 'blur(4px)',
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'center',
            padding: '20px',
          }}
          onClick={() => !deleting && setDeleteModal({ open: false, id: null, email: '' })}
        >
          <div
            style={{
              backgroundColor: '#ffffff',
              borderRadius: '16px',
              maxWidth: '460px',
              width: '100%',
              padding: '28px',
              textAlign: 'center',
              boxShadow: '0 20px 40px rgba(0,0,0,0.15)',
            }}
            onClick={(e) => e.stopPropagation()}
          >
            <div
              style={{
                width: '56px',
                height: '56px',
                borderRadius: '50%',
                backgroundColor: '#FEEAF1',
                color: '#F72A75',
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'center',
                margin: '0 auto 16px',
              }}
            >
              <AlertTriangle size={28} />
            </div>

            <h3 style={{ fontSize: '19px', fontWeight: '800', color: '#171151', margin: '0 0 8px' }}>
              Confirm Subscriber Removal
            </h3>

            <p style={{ fontSize: '14px', color: '#64748B', margin: '0 0 24px', lineHeight: '1.5' }}>
              Are you sure you want to remove <strong style={{ color: '#171151' }}>&ldquo;{deleteModal.email}&rdquo;</strong> from the newsletter mailing list?
            </p>

            <div style={{ display: 'flex', gap: '12px' }}>
              <button
                type="button"
                disabled={deleting}
                onClick={() => setDeleteModal({ open: false, id: null, email: '' })}
                style={{
                  flex: 1,
                  padding: '11px 18px',
                  backgroundColor: '#F1F5F9',
                  color: '#475569',
                  border: 'none',
                  borderRadius: '8px',
                  fontWeight: '600',
                  cursor: deleting ? 'not-allowed' : 'pointer',
                  fontSize: '14px',
                }}
              >
                Cancel
              </button>

              <button
                type="button"
                disabled={deleting}
                onClick={handleConfirmDelete}
                style={{
                  flex: 1,
                  padding: '11px 18px',
                  backgroundColor: '#F72A75',
                  color: '#fff',
                  border: 'none',
                  borderRadius: '8px',
                  fontWeight: '600',
                  cursor: deleting ? 'not-allowed' : 'pointer',
                  fontSize: '14px',
                }}
              >
                {deleting ? 'Removing...' : 'Yes, Remove'}
              </button>
            </div>
          </div>
        </div>
      )}
    </div>
  );
}
