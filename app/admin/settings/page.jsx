'use client';

import React, { useState, useEffect, useRef } from 'react';
import {
  Globe,
  Shield,
  Search,
  Phone,
  Save,
  CheckCircle2,
  AlertTriangle,
  Upload,
  ExternalLink,
  Eye,
  Sparkles,
  Share2,
  MessageSquare,
  MessageCircle,
  Smartphone,
} from 'lucide-react';
import { fallbackSiteConfig } from '@/lib/data';

function FileUploadField({
  label,
  value,
  onChange,
  accept = 'image/*',
  placeholder = 'Upload image or enter path...',
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
    <div style={{ marginBottom: '16px' }}>
      {label && (
        <label style={{ display: 'block', fontSize: '13px', fontWeight: '700', color: '#171151', marginBottom: '6px' }}>
          {label}
        </label>
      )}
      <div style={{ display: 'flex', gap: '10px', alignItems: 'center', flexWrap: 'wrap' }}>
        {value && (
          <div
            style={{
              width: '52px',
              height: '52px',
              borderRadius: '8px',
              overflow: 'hidden',
              border: '1px solid #CBD5E1',
              backgroundColor: '#F8FAFC',
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'center',
              flexShrink: 0,
              padding: '2px',
            }}
          >
            <img src={value} alt="Preview" style={{ maxWidth: '100%', maxHeight: '100%', objectFit: 'contain' }} />
          </div>
        )}
        <input
          type="text"
          value={value || ''}
          onChange={(e) => onChange(e.target.value)}
          placeholder={placeholder}
          style={{
            flex: 1,
            minWidth: '220px',
            padding: '10px 14px',
            borderRadius: '8px',
            border: '1px solid #D1D6E0',
            fontSize: '13px',
            color: '#171151',
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
            padding: '10px 16px',
            backgroundColor: '#F1F5F9',
            color: '#0E63FF',
            border: '1px solid #CBD5E1',
            borderRadius: '8px',
            fontSize: '13px',
            fontWeight: '700',
            cursor: uploading ? 'not-allowed' : 'pointer',
            transition: 'all 0.2s',
          }}
          onMouseEnter={(e) => (e.currentTarget.style.backgroundColor = '#E2E8F0')}
          onMouseLeave={(e) => (e.currentTarget.style.backgroundColor = '#F1F5F9')}
        >
          <Upload size={15} />
          <span>{uploading ? 'Uploading...' : 'Browse File'}</span>
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

export default function AdminSettingsPage() {
  const [activeTab, setActiveTab] = useState('identity');
  const [config, setConfig] = useState(fallbackSiteConfig);
  const [saving, setSaving] = useState(false);
  const [status, setStatus] = useState({ type: '', msg: '' });

  useEffect(() => {
    fetch('/api/admin/site-config')
      .then((res) => res.json())
      .then((data) => {
        if (data.data) {
          setConfig(data.data);
        }
      })
      .catch((err) => console.error(err));
  }, []);

  const handleGeneralChange = (field, val) => {
    setConfig((prev) => ({
      ...prev,
      generalSettings: {
        ...(prev.generalSettings || fallbackSiteConfig.generalSettings),
        [field]: val,
      },
    }));
  };

  const handleSeoChange = (field, val) => {
    setConfig((prev) => ({
      ...prev,
      seoSettings: {
        ...(prev.seoSettings || fallbackSiteConfig.seoSettings),
        [field]: val,
      },
    }));
  };

  const handleContactChange = (field, val) => {
    setConfig((prev) => ({
      ...prev,
      contact: {
        ...prev.contact,
        [field]: val,
      },
    }));
  };

  const handleFooterChange = (field, val) => {
    setConfig((prev) => ({
      ...prev,
      footer: {
        ...prev.footer,
        [field]: val,
      },
    }));
  };

  const handleChatChange = (field, val) => {
    setConfig((prev) => ({
      ...prev,
      chatWidget: {
        ...(prev.chatWidget || fallbackSiteConfig.chatWidget || {}),
        [field]: val,
      },
    }));
  };

  const handleWhatsappChange = (field, val) => {
    setConfig((prev) => ({
      ...prev,
      whatsappWidget: {
        ...(prev.whatsappWidget || fallbackSiteConfig.whatsappWidget || {}),
        [field]: val,
      },
    }));
  };

  const handleSave = async (e) => {
    if (e) e.preventDefault();
    setSaving(true);
    setStatus({ type: '', msg: '' });

    try {
      const res = await fetch('/api/admin/site-config', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(config),
      });
      const data = await res.json();
      if (res.ok) {
        setStatus({ type: 'success', msg: '✓ Site & SEO settings saved successfully to live website and database!' });
      } else {
        setStatus({ type: 'error', msg: data.message || 'Error saving settings' });
      }
    } catch (err) {
      setStatus({ type: 'error', msg: 'Network error saving settings.' });
    } finally {
      setSaving(false);
      setTimeout(() => setStatus({ type: '', msg: '' }), 5000);
    }
  };

  const general = config.generalSettings || fallbackSiteConfig.generalSettings || {};
  const seo = config.seoSettings || fallbackSiteConfig.seoSettings || {};
  const contact = config.contact || {};
  const footer = config.footer || fallbackSiteConfig.footer || {};
  const chat = config.chatWidget || fallbackSiteConfig.chatWidget || {};
  const whatsapp = config.whatsappWidget || fallbackSiteConfig.whatsappWidget || {};

  const tabs = [
    { id: 'identity', label: '1. Site Identity & Logos', icon: Globe },
    { id: 'seo', label: '2. SEO & Meta Tags', icon: Search },
    { id: 'admin', label: '3. Admin Portal Branding', icon: Shield },
    { id: 'contact', label: '4. Organization & Contact', icon: Phone },
    { id: 'chat', label: '5. Live Chat & WhatsApp', icon: MessageSquare },
  ];

  return (
    <div style={{ maxWidth: '1200px', margin: '0 auto', paddingBottom: '60px' }}>
      {/* Top Header Bar */}
      <div
        style={{
          display: 'flex',
          justifyContent: 'space-between',
          alignItems: 'center',
          flexWrap: 'wrap',
          gap: '15px',
          marginBottom: '24px',
          paddingBottom: '16px',
          borderBottom: '1px solid #ECEEF3',
        }}
      >
        <div>
          <h1 style={{ fontSize: '24px', fontWeight: '800', color: '#171151', margin: '0 0 6px' }}>
            Site Identity, SEO & Brand Settings
          </h1>
          <p style={{ fontSize: '14px', color: '#64748B', margin: 0 }}>
            Manage website title, logos, favicon, Google SEO meta tags, social share previews, and admin portal branding.
          </p>
        </div>

        <button
          type="button"
          onClick={handleSave}
          disabled={saving}
          style={{
            display: 'inline-flex',
            alignItems: 'center',
            gap: '8px',
            padding: '12px 26px',
            backgroundColor: '#0E63FF',
            color: '#ffffff',
            borderRadius: '8px',
            border: 'none',
            fontWeight: '700',
            fontSize: '14px',
            cursor: saving ? 'not-allowed' : 'pointer',
            boxShadow: '0 4px 14px rgba(14, 99, 255, 0.28)',
            transition: 'all 0.2s',
          }}
        >
          <Save size={18} />
          <span>{saving ? 'Saving to Database...' : 'Save All Settings'}</span>
        </button>
      </div>

      {/* Success / Error Notification */}
      {status.msg && (
        <div
          style={{
            padding: '14px 20px',
            borderRadius: '10px',
            marginBottom: '20px',
            backgroundColor: status.type === 'success' ? '#E7FAF6' : '#FEEAF1',
            color: status.type === 'success' ? '#0b9748' : '#F72A75',
            fontWeight: '700',
            fontSize: '14px',
            border: `1.5px solid ${status.type === 'success' ? '#A3EAD8' : '#FDCAD9'}`,
            display: 'flex',
            alignItems: 'center',
            gap: '10px',
            boxShadow: '0 4px 12px rgba(0,0,0,0.05)',
            animation: 'fadeIn 0.25s ease',
          }}
        >
          {status.type === 'success' ? <CheckCircle2 size={20} /> : <AlertTriangle size={20} />}
          <span>{status.msg}</span>
        </div>
      )}

      {/* Navigation Tabs Bar */}
      <div
        style={{
          display: 'flex',
          gap: '8px',
          overflowX: 'auto',
          paddingBottom: '12px',
          marginBottom: '24px',
          borderBottom: '1px solid #ECEEF3',
        }}
      >
        {tabs.map((tab) => {
          const Icon = tab.icon;
          const isActive = activeTab === tab.id;
          return (
            <button
              key={tab.id}
              onClick={() => setActiveTab(tab.id)}
              style={{
                display: 'inline-flex',
                alignItems: 'center',
                gap: '8px',
                padding: '10px 18px',
                borderRadius: '8px',
                border: 'none',
                fontSize: '13px',
                fontWeight: '700',
                cursor: 'pointer',
                backgroundColor: isActive ? '#0E63FF' : '#ffffff',
                color: isActive ? '#ffffff' : '#64748B',
                boxShadow: isActive ? '0 4px 12px rgba(14, 99, 255, 0.25)' : 'none',
                borderBottom: isActive ? 'none' : '1px solid #ECEEF3',
                transition: 'all 0.2s',
                whiteSpace: 'nowrap',
              }}
            >
              <Icon size={16} />
              <span>{tab.label}</span>
            </button>
          );
        })}
      </div>

      {/* ── TAB 1: SITE IDENTITY & LOGOS ── */}
      {activeTab === 'identity' && (
        <div style={{ display: 'grid', gap: '20px' }}>
          <div
            style={{
              backgroundColor: '#ffffff',
              borderRadius: '12px',
              padding: '28px',
              border: '1px solid #ECEEF3',
              boxShadow: '0 4px 12px rgba(0,0,0,0.02)',
            }}
          >
            <h3 style={{ fontSize: '16px', fontWeight: '800', color: '#171151', marginBottom: '20px', display: 'flex', alignItems: 'center', gap: '8px' }}>
              <Globe size={18} color="#0E63FF" /> Website Name & Brand Taglines
            </h3>

            <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(280px, 1fr))', gap: '20px' }}>
              <div>
                <label style={{ display: 'block', fontSize: '13px', fontWeight: '700', color: '#171151', marginBottom: '6px' }}>
                  Official Organization / Site Name
                </label>
                <input
                  type="text"
                  value={general.siteName || ''}
                  onChange={(e) => handleGeneralChange('siteName', e.target.value)}
                  placeholder="e.g. INNOTECH MEDICAL PVT LTD"
                  style={{
                    width: '100%',
                    padding: '11px 14px',
                    borderRadius: '8px',
                    border: '1px solid #D1D6E0',
                    fontSize: '14px',
                    boxSizing: 'border-box',
                  }}
                />
              </div>

              <div>
                <label style={{ display: 'block', fontSize: '13px', fontWeight: '700', color: '#171151', marginBottom: '6px' }}>
                  Primary Slogan / Tagline
                </label>
                <input
                  type="text"
                  value={general.siteTagline || ''}
                  onChange={(e) => handleGeneralChange('siteTagline', e.target.value)}
                  placeholder="e.g. Innovating Healthcare with Advance Technologies"
                  style={{
                    width: '100%',
                    padding: '11px 14px',
                    borderRadius: '8px',
                    border: '1px solid #D1D6E0',
                    fontSize: '14px',
                    boxSizing: 'border-box',
                  }}
                />
              </div>
            </div>
          </div>

          <div
            style={{
              backgroundColor: '#ffffff',
              borderRadius: '12px',
              padding: '28px',
              border: '1px solid #ECEEF3',
              boxShadow: '0 4px 12px rgba(0,0,0,0.02)',
            }}
          >
            <h3 style={{ fontSize: '16px', fontWeight: '800', color: '#171151', marginBottom: '20px' }}>
              🖼️ Website Logos & Browser Favicon
            </h3>

            <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(320px, 1fr))', gap: '24px' }}>
              {/* Main Light Background Logo */}
              <div style={{ backgroundColor: '#F8FAFC', padding: '20px', borderRadius: '10px', border: '1px solid #E2E8F0' }}>
                <FileUploadField
                  label="1. Main Website Logo (Light Backgrounds)"
                  value={general.mainLogo || ''}
                  onChange={(url) => handleGeneralChange('mainLogo', url)}
                  placeholder="/assets/img/logo/logo.png"
                  helperText="Recommended: Transparent PNG or SVG (height: 50px - 70px)"
                />
                {general.mainLogo && (
                  <div style={{ marginTop: '10px', padding: '16px', backgroundColor: '#ffffff', borderRadius: '8px', border: '1px dashed #CBD5E1', textAlign: 'center' }}>
                    <span style={{ fontSize: '11px', color: '#94A3B8', display: 'block', marginBottom: '8px' }}>Light Background Preview:</span>
                    <img src={general.mainLogo} alt="Main Logo" style={{ maxHeight: '55px', maxWidth: '100%', objectFit: 'contain' }} />
                  </div>
                )}
              </div>

              {/* White/Dark Background Logo */}
              <div style={{ backgroundColor: '#F8FAFC', padding: '20px', borderRadius: '10px', border: '1px solid #E2E8F0' }}>
                <FileUploadField
                  label="2. White / Inverted Logo (Dark Backgrounds & Footer)"
                  value={general.whiteLogo || ''}
                  onChange={(url) => handleGeneralChange('whiteLogo', url)}
                  placeholder="/assets/img/logo/white-logo.png"
                  helperText="Recommended: White or light-colored PNG/SVG for dark footer"
                />
                {general.whiteLogo && (
                  <div style={{ marginTop: '10px', padding: '16px', backgroundColor: '#171151', borderRadius: '8px', textAlign: 'center' }}>
                    <span style={{ fontSize: '11px', color: '#94A3B8', display: 'block', marginBottom: '8px' }}>Dark Background Preview:</span>
                    <img src={general.whiteLogo} alt="White Logo" style={{ maxHeight: '55px', maxWidth: '100%', objectFit: 'contain' }} />
                  </div>
                )}
              </div>

              {/* Browser Favicon */}
              <div style={{ backgroundColor: '#F8FAFC', padding: '20px', borderRadius: '10px', border: '1px solid #E2E8F0' }}>
                <FileUploadField
                  label="3. Browser Tab Favicon Icon"
                  value={general.favicon || ''}
                  onChange={(url) => handleGeneralChange('favicon', url)}
                  placeholder="/assets/img/logo/favicon.png"
                  helperText="Recommended: 32x32px or 64x64px square PNG/ICO"
                />
                {general.favicon && (
                  <div style={{ marginTop: '10px', padding: '14px', backgroundColor: '#ffffff', borderRadius: '8px', border: '1px solid #E2E8F0', display: 'flex', alignItems: 'center', gap: '10px' }}>
                    <img src={general.favicon} alt="Favicon" style={{ width: '28px', height: '28px', objectFit: 'contain' }} />
                    <span style={{ fontSize: '12px', fontWeight: '600', color: '#171151' }}>Browser Tab Preview: {general.siteName || 'Innotech'}</span>
                  </div>
                )}
              </div>
            </div>
          </div>
        </div>
      )}

      {/* ── TAB 2: SEO & META TAGS ── */}
      {activeTab === 'seo' && (
        <div style={{ display: 'grid', gap: '20px' }}>
          {/* Live Google Search Snippet Preview */}
          <div
            style={{
              backgroundColor: '#ffffff',
              borderRadius: '12px',
              padding: '24px',
              border: '1.5px solid #0E63FF',
              boxShadow: '0 8px 24px rgba(14, 99, 255, 0.06)',
            }}
          >
            <div style={{ display: 'flex', alignItems: 'center', gap: '8px', marginBottom: '14px' }}>
              <Sparkles size={18} color="#0E63FF" />
              <h3 style={{ fontSize: '15px', fontWeight: '800', color: '#0E63FF', margin: 0, textTransform: 'uppercase', letterSpacing: '0.5px' }}>
                Live Google Search Engine Result Preview (SERP Mockup)
              </h3>
            </div>

            <div
              style={{
                backgroundColor: '#F8FAFC',
                padding: '20px',
                borderRadius: '10px',
                border: '1px solid #E2E8F0',
                fontFamily: 'Arial, sans-serif',
              }}
            >
              <div style={{ fontSize: '12px', color: '#202124', marginBottom: '4px' }}>
                {seo.canonicalUrl || 'https://innotechmedical.org'}
              </div>
              <h4 style={{ fontSize: '18px', color: '#1a0dab', margin: '0 0 4px', fontWeight: '400', cursor: 'pointer' }}>
                {seo.metaTitle || `${general.siteName || 'INNOTECH MEDICAL PVT LTD'} - ${general.siteTagline || 'Innovating Healthcare'}`}
              </h4>
              <p style={{ fontSize: '13px', color: '#4d5156', margin: 0, lineHeight: '1.5' }}>
                {seo.metaDescription ||
                  'Innotech Medical Pvt Ltd is a leading distributor of top-quality medical equipment, diagnostic systems, and specialized clinical solutions across Pakistan.'}
              </p>
            </div>
          </div>

          {/* SEO Input Fields Card */}
          <div
            style={{
              backgroundColor: '#ffffff',
              borderRadius: '12px',
              padding: '28px',
              border: '1px solid #ECEEF3',
              boxShadow: '0 4px 12px rgba(0,0,0,0.02)',
            }}
          >
            <h3 style={{ fontSize: '16px', fontWeight: '800', color: '#171151', marginBottom: '20px', display: 'flex', alignItems: 'center', gap: '8px' }}>
              <Search size={18} color="#0E63FF" /> Meta Titles, Description & Keywords
            </h3>

            <div style={{ display: 'grid', gap: '18px' }}>
              {/* Meta Title */}
              <div>
                <div style={{ display: 'flex', justifyContent: 'space-between', marginBottom: '6px' }}>
                  <label style={{ fontSize: '13px', fontWeight: '700', color: '#171151' }}>
                    Default SEO Meta Title
                  </label>
                  <span style={{ fontSize: '12px', color: (seo.metaTitle || '').length > 65 ? '#F72A75' : '#64748B' }}>
                    {(seo.metaTitle || '').length} / 60 characters
                  </span>
                </div>
                <input
                  type="text"
                  value={seo.metaTitle || ''}
                  onChange={(e) => handleSeoChange('metaTitle', e.target.value)}
                  placeholder="e.g. INNOTECH MEDICAL PVT LTD - Innovating Healthcare with Advance Technologies"
                  style={{
                    width: '100%',
                    padding: '11px 14px',
                    borderRadius: '8px',
                    border: '1px solid #D1D6E0',
                    fontSize: '14px',
                    boxSizing: 'border-box',
                  }}
                />
              </div>

              {/* Meta Description */}
              <div>
                <div style={{ display: 'flex', justifyContent: 'space-between', marginBottom: '6px' }}>
                  <label style={{ fontSize: '13px', fontWeight: '700', color: '#171151' }}>
                    Default SEO Meta Description
                  </label>
                  <span style={{ fontSize: '12px', color: (seo.metaDescription || '').length > 165 ? '#F72A75' : '#64748B' }}>
                    {(seo.metaDescription || '').length} / 160 characters
                  </span>
                </div>
                <textarea
                  rows={3}
                  value={seo.metaDescription || ''}
                  onChange={(e) => handleSeoChange('metaDescription', e.target.value)}
                  placeholder="Summarize your healthcare products, clinical solutions, and services for search engine crawlers..."
                  style={{
                    width: '100%',
                    padding: '11px 14px',
                    borderRadius: '8px',
                    border: '1px solid #D1D6E0',
                    fontSize: '14px',
                    boxSizing: 'border-box',
                  }}
                />
              </div>

              {/* Meta Keywords */}
              <div>
                <label style={{ display: 'block', fontSize: '13px', fontWeight: '700', color: '#171151', marginBottom: '6px' }}>
                  Meta Keywords (Comma-Separated)
                </label>
                <input
                  type="text"
                  value={seo.metaKeywords || ''}
                  onChange={(e) => handleSeoChange('metaKeywords', e.target.value)}
                  placeholder="medical equipment, diagnostic systems, laboratory supplies, hospital devices, Innotech Medical, Pakistan"
                  style={{
                    width: '100%',
                    padding: '11px 14px',
                    borderRadius: '8px',
                    border: '1px solid #D1D6E0',
                    fontSize: '14px',
                    boxSizing: 'border-box',
                  }}
                />
                <span style={{ fontSize: '11px', color: '#64748B', display: 'block', marginTop: '4px' }}>
                  💡 Separate each keyword or search phrase with a comma.
                </span>
              </div>

              {/* Social Share (OpenGraph) Image */}
              <div style={{ backgroundColor: '#F8FAFC', padding: '20px', borderRadius: '10px', border: '1px solid #E2E8F0', marginTop: '10px' }}>
                <div style={{ display: 'flex', alignItems: 'center', gap: '8px', marginBottom: '10px' }}>
                  <Share2 size={16} color="#0E63FF" />
                  <h4 style={{ fontSize: '14px', fontWeight: '700', color: '#171151', margin: 0 }}>
                    Social Share Image (OpenGraph & Twitter Card Image)
                  </h4>
                </div>
                <FileUploadField
                  value={seo.metaImage || ''}
                  onChange={(url) => handleSeoChange('metaImage', url)}
                  placeholder="/assets/img/banner/breadcrumb-01.jpg"
                  helperText="Image displayed when your link is shared on WhatsApp, Facebook, LinkedIn, or Twitter (Recommended: 1200 x 630px)"
                />
              </div>

              {/* Additional Technical SEO Details */}
              <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(240px, 1fr))', gap: '16px' }}>
                <div>
                  <label style={{ display: 'block', fontSize: '13px', fontWeight: '700', color: '#171151', marginBottom: '6px' }}>
                    Author / Organization Name
                  </label>
                  <input
                    type="text"
                    value={seo.metaAuthor || 'Innotech Medical Pvt Ltd'}
                    onChange={(e) => handleSeoChange('metaAuthor', e.target.value)}
                    style={{
                      width: '100%',
                      padding: '10px 14px',
                      borderRadius: '8px',
                      border: '1px solid #D1D6E0',
                      fontSize: '13px',
                      boxSizing: 'border-box',
                    }}
                  />
                </div>

                <div>
                  <label style={{ display: 'block', fontSize: '13px', fontWeight: '700', color: '#171151', marginBottom: '6px' }}>
                    Production / Canonical Site URL
                  </label>
                  <input
                    type="text"
                    value={seo.canonicalUrl || 'https://innotechmedical.org'}
                    onChange={(e) => handleSeoChange('canonicalUrl', e.target.value)}
                    placeholder="https://innotechmedical.org"
                    style={{
                      width: '100%',
                      padding: '10px 14px',
                      borderRadius: '8px',
                      border: '1px solid #D1D6E0',
                      fontSize: '13px',
                      boxSizing: 'border-box',
                    }}
                  />
                </div>

                <div>
                  <label style={{ display: 'block', fontSize: '13px', fontWeight: '700', color: '#171151', marginBottom: '6px' }}>
                    Search Robots Indexing Directive
                  </label>
                  <select
                    value={seo.robotsIndex || 'index, follow'}
                    onChange={(e) => handleSeoChange('robotsIndex', e.target.value)}
                    style={{
                      width: '100%',
                      padding: '10px 14px',
                      borderRadius: '8px',
                      border: '1px solid #D1D6E0',
                      fontSize: '13px',
                      boxSizing: 'border-box',
                      backgroundColor: '#ffffff',
                    }}
                  >
                    <option value="index, follow">✅ Index, Follow (Recommended for Live Production)</option>
                    <option value="noindex, nofollow">🚫 NoIndex, NoFollow (Hide from Search Engines)</option>
                  </select>
                </div>
              </div>
            </div>
          </div>
        </div>
      )}

      {/* ── TAB 3: ADMIN PORTAL BRANDING ── */}
      {activeTab === 'admin' && (
        <div
          style={{
            backgroundColor: '#ffffff',
            borderRadius: '12px',
            padding: '28px',
            border: '1px solid #ECEEF3',
            boxShadow: '0 4px 12px rgba(0,0,0,0.02)',
          }}
        >
          <h3 style={{ fontSize: '16px', fontWeight: '800', color: '#171151', marginBottom: '20px', display: 'flex', alignItems: 'center', gap: '8px' }}>
            <Shield size={18} color="#0E63FF" /> Admin Portal Branding & Navigation Logo
          </h3>

          <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(280px, 1fr))', gap: '24px' }}>
            <div>
              <label style={{ display: 'block', fontSize: '13px', fontWeight: '700', color: '#171151', marginBottom: '6px' }}>
                Admin Portal Header Name
              </label>
              <input
                type="text"
                value={general.adminName || 'INNOTECH Admin Portal'}
                onChange={(e) => handleGeneralChange('adminName', e.target.value)}
                placeholder="e.g. INNOTECH Admin Portal"
                style={{
                  width: '100%',
                  padding: '11px 14px',
                  borderRadius: '8px',
                  border: '1px solid #D1D6E0',
                  fontSize: '14px',
                  boxSizing: 'border-box',
                }}
              />
            </div>

            <div>
              <FileUploadField
                label="Admin Sidebar Logo"
                value={general.adminLogo || ''}
                onChange={(url) => handleGeneralChange('adminLogo', url)}
                placeholder="/assets/img/logo/white-logo.png"
                helperText="White/light logo displayed on the dark admin sidebar"
              />
            </div>
          </div>
        </div>
      )}

      {/* ── TAB 4: ORGANIZATION & CONTACT ── */}
      {activeTab === 'contact' && (
        <div
          style={{
            backgroundColor: '#ffffff',
            borderRadius: '12px',
            padding: '28px',
            border: '1px solid #ECEEF3',
            boxShadow: '0 4px 12px rgba(0,0,0,0.02)',
          }}
        >
          <h3 style={{ fontSize: '16px', fontWeight: '800', color: '#171151', marginBottom: '20px', display: 'flex', alignItems: 'center', gap: '8px' }}>
            <Phone size={18} color="#0E63FF" /> Organization Contact, Help Desks & Address
          </h3>

          <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(280px, 1fr))', gap: '20px' }}>
            <div>
              <label style={{ display: 'block', fontSize: '13px', fontWeight: '700', color: '#171151', marginBottom: '6px' }}>
                Primary Help Desk Phone
              </label>
              <input
                type="text"
                value={contact.helpDeskPhone || footer.phone || '+92 331 6699992'}
                onChange={(e) => {
                  handleContactChange('helpDeskPhone', e.target.value);
                  handleFooterChange('phone', e.target.value);
                }}
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

            <div>
              <label style={{ display: 'block', fontSize: '13px', fontWeight: '700', color: '#171151', marginBottom: '6px' }}>
                24/7 Emergency Support Phone
              </label>
              <input
                type="text"
                value={contact.emergencyPhone || '+92 331 6699992'}
                onChange={(e) => handleContactChange('emergencyPhone', e.target.value)}
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

            <div>
              <label style={{ display: 'block', fontSize: '13px', fontWeight: '700', color: '#171151', marginBottom: '6px' }}>
                Official Email
              </label>
              <input
                type="email"
                value={contact.email || footer.email || 'info@innotecmedical.org'}
                onChange={(e) => {
                  handleContactChange('email', e.target.value);
                  handleFooterChange('email', e.target.value);
                }}
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

            <div>
              <label style={{ display: 'block', fontSize: '13px', fontWeight: '700', color: '#171151', marginBottom: '6px' }}>
                Working Hours
              </label>
              <input
                type="text"
                value={contact.officeHours || footer.officeHours || '10AM - 6PM'}
                onChange={(e) => {
                  handleContactChange('officeHours', e.target.value);
                  handleFooterChange('officeHours', e.target.value);
                }}
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

            <div style={{ gridColumn: '1 / -1' }}>
              <label style={{ display: 'block', fontSize: '13px', fontWeight: '700', color: '#171151', marginBottom: '6px' }}>
                Head Office Address
              </label>
              <textarea
                rows={2}
                value={contact.address || footer.address || ''}
                onChange={(e) => {
                  handleContactChange('address', e.target.value);
                  handleFooterChange('address', e.target.value);
                }}
                style={{
                  width: '100%',
                  padding: '10px 14px',
                  borderRadius: '8px',
                  border: '1px solid #D1D6E0',
                  fontSize: '14px',
                  boxSizing: 'border-box',
                }}
              ></textarea>
            </div>
          </div>
        </div>
      )}

      {/* ── TAB 5: LIVE CHAT & WHATSAPP SUPPORT ── */}
      {activeTab === 'chat' && (
        <div style={{ display: 'grid', gap: '24px' }}>
          {/* Card 1: Website Live Chat Support */}
          <div
            style={{
              backgroundColor: '#ffffff',
              borderRadius: '12px',
              padding: '28px',
              border: '1px solid #ECEEF3',
              boxShadow: '0 4px 12px rgba(0,0,0,0.02)',
            }}
          >
            <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '20px', flexWrap: 'wrap', gap: '12px' }}>
              <div style={{ display: 'flex', alignItems: 'center', gap: '10px' }}>
                <div style={{ width: '38px', height: '38px', borderRadius: '8px', backgroundColor: '#EEF2FF', display: 'flex', alignItems: 'center', justifyContent: 'center', color: '#0E63FF' }}>
                  <MessageSquare size={20} />
                </div>
                <div>
                  <h3 style={{ fontSize: '16px', fontWeight: '800', color: '#171151', margin: 0 }}>
                    Website Live Chat Support Widget
                  </h3>
                  <p style={{ fontSize: '12px', color: '#64748B', margin: 0 }}>
                    Enable floating bottom-right chat widget with customer intake form (Name, Phone, City) & instant auto-reply.
                  </p>
                </div>
              </div>

              {/* Toggle Switch */}
              <label style={{ display: 'inline-flex', alignItems: 'center', cursor: 'pointer', gap: '10px' }}>
                <span style={{ fontSize: '13px', fontWeight: '700', color: chat.enabled !== false ? '#0E63FF' : '#94A3B8' }}>
                  {chat.enabled !== false ? 'Live Chat Enabled (Visible)' : 'Live Chat Disabled (Hidden)'}
                </span>
                <input
                  type="checkbox"
                  checked={chat.enabled !== false}
                  onChange={(e) => handleChatChange('enabled', e.target.checked)}
                  style={{ display: 'none' }}
                />
                <div
                  style={{
                    width: '48px',
                    height: '26px',
                    backgroundColor: chat.enabled !== false ? '#0E63FF' : '#CBD5E1',
                    borderRadius: '13px',
                    position: 'relative',
                    transition: 'background-color 0.2s',
                  }}
                >
                  <div
                    style={{
                      width: '20px',
                      height: '20px',
                      backgroundColor: '#ffffff',
                      borderRadius: '50%',
                      position: 'absolute',
                      top: '3px',
                      left: chat.enabled !== false ? '25px' : '3px',
                      transition: 'left 0.2s',
                      boxShadow: '0 2px 4px rgba(0,0,0,0.2)',
                    }}
                  />
                </div>
              </label>
            </div>

            <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(280px, 1fr))', gap: '20px' }}>
              <div>
                <label style={{ display: 'block', fontSize: '13px', fontWeight: '700', color: '#171151', marginBottom: '6px' }}>
                  Chat Header Title
                </label>
                <input
                  type="text"
                  value={chat.title || ''}
                  onChange={(e) => handleChatChange('title', e.target.value)}
                  placeholder="e.g. Innotech Live Support"
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

              <div>
                <label style={{ display: 'block', fontSize: '13px', fontWeight: '700', color: '#171151', marginBottom: '6px' }}>
                  Agent Subtitle Status
                </label>
                <input
                  type="text"
                  value={chat.subtitle || ''}
                  onChange={(e) => handleChatChange('subtitle', e.target.value)}
                  placeholder="e.g. Typically replies within minutes"
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

              <div>
                <label style={{ display: 'block', fontSize: '13px', fontWeight: '700', color: '#171151', marginBottom: '6px' }}>
                  Support Team / Agent Name
                </label>
                <input
                  type="text"
                  value={chat.agentName || ''}
                  onChange={(e) => handleChatChange('agentName', e.target.value)}
                  placeholder="e.g. Innotech Support Team"
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

              <div style={{ gridColumn: '1 / -1' }}>
                <label style={{ display: 'block', fontSize: '13px', fontWeight: '700', color: '#171151', marginBottom: '6px' }}>
                  1. Automated Welcome Greeting Message (Sent immediately upon starting chat)
                </label>
                <textarea
                  rows={3}
                  value={chat.welcomeMessage || ''}
                  onChange={(e) => handleChatChange('welcomeMessage', e.target.value)}
                  placeholder="e.g. Welcome to Innotech Medical Support! How can we assist you with our medical equipment and services today?"
                  style={{
                    width: '100%',
                    padding: '11px 14px',
                    borderRadius: '8px',
                    border: '1px solid #D1D6E0',
                    fontSize: '14px',
                    boxSizing: 'border-box',
                  }}
                />
                <span style={{ fontSize: '11px', color: '#64748B', display: 'block', marginTop: '4px' }}>
                  💡 This initial greeting welcomes the user as soon as they submit their Name, Phone, and City.
                </span>
              </div>

              {/* Auto Reply Setting Box */}
              <div
                style={{
                  gridColumn: '1 / -1',
                  marginTop: '10px',
                  padding: '18px 20px',
                  backgroundColor: '#F8FAFC',
                  borderRadius: '10px',
                  border: '1.5px solid #E2E8F0',
                }}
              >
                <div
                  style={{
                    display: 'flex',
                    justifyContent: 'space-between',
                    alignItems: 'center',
                    marginBottom: '12px',
                    flexWrap: 'wrap',
                    gap: '10px',
                  }}
                >
                  <div style={{ display: 'flex', alignItems: 'center', gap: '8px' }}>
                    <div
                      style={{
                        width: '28px',
                        height: '28px',
                        borderRadius: '6px',
                        backgroundColor: '#EEF2FF',
                        display: 'flex',
                        alignItems: 'center',
                        justifyContent: 'center',
                        color: '#0E63FF',
                      }}
                    >
                      <Sparkles size={16} />
                    </div>
                    <div>
                      <div style={{ fontSize: '14px', fontWeight: '800', color: '#171151' }}>
                        2. Automated Auto-Reply Message (Sent when visitor sends a message)
                      </div>
                      <div style={{ fontSize: '12px', color: '#64748B' }}>
                        Trigger an automated confirmation message right after the visitor types & sends their inquiry.
                      </div>
                    </div>
                  </div>

                  {/* Toggle Switch */}
                  <label style={{ display: 'inline-flex', alignItems: 'center', cursor: 'pointer', gap: '8px' }}>
                    <span
                      style={{
                        fontSize: '12px',
                        fontWeight: '700',
                        color: chat.autoReplyEnabled !== false ? '#0E63FF' : '#94A3B8',
                      }}
                    >
                      {chat.autoReplyEnabled !== false ? 'Auto-Reply Active' : 'Auto-Reply Disabled'}
                    </span>
                    <input
                      type="checkbox"
                      checked={chat.autoReplyEnabled !== false}
                      onChange={(e) => handleChatChange('autoReplyEnabled', e.target.checked)}
                      style={{ display: 'none' }}
                    />
                    <div
                      style={{
                        width: '42px',
                        height: '22px',
                        backgroundColor: chat.autoReplyEnabled !== false ? '#0E63FF' : '#CBD5E1',
                        borderRadius: '11px',
                        position: 'relative',
                        transition: 'background-color 0.2s',
                      }}
                    >
                      <div
                        style={{
                          width: '16px',
                          height: '16px',
                          backgroundColor: '#ffffff',
                          borderRadius: '50%',
                          position: 'absolute',
                          top: '3px',
                          left: chat.autoReplyEnabled !== false ? '23px' : '3px',
                          transition: 'left 0.2s',
                          boxShadow: '0 1px 3px rgba(0,0,0,0.2)',
                        }}
                      />
                    </div>
                  </label>
                </div>

                <textarea
                  rows={3}
                  value={chat.autoReplyMessage || ''}
                  onChange={(e) => handleChatChange('autoReplyMessage', e.target.value)}
                  disabled={chat.autoReplyEnabled === false}
                  placeholder="e.g. Thank you for reaching out! Our support team has received your message and will respond to you shortly. For urgent assistance, please call +92 331 6699992."
                  style={{
                    width: '100%',
                    padding: '11px 14px',
                    borderRadius: '8px',
                    border: '1px solid #D1D6E0',
                    fontSize: '14px',
                    boxSizing: 'border-box',
                    backgroundColor: chat.autoReplyEnabled === false ? '#F1F5F9' : '#ffffff',
                    color: chat.autoReplyEnabled === false ? '#94A3B8' : '#171151',
                  }}
                />
                <span style={{ fontSize: '11px', color: '#64748B', display: 'block', marginTop: '6px' }}>
                  💬 <strong>How it works:</strong> Step 1: User enters chat and receives the Welcome Message. Step 2: User types their first message and sends it. Step 3: This Auto-Reply message is instantly sent to reassure them that their inquiry is being processed by the team.
                </span>
              </div>
            </div>
          </div>

          {/* Card 2: Floating WhatsApp Chat Widget */}
          <div
            style={{
              backgroundColor: '#ffffff',
              borderRadius: '12px',
              padding: '28px',
              border: '1px solid #ECEEF3',
              boxShadow: '0 4px 12px rgba(0,0,0,0.02)',
            }}
          >
            <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '20px', flexWrap: 'wrap', gap: '12px' }}>
              <div style={{ display: 'flex', alignItems: 'center', gap: '10px' }}>
                <div style={{ width: '38px', height: '38px', borderRadius: '8px', backgroundColor: '#DCFCE7', display: 'flex', alignItems: 'center', justifyContent: 'center', color: '#16A34A' }}>
                  <MessageCircle size={20} />
                </div>
                <div>
                  <h3 style={{ fontSize: '16px', fontWeight: '800', color: '#171151', margin: 0 }}>
                    Floating WhatsApp Support Button
                  </h3>
                  <p style={{ fontSize: '12px', color: '#64748B', margin: 0 }}>
                    Enable floating green WhatsApp icon that redirects visitors directly to your WhatsApp with a customized pre-filled message.
                  </p>
                </div>
              </div>

              {/* Toggle Switch */}
              <label style={{ display: 'inline-flex', alignItems: 'center', cursor: 'pointer', gap: '10px' }}>
                <span style={{ fontSize: '13px', fontWeight: '700', color: whatsapp.enabled !== false ? '#16A34A' : '#94A3B8' }}>
                  {whatsapp.enabled !== false ? 'WhatsApp Button Enabled (Visible)' : 'WhatsApp Button Disabled (Hidden)'}
                </span>
                <input
                  type="checkbox"
                  checked={whatsapp.enabled !== false}
                  onChange={(e) => handleWhatsappChange('enabled', e.target.checked)}
                  style={{ display: 'none' }}
                />
                <div
                  style={{
                    width: '48px',
                    height: '26px',
                    backgroundColor: whatsapp.enabled !== false ? '#16A34A' : '#CBD5E1',
                    borderRadius: '13px',
                    position: 'relative',
                    transition: 'background-color 0.2s',
                  }}
                >
                  <div
                    style={{
                      width: '20px',
                      height: '20px',
                      backgroundColor: '#ffffff',
                      borderRadius: '50%',
                      position: 'absolute',
                      top: '3px',
                      left: whatsapp.enabled !== false ? '25px' : '3px',
                      transition: 'left 0.2s',
                      boxShadow: '0 2px 4px rgba(0,0,0,0.2)',
                    }}
                  />
                </div>
              </label>
            </div>

            <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(280px, 1fr))', gap: '20px' }}>
              <div>
                <label style={{ display: 'block', fontSize: '13px', fontWeight: '700', color: '#171151', marginBottom: '6px' }}>
                  WhatsApp Phone Number (with Country Code)
                </label>
                <input
                  type="text"
                  value={whatsapp.phoneNumber || ''}
                  onChange={(e) => handleWhatsappChange('phoneNumber', e.target.value)}
                  placeholder="e.g. +92 331 6699992 or 923316699992"
                  style={{
                    width: '100%',
                    padding: '10px 14px',
                    borderRadius: '8px',
                    border: '1px solid #D1D6E0',
                    fontSize: '14px',
                    boxSizing: 'border-box',
                  }}
                />
                <span style={{ fontSize: '11px', color: '#64748B', display: 'block', marginTop: '4px' }}>
                  Include international dialing code (e.g. +92 for Pakistan).
                </span>
              </div>

              <div>
                <label style={{ display: 'block', fontSize: '13px', fontWeight: '700', color: '#171151', marginBottom: '6px' }}>
                  Button Hover Tooltip Text
                </label>
                <input
                  type="text"
                  value={whatsapp.tooltipText || ''}
                  onChange={(e) => handleWhatsappChange('tooltipText', e.target.value)}
                  placeholder="e.g. Chat with us on WhatsApp"
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

              <div style={{ gridColumn: '1 / -1' }}>
                <label style={{ display: 'block', fontSize: '13px', fontWeight: '700', color: '#171151', marginBottom: '6px' }}>
                  Default Pre-filled WhatsApp Message
                </label>
                <textarea
                  rows={2}
                  value={whatsapp.defaultMessage || ''}
                  onChange={(e) => handleWhatsappChange('defaultMessage', e.target.value)}
                  placeholder="e.g. Hello Innotech Medical, I would like to inquire about your medical equipment and solutions."
                  style={{
                    width: '100%',
                    padding: '11px 14px',
                    borderRadius: '8px',
                    border: '1px solid #D1D6E0',
                    fontSize: '14px',
                    boxSizing: 'border-box',
                  }}
                />
                <span style={{ fontSize: '11px', color: '#64748B', display: 'block', marginTop: '4px' }}>
                  When user clicks the WhatsApp button, this message will already be typed in their chat box.
                </span>
              </div>
            </div>
          </div>
        </div>
      )}

      {/* Bottom Save Action Bar */}
      <div
        style={{
          marginTop: '28px',
          display: 'flex',
          justifyContent: 'flex-end',
        }}
      >
        <button
          type="button"
          onClick={handleSave}
          disabled={saving}
          style={{
            display: 'inline-flex',
            alignItems: 'center',
            gap: '8px',
            padding: '13px 32px',
            backgroundColor: '#0E63FF',
            color: '#ffffff',
            borderRadius: '8px',
            border: 'none',
            fontWeight: '700',
            fontSize: '14px',
            cursor: saving ? 'not-allowed' : 'pointer',
            boxShadow: '0 6px 18px rgba(14, 99, 255, 0.28)',
            transition: 'all 0.2s',
          }}
        >
          <Save size={18} />
          <span>{saving ? 'Saving to Database...' : 'Save All Settings'}</span>
        </button>
      </div>
    </div>
  );
}
