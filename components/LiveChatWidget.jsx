'use client';

import React, { useState, useEffect, useRef, useCallback } from 'react';
import {
  MessageSquare,
  X,
  Send,
  User,
  Phone,
  MapPin,
  RotateCcw,
  Sparkles,
  CheckCheck,
  Minimize2,
  AlertCircle,
  HelpCircle,
} from 'lucide-react';

export default function LiveChatWidget() {
  const [isOpen, setIsOpen] = useState(false);
  const [siteConfig, setSiteConfig] = useState(null);
  const [sessionId, setSessionId] = useState(null);
  const [sessionData, setSessionData] = useState(null);
  const [messages, setMessages] = useState([]);
  const [inputText, setInputText] = useState('');
  const [sending, setSending] = useState(false);
  const [submittingForm, setSubmittingForm] = useState(false);
  const [unreadUserCount, setUnreadUserCount] = useState(0);
  const [showTeaser, setShowTeaser] = useState(true);
  const [showResetModal, setShowResetModal] = useState(false);

  // Intake form state
  const [formData, setFormData] = useState({
    name: '',
    phone: '',
    city: '',
    initialMessage: '',
  });
  const [formErrors, setFormErrors] = useState({});

  const messagesEndRef = useRef(null);
  const isFirstMount = useRef(true);

  // 1. Fetch site config for chat and whatsapp settings
  useEffect(() => {
    fetch('/api/admin/site-config')
      .then((res) => res.json())
      .then((data) => {
        if (data.data) {
          setSiteConfig(data.data);
        }
      })
      .catch((e) => console.error('Error fetching site config for chat:', e));

    // Check if previous session exists in localStorage
    if (typeof window !== 'undefined') {
      const savedSessionId = localStorage.getItem('innotech_chat_session_id');
      if (savedSessionId) {
        setSessionId(savedSessionId);
      }
    }
  }, []);

  // 2. Fetch session details when sessionId is present
  const fetchSessionMessages = useCallback(async () => {
    if (!sessionId) return;
    try {
      const res = await fetch(`/api/chat/session?sessionId=${sessionId}`);
      const data = await res.json();
      if (data.success && data.session) {
        setSessionData(data.session);
        setMessages(data.session.messages || []);
        if (!isOpen && data.session.unreadUserCount > 0) {
          setUnreadUserCount(data.session.unreadUserCount);
        } else if (isOpen) {
          setUnreadUserCount(0);
        }
      }
    } catch (e) {
      console.error('Error polling chat messages:', e);
    }
  }, [sessionId, isOpen]);

  // Polling for live messages when session is active
  useEffect(() => {
    if (!sessionId) return;

    fetchSessionMessages();
    const interval = setInterval(fetchSessionMessages, 2500);

    return () => clearInterval(interval);
  }, [sessionId, fetchSessionMessages]);

  // Scroll to bottom when messages update
  useEffect(() => {
    if (isOpen && messagesEndRef.current) {
      messagesEndRef.current.scrollIntoView({ behavior: isFirstMount.current ? 'auto' : 'smooth' });
      isFirstMount.current = false;
    }
  }, [messages, isOpen]);

  // Clear unread count on chat open
  const handleOpenChat = () => {
    setIsOpen(true);
    setShowTeaser(false);
    setUnreadUserCount(0);
  };

  // Handle registration form submit
  const handleStartChat = async (e) => {
    e.preventDefault();
    const errors = {};
    if (!formData.name.trim()) errors.name = 'Please enter your full name';
    if (!formData.phone.trim()) errors.phone = 'Please enter your phone number';
    if (!formData.city.trim()) errors.city = 'Please enter your city';

    if (Object.keys(errors).length > 0) {
      setFormErrors(errors);
      return;
    }

    setSubmittingForm(true);
    setFormErrors({});

    try {
      const res = await fetch('/api/chat/session', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          userName: formData.name.trim(),
          userPhone: formData.phone.trim(),
          userCity: formData.city.trim(),
          initialMessage: formData.initialMessage.trim(),
        }),
      });

      const data = await res.json();
      if (data.success && data.sessionId) {
        setSessionId(data.sessionId);
        setSessionData(data.session);
        setMessages(data.session.messages || []);
        localStorage.setItem('innotech_chat_session_id', data.sessionId);
        localStorage.setItem('innotech_chat_user_name', formData.name.trim());
      } else {
        alert(data.message || 'Failed to start chat. Please try again.');
      }
    } catch (err) {
      alert('Network error. Please try again.');
    } finally {
      setSubmittingForm(false);
    }
  };

  // Send message
  const handleSendMessage = async (e) => {
    if (e) e.preventDefault();
    if (!inputText.trim() || !sessionId || sending) return;

    const text = inputText.trim();
    setInputText('');
    setSending(true);

    const userName = sessionData?.userName || localStorage.getItem('innotech_chat_user_name') || 'Visitor';

    const optimisticMsg = {
      id: 'temp_' + Date.now(),
      sender: 'user',
      senderName: userName,
      text: text,
      timestamp: new Date(),
      read: false,
    };

    setMessages((prev) => [...prev, optimisticMsg]);

    try {
      const res = await fetch('/api/chat/messages', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          sessionId,
          text,
          sender: 'user',
          senderName: userName,
        }),
      });

      const data = await res.json();
      if (data.success) {
        if (data.session && Array.isArray(data.session.messages)) {
          setSessionData(data.session);
          setMessages(data.session.messages);
        } else if (data.newMessage) {
          setMessages((prev) => {
            const updated = prev.map((m) => (m.id === optimisticMsg.id ? data.newMessage : m));
            if (data.autoReplyMessage) {
              return [...updated, data.autoReplyMessage];
            }
            return updated;
          });
        }
      }
    } catch (err) {
      console.error('Error sending message:', err);
    } finally {
      setSending(false);
    }
  };

  // Reset conversation
  const handleResetChat = async () => {
    if (!sessionId) return;
    try {
      await fetch('/api/chat/reset', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ sessionId }),
      });
    } catch (e) {}

    localStorage.removeItem('innotech_chat_session_id');
    localStorage.removeItem('innotech_chat_user_name');
    setSessionId(null);
    setSessionData(null);
    setMessages([]);
    setFormData({ name: '', phone: '', city: '', initialMessage: '' });
    setShowResetModal(false);
  };

  // WhatsApp click handler
  const handleWhatsAppClick = () => {
    const rawNumber = siteConfig?.whatsappWidget?.phoneNumber || '+92 331 6699992';
    const cleanNumber = rawNumber.replace(/[^0-9]/g, '');
    const defaultMsg =
      siteConfig?.whatsappWidget?.defaultMessage ||
      'Hello Innotech Medical, I would like to inquire about your medical equipment and solutions.';

    const url = `https://wa.me/${cleanNumber}?text=${encodeURIComponent(defaultMsg)}`;
    window.open(url, '_blank', 'noopener,noreferrer');
  };

  const chatConfig = siteConfig?.chatWidget || {};
  const whatsappConfig = siteConfig?.whatsappWidget || {};

  const isChatEnabled = chatConfig.enabled !== false;
  const isWhatsAppEnabled = whatsappConfig.enabled !== false;

  if (!isChatEnabled && !isWhatsAppEnabled) {
    return null;
  }

  const formatTime = (dateStr) => {
    if (!dateStr) return '';
    const date = new Date(dateStr);
    return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
  };

  return (
    <>
      {/* ── FLOATING BUTTONS CONTAINER (BOTTOM RIGHT) ── */}
      <div
        style={{
          position: 'fixed',
          bottom: '24px',
          right: '24px',
          zIndex: 9999,
          display: 'flex',
          flexDirection: 'column',
          alignItems: 'flex-end',
          gap: '12px',
          fontFamily: "'Archivo', sans-serif",
        }}
      >
        {/* Floating WhatsApp Button (Visible only when chat window is closed) */}
        {isWhatsAppEnabled && !isOpen && (
          <div style={{ position: 'relative' }} className="whatsapp-floating-wrap">
            <button
              onClick={handleWhatsAppClick}
              aria-label="Chat on WhatsApp"
              style={{
                width: '54px',
                height: '54px',
                borderRadius: '50%',
                backgroundColor: '#25D366',
                border: 'none',
                color: '#ffffff',
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'center',
                cursor: 'pointer',
                boxShadow: '0 6px 18px rgba(37, 211, 102, 0.4)',
                transition: 'all 0.25s cubic-bezier(0.175, 0.885, 0.32, 1.275)',
              }}
              onMouseEnter={(e) => {
                e.currentTarget.style.transform = 'scale(1.1)';
                e.currentTarget.style.boxShadow = '0 8px 24px rgba(37, 211, 102, 0.55)';
              }}
              onMouseLeave={(e) => {
                e.currentTarget.style.transform = 'scale(1)';
                e.currentTarget.style.boxShadow = '0 6px 18px rgba(37, 211, 102, 0.4)';
              }}
            >
              {/* WhatsApp SVG Icon */}
              <svg width="30" height="30" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.664-.698c.969.53 1.954.813 2.796.813h.005c3.18 0 5.767-2.586 5.768-5.766 0-1.542-.6-2.991-1.691-4.081-1.09-1.09-2.539-1.69-4.082-1.69l-.001-.029zm3.435 8.196c-.147.414-.736.782-1.037.834-.302.052-.693.076-1.127-.063-.268-.087-.616-.207-1.066-.402-1.898-.826-3.136-2.757-3.232-2.884-.094-.127-.768-1.021-.768-1.947 0-.926.486-1.381.659-1.57.172-.189.376-.236.502-.236.126 0 .252.002.361.008.117.006.273-.044.426.326.157.377.534 1.303.581 1.398.047.095.079.206.016.332-.063.126-.095.205-.189.315-.094.111-.198.247-.283.332-.095.094-.194.197-.083.388.111.189.493.813 1.057 1.316.726.647 1.339.847 1.528.942.189.095.301.079.414-.047.114-.127.487-.566.617-.76.13-.194.26-.162.439-.095.179.068 1.137.536 1.332.634.195.098.326.147.373.228.047.081.047.47-.1 884z" />
                <path d="M12 2C6.477 2 2 6.477 2 12c0 1.891.524 3.661 1.434 5.178L2 22l4.981-1.306A9.957 9.957 0 0 0 12 22c5.523 0 10-4.477 10-10S17.523 2 12 2zm0 18.25c-1.636 0-3.167-.477-4.462-1.3l-.32-.204-2.964.777.791-2.889-.224-.356A8.196 8.196 0 0 1 3.75 12c0-4.549 3.701-8.25 8.25-8.25s8.25 3.701 8.25 8.25-3.701 8.25-8.25 8.25z" />
              </svg>
            </button>
            <div className="whatsapp-tooltip">
              {whatsappConfig.tooltipText || 'Chat with us on WhatsApp'}
            </div>
          </div>
        )}

        {/* Live Chat Floating Button & Teaser */}
        {isChatEnabled && (
          <div style={{ position: 'relative' }}>
            {/* Teaser notification bubble */}
            {showTeaser && !isOpen && (
              <div
                style={{
                  position: 'absolute',
                  bottom: '70px',
                  right: '0',
                  backgroundColor: '#ffffff',
                  color: '#171151',
                  padding: '12px 16px',
                  borderRadius: '14px',
                  boxShadow: '0 10px 30px rgba(0,0,0,0.15)',
                  border: '1px solid #E2E8F0',
                  fontSize: '13px',
                  fontWeight: '600',
                  width: '240px',
                  lineHeight: '1.4',
                  animation: 'fadeInUp 0.3s ease',
                  zIndex: 99,
                }}
              >
                <button
                  onClick={() => setShowTeaser(false)}
                  style={{
                    position: 'absolute',
                    top: '6px',
                    right: '6px',
                    background: 'transparent',
                    border: 'none',
                    color: '#94A3B8',
                    cursor: 'pointer',
                    padding: '2px',
                  }}
                >
                  <X size={14} />
                </button>
                <div style={{ display: 'flex', alignItems: 'center', gap: '6px', marginBottom: '4px', color: '#0E63FF', fontWeight: '700' }}>
                  <Sparkles size={14} /> Live Chat Support
                </div>
                <div>Have questions about medical equipment? Let&apos;s chat!</div>
              </div>
            )}

            {/* Chat Trigger Button */}
            <button
              onClick={() => (isOpen ? setIsOpen(false) : handleOpenChat())}
              aria-label="Open live chat"
              style={{
                width: '60px',
                height: '60px',
                borderRadius: '50%',
                backgroundColor: isOpen ? '#171151' : '#0E63FF',
                color: '#ffffff',
                border: 'none',
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'center',
                cursor: 'pointer',
                boxShadow: isOpen ? '0 6px 20px rgba(23, 17, 81, 0.4)' : '0 8px 24px rgba(14, 99, 255, 0.45)',
                transition: 'all 0.25s cubic-bezier(0.175, 0.885, 0.32, 1.275)',
                position: 'relative',
              }}
              onMouseEnter={(e) => {
                e.currentTarget.style.transform = 'scale(1.08)';
              }}
              onMouseLeave={(e) => {
                e.currentTarget.style.transform = 'scale(1)';
              }}
            >
              {isOpen ? <X size={26} /> : <MessageSquare size={26} />}

              {/* Unread badge */}
              {unreadUserCount > 0 && !isOpen && (
                <div
                  style={{
                    position: 'absolute',
                    top: '-2px',
                    right: '-2px',
                    width: '22px',
                    height: '22px',
                    borderRadius: '50%',
                    backgroundColor: '#EF4444',
                    color: '#ffffff',
                    fontSize: '11px',
                    fontWeight: '800',
                    display: 'flex',
                    alignItems: 'center',
                    justifyContent: 'center',
                    boxShadow: '0 2px 6px rgba(239, 68, 68, 0.6)',
                  }}
                >
                  {unreadUserCount}
                </div>
              )}
            </button>
          </div>
        )}
      </div>

      {/* ── CHAT WINDOW PANEL ── */}
      {isOpen && isChatEnabled && (
        <div
          style={{
            position: 'fixed',
            bottom: '96px',
            right: '24px',
            width: '390px',
            maxWidth: 'calc(100vw - 32px)',
            height: '600px',
            maxHeight: 'calc(100vh - 120px)',
            backgroundColor: '#ffffff',
            borderRadius: '18px',
            boxShadow: '0 16px 45px rgba(23, 17, 81, 0.2)',
            border: '1px solid #ECEEF3',
            zIndex: 9998,
            display: 'flex',
            flexDirection: 'column',
            overflow: 'hidden',
            fontFamily: "'Archivo', sans-serif",
            animation: 'chatOpenAnim 0.25s cubic-bezier(0.16, 1, 0.3, 1)',
          }}
        >
          {/* Header */}
          <div
            style={{
              padding: '16px 20px',
              backgroundColor: '#171151',
              color: '#ffffff',
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'space-between',
              boxShadow: '0 2px 10px rgba(0,0,0,0.1)',
            }}
          >
            <div style={{ display: 'flex', alignItems: 'center', gap: '12px' }}>
              <div
                style={{
                  width: '38px',
                  height: '38px',
                  borderRadius: '50%',
                  backgroundColor: '#0E63FF',
                  display: 'flex',
                  alignItems: 'center',
                  justifyContent: 'center',
                  fontWeight: '800',
                  fontSize: '16px',
                  color: '#ffffff',
                  position: 'relative',
                  border: '2px solid rgba(255,255,255,0.2)',
                }}
              >
                <img
                  src={chatConfig.agentAvatar || '/assets/img/logo/favicon.png'}
                  alt="Innotech Support"
                  style={{ width: '22px', height: '22px', objectFit: 'contain' }}
                  onError={(e) => {
                    e.currentTarget.style.display = 'none';
                  }}
                />
                <span
                  style={{
                    position: 'absolute',
                    bottom: '0',
                    right: '0',
                    width: '10px',
                    height: '10px',
                    borderRadius: '50%',
                    backgroundColor: '#10D0A1',
                    border: '2px solid #171151',
                  }}
                />
              </div>

              <div>
                <h4 style={{ fontSize: '15px', fontWeight: '800', margin: '0 0 2px', color: '#ffffff' }}>
                  {chatConfig.title || 'Innotech Live Support'}
                </h4>
                <div style={{ fontSize: '11px', color: '#10D0A1', display: 'flex', alignItems: 'center', gap: '4px', fontWeight: '600' }}>
                  <span style={{ width: '6px', height: '6px', borderRadius: '50%', backgroundColor: '#10D0A1' }} />
                  {chatConfig.subtitle || 'Typically replies within minutes'}
                </div>
              </div>
            </div>

            {/* Header Action Buttons */}
            <div style={{ display: 'flex', alignItems: 'center', gap: '6px' }}>
              {sessionId && (
                <button
                  onClick={() => setShowResetModal(true)}
                  title="Reset & Start New Chat"
                  style={{
                    background: 'rgba(255,255,255,0.1)',
                    border: 'none',
                    color: '#ffffff',
                    borderRadius: '6px',
                    padding: '6px 8px',
                    cursor: 'pointer',
                    display: 'flex',
                    alignItems: 'center',
                    gap: '4px',
                    fontSize: '11px',
                    fontWeight: '600',
                  }}
                >
                  <RotateCcw size={13} />
                  <span>Reset</span>
                </button>
              )}

              <button
                onClick={() => setIsOpen(false)}
                title="Minimize Chat"
                style={{
                  background: 'transparent',
                  border: 'none',
                  color: '#ffffff',
                  cursor: 'pointer',
                  padding: '4px',
                  display: 'flex',
                  alignItems: 'center',
                }}
              >
                <X size={18} />
              </button>
            </div>
          </div>

          {/* ── VIEW 1: REGISTRATION / INTAKE FORM (When no session active) ── */}
          {!sessionId ? (
            <div
              style={{
                flex: 1,
                padding: '24px',
                overflowY: 'auto',
                backgroundColor: '#FAFCFF',
                display: 'flex',
                flexDirection: 'column',
              }}
            >
              <div style={{ textAlign: 'center', marginBottom: '20px' }}>
                <div
                  style={{
                    width: '52px',
                    height: '52px',
                    borderRadius: '50%',
                    backgroundColor: '#EEF2FF',
                    color: '#0E63FF',
                    display: 'flex',
                    alignItems: 'center',
                    justifyContent: 'center',
                    margin: '0 auto 12px',
                  }}
                >
                  <MessageSquare size={26} />
                </div>
                <h3 style={{ fontSize: '18px', fontWeight: '800', color: '#171151', margin: '0 0 6px' }}>
                  Start Live Conversation
                </h3>
                <p style={{ fontSize: '13px', color: '#64748B', margin: 0, lineHeight: '1.4' }}>
                  Please share your contact details below so our biomedical team can assist you directly.
                </p>
              </div>

              <form onSubmit={handleStartChat} style={{ display: 'flex', flexDirection: 'column', gap: '14px' }}>
                {/* Full Name */}
                <div>
                  <label style={{ display: 'block', fontSize: '12px', fontWeight: '700', color: '#171151', marginBottom: '5px' }}>
                    Full Name <span style={{ color: '#EF4444' }}>*</span>
                  </label>
                  <div style={{ position: 'relative' }}>
                    <User size={15} color="#94A3B8" style={{ position: 'absolute', left: '12px', top: '50%', transform: 'translateY(-50%)' }} />
                    <input
                      type="text"
                      value={formData.name}
                      onChange={(e) => setFormData({ ...formData, name: e.target.value })}
                      placeholder="e.g. Dr. Salman Khan"
                      style={{
                        width: '100%',
                        padding: '11px 12px 11px 36px',
                        borderRadius: '8px',
                        border: `1.5px solid ${formErrors.name ? '#EF4444' : '#D1D6E0'}`,
                        fontSize: '13px',
                        boxSizing: 'border-box',
                        outline: 'none',
                      }}
                    />
                  </div>
                  {formErrors.name && <span style={{ fontSize: '11px', color: '#EF4444', marginTop: '2px', display: 'block' }}>{formErrors.name}</span>}
                </div>

                {/* Phone Number */}
                <div>
                  <label style={{ display: 'block', fontSize: '12px', fontWeight: '700', color: '#171151', marginBottom: '5px' }}>
                    Phone / WhatsApp Number <span style={{ color: '#EF4444' }}>*</span>
                  </label>
                  <div style={{ position: 'relative' }}>
                    <Phone size={15} color="#94A3B8" style={{ position: 'absolute', left: '12px', top: '50%', transform: 'translateY(-50%)' }} />
                    <input
                      type="tel"
                      value={formData.phone}
                      onChange={(e) => setFormData({ ...formData, phone: e.target.value })}
                      placeholder="e.g. 0331-6699992"
                      style={{
                        width: '100%',
                        padding: '11px 12px 11px 36px',
                        borderRadius: '8px',
                        border: `1.5px solid ${formErrors.phone ? '#EF4444' : '#D1D6E0'}`,
                        fontSize: '13px',
                        boxSizing: 'border-box',
                        outline: 'none',
                      }}
                    />
                  </div>
                  {formErrors.phone && <span style={{ fontSize: '11px', color: '#EF4444', marginTop: '2px', display: 'block' }}>{formErrors.phone}</span>}
                </div>

                {/* City */}
                <div>
                  <label style={{ display: 'block', fontSize: '12px', fontWeight: '700', color: '#171151', marginBottom: '5px' }}>
                    City <span style={{ color: '#EF4444' }}>*</span>
                  </label>
                  <div style={{ position: 'relative' }}>
                    <MapPin size={15} color="#94A3B8" style={{ position: 'absolute', left: '12px', top: '50%', transform: 'translateY(-50%)' }} />
                    <input
                      type="text"
                      value={formData.city}
                      onChange={(e) => setFormData({ ...formData, city: e.target.value })}
                      placeholder="e.g. Karachi, Lahore, Islamabad"
                      style={{
                        width: '100%',
                        padding: '11px 12px 11px 36px',
                        borderRadius: '8px',
                        border: `1.5px solid ${formErrors.city ? '#EF4444' : '#D1D6E0'}`,
                        fontSize: '13px',
                        boxSizing: 'border-box',
                        outline: 'none',
                      }}
                    />
                  </div>
                  {formErrors.city && <span style={{ fontSize: '11px', color: '#EF4444', marginTop: '2px', display: 'block' }}>{formErrors.city}</span>}
                </div>

                {/* Optional First Message */}
                <div>
                  <label style={{ display: 'block', fontSize: '12px', fontWeight: '700', color: '#171151', marginBottom: '5px' }}>
                    How can we help you? (Optional)
                  </label>
                  <textarea
                    rows={2}
                    value={formData.initialMessage}
                    onChange={(e) => setFormData({ ...formData, initialMessage: e.target.value })}
                    placeholder="e.g. Inquiring about ICU Ventilators or Hematology Analyzers..."
                    style={{
                      width: '100%',
                      padding: '10px 12px',
                      borderRadius: '8px',
                      border: '1.5px solid #D1D6E0',
                      fontSize: '13px',
                      boxSizing: 'border-box',
                      outline: 'none',
                    }}
                  />
                </div>

                <button
                  type="submit"
                  disabled={submittingForm}
                  style={{
                    marginTop: '8px',
                    padding: '13px',
                    borderRadius: '8px',
                    backgroundColor: '#0E63FF',
                    color: '#ffffff',
                    border: 'none',
                    fontWeight: '700',
                    fontSize: '14px',
                    cursor: submittingForm ? 'not-allowed' : 'pointer',
                    boxShadow: '0 4px 14px rgba(14, 99, 255, 0.35)',
                    display: 'flex',
                    alignItems: 'center',
                    justifyContent: 'center',
                    gap: '8px',
                  }}
                >
                  <span>{submittingForm ? 'Connecting to Support...' : 'Start Chat Session'}</span>
                  <Send size={16} />
                </button>
              </form>
            </div>
          ) : (
            /* ── VIEW 2: ACTIVE LIVE CHAT CONVERSATION ── */
            <div style={{ flex: 1, display: 'flex', flexDirection: 'column', minHeight: 0 }}>
              {/* Message Stream */}
              <div
                style={{
                  flex: 1,
                  overflowY: 'auto',
                  padding: '16px',
                  backgroundColor: '#F8FAFC',
                  display: 'flex',
                  flexDirection: 'column',
                  gap: '12px',
                }}
              >
                {messages.map((msg, index) => {
                  const isUser = msg.sender === 'user';
                  const senderDisplayName = isUser
                    ? 'You'
                    : msg.senderName || chatConfig.agentName || 'Innotech Support Team';

                  return (
                    <div
                      key={msg.id || index}
                      style={{
                        display: 'flex',
                        flexDirection: 'column',
                        alignItems: isUser ? 'flex-end' : 'flex-start',
                        maxWidth: '82%',
                        alignSelf: isUser ? 'flex-end' : 'flex-start',
                      }}
                    >
                      <div
                        style={{
                          fontSize: '11px',
                          fontWeight: '700',
                          color: isUser ? '#64748B' : '#0E63FF',
                          marginBottom: '3px',
                          padding: '0 4px',
                        }}
                      >
                        {senderDisplayName}
                      </div>

                      <div
                        style={{
                          padding: '11px 15px',
                          borderRadius: isUser ? '16px 4px 16px 16px' : '4px 16px 16px 16px',
                          backgroundColor: isUser ? '#0E63FF' : '#ffffff',
                          color: isUser ? '#ffffff' : '#171151',
                          fontSize: '13.5px',
                          lineHeight: '1.45',
                          boxShadow: isUser ? '0 2px 8px rgba(14,99,255,0.25)' : '0 2px 8px rgba(0,0,0,0.04)',
                          border: isUser ? 'none' : '1px solid #E2E8F0',
                          wordBreak: 'break-word',
                          whiteSpace: 'pre-wrap',
                        }}
                      >
                        {msg.text}
                      </div>

                      <div
                        style={{
                          fontSize: '10px',
                          color: '#94A3B8',
                          marginTop: '3px',
                          padding: '0 4px',
                          display: 'flex',
                          alignItems: 'center',
                          gap: '3px',
                        }}
                      >
                        <span>{formatTime(msg.timestamp)}</span>
                        {isUser && <CheckCheck size={12} color="#0E63FF" />}
                      </div>
                    </div>
                  );
                })}
                <div ref={messagesEndRef} />
              </div>

              {/* Message Input Form */}
              <form
                onSubmit={handleSendMessage}
                style={{
                  padding: '12px 14px',
                  backgroundColor: '#ffffff',
                  borderTop: '1px solid #ECEEF3',
                  display: 'flex',
                  gap: '8px',
                  alignItems: 'center',
                }}
              >
                <input
                  type="text"
                  value={inputText}
                  onChange={(e) => setInputText(e.target.value)}
                  placeholder="Type a message..."
                  disabled={sending}
                  style={{
                    flex: 1,
                    padding: '10px 14px',
                    borderRadius: '8px',
                    border: '1.5px solid #D1D6E0',
                    fontSize: '13px',
                    outline: 'none',
                    color: '#171151',
                  }}
                />

                <button
                  type="submit"
                  disabled={sending || !inputText.trim()}
                  style={{
                    width: '40px',
                    height: '40px',
                    borderRadius: '8px',
                    backgroundColor: inputText.trim() ? '#0E63FF' : '#CBD5E1',
                    color: '#ffffff',
                    border: 'none',
                    display: 'flex',
                    alignItems: 'center',
                    justifyContent: 'center',
                    cursor: inputText.trim() ? 'pointer' : 'not-allowed',
                    flexShrink: 0,
                    transition: 'all 0.2s',
                  }}
                >
                  <Send size={16} />
                </button>
              </form>
            </div>
          )}
        </div>
      )}

      {/* ── RESET CONFIRMATION MODAL ── */}
      {showResetModal && (
        <div
          style={{
            position: 'fixed',
            inset: 0,
            backgroundColor: 'rgba(0,0,0,0.5)',
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'center',
            zIndex: 10000,
            padding: '20px',
            fontFamily: "'Archivo', sans-serif",
          }}
        >
          <div
            style={{
              backgroundColor: '#ffffff',
              borderRadius: '14px',
              padding: '22px',
              maxWidth: '360px',
              width: '100%',
              boxShadow: '0 16px 36px rgba(0,0,0,0.2)',
              textAlign: 'center',
            }}
          >
            <div
              style={{
                width: '46px',
                height: '46px',
                borderRadius: '50%',
                backgroundColor: '#FEE2E2',
                color: '#DC2626',
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'center',
                margin: '0 auto 12px',
              }}
            >
              <RotateCcw size={22} />
            </div>

            <h3 style={{ fontSize: '16px', fontWeight: '800', color: '#171151', margin: '0 0 6px' }}>
              Reset Chat Session?
            </h3>
            <p style={{ fontSize: '13px', color: '#64748B', margin: '0 0 20px', lineHeight: '1.4' }}>
              This will end your current conversation and allow you to start a fresh chat with our team.
            </p>

            <div style={{ display: 'flex', gap: '10px', justifyContent: 'center' }}>
              <button
                type="button"
                onClick={() => setShowResetModal(false)}
                style={{
                  padding: '9px 16px',
                  borderRadius: '8px',
                  border: '1px solid #D1D6E0',
                  backgroundColor: '#ffffff',
                  color: '#475569',
                  fontWeight: '600',
                  fontSize: '13px',
                  cursor: 'pointer',
                }}
              >
                Cancel
              </button>
              <button
                type="button"
                onClick={handleResetChat}
                style={{
                  padding: '9px 18px',
                  borderRadius: '8px',
                  border: 'none',
                  backgroundColor: '#DC2626',
                  color: '#ffffff',
                  fontWeight: '700',
                  fontSize: '13px',
                  cursor: 'pointer',
                }}
              >
                Yes, Reset
              </button>
            </div>
          </div>
        </div>
      )}

      <style jsx global>{`
        .whatsapp-floating-wrap {
          position: relative;
        }
        .whatsapp-tooltip {
          position: absolute;
          right: 66px;
          top: 50%;
          transform: translateY(-50%);
          background-color: #171151;
          color: #ffffff;
          padding: 6px 12px;
          border-radius: 6px;
          font-size: 12px;
          font-weight: 600;
          white-space: nowrap;
          box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
          opacity: 0;
          pointer-events: none;
          transition: all 0.2s ease;
        }
        .whatsapp-floating-wrap:hover .whatsapp-tooltip {
          opacity: 1;
          right: 62px;
        }
        @keyframes chatOpenAnim {
          from {
            opacity: 0;
            transform: scale(0.9) translateY(20px);
          }
          to {
            opacity: 1;
            transform: scale(1) translateY(0);
          }
        }
        @keyframes fadeInUp {
          from {
            opacity: 0;
            transform: translateY(10px);
          }
          to {
            opacity: 1;
            transform: translateY(0);
          }
        }
      `}</style>
    </>
  );
}
