'use client';

import React, { useState, useEffect, useRef } from 'react';
import {
  Mail,
  Send,
  Users,
  Eye,
  Plus,
  X,
  FileText,
  Upload,
  CheckCircle,
  AlertCircle,
  Sparkles,
  Smartphone,
  Monitor,
  RefreshCw,
  Clock,
  Layers,
  Award,
  Bell,
  Code,
  Copy,
  Check,
} from 'lucide-react';

const TEMPLATES = [
  {
    id: 'announcement',
    name: 'Healthcare Announcement',
    icon: Bell,
    badge: 'Corporate',
    badgeColor: '#0E63FF',
    badgeBg: '#EFF6FF',
    desc: 'Official medical & corporate announcements with verified header and support box.',
    defaultSubject: 'Important Healthcare Technology Update - Innotech Medical',
    defaultBody:
      'We are pleased to announce our latest expansion in biomedical technology distribution across Pakistan.\n\nInnotech Medical continues to deliver cutting-edge clinical devices, ICU life support instruments, and diagnostic equipment with certified engineering support.',
    defaultCtaText: 'Read Full Announcement',
    defaultCtaUrl: 'https://innotechmedical.org/about',
    ctaColor: '#0E63FF',
  },
  {
    id: 'product_launch',
    name: 'Product & Equipment Launch',
    icon: Award,
    badge: 'Equipment Showcase',
    badgeColor: '#10D0A1',
    badgeBg: '#E7FAF6',
    desc: 'Showcase advanced hospital equipment, feature highlights, and turnkey solutions.',
    defaultSubject: 'Introducing Next-Gen Diagnostic & Surgical Equipment Solutions',
    defaultBody:
      'Elevate your hospital and laboratory diagnostic accuracy with Innotech Medical’s newly launched clinical equipment line.\n\nExplore world-class biomedical devices backed by 24/7 priority maintenance and hospital staff engineering training.',
    defaultCtaText: 'Explore Equipment Catalog',
    defaultCtaUrl: 'https://innotechmedical.org/services',
    ctaColor: '#10D0A1',
  },
  {
    id: 'newsletter',
    name: 'Clinical Newsletter Digest',
    icon: Layers,
    badge: 'Monthly Digest',
    badgeColor: '#8B5CF6',
    badgeBg: '#F3E8FF',
    desc: 'Clean editorial healthcare newsletter digest with featured updates and insights.',
    defaultSubject: 'Innotech Healthcare Insights & Monthly Clinical Digest',
    defaultBody:
      'Welcome to this month’s edition of Innotech Clinical Insights.\n\nLearn how preventive calibration and high-precision laboratory diagnostics are transforming patient care standards across medical institutions in Pakistan.',
    defaultCtaText: 'Read Clinical Articles',
    defaultCtaUrl: 'https://innotechmedical.org/blog',
    ctaColor: '#8B5CF6',
  },
  {
    id: 'maintenance',
    name: 'Service & Maintenance Alert',
    icon: AlertCircle,
    badge: 'Urgent Advisory',
    badgeColor: '#F72A75',
    badgeBg: '#FEEAF1',
    desc: 'High-priority technical service notices, calibration reminders, and emergency support.',
    defaultSubject: 'Urgent Equipment Maintenance & Calibration Advisory',
    defaultBody:
      'Routine biomedical calibration and safety inspections are scheduled for partner medical facilities.\n\nPlease review your equipment operation logs and connect with our dedicated engineering hotline for immediate service dispatch.',
    defaultCtaText: 'Schedule Service Dispatch',
    defaultCtaUrl: 'https://innotechmedical.org/contact',
    ctaColor: '#F72A75',
  },
  {
    id: 'custom',
    name: 'Custom HTML / Upload Code',
    icon: Code,
    badge: 'Developer / Custom',
    badgeColor: '#F59E0B',
    badgeBg: '#FEF3C7',
    desc: 'Paste your own raw HTML code or upload a custom .html email template file.',
    defaultSubject: 'Custom Healthcare Campaign - Innotech Medical',
    defaultBody: '',
    defaultCtaText: '',
    defaultCtaUrl: '',
    ctaColor: '#0E63FF',
  },
];

export default function EmailTemplatesAdminPage() {
  const [data, setData] = useState({
    campaigns: [],
    subscriberCount: 0,
    inquiryCount: 0,
    subscribers: [],
    inquiryEmails: [],
  });
  const [loading, setLoading] = useState(true);
  const [sending, setSending] = useState(false);
  const [statusMsg, setStatusMsg] = useState({ type: '', msg: '' });

  // Form State
  const [selectedTemplate, setSelectedTemplate] = useState('announcement');
  const [senderName, setSenderName] = useState('Innotech Medical Support');
  const [senderEmail, setSenderEmail] = useState('info@innotechmedical.org');
  const [subject, setSubject] = useState(TEMPLATES[0].defaultSubject);
  const [messageBody, setMessageBody] = useState(TEMPLATES[0].defaultBody);
  const [ctaText, setCtaText] = useState(TEMPLATES[0].defaultCtaText);
  const [ctaUrl, setCtaUrl] = useState(TEMPLATES[0].defaultCtaUrl);
  const [ctaColor, setCtaColor] = useState(TEMPLATES[0].ctaColor);
  const [customHtml, setCustomHtml] = useState('');

  // Recipients Management
  const [recipients, setRecipients] = useState(['info@innotechmedical.org']);
  const [emailInput, setEmailInput] = useState('');
  const [previewModalOpen, setPreviewModalOpen] = useState(false);
  const [previewDevice, setPreviewDevice] = useState('desktop'); // 'desktop' | 'mobile'
  const [testEmailModal, setTestEmailModal] = useState(false);
  const [testEmailAddress, setTestEmailAddress] = useState('admin@innotechmedical.org');
  const fileInputRef = useRef(null);
  const htmlFileInputRef = useRef(null);

  const fetchCampaignData = async () => {
    try {
      const res = await fetch('/api/admin/broadcast-email');
      const resData = await res.json();
      if (resData.success) {
        setData(resData);
      }
    } catch (e) {
      console.error(e);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchCampaignData();
  }, []);

  const handleTemplateSelect = (tmpl) => {
    setSelectedTemplate(tmpl.id);
    if (tmpl.id !== 'custom') {
      setSubject(tmpl.defaultSubject);
      setMessageBody(tmpl.defaultBody);
      setCtaText(tmpl.defaultCtaText);
      setCtaUrl(tmpl.defaultCtaUrl);
      setCtaColor(tmpl.ctaColor);
    }
  };

  // Add recipient email
  const addEmail = (raw) => {
    const clean = (raw || '').trim().toLowerCase();
    if (!clean) return;
    if (!clean.includes('@') || !clean.includes('.')) {
      alert(`Invalid email format: "${clean}"`);
      return;
    }
    if (!recipients.includes(clean)) {
      setRecipients((prev) => [...prev, clean]);
    }
    setEmailInput('');
  };

  const handleKeyDown = (e) => {
    if (e.key === 'Enter' || e.key === ',' || e.key === ' ') {
      e.preventDefault();
      addEmail(emailInput);
    }
  };

  const removeRecipient = (emailToRemove) => {
    setRecipients((prev) => prev.filter((e) => e !== emailToRemove));
  };

  const importSubscribers = () => {
    if (!data.subscribers || data.subscribers.length === 0) {
      alert('No active newsletter subscribers found in the database.');
      return;
    }
    const merged = Array.from(new Set([...recipients, ...data.subscribers]));
    setRecipients(merged);
    setStatusMsg({
      type: 'success',
      msg: `✓ Added ${data.subscribers.length} newsletter subscribers to recipient list!`,
    });
    setTimeout(() => setStatusMsg({ type: '', msg: '' }), 4000);
  };

  const importInquiries = () => {
    if (!data.inquiryEmails || data.inquiryEmails.length === 0) {
      alert('No customer inquiry emails found in the database.');
      return;
    }
    const merged = Array.from(new Set([...recipients, ...data.inquiryEmails]));
    setRecipients(merged);
    setStatusMsg({
      type: 'success',
      msg: `✓ Added ${data.inquiryEmails.length} customer inquiry emails to recipient list!`,
    });
    setTimeout(() => setStatusMsg({ type: '', msg: '' }), 4000);
  };

  // CSV / TXT File Upload Handler
  const handleFileUpload = (e) => {
    const file = e.target.files?.[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = (event) => {
      const text = event.target?.result || '';
      const extracted = text
        .split(/[\r\n,;]+/)
        .map((s) => s.trim().toLowerCase())
        .filter((s) => s.includes('@') && s.includes('.'));

      if (extracted.length === 0) {
        alert('No valid email addresses found in the uploaded file.');
        return;
      }

      const merged = Array.from(new Set([...recipients, ...extracted]));
      setRecipients(merged);
      setStatusMsg({
        type: 'success',
        msg: `✓ Imported ${extracted.length} recipient emails from "${file.name}"!`,
      });
      setTimeout(() => setStatusMsg({ type: '', msg: '' }), 5000);
    };
    reader.readAsText(file);
    if (fileInputRef.current) fileInputRef.current.value = '';
  };

  // Custom HTML file upload
  const handleHtmlFileUpload = (e) => {
    const file = e.target.files?.[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = (event) => {
      const html = event.target?.result || '';
      setCustomHtml(html);
      setSelectedTemplate('custom');
      setStatusMsg({
        type: 'success',
        msg: `✓ Custom HTML template loaded from "${file.name}"!`,
      });
      setTimeout(() => setStatusMsg({ type: '', msg: '' }), 4000);
    };
    reader.readAsText(file);
    if (htmlFileInputRef.current) htmlFileInputRef.current.value = '';
  };

  // Send Email (Bulk or Test)
  const handleSendEmail = async (isTest = false) => {
    if (isTest && !testEmailAddress.trim()) {
      alert('Please enter a test recipient email.');
      return;
    }

    if (!isTest && recipients.length === 0) {
      alert('Please add at least one recipient email address.');
      return;
    }

    if (!subject.trim()) {
      alert('Please enter an email subject line.');
      return;
    }

    if (selectedTemplate === 'custom' && !customHtml.trim()) {
      alert('Please paste or upload custom HTML code.');
      return;
    }

    if (selectedTemplate !== 'custom' && !messageBody.trim()) {
      alert('Please enter your message body.');
      return;
    }

    setSending(true);
    setStatusMsg({ type: '', msg: '' });

    try {
      const res = await fetch('/api/admin/broadcast-email', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          recipients,
          subject,
          templateId: selectedTemplate,
          messageBody,
          ctaText,
          ctaUrl,
          ctaColor,
          senderName,
          senderEmail,
          customHtml,
          isTest,
          testEmail: testEmailAddress,
        }),
      });

      const resData = await res.json();
      if (res.ok && resData.success) {
        setStatusMsg({ type: 'success', msg: resData.message });
        if (isTest) {
          setTestEmailModal(false);
        }
        fetchCampaignData();
      } else {
        setStatusMsg({
          type: 'error',
          msg: resData.message || 'Failed to dispatch email campaign.',
        });
      }
    } catch (err) {
      console.error(err);
      setStatusMsg({ type: 'error', msg: 'Network error sending email.' });
    } finally {
      setSending(false);
    }
  };

  // Generate preview HTML string
  const getPreviewHtml = () => {
    if (selectedTemplate === 'custom') {
      return customHtml || '<div style="padding:40px; text-align:center; color:#94a3b8;">Paste custom HTML to preview</div>';
    }

    const formattedBody = (messageBody || 'Your message content goes here...')
      .split('\n\n')
      .map((p) => `<p style="margin: 0 0 16px; font-size: 15px; line-height: 1.6; color: #334155;">${p.replace(/\n/g, '<br/>')}</p>`)
      .join('');

    const ctaButtonHtml =
      ctaText && ctaUrl
        ? `
      <div style="text-align: center; margin: 30px 0 20px;">
        <a href="${ctaUrl}" target="_blank" style="display: inline-block; background-color: ${ctaColor}; color: #ffffff; text-decoration: none; padding: 14px 28px; font-size: 15px; font-weight: 700; border-radius: 8px; box-shadow: 0 4px 14px rgba(14, 99, 255, 0.25); text-transform: uppercase; letter-spacing: 0.5px;">
          ${ctaText} &rarr;
        </a>
      </div>
    `
        : '';

    if (selectedTemplate === 'announcement') {
      return `
        <div style="background-color: #f1f5f9; padding: 30px 15px; font-family: 'Segoe UI', sans-serif;">
          <div style="max-width: 580px; margin: 0 auto; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 8px 25px rgba(0,0,0,0.06);">
            <div style="background: linear-gradient(135deg, #171151 0%, #0E63FF 100%); padding: 28px 30px; text-align: center;">
              <h1 style="color: #fff; margin: 0; font-size: 20px; font-weight: 700;">INNOTECH MEDICAL PVT LTD</h1>
              <p style="color: #cbd5e1; margin: 6px 0 0; font-size: 12px;">Official Healthcare & Clinical Notification</p>
            </div>
            <div style="padding: 24px 30px 0;">
              <span style="display: inline-block; background-color: #E7FAF6; color: #0b9748; padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 700; text-transform: uppercase;">
                Verified Announcement
              </span>
            </div>
            <div style="padding: 16px 30px 24px;">
              <h2 style="color: #171151; font-size: 19px; font-weight: 700; margin: 0 0 16px;">${subject || 'Announcement Subject'}</h2>
              ${formattedBody}
              ${ctaButtonHtml}
            </div>
            <div style="padding: 0 30px 24px;">
              <div style="background-color: #f8fafc; border-left: 4px solid #0E63FF; padding: 12px 16px; border-radius: 4px; font-size: 12px; color: #475569;">
                <strong>Need assistance?</strong> Contact our technical engineering team at info@innotechmedical.org or call +92 331 6699992.
              </div>
            </div>
            <div style="background-color: #0f172a; padding: 18px 30px; text-align: center; color: #94a3b8; font-size: 11px;">
              &copy; ${new Date().getFullYear()} INNOTECH MEDICAL PVT LTD. All rights reserved.
            </div>
          </div>
        </div>
      `;
    }

    if (selectedTemplate === 'product_launch') {
      return `
        <div style="background-color: #0f172a; padding: 30px 15px; font-family: 'Segoe UI', sans-serif;">
          <div style="max-width: 580px; margin: 0 auto; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.3);">
            <div style="background-color: #171151; padding: 12px 20px; text-align: center; border-bottom: 3px solid #10D0A1;">
              <span style="color: #10D0A1; font-size: 11px; font-weight: 700; text-transform: uppercase;">
                ⭐ NEW MEDICAL TECHNOLOGY &bull; ADVANCED CLINICAL SOLUTIONS
              </span>
            </div>
            <div style="background: linear-gradient(135deg, #171151 0%, #1e1b4b 100%); padding: 30px 25px 24px; text-align: center;">
              <h1 style="color: #ffffff; margin: 0 0 6px; font-size: 22px; font-weight: 800;">${subject || 'Product Launch Title'}</h1>
              <p style="color: #94a3b8; font-size: 13px; margin: 0;">Authorized Distributor in Pakistan &bull; Turnkey Hospital Systems</p>
            </div>
            <div style="padding: 26px 30px 20px;">
              ${formattedBody}
              <div style="background-color: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 8px; padding: 14px 18px; margin: 16px 0;">
                <h4 style="margin: 0 0 6px; color: #171151; font-size: 13px; font-weight: 700;">Key Equipment Highlights:</h4>
                <ul style="margin: 0; padding-left: 18px; color: #475569; font-size: 12.5px; line-height: 1.5;">
                  <li>Certified Biomedical Engineering & Precision Calibration</li>
                  <li>Complete On-Site Hospital Installation & Staff Training</li>
                  <li>24/7 Priority Emergency Replacement & Technical Warranty</li>
                </ul>
              </div>
              ${ctaButtonHtml}
            </div>
            <div style="background-color: #f1f5f9; padding: 18px 30px; text-align: center; border-top: 1px solid #e2e8f0; font-size: 11px; color: #64748b;">
              INNOTECH MEDICAL PVT LTD &bull; Helpline: +92 331 6699992
            </div>
          </div>
        </div>
      `;
    }

    if (selectedTemplate === 'newsletter') {
      return `
        <div style="background-color: #f8fafc; padding: 30px 15px; font-family: 'Segoe UI', sans-serif;">
          <div style="max-width: 580px; margin: 0 auto; background: #fff; border-radius: 12px; overflow: hidden; border: 1px solid #e2e8f0; box-shadow: 0 4px 15px rgba(0,0,0,0.04);">
            <div style="padding: 24px 30px; border-bottom: 2px solid #8B5CF6; background: #fff;">
              <h2 style="color: #171151; margin: 0; font-size: 18px; font-weight: 800;">INNOTECH MEDICAL PVT LTD</h2>
              <span style="color: #8B5CF6; font-size: 11px; font-weight: 700; text-transform: uppercase;">Healthcare Innovations &bull; Monthly Digest</span>
            </div>
            <div style="padding: 26px 30px;">
              <h3 style="color: #171151; font-size: 18px; font-weight: 700; margin: 0 0 14px;">${subject || 'Newsletter Title'}</h3>
              ${formattedBody}
              ${ctaButtonHtml}
            </div>
            <div style="background-color: #171151; padding: 18px 30px; text-align: center; color: #fff; font-size: 11px;">
              Thank you for being part of the Innotech Medical healthcare network.
            </div>
          </div>
        </div>
      `;
    }

    // Maintenance / Advisory
    return `
      <div style="background-color: #f1f5f9; padding: 30px 15px; font-family: 'Segoe UI', sans-serif;">
        <div style="max-width: 580px; margin: 0 auto; background: #fff; border-radius: 10px; overflow: hidden; border-top: 4px solid #F72A75; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
          <div style="padding: 22px 30px 10px;">
            <span style="background-color: #FEEAF1; color: #D92D20; padding: 4px 10px; border-radius: 14px; font-size: 11px; font-weight: 700; text-transform: uppercase;">
              Service & Technical Advisory
            </span>
            <h2 style="color: #171151; margin: 10px 0 0; font-size: 18px; font-weight: 700;">${subject || 'Advisory Subject'}</h2>
          </div>
          <div style="padding: 14px 30px 20px;">
            ${formattedBody}
            ${ctaButtonHtml}
          </div>
          <div style="padding: 0 30px 20px;">
            <div style="background-color: #FFF8E6; border: 1px solid #FFE08A; padding: 10px 14px; border-radius: 6px; font-size: 12px; color: #8A5B00;">
              <strong>Emergency Maintenance Support:</strong> Dial <strong>+92 331 6699992</strong> directly for 24/7 technical team dispatch.
            </div>
          </div>
          <div style="background-color: #0f172a; padding: 16px 30px; text-align: center; color: #94a3b8; font-size: 11px;">
            &copy; ${new Date().getFullYear()} INNOTECH MEDICAL PVT LTD &bull; Specialized Biomedical Engineering Services
          </div>
        </div>
      </div>
    `;
  };

  return (
    <div style={{ maxWidth: '1400px', margin: '0 auto' }}>
      {/* ── 1. VIP HEADER BANNER ── */}
      <div
        style={{
          background: 'linear-gradient(135deg, #171151 0%, #0E63FF 100%)',
          borderRadius: '16px',
          padding: '28px 32px',
          color: '#ffffff',
          marginBottom: '28px',
          display: 'flex',
          justifyContent: 'space-between',
          alignItems: 'center',
          flexWrap: 'wrap',
          gap: '20px',
          boxShadow: '0 10px 30px rgba(14, 99, 255, 0.18)',
        }}
      >
        <div>
          <div style={{ display: 'inline-flex', alignItems: 'center', gap: '8px', padding: '4px 12px', borderRadius: '20px', backgroundColor: 'rgba(255,255,255,0.15)', fontSize: '12px', fontWeight: '700', textTransform: 'uppercase', letterSpacing: '0.8px', marginBottom: '10px' }}>
            <Sparkles size={14} color="#10D0A1" />
            <span>VIP Bulk Email Dispatcher & Template Studio</span>
          </div>
          <h1 style={{ fontSize: '24px', fontWeight: '800', margin: '0 0 6px', color: '#ffffff' }}>
            Multi-User Email Campaigns & Template Builder
          </h1>
          <p style={{ margin: 0, fontSize: '13.5px', color: '#E2E8F0', maxWidth: '650px', lineHeight: '1.5' }}>
            Broadcast beautiful, branded HTML emails to multiple recipients simultaneously. Select pre-built VIP templates, import subscribers & inquiries, or upload custom HTML code.
          </p>
        </div>

        {/* Quick Stat Badges */}
        <div style={{ display: 'flex', gap: '12px', flexWrap: 'wrap' }}>
          <div style={{ backgroundColor: 'rgba(255,255,255,0.1)', backdropFilter: 'blur(4px)', padding: '12px 18px', borderRadius: '10px', border: '1px solid rgba(255,255,255,0.15)', textAlign: 'center' }}>
            <div style={{ fontSize: '11px', color: '#93C5FD', fontWeight: 600, textTransform: 'uppercase' }}>Subscribers</div>
            <div style={{ fontSize: '20px', fontWeight: 800, color: '#ffffff' }}>{data.subscriberCount}</div>
          </div>
          <div style={{ backgroundColor: 'rgba(255,255,255,0.1)', backdropFilter: 'blur(4px)', padding: '12px 18px', borderRadius: '10px', border: '1px solid rgba(255,255,255,0.15)', textAlign: 'center' }}>
            <div style={{ fontSize: '11px', color: '#A7F3D0', fontWeight: 600, textTransform: 'uppercase' }}>Inquiries</div>
            <div style={{ fontSize: '20px', fontWeight: 800, color: '#ffffff' }}>{data.inquiryCount}</div>
          </div>
          <div style={{ backgroundColor: 'rgba(255,255,255,0.1)', backdropFilter: 'blur(4px)', padding: '12px 18px', borderRadius: '10px', border: '1px solid rgba(255,255,255,0.15)', textAlign: 'center' }}>
            <div style={{ fontSize: '11px', color: '#FDE68A', fontWeight: 600, textTransform: 'uppercase' }}>Campaigns Sent</div>
            <div style={{ fontSize: '20px', fontWeight: 800, color: '#ffffff' }}>{data.totalCampaigns || 0}</div>
          </div>
        </div>
      </div>

      {/* Status Notification Toast */}
      {statusMsg.msg && (
        <div
          style={{
            padding: '14px 20px',
            borderRadius: '10px',
            marginBottom: '24px',
            display: 'flex',
            alignItems: 'center',
            gap: '12px',
            backgroundColor: statusMsg.type === 'success' ? '#E7FAF6' : '#FEEAF1',
            color: statusMsg.type === 'success' ? '#0B9748' : '#D92D20',
            border: `1px solid ${statusMsg.type === 'success' ? '#A3EAD8' : '#FDA29B'}`,
            fontSize: '14px',
            fontWeight: 600,
          }}
        >
          {statusMsg.type === 'success' ? <CheckCircle size={18} /> : <AlertCircle size={18} />}
          <span>{statusMsg.msg}</span>
        </div>
      )}

      {/* ── 2. MAIN COMPOSER GRID ── */}
      <div style={{ display: 'grid', gridTemplateColumns: 'minmax(0, 1.4fr) minmax(0, 1fr)', gap: '24px', alignItems: 'start' }}>
        {/* LEFT COLUMN: Recipient Manager & Email Composer */}
        <div style={{ display: 'flex', flexDirection: 'column', gap: '24px' }}>
          {/* Card 1: Multi-Recipient Selector & Batch Importer */}
          <div style={{ backgroundColor: '#ffffff', borderRadius: '14px', padding: '24px', boxShadow: '0 4px 16px rgba(0,0,0,0.04)', border: '1px solid #ECEEF3' }}>
            <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '16px' }}>
              <div style={{ display: 'flex', alignItems: 'center', gap: '10px' }}>
                <div style={{ width: '36px', height: '36px', borderRadius: '8px', backgroundColor: '#EFF6FF', color: '#0E63FF', display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
                  <Users size={18} />
                </div>
                <div>
                  <h3 style={{ fontSize: '16px', fontWeight: 700, margin: 0, color: '#171151' }}>
                    Target Recipients ({recipients.length})
                  </h3>
                  <p style={{ fontSize: '12px', color: '#64748b', margin: 0 }}>Add multiple emails, import subscriber list, or upload CSV/TXT.</p>
                </div>
              </div>

              {recipients.length > 0 && (
                <button
                  type="button"
                  onClick={() => setRecipients([])}
                  style={{ fontSize: '12px', color: '#EF4444', background: 'transparent', border: 'none', cursor: 'pointer', fontWeight: 600 }}
                >
                  Clear All
                </button>
              )}
            </div>

            {/* Fast Import Quick Buttons */}
            <div style={{ display: 'flex', gap: '10px', flexWrap: 'wrap', marginBottom: '16px' }}>
              <button
                type="button"
                onClick={importSubscribers}
                style={{
                  padding: '8px 14px',
                  backgroundColor: '#EFF6FF',
                  color: '#0E63FF',
                  border: '1px solid #BFDBFE',
                  borderRadius: '6px',
                  fontSize: '12.5px',
                  fontWeight: 600,
                  cursor: 'pointer',
                  display: 'inline-flex',
                  alignItems: 'center',
                  gap: '6px',
                }}
              >
                <Users size={14} />
                <span>+ Load Subscribers ({data.subscriberCount})</span>
              </button>

              <button
                type="button"
                onClick={importInquiries}
                style={{
                  padding: '8px 14px',
                  backgroundColor: '#E7FAF6',
                  color: '#0B9748',
                  border: '1px solid #A3EAD8',
                  borderRadius: '6px',
                  fontSize: '12.5px',
                  fontWeight: 600,
                  cursor: 'pointer',
                  display: 'inline-flex',
                  alignItems: 'center',
                  gap: '6px',
                }}
              >
                <Mail size={14} />
                <span>+ Load Inquiries ({data.inquiryCount})</span>
              </button>

              <input
                type="file"
                ref={fileInputRef}
                onChange={handleFileUpload}
                accept=".csv,.txt"
                style={{ display: 'none' }}
              />
              <button
                type="button"
                onClick={() => fileInputRef.current?.click()}
                style={{
                  padding: '8px 14px',
                  backgroundColor: '#F8FAFC',
                  color: '#475569',
                  border: '1px solid #CBD5E1',
                  borderRadius: '6px',
                  fontSize: '12.5px',
                  fontWeight: 600,
                  cursor: 'pointer',
                  display: 'inline-flex',
                  alignItems: 'center',
                  gap: '6px',
                }}
              >
                <Upload size={14} />
                <span>Upload CSV / TXT</span>
              </button>
            </div>

            {/* Tag Input Box */}
            <div
              style={{
                border: '1px solid #D1D6E0',
                borderRadius: '8px',
                padding: '10px 12px',
                backgroundColor: '#FAFAFC',
                minHeight: '80px',
                maxHeight: '180px',
                overflowY: 'auto',
                display: 'flex',
                flexWrap: 'wrap',
                gap: '6px',
                alignItems: 'center',
              }}
            >
              {recipients.map((email) => (
                <span
                  key={email}
                  style={{
                    display: 'inline-flex',
                    alignItems: 'center',
                    gap: '6px',
                    padding: '4px 10px',
                    backgroundColor: '#171151',
                    color: '#ffffff',
                    borderRadius: '16px',
                    fontSize: '12px',
                    fontWeight: 500,
                  }}
                >
                  <span>{email}</span>
                  <button
                    type="button"
                    onClick={() => removeRecipient(email)}
                    style={{ background: 'transparent', border: 'none', color: '#94a3b8', cursor: 'pointer', padding: 0, display: 'flex', alignItems: 'center' }}
                  >
                    <X size={13} />
                  </button>
                </span>
              ))}

              <input
                type="text"
                value={emailInput}
                onChange={(e) => setEmailInput(e.target.value)}
                onKeyDown={handleKeyDown}
                placeholder={recipients.length === 0 ? 'Type email address and press Enter...' : 'Add another email...'}
                style={{
                  border: 'none',
                  outline: 'none',
                  backgroundColor: 'transparent',
                  flex: 1,
                  minWidth: '220px',
                  fontSize: '13px',
                  color: '#171151',
                  padding: '4px 0',
                }}
              />
            </div>
            <div style={{ fontSize: '11px', color: '#94a3b8', marginTop: '6px' }}>
              Tip: Press <strong>Enter</strong> or <strong>Comma (,)</strong> to add emails in bulk.
            </div>
          </div>

          {/* Card 2: Template Selector */}
          <div style={{ backgroundColor: '#ffffff', borderRadius: '14px', padding: '24px', boxShadow: '0 4px 16px rgba(0,0,0,0.04)', border: '1px solid #ECEEF3' }}>
            <h3 style={{ fontSize: '16px', fontWeight: 700, margin: '0 0 16px', color: '#171151' }}>
              Select VIP HTML Email Template
            </h3>

            <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fill, minmax(240px, 1fr))', gap: '14px' }}>
              {TEMPLATES.map((tmpl) => {
                const Icon = tmpl.icon;
                const isSelected = selectedTemplate === tmpl.id;
                return (
                  <div
                    key={tmpl.id}
                    onClick={() => handleTemplateSelect(tmpl)}
                    style={{
                      padding: '16px',
                      borderRadius: '10px',
                      border: isSelected ? `2px solid ${tmpl.badgeColor}` : '1px solid #E2E8F0',
                      backgroundColor: isSelected ? '#F8FAFC' : '#ffffff',
                      cursor: 'pointer',
                      transition: 'all 0.2s ease',
                      boxShadow: isSelected ? `0 4px 14px ${tmpl.badgeColor}20` : 'none',
                      position: 'relative',
                    }}
                  >
                    <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'flex-start', marginBottom: '8px' }}>
                      <div
                        style={{
                          width: '32px',
                          height: '32px',
                          borderRadius: '6px',
                          backgroundColor: tmpl.badgeBg,
                          color: tmpl.badgeColor,
                          display: 'flex',
                          alignItems: 'center',
                          justifyContent: 'center',
                        }}
                      >
                        <Icon size={16} />
                      </div>
                      <span
                        style={{
                          fontSize: '10.5px',
                          fontWeight: 700,
                          padding: '2px 8px',
                          borderRadius: '10px',
                          backgroundColor: tmpl.badgeBg,
                          color: tmpl.badgeColor,
                        }}
                      >
                        {tmpl.badge}
                      </span>
                    </div>

                    <h4 style={{ fontSize: '14px', fontWeight: 700, margin: '0 0 4px', color: '#171151' }}>
                      {tmpl.name}
                    </h4>
                    <p style={{ fontSize: '11.5px', color: '#64748b', margin: 0, lineHeight: '1.4' }}>
                      {tmpl.desc}
                    </p>
                  </div>
                );
              })}
            </div>
          </div>

          {/* Card 3: Email Content Composer */}
          <div style={{ backgroundColor: '#ffffff', borderRadius: '14px', padding: '24px', boxShadow: '0 4px 16px rgba(0,0,0,0.04)', border: '1px solid #ECEEF3' }}>
            <h3 style={{ fontSize: '16px', fontWeight: 700, margin: '0 0 18px', color: '#171151' }}>
              Compose Email Message
            </h3>

            {/* Sender Info Row */}
            <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '14px', marginBottom: '16px' }}>
              <div>
                <label style={{ display: 'block', fontSize: '12px', fontWeight: 700, color: '#475569', marginBottom: '6px' }}>
                  Sender Name
                </label>
                <input
                  type="text"
                  value={senderName}
                  onChange={(e) => setSenderName(e.target.value)}
                  style={{ width: '100%', padding: '10px 12px', borderRadius: '6px', border: '1px solid #D1D6E0', fontSize: '13.5px', color: '#171151', boxSizing: 'border-box' }}
                />
              </div>
              <div>
                <label style={{ display: 'block', fontSize: '12px', fontWeight: 700, color: '#475569', marginBottom: '6px' }}>
                  Sender Email Address
                </label>
                <input
                  type="email"
                  value={senderEmail}
                  onChange={(e) => setSenderEmail(e.target.value)}
                  style={{ width: '100%', padding: '10px 12px', borderRadius: '6px', border: '1px solid #D1D6E0', fontSize: '13.5px', color: '#171151', boxSizing: 'border-box' }}
                />
              </div>
            </div>

            {/* Subject Line */}
            <div style={{ marginBottom: '16px' }}>
              <label style={{ display: 'block', fontSize: '12px', fontWeight: 700, color: '#475569', marginBottom: '6px' }}>
                Subject Line
              </label>
              <input
                type="text"
                value={subject}
                onChange={(e) => setSubject(e.target.value)}
                placeholder="Enter compelling email subject line..."
                style={{ width: '100%', padding: '11px 14px', borderRadius: '6px', border: '1px solid #D1D6E0', fontSize: '14px', fontWeight: 600, color: '#171151', boxSizing: 'border-box' }}
              />
            </div>

            {/* Message Body (Or Custom HTML) */}
            {selectedTemplate !== 'custom' ? (
              <>
                <div style={{ marginBottom: '16px' }}>
                  <label style={{ display: 'block', fontSize: '12px', fontWeight: 700, color: '#475569', marginBottom: '6px' }}>
                    Email Body Content (Formatted paragraphs)
                  </label>
                  <textarea
                    rows={6}
                    value={messageBody}
                    onChange={(e) => setMessageBody(e.target.value)}
                    placeholder="Write your email body content here..."
                    style={{ width: '100%', padding: '12px 14px', borderRadius: '6px', border: '1px solid #D1D6E0', fontSize: '13.5px', lineHeight: '1.6', color: '#171151', boxSizing: 'border-box', fontFamily: 'inherit' }}
                  />
                </div>

                {/* Call to Action Button Builder */}
                <div style={{ backgroundColor: '#F8FAFC', border: '1px solid #E2E8F0', borderRadius: '8px', padding: '16px', marginBottom: '16px' }}>
                  <div style={{ fontSize: '13px', fontWeight: 700, color: '#171151', marginBottom: '12px' }}>
                    🔘 Call To Action (CTA) Button Builder
                  </div>
                  <div style={{ display: 'grid', gridTemplateColumns: '1.2fr 1.5fr 0.8fr', gap: '12px' }}>
                    <div>
                      <label style={{ display: 'block', fontSize: '11px', fontWeight: 700, color: '#64748b', marginBottom: '4px' }}>Button Text</label>
                      <input
                        type="text"
                        value={ctaText}
                        onChange={(e) => setCtaText(e.target.value)}
                        placeholder="e.g. View Products"
                        style={{ width: '100%', padding: '8px 10px', borderRadius: '6px', border: '1px solid #CBD5E1', fontSize: '13px', boxSizing: 'border-box' }}
                      />
                    </div>
                    <div>
                      <label style={{ display: 'block', fontSize: '11px', fontWeight: 700, color: '#64748b', marginBottom: '4px' }}>Target URL</label>
                      <input
                        type="text"
                        value={ctaUrl}
                        onChange={(e) => setCtaUrl(e.target.value)}
                        placeholder="https://..."
                        style={{ width: '100%', padding: '8px 10px', borderRadius: '6px', border: '1px solid #CBD5E1', fontSize: '13px', boxSizing: 'border-box' }}
                      />
                    </div>
                    <div>
                      <label style={{ display: 'block', fontSize: '11px', fontWeight: 700, color: '#64748b', marginBottom: '4px' }}>Color</label>
                      <div style={{ display: 'flex', gap: '6px', alignItems: 'center' }}>
                        {['#0E63FF', '#10D0A1', '#F72A75', '#171151'].map((color) => (
                          <button
                            key={color}
                            type="button"
                            onClick={() => setCtaColor(color)}
                            style={{
                              width: '26px',
                              height: '26px',
                              borderRadius: '50%',
                              backgroundColor: color,
                              border: ctaColor === color ? '2px solid #000' : '2px solid transparent',
                              cursor: 'pointer',
                              padding: 0,
                            }}
                          />
                        ))}
                      </div>
                    </div>
                  </div>
                </div>
              </>
            ) : (
              /* Custom HTML Mode */
              <div style={{ marginBottom: '16px' }}>
                <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '6px' }}>
                  <label style={{ fontSize: '12px', fontWeight: 700, color: '#475569' }}>
                    Raw Custom HTML Code
                  </label>
                  <div>
                    <input
                      type="file"
                      ref={htmlFileInputRef}
                      onChange={handleHtmlFileUpload}
                      accept=".html,.htm"
                      style={{ display: 'none' }}
                    />
                    <button
                      type="button"
                      onClick={() => htmlFileInputRef.current?.click()}
                      style={{ fontSize: '12px', color: '#0E63FF', background: 'transparent', border: 'none', cursor: 'pointer', fontWeight: 600 }}
                    >
                      📁 Upload .html File
                    </button>
                  </div>
                </div>
                <textarea
                  rows={10}
                  value={customHtml}
                  onChange={(e) => setCustomHtml(e.target.value)}
                  placeholder="<!DOCTYPE html><html><body><h1>Hello {email}</h1>...</body></html>"
                  style={{ width: '100%', padding: '12px 14px', borderRadius: '6px', border: '1px solid #D1D6E0', fontSize: '12.5px', fontFamily: 'monospace', color: '#0f172a', boxSizing: 'border-box', backgroundColor: '#0f172a', color: '#38bdf8' }}
                />
              </div>
            )}

            {/* Action Buttons */}
            <div style={{ display: 'flex', gap: '12px', flexWrap: 'wrap', marginTop: '20px' }}>
              <button
                type="button"
                onClick={() => setPreviewModalOpen(true)}
                style={{
                  padding: '12px 20px',
                  backgroundColor: '#F1F5F9',
                  color: '#1E293B',
                  border: '1px solid #CBD5E1',
                  borderRadius: '8px',
                  fontSize: '13.5px',
                  fontWeight: 700,
                  cursor: 'pointer',
                  display: 'inline-flex',
                  alignItems: 'center',
                  gap: '8px',
                }}
              >
                <Eye size={16} />
                <span>Live Device Preview</span>
              </button>

              <button
                type="button"
                onClick={() => setTestEmailModal(true)}
                style={{
                  padding: '12px 20px',
                  backgroundColor: '#EFF6FF',
                  color: '#0E63FF',
                  border: '1px solid #BFDBFE',
                  borderRadius: '8px',
                  fontSize: '13.5px',
                  fontWeight: 700,
                  cursor: 'pointer',
                  display: 'inline-flex',
                  alignItems: 'center',
                  gap: '8px',
                }}
              >
                <Send size={16} />
                <span>Send Test Email</span>
              </button>

              <button
                type="button"
                onClick={() => handleSendEmail(false)}
                disabled={sending}
                style={{
                  flex: 1,
                  minWidth: '200px',
                  padding: '12px 24px',
                  backgroundColor: '#0E63FF',
                  color: '#ffffff',
                  border: 'none',
                  borderRadius: '8px',
                  fontSize: '14px',
                  fontWeight: 700,
                  cursor: sending ? 'not-allowed' : 'pointer',
                  display: 'inline-flex',
                  alignItems: 'center',
                  justifyContent: 'center',
                  gap: '8px',
                  boxShadow: '0 4px 14px rgba(14, 99, 255, 0.3)',
                }}
              >
                {sending ? (
                  <>
                    <RefreshCw size={16} className="spinner" />
                    <span>Dispatching Email Campaign...</span>
                  </>
                ) : (
                  <>
                    <Send size={16} />
                    <span>Send to {recipients.length} Recipients Now</span>
                  </>
                )}
              </button>
            </div>
          </div>
        </div>

        {/* RIGHT COLUMN: Real-Time Live Preview Frame */}
        <div style={{ display: 'flex', flexDirection: 'column', gap: '24px' }}>
          {/* Card: Live Preview Frame */}
          <div style={{ backgroundColor: '#ffffff', borderRadius: '14px', padding: '20px', boxShadow: '0 4px 16px rgba(0,0,0,0.04)', border: '1px solid #ECEEF3', position: 'sticky', top: '90px' }}>
            <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '14px' }}>
              <div style={{ display: 'flex', alignItems: 'center', gap: '8px' }}>
                <Eye size={16} color="#0E63FF" />
                <h3 style={{ fontSize: '15px', fontWeight: 700, margin: 0, color: '#171151' }}>
                  Live Real-Time Preview
                </h3>
              </div>

              {/* Device switcher */}
              <div style={{ display: 'flex', backgroundColor: '#F1F5F9', borderRadius: '6px', padding: '2px' }}>
                <button
                  type="button"
                  onClick={() => setPreviewDevice('desktop')}
                  style={{
                    padding: '4px 8px',
                    borderRadius: '4px',
                    border: 'none',
                    backgroundColor: previewDevice === 'desktop' ? '#ffffff' : 'transparent',
                    color: previewDevice === 'desktop' ? '#0E63FF' : '#64748b',
                    cursor: 'pointer',
                    display: 'flex',
                    alignItems: 'center',
                  }}
                  title="Desktop View"
                >
                  <Monitor size={15} />
                </button>
                <button
                  type="button"
                  onClick={() => setPreviewDevice('mobile')}
                  style={{
                    padding: '4px 8px',
                    borderRadius: '4px',
                    border: 'none',
                    backgroundColor: previewDevice === 'mobile' ? '#ffffff' : 'transparent',
                    color: previewDevice === 'mobile' ? '#0E63FF' : '#64748b',
                    cursor: 'pointer',
                    display: 'flex',
                    alignItems: 'center',
                  }}
                  title="Mobile View"
                >
                  <Smartphone size={15} />
                </button>
              </div>
            </div>

            {/* Simulated Email Frame */}
            <div
              style={{
                width: '100%',
                maxHeight: '640px',
                overflowY: 'auto',
                border: '1px solid #E2E8F0',
                borderRadius: '8px',
                backgroundColor: '#F8FAFC',
                boxSizing: 'border-box',
                maxWidth: previewDevice === 'mobile' ? '340px' : '100%',
                margin: '0 auto',
                transition: 'max-width 0.3s ease',
              }}
            >
              {/* Fake Email Client Bar */}
              <div style={{ padding: '8px 12px', backgroundColor: '#EDEFEF', borderBottom: '1px solid #E2E8F0', fontSize: '11px', color: '#64748b' }}>
                <div><strong>From:</strong> {senderName} &lt;{senderEmail}&gt;</div>
                <div><strong>Subject:</strong> {subject || '(No subject)'}</div>
              </div>

              {/* Injected HTML */}
              <div dangerouslySetInnerHTML={{ __html: getPreviewHtml() }} />
            </div>
          </div>
        </div>
      </div>

      {/* ── 3. CAMPAIGN DISPATCH HISTORY TABLE ── */}
      <div style={{ backgroundColor: '#ffffff', borderRadius: '14px', padding: '24px', boxShadow: '0 4px 16px rgba(0,0,0,0.04)', border: '1px solid #ECEEF3', marginTop: '32px' }}>
        <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '18px' }}>
          <div style={{ display: 'flex', alignItems: 'center', gap: '10px' }}>
            <Clock size={18} color="#0E63FF" />
            <h3 style={{ fontSize: '16px', fontWeight: 700, margin: 0, color: '#171151' }}>
              Broadcast History & Sent Campaigns ({data.campaigns.length})
            </h3>
          </div>
          <button
            type="button"
            onClick={fetchCampaignData}
            style={{ fontSize: '12px', color: '#0E63FF', background: 'transparent', border: 'none', cursor: 'pointer', fontWeight: 600, display: 'flex', alignItems: 'center', gap: '4px' }}
          >
            <RefreshCw size={13} /> Refresh Logs
          </button>
        </div>

        {data.campaigns.length === 0 ? (
          <div style={{ padding: '36px', textAlign: 'center', color: '#94a3b8', backgroundColor: '#F8FAFC', borderRadius: '8px', border: '1px dashed #CBD5E1' }}>
            <Mail size={32} style={{ marginBottom: '8px', opacity: 0.5 }} />
            <div style={{ fontSize: '14px', fontWeight: 600 }}>No email campaigns dispatched yet.</div>
            <div style={{ fontSize: '12px' }}>Use the composer above to launch your first broadcast!</div>
          </div>
        ) : (
          <div style={{ overflowX: 'auto' }}>
            <table style={{ width: '100%', borderCollapse: 'collapse', fontSize: '13px' }}>
              <thead>
                <tr style={{ backgroundColor: '#F8FAFC', borderBottom: '2px solid #E2E8F0', textAlign: 'left', color: '#475569' }}>
                  <th style={{ padding: '12px 16px', fontWeight: 700 }}>Subject & Message</th>
                  <th style={{ padding: '12px 16px', fontWeight: 700 }}>Template</th>
                  <th style={{ padding: '12px 16px', fontWeight: 700 }}>Recipients</th>
                  <th style={{ padding: '12px 16px', fontWeight: 700 }}>Status</th>
                  <th style={{ padding: '12px 16px', fontWeight: 700 }}>Sent Date</th>
                </tr>
              </thead>
              <tbody>
                {data.campaigns.map((camp) => (
                  <tr key={camp.id} style={{ borderBottom: '1px solid #ECEEF3' }}>
                    <td style={{ padding: '14px 16px', color: '#171151', fontWeight: 600 }}>
                      <div>{camp.subject}</div>
                      {camp.ctaText && (
                        <span style={{ fontSize: '11px', color: '#0E63FF', fontWeight: 500 }}>
                          Button: {camp.ctaText} &rarr; {camp.ctaUrl}
                        </span>
                      )}
                    </td>
                    <td style={{ padding: '14px 16px' }}>
                      <span style={{ padding: '3px 8px', borderRadius: '10px', backgroundColor: '#EFF6FF', color: '#0E63FF', fontSize: '11.5px', fontWeight: 700, textTransform: 'capitalize' }}>
                        {camp.templateId}
                      </span>
                    </td>
                    <td style={{ padding: '14px 16px', color: '#475569' }}>
                      <strong>{camp.recipientsCount}</strong> recipients
                    </td>
                    <td style={{ padding: '14px 16px' }}>
                      <span
                        style={{
                          padding: '3px 10px',
                          borderRadius: '12px',
                          fontSize: '11.5px',
                          fontWeight: 700,
                          backgroundColor: camp.status === 'Sent' ? '#E7FAF6' : '#FEEAF1',
                          color: camp.status === 'Sent' ? '#0B9748' : '#D92D20',
                        }}
                      >
                        {camp.status}
                      </span>
                    </td>
                    <td style={{ padding: '14px 16px', color: '#64748b', fontSize: '12px' }}>
                      {new Date(camp.sentAt).toLocaleString()}
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        )}
      </div>

      {/* ── 4. FULLSCREEN PREVIEW MODAL ── */}
      {previewModalOpen && (
        <div
          style={{
            position: 'fixed',
            inset: 0,
            backgroundColor: 'rgba(15, 23, 42, 0.75)',
            zIndex: 9999,
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'center',
            padding: '20px',
            backdropFilter: 'blur(4px)',
          }}
        >
          <div
            style={{
              backgroundColor: '#ffffff',
              borderRadius: '16px',
              width: '100%',
              maxWidth: '850px',
              maxHeight: '90vh',
              display: 'flex',
              flexDirection: 'column',
              overflow: 'hidden',
              boxShadow: '0 20px 40px rgba(0,0,0,0.3)',
            }}
          >
            {/* Modal Header */}
            <div style={{ padding: '18px 24px', borderBottom: '1px solid #ECEEF3', display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
              <div style={{ display: 'flex', alignItems: 'center', gap: '10px' }}>
                <Eye size={18} color="#0E63FF" />
                <h3 style={{ fontSize: '16px', fontWeight: 700, margin: 0, color: '#171151' }}>
                  Live Device Email Preview
                </h3>
              </div>
              <button
                type="button"
                onClick={() => setPreviewModalOpen(false)}
                style={{ background: 'transparent', border: 'none', color: '#64748b', cursor: 'pointer', padding: '4px' }}
              >
                <X size={20} />
              </button>
            </div>

            {/* Modal Body */}
            <div style={{ flex: 1, overflowY: 'auto', backgroundColor: '#F8FAFC', padding: '20px' }}>
              <div dangerouslySetInnerHTML={{ __html: getPreviewHtml() }} />
            </div>

            {/* Modal Footer */}
            <div style={{ padding: '16px 24px', borderTop: '1px solid #ECEEF3', display: 'flex', justifyContent: 'flex-end', gap: '10px' }}>
              <button
                type="button"
                onClick={() => setPreviewModalOpen(false)}
                style={{ padding: '10px 18px', backgroundColor: '#F1F5F9', color: '#475569', border: 'none', borderRadius: '6px', fontWeight: 600, cursor: 'pointer' }}
              >
                Close Preview
              </button>
            </div>
          </div>
        </div>
      )}

      {/* ── 5. TEST EMAIL MODAL ── */}
      {testEmailModal && (
        <div
          style={{
            position: 'fixed',
            inset: 0,
            backgroundColor: 'rgba(15, 23, 42, 0.75)',
            zIndex: 9999,
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'center',
            padding: '20px',
            backdropFilter: 'blur(4px)',
          }}
        >
          <div
            style={{
              backgroundColor: '#ffffff',
              borderRadius: '14px',
              width: '100%',
              maxWidth: '480px',
              padding: '24px',
              boxShadow: '0 20px 40px rgba(0,0,0,0.3)',
            }}
          >
            <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '14px' }}>
              <h3 style={{ fontSize: '16px', fontWeight: 700, margin: 0, color: '#171151' }}>
                Send Single Test Email
              </h3>
              <button
                type="button"
                onClick={() => setTestEmailModal(false)}
                style={{ background: 'transparent', border: 'none', color: '#64748b', cursor: 'pointer', padding: '4px' }}
              >
                <X size={18} />
              </button>
            </div>

            <p style={{ fontSize: '13px', color: '#64748b', marginTop: 0, marginBottom: '16px', lineHeight: '1.5' }}>
              Send a real test email of your configured template to verify exactly how it looks in your personal email inbox before sending to all recipients.
            </p>

            <div style={{ marginBottom: '18px' }}>
              <label style={{ display: 'block', fontSize: '12px', fontWeight: 700, color: '#475569', marginBottom: '6px' }}>
                Your Email Address
              </label>
              <input
                type="email"
                value={testEmailAddress}
                onChange={(e) => setTestEmailAddress(e.target.value)}
                placeholder="admin@example.com"
                style={{ width: '100%', padding: '10px 12px', borderRadius: '6px', border: '1px solid #CBD5E1', fontSize: '14px', boxSizing: 'border-box' }}
              />
            </div>

            <div style={{ display: 'flex', justifyContent: 'flex-end', gap: '10px' }}>
              <button
                type="button"
                onClick={() => setTestEmailModal(false)}
                style={{ padding: '9px 16px', backgroundColor: '#F1F5F9', color: '#475569', border: 'none', borderRadius: '6px', fontWeight: 600, cursor: 'pointer' }}
              >
                Cancel
              </button>
              <button
                type="button"
                onClick={() => handleSendEmail(true)}
                disabled={sending}
                style={{
                  padding: '9px 18px',
                  backgroundColor: '#0E63FF',
                  color: '#ffffff',
                  border: 'none',
                  borderRadius: '6px',
                  fontWeight: 700,
                  cursor: sending ? 'not-allowed' : 'pointer',
                  display: 'inline-flex',
                  alignItems: 'center',
                  gap: '6px',
                }}
              >
                {sending ? 'Sending...' : 'Send Test Now'}
              </button>
            </div>
          </div>
        </div>
      )}
    </div>
  );
}
