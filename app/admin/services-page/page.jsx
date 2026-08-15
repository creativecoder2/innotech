'use client';

import React, { useState, useEffect } from 'react';
import {
  Save,
  Plus,
  Trash2,
  CheckCircle2,
  AlertTriangle,
  Layers,
  LayoutTemplate,
  Award,
  HelpCircle,
  Edit3,
  ExternalLink,
  X,
  Image as ImageIcon,
  Sparkles,
} from 'lucide-react';
import { fallbackServicesPage } from '@/lib/data';

function ImageUploadControl({ label, value, onChange }) {
  const [uploading, setUploading] = useState(false);
  const fileInputRef = React.useRef(null);

  const handleFile = async (e) => {
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
    <div style={{ marginBottom: '16px' }}>
      <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>
        {label}
      </label>
      <div style={{ display: 'flex', gap: '10px', alignItems: 'center', flexWrap: 'wrap' }}>
        <input
          type="text"
          value={value || ''}
          onChange={(e) => onChange(e.target.value)}
          placeholder="Enter image URL or upload from your computer"
          style={{ flex: 1, minWidth: '220px', padding: '10px 14px', borderRadius: '8px', border: '1px solid #D1D6E0', fontSize: '14px', boxSizing: 'border-box' }}
        />
        <input
          type="file"
          ref={fileInputRef}
          onChange={handleFile}
          accept="image/*"
          style={{ display: 'none' }}
        />
        <button
          type="button"
          onClick={() => fileInputRef.current?.click()}
          disabled={uploading}
          style={{
            padding: '10px 16px',
            backgroundColor: '#EFF6FF',
            color: '#0E63FF',
            border: '1px solid #BFDBFE',
            borderRadius: '8px',
            fontSize: '13px',
            fontWeight: '600',
            cursor: uploading ? 'not-allowed' : 'pointer',
            display: 'inline-flex',
            alignItems: 'center',
            gap: '6px',
          }}
        >
          {uploading ? (
            <span>Uploading...</span>
          ) : (
            <>
              <ImageIcon size={15} />
              <span>Upload Image File</span>
            </>
          )}
        </button>
      </div>

      {value && (
        <div style={{ marginTop: '10px', display: 'flex', alignItems: 'center', gap: '12px' }}>
          <div
            style={{
              width: '120px',
              height: '70px',
              borderRadius: '6px',
              overflow: 'hidden',
              border: '1px solid #E2E8F0',
              backgroundColor: '#F8FAFC',
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'center',
            }}
          >
            <img
              src={value}
              alt="Preview"
              style={{ width: '100%', height: '100%', objectFit: 'cover' }}
              onError={(e) => {
                e.currentTarget.style.display = 'none';
              }}
            />
          </div>
          <span style={{ fontSize: '12px', color: '#64748B' }}>
            Current Image Preview
          </span>
        </div>
      )}
    </div>
  );
}

export default function AdminServicesPageManager() {
  const [activeTab, setActiveTab] = useState('services');
  const [data, setData] = useState(fallbackServicesPage);
  const [saving, setSaving] = useState(false);
  const [saveStatus, setSaveStatus] = useState({ type: '', msg: '' });

  // Modal editor for editing both card & detail page of a service
  const [editingServiceIndex, setEditingServiceIndex] = useState(null);
  const [editFormData, setEditFormData] = useState(null);

  // Delete modal state
  const [deleteModal, setDeleteModal] = useState({
    open: false,
    type: '',
    id: null,
    title: '',
  });

  useEffect(() => {
    fetchData();
  }, []);

  const showToast = (msg, type = 'success') => {
    setSaveStatus({ type, msg });
    setTimeout(() => {
      setSaveStatus((prev) => (prev.msg === msg ? { type: '', msg: '' } : prev));
    }, 4500);
  };

  const fetchData = async () => {
    try {
      const res = await fetch('/api/admin/services-page');
      const json = await res.json();
      if (json.data) {
        setData(json.data);
      }
    } catch (e) {
      console.error('Error loading services page data:', e);
    }
  };

  const syncData = async (updatedData) => {
    try {
      await fetch('/api/admin/services-page', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(updatedData || data),
      });
    } catch (err) {
      console.error('Error syncing services page data:', err);
    }
  };

  const updateBanner = (field, value) => {
    const updated = {
      ...data,
      banner: {
        ...(data.banner || fallbackServicesPage.banner),
        [field]: value,
      },
    };
    setData(updated);
    syncData(updated);
  };

  const updateServicesSection = (field, value) => {
    const updated = {
      ...data,
      servicesSection: {
        ...(data.servicesSection || fallbackServicesPage.servicesSection),
        [field]: value,
      },
    };
    setData(updated);
    syncData(updated);
  };

  const openServiceModal = (index) => {
    const items = [...((data.servicesSection && data.servicesSection.items) || fallbackServicesPage.servicesSection.items)];
    const item = items[index] || {};

    const fbItem =
      fallbackServicesPage.servicesSection.items.find(
        (f) =>
          f.slug === item.slug ||
          f.id === item.id ||
          (f.title && f.title.toLowerCase() === (item.title || '').toLowerCase())
      ) || fallbackServicesPage.servicesSection.items[index] || {};

    const slug =
      item.slug ||
      fbItem.slug ||
      (item.title || '').toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, '');

    const fullItem = {
      ...fbItem,
      ...item,
      slug,
      bannerImage: item.bannerImage || fbItem.bannerImage || '/assets/img/banner/breadcrumb-01.jpg',
      bannerSubTitle: item.bannerSubTitle || fbItem.bannerSubTitle || 'Automated Clinical Diagnostic Systems',
      image1: item.image1 || fbItem.image1 || '/assets/img/services/services-thumb-07.jpg',
      image2: item.image2 || fbItem.image2 || '/assets/img/services/services-thumb-08.jpg',
      showcaseBanner: item.showcaseBanner || fbItem.showcaseBanner || '/assets/img/services/services-thumb-09.jpg',
      processTitle: item.processTitle || fbItem.processTitle || `${item.title || 'Clinical'} Process`,
      processText: item.processText || fbItem.processText || 'Must explain to you how all this mistaken idea of denouncing works pleasure and praising its pain was born and I will gives you a completed account of the system.',
      processText2: item.processText2 || fbItem.processText2 || 'Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt.',
      processPoints: (item.processPoints && item.processPoints.length > 0) ? item.processPoints : (fbItem.processPoints || ['Precision microfluidic measurement channels with laser optical flow cytometry.', 'High-throughput automated sampling with closed-tube barcode scanning.', 'Fully compliant with ISO 15189 laboratory quality standards.', 'Comprehensive warranty, preventive calibration, and genuine reagent packs.']),
      stepsTitle: item.stepsTitle || fbItem.stepsTitle || '4 Simple Steps',
      stepsText: item.stepsText || fbItem.stepsText || 'Our turnkey analytical process guarantees maximum instrument uptime, precision calibration benchmarks, and seamless hospital LIS integration.',
      stepsCol1: (item.stepsCol1 && item.stepsCol1.length > 0) ? item.stepsCol1 : (fbItem.stepsCol1 || ['Sample Preparation & Barcode Scan', 'Microfluidic Analysis', 'Automated Quality Control', 'In aliquet dui nec lectus']),
      stepsCol2: (item.stepsCol2 && item.stepsCol2.length > 0) ? item.stepsCol2 : (fbItem.stepsCol2 || ['High-Resolution Optical Detection', 'Digital Differential Profiling', 'Real-Time LIS Sync', 'Bacteria Markers']),
      stepsCol3: (item.stepsCol3 && item.stepsCol3.length > 0) ? item.stepsCol3 : (fbItem.stepsCol3 || ['Automated Probe Washing', 'Diagnostic Report Generation', 'Preventive Maintenance Log', 'Extramural Funding']),
      researchTitle: item.researchTitle || fbItem.researchTitle || 'Our Research',
      researchText: item.researchText || fbItem.researchText || 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.',
      bottomLinkText: item.bottomLinkText || fbItem.bottomLinkText || 'Our Project',
      bottomLink: item.bottomLink || fbItem.bottomLink || '/contact',
    };

    setEditingServiceIndex(index);
    setEditFormData({
      ...fullItem,
      processPointsText: (fullItem.processPoints || []).join('\n'),
      stepsCol1Text: (fullItem.stepsCol1 || []).join('\n'),
      stepsCol2Text: (fullItem.stepsCol2 || []).join('\n'),
      stepsCol3Text: (fullItem.stepsCol3 || []).join('\n'),
    });
  };

  const saveServiceModal = () => {
    if (editingServiceIndex === null || !editFormData) return;

    const items = [...((data.servicesSection && data.servicesSection.items) || fallbackServicesPage.servicesSection.items)];
    
    // Convert multiline text back to arrays
    const processPoints = (editFormData.processPointsText || '')
      .split('\n')
      .map((s) => s.trim())
      .filter(Boolean);
    const stepsCol1 = (editFormData.stepsCol1Text || '')
      .split('\n')
      .map((s) => s.trim())
      .filter(Boolean);
    const stepsCol2 = (editFormData.stepsCol2Text || '')
      .split('\n')
      .map((s) => s.trim())
      .filter(Boolean);
    const stepsCol3 = (editFormData.stepsCol3Text || '')
      .split('\n')
      .map((s) => s.trim())
      .filter(Boolean);

    const slug =
      editFormData.slug ||
      (editFormData.title || '').toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, '');

    items[editingServiceIndex] = {
      ...editFormData,
      slug,
      link: `/services/${slug}`,
      processPoints,
      stepsCol1,
      stepsCol2,
      stepsCol3,
    };

    const updated = {
      ...data,
      servicesSection: {
        ...(data.servicesSection || fallbackServicesPage.servicesSection),
        items,
      },
    };

    setData(updated);
    syncData(updated);
    setEditingServiceIndex(null);
    setEditFormData(null);
    showToast(`Service "${editFormData.title}" updated successfully!`);
  };

  const addServiceItem = () => {
    const items = [...((data.servicesSection && data.servicesSection.items) || fallbackServicesPage.servicesSection.items)];
    const idNum = Date.now();
    const slug = `clinical-service-${items.length + 1}`;
    const newItem = {
      id: `srv-${idNum}`,
      title: `Service #${items.length + 1}`,
      slug,
      description: 'Comprehensive biomedical diagnostic and clinical laboratory testing solutions.',
      btnText: 'Read More',
      link: `/services/${slug}`,
      enabled: true,
      bannerImage: '/assets/img/banner/breadcrumb-01.jpg',
      bannerSubTitle: 'Specialized Biomedical & Clinical Solutions',
      image1: '/assets/img/services/services-thumb-07.jpg',
      image2: '/assets/img/services/services-thumb-08.jpg',
      showcaseBanner: '/assets/img/services/services-thumb-09.jpg',
      processTitle: 'Clinical Diagnostic Process',
      processText: 'Must explain to you how all this mistaken idea of denouncing works pleasure and praising its pain was born and I will gives you a complete account of the system.',
      processText2: 'Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt.',
      processPoints: [
        'Consectetur, adipisci velit, sed quia non numquam eius modi',
        'Perspiciatis unde omnis iste natus error sit voluptatem',
        'Ut enim ad minima veniam, quis nostrum exercitationem',
      ],
      stepsTitle: '4 Simple Steps',
      stepsText: 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam.',
      stepsCol1: ['Extramural Funding', 'Bacteria Markers', 'Nam nec mi euismod'],
      stepsCol2: ['Sample Preparation', 'Optical Detection', 'Quality Control Verification'],
      stepsCol3: ['LIS Data Sync', 'Report Validation', 'Digital Archiving'],
      researchTitle: 'Our Research',
      researchText: 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam.',
      bottomLinkText: 'Our Project',
      bottomLink: '/contact',
    };
    items.push(newItem);
    const updated = {
      ...data,
      servicesSection: {
        ...(data.servicesSection || fallbackServicesPage.servicesSection),
        items,
      },
    };
    setData(updated);
    syncData(updated);
    openServiceModal(items.length - 1);
  };

  const updateChooseSection = (field, value) => {
    const updated = {
      ...data,
      chooseSection: {
        ...(data.chooseSection || fallbackServicesPage.chooseSection),
        [field]: value,
      },
    };
    setData(updated);
    syncData(updated);
  };

  const updateChooseItem = (index, field, value) => {
    const items = [...((data.chooseSection && data.chooseSection.items) || fallbackServicesPage.chooseSection.items)];
    items[index] = { ...items[index], [field]: value };
    const updated = {
      ...data,
      chooseSection: {
        ...(data.chooseSection || fallbackServicesPage.chooseSection),
        items,
      },
    };
    setData(updated);
    syncData(updated);
  };

  const updateSupportSection = (field, value) => {
    const updated = {
      ...data,
      supportSection: {
        ...(data.supportSection || fallbackServicesPage.supportSection),
        [field]: value,
      },
    };
    setData(updated);
    syncData(updated);
  };

  const handleConfirmDelete = () => {
    const { type, id, title } = deleteModal;
    if (type === 'serviceItem') {
      const items = (data.servicesSection?.items || []).filter((_, i) => i !== id);
      const updated = {
        ...data,
        servicesSection: {
          ...data.servicesSection,
          items,
        },
      };
      setData(updated);
      syncData(updated);
      showToast(`Service "${title}" removed!`);
    }
    setDeleteModal({ open: false, type: '', id: null, title: '' });
  };

  const handleSaveAll = async (e) => {
    if (e) e.preventDefault();
    setSaving(true);
    try {
      const res = await fetch('/api/admin/services-page', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data),
      });
      const json = await res.json();
      if (res.ok) {
        showToast('Services Page & all Detail Pages successfully saved and published live!');
      } else {
        showToast(json.message || 'Error saving services page', 'error');
      }
    } catch (err) {
      showToast('Network error saving services page.', 'error');
    } finally {
      setSaving(false);
    }
  };

  const banner = data.banner || fallbackServicesPage.banner;
  const servicesSection = data.servicesSection || fallbackServicesPage.servicesSection;
  const chooseSection = data.chooseSection || fallbackServicesPage.chooseSection;
  const supportSection = data.supportSection || fallbackServicesPage.supportSection;

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
          marginBottom: '20px',
          backgroundColor: '#ffffff',
          padding: '20px 24px',
          borderRadius: '12px',
          border: '1px solid #ECEEF3',
          boxShadow: '0 2px 8px rgba(0,0,0,0.04)',
        }}
      >
        <div>
          <h1 style={{ fontSize: '24px', fontWeight: '800', color: '#171151', margin: '0 0 6px', display: 'flex', alignItems: 'center', gap: '10px' }}>
            <span>Services Page Manager</span>
            <span style={{ fontSize: '12px', fontWeight: '600', backgroundColor: '#E7FAF6', color: '#0B9748', padding: '4px 10px', borderRadius: '20px' }}>
              Live Dynamic
            </span>
          </h1>
          <p style={{ fontSize: '14px', color: '#6b6b6b', margin: 0 }}>
            Manage the Services Page cards and their full Service Detail pages in one place.
          </p>
        </div>

        <button
          onClick={handleSaveAll}
          disabled={saving}
          style={{
            display: 'inline-flex',
            alignItems: 'center',
            gap: '8px',
            padding: '12px 24px',
            backgroundColor: '#0b9748',
            color: '#ffffff',
            borderRadius: '8px',
            border: 'none',
            fontWeight: '600',
            fontSize: '14px',
            cursor: 'pointer',
            boxShadow: '0 4px 12px rgba(11, 151, 72, 0.25)',
            transition: 'all 0.2s',
          }}
        >
          <Save size={18} />
          <span>{saving ? 'Saving...' : 'Save & Publish Live'}</span>
        </button>
      </div>

      {/* Toast Notification Banner */}
      {saveStatus.msg && (
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
            backgroundColor: saveStatus.type === 'error' ? '#FEEAF1' : '#E7FAF6',
            color: saveStatus.type === 'error' ? '#F72A75' : '#0b9748',
            border: `1.5px solid ${saveStatus.type === 'error' ? '#FDCAD9' : '#A3EAD8'}`,
            boxShadow: '0 4px 14px rgba(0,0,0,0.06)',
            animation: 'fadeIn 0.25s ease',
          }}
        >
          {saveStatus.type === 'error' ? <AlertTriangle size={20} /> : <CheckCircle2 size={20} />}
          <span>{saveStatus.msg}</span>
        </div>
      )}

      {/* Tabs Navigation */}
      <div style={{ display: 'flex', gap: '10px', flexWrap: 'wrap', marginBottom: '20px' }}>
        <button
          onClick={() => setActiveTab('services')}
          style={{
            display: 'inline-flex',
            alignItems: 'center',
            gap: '8px',
            padding: '12px 20px',
            borderRadius: '8px',
            border: 'none',
            fontSize: '14px',
            fontWeight: '700',
            cursor: 'pointer',
            backgroundColor: activeTab === 'services' ? '#0E63FF' : '#ffffff',
            color: activeTab === 'services' ? '#ffffff' : '#475569',
            boxShadow: activeTab === 'services' ? '0 4px 12px rgba(14, 99, 255, 0.2)' : 'none',
            transition: 'all 0.2s ease',
          }}
        >
          <Layers size={18} />
          <span>1. Services &amp; Detail Pages ({ (servicesSection.items || []).length })</span>
        </button>

        <button
          onClick={() => setActiveTab('banner')}
          style={{
            display: 'inline-flex',
            alignItems: 'center',
            gap: '8px',
            padding: '12px 20px',
            borderRadius: '8px',
            border: 'none',
            fontSize: '14px',
            fontWeight: '700',
            cursor: 'pointer',
            backgroundColor: activeTab === 'banner' ? '#0E63FF' : '#ffffff',
            color: activeTab === 'banner' ? '#ffffff' : '#475569',
            boxShadow: activeTab === 'banner' ? '0 4px 12px rgba(14, 99, 255, 0.2)' : 'none',
            transition: 'all 0.2s ease',
          }}
        >
          <LayoutTemplate size={18} />
          <span>2. Top Banner</span>
        </button>

        <button
          onClick={() => setActiveTab('choose')}
          style={{
            display: 'inline-flex',
            alignItems: 'center',
            gap: '8px',
            padding: '12px 20px',
            borderRadius: '8px',
            border: 'none',
            fontSize: '14px',
            fontWeight: '700',
            cursor: 'pointer',
            backgroundColor: activeTab === 'choose' ? '#0E63FF' : '#ffffff',
            color: activeTab === 'choose' ? '#ffffff' : '#475569',
            boxShadow: activeTab === 'choose' ? '0 4px 12px rgba(14, 99, 255, 0.2)' : 'none',
            transition: 'all 0.2s ease',
          }}
        >
          <Award size={18} />
          <span>3. Why Choose Us (Specialists)</span>
        </button>

        <button
          onClick={() => setActiveTab('support')}
          style={{
            display: 'inline-flex',
            alignItems: 'center',
            gap: '8px',
            padding: '12px 20px',
            borderRadius: '8px',
            border: 'none',
            fontSize: '14px',
            fontWeight: '700',
            cursor: 'pointer',
            backgroundColor: activeTab === 'support' ? '#0E63FF' : '#ffffff',
            color: activeTab === 'support' ? '#ffffff' : '#475569',
            boxShadow: activeTab === 'support' ? '0 4px 12px rgba(14, 99, 255, 0.2)' : 'none',
            transition: 'all 0.2s ease',
          }}
        >
          <HelpCircle size={18} />
          <span>4. Need Any Help (Contact Form)</span>
        </button>
      </div>

      {/* TAB 1: SERVICES LIST & DETAIL EDITORS */}
      {activeTab === 'services' && (
        <div style={{ display: 'flex', flexDirection: 'column', gap: '20px' }}>
          {/* Section Heading Settings */}
          <div style={{ backgroundColor: '#ffffff', borderRadius: '12px', padding: '24px', border: '1px solid #ECEEF3' }}>
            <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '20px' }}>
              <h3 style={{ fontSize: '17px', fontWeight: '700', color: '#171151', margin: 0 }}>
                Services Area Header &amp; Pattern
              </h3>
              <label style={{ display: 'flex', alignItems: 'center', gap: '8px', fontSize: '14px', fontWeight: '600', color: '#171151', cursor: 'pointer' }}>
                <input
                  type="checkbox"
                  checked={servicesSection.enabled !== false}
                  onChange={(e) => updateServicesSection('enabled', e.target.checked)}
                  style={{ width: '18px', height: '18px', accentColor: '#0E63FF' }}
                />
                <span>Enable Section</span>
              </label>
            </div>

            <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(260px, 1fr))', gap: '16px', marginBottom: '16px' }}>
              <div>
                <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>Section Subtitle</label>
                <input
                  type="text"
                  value={servicesSection.subTitle || ''}
                  onChange={(e) => updateServicesSection('subTitle', e.target.value)}
                  placeholder="e.g. our Services"
                  style={{ width: '100%', padding: '10px 14px', borderRadius: '8px', border: '1px solid #D1D6E0', fontSize: '14px', boxSizing: 'border-box' }}
                />
              </div>
              <div>
                <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>Section Title</label>
                <input
                  type="text"
                  value={servicesSection.title || ''}
                  onChange={(e) => updateServicesSection('title', e.target.value)}
                  placeholder="e.g. Service Area"
                  style={{ width: '100%', padding: '10px 14px', borderRadius: '8px', border: '1px solid #D1D6E0', fontSize: '14px', boxSizing: 'border-box' }}
                />
              </div>
            </div>

            <ImageUploadControl
              label="Background Pattern Shape Image"
              value={servicesSection.bgImage || ''}
              onChange={(url) => updateServicesSection('bgImage', url)}
            />
          </div>

          {/* Service Cards List */}
          <div style={{ backgroundColor: '#ffffff', borderRadius: '12px', padding: '24px', border: '1px solid #ECEEF3' }}>
            <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '20px', flexWrap: 'wrap', gap: '10px' }}>
              <div>
                <h3 style={{ fontSize: '18px', fontWeight: '800', color: '#171151', margin: '0 0 4px' }}>
                  All Services ({ (servicesSection.items || []).length })
                </h3>
                <p style={{ fontSize: '13px', color: '#64748B', margin: 0 }}>
                  Click &quot;Edit Service &amp; Detail Page&quot; to update both card text and its full detail page.
                </p>
              </div>
              <button
                type="button"
                onClick={addServiceItem}
                style={{
                  display: 'inline-flex',
                  alignItems: 'center',
                  gap: '6px',
                  padding: '10px 18px',
                  backgroundColor: '#0E63FF',
                  color: '#ffffff',
                  border: 'none',
                  borderRadius: '8px',
                  fontSize: '13px',
                  fontWeight: '700',
                  cursor: 'pointer',
                  boxShadow: '0 4px 12px rgba(14, 99, 255, 0.25)',
                }}
              >
                <Plus size={16} />
                <span>Add New Service</span>
              </button>
            </div>

            <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(360px, 1fr))', gap: '16px' }}>
              {(servicesSection.items || []).map((item, idx) => {
                const serviceSlug =
                  item.slug ||
                  (item.title || '').toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, '');

                return (
                  <div
                    key={item.id || idx}
                    style={{
                      border: '1px solid #E2E8F0',
                      borderRadius: '12px',
                      padding: '20px',
                      backgroundColor: '#F8FAFC',
                      display: 'flex',
                      flexDirection: 'column',
                      justifyContent: 'space-between',
                      boxShadow: '0 2px 6px rgba(0,0,0,0.02)',
                    }}
                  >
                    <div>
                      <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '12px' }}>
                        <span
                          style={{
                            padding: '3px 10px',
                            backgroundColor: '#E0E7FF',
                            color: '#0E63FF',
                            borderRadius: '12px',
                            fontSize: '12px',
                            fontWeight: '700',
                          }}
                        >
                          Service #{idx + 1}
                        </span>
                        <div style={{ display: 'flex', gap: '8px' }}>
                          <a
                            href={`/services/${serviceSlug}`}
                            target="_blank"
                            rel="noreferrer"
                            style={{
                              padding: '4px 8px',
                              backgroundColor: '#ffffff',
                              color: '#64748B',
                              border: '1px solid #CBD5E1',
                              borderRadius: '6px',
                              fontSize: '11px',
                              textDecoration: 'none',
                              display: 'inline-flex',
                              alignItems: 'center',
                              gap: '4px',
                            }}
                          >
                            <span>Preview</span>
                            <ExternalLink size={12} />
                          </a>
                          <button
                            type="button"
                            onClick={() => setDeleteModal({ open: true, type: 'serviceItem', id: idx, title: item.title })}
                            style={{
                              padding: '4px 8px',
                              backgroundColor: '#FEEAF1',
                              color: '#F72A75',
                              border: 'none',
                              borderRadius: '6px',
                              cursor: 'pointer',
                            }}
                          >
                            <Trash2 size={13} />
                          </button>
                        </div>
                      </div>

                      <h4 style={{ fontSize: '16px', fontWeight: '700', color: '#171151', margin: '0 0 6px' }}>
                        {item.title || 'Untitled Service'}
                      </h4>
                      <p style={{ fontSize: '13px', color: '#64748B', lineHeight: '1.5', margin: '0 0 16px' }}>
                        {item.description || 'No description provided.'}
                      </p>
                    </div>

                    <button
                      type="button"
                      onClick={() => openServiceModal(idx)}
                      style={{
                        width: '100%',
                        padding: '10px',
                        backgroundColor: '#0E63FF',
                        color: '#ffffff',
                        border: 'none',
                        borderRadius: '8px',
                        fontSize: '13px',
                        fontWeight: '700',
                        cursor: 'pointer',
                        display: 'flex',
                        alignItems: 'center',
                        justifyContent: 'center',
                        gap: '6px',
                        boxShadow: '0 2px 8px rgba(14, 99, 255, 0.2)',
                      }}
                    >
                      <Edit3 size={15} />
                      <span>Edit Service &amp; Detail Page</span>
                    </button>
                  </div>
                );
              })}
            </div>
          </div>
        </div>
      )}

      {/* TAB 2: TOP BANNER SETTINGS */}
      {activeTab === 'banner' && (
        <div style={{ backgroundColor: '#ffffff', borderRadius: '12px', padding: '28px', border: '1px solid #ECEEF3' }}>
          <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '24px' }}>
            <h3 style={{ fontSize: '18px', fontWeight: '700', color: '#171151', margin: 0 }}>
              Top Hero Breadcrumb Banner
            </h3>
            <label style={{ display: 'flex', alignItems: 'center', gap: '8px', fontSize: '14px', fontWeight: '600', color: '#171151', cursor: 'pointer' }}>
              <input
                type="checkbox"
                checked={banner.enabled !== false}
                onChange={(e) => updateBanner('enabled', e.target.checked)}
                style={{ width: '18px', height: '18px', accentColor: '#0E63FF' }}
              />
              <span>Enable Breadcrumb Banner</span>
            </label>
          </div>

          <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(280px, 1fr))', gap: '20px', marginBottom: '20px' }}>
            <div>
              <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>Banner Title</label>
              <input
                type="text"
                value={banner.title || ''}
                onChange={(e) => updateBanner('title', e.target.value)}
                placeholder="e.g. Services- 01"
                style={{ width: '100%', padding: '10px 14px', borderRadius: '8px', border: '1px solid #D1D6E0', fontSize: '14px', boxSizing: 'border-box' }}
              />
            </div>
            <div>
              <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>Breadcrumb Link Text</label>
              <input
                type="text"
                value={banner.subTitle || ''}
                onChange={(e) => updateBanner('subTitle', e.target.value)}
                placeholder="e.g. Bioxlab : Services"
                style={{ width: '100%', padding: '10px 14px', borderRadius: '8px', border: '1px solid #D1D6E0', fontSize: '14px', boxSizing: 'border-box' }}
              />
            </div>
          </div>

          <ImageUploadControl
            label="Banner Background Image"
            value={banner.bgImage || ''}
            onChange={(url) => updateBanner('bgImage', url)}
          />
        </div>
      )}

      {/* TAB 3: WHY CHOOSE US / SPECIALISTS */}
      {activeTab === 'choose' && (
        <div style={{ display: 'flex', flexDirection: 'column', gap: '20px' }}>
          {/* Header & Bottom Link */}
          <div style={{ backgroundColor: '#ffffff', borderRadius: '12px', padding: '28px', border: '1px solid #ECEEF3' }}>
            <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '20px' }}>
              <h3 style={{ fontSize: '18px', fontWeight: '700', color: '#171151', margin: 0 }}>
                Why Choose Us (Specialists Section)
              </h3>
              <label style={{ display: 'flex', alignItems: 'center', gap: '8px', fontSize: '14px', fontWeight: '600', color: '#171151', cursor: 'pointer' }}>
                <input
                  type="checkbox"
                  checked={chooseSection.enabled !== false}
                  onChange={(e) => updateChooseSection('enabled', e.target.checked)}
                  style={{ width: '18px', height: '18px', accentColor: '#0E63FF' }}
                />
                <span>Enable Choose Section</span>
              </label>
            </div>

            <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(280px, 1fr))', gap: '20px' }}>
              <div>
                <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>Section Subtitle</label>
                <input
                  type="text"
                  value={chooseSection.subTitle || ''}
                  onChange={(e) => updateChooseSection('subTitle', e.target.value)}
                  placeholder="e.g. Our Specialists"
                  style={{ width: '100%', padding: '10px 14px', borderRadius: '8px', border: '1px solid #D1D6E0', fontSize: '14px', boxSizing: 'border-box' }}
                />
              </div>
              <div>
                <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>Section Title</label>
                <input
                  type="text"
                  value={chooseSection.title || ''}
                  onChange={(e) => updateChooseSection('title', e.target.value)}
                  placeholder="e.g. Why Choose Us"
                  style={{ width: '100%', padding: '10px 14px', borderRadius: '8px', border: '1px solid #D1D6E0', fontSize: '14px', boxSizing: 'border-box' }}
                />
              </div>
              <div>
                <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>Bottom Text Prefix</label>
                <input
                  type="text"
                  value={chooseSection.bottomText || ''}
                  onChange={(e) => updateChooseSection('bottomText', e.target.value)}
                  placeholder="e.g. Laboratories Used For Scientific Research :"
                  style={{ width: '100%', padding: '10px 14px', borderRadius: '8px', border: '1px solid #D1D6E0', fontSize: '14px', boxSizing: 'border-box' }}
                />
              </div>
              <div>
                <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>Bottom Link Text</label>
                <input
                  type="text"
                  value={chooseSection.bottomLinkText || ''}
                  onChange={(e) => updateChooseSection('bottomLinkText', e.target.value)}
                  placeholder="e.g. Take Many Forms"
                  style={{ width: '100%', padding: '10px 14px', borderRadius: '8px', border: '1px solid #D1D6E0', fontSize: '14px', boxSizing: 'border-box' }}
                />
              </div>
            </div>
          </div>

          {/* 4 Feature Items */}
          <div style={{ backgroundColor: '#ffffff', borderRadius: '12px', padding: '28px', border: '1px solid #ECEEF3' }}>
            <h3 style={{ fontSize: '18px', fontWeight: '700', color: '#171151', margin: '0 0 20px' }}>
              Specialist Feature Items
            </h3>
            <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(280px, 1fr))', gap: '20px' }}>
              {(chooseSection.items || fallbackServicesPage.chooseSection.items).map((item, idx) => (
                <div
                  key={item.id || idx}
                  style={{
                    border: '1px solid #E2E8F0',
                    borderRadius: '10px',
                    padding: '20px',
                    backgroundColor: '#F8FAFC',
                  }}
                >
                  <h5 style={{ fontSize: '14px', fontWeight: '700', color: '#0E63FF', margin: '0 0 12px' }}>
                    Feature #{idx + 1}
                  </h5>

                  <div style={{ marginBottom: '10px' }}>
                    <label style={{ display: 'block', fontSize: '12px', fontWeight: '600', color: '#64748B', marginBottom: '4px' }}>Feature Title</label>
                    <input
                      type="text"
                      value={item.title || ''}
                      onChange={(e) => updateChooseItem(idx, 'title', e.target.value)}
                      style={{ width: '100%', padding: '8px 12px', borderRadius: '6px', border: '1px solid #CBD5E1', fontSize: '13px', boxSizing: 'border-box' }}
                    />
                  </div>

                  <div>
                    <label style={{ display: 'block', fontSize: '12px', fontWeight: '600', color: '#64748B', marginBottom: '4px' }}>Description</label>
                    <textarea
                      rows={2}
                      value={item.description || ''}
                      onChange={(e) => updateChooseItem(idx, 'description', e.target.value)}
                      style={{ width: '100%', padding: '8px 12px', borderRadius: '6px', border: '1px solid #CBD5E1', fontSize: '13px', lineHeight: '1.4', boxSizing: 'border-box' }}
                    ></textarea>
                  </div>
                </div>
              ))}
            </div>
          </div>
        </div>
      )}

      {/* TAB 4: SUPPORT AREA / NEED ANY HELP FORM */}
      {activeTab === 'support' && (
        <div style={{ backgroundColor: '#ffffff', borderRadius: '12px', padding: '28px', border: '1px solid #ECEEF3' }}>
          <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '20px' }}>
            <h3 style={{ fontSize: '18px', fontWeight: '700', color: '#171151', margin: 0 }}>
              Need Any Help (Direct Contact Support Form)
            </h3>
            <label style={{ display: 'flex', alignItems: 'center', gap: '8px', fontSize: '14px', fontWeight: '600', color: '#171151', cursor: 'pointer' }}>
              <input
                type="checkbox"
                checked={supportSection.enabled !== false}
                onChange={(e) => updateSupportSection('enabled', e.target.checked)}
                style={{ width: '18px', height: '18px', accentColor: '#0E63FF' }}
              />
              <span>Enable Support Form Section</span>
            </label>
          </div>

          <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(280px, 1fr))', gap: '20px' }}>
            <div>
              <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>Section Subtitle</label>
              <input
                type="text"
                value={supportSection.subTitle || ''}
                onChange={(e) => updateSupportSection('subTitle', e.target.value)}
                placeholder="e.g. Get in touch"
                style={{ width: '100%', padding: '10px 14px', borderRadius: '8px', border: '1px solid #D1D6E0', fontSize: '14px', boxSizing: 'border-box' }}
              />
            </div>
            <div>
              <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>Section Title</label>
              <input
                type="text"
                value={supportSection.title || ''}
                onChange={(e) => updateSupportSection('title', e.target.value)}
                placeholder="e.g. Need Any Help"
                style={{ width: '100%', padding: '10px 14px', borderRadius: '8px', border: '1px solid #D1D6E0', fontSize: '14px', boxSizing: 'border-box' }}
              />
            </div>
            <div>
              <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>Form Top Tagline</label>
              <input
                type="text"
                value={supportSection.tagline || ''}
                onChange={(e) => updateSupportSection('tagline', e.target.value)}
                placeholder="e.g. Derect Contact with us"
                style={{ width: '100%', padding: '10px 14px', borderRadius: '8px', border: '1px solid #D1D6E0', fontSize: '14px', boxSizing: 'border-box' }}
              />
            </div>
            <div>
              <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>Form Submit Button Text</label>
              <input
                type="text"
                value={supportSection.btnText || ''}
                onChange={(e) => updateSupportSection('btnText', e.target.value)}
                placeholder="e.g. Send Message"
                style={{ width: '100%', padding: '10px 14px', borderRadius: '8px', border: '1px solid #D1D6E0', fontSize: '14px', boxSizing: 'border-box' }}
              />
            </div>
          </div>
        </div>
      )}

      {/* FULL SERVICE & DETAIL PAGE MODAL EDITOR */}
      {editingServiceIndex !== null && editFormData && (
        <div
          style={{
            position: 'fixed',
            inset: 0,
            backgroundColor: 'rgba(15, 23, 42, 0.7)',
            backdropFilter: 'blur(4px)',
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'center',
            zIndex: 9999,
            padding: '20px',
          }}
        >
          <div
            style={{
              backgroundColor: '#ffffff',
              borderRadius: '16px',
              maxWidth: '900px',
              width: '100%',
              maxHeight: '90vh',
              display: 'flex',
              flexDirection: 'column',
              boxShadow: '0 25px 50px -12px rgba(0, 0, 0, 0.25)',
              overflow: 'hidden',
            }}
          >
            {/* Modal Header */}
            <div
              style={{
                padding: '20px 28px',
                borderBottom: '1px solid #E2E8F0',
                display: 'flex',
                justifyContent: 'space-between',
                alignItems: 'center',
                backgroundColor: '#F8FAFC',
              }}
            >
              <div>
                <h3 style={{ fontSize: '18px', fontWeight: '800', color: '#171151', margin: '0 0 2px' }}>
                  Edit Service: {editFormData.title || 'Untitled'}
                </h3>
                <span style={{ fontSize: '12px', color: '#64748B' }}>
                  Live URL: <code>/services/{editFormData.slug || 'slug'}</code>
                </span>
              </div>
              <button
                type="button"
                onClick={() => {
                  setEditingServiceIndex(null);
                  setEditFormData(null);
                }}
                style={{
                  background: 'none',
                  border: 'none',
                  cursor: 'pointer',
                  padding: '6px',
                  color: '#64748B',
                }}
              >
                <X size={20} />
              </button>
            </div>

            {/* Modal Scrollable Body */}
            <div style={{ padding: '28px', overflowY: 'auto', flex: 1, display: 'flex', flexDirection: 'column', gap: '24px' }}>
              
              {/* Section 1: Services Page Card Info */}
              <div style={{ backgroundColor: '#F8FAFC', padding: '20px', borderRadius: '12px', border: '1px solid #E2E8F0' }}>
                <h4 style={{ fontSize: '15px', fontWeight: '700', color: '#0E63FF', margin: '0 0 16px', display: 'flex', alignItems: 'center', gap: '6px' }}>
                  <span>1. Card Details (Shown on Services Page)</span>
                </h4>
                <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(240px, 1fr))', gap: '16px', marginBottom: '14px' }}>
                  <div>
                    <label style={{ display: 'block', fontSize: '12px', fontWeight: '600', color: '#171151', marginBottom: '4px' }}>Service Title</label>
                    <input
                      type="text"
                      value={editFormData.title || ''}
                      onChange={(e) => setEditFormData({ ...editFormData, title: e.target.value })}
                      style={{ width: '100%', padding: '10px 12px', borderRadius: '6px', border: '1px solid #CBD5E1', fontSize: '13px', boxSizing: 'border-box' }}
                    />
                  </div>
                  <div>
                    <label style={{ display: 'block', fontSize: '12px', fontWeight: '600', color: '#171151', marginBottom: '4px' }}>Slug / URL identifier</label>
                    <input
                      type="text"
                      value={editFormData.slug || ''}
                      onChange={(e) => setEditFormData({ ...editFormData, slug: e.target.value })}
                      placeholder="e.g. hemoglobin-test"
                      style={{ width: '100%', padding: '10px 12px', borderRadius: '6px', border: '1px solid #CBD5E1', fontSize: '13px', boxSizing: 'border-box' }}
                    />
                  </div>
                  <div>
                    <label style={{ display: 'block', fontSize: '12px', fontWeight: '600', color: '#171151', marginBottom: '4px' }}>Button Text</label>
                    <input
                      type="text"
                      value={editFormData.btnText || 'Read More'}
                      onChange={(e) => setEditFormData({ ...editFormData, btnText: e.target.value })}
                      style={{ width: '100%', padding: '10px 12px', borderRadius: '6px', border: '1px solid #CBD5E1', fontSize: '13px', boxSizing: 'border-box' }}
                    />
                  </div>
                </div>
                <div>
                  <label style={{ display: 'block', fontSize: '12px', fontWeight: '600', color: '#171151', marginBottom: '4px' }}>Short Description</label>
                  <textarea
                    rows={2}
                    value={editFormData.description || ''}
                    onChange={(e) => setEditFormData({ ...editFormData, description: e.target.value })}
                    style={{ width: '100%', padding: '10px 12px', borderRadius: '6px', border: '1px solid #CBD5E1', fontSize: '13px', boxSizing: 'border-box' }}
                  ></textarea>
                </div>
              </div>

              {/* Section 2: Detail Page Hero Banner & Images */}
              <div style={{ backgroundColor: '#F8FAFC', padding: '20px', borderRadius: '12px', border: '1px solid #E2E8F0' }}>
                <h4 style={{ fontSize: '15px', fontWeight: '700', color: '#0E63FF', margin: '0 0 16px' }}>
                  2. Detail Page Hero Banner &amp; Images
                </h4>
                <div style={{ marginBottom: '14px' }}>
                  <label style={{ display: 'block', fontSize: '12px', fontWeight: '600', color: '#171151', marginBottom: '4px' }}>Banner Subtitle / Category</label>
                  <input
                    type="text"
                    value={editFormData.bannerSubTitle || ''}
                    onChange={(e) => setEditFormData({ ...editFormData, bannerSubTitle: e.target.value })}
                    placeholder="e.g. Automated Clinical Diagnostic & Hematology Systems"
                    style={{ width: '100%', padding: '10px 12px', borderRadius: '6px', border: '1px solid #CBD5E1', fontSize: '13px', boxSizing: 'border-box' }}
                  />
                </div>

                <ImageUploadControl
                  label="Hero Breadcrumb Background Image"
                  value={editFormData.bannerImage || '/assets/img/banner/breadcrumb-01.jpg'}
                  onChange={(url) => setEditFormData({ ...editFormData, bannerImage: url })}
                />

                <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(280px, 1fr))', gap: '16px' }}>
                  <ImageUploadControl
                    label="Showcase Image 1 (Left)"
                    value={editFormData.image1 || '/assets/img/services/services-thumb-07.jpg'}
                    onChange={(url) => setEditFormData({ ...editFormData, image1: url })}
                  />
                  <ImageUploadControl
                    label="Showcase Image 2 (Right)"
                    value={editFormData.image2 || '/assets/img/services/services-thumb-08.jpg'}
                    onChange={(url) => setEditFormData({ ...editFormData, image2: url })}
                  />
                </div>
              </div>

              {/* Section 3: Technical Process Section */}
              <div style={{ backgroundColor: '#F8FAFC', padding: '20px', borderRadius: '12px', border: '1px solid #E2E8F0' }}>
                <h4 style={{ fontSize: '15px', fontWeight: '700', color: '#0E63FF', margin: '0 0 16px' }}>
                  3. Technical Process Section
                </h4>
                <div style={{ marginBottom: '14px' }}>
                  <label style={{ display: 'block', fontSize: '12px', fontWeight: '600', color: '#171151', marginBottom: '4px' }}>Process Title</label>
                  <input
                    type="text"
                    value={editFormData.processTitle || ''}
                    onChange={(e) => setEditFormData({ ...editFormData, processTitle: e.target.value })}
                    placeholder="e.g. Hemoglobin & Hematology Analytical Process"
                    style={{ width: '100%', padding: '10px 12px', borderRadius: '6px', border: '1px solid #CBD5E1', fontSize: '13px', boxSizing: 'border-box' }}
                  />
                </div>
                <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(280px, 1fr))', gap: '14px', marginBottom: '14px' }}>
                  <div>
                    <label style={{ display: 'block', fontSize: '12px', fontWeight: '600', color: '#171151', marginBottom: '4px' }}>Process Paragraph 1</label>
                    <textarea
                      rows={3}
                      value={editFormData.processText || ''}
                      onChange={(e) => setEditFormData({ ...editFormData, processText: e.target.value })}
                      style={{ width: '100%', padding: '10px 12px', borderRadius: '6px', border: '1px solid #CBD5E1', fontSize: '13px', boxSizing: 'border-box' }}
                    ></textarea>
                  </div>
                  <div>
                    <label style={{ display: 'block', fontSize: '12px', fontWeight: '600', color: '#171151', marginBottom: '4px' }}>Process Paragraph 2</label>
                    <textarea
                      rows={3}
                      value={editFormData.processText2 || ''}
                      onChange={(e) => setEditFormData({ ...editFormData, processText2: e.target.value })}
                      style={{ width: '100%', padding: '10px 12px', borderRadius: '6px', border: '1px solid #CBD5E1', fontSize: '13px', boxSizing: 'border-box' }}
                    ></textarea>
                  </div>
                </div>
                <div>
                  <label style={{ display: 'block', fontSize: '12px', fontWeight: '600', color: '#171151', marginBottom: '4px' }}>
                    Process Key Bullet Points (One per line)
                  </label>
                  <textarea
                    rows={4}
                    value={editFormData.processPointsText || ''}
                    onChange={(e) => setEditFormData({ ...editFormData, processPointsText: e.target.value })}
                    placeholder="Enter each bullet point on a new line..."
                    style={{ width: '100%', padding: '10px 12px', borderRadius: '6px', border: '1px solid #CBD5E1', fontSize: '13px', lineHeight: '1.5', boxSizing: 'border-box' }}
                  ></textarea>
                </div>
              </div>

              {/* Section 4: 4 Simple Steps Section */}
              <div style={{ backgroundColor: '#F8FAFC', padding: '20px', borderRadius: '12px', border: '1px solid #E2E8F0' }}>
                <h4 style={{ fontSize: '15px', fontWeight: '700', color: '#0E63FF', margin: '0 0 16px' }}>
                  4. 4 Simple Steps Section
                </h4>
                <div style={{ marginBottom: '14px' }}>
                  <label style={{ display: 'block', fontSize: '12px', fontWeight: '600', color: '#171151', marginBottom: '4px' }}>Steps Section Title</label>
                  <input
                    type="text"
                    value={editFormData.stepsTitle || ''}
                    onChange={(e) => setEditFormData({ ...editFormData, stepsTitle: e.target.value })}
                    placeholder="e.g. 4 Simple Steps"
                    style={{ width: '100%', padding: '10px 12px', borderRadius: '6px', border: '1px solid #CBD5E1', fontSize: '13px', boxSizing: 'border-box' }}
                  />
                </div>
                <div style={{ marginBottom: '14px' }}>
                  <label style={{ display: 'block', fontSize: '12px', fontWeight: '600', color: '#171151', marginBottom: '4px' }}>Steps Description</label>
                  <textarea
                    rows={2}
                    value={editFormData.stepsText || ''}
                    onChange={(e) => setEditFormData({ ...editFormData, stepsText: e.target.value })}
                    style={{ width: '100%', padding: '10px 12px', borderRadius: '6px', border: '1px solid #CBD5E1', fontSize: '13px', boxSizing: 'border-box' }}
                  ></textarea>
                </div>
                <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(240px, 1fr))', gap: '14px' }}>
                  <div>
                    <label style={{ display: 'block', fontSize: '12px', fontWeight: '600', color: '#171151', marginBottom: '4px' }}>Phase 1 Points (1 per line)</label>
                    <textarea
                      rows={4}
                      value={editFormData.stepsCol1Text || ''}
                      onChange={(e) => setEditFormData({ ...editFormData, stepsCol1Text: e.target.value })}
                      style={{ width: '100%', padding: '8px 10px', borderRadius: '6px', border: '1px solid #CBD5E1', fontSize: '12px', boxSizing: 'border-box' }}
                    ></textarea>
                  </div>
                  <div>
                    <label style={{ display: 'block', fontSize: '12px', fontWeight: '600', color: '#171151', marginBottom: '4px' }}>Phase 2 Points (1 per line)</label>
                    <textarea
                      rows={4}
                      value={editFormData.stepsCol2Text || ''}
                      onChange={(e) => setEditFormData({ ...editFormData, stepsCol2Text: e.target.value })}
                      style={{ width: '100%', padding: '8px 10px', borderRadius: '6px', border: '1px solid #CBD5E1', fontSize: '12px', boxSizing: 'border-box' }}
                    ></textarea>
                  </div>
                  <div>
                    <label style={{ display: 'block', fontSize: '12px', fontWeight: '600', color: '#171151', marginBottom: '4px' }}>Phase 3 Points (1 per line)</label>
                    <textarea
                      rows={4}
                      value={editFormData.stepsCol3Text || ''}
                      onChange={(e) => setEditFormData({ ...editFormData, stepsCol3Text: e.target.value })}
                      style={{ width: '100%', padding: '8px 10px', borderRadius: '6px', border: '1px solid #CBD5E1', fontSize: '12px', boxSizing: 'border-box' }}
                    ></textarea>
                  </div>
                </div>
              </div>

              {/* Section 5: Research & Bottom Showcase Banner */}
              <div style={{ backgroundColor: '#F8FAFC', padding: '20px', borderRadius: '12px', border: '1px solid #E2E8F0' }}>
                <h4 style={{ fontSize: '15px', fontWeight: '700', color: '#0E63FF', margin: '0 0 16px' }}>
                  5. Research Section &amp; Bottom Showcase
                </h4>
                <div style={{ marginBottom: '14px' }}>
                  <label style={{ display: 'block', fontSize: '12px', fontWeight: '600', color: '#171151', marginBottom: '4px' }}>Research Section Title</label>
                  <input
                    type="text"
                    value={editFormData.researchTitle || ''}
                    onChange={(e) => setEditFormData({ ...editFormData, researchTitle: e.target.value })}
                    placeholder="e.g. Our Research"
                    style={{ width: '100%', padding: '10px 12px', borderRadius: '6px', border: '1px solid #CBD5E1', fontSize: '13px', boxSizing: 'border-box' }}
                  />
                </div>
                <div style={{ marginBottom: '16px' }}>
                  <label style={{ display: 'block', fontSize: '12px', fontWeight: '600', color: '#171151', marginBottom: '4px' }}>Research Description</label>
                  <textarea
                    rows={2}
                    value={editFormData.researchText || ''}
                    onChange={(e) => setEditFormData({ ...editFormData, researchText: e.target.value })}
                    style={{ width: '100%', padding: '10px 12px', borderRadius: '6px', border: '1px solid #CBD5E1', fontSize: '13px', boxSizing: 'border-box' }}
                  ></textarea>
                </div>

                <ImageUploadControl
                  label="Bottom Full-Width Showcase Banner Image"
                  value={editFormData.showcaseBanner || '/assets/img/services/services-thumb-09.jpg'}
                  onChange={(url) => setEditFormData({ ...editFormData, showcaseBanner: url })}
                />

                <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(240px, 1fr))', gap: '14px' }}>
                  <div>
                    <label style={{ display: 'block', fontSize: '12px', fontWeight: '600', color: '#171151', marginBottom: '4px' }}>Bottom Project Link Text</label>
                    <input
                      type="text"
                      value={editFormData.bottomLinkText || 'Our Project'}
                      onChange={(e) => setEditFormData({ ...editFormData, bottomLinkText: e.target.value })}
                      style={{ width: '100%', padding: '10px 12px', borderRadius: '6px', border: '1px solid #CBD5E1', fontSize: '13px', boxSizing: 'border-box' }}
                    />
                  </div>
                  <div>
                    <label style={{ display: 'block', fontSize: '12px', fontWeight: '600', color: '#171151', marginBottom: '4px' }}>Bottom Project Link Destination</label>
                    <input
                      type="text"
                      value={editFormData.bottomLink || '/contact'}
                      onChange={(e) => setEditFormData({ ...editFormData, bottomLink: e.target.value })}
                      style={{ width: '100%', padding: '10px 12px', borderRadius: '6px', border: '1px solid #CBD5E1', fontSize: '13px', boxSizing: 'border-box' }}
                    />
                  </div>
                </div>
              </div>

            </div>

            {/* Modal Footer */}
            <div
              style={{
                padding: '16px 28px',
                borderTop: '1px solid #E2E8F0',
                display: 'flex',
                justifyContent: 'flex-end',
                alignItems: 'center',
                gap: '12px',
                backgroundColor: '#F8FAFC',
              }}
            >
              <button
                type="button"
                onClick={() => {
                  setEditingServiceIndex(null);
                  setEditFormData(null);
                }}
                style={{
                  padding: '10px 20px',
                  borderRadius: '8px',
                  border: '1px solid #CBD5E1',
                  backgroundColor: '#ffffff',
                  color: '#475569',
                  fontWeight: '600',
                  fontSize: '14px',
                  cursor: 'pointer',
                }}
              >
                Cancel
              </button>
              <button
                type="button"
                onClick={saveServiceModal}
                style={{
                  padding: '10px 24px',
                  borderRadius: '8px',
                  border: 'none',
                  backgroundColor: '#0E63FF',
                  color: '#ffffff',
                  fontWeight: '700',
                  fontSize: '14px',
                  cursor: 'pointer',
                  display: 'inline-flex',
                  alignItems: 'center',
                  gap: '6px',
                  boxShadow: '0 4px 12px rgba(14, 99, 255, 0.25)',
                }}
              >
                <Save size={16} />
                <span>Save Service &amp; Detail Page</span>
              </button>
            </div>
          </div>
        </div>
      )}

      {/* Delete Confirmation Modal */}
      {deleteModal.open && (
        <div
          style={{
            position: 'fixed',
            inset: 0,
            backgroundColor: 'rgba(0,0,0,0.5)',
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'center',
            zIndex: 9999,
          }}
        >
          <div
            style={{
              backgroundColor: '#ffffff',
              borderRadius: '12px',
              padding: '24px',
              maxWidth: '420px',
              width: '90%',
              boxShadow: '0 20px 25px -5px rgba(0, 0, 0, 0.1)',
            }}
          >
            <h4 style={{ fontSize: '18px', fontWeight: '700', color: '#171151', margin: '0 0 10px' }}>
              Delete Service Card?
            </h4>
            <p style={{ fontSize: '14px', color: '#6b6b6b', margin: '0 0 20px' }}>
              Are you sure you want to delete &quot;<strong>{deleteModal.title}</strong>&quot;? This will also remove its detail page.
            </p>
            <div style={{ display: 'flex', justifyContent: 'flex-end', gap: '10px' }}>
              <button
                type="button"
                onClick={() => setDeleteModal({ open: false, type: '', id: null, title: '' })}
                style={{ padding: '8px 16px', borderRadius: '6px', border: '1px solid #D1D6E0', backgroundColor: '#ffffff', color: '#475569', fontWeight: '600', cursor: 'pointer' }}
              >
                Cancel
              </button>
              <button
                type="button"
                onClick={handleConfirmDelete}
                style={{ padding: '8px 16px', borderRadius: '6px', border: 'none', backgroundColor: '#EF4444', color: '#ffffff', fontWeight: '600', cursor: 'pointer' }}
              >
                Delete
              </button>
            </div>
          </div>
        </div>
      )}
    </div>
  );
}
