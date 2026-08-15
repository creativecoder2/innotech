'use client';

import React, { useState, useEffect } from 'react';
import {
  Save,
  Plus,
  Trash2,
  CheckCircle2,
  AlertTriangle,
  FileText,
  ShieldCheck,
  Building2,
  Mail,
  Phone,
  Calendar,
  Layers,
} from 'lucide-react';
import { fallbackTermsPage, fallbackPrivacyPage } from '@/lib/data';

export default function AdminLegalPageManager() {
  const [activeTab, setActiveTab] = useState('terms');
  const [termsData, setTermsData] = useState(fallbackTermsPage);
  const [privacyData, setPrivacyData] = useState(fallbackPrivacyPage);
  const [saving, setSaving] = useState(false);
  const [saveStatus, setSaveStatus] = useState({ type: '', msg: '' });

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
      const res = await fetch('/api/admin/legal');
      const data = await res.json();
      if (data.data) {
        if (data.data.termsPage) setTermsData(data.data.termsPage);
        if (data.data.privacyPage) setPrivacyData(data.data.privacyPage);
      }
    } catch (e) {
      console.error('Error fetching legal policies data:', e);
    }
  };

  const syncData = async (updatedTerms, updatedPrivacy) => {
    try {
      await fetch('/api/admin/legal', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          termsPage: updatedTerms || termsData,
          privacyPage: updatedPrivacy || privacyData,
        }),
      });
    } catch (err) {
      console.error('Error syncing legal data:', err);
    }
  };

  // Section handling for Terms
  const handleTermsChange = (field, value) => {
    const updated = { ...termsData, [field]: value };
    setTermsData(updated);
    syncData(updated, privacyData);
  };

  const handleTermsSectionChange = (index, field, value) => {
    const updatedSections = [...(termsData.sections || [])];
    updatedSections[index] = { ...updatedSections[index], [field]: value };
    const updated = { ...termsData, sections: updatedSections };
    setTermsData(updated);
    syncData(updated, privacyData);
  };

  const addTermsSection = () => {
    const newSec = {
      id: `sec-${Date.now()}`,
      title: `${(termsData.sections || []).length + 1}. New Policy Section`,
      content: 'Enter the policy section terms and details here...',
    };
    const updated = { ...termsData, sections: [...(termsData.sections || []), newSec] };
    setTermsData(updated);
    syncData(updated, privacyData);
    showToast('New Terms & Conditions section added!');
  };

  // Section handling for Privacy
  const handlePrivacyChange = (field, value) => {
    const updated = { ...privacyData, [field]: value };
    setPrivacyData(updated);
    syncData(termsData, updated);
  };

  const handlePrivacySectionChange = (index, field, value) => {
    const updatedSections = [...(privacyData.sections || [])];
    updatedSections[index] = { ...updatedSections[index], [field]: value };
    const updated = { ...privacyData, sections: updatedSections };
    setPrivacyData(updated);
    syncData(termsData, updated);
  };

  const addPrivacySection = () => {
    const newSec = {
      id: `sec-${Date.now()}`,
      title: `${(privacyData.sections || []).length + 1}. New Privacy Policy Section`,
      content: 'Enter the privacy data protection clause here...',
    };
    const updated = { ...privacyData, sections: [...(privacyData.sections || []), newSec] };
    setPrivacyData(updated);
    syncData(termsData, updated);
    showToast('New Privacy Policy section added!');
  };

  const handleConfirmDelete = () => {
    const { type, id, title } = deleteModal;
    if (type === 'termsSection') {
      const updated = {
        ...termsData,
        sections: (termsData.sections || []).filter((_, i) => i !== id),
      };
      setTermsData(updated);
      syncData(updated, privacyData);
      showToast(`Section "${title}" deleted!`);
    } else if (type === 'privacySection') {
      const updated = {
        ...privacyData,
        sections: (privacyData.sections || []).filter((_, i) => i !== id),
      };
      setPrivacyData(updated);
      syncData(termsData, updated);
      showToast(`Section "${title}" deleted!`);
    }
    setDeleteModal({ open: false, type: '', id: null, title: '' });
  };

  const handleSaveAll = async (e) => {
    if (e) e.preventDefault();
    setSaving(true);
    try {
      const res = await fetch('/api/admin/legal', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          termsPage: termsData,
          privacyPage: privacyData,
        }),
      });
      const data = await res.json();
      if (res.ok) {
        showToast('Legal policy pages published and live!');
      } else {
        showToast(data.message || 'Error saving changes', 'error');
      }
    } catch (err) {
      showToast('Network error saving configuration.', 'error');
    } finally {
      setSaving(false);
    }
  };

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
            <span>Legal & Policy Pages Manager</span>
            <span style={{ fontSize: '12px', fontWeight: '600', backgroundColor: '#E7FAF6', color: '#0B9748', padding: '4px 10px', borderRadius: '20px' }}>
              Dynamic & Live
            </span>
          </h1>
          <p style={{ fontSize: '14px', color: '#6b6b6b', margin: 0 }}>
            Manage the content, policy sections, and registry details for /terms and /privacy pages.
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
          <span>{saving ? 'Saving...' : 'Save & Publish Policies'}</span>
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
      <div style={{ display: 'flex', gap: '10px', marginBottom: '20px' }}>
        <button
          onClick={() => setActiveTab('terms')}
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
            backgroundColor: activeTab === 'terms' ? '#0E63FF' : '#ffffff',
            color: activeTab === 'terms' ? '#ffffff' : '#475569',
            boxShadow: activeTab === 'terms' ? '0 4px 12px rgba(14, 99, 255, 0.2)' : 'none',
            transition: 'all 0.2s ease',
          }}
        >
          <FileText size={18} />
          <span>1. Terms & Conditions (/terms)</span>
        </button>

        <button
          onClick={() => setActiveTab('privacy')}
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
            backgroundColor: activeTab === 'privacy' ? '#0B9748' : '#ffffff',
            color: activeTab === 'privacy' ? '#ffffff' : '#475569',
            boxShadow: activeTab === 'privacy' ? '0 4px 12px rgba(11, 151, 72, 0.2)' : 'none',
            transition: 'all 0.2s ease',
          }}
        >
          <ShieldCheck size={18} />
          <span>2. Privacy Policy (/privacy)</span>
        </button>
      </div>

      {/* TAB 1: TERMS AND CONDITIONS */}
      {activeTab === 'terms' && (
        <div style={{ display: 'flex', flexDirection: 'column', gap: '20px' }}>
          {/* General Information */}
          <div style={{ backgroundColor: '#ffffff', borderRadius: '12px', padding: '28px', border: '1px solid #ECEEF3' }}>
            <h3 style={{ fontSize: '18px', fontWeight: '700', color: '#171151', margin: '0 0 20px' }}>
              General Page Settings & Header
            </h3>
            <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(280px, 1fr))', gap: '20px' }}>
              <div>
                <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>Page Title</label>
                <input
                  type="text"
                  value={termsData.banner?.title || ''}
                  onChange={(e) => {
                    const updatedBanner = { ...termsData.banner, title: e.target.value };
                    handleTermsChange('banner', updatedBanner);
                  }}
                  style={{ width: '100%', padding: '10px 14px', borderRadius: '8px', border: '1px solid #D1D6E0', fontSize: '14px', boxSizing: 'border-box' }}
                />
              </div>
              <div>
                <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>Subtitle</label>
                <input
                  type="text"
                  value={termsData.banner?.subTitle || ''}
                  onChange={(e) => {
                    const updatedBanner = { ...termsData.banner, subTitle: e.target.value };
                    handleTermsChange('banner', updatedBanner);
                  }}
                  style={{ width: '100%', padding: '10px 14px', borderRadius: '8px', border: '1px solid #D1D6E0', fontSize: '14px', boxSizing: 'border-box' }}
                />
              </div>
              <div>
                <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>Last Updated Date</label>
                <input
                  type="text"
                  value={termsData.lastUpdated || ''}
                  onChange={(e) => handleTermsChange('lastUpdated', e.target.value)}
                  placeholder="e.g. August 15, 2026"
                  style={{ width: '100%', padding: '10px 14px', borderRadius: '8px', border: '1px solid #D1D6E0', fontSize: '14px', boxSizing: 'border-box' }}
                />
              </div>
              <div style={{ gridColumn: '1 / -1' }}>
                <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>Policy Introduction / Preamble</label>
                <textarea
                  rows={3}
                  value={termsData.introduction || ''}
                  onChange={(e) => handleTermsChange('introduction', e.target.value)}
                  style={{ width: '100%', padding: '10px 14px', borderRadius: '8px', border: '1px solid #D1D6E0', fontSize: '14px', boxSizing: 'border-box' }}
                ></textarea>
              </div>
            </div>
          </div>

          {/* Policy Sections */}
          <div style={{ backgroundColor: '#ffffff', borderRadius: '12px', padding: '28px', border: '1px solid #ECEEF3' }}>
            <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '20px' }}>
              <h3 style={{ fontSize: '18px', fontWeight: '700', color: '#171151', margin: 0 }}>
                Policy Sections ({ (termsData.sections || []).length })
              </h3>
              <button
                type="button"
                onClick={addTermsSection}
                style={{
                  display: 'inline-flex',
                  alignItems: 'center',
                  gap: '6px',
                  padding: '8px 16px',
                  backgroundColor: '#EFF6FF',
                  color: '#0E63FF',
                  border: '1px solid #BFDBFE',
                  borderRadius: '6px',
                  fontSize: '13px',
                  fontWeight: '600',
                  cursor: 'pointer',
                }}
              >
                <Plus size={16} />
                <span>Add Section</span>
              </button>
            </div>

            <div style={{ display: 'flex', flexDirection: 'column', gap: '16px' }}>
              {(termsData.sections || []).map((sec, idx) => (
                <div
                  key={sec.id || idx}
                  style={{
                    border: '1px solid #E2E8F0',
                    borderRadius: '10px',
                    padding: '20px',
                    backgroundColor: '#F8FAFC',
                  }}
                >
                  <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '10px' }}>
                    <input
                      type="text"
                      value={sec.title || ''}
                      onChange={(e) => handleTermsSectionChange(idx, 'title', e.target.value)}
                      placeholder="Section Title"
                      style={{
                        flex: 1,
                        maxWidth: '500px',
                        padding: '8px 12px',
                        borderRadius: '6px',
                        border: '1px solid #CBD5E1',
                        fontSize: '14px',
                        fontWeight: '700',
                        color: '#171151',
                      }}
                    />
                    <button
                      type="button"
                      onClick={() => setDeleteModal({ open: true, type: 'termsSection', id: idx, title: sec.title })}
                      style={{
                        padding: '8px 12px',
                        backgroundColor: '#FEEAF1',
                        color: '#F72A75',
                        border: 'none',
                        borderRadius: '6px',
                        cursor: 'pointer',
                        display: 'inline-flex',
                        alignItems: 'center',
                        gap: '4px',
                        fontSize: '12px',
                        fontWeight: '600',
                      }}
                    >
                      <Trash2 size={14} />
                      <span>Delete</span>
                    </button>
                  </div>
                  <textarea
                    rows={4}
                    value={sec.content || ''}
                    onChange={(e) => handleTermsSectionChange(idx, 'content', e.target.value)}
                    placeholder="Section Content"
                    style={{
                      width: '100%',
                      padding: '10px 12px',
                      borderRadius: '6px',
                      border: '1px solid #CBD5E1',
                      fontSize: '13px',
                      lineHeight: '1.6',
                      boxSizing: 'border-box',
                    }}
                  ></textarea>
                </div>
              ))}
            </div>
          </div>

          {/* Contact Details */}
          <div style={{ backgroundColor: '#ffffff', borderRadius: '12px', padding: '28px', border: '1px solid #ECEEF3' }}>
            <h3 style={{ fontSize: '18px', fontWeight: '700', color: '#171151', margin: '0 0 20px' }}>
              Legal Registry Contact Information
            </h3>
            <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(280px, 1fr))', gap: '20px' }}>
              <div>
                <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>Official Email</label>
                <input
                  type="text"
                  value={termsData.contactEmail || ''}
                  onChange={(e) => handleTermsChange('contactEmail', e.target.value)}
                  style={{ width: '100%', padding: '10px 14px', borderRadius: '8px', border: '1px solid #D1D6E0', fontSize: '14px', boxSizing: 'border-box' }}
                />
              </div>
              <div>
                <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>Direct Phone</label>
                <input
                  type="text"
                  value={termsData.contactPhone || ''}
                  onChange={(e) => handleTermsChange('contactPhone', e.target.value)}
                  style={{ width: '100%', padding: '10px 14px', borderRadius: '8px', border: '1px solid #D1D6E0', fontSize: '14px', boxSizing: 'border-box' }}
                />
              </div>
              <div style={{ gridColumn: '1 / -1' }}>
                <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>Head Office / Registry Address</label>
                <input
                  type="text"
                  value={termsData.contactAddress || ''}
                  onChange={(e) => handleTermsChange('contactAddress', e.target.value)}
                  style={{ width: '100%', padding: '10px 14px', borderRadius: '8px', border: '1px solid #D1D6E0', fontSize: '14px', boxSizing: 'border-box' }}
                />
              </div>
            </div>
          </div>
        </div>
      )}

      {/* TAB 2: PRIVACY POLICY */}
      {activeTab === 'privacy' && (
        <div style={{ display: 'flex', flexDirection: 'column', gap: '20px' }}>
          {/* General Information */}
          <div style={{ backgroundColor: '#ffffff', borderRadius: '12px', padding: '28px', border: '1px solid #ECEEF3' }}>
            <h3 style={{ fontSize: '18px', fontWeight: '700', color: '#171151', margin: '0 0 20px' }}>
              Privacy Policy General Settings
            </h3>
            <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(280px, 1fr))', gap: '20px' }}>
              <div>
                <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>Page Title</label>
                <input
                  type="text"
                  value={privacyData.banner?.title || ''}
                  onChange={(e) => {
                    const updatedBanner = { ...privacyData.banner, title: e.target.value };
                    handlePrivacyChange('banner', updatedBanner);
                  }}
                  style={{ width: '100%', padding: '10px 14px', borderRadius: '8px', border: '1px solid #D1D6E0', fontSize: '14px', boxSizing: 'border-box' }}
                />
              </div>
              <div>
                <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>Subtitle</label>
                <input
                  type="text"
                  value={privacyData.banner?.subTitle || ''}
                  onChange={(e) => {
                    const updatedBanner = { ...privacyData.banner, subTitle: e.target.value };
                    handlePrivacyChange('banner', updatedBanner);
                  }}
                  style={{ width: '100%', padding: '10px 14px', borderRadius: '8px', border: '1px solid #D1D6E0', fontSize: '14px', boxSizing: 'border-box' }}
                />
              </div>
              <div>
                <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>Last Updated Date</label>
                <input
                  type="text"
                  value={privacyData.lastUpdated || ''}
                  onChange={(e) => handlePrivacyChange('lastUpdated', e.target.value)}
                  placeholder="e.g. August 15, 2026"
                  style={{ width: '100%', padding: '10px 14px', borderRadius: '8px', border: '1px solid #D1D6E0', fontSize: '14px', boxSizing: 'border-box' }}
                />
              </div>
              <div style={{ gridColumn: '1 / -1' }}>
                <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>Privacy Introduction Statement</label>
                <textarea
                  rows={3}
                  value={privacyData.introduction || ''}
                  onChange={(e) => handlePrivacyChange('introduction', e.target.value)}
                  style={{ width: '100%', padding: '10px 14px', borderRadius: '8px', border: '1px solid #D1D6E0', fontSize: '14px', boxSizing: 'border-box' }}
                ></textarea>
              </div>
            </div>
          </div>

          {/* Privacy Policy Sections */}
          <div style={{ backgroundColor: '#ffffff', borderRadius: '12px', padding: '28px', border: '1px solid #ECEEF3' }}>
            <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '20px' }}>
              <h3 style={{ fontSize: '18px', fontWeight: '700', color: '#171151', margin: 0 }}>
                Privacy Sections ({ (privacyData.sections || []).length })
              </h3>
              <button
                type="button"
                onClick={addPrivacySection}
                style={{
                  display: 'inline-flex',
                  alignItems: 'center',
                  gap: '6px',
                  padding: '8px 16px',
                  backgroundColor: '#E7FAF6',
                  color: '#0B9748',
                  border: '1px solid #A3EAD8',
                  borderRadius: '6px',
                  fontSize: '13px',
                  fontWeight: '600',
                  cursor: 'pointer',
                }}
              >
                <Plus size={16} />
                <span>Add Section</span>
              </button>
            </div>

            <div style={{ display: 'flex', flexDirection: 'column', gap: '16px' }}>
              {(privacyData.sections || []).map((sec, idx) => (
                <div
                  key={sec.id || idx}
                  style={{
                    border: '1px solid #E2E8F0',
                    borderRadius: '10px',
                    padding: '20px',
                    backgroundColor: '#F8FAFC',
                  }}
                >
                  <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '10px' }}>
                    <input
                      type="text"
                      value={sec.title || ''}
                      onChange={(e) => handlePrivacySectionChange(idx, 'title', e.target.value)}
                      placeholder="Section Title"
                      style={{
                        flex: 1,
                        maxWidth: '500px',
                        padding: '8px 12px',
                        borderRadius: '6px',
                        border: '1px solid #CBD5E1',
                        fontSize: '14px',
                        fontWeight: '700',
                        color: '#171151',
                      }}
                    />
                    <button
                      type="button"
                      onClick={() => setDeleteModal({ open: true, type: 'privacySection', id: idx, title: sec.title })}
                      style={{
                        padding: '8px 12px',
                        backgroundColor: '#FEEAF1',
                        color: '#F72A75',
                        border: 'none',
                        borderRadius: '6px',
                        cursor: 'pointer',
                        display: 'inline-flex',
                        alignItems: 'center',
                        gap: '4px',
                        fontSize: '12px',
                        fontWeight: '600',
                      }}
                    >
                      <Trash2 size={14} />
                      <span>Delete</span>
                    </button>
                  </div>
                  <textarea
                    rows={4}
                    value={sec.content || ''}
                    onChange={(e) => handlePrivacySectionChange(idx, 'content', e.target.value)}
                    placeholder="Section Content"
                    style={{
                      width: '100%',
                      padding: '10px 12px',
                      borderRadius: '6px',
                      border: '1px solid #CBD5E1',
                      fontSize: '13px',
                      lineHeight: '1.6',
                      boxSizing: 'border-box',
                    }}
                  ></textarea>
                </div>
              ))}
            </div>
          </div>

          {/* Contact Details */}
          <div style={{ backgroundColor: '#ffffff', borderRadius: '12px', padding: '28px', border: '1px solid #ECEEF3' }}>
            <h3 style={{ fontSize: '18px', fontWeight: '700', color: '#171151', margin: '0 0 20px' }}>
              Data Protection Officer Contact Information
            </h3>
            <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(280px, 1fr))', gap: '20px' }}>
              <div>
                <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>Compliance Email</label>
                <input
                  type="text"
                  value={privacyData.contactEmail || ''}
                  onChange={(e) => handlePrivacyChange('contactEmail', e.target.value)}
                  style={{ width: '100%', padding: '10px 14px', borderRadius: '8px', border: '1px solid #D1D6E0', fontSize: '14px', boxSizing: 'border-box' }}
                />
              </div>
              <div>
                <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>Direct Phone</label>
                <input
                  type="text"
                  value={privacyData.contactPhone || ''}
                  onChange={(e) => handlePrivacyChange('contactPhone', e.target.value)}
                  style={{ width: '100%', padding: '10px 14px', borderRadius: '8px', border: '1px solid #D1D6E0', fontSize: '14px', boxSizing: 'border-box' }}
                />
              </div>
              <div style={{ gridColumn: '1 / -1' }}>
                <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>Office Address</label>
                <input
                  type="text"
                  value={privacyData.contactAddress || ''}
                  onChange={(e) => handlePrivacyChange('contactAddress', e.target.value)}
                  style={{ width: '100%', padding: '10px 14px', borderRadius: '8px', border: '1px solid #D1D6E0', fontSize: '14px', boxSizing: 'border-box' }}
                />
              </div>
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
            zIndex: 999999,
            backgroundColor: 'rgba(15, 23, 42, 0.65)',
            backdropFilter: 'blur(4px)',
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'center',
            padding: '20px',
          }}
          onClick={() => setDeleteModal({ open: false, type: '', id: null, title: '' })}
        >
          <div
            style={{
              backgroundColor: '#ffffff',
              borderRadius: '16px',
              maxWidth: '440px',
              width: '100%',
              padding: '28px',
              textAlign: 'center',
              boxShadow: '0 25px 50px -12px rgba(0, 0, 0, 0.25)',
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
              Confirm Section Deletion
            </h3>
            <p style={{ fontSize: '14px', color: '#64748B', lineHeight: '1.5', margin: '0 0 24px' }}>
              Are you sure you want to delete <strong style={{ color: '#171151' }}>&ldquo;{deleteModal.title}&rdquo;</strong>?
            </p>
            <div style={{ display: 'flex', gap: '12px', justifyContent: 'center' }}>
              <button
                type="button"
                onClick={() => setDeleteModal({ open: false, type: '', id: null, title: '' })}
                style={{
                  flex: 1,
                  padding: '11px 18px',
                  backgroundColor: '#F1F5F9',
                  color: '#475569',
                  border: 'none',
                  borderRadius: '8px',
                  fontWeight: '600',
                  fontSize: '14px',
                  cursor: 'pointer',
                }}
              >
                Cancel
              </button>
              <button
                type="button"
                onClick={handleConfirmDelete}
                style={{
                  flex: 1,
                  padding: '11px 18px',
                  backgroundColor: '#F72A75',
                  color: '#ffffff',
                  border: 'none',
                  borderRadius: '8px',
                  fontWeight: '600',
                  fontSize: '14px',
                  cursor: 'pointer',
                }}
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
