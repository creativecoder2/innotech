'use client';

import React, { useState, useEffect, useRef } from 'react';
import Link from 'next/link';
import {
  Layers,
  Plus,
  Trash2,
  Edit,
  Eye,
  EyeOff,
  Save,
  CheckCircle2,
  AlertTriangle,
  Upload,
  ExternalLink,
  Table as TableIcon,
  HelpCircle,
  FileText,
  Sparkles,
  ArrowRight,
  RefreshCw,
  X,
} from 'lucide-react';
import { fallbackServices, fallbackHeaderConfig } from '@/lib/data';

function FileUploadField({
  label,
  value,
  onChange,
  accept = 'image/*',
  placeholder = 'Upload image or enter URL...',
  helperText,
}) {
  const [uploading, setUploading] = useState(false);
  const fileInputRef = useRef(null);

  const handleFileChange = async (e) => {
    const file = e.target.files?.[0];
    if (!file) return;

    setUploading(true);
    const formData = new FormData();
    formData.append('file', file);

    try {
      const res = await fetch('/api/upload', {
        method: 'POST',
        body: formData,
      });
      const data = await res.json();
      if (res.ok && data.url) {
        onChange(data.url);
      } else {
        alert(data.message || 'Upload failed');
      }
    } catch (err) {
      console.error(err);
      alert('Error uploading file');
    } finally {
      setUploading(false);
      if (fileInputRef.current) fileInputRef.current.value = '';
    }
  };

  return (
    <div style={{ marginBottom: '14px' }}>
      {label && (
        <label style={{ display: 'block', fontSize: '13px', fontWeight: '700', color: '#171151', marginBottom: '6px' }}>
          {label}
        </label>
      )}
      <div style={{ display: 'flex', gap: '8px', alignItems: 'center' }}>
        {value && (
          <div
            style={{
              width: '44px',
              height: '44px',
              borderRadius: '6px',
              overflow: 'hidden',
              border: '1px solid #CBD5E1',
              backgroundColor: '#F8FAFC',
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'center',
              flexShrink: 0,
            }}
          >
            <img src={value} alt="Preview" style={{ maxWidth: '100%', maxHeight: '100%', objectFit: 'cover' }} />
          </div>
        )}
        <input
          type="text"
          value={value || ''}
          onChange={(e) => onChange(e.target.value)}
          placeholder={placeholder}
          style={{
            flex: 1,
            padding: '9px 12px',
            borderRadius: '6px',
            border: '1px solid #D1D6E0',
            fontSize: '13px',
            boxSizing: 'border-box',
          }}
        />
        <input
          type="file"
          ref={fileInputRef}
          onChange={handleFileChange}
          accept={accept}
          style={{ display: 'none' }}
        />
        <button
          type="button"
          onClick={() => fileInputRef.current?.click()}
          disabled={uploading}
          style={{
            display: 'inline-flex',
            alignItems: 'center',
            gap: '6px',
            padding: '9px 14px',
            backgroundColor: '#F1F5F9',
            color: '#0E63FF',
            border: '1px solid #CBD5E1',
            borderRadius: '6px',
            fontSize: '12px',
            fontWeight: '700',
            cursor: uploading ? 'not-allowed' : 'pointer',
            whiteSpace: 'nowrap',
          }}
        >
          <Upload size={14} />
          <span>{uploading ? '...' : 'Browse'}</span>
        </button>
      </div>
      {helperText && (
        <span style={{ display: 'block', fontSize: '11px', color: '#64748B', marginTop: '4px' }}>
          {helperText}
        </span>
      )}
    </div>
  );
}

const emptyService = {
  title: '',
  slug: '',
  category: 'Medical Equipment & Devices',
  iconClass: 'flaticon-hemoglobin-test-meter',
  iconTheme: 'blue',
  description: '',
  bannerImage: '/assets/img/banner/breadcrumb-01.jpg',
  bannerSubTitle: 'Precision Medical Equipment & Healthcare Solutions',
  image1: '/assets/img/services/services-thumb-07.jpg',
  image2: '/assets/img/services/services-thumb-08.jpg',
  showcaseBanner: '/assets/img/services/services-thumb-09.jpg',
  processTitle: 'Technical Overview & Clinical Integration',
  processText: '',
  processPoints: [
    'FDA, CE, and ISO certified medical equipment compliant with international standards.',
    'Seamless integration with hospital information systems and ICU workflows.',
    'Precision calibration, preventive maintenance, and genuine replacement components.',
    '24/7 dedicated biomedical engineering support and rapid technical dispatch.',
  ],
  stepsTitle: '4 Simple Deployment Steps',
  stepsText: 'Our streamlined turnkey approach ensures minimal equipment downtime and rapid clinical handover.',
  stepPoints1: ['Needs Assessment & Planning', 'Biomedical Specification Review'],
  stepPoints2: ['Turnkey Procurement', 'On-Site Mechanical & Electrical Setup'],
  stepPoints3: ['Clinical Staff Training', '24/7 Emergency Support'],
  specsTable: [
    { feature: 'Certification', spec: 'FDA, CE, ISO 13485' },
    { feature: 'Warranty', spec: '2 Years Comprehensive OEM Warranty' },
    { feature: 'Support', spec: '24/7 Biomedical Support across Pakistan' },
  ],
  fullContent: '',
  faq: [],
  enabled: true,
  showInHeader: true,
};

export default function AdminServicesCMS() {
  const [services, setServices] = useState(fallbackServices);
  const [activeTab, setActiveTab] = useState('list'); // 'list' or 'editor'
  const [editorMode, setEditorMode] = useState('edit'); // 'edit' or 'preview'
  const [currentService, setCurrentService] = useState(emptyService);
  const [isEditing, setIsEditing] = useState(false);
  const [saving, setSaving] = useState(false);
  const [toast, setToast] = useState({ type: '', msg: '' });
  const [deleteModal, setDeleteModal] = useState({ open: false, id: null, title: '' });

  useEffect(() => {
    fetchServices();
  }, []);

  const showToast = (msg, type = 'success') => {
    setToast({ type, msg });
    setTimeout(() => {
      setToast((prev) => (prev.msg === msg ? { type: '', msg: '' } : prev));
    }, 4500);
  };

  const fetchServices = async () => {
    try {
      const res = await fetch('/api/admin/services');
      const data = await res.json();
      if (data.data) {
        setServices(data.data);
      }
    } catch (e) {
      console.error('Error fetching services:', e);
    }
  };

  const handleTitleChange = (val) => {
    const autoSlug = val
      .toLowerCase()
      .replace(/[^a-z0-9]+/g, '-')
      .replace(/^-|-$/g, '');

    setCurrentService((prev) => ({
      ...prev,
      title: val,
      slug: !isEditing || !prev.slug ? autoSlug : prev.slug,
    }));
  };

  const handleStartCreate = () => {
    setCurrentService({ ...emptyService, _id: undefined });
    setIsEditing(false);
    setActiveTab('editor');
    setEditorMode('edit');
  };

  const handleStartEdit = (service) => {
    setCurrentService({ ...service });
    setIsEditing(true);
    setActiveTab('editor');
    setEditorMode('edit');
  };

  const handleToggleEnabled = async (service) => {
    const nextState = service.enabled === false ? true : false;
    const updated = { ...service, enabled: nextState };

    try {
      const res = await fetch('/api/admin/services', {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(updated),
      });
      if (res.ok) {
        setServices((prev) => prev.map((s) => (s._id === service._id ? updated : s)));
        showToast(`Page "${service.title}" is now ${nextState ? 'Active & Published' : 'Hidden from Website'}!`);
      }
    } catch (e) {
      showToast('Error updating status', 'error');
    }
  };

  const handleSaveService = async (e) => {
    if (e) e.preventDefault();
    if (!currentService.title.trim()) {
      showToast('Page title is required', 'error');
      return;
    }

    setSaving(true);
    try {
      const method = isEditing ? 'PUT' : 'POST';
      const res = await fetch('/api/admin/services', {
        method,
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(currentService),
      });
      const data = await res.json();

      if (res.ok) {
        showToast(
          `✓ Page "${currentService.title}" saved successfully to live website and database!`
        );
        fetchServices();
        setActiveTab('list');
      } else {
        showToast(data.message || 'Error saving page', 'error');
      }
    } catch (e) {
      showToast('Network error saving page', 'error');
    } finally {
      setSaving(false);
    }
  };

  const handleDeleteConfirm = async () => {
    const { id, title } = deleteModal;
    try {
      const res = await fetch(`/api/admin/services?id=${id}`, {
        method: 'DELETE',
      });
      if (res.ok) {
        setServices((prev) => prev.filter((s) => s._id !== id));
        showToast(`Page "${title}" deleted successfully!`);
        setDeleteModal({ open: false, id: null, title: '' });
      } else {
        showToast('Error deleting page', 'error');
      }
    } catch (e) {
      showToast('Network error deleting page', 'error');
    }
  };

  // Sync to Header Dropdown
  const handleSyncToHeader = async () => {
    try {
      const headerRes = await fetch('/api/admin/header');
      const headerData = await headerRes.json();
      const currentHeader = headerData.data || fallbackHeaderConfig;

      const activeServices = services.filter((s) => s.enabled !== false && s.showInHeader !== false);
      const subItems = activeServices.map((s, idx) => ({
        id: `s-${s.slug || idx}`,
        label: s.title,
        link: `/services/${s.slug}`,
        enabled: true,
      }));

      // Also add 'All Products & Services' link
      subItems.push({
        id: 's-all',
        label: 'All Products & Services',
        link: '/services',
        enabled: true,
      });

      const updatedMenuItems = currentHeader.menuItems.map((item) => {
        if (item.link === '/services' || item.label.toLowerCase().includes('services') || item.hasDropdown) {
          return {
            ...item,
            hasDropdown: true,
            subItems,
          };
        }
        return item;
      });

      const newHeaderConfig = { ...currentHeader, menuItems: updatedMenuItems };
      await fetch('/api/admin/header', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(newHeaderConfig),
      });

      showToast(`✓ Header "Products & Services" dropdown synchronized with ${activeServices.length} active pages!`);
    } catch (e) {
      showToast('Error synchronizing with header', 'error');
    }
  };

  // Specs Table Helpers
  const addSpecRow = () => {
    setCurrentService((prev) => ({
      ...prev,
      specsTable: [...(prev.specsTable || []), { feature: '', spec: '' }],
    }));
  };

  const removeSpecRow = (idx) => {
    setCurrentService((prev) => ({
      ...prev,
      specsTable: (prev.specsTable || []).filter((_, i) => i !== idx),
    }));
  };

  const updateSpecRow = (idx, field, val) => {
    const updated = [...(currentService.specsTable || [])];
    updated[idx][field] = val;
    setCurrentService((prev) => ({ ...prev, specsTable: updated }));
  };

  // Bullet Points Helper
  const addProcessPoint = () => {
    setCurrentService((prev) => ({
      ...prev,
      processPoints: [...(prev.processPoints || []), ''],
    }));
  };

  const removeProcessPoint = (idx) => {
    setCurrentService((prev) => ({
      ...prev,
      processPoints: (prev.processPoints || []).filter((_, i) => i !== idx),
    }));
  };

  const updateProcessPoint = (idx, val) => {
    const updated = [...(currentService.processPoints || [])];
    updated[idx] = val;
    setCurrentService((prev) => ({ ...prev, processPoints: updated }));
  };

  return (
    <div style={{ maxWidth: '1400px', margin: '0 auto', paddingBottom: '60px' }}>
      {/* Top Header Bar */}
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
          boxShadow: '0 2px 8px rgba(0,0,0,0.03)',
        }}
      >
        <div>
          <h1 style={{ fontSize: '24px', fontWeight: '800', color: '#171151', margin: '0 0 6px', display: 'flex', alignItems: 'center', gap: '10px' }}>
            <Layers size={24} color="#0E63FF" />
            <span>Products &amp; Services Page Builder &amp; CMS</span>
          </h1>
          <p style={{ fontSize: '14px', color: '#64748B', margin: 0 }}>
            Create, edit, hide/publish custom product &amp; service pages with rich layouts, specifications, and instant live preview.
          </p>
        </div>

        <div style={{ display: 'flex', gap: '10px', flexWrap: 'wrap' }}>
          <button
            type="button"
            onClick={handleSyncToHeader}
            style={{
              display: 'inline-flex',
              alignItems: 'center',
              gap: '6px',
              padding: '10px 18px',
              backgroundColor: '#EFF6FF',
              color: '#0E63FF',
              borderRadius: '8px',
              border: '1px solid #BFDBFE',
              fontWeight: '700',
              fontSize: '13px',
              cursor: 'pointer',
            }}
          >
            <RefreshCw size={15} />
            <span>Sync Dropdown in Header</span>
          </button>

          {activeTab === 'list' ? (
            <button
              type="button"
              onClick={handleStartCreate}
              style={{
                display: 'inline-flex',
                alignItems: 'center',
                gap: '8px',
                padding: '10px 22px',
                backgroundColor: '#0E63FF',
                color: '#ffffff',
                borderRadius: '8px',
                border: 'none',
                fontWeight: '700',
                fontSize: '13px',
                cursor: 'pointer',
                boxShadow: '0 4px 12px rgba(14, 99, 255, 0.25)',
              }}
            >
              <Plus size={16} />
              <span>Create New Service Page</span>
            </button>
          ) : (
            <button
              type="button"
              onClick={() => setActiveTab('list')}
              style={{
                display: 'inline-flex',
                alignItems: 'center',
                gap: '6px',
                padding: '10px 18px',
                backgroundColor: '#F1F5F9',
                color: '#475569',
                borderRadius: '8px',
                border: 'none',
                fontWeight: '700',
                fontSize: '13px',
                cursor: 'pointer',
              }}
            >
              <span>← Back to All Pages</span>
            </button>
          )}
        </div>
      </div>

      {/* Toast Alert */}
      {toast.msg && (
        <div
          style={{
            padding: '14px 20px',
            marginBottom: '20px',
            borderRadius: '10px',
            display: 'flex',
            alignItems: 'center',
            gap: '10px',
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

      {/* ── TAB 1: ALL PAGES DIRECTORY LIST ── */}
      {activeTab === 'list' && (
        <div style={{ backgroundColor: '#ffffff', borderRadius: '12px', padding: '24px', border: '1px solid #ECEEF3' }}>
          <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '20px' }}>
            <h3 style={{ fontSize: '17px', fontWeight: '800', color: '#171151', margin: 0 }}>
              All Products &amp; Service Pages ({services.length})
            </h3>
            <span style={{ fontSize: '12px', color: '#64748B' }}>
              💡 All active pages can be viewed live at <code>/services/[slug]</code>
            </span>
          </div>

          <div style={{ overflowX: 'auto' }}>
            <table style={{ width: '100%', borderCollapse: 'collapse', fontSize: '13px' }}>
              <thead>
                <tr style={{ color: '#8A879F', borderBottom: '1px solid #ECEEF3', textAlign: 'left' }}>
                  <th style={{ padding: '12px 10px', width: '50px' }}>#</th>
                  <th style={{ padding: '12px 10px' }}>Page Title &amp; Category</th>
                  <th style={{ padding: '12px 10px' }}>Live URL Slug</th>
                  <th style={{ padding: '12px 10px' }}>Header Dropdown</th>
                  <th style={{ padding: '12px 10px' }}>Status</th>
                  <th style={{ padding: '12px 10px', textAlign: 'right' }}>Actions</th>
                </tr>
              </thead>
              <tbody>
                {services.map((item, idx) => (
                  <tr key={item._id || item.slug || idx} style={{ borderBottom: '1px solid #F2F5FA' }}>
                    <td style={{ padding: '14px 10px', color: '#94A3B8', fontWeight: '600' }}>{idx + 1}</td>
                    <td style={{ padding: '14px 10px' }}>
                      <div style={{ display: 'flex', alignItems: 'center', gap: '12px' }}>
                        <div
                          style={{
                            width: '42px',
                            height: '42px',
                            borderRadius: '8px',
                            backgroundColor: '#F8FAFC',
                            border: '1px solid #E2E8F0',
                            display: 'flex',
                            alignItems: 'center',
                            justifyContent: 'center',
                            fontSize: '20px',
                            color: '#0E63FF',
                            flexShrink: 0,
                          }}
                        >
                          <i className={item.iconClass || 'flaticon-hemoglobin-test-meter'}></i>
                        </div>
                        <div>
                          <div style={{ fontWeight: '800', color: '#171151', fontSize: '14px' }}>{item.title}</div>
                          <span style={{ fontSize: '11px', color: '#0E63FF', fontWeight: '700', textTransform: 'uppercase' }}>
                            {item.category || 'General Service'}
                          </span>
                        </div>
                      </div>
                    </td>
                    <td style={{ padding: '14px 10px' }}>
                      <a
                        href={`/services/${item.slug}`}
                        target="_blank"
                        rel="noreferrer"
                        style={{
                          color: '#0E63FF',
                          fontFamily: 'monospace',
                          fontSize: '12px',
                          display: 'inline-flex',
                          alignItems: 'center',
                          gap: '4px',
                          textDecoration: 'none',
                        }}
                      >
                        <span>/services/{item.slug}</span>
                        <ExternalLink size={12} />
                      </a>
                    </td>
                    <td style={{ padding: '14px 10px' }}>
                      {item.showInHeader !== false ? (
                        <span style={{ display: 'inline-flex', alignItems: 'center', gap: '4px', padding: '3px 8px', borderRadius: '4px', backgroundColor: '#EFF6FF', color: '#0E63FF', fontSize: '11px', fontWeight: '700' }}>
                          ✓ In Menu
                        </span>
                      ) : (
                        <span style={{ color: '#94A3B8', fontSize: '12px' }}>No</span>
                      )}
                    </td>
                    <td style={{ padding: '14px 10px' }}>
                      <button
                        type="button"
                        onClick={() => handleToggleEnabled(item)}
                        style={{
                          display: 'inline-flex',
                          alignItems: 'center',
                          gap: '6px',
                          padding: '5px 12px',
                          borderRadius: '20px',
                          border: 'none',
                          fontSize: '12px',
                          fontWeight: '700',
                          backgroundColor: item.enabled !== false ? '#E7FAF6' : '#FEEAF1',
                          color: item.enabled !== false ? '#0b9748' : '#F72A75',
                          cursor: 'pointer',
                        }}
                      >
                        {item.enabled !== false ? <Eye size={13} /> : <EyeOff size={13} />}
                        <span>{item.enabled !== false ? 'Active' : 'Hidden'}</span>
                      </button>
                    </td>
                    <td style={{ padding: '14px 10px', textAlign: 'right' }}>
                      <div style={{ display: 'flex', gap: '8px', justifyContent: 'flex-end' }}>
                        <button
                          type="button"
                          onClick={() => handleStartEdit(item)}
                          style={{
                            display: 'inline-flex',
                            alignItems: 'center',
                            gap: '4px',
                            padding: '6px 12px',
                            backgroundColor: '#EFF6FF',
                            color: '#0E63FF',
                            border: '1px solid #BFDBFE',
                            borderRadius: '6px',
                            fontSize: '12px',
                            fontWeight: '700',
                            cursor: 'pointer',
                          }}
                        >
                          <Edit size={13} /> Edit Page
                        </button>
                        <button
                          type="button"
                          onClick={() => setDeleteModal({ open: true, id: item._id, title: item.title })}
                          style={{
                            padding: '6px 10px',
                            backgroundColor: '#FEEAF1',
                            color: '#F72A75',
                            border: '1px solid #FECDD3',
                            borderRadius: '6px',
                            cursor: 'pointer',
                          }}
                        >
                          <Trash2 size={14} />
                        </button>
                      </div>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </div>
      )}

      {/* ── TAB 2: VISUAL PAGE BUILDER & EDITOR ── */}
      {activeTab === 'editor' && (
        <div>
          {/* Editor Sub-Bar */}
          <div
            style={{
              display: 'flex',
              justifyContent: 'space-between',
              alignItems: 'center',
              marginBottom: '20px',
              backgroundColor: '#ffffff',
              padding: '16px 20px',
              borderRadius: '10px',
              border: '1px solid #ECEEF3',
            }}
          >
            <div style={{ display: 'flex', alignItems: 'center', gap: '12px' }}>
              <button
                type="button"
                onClick={() => setEditorMode('edit')}
                style={{
                  padding: '8px 16px',
                  borderRadius: '6px',
                  border: 'none',
                  fontSize: '13px',
                  fontWeight: '700',
                  cursor: 'pointer',
                  backgroundColor: editorMode === 'edit' ? '#0E63FF' : '#F1F5F9',
                  color: editorMode === 'edit' ? '#ffffff' : '#64748B',
                }}
              >
                ✏️ Visual Editor
              </button>
              <button
                type="button"
                onClick={() => setEditorMode('preview')}
                style={{
                  padding: '8px 16px',
                  borderRadius: '6px',
                  border: 'none',
                  fontSize: '13px',
                  fontWeight: '700',
                  cursor: 'pointer',
                  backgroundColor: editorMode === 'preview' ? '#0E63FF' : '#F1F5F9',
                  color: editorMode === 'preview' ? '#ffffff' : '#64748B',
                  display: 'inline-flex',
                  alignItems: 'center',
                  gap: '6px',
                }}
              >
                <Sparkles size={14} />
                <span>Live Instant Preview</span>
              </button>
            </div>

            <button
              type="button"
              onClick={handleSaveService}
              disabled={saving}
              style={{
                display: 'inline-flex',
                alignItems: 'center',
                gap: '8px',
                padding: '10px 24px',
                backgroundColor: '#0b9748',
                color: '#ffffff',
                borderRadius: '8px',
                border: 'none',
                fontWeight: '700',
                fontSize: '13px',
                cursor: saving ? 'not-allowed' : 'pointer',
                boxShadow: '0 4px 12px rgba(11, 151, 72, 0.25)',
              }}
            >
              <Save size={16} />
              <span>{saving ? 'Saving...' : 'Save & Publish Page'}</span>
            </button>
          </div>

          {/* EDIT MODE */}
          {editorMode === 'edit' && (
            <form onSubmit={handleSaveService} style={{ display: 'grid', gap: '20px' }}>
              {/* 1. Basic Page Info */}
              <div style={{ backgroundColor: '#ffffff', borderRadius: '12px', padding: '24px', border: '1px solid #ECEEF3' }}>
                <h3 style={{ fontSize: '16px', fontWeight: '800', color: '#171151', marginBottom: '16px' }}>
                  1. Page Title &amp; Category URL
                </h3>
                <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(280px, 1fr))', gap: '16px' }}>
                  <div>
                    <label style={{ display: 'block', fontSize: '13px', fontWeight: '700', color: '#171151', marginBottom: '6px' }}>
                      Page Title
                    </label>
                    <input
                      type="text"
                      value={currentService.title}
                      onChange={(e) => handleTitleChange(e.target.value)}
                      placeholder="e.g. Medical Equipment & Devices"
                      required
                      style={{ width: '100%', padding: '10px', borderRadius: '6px', border: '1px solid #D1D6E0', fontSize: '14px', boxSizing: 'border-box' }}
                    />
                  </div>

                  <div>
                    <label style={{ display: 'block', fontSize: '13px', fontWeight: '700', color: '#171151', marginBottom: '6px' }}>
                      URL Slug (Page Link)
                    </label>
                    <div style={{ display: 'flex', alignItems: 'center' }}>
                      <span style={{ padding: '10px', backgroundColor: '#F1F5F9', border: '1px solid #D1D6E0', borderRight: 'none', borderRadius: '6px 0 0 6px', fontSize: '12px', color: '#64748B' }}>
                        /services/
                      </span>
                      <input
                        type="text"
                        value={currentService.slug}
                        onChange={(e) => setCurrentService({ ...currentService, slug: e.target.value })}
                        required
                        style={{ flex: 1, padding: '10px', borderRadius: '0 6px 6px 0', border: '1px solid #D1D6E0', fontSize: '13px', boxSizing: 'border-box' }}
                      />
                    </div>
                  </div>

                  <div>
                    <label style={{ display: 'block', fontSize: '13px', fontWeight: '700', color: '#171151', marginBottom: '6px' }}>
                      Category Tag
                    </label>
                    <input
                      type="text"
                      value={currentService.category || ''}
                      onChange={(e) => setCurrentService({ ...currentService, category: e.target.value })}
                      placeholder="e.g. Critical Care & ICU"
                      style={{ width: '100%', padding: '10px', borderRadius: '6px', border: '1px solid #D1D6E0', fontSize: '13px', boxSizing: 'border-box' }}
                    />
                  </div>

                  <div>
                    <label style={{ display: 'block', fontSize: '13px', fontWeight: '700', color: '#171151', marginBottom: '6px' }}>
                      Icon Class &amp; Theme Color
                    </label>
                    <div style={{ display: 'flex', gap: '8px' }}>
                      <input
                        type="text"
                        value={currentService.iconClass || 'flaticon-hemoglobin-test-meter'}
                        onChange={(e) => setCurrentService({ ...currentService, iconClass: e.target.value })}
                        style={{ flex: 1, padding: '10px', borderRadius: '6px', border: '1px solid #D1D6E0', fontSize: '13px', boxSizing: 'border-box' }}
                      />
                      <select
                        value={currentService.iconTheme || 'blue'}
                        onChange={(e) => setCurrentService({ ...currentService, iconTheme: e.target.value })}
                        style={{ width: '100px', padding: '10px', borderRadius: '6px', border: '1px solid #D1D6E0', fontSize: '13px', backgroundColor: '#ffffff' }}
                      >
                        <option value="blue">Blue</option>
                        <option value="pink">Pink</option>
                        <option value="green">Green</option>
                        <option value="sky">Sky</option>
                      </select>
                    </div>
                  </div>

                  <div style={{ gridColumn: '1 / -1' }}>
                    <label style={{ display: 'block', fontSize: '13px', fontWeight: '700', color: '#171151', marginBottom: '6px' }}>
                      Short Summary / Excerpt
                    </label>
                    <textarea
                      rows={2}
                      value={currentService.description}
                      onChange={(e) => setCurrentService({ ...currentService, description: e.target.value })}
                      placeholder="Brief overview shown on cards and search engines..."
                      required
                      style={{ width: '100%', padding: '10px', borderRadius: '6px', border: '1px solid #D1D6E0', fontSize: '13px', boxSizing: 'border-box' }}
                    />
                  </div>
                </div>
              </div>

              {/* 2. Visual Media & Banner Images */}
              <div style={{ backgroundColor: '#ffffff', borderRadius: '12px', padding: '24px', border: '1px solid #ECEEF3' }}>
                <h3 style={{ fontSize: '16px', fontWeight: '800', color: '#171151', marginBottom: '16px' }}>
                  2. Hero Banner &amp; Showcase Images
                </h3>
                <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(280px, 1fr))', gap: '16px' }}>
                  <div>
                    <FileUploadField
                      label="Hero Breadcrumb Background Banner"
                      value={currentService.bannerImage || ''}
                      onChange={(url) => setCurrentService({ ...currentService, bannerImage: url })}
                      placeholder="/assets/img/banner/breadcrumb-01.jpg"
                    />
                    <label style={{ display: 'block', fontSize: '12px', fontWeight: '600', color: '#64748B', marginBottom: '4px' }}>
                      Hero Subtitle
                    </label>
                    <input
                      type="text"
                      value={currentService.bannerSubTitle || ''}
                      onChange={(e) => setCurrentService({ ...currentService, bannerSubTitle: e.target.value })}
                      placeholder="e.g. Precision Medical Equipment & Healthcare Solutions"
                      style={{ width: '100%', padding: '8px 10px', borderRadius: '6px', border: '1px solid #D1D6E0', fontSize: '13px', boxSizing: 'border-box' }}
                    />
                  </div>

                  <div>
                    <FileUploadField
                      label="Showcase Image 1 (Left Photo)"
                      value={currentService.image1 || ''}
                      onChange={(url) => setCurrentService({ ...currentService, image1: url })}
                      placeholder="/assets/img/services/services-thumb-07.jpg"
                    />
                  </div>

                  <div>
                    <FileUploadField
                      label="Showcase Image 2 (Right Photo)"
                      value={currentService.image2 || ''}
                      onChange={(url) => setCurrentService({ ...currentService, image2: url })}
                      placeholder="/assets/img/services/services-thumb-08.jpg"
                    />
                  </div>

                  <div>
                    <FileUploadField
                      label="Full-Width Project Showcase Banner"
                      value={currentService.showcaseBanner || ''}
                      onChange={(url) => setCurrentService({ ...currentService, showcaseBanner: url })}
                      placeholder="/assets/img/services/services-thumb-09.jpg"
                    />
                  </div>
                </div>
              </div>

              {/* 3. Technical Process & Bullet Points */}
              <div style={{ backgroundColor: '#ffffff', borderRadius: '12px', padding: '24px', border: '1px solid #ECEEF3' }}>
                <h3 style={{ fontSize: '16px', fontWeight: '800', color: '#171151', marginBottom: '16px' }}>
                  3. Technical Overview &amp; Key Feature Bullet Points
                </h3>

                <div style={{ marginBottom: '16px' }}>
                  <label style={{ display: 'block', fontSize: '13px', fontWeight: '700', color: '#171151', marginBottom: '6px' }}>
                    Process Section Heading
                  </label>
                  <input
                    type="text"
                    value={currentService.processTitle || ''}
                    onChange={(e) => setCurrentService({ ...currentService, processTitle: e.target.value })}
                    placeholder="e.g. Technical Overview & Clinical Integration"
                    style={{ width: '100%', padding: '10px', borderRadius: '6px', border: '1px solid #D1D6E0', fontSize: '13px', boxSizing: 'border-box' }}
                  />
                </div>

                <div style={{ marginBottom: '16px' }}>
                  <label style={{ display: 'block', fontSize: '13px', fontWeight: '700', color: '#171151', marginBottom: '6px' }}>
                    Detailed Overview Text
                  </label>
                  <textarea
                    rows={4}
                    value={currentService.processText || ''}
                    onChange={(e) => setCurrentService({ ...currentService, processText: e.target.value })}
                    placeholder="Detailed explanation of clinical applications, engineering architecture, and patient benefits..."
                    style={{ width: '100%', padding: '10px', borderRadius: '6px', border: '1px solid #D1D6E0', fontSize: '13px', boxSizing: 'border-box' }}
                  />
                </div>

                <div>
                  <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '10px' }}>
                    <label style={{ fontSize: '13px', fontWeight: '700', color: '#171151' }}>
                      Key Features (Checkmark Bullets)
                    </label>
                    <button
                      type="button"
                      onClick={addProcessPoint}
                      style={{ padding: '4px 10px', backgroundColor: '#EFF6FF', color: '#0E63FF', border: 'none', borderRadius: '4px', fontSize: '12px', fontWeight: '700', cursor: 'pointer' }}
                    >
                      + Add Bullet
                    </button>
                  </div>
                  {(currentService.processPoints || []).map((pt, idx) => (
                    <div key={idx} style={{ display: 'flex', gap: '8px', marginBottom: '8px' }}>
                      <input
                        type="text"
                        value={pt}
                        onChange={(e) => updateProcessPoint(idx, e.target.value)}
                        placeholder="e.g. FDA, CE, and ISO certified compliant with international benchmarks."
                        style={{ flex: 1, padding: '8px 10px', borderRadius: '6px', border: '1px solid #D1D6E0', fontSize: '13px' }}
                      />
                      <button
                        type="button"
                        onClick={() => removeProcessPoint(idx)}
                        style={{ padding: '6px 10px', backgroundColor: '#FEEAF1', color: '#F72A75', border: 'none', borderRadius: '6px', cursor: 'pointer' }}
                      >
                        <X size={14} />
                      </button>
                    </div>
                  ))}
                </div>
              </div>

              {/* 4. Technical Specifications Table Builder */}
              <div style={{ backgroundColor: '#ffffff', borderRadius: '12px', padding: '24px', border: '1px solid #ECEEF3' }}>
                <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '16px' }}>
                  <h3 style={{ fontSize: '16px', fontWeight: '800', color: '#171151', margin: 0, display: 'flex', alignItems: 'center', gap: '8px' }}>
                    <TableIcon size={18} color="#0E63FF" /> 4. Technical Specifications Table
                  </h3>
                  <button
                    type="button"
                    onClick={addSpecRow}
                    style={{ padding: '6px 12px', backgroundColor: '#0E63FF', color: '#fff', border: 'none', borderRadius: '6px', fontSize: '12px', fontWeight: '700', cursor: 'pointer' }}
                  >
                    + Add Parameter Row
                  </button>
                </div>

                {(currentService.specsTable || []).map((row, idx) => (
                  <div key={idx} style={{ display: 'grid', gridTemplateColumns: '1fr 1.5fr auto', gap: '10px', marginBottom: '8px', alignItems: 'center' }}>
                    <input
                      type="text"
                      value={row.feature}
                      onChange={(e) => updateSpecRow(idx, 'feature', e.target.value)}
                      placeholder="Parameter (e.g. Throughput, Certification, Display)"
                      style={{ padding: '8px 10px', borderRadius: '6px', border: '1px solid #D1D6E0', fontSize: '13px' }}
                    />
                    <input
                      type="text"
                      value={row.spec}
                      onChange={(e) => updateSpecRow(idx, 'spec', e.target.value)}
                      placeholder="Value (e.g. 120 samples/hr, FDA 510(k))"
                      style={{ padding: '8px 10px', borderRadius: '6px', border: '1px solid #D1D6E0', fontSize: '13px' }}
                    />
                    <button
                      type="button"
                      onClick={() => removeSpecRow(idx)}
                      style={{ padding: '8px 10px', backgroundColor: '#FEEAF1', color: '#F72A75', border: 'none', borderRadius: '6px', cursor: 'pointer' }}
                    >
                      <Trash2 size={14} />
                    </button>
                  </div>
                ))}
              </div>

              {/* 5. Rich HTML Content Body */}
              <div style={{ backgroundColor: '#ffffff', borderRadius: '12px', padding: '24px', border: '1px solid #ECEEF3' }}>
                <h3 style={{ fontSize: '16px', fontWeight: '800', color: '#171151', marginBottom: '12px', display: 'flex', alignItems: 'center', gap: '8px' }}>
                  <FileText size={18} color="#0E63FF" /> 5. Custom Rich HTML / Extended Content Block (Optional)
                </h3>
                <p style={{ fontSize: '12px', color: '#64748B', margin: '0 0 10px' }}>
                  You can enter custom HTML blocks, extra paragraphs, equipment models list, or embedded brochures.
                </p>
                <textarea
                  rows={5}
                  value={currentService.fullContent || ''}
                  onChange={(e) => setCurrentService({ ...currentService, fullContent: e.target.value })}
                  placeholder="<p>Extended technical documentation, biomedical specifications, or custom formatting...</p>"
                  style={{ width: '100%', padding: '10px', borderRadius: '6px', border: '1px solid #D1D6E0', fontSize: '13px', fontFamily: 'monospace', boxSizing: 'border-box' }}
                />
              </div>

              {/* 6. Settings & Visibility Options */}
              <div style={{ backgroundColor: '#ffffff', borderRadius: '12px', padding: '24px', border: '1px solid #ECEEF3' }}>
                <h3 style={{ fontSize: '16px', fontWeight: '800', color: '#171151', marginBottom: '16px' }}>
                  6. Publishing &amp; Header Navigation Options
                </h3>
                <div style={{ display: 'flex', gap: '30px', flexWrap: 'wrap' }}>
                  <label style={{ display: 'flex', alignItems: 'center', gap: '8px', cursor: 'pointer', fontSize: '14px', fontWeight: '700', color: '#171151' }}>
                    <input
                      type="checkbox"
                      checked={currentService.enabled !== false}
                      onChange={(e) => setCurrentService({ ...currentService, enabled: e.target.checked })}
                      style={{ width: '18px', height: '18px' }}
                    />
                    <span>Publish &amp; Show on Live Website</span>
                  </label>

                  <label style={{ display: 'flex', alignItems: 'center', gap: '8px', cursor: 'pointer', fontSize: '14px', fontWeight: '700', color: '#0E63FF' }}>
                    <input
                      type="checkbox"
                      checked={currentService.showInHeader !== false}
                      onChange={(e) => setCurrentService({ ...currentService, showInHeader: e.target.checked })}
                      style={{ width: '18px', height: '18px' }}
                    />
                    <span>Include in Header Menu &quot;Products &amp; Services&quot; Dropdown</span>
                  </label>
                </div>
              </div>

              {/* Bottom Action Bar */}
              <div style={{ display: 'flex', justifyContent: 'flex-end', gap: '12px', marginTop: '10px' }}>
                <button
                  type="button"
                  onClick={() => setActiveTab('list')}
                  style={{ padding: '12px 24px', backgroundColor: '#F1F5F9', color: '#475569', borderRadius: '8px', border: 'none', fontWeight: '700', cursor: 'pointer' }}
                >
                  Cancel
                </button>
                <button
                  type="submit"
                  disabled={saving}
                  style={{
                    display: 'inline-flex',
                    alignItems: 'center',
                    gap: '8px',
                    padding: '12px 32px',
                    backgroundColor: '#0b9748',
                    color: '#ffffff',
                    borderRadius: '8px',
                    border: 'none',
                    fontWeight: '700',
                    fontSize: '14px',
                    cursor: saving ? 'not-allowed' : 'pointer',
                    boxShadow: '0 4px 14px rgba(11, 151, 72, 0.28)',
                  }}
                >
                  <Save size={18} />
                  <span>{saving ? 'Saving...' : 'Save & Publish Page'}</span>
                </button>
              </div>
            </form>
          )}

          {/* PREVIEW MODE */}
          {editorMode === 'preview' && (
            <div style={{ border: '2px solid #0E63FF', borderRadius: '14px', overflow: 'hidden', backgroundColor: '#ffffff', boxShadow: '0 10px 40px rgba(0,0,0,0.08)' }}>
              {/* Preview Banner */}
              <div
                style={{
                  backgroundImage: `url(${currentService.bannerImage || '/assets/img/banner/breadcrumb-01.jpg'})`,
                  backgroundSize: 'cover',
                  backgroundPosition: 'center',
                  padding: '60px 40px',
                  color: '#ffffff',
                }}
              >
                <span style={{ color: '#239FDA', fontWeight: '700', fontSize: '13px', textTransform: 'uppercase', letterSpacing: '1px' }}>
                  {currentService.category || 'Products & Services'}
                </span>
                <h1 style={{ fontSize: '32px', fontWeight: '800', margin: '8px 0', color: '#fff' }}>
                  {currentService.title || 'Service Title Preview'}
                </h1>
                <p style={{ color: 'rgba(255,255,255,0.85)', margin: 0, fontSize: '14px' }}>
                  {currentService.bannerSubTitle || 'Precision Medical Equipment & Healthcare Solutions'}
                </p>
              </div>

              {/* Preview Content */}
              <div style={{ padding: '40px' }}>
                <h3 style={{ fontSize: '22px', fontWeight: '800', color: '#171151', marginBottom: '14px' }}>
                  {currentService.processTitle || 'Technical Overview'}
                </h3>
                <p style={{ fontSize: '15px', color: '#475569', lineHeight: '1.7', marginBottom: '24px' }}>
                  {currentService.processText || currentService.description || 'Service description will appear here...'}
                </p>

                {/* Bullets */}
                <div style={{ marginBottom: '30px' }}>
                  {(currentService.processPoints || []).map((pt, idx) => (
                    <div key={idx} style={{ display: 'flex', alignItems: 'center', gap: '10px', fontSize: '14px', color: '#171151', marginBottom: '8px' }}>
                      <CheckCircle2 size={16} color="#0E63FF" />
                      <span>{pt}</span>
                    </div>
                  ))}
                </div>

                {/* Specs Table */}
                {(currentService.specsTable || []).length > 0 && (
                  <div style={{ backgroundColor: '#F8FAFC', padding: '20px', borderRadius: '10px', border: '1px solid #E2E8F0', marginBottom: '30px' }}>
                    <h4 style={{ fontSize: '16px', fontWeight: '800', color: '#171151', marginBottom: '12px' }}>
                      ⚙️ Specifications Table Preview
                    </h4>
                    <table style={{ width: '100%', borderCollapse: 'collapse', fontSize: '13px' }}>
                      <tbody>
                        {currentService.specsTable.map((row, rIdx) => (
                          <tr key={rIdx} style={{ borderBottom: '1px solid #E2E8F0' }}>
                            <td style={{ padding: '10px', fontWeight: '700', color: '#171151', width: '35%' }}>{row.feature}</td>
                            <td style={{ padding: '10px', color: '#475569' }}>{row.spec}</td>
                          </tr>
                        ))}
                      </tbody>
                    </table>
                  </div>
                )}
              </div>
            </div>
          )}
        </div>
      )}

      {/* Delete Confirmation Modal */}
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
          onClick={() => setDeleteModal({ open: false, id: null, title: '' })}
        >
          <div
            style={{
              backgroundColor: '#ffffff',
              borderRadius: '16px',
              maxWidth: '460px',
              width: '100%',
              padding: '28px',
              textAlign: 'center',
            }}
            onClick={(e) => e.stopPropagation()}
          >
            <div style={{ width: '56px', height: '56px', borderRadius: '50%', backgroundColor: '#FEEAF1', color: '#F72A75', display: 'flex', alignItems: 'center', justifyContent: 'center', margin: '0 auto 16px' }}>
              <AlertTriangle size={28} />
            </div>
            <h3 style={{ fontSize: '19px', fontWeight: '800', color: '#171151', margin: '0 0 8px' }}>
              Confirm Page Deletion
            </h3>
            <p style={{ fontSize: '14px', color: '#64748B', margin: '0 0 24px' }}>
              Are you sure you want to permanently delete <strong style={{ color: '#171151' }}>&ldquo;{deleteModal.title}&rdquo;</strong>?
            </p>
            <div style={{ display: 'flex', gap: '12px' }}>
              <button
                type="button"
                onClick={() => setDeleteModal({ open: false, id: null, title: '' })}
                style={{ flex: 1, padding: '11px 18px', backgroundColor: '#F1F5F9', color: '#475569', border: 'none', borderRadius: '8px', fontWeight: '600', cursor: 'pointer' }}
              >
                Cancel
              </button>
              <button
                type="button"
                onClick={handleDeleteConfirm}
                style={{ flex: 1, padding: '11px 18px', backgroundColor: '#F72A75', color: '#fff', border: 'none', borderRadius: '8px', fontWeight: '600', cursor: 'pointer' }}
              >
                Yes, Delete
              </button>
            </div>
          </div>
        </div>
      )}
    </div>
  );
}
