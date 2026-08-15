'use client';

import React, { useState, useEffect, useRef } from 'react';
import {
  Save,
  CheckCircle2,
  PhoneCall,
  Mail,
  MapPin,
  Clock,
  LayoutTemplate,
  Eye,
  EyeOff,
  AlertTriangle,
  UploadCloud,
  Globe,
  MessageSquare,
  Search,
  Filter,
  Trash2,
  Send,
  RefreshCw,
  User,
  Inbox,
  Calendar,
  ChevronDown,
  ChevronUp,
  ChevronLeft,
  ChevronRight,
} from 'lucide-react';
import { fallbackContactPage } from '@/lib/data';

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
    try {
      const formData = new FormData();
      formData.append('file', file);

      const res = await fetch('/api/admin/upload', {
        method: 'POST',
        body: formData,
      });

      const data = await res.json();
      if (res.ok && data.url) {
        onChange(data.url);
      } else {
        alert(data.message || 'File upload failed');
      }
    } catch (err) {
      alert('Error uploading file');
    } finally {
      setUploading(false);
    }
  };

  return (
    <div style={{ marginBottom: '16px' }}>
      <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>
        {label}
      </label>
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

export default function AdminContactPageManager() {
  const [activeTab, setActiveTab] = useState('banner');
  const [contactData, setContactData] = useState(fallbackContactPage);
  const [saving, setSaving] = useState(false);
  const [saveStatus, setSaveStatus] = useState({ type: '', msg: '' });

  // Inquiries State
  const [inquiries, setInquiries] = useState([]);
  const [loadingInquiries, setLoadingInquiries] = useState(false);
  const [inquiryCounts, setInquiryCounts] = useState({ total: 0, unread: 0, replied: 0, archived: 0 });
  const [inquiryFilter, setInquiryFilter] = useState('all');
  const [inquirySearch, setInquirySearch] = useState('');
  const [inquiryPage, setInquiryPage] = useState(1);
  const inquiryPageSize = 8;
  const [expandedInquiryId, setExpandedInquiryId] = useState(null);
  const [replyModal, setReplyModal] = useState({ open: false, inquiry: null });
  const [replySubject, setReplySubject] = useState('');
  const [replyMessage, setReplyMessage] = useState('');
  const [sendingReply, setSendingReply] = useState(false);

  useEffect(() => {
    fetchData();
    fetchInquiries();
  }, []);

  const showToast = (msg, type = 'success') => {
    setSaveStatus({ type, msg });
    setTimeout(() => {
      setSaveStatus((prev) => (prev.msg === msg ? { type: '', msg: '' } : prev));
    }, 4500);
  };

  const fetchData = async () => {
    try {
      const res = await fetch('/api/admin/contact');
      const data = await res.json();
      if (data.data) {
        setContactData(data.data);
      }
    } catch (e) {
      console.error('Error fetching contact data:', e);
    }
  };

  const fetchInquiries = async () => {
    setLoadingInquiries(true);
    try {
      const res = await fetch('/api/admin/inquiries');
      const data = await res.json();
      if (data.success && Array.isArray(data.data)) {
        setInquiries(data.data);
        if (data.counts) {
          setInquiryCounts(data.counts);
        }
      }
    } catch (e) {
      console.error('Error fetching inquiries:', e);
    } finally {
      setLoadingInquiries(false);
    }
  };

  const handleUpdateInquiryStatus = async (id, status, showToastMsg = true) => {
    try {
      const res = await fetch('/api/admin/inquiries', {
        method: 'PATCH',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ ids: [id], status }),
      });
      const data = await res.json();
      if (res.ok && data.success) {
        if (showToastMsg) showToast(`Inquiry marked as ${status}!`);
        if (typeof window !== 'undefined') {
          window.dispatchEvent(new Event('innotech_notifications_updated'));
        }
        fetchInquiries();
      } else {
        if (showToastMsg) showToast(data.message || 'Error updating status', 'error');
      }
    } catch (err) {
      if (showToastMsg) showToast('Network error updating status', 'error');
    }
  };

  const handleDeleteInquiry = async (id) => {
    if (!confirm('Are you sure you want to delete this inquiry record?')) return;
    try {
      const res = await fetch('/api/admin/inquiries', {
        method: 'DELETE',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ ids: [id] }),
      });
      const data = await res.json();
      if (res.ok && data.success) {
        showToast('Inquiry deleted successfully!');
        if (typeof window !== 'undefined') {
          window.dispatchEvent(new Event('innotech_notifications_updated'));
        }
        fetchInquiries();
      } else {
        showToast(data.message || 'Error deleting inquiry', 'error');
      }
    } catch (err) {
      showToast('Network error deleting inquiry', 'error');
    }
  };

  const openReplyModal = (inquiry) => {
    setReplyModal({ open: true, inquiry });
    setReplySubject(`Re: ${inquiry.subject || 'Innotech Medical Equipment Inquiry'}`);
    setReplyMessage(
      `Dear ${inquiry.name},\n\nThank you for reaching out to Innotech Medical Pvt Ltd regarding your inquiry.\n\n`
    );
  };

  const handleSendReply = async (e) => {
    e.preventDefault();
    if (!replySubject.trim() || !replyMessage.trim() || !replyModal.inquiry) return;
    setSendingReply(true);

    try {
      const res = await fetch('/api/admin/inquiries/reply', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          inquiryIds: [replyModal.inquiry._id],
          subject: replySubject,
          replyMessage: replyMessage,
          sentBy: 'Innotech Engineering Desk',
        }),
      });

      const data = await res.json();
      if (res.ok && data.success) {
        showToast('Reply sent and logged successfully!');
        setReplyModal({ open: false, inquiry: null });
        fetchInquiries();
      } else {
        showToast(data.message || 'Error sending reply', 'error');
      }
    } catch (err) {
      showToast('Network error sending reply', 'error');
    } finally {
      setSendingReply(false);
    }
  };

  const syncContactData = async (updatedData) => {
    try {
      await fetch('/api/admin/contact', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(updatedData || contactData),
      });
    } catch (err) {
      console.error('Error syncing contact page data:', err);
    }
  };

  const handleChange = (section, field, value) => {
    setContactData((prev) => {
      const updated = {
        ...prev,
        [section]: {
          ...prev[section],
          [field]: value,
        },
      };
      syncContactData(updated);
      return updated;
    });
  };

  const toggleSection = (section) => {
    const newEnabled = contactData[section]?.enabled === false ? true : false;
    const updated = {
      ...contactData,
      [section]: {
        ...contactData[section],
        enabled: newEnabled,
      },
    };
    setContactData(updated);
    syncContactData(updated);
    showToast(`${section} is now ${newEnabled ? 'Enabled & Live' : 'Disabled & Hidden'} on Contact page!`);
  };

  const handleSaveAll = async (e) => {
    if (e) e.preventDefault();
    setSaving(true);
    try {
      const res = await fetch('/api/admin/contact', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(contactData),
      });
      const data = await res.json();
      if (res.ok) {
        showToast('Contact Us page settings saved and published live!');
      } else {
        showToast(data.message || 'Error saving changes', 'error');
      }
    } catch (err) {
      showToast('Network error saving configuration.', 'error');
    } finally {
      setSaving(false);
    }
  };

  const filteredInquiries = inquiries.filter((item) => {
    const matchFilter = inquiryFilter === 'all' ? true : item.status === inquiryFilter;
    const matchSearch =
      inquirySearch === '' ||
      (item.name && item.name.toLowerCase().includes(inquirySearch.toLowerCase())) ||
      (item.email && item.email.toLowerCase().includes(inquirySearch.toLowerCase())) ||
      (item.phone && item.phone.toLowerCase().includes(inquirySearch.toLowerCase())) ||
      (item.subject && item.subject.toLowerCase().includes(inquirySearch.toLowerCase())) ||
      (item.message && item.message.toLowerCase().includes(inquirySearch.toLowerCase()));
    return matchFilter && matchSearch;
  });

  const tabs = [
    { id: 'banner', label: '1. Top Banner', icon: LayoutTemplate, sectionKey: 'banner' },
    { id: 'info', label: '2. Info Cards (Phone/Email/Address)', icon: MapPin, sectionKey: 'info' },
    { id: 'form', label: '3. Inquiry Form', icon: Mail, sectionKey: 'formSection' },
    { id: 'map', label: '4. Google Maps Embed', icon: Globe, sectionKey: 'map' },
    {
      id: 'inquiries',
      label: `5. Received Inquiries (${inquiryCounts.unread || 0} New)`,
      icon: MessageSquare,
      badge: inquiryCounts.unread || 0,
      isSpecial: true,
    },
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
            <span>Contact Us Page Manager</span>
            <span style={{ fontSize: '12px', fontWeight: '600', backgroundColor: '#EFF6FF', color: '#0E63FF', padding: '4px 10px', borderRadius: '20px' }}>
              Dynamic & Live
            </span>
          </h1>
          <p style={{ fontSize: '14px', color: '#6b6b6b', margin: 0 }}>
            Configure phone numbers, emails, physical location address, office hours, and Google Map for /contact.
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
          <span>{saving ? 'Saving...' : 'Save & Publish Contact Page'}</span>
        </button>
      </div>

      {/* Prominent Toast Notification Banner */}
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
          const isEnabled = contactData[t.sectionKey]?.enabled !== false;
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
              Contact Page Top Breadcrumb Banner
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
                backgroundColor: contactData.banner?.enabled !== false ? '#E7FAF6' : '#FEEAF1',
                color: contactData.banner?.enabled !== false ? '#0b9748' : '#F72A75',
              }}
            >
              {contactData.banner?.enabled !== false ? <Eye size={14} /> : <EyeOff size={14} />}
              <span>{contactData.banner?.enabled !== false ? 'Banner Visible' : 'Banner Disabled'}</span>
            </button>
          </div>

          <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(280px, 1fr))', gap: '20px' }}>
            <div>
              <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>Main Title</label>
              <input
                type="text"
                value={contactData.banner?.title || ''}
                onChange={(e) => handleChange('banner', 'title', e.target.value)}
                style={{ width: '100%', padding: '10px 14px', borderRadius: '8px', border: '1px solid #D1D6E0', fontSize: '14px', boxSizing: 'border-box' }}
              />
            </div>
            <div>
              <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>Subtitle / Tagline</label>
              <input
                type="text"
                value={contactData.banner?.subTitle || ''}
                onChange={(e) => handleChange('banner', 'subTitle', e.target.value)}
                style={{ width: '100%', padding: '10px 14px', borderRadius: '8px', border: '1px solid #D1D6E0', fontSize: '14px', boxSizing: 'border-box' }}
              />
            </div>
            <div style={{ gridColumn: '1 / -1' }}>
              <FileUploadField
                label="Banner Background Image"
                value={contactData.banner?.bgImage || ''}
                onChange={(url) => handleChange('banner', 'bgImage', url)}
                placeholder="/assets/img/banner/breadcrumb-01.jpg"
              />
            </div>
          </div>
        </div>
      )}

      {/* TAB 2: INFO CARDS */}
      {activeTab === 'info' && (
        <div style={{ backgroundColor: '#ffffff', borderRadius: '12px', padding: '28px', border: '1px solid #ECEEF3' }}>
          <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '20px' }}>
            <h3 style={{ fontSize: '18px', fontWeight: '700', color: '#171151', margin: 0 }}>
              Official Contact Info Cards
            </h3>
            <button
              onClick={() => toggleSection('info')}
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
                backgroundColor: contactData.info?.enabled !== false ? '#E7FAF6' : '#FEEAF1',
                color: contactData.info?.enabled !== false ? '#0b9748' : '#F72A75',
              }}
            >
              {contactData.info?.enabled !== false ? <Eye size={14} /> : <EyeOff size={14} />}
              <span>{contactData.info?.enabled !== false ? 'Cards Visible' : 'Cards Disabled'}</span>
            </button>
          </div>

          <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(280px, 1fr))', gap: '20px' }}>
            {/* Address */}
            <div style={{ border: '1px solid #ECEEF3', padding: '20px', borderRadius: '10px', backgroundColor: '#F8FAFC', gridColumn: '1 / -1' }}>
              <label style={{ display: 'block', fontSize: '14px', fontWeight: '700', color: '#0E63FF', marginBottom: '8px' }}>Head Office Physical Address</label>
              <textarea
                rows={2}
                value={contactData.info?.address || ''}
                onChange={(e) => handleChange('info', 'address', e.target.value)}
                style={{ width: '100%', padding: '10px 14px', borderRadius: '8px', border: '1px solid #CBD5E1', fontSize: '14px', boxSizing: 'border-box' }}
              ></textarea>
            </div>

            {/* Phone */}
            <div style={{ border: '1px solid #ECEEF3', padding: '20px', borderRadius: '10px', backgroundColor: '#F8FAFC' }}>
              <label style={{ display: 'block', fontSize: '14px', fontWeight: '700', color: '#0B9748', marginBottom: '8px' }}>Contact Phone / Helpline</label>
              <input
                type="text"
                value={contactData.info?.phone || ''}
                onChange={(e) => handleChange('info', 'phone', e.target.value)}
                style={{ width: '100%', padding: '10px 14px', borderRadius: '8px', border: '1px solid #CBD5E1', fontSize: '14px', marginBottom: '8px', boxSizing: 'border-box' }}
              />
              <input
                type="text"
                placeholder="Subtitle e.g. Emergency Support Available"
                value={contactData.info?.phoneSub || ''}
                onChange={(e) => handleChange('info', 'phoneSub', e.target.value)}
                style={{ width: '100%', padding: '8px 12px', borderRadius: '6px', border: '1px solid #E2E8F0', fontSize: '12px', boxSizing: 'border-box' }}
              />
            </div>

            {/* Email */}
            <div style={{ border: '1px solid #ECEEF3', padding: '20px', borderRadius: '10px', backgroundColor: '#F8FAFC' }}>
              <label style={{ display: 'block', fontSize: '14px', fontWeight: '700', color: '#F72A75', marginBottom: '8px' }}>Official Email Address</label>
              <input
                type="email"
                value={contactData.info?.email || ''}
                onChange={(e) => handleChange('info', 'email', e.target.value)}
                style={{ width: '100%', padding: '10px 14px', borderRadius: '8px', border: '1px solid #CBD5E1', fontSize: '14px', marginBottom: '8px', boxSizing: 'border-box' }}
              />
              <input
                type="text"
                placeholder="Subtitle e.g. Quick Response within 24h"
                value={contactData.info?.emailSub || ''}
                onChange={(e) => handleChange('info', 'emailSub', e.target.value)}
                style={{ width: '100%', padding: '8px 12px', borderRadius: '6px', border: '1px solid #E2E8F0', fontSize: '12px', boxSizing: 'border-box' }}
              />
            </div>

            {/* Office Hours */}
            <div style={{ border: '1px solid #ECEEF3', padding: '20px', borderRadius: '10px', backgroundColor: '#F8FAFC', gridColumn: '1 / -1' }}>
              <label style={{ display: 'block', fontSize: '14px', fontWeight: '700', color: '#171151', marginBottom: '8px' }}>Working Office Hours</label>
              <input
                type="text"
                value={contactData.info?.officeHours || ''}
                onChange={(e) => handleChange('info', 'officeHours', e.target.value)}
                style={{ width: '100%', padding: '10px 14px', borderRadius: '8px', border: '1px solid #CBD5E1', fontSize: '14px', marginBottom: '8px', boxSizing: 'border-box' }}
              />
              <input
                type="text"
                placeholder="Weekend note e.g. Sunday: Emergency On-Call Support"
                value={contactData.info?.weekendText || ''}
                onChange={(e) => handleChange('info', 'weekendText', e.target.value)}
                style={{ width: '100%', padding: '8px 12px', borderRadius: '6px', border: '1px solid #E2E8F0', fontSize: '12px', boxSizing: 'border-box' }}
              />
            </div>
          </div>
        </div>
      )}

      {/* TAB 3: INQUIRY FORM */}
      {activeTab === 'form' && (
        <div style={{ backgroundColor: '#ffffff', borderRadius: '12px', padding: '28px', border: '1px solid #ECEEF3' }}>
          <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '20px' }}>
            <h3 style={{ fontSize: '18px', fontWeight: '700', color: '#171151', margin: 0 }}>
              Contact & Inquiry Form Settings
            </h3>
            <button
              onClick={() => toggleSection('formSection')}
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
                backgroundColor: contactData.formSection?.enabled !== false ? '#E7FAF6' : '#FEEAF1',
                color: contactData.formSection?.enabled !== false ? '#0b9748' : '#F72A75',
              }}
            >
              {contactData.formSection?.enabled !== false ? <Eye size={14} /> : <EyeOff size={14} />}
              <span>{contactData.formSection?.enabled !== false ? 'Form Visible' : 'Form Disabled'}</span>
            </button>
          </div>

          <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(280px, 1fr))', gap: '20px' }}>
            <div>
              <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>Form Headline Title</label>
              <input
                type="text"
                value={contactData.formSection?.title || ''}
                onChange={(e) => handleChange('formSection', 'title', e.target.value)}
                style={{ width: '100%', padding: '10px 14px', borderRadius: '8px', border: '1px solid #D1D6E0', fontSize: '14px', boxSizing: 'border-box' }}
              />
            </div>
            <div>
              <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>Subtitle</label>
              <input
                type="text"
                value={contactData.formSection?.subTitle || ''}
                onChange={(e) => handleChange('formSection', 'subTitle', e.target.value)}
                style={{ width: '100%', padding: '10px 14px', borderRadius: '8px', border: '1px solid #D1D6E0', fontSize: '14px', boxSizing: 'border-box' }}
              />
            </div>
            <div style={{ gridColumn: '1 / -1' }}>
              <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>Form Instructions / Description</label>
              <textarea
                rows={2}
                value={contactData.formSection?.description || ''}
                onChange={(e) => handleChange('formSection', 'description', e.target.value)}
                style={{ width: '100%', padding: '10px 14px', borderRadius: '8px', border: '1px solid #D1D6E0', fontSize: '14px', boxSizing: 'border-box' }}
              ></textarea>
            </div>
          </div>
        </div>
      )}

      {/* TAB 4: GOOGLE MAPS EMBED */}
      {activeTab === 'map' && (
        <div style={{ backgroundColor: '#ffffff', borderRadius: '12px', padding: '28px', border: '1px solid #ECEEF3' }}>
          <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '20px' }}>
            <h3 style={{ fontSize: '18px', fontWeight: '700', color: '#171151', margin: 0 }}>
              Google Maps Interactive Embed
            </h3>
            <button
              onClick={() => toggleSection('map')}
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
                backgroundColor: contactData.map?.enabled !== false ? '#E7FAF6' : '#FEEAF1',
                color: contactData.map?.enabled !== false ? '#0b9748' : '#F72A75',
              }}
            >
              {contactData.map?.enabled !== false ? <Eye size={14} /> : <EyeOff size={14} />}
              <span>{contactData.map?.enabled !== false ? 'Map Visible' : 'Map Disabled'}</span>
            </button>
          </div>

          <div style={{ marginBottom: '20px' }}>
            <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>
              Google Maps Embed URL (or Search Query)
            </label>
            <input
              type="text"
              value={contactData.map?.mapUrl || ''}
              onChange={(e) => handleChange('map', 'mapUrl', e.target.value)}
              placeholder="https://maps.google.com/maps?q=...&output=embed"
              style={{ width: '100%', padding: '10px 14px', borderRadius: '8px', border: '1px solid #D1D6E0', fontSize: '14px', boxSizing: 'border-box' }}
            />
          </div>

          {contactData.map?.mapUrl && (
            <div style={{ borderRadius: '10px', overflow: 'hidden', height: '350px', border: '1px solid #CBD5E1' }}>
              <iframe
                src={contactData.map.mapUrl}
                width="100%"
                height="100%"
                style={{ border: 0 }}
                loading="lazy"
                title="Google Maps Location Preview"
              ></iframe>
            </div>
          )}
        </div>
      )}

      {/* TAB 5: RECEIVED INQUIRIES */}
      {activeTab === 'inquiries' && (
        <div style={{ backgroundColor: '#ffffff', borderRadius: '12px', padding: '28px', border: '1px solid #ECEEF3' }}>
          {/* Top Bar */}
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
              <h3 style={{ fontSize: '18px', fontWeight: '700', color: '#171151', margin: '0 0 4px', display: 'flex', alignItems: 'center', gap: '8px' }}>
                <Inbox size={20} color="#0E63FF" />
                <span>Received Contact Inquiries & Leads</span>
              </h3>
              <p style={{ fontSize: '13px', color: '#6b6b6b', margin: 0 }}>
                Real-time submissions from the public /contact form. Manage statuses, view customer messages, and dispatch replies.
              </p>
            </div>

            <button
              onClick={fetchInquiries}
              disabled={loadingInquiries}
              style={{
                display: 'inline-flex',
                alignItems: 'center',
                gap: '6px',
                padding: '8px 16px',
                borderRadius: '8px',
                backgroundColor: '#EFF6FF',
                color: '#0E63FF',
                border: '1px solid #BFDBFE',
                fontWeight: '600',
                fontSize: '13px',
                cursor: 'pointer',
              }}
            >
              <RefreshCw size={14} className={loadingInquiries ? 'animate-spin' : ''} />
              <span>{loadingInquiries ? 'Refreshing...' : 'Refresh List'}</span>
            </button>
          </div>

          {/* Metric Filter Tabs & Search Bar */}
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
            {/* Filter Badges */}
            <div style={{ display: 'flex', gap: '8px', flexWrap: 'wrap' }}>
              {[
                { key: 'all', label: 'All Inquiries', count: inquiryCounts.total, color: '#171151', bg: '#F1F5F9' },
                { key: 'unread', label: 'Unread / New', count: inquiryCounts.unread, color: '#F72A75', bg: '#FEEAF1' },
                { key: 'replied', label: 'Replied', count: inquiryCounts.replied, color: '#0B9748', bg: '#E7FAF6' },
                { key: 'archived', label: 'Archived', count: inquiryCounts.archived, color: '#64748B', bg: '#F8FAFC' },
              ].map((f) => {
                const isSelected = inquiryFilter === f.key;
                return (
                  <button
                    key={f.key}
                    onClick={() => setInquiryFilter(f.key)}
                    style={{
                      display: 'inline-flex',
                      alignItems: 'center',
                      gap: '8px',
                      padding: '8px 14px',
                      borderRadius: '20px',
                      border: isSelected ? `2px solid ${f.color}` : '1px solid #E2E8F0',
                      backgroundColor: isSelected ? f.bg : '#ffffff',
                      color: isSelected ? f.color : '#64748B',
                      fontSize: '13px',
                      fontWeight: isSelected ? '700' : '500',
                      cursor: 'pointer',
                      transition: 'all 0.2s',
                    }}
                  >
                    <span>{f.label}</span>
                    <span
                      style={{
                        padding: '2px 8px',
                        borderRadius: '10px',
                        backgroundColor: isSelected ? f.color : '#E2E8F0',
                        color: isSelected ? '#ffffff' : '#475569',
                        fontSize: '11px',
                        fontWeight: '700',
                      }}
                    >
                      {f.count || 0}
                    </span>
                  </button>
                );
              })}
            </div>

            {/* Search Input */}
            <div style={{ position: 'relative', minWidth: '260px', flex: '1', maxWidth: '380px' }}>
              <Search
                size={16}
                style={{ position: 'absolute', left: '12px', top: '50%', transform: 'translateY(-50%)', color: '#94A3B8' }}
              />
              <input
                type="text"
                placeholder="Search by name, email, subject, phone..."
                value={inquirySearch}
                onChange={(e) => setInquirySearch(e.target.value)}
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
          </div>

          {/* Inquiries Listing */}
          {loadingInquiries && inquiries.length === 0 ? (
            <div style={{ padding: '60px 20px', textAlign: 'center', color: '#64748B' }}>
              <RefreshCw size={32} className="animate-spin" style={{ margin: '0 auto 12px', color: '#0E63FF' }} />
              <p style={{ fontWeight: '600', fontSize: '14px' }}>Loading inquiries...</p>
            </div>
          ) : filteredInquiries.length === 0 ? (
            <div
              style={{
                padding: '60px 20px',
                textAlign: 'center',
                backgroundColor: '#F8FAFC',
                borderRadius: '10px',
                border: '1px dashed #CBD5E1',
              }}
            >
              <Inbox size={40} style={{ margin: '0 auto 12px', color: '#94A3B8' }} />
              <h4 style={{ fontSize: '16px', fontWeight: '700', color: '#171151', margin: '0 0 6px' }}>
                No inquiries found
              </h4>
              <p style={{ fontSize: '13px', color: '#64748B', margin: 0 }}>
                {inquirySearch ? 'No submissions match your search query.' : 'There are no inquiries in this category.'}
              </p>
            </div>
          ) : (
            <div style={{ display: 'flex', flexDirection: 'column', gap: '14px' }}>
              {filteredInquiries.slice((inquiryPage - 1) * inquiryPageSize, inquiryPage * inquiryPageSize).map((item) => {
                const isExpanded = expandedInquiryId === item._id;
                const isUnread = item.status === 'unread';

                return (
                  <div
                    key={item._id}
                    style={{
                      borderRadius: '10px',
                      border: isUnread ? '1.5px solid #BFDBFE' : '1px solid #E2E8F0',
                      backgroundColor: isUnread ? '#F8FAFF' : '#ffffff',
                      boxShadow: isUnread ? '0 4px 12px rgba(14, 99, 255, 0.05)' : '0 2px 6px rgba(0,0,0,0.02)',
                      overflow: 'hidden',
                      transition: 'all 0.2s ease',
                    }}
                  >
                    {/* Header Row */}
                    <div
                      style={{
                        padding: '16px 20px',
                        display: 'flex',
                        justifyContent: 'space-between',
                        alignItems: 'center',
                        flexWrap: 'wrap',
                        gap: '12px',
                        cursor: 'pointer',
                        userSelect: 'none',
                      }}
                      onClick={() => {
                        setExpandedInquiryId(isExpanded ? null : item._id);
                        if (!isExpanded && (item.status === 'unread' || !item.status)) {
                          handleUpdateInquiryStatus(item._id, 'read', false);
                        }
                      }}
                    >
                      <div style={{ display: 'flex', alignItems: 'center', gap: '14px', flex: '1', minWidth: '240px' }}>
                        <div
                          style={{
                            width: '40px',
                            height: '40px',
                            borderRadius: '50%',
                            backgroundColor: isUnread ? '#EFF6FF' : '#F1F5F9',
                            color: isUnread ? '#0E63FF' : '#475569',
                            display: 'flex',
                            alignItems: 'center',
                            justifyContent: 'center',
                            fontWeight: '700',
                            fontSize: '15px',
                            flexShrink: 0,
                          }}
                        >
                          {item.name ? item.name.charAt(0).toUpperCase() : 'U'}
                        </div>

                        <div>
                          <div style={{ display: 'flex', alignItems: 'center', gap: '8px', flexWrap: 'wrap' }}>
                            <span style={{ fontSize: '15px', fontWeight: '700', color: '#171151' }}>
                              {item.name || 'Anonymous Contact'}
                            </span>
                            <span
                              style={{
                                fontSize: '11px',
                                fontWeight: '700',
                                padding: '2px 8px',
                                borderRadius: '10px',
                                textTransform: 'uppercase',
                                backgroundColor:
                                  item.status === 'unread'
                                    ? '#FEEAF1'
                                    : item.status === 'replied'
                                    ? '#E7FAF6'
                                    : '#F1F5F9',
                                color:
                                  item.status === 'unread'
                                    ? '#F72A75'
                                    : item.status === 'replied'
                                    ? '#0B9748'
                                    : '#64748B',
                              }}
                            >
                              {item.status || 'unread'}
                            </span>
                          </div>
                          <div style={{ fontSize: '13px', color: '#475569', marginTop: '2px', fontWeight: '500' }}>
                            {item.subject || 'General Inquiry'}
                          </div>
                        </div>
                      </div>

                      <div style={{ display: 'flex', alignItems: 'center', gap: '16px' }}>
                        <div style={{ textAlign: 'right', fontSize: '12px', color: '#8A879F' }}>
                          <div>{item.phone}</div>
                          <div>{item.createdAt ? new Date(item.createdAt).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) : 'Recent'}</div>
                        </div>
                        <div
                          style={{
                            width: '28px',
                            height: '28px',
                            borderRadius: '50%',
                            backgroundColor: '#F1F5F9',
                            display: 'flex',
                            alignItems: 'center',
                            justifyContent: 'center',
                            color: '#64748B',
                          }}
                        >
                          {isExpanded ? <ChevronUp size={16} /> : <ChevronDown size={16} />}
                        </div>
                      </div>
                    </div>

                    {/* Expanded Detail Panel */}
                    {isExpanded && (
                      <div
                        style={{
                          padding: '20px',
                          borderTop: '1px solid #F1F5F9',
                          backgroundColor: '#FAFCFF',
                        }}
                      >
                        {/* Meta Data Grid */}
                        <div
                          style={{
                            display: 'grid',
                            gridTemplateColumns: 'repeat(auto-fit, minmax(200px, 1fr))',
                            gap: '14px',
                            marginBottom: '16px',
                            padding: '12px 16px',
                            backgroundColor: '#ffffff',
                            borderRadius: '8px',
                            border: '1px solid #E2E8F0',
                            fontSize: '13px',
                          }}
                        >
                          <div>
                            <span style={{ color: '#8A879F', display: 'block', fontSize: '11px', textTransform: 'uppercase', fontWeight: '700' }}>Sender Email:</span>
                            <a href={`mailto:${item.email}`} style={{ color: '#0E63FF', fontWeight: '600', textDecoration: 'none' }}>{item.email}</a>
                          </div>
                          <div>
                            <span style={{ color: '#8A879F', display: 'block', fontSize: '11px', textTransform: 'uppercase', fontWeight: '700' }}>Phone / WhatsApp:</span>
                            <span style={{ color: '#171151', fontWeight: '600' }}>{item.phone || 'N/A'}</span>
                          </div>
                          <div>
                            <span style={{ color: '#8A879F', display: 'block', fontSize: '11px', textTransform: 'uppercase', fontWeight: '700' }}>Inquiry Source:</span>
                            <span style={{ color: '#171151', fontWeight: '600' }}>{item.source || 'Website Contact Form'}</span>
                          </div>
                        </div>

                        {/* Message Body */}
                        <div style={{ marginBottom: '16px' }}>
                          <label style={{ display: 'block', fontSize: '12px', fontWeight: '700', color: '#171151', textTransform: 'uppercase', marginBottom: '6px' }}>
                            Inquiry Message:
                          </label>
                          <div
                            style={{
                              padding: '14px 16px',
                              backgroundColor: '#ffffff',
                              borderRadius: '8px',
                              border: '1px solid #E2E8F0',
                              fontSize: '14px',
                              color: '#334155',
                              lineHeight: '1.6',
                              whiteSpace: 'pre-wrap',
                            }}
                          >
                            {item.message || 'No message provided.'}
                          </div>
                        </div>

                        {/* Action Buttons */}
                        <div style={{ display: 'flex', gap: '8px', justifyContent: 'flex-end', flexWrap: 'wrap' }}>
                          <button
                            type="button"
                            onClick={() => openReplyModal(item)}
                            style={{
                              display: 'inline-flex',
                              alignItems: 'center',
                              gap: '6px',
                              padding: '8px 14px',
                              borderRadius: '6px',
                              backgroundColor: '#0E63FF',
                              color: '#ffffff',
                              border: 'none',
                              fontSize: '13px',
                              fontWeight: '600',
                              cursor: 'pointer',
                            }}
                          >
                            <Send size={14} />
                            <span>Reply via Email</span>
                          </button>

                          {item.status !== 'replied' && (
                            <button
                              type="button"
                              onClick={() => handleUpdateInquiryStatus(item._id, 'replied')}
                              style={{
                                display: 'inline-flex',
                                alignItems: 'center',
                                gap: '6px',
                                padding: '8px 14px',
                                borderRadius: '6px',
                                backgroundColor: '#E7FAF6',
                                color: '#0B9748',
                                border: '1px solid #A7F3D0',
                                fontSize: '13px',
                                fontWeight: '600',
                                cursor: 'pointer',
                              }}
                            >
                              <CheckCircle2 size={14} />
                              <span>Mark as Replied</span>
                            </button>
                          )}

                          {item.status === 'unread' && (
                            <button
                              type="button"
                              onClick={() => handleUpdateInquiryStatus(item._id, 'read')}
                              style={{
                                display: 'inline-flex',
                                alignItems: 'center',
                                gap: '6px',
                                padding: '8px 14px',
                                borderRadius: '6px',
                                backgroundColor: '#EFF6FF',
                                color: '#0E63FF',
                                border: '1px solid #BFDBFE',
                                fontSize: '13px',
                                fontWeight: '600',
                                cursor: 'pointer',
                              }}
                            >
                              <Eye size={14} />
                              <span>Mark as Read</span>
                            </button>
                          )}

                          <button
                            type="button"
                            onClick={() => handleDeleteInquiry(item._id)}
                            style={{
                              display: 'inline-flex',
                              alignItems: 'center',
                              gap: '6px',
                              padding: '8px 14px',
                              borderRadius: '6px',
                              backgroundColor: '#FFF1F2',
                              color: '#E11D48',
                              border: '1px solid #FDA4AF',
                              fontSize: '13px',
                              fontWeight: '600',
                              cursor: 'pointer',
                            }}
                          >
                            <Trash2 size={14} />
                            <span>Delete</span>
                          </button>
                        </div>
                      </div>
                    )}
                  </div>
                );
              })}
            </div>
          )}

          {/* Inquiries Pagination Bar */}
          {filteredInquiries.length > inquiryPageSize && (
            <div
              style={{
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'space-between',
                paddingTop: '20px',
                marginTop: '16px',
                borderTop: '1px solid #E2E8F0',
                flexWrap: 'wrap',
                gap: '12px',
              }}
            >
              <span style={{ fontSize: '13px', color: '#64748B' }}>
                Showing {(inquiryPage - 1) * inquiryPageSize + 1} to {Math.min(inquiryPage * inquiryPageSize, filteredInquiries.length)} of {filteredInquiries.length} inquiries
              </span>
              <div style={{ display: 'flex', alignItems: 'center', gap: '6px' }}>
                <button
                  type="button"
                  disabled={inquiryPage === 1}
                  onClick={() => setInquiryPage((p) => Math.max(p - 1, 1))}
                  style={{
                    padding: '6px 12px',
                    borderRadius: '6px',
                    border: '1px solid #D1D6E0',
                    backgroundColor: inquiryPage === 1 ? '#F8FAFC' : '#ffffff',
                    color: inquiryPage === 1 ? '#CBD5E1' : '#171151',
                    cursor: inquiryPage === 1 ? 'not-allowed' : 'pointer',
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
                  Page {inquiryPage} of {Math.ceil(filteredInquiries.length / inquiryPageSize) || 1}
                </span>
                <button
                  type="button"
                  disabled={inquiryPage >= Math.ceil(filteredInquiries.length / inquiryPageSize)}
                  onClick={() => setInquiryPage((p) => Math.min(p + 1, Math.ceil(filteredInquiries.length / inquiryPageSize)))}
                  style={{
                    padding: '6px 12px',
                    borderRadius: '6px',
                    border: '1px solid #D1D6E0',
                    backgroundColor: inquiryPage >= Math.ceil(filteredInquiries.length / inquiryPageSize) ? '#F8FAFC' : '#ffffff',
                    color: inquiryPage >= Math.ceil(filteredInquiries.length / inquiryPageSize) ? '#CBD5E1' : '#171151',
                    cursor: inquiryPage >= Math.ceil(filteredInquiries.length / inquiryPageSize) ? 'not-allowed' : 'pointer',
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
      )}

      {/* REPLY MODAL POPUP */}
      {replyModal.open && replyModal.inquiry && (
        <div
          style={{
            position: 'fixed',
            top: 0,
            left: 0,
            right: 0,
            bottom: 0,
            backgroundColor: 'rgba(15, 23, 42, 0.65)',
            zIndex: 10000,
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'center',
            padding: '20px',
          }}
        >
          <div
            style={{
              backgroundColor: '#ffffff',
              borderRadius: '14px',
              maxWidth: '620px',
              width: '100%',
              boxShadow: '0 20px 40px rgba(0,0,0,0.2)',
              overflow: 'hidden',
              animation: 'fadeIn 0.2s ease',
            }}
          >
            {/* Modal Header */}
            <div
              style={{
                padding: '20px 24px',
                backgroundColor: '#171151',
                color: '#ffffff',
                display: 'flex',
                justifyContent: 'space-between',
                alignItems: 'center',
              }}
            >
              <div>
                <h3 style={{ fontSize: '17px', fontWeight: '700', margin: 0 }}>
                  Send Email Reply to {replyModal.inquiry.name}
                </h3>
                <span style={{ fontSize: '13px', color: '#94A3B8' }}>
                  Recipient: {replyModal.inquiry.email} ({replyModal.inquiry.phone})
                </span>
              </div>
              <button
                onClick={() => setReplyModal({ open: false, inquiry: null })}
                style={{
                  background: 'none',
                  border: 'none',
                  color: '#94A3B8',
                  fontSize: '22px',
                  cursor: 'pointer',
                }}
              >
                &times;
              </button>
            </div>

            {/* Modal Form */}
            <form onSubmit={handleSendReply} style={{ padding: '24px' }}>
              <div style={{ marginBottom: '16px' }}>
                <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>
                  Subject
                </label>
                <input
                  type="text"
                  value={replySubject}
                  onChange={(e) => setReplySubject(e.target.value)}
                  required
                  style={{
                    width: '100%',
                    padding: '10px 14px',
                    borderRadius: '8px',
                    border: '1px solid #CBD5E1',
                    fontSize: '14px',
                    boxSizing: 'border-box',
                  }}
                />
              </div>

              <div style={{ marginBottom: '20px' }}>
                <label style={{ display: 'block', fontSize: '13px', fontWeight: '600', color: '#171151', marginBottom: '6px' }}>
                  Reply Message Body
                </label>
                <textarea
                  rows={6}
                  value={replyMessage}
                  onChange={(e) => setReplyMessage(e.target.value)}
                  required
                  style={{
                    width: '100%',
                    padding: '12px 14px',
                    borderRadius: '8px',
                    border: '1px solid #CBD5E1',
                    fontSize: '14px',
                    boxSizing: 'border-box',
                    lineHeight: '1.5',
                  }}
                ></textarea>
              </div>

              <div style={{ display: 'flex', justifyContent: 'flex-end', gap: '10px' }}>
                <button
                  type="button"
                  onClick={() => setReplyModal({ open: false, inquiry: null })}
                  disabled={sendingReply}
                  style={{
                    padding: '10px 18px',
                    borderRadius: '8px',
                    backgroundColor: '#F1F5F9',
                    color: '#475569',
                    border: '1px solid #CBD5E1',
                    fontWeight: '600',
                    fontSize: '13px',
                    cursor: 'pointer',
                  }}
                >
                  Cancel
                </button>
                <button
                  type="submit"
                  disabled={sendingReply}
                  style={{
                    display: 'inline-flex',
                    alignItems: 'center',
                    gap: '6px',
                    padding: '10px 22px',
                    borderRadius: '8px',
                    backgroundColor: '#0b9748',
                    color: '#ffffff',
                    border: 'none',
                    fontWeight: '600',
                    fontSize: '13px',
                    cursor: sendingReply ? 'not-allowed' : 'pointer',
                  }}
                >
                  <Send size={15} />
                  <span>{sendingReply ? 'Dispatching...' : 'Dispatch Reply'}</span>
                </button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
}
