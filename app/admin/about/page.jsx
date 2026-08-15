'use client';

import React, { useState, useEffect, useRef } from 'react';
import {
  Save,
  Plus,
  Trash2,
  CheckCircle2,
  Award,
  CheckSquare,
  Users,
  LayoutTemplate,
  Eye,
  EyeOff,
  AlertTriangle,
  UploadCloud,
  Compass,
  Layers,
} from 'lucide-react';
import { fallbackAboutPage } from '@/lib/data';

function FileUploadField({
  label,
  value,
  onChange,
  accept = 'image/*',
  placeholder = 'Select local file or enter path...',
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
    <div>
      {label && (
        <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>
          {label}
        </label>
      )}
      <div style={{ display: 'flex', gap: '10px', alignItems: 'center', flexWrap: 'wrap' }}>
        {value && (
          <div
            style={{
              width: '46px',
              height: '46px',
              borderRadius: '8px',
              overflow: 'hidden',
              border: '1px solid #CBD5E1',
              backgroundColor: '#F8FAFC',
              flexShrink: 0,
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'center',
            }}
          >
            <img src={value} alt="Preview" style={{ width: '100%', height: '100%', objectFit: 'cover' }} />
          </div>
        )}

        <input
          type="text"
          value={value || ''}
          onChange={(e) => onChange(e.target.value)}
          placeholder={placeholder}
          style={{
            flex: 1,
            minWidth: '180px',
            padding: '10px 14px',
            borderRadius: '8px',
            border: '1px solid #D1D6E0',
            fontSize: '13px',
            boxSizing: 'border-box',
          }}
        />

        <input
          ref={fileInputRef}
          type="file"
          accept={accept}
          onChange={handleFileChange}
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
            padding: '10px 16px',
            backgroundColor: '#EFF6FF',
            color: '#0E63FF',
            border: '1px solid #BFDBFE',
            borderRadius: '8px',
            fontSize: '13px',
            fontWeight: '600',
            cursor: uploading ? 'not-allowed' : 'pointer',
            whiteSpace: 'nowrap',
          }}
        >
          <UploadCloud size={16} />
          <span>{uploading ? 'Uploading...' : 'Upload Image'}</span>
        </button>
      </div>
    </div>
  );
}

export default function AdminAboutPageManager() {
  const [activeTab, setActiveTab] = useState('banner');
  const [aboutData, setAboutData] = useState(fallbackAboutPage);
  const [saving, setSaving] = useState(false);
  const [saveStatus, setSaveStatus] = useState({ type: '', msg: '' });

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
      const res = await fetch('/api/admin/about');
      const data = await res.json();
      if (data.data) {
        setAboutData(data.data);
      }
    } catch (e) {
      console.error('Error fetching about page data:', e);
    }
  };

  const syncAboutData = async (updatedData) => {
    try {
      await fetch('/api/admin/about', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(updatedData || aboutData),
      });
    } catch (err) {
      console.error('Error syncing about page data:', err);
    }
  };

  const handleChange = (section, field, value) => {
    setAboutData((prev) => {
      const updated = {
        ...prev,
        [section]: {
          ...prev[section],
          [field]: value,
        },
      };
      syncAboutData(updated);
      return updated;
    });
  };

  const toggleSection = (section) => {
    const newEnabled = aboutData[section]?.enabled === false ? true : false;
    const updated = {
      ...aboutData,
      [section]: {
        ...aboutData[section],
        enabled: newEnabled,
      },
    };
    setAboutData(updated);
    syncAboutData(updated);
    showToast(`${section} is now ${newEnabled ? 'Enabled & Live' : 'Disabled & Hidden'} on About Us page!`);
  };

  const handleSaveAll = async (e) => {
    if (e) e.preventDefault();
    setSaving(true);
    try {
      const res = await fetch('/api/admin/about', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(aboutData),
      });
      const data = await res.json();
      if (res.ok) {
        showToast('About Us page settings saved and published live!');
      } else {
        showToast(data.message || 'Error saving changes', 'error');
      }
    } catch (err) {
      showToast('Network error saving configuration.', 'error');
    } finally {
      setSaving(false);
    }
  };

  const tabs = [
    { id: 'banner', label: '1. Top Banner', icon: LayoutTemplate, sectionKey: 'banner' },
    { id: 'about', label: '2. Company Profile', icon: Award, sectionKey: 'about' },
    { id: 'counters', label: '3. Statistics Counters', icon: CheckSquare, sectionKey: 'counters' },
    { id: 'why', label: '4. Why Choose Us', icon: Compass, sectionKey: 'whyChooseUs' },
    { id: 'tabs', label: '5. Process, Mission & Value Tabs', icon: Layers, sectionKey: 'tabsSection' },
    { id: 'team', label: '6. Specialists Team', icon: Users, sectionKey: 'teamSection' },
  ];

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
            <span>About Us Page Manager</span>
            <span style={{ fontSize: '12px', fontWeight: '600', backgroundColor: '#EFF6FF', color: '#0E63FF', padding: '4px 10px', borderRadius: '20px' }}>
              Dynamic & Live
            </span>
          </h1>
          <p style={{ fontSize: '14px', color: '#6b6b6b', margin: 0 }}>
            Customize all content, images, counters, process tabs, and section visibilities for the /about page.
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
          <span>{saving ? 'Saving...' : 'Save & Publish About Page'}</span>
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
      <div
        style={{
          display: 'flex',
          gap: '8px',
          overflowX: 'auto',
          paddingBottom: '12px',
          marginBottom: '20px',
          borderBottom: '1px solid #ECEEF3',
        }}
      >
        {tabs.map((t) => {
          const Icon = t.icon;
          const isEnabled = aboutData[t.sectionKey]?.enabled !== false;
          const isActive = activeTab === t.id;

          return (
            <button
              key={t.id}
              onClick={() => setActiveTab(t.id)}
              style={{
                display: 'inline-flex',
                alignItems: 'center',
                gap: '8px',
                padding: '10px 16px',
                borderRadius: '8px',
                border: 'none',
                fontSize: '13px',
                fontWeight: '600',
                cursor: 'pointer',
                whiteSpace: 'nowrap',
                transition: 'all 0.2s ease',
                backgroundColor: isActive ? '#0E63FF' : '#ffffff',
                color: isActive ? '#ffffff' : '#475569',
                boxShadow: isActive ? '0 4px 12px rgba(14, 99, 255, 0.2)' : 'none',
              }}
            >
              <Icon size={16} />
              <span>{t.label}</span>
              <span
                style={{
                  width: '8px',
                  height: '8px',
                  borderRadius: '50%',
                  backgroundColor: isEnabled ? (isActive ? '#ffffff' : '#0b9748') : '#F72A75',
                }}
              ></span>
            </button>
          );
        })}
      </div>

      {/* TAB 1: TOP BANNER */}
      {activeTab === 'banner' && (
        <div style={{ backgroundColor: '#ffffff', borderRadius: '12px', padding: '28px', border: '1px solid #ECEEF3' }}>
          <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '20px' }}>
            <h3 style={{ fontSize: '18px', fontWeight: '700', color: '#171151', margin: 0 }}>
              About Page Breadcrumb & Top Banner
            </h3>
            <button
              onClick={() => toggleSection('banner')}
              style={{
                display: 'inline-flex',
                alignItems: 'center',
                gap: '6px',
                padding: '6px 14px',
                borderRadius: '20px',
                border: 'none',
                fontWeight: '600',
                fontSize: '12px',
                cursor: 'pointer',
                backgroundColor: aboutData.banner?.enabled !== false ? '#E7FAF6' : '#FEEAF1',
                color: aboutData.banner?.enabled !== false ? '#0b9748' : '#F72A75',
              }}
            >
              {aboutData.banner?.enabled !== false ? <Eye size={14} /> : <EyeOff size={14} />}
              <span>{aboutData.banner?.enabled !== false ? 'Banner Visible' : 'Banner Disabled'}</span>
            </button>
          </div>

          <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(280px, 1fr))', gap: '20px' }}>
            <div>
              <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>Main Banner Title</label>
              <input
                type="text"
                value={aboutData.banner?.title || ''}
                onChange={(e) => handleChange('banner', 'title', e.target.value)}
                style={{ width: '100%', padding: '10px 14px', borderRadius: '8px', border: '1px solid #D1D6E0', fontSize: '14px', boxSizing: 'border-box' }}
              />
            </div>
            <div>
              <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>Subtitle / Tagline (Optional)</label>
              <input
                type="text"
                value={aboutData.banner?.subTitle || ''}
                onChange={(e) => handleChange('banner', 'subTitle', e.target.value)}
                style={{ width: '100%', padding: '10px 14px', borderRadius: '8px', border: '1px solid #D1D6E0', fontSize: '14px', boxSizing: 'border-box' }}
              />
            </div>
            <div style={{ gridColumn: '1 / -1' }}>
              <FileUploadField
                label="Banner Background Image (Upload Local Image or Enter Path)"
                value={aboutData.banner?.bgImage || ''}
                onChange={(url) => handleChange('banner', 'bgImage', url)}
                placeholder="/assets/img/banner/breadcrumb-01.jpg"
              />
            </div>
          </div>
        </div>
      )}

      {/* TAB 2: COMPANY PROFILE & STORY */}
      {activeTab === 'about' && (
        <div style={{ backgroundColor: '#ffffff', borderRadius: '12px', padding: '28px', border: '1px solid #ECEEF3' }}>
          <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '20px' }}>
            <h3 style={{ fontSize: '18px', fontWeight: '700', color: '#171151', margin: 0 }}>
              Company Story & Experience Section
            </h3>
            <button
              onClick={() => toggleSection('about')}
              style={{
                display: 'inline-flex',
                alignItems: 'center',
                gap: '6px',
                padding: '6px 14px',
                borderRadius: '20px',
                border: 'none',
                fontWeight: '600',
                fontSize: '12px',
                cursor: 'pointer',
                backgroundColor: aboutData.about?.enabled !== false ? '#E7FAF6' : '#FEEAF1',
                color: aboutData.about?.enabled !== false ? '#0b9748' : '#F72A75',
              }}
            >
              {aboutData.about?.enabled !== false ? <Eye size={14} /> : <EyeOff size={14} />}
              <span>{aboutData.about?.enabled !== false ? 'Section Visible' : 'Section Disabled'}</span>
            </button>
          </div>

          <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(280px, 1fr))', gap: '20px' }}>
            <div>
              <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>Experience Years</label>
              <input
                type="text"
                value={aboutData.about?.experienceYears || ''}
                onChange={(e) => handleChange('about', 'experienceYears', e.target.value)}
                style={{ width: '100%', padding: '10px 14px', borderRadius: '8px', border: '1px solid #D1D6E0', fontSize: '14px', boxSizing: 'border-box' }}
              />
            </div>
            <div>
              <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>Experience Badge Label</label>
              <input
                type="text"
                value={aboutData.about?.experienceText || 'Years of Experience'}
                onChange={(e) => handleChange('about', 'experienceText', e.target.value)}
                style={{ width: '100%', padding: '10px 14px', borderRadius: '8px', border: '1px solid #D1D6E0', fontSize: '14px', boxSizing: 'border-box' }}
              />
            </div>
            <div style={{ gridColumn: '1 / -1' }}>
              <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>Section Headline</label>
              <input
                type="text"
                value={aboutData.about?.title || ''}
                onChange={(e) => handleChange('about', 'title', e.target.value)}
                style={{ width: '100%', padding: '10px 14px', borderRadius: '8px', border: '1px solid #D1D6E0', fontSize: '14px', boxSizing: 'border-box' }}
              />
            </div>
            <div>
              <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>Link Text</label>
              <input
                type="text"
                value={aboutData.about?.linkText || 'Read our MIssion & Vission'}
                onChange={(e) => handleChange('about', 'linkText', e.target.value)}
                style={{ width: '100%', padding: '10px 14px', borderRadius: '8px', border: '1px solid #D1D6E0', fontSize: '14px', boxSizing: 'border-box' }}
              />
            </div>
            <div>
              <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>Link Destination (Anchor / URL)</label>
              <input
                type="text"
                value={aboutData.about?.linkUrl || '#process-mission-tabs'}
                onChange={(e) => handleChange('about', 'linkUrl', e.target.value)}
                style={{ width: '100%', padding: '10px 14px', borderRadius: '8px', border: '1px solid #D1D6E0', fontSize: '14px', boxSizing: 'border-box' }}
              />
            </div>
            <div style={{ gridColumn: '1 / -1' }}>
              <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>Company Story / Description</label>
              <textarea
                rows={5}
                value={aboutData.about?.description || ''}
                onChange={(e) => handleChange('about', 'description', e.target.value)}
                style={{ width: '100%', padding: '10px 14px', borderRadius: '8px', border: '1px solid #D1D6E0', fontSize: '14px', boxSizing: 'border-box' }}
              ></textarea>
            </div>

            {/* Images */}
            <div>
              <FileUploadField
                label="Main About Image"
                value={aboutData.about?.imageMain || '/assets/img/about/about-bg-04.jpg'}
                onChange={(url) => handleChange('about', 'imageMain', url)}
                placeholder="/assets/img/about/about-bg-04.jpg"
              />
            </div>
            <div>
              <FileUploadField
                label="Side Image 1"
                value={aboutData.about?.shape1 || '/assets/img/about/about-bg-05.jpg'}
                onChange={(url) => handleChange('about', 'shape1', url)}
                placeholder="/assets/img/about/about-bg-05.jpg"
              />
            </div>
            <div>
              <FileUploadField
                label="Side Image 2"
                value={aboutData.about?.shape2 || '/assets/img/about/about-bg-06.jpg'}
                onChange={(url) => handleChange('about', 'shape2', url)}
                placeholder="/assets/img/about/about-bg-06.jpg"
              />
            </div>
          </div>
        </div>
      )}

      {/* TAB 3: COUNTERS */}
      {activeTab === 'counters' && (
        <div style={{ backgroundColor: '#ffffff', borderRadius: '12px', padding: '28px', border: '1px solid #ECEEF3' }}>
          <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '20px' }}>
            <h3 style={{ fontSize: '18px', fontWeight: '700', color: '#171151', margin: 0 }}>
              Statistics Counters
            </h3>
            <button
              onClick={() => toggleSection('counters')}
              style={{
                display: 'inline-flex',
                alignItems: 'center',
                gap: '6px',
                padding: '6px 14px',
                borderRadius: '20px',
                border: 'none',
                fontWeight: '600',
                fontSize: '12px',
                cursor: 'pointer',
                backgroundColor: aboutData.counters?.enabled !== false ? '#E7FAF6' : '#FEEAF1',
                color: aboutData.counters?.enabled !== false ? '#0b9748' : '#F72A75',
              }}
            >
              {aboutData.counters?.enabled !== false ? <Eye size={14} /> : <EyeOff size={14} />}
              <span>{aboutData.counters?.enabled !== false ? 'Section Visible' : 'Section Disabled'}</span>
            </button>
          </div>

          <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(240px, 1fr))', gap: '20px' }}>
            {[1, 2, 3, 4].map((num) => (
              <div key={num} style={{ border: '1px solid #ECEEF3', padding: '16px', borderRadius: '8px', backgroundColor: '#F9FAFC' }}>
                <label style={{ display: 'block', fontSize: '13px', fontWeight: '700', color: '#171151', marginBottom: '6px' }}>
                  Counter {num} Number
                </label>
                <input
                  type="text"
                  value={aboutData.counters?.[`item${num}Number`] || ''}
                  onChange={(e) => handleChange('counters', `item${num}Number`, e.target.value)}
                  style={{ width: '100%', padding: '8px 12px', borderRadius: '6px', border: '1px solid #D1D6E0', fontSize: '14px', marginBottom: '8px', boxSizing: 'border-box' }}
                />
                <label style={{ display: 'block', fontSize: '12px', color: '#64748B', marginBottom: '4px' }}>
                  Counter {num} Title
                </label>
                <input
                  type="text"
                  value={aboutData.counters?.[`item${num}Title`] || ''}
                  onChange={(e) => handleChange('counters', `item${num}Title`, e.target.value)}
                  style={{ width: '100%', padding: '8px 12px', borderRadius: '6px', border: '1px solid #D1D6E0', fontSize: '13px', boxSizing: 'border-box' }}
                />
              </div>
            ))}
          </div>
        </div>
      )}

      {/* TAB 4: WHY CHOOSE US */}
      {activeTab === 'why' && (
        <div style={{ backgroundColor: '#ffffff', borderRadius: '12px', padding: '28px', border: '1px solid #ECEEF3' }}>
          <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '20px' }}>
            <h3 style={{ fontSize: '18px', fontWeight: '700', color: '#171151', margin: 0 }}>
              Why Choose Us Section
            </h3>
            <button
              onClick={() => toggleSection('whyChooseUs')}
              style={{
                display: 'inline-flex',
                alignItems: 'center',
                gap: '6px',
                padding: '6px 14px',
                borderRadius: '20px',
                border: 'none',
                fontWeight: '600',
                fontSize: '12px',
                cursor: 'pointer',
                backgroundColor: aboutData.whyChooseUs?.enabled !== false ? '#E7FAF6' : '#FEEAF1',
                color: aboutData.whyChooseUs?.enabled !== false ? '#0b9748' : '#F72A75',
              }}
            >
              {aboutData.whyChooseUs?.enabled !== false ? <Eye size={14} /> : <EyeOff size={14} />}
              <span>{aboutData.whyChooseUs?.enabled !== false ? 'Section Visible' : 'Section Disabled'}</span>
            </button>
          </div>

          <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(280px, 1fr))', gap: '20px' }}>
            <div>
              <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>Section Subtitle</label>
              <input
                type="text"
                value={aboutData.whyChooseUs?.subTitle || ''}
                onChange={(e) => handleChange('whyChooseUs', 'subTitle', e.target.value)}
                style={{ width: '100%', padding: '10px', borderRadius: '6px', border: '1px solid #D1D6E0', boxSizing: 'border-box' }}
              />
            </div>
            <div>
              <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>Section Title</label>
              <input
                type="text"
                value={aboutData.whyChooseUs?.title || ''}
                onChange={(e) => handleChange('whyChooseUs', 'title', e.target.value)}
                style={{ width: '100%', padding: '10px', borderRadius: '6px', border: '1px solid #D1D6E0', boxSizing: 'border-box' }}
              />
            </div>
          </div>
        </div>
      )}

      {/* TAB 5: PROCESS, MISSION & VALUE TABS */}
      {activeTab === 'tabs' && (
        <div style={{ backgroundColor: '#ffffff', borderRadius: '12px', padding: '28px', border: '1px solid #ECEEF3' }}>
          <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '20px' }}>
            <h3 style={{ fontSize: '18px', fontWeight: '700', color: '#171151', margin: 0 }}>
              Process, Mission & Value Interactive Tabs
            </h3>
            <button
              onClick={() => toggleSection('tabsSection')}
              style={{
                display: 'inline-flex',
                alignItems: 'center',
                gap: '6px',
                padding: '6px 14px',
                borderRadius: '20px',
                border: 'none',
                fontWeight: '600',
                fontSize: '12px',
                cursor: 'pointer',
                backgroundColor: aboutData.tabsSection?.enabled !== false ? '#E7FAF6' : '#FEEAF1',
                color: aboutData.tabsSection?.enabled !== false ? '#0b9748' : '#F72A75',
              }}
            >
              {aboutData.tabsSection?.enabled !== false ? <Eye size={14} /> : <EyeOff size={14} />}
              <span>{aboutData.tabsSection?.enabled !== false ? 'Section Visible' : 'Section Disabled'}</span>
            </button>
          </div>

          {/* Sub Tab 1: Process */}
          <div style={{ border: '1px solid #ECEEF3', padding: '20px', borderRadius: '10px', backgroundColor: '#F8FAFC', marginBottom: '24px' }}>
            <h4 style={{ fontSize: '16px', fontWeight: '800', color: '#0E63FF', margin: '0 0 14px' }}>
              Tab 1: Our Process
            </h4>
            <div style={{ marginBottom: '14px' }}>
              <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>Process Subtitle / Info</label>
              <textarea
                rows={2}
                value={aboutData.tabsSection?.processInfo || ''}
                onChange={(e) => handleChange('tabsSection', 'processInfo', e.target.value)}
                style={{ width: '100%', padding: '8px 12px', borderRadius: '6px', border: '1px solid #D1D6E0', fontSize: '13px', boxSizing: 'border-box' }}
              ></textarea>
            </div>
            <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(260px, 1fr))', gap: '16px' }}>
              <div style={{ backgroundColor: '#ffffff', padding: '14px', borderRadius: '8px', border: '1px solid #E2E8F0' }}>
                <label style={{ display: 'block', fontSize: '12px', fontWeight: '700', color: '#0E63FF', marginBottom: '4px' }}>Step 1 Title</label>
                <input
                  type="text"
                  value={aboutData.tabsSection?.step1Title || ''}
                  onChange={(e) => handleChange('tabsSection', 'step1Title', e.target.value)}
                  style={{ width: '100%', padding: '6px 10px', borderRadius: '6px', border: '1px solid #D1D6E0', fontSize: '13px', marginBottom: '8px', boxSizing: 'border-box' }}
                />
                <label style={{ display: 'block', fontSize: '12px', fontWeight: '600', color: '#64748B', marginBottom: '4px' }}>Step 1 Description</label>
                <textarea
                  rows={2}
                  value={aboutData.tabsSection?.step1Desc || ''}
                  onChange={(e) => handleChange('tabsSection', 'step1Desc', e.target.value)}
                  style={{ width: '100%', padding: '6px 10px', borderRadius: '6px', border: '1px solid #D1D6E0', fontSize: '12px', boxSizing: 'border-box' }}
                ></textarea>
              </div>

              <div style={{ backgroundColor: '#ffffff', padding: '14px', borderRadius: '8px', border: '1px solid #E2E8F0' }}>
                <label style={{ display: 'block', fontSize: '12px', fontWeight: '700', color: '#F72A75', marginBottom: '4px' }}>Step 2 Title</label>
                <input
                  type="text"
                  value={aboutData.tabsSection?.step2Title || ''}
                  onChange={(e) => handleChange('tabsSection', 'step2Title', e.target.value)}
                  style={{ width: '100%', padding: '6px 10px', borderRadius: '6px', border: '1px solid #D1D6E0', fontSize: '13px', marginBottom: '8px', boxSizing: 'border-box' }}
                />
                <label style={{ display: 'block', fontSize: '12px', fontWeight: '600', color: '#64748B', marginBottom: '4px' }}>Step 2 Description</label>
                <textarea
                  rows={2}
                  value={aboutData.tabsSection?.step2Desc || ''}
                  onChange={(e) => handleChange('tabsSection', 'step2Desc', e.target.value)}
                  style={{ width: '100%', padding: '6px 10px', borderRadius: '6px', border: '1px solid #D1D6E0', fontSize: '12px', boxSizing: 'border-box' }}
                ></textarea>
              </div>

              <div style={{ backgroundColor: '#ffffff', padding: '14px', borderRadius: '8px', border: '1px solid #E2E8F0' }}>
                <label style={{ display: 'block', fontSize: '12px', fontWeight: '700', color: '#0B9748', marginBottom: '4px' }}>Step 3 Title</label>
                <input
                  type="text"
                  value={aboutData.tabsSection?.step3Title || ''}
                  onChange={(e) => handleChange('tabsSection', 'step3Title', e.target.value)}
                  style={{ width: '100%', padding: '6px 10px', borderRadius: '6px', border: '1px solid #D1D6E0', fontSize: '13px', marginBottom: '8px', boxSizing: 'border-box' }}
                />
                <label style={{ display: 'block', fontSize: '12px', fontWeight: '600', color: '#64748B', marginBottom: '4px' }}>Step 3 Description</label>
                <textarea
                  rows={2}
                  value={aboutData.tabsSection?.step3Desc || ''}
                  onChange={(e) => handleChange('tabsSection', 'step3Desc', e.target.value)}
                  style={{ width: '100%', padding: '6px 10px', borderRadius: '6px', border: '1px solid #D1D6E0', fontSize: '12px', boxSizing: 'border-box' }}
                ></textarea>
              </div>
            </div>
          </div>

          {/* Sub Tab 2: Mission */}
          <div style={{ border: '1px solid #ECEEF3', padding: '20px', borderRadius: '10px', backgroundColor: '#F8FAFC', marginBottom: '24px' }}>
            <h4 style={{ fontSize: '16px', fontWeight: '800', color: '#F72A75', margin: '0 0 14px' }}>
              Tab 2: Our Mission
            </h4>
            <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(280px, 1fr))', gap: '16px' }}>
              <div style={{ gridColumn: '1 / -1' }}>
                <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>Mission Headline</label>
                <input
                  type="text"
                  value={aboutData.tabsSection?.missionHeadline || ''}
                  onChange={(e) => handleChange('tabsSection', 'missionHeadline', e.target.value)}
                  style={{ width: '100%', padding: '8px 12px', borderRadius: '6px', border: '1px solid #D1D6E0', fontSize: '13px', boxSizing: 'border-box' }}
                />
              </div>
              <div style={{ gridColumn: '1 / -1' }}>
                <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>Mission Detailed Description</label>
                <textarea
                  rows={3}
                  value={aboutData.tabsSection?.missionDesc || ''}
                  onChange={(e) => handleChange('tabsSection', 'missionDesc', e.target.value)}
                  style={{ width: '100%', padding: '8px 12px', borderRadius: '6px', border: '1px solid #D1D6E0', fontSize: '13px', boxSizing: 'border-box' }}
                ></textarea>
              </div>
              <div>
                <FileUploadField
                  label="Mission Left Image"
                  value={aboutData.tabsSection?.missionImage1 || '/assets/img/tab/tab-thumb-03.jpg'}
                  onChange={(url) => handleChange('tabsSection', 'missionImage1', url)}
                  placeholder="/assets/img/tab/tab-thumb-03.jpg"
                />
              </div>
              <div>
                <FileUploadField
                  label="Mission Right Image"
                  value={aboutData.tabsSection?.missionImage2 || '/assets/img/tab/tab-thumb-04.jpg'}
                  onChange={(url) => handleChange('tabsSection', 'missionImage2', url)}
                  placeholder="/assets/img/tab/tab-thumb-04.jpg"
                />
              </div>
            </div>
          </div>

          {/* Sub Tab 3: Value */}
          <div style={{ border: '1px solid #ECEEF3', padding: '20px', borderRadius: '10px', backgroundColor: '#F8FAFC' }}>
            <h4 style={{ fontSize: '16px', fontWeight: '800', color: '#0B9748', margin: '0 0 14px' }}>
              Tab 3: Our Value
            </h4>
            <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(280px, 1fr))', gap: '16px' }}>
              <div style={{ gridColumn: '1 / -1' }}>
                <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>Value Headline</label>
                <input
                  type="text"
                  value={aboutData.tabsSection?.valueHeadline || ''}
                  onChange={(e) => handleChange('tabsSection', 'valueHeadline', e.target.value)}
                  style={{ width: '100%', padding: '8px 12px', borderRadius: '6px', border: '1px solid #D1D6E0', fontSize: '13px', boxSizing: 'border-box' }}
                />
              </div>
              <div style={{ gridColumn: '1 / -1' }}>
                <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>Value Detailed Description</label>
                <textarea
                  rows={3}
                  value={aboutData.tabsSection?.valueDesc || ''}
                  onChange={(e) => handleChange('tabsSection', 'valueDesc', e.target.value)}
                  style={{ width: '100%', padding: '8px 12px', borderRadius: '6px', border: '1px solid #D1D6E0', fontSize: '13px', boxSizing: 'border-box' }}
                ></textarea>
              </div>
              <div>
                <FileUploadField
                  label="Value Left Image"
                  value={aboutData.tabsSection?.valueImage1 || '/assets/img/tab/tab-thumb-01.jpg'}
                  onChange={(url) => handleChange('tabsSection', 'valueImage1', url)}
                  placeholder="/assets/img/tab/tab-thumb-01.jpg"
                />
              </div>
              <div>
                <FileUploadField
                  label="Value Right Image"
                  value={aboutData.tabsSection?.valueImage2 || '/assets/img/tab/tab-thumb-02.jpg'}
                  onChange={(url) => handleChange('tabsSection', 'valueImage2', url)}
                  placeholder="/assets/img/tab/tab-thumb-02.jpg"
                />
              </div>
            </div>
          </div>
        </div>
      )}

      {/* TAB 6: TEAM SECTION */}
      {activeTab === 'team' && (
        <div style={{ backgroundColor: '#ffffff', borderRadius: '12px', padding: '28px', border: '1px solid #ECEEF3' }}>
          <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '20px' }}>
            <h3 style={{ fontSize: '18px', fontWeight: '700', color: '#171151', margin: 0 }}>
              Specialists Team Showcase
            </h3>
            <button
              onClick={() => toggleSection('teamSection')}
              style={{
                display: 'inline-flex',
                alignItems: 'center',
                gap: '6px',
                padding: '6px 14px',
                borderRadius: '20px',
                border: 'none',
                fontWeight: '600',
                fontSize: '12px',
                cursor: 'pointer',
                backgroundColor: aboutData.teamSection?.enabled !== false ? '#E7FAF6' : '#FEEAF1',
                color: aboutData.teamSection?.enabled !== false ? '#0b9748' : '#F72A75',
              }}
            >
              {aboutData.teamSection?.enabled !== false ? <Eye size={14} /> : <EyeOff size={14} />}
              <span>{aboutData.teamSection?.enabled !== false ? 'Section Visible' : 'Section Disabled'}</span>
            </button>
          </div>

          <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(280px, 1fr))', gap: '20px' }}>
            <div>
              <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>Section Subtitle</label>
              <input
                type="text"
                value={aboutData.teamSection?.subTitle || ''}
                onChange={(e) => handleChange('teamSection', 'subTitle', e.target.value)}
                style={{ width: '100%', padding: '10px', borderRadius: '6px', border: '1px solid #D1D6E0', boxSizing: 'border-box' }}
              />
            </div>
            <div>
              <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>Section Title</label>
              <input
                type="text"
                value={aboutData.teamSection?.title || ''}
                onChange={(e) => handleChange('teamSection', 'title', e.target.value)}
                style={{ width: '100%', padding: '10px', borderRadius: '6px', border: '1px solid #D1D6E0', boxSizing: 'border-box' }}
              />
            </div>
          </div>
        </div>
      )}
    </div>
  );
}
