'use client';

import React, { useState, useEffect, useRef, useCallback } from 'react';
import {
  MessageSquare,
  Search,
  Send,
  User,
  Phone,
  MapPin,
  Clock,
  CheckCircle2,
  AlertCircle,
  Trash2,
  RefreshCw,
  Sparkles,
  Volume2,
  VolumeX,
  CheckCheck,
  RotateCcw,
  Zap,
  ArrowLeft,
  LayoutGrid,
  Columns,
  MessageCircle,
  ChevronRight,
  ExternalLink,
} from 'lucide-react';

const QUICK_REPLIES = [
  'Welcome to Innotech Medical! How can we assist you with our diagnostic and surgical equipment today?',
  'Thank you for contacting us. Our senior product specialist will review your request and call you shortly.',
  'Could you please specify the equipment model, clinical application, or laboratory requirement?',
  'We offer nationwide delivery, installation, warranty, and 24/7 biomedical engineering support across Pakistan.',
  'Our team has noted your contact information. Is there any specific hospital or clinic this is for?',
  'Thank you for chatting with Innotech Medical. Feel free to contact us anytime!',
];

export default function AdminSupportChatPage() {
  const [sessions, setSessions] = useState([]);
  const [selectedSessionId, setSelectedSessionId] = useState(null);
  const [activeSession, setActiveSession] = useState(null);
  const [filterStatus, setFilterStatus] = useState('all'); // 'all' | 'unread' | 'active' | 'resolved'
  const [searchQuery, setSearchQuery] = useState('');
  const [inputText, setInputText] = useState('');
  const [sending, setSending] = useState(false);
  const [loading, setLoading] = useState(true);
  const [stats, setStats] = useState({ total: 0, unread: 0, active: 0, resolved: 0 });
  const [soundEnabled, setSoundEnabled] = useState(true);
  const [refreshing, setRefreshing] = useState(false);
  const [deleteModalOpen, setDeleteModalOpen] = useState(false);
  const [sessionToDelete, setSessionToDelete] = useState(null);

  const messagesEndRef = useRef(null);
  const prevMessagesCountRef = useRef(0);
  const audioRef = useRef(null);

  // Initialize audio chime for new messages
  useEffect(() => {
    try {
      audioRef.current = new Audio('https://assets.mixkit.co/active_storage/sfx/2869/2869-preview.mp3');
    } catch (e) {}
  }, []);

  const playNotificationSound = useCallback(() => {
    if (soundEnabled && audioRef.current) {
      audioRef.current.play().catch(() => {});
    }
  }, [soundEnabled]);

  // Fetch all chat sessions
  const fetchSessions = useCallback(
    async (isManual = false) => {
      if (isManual) setRefreshing(true);
      try {
        const params = new URLSearchParams();
        if (filterStatus !== 'all') params.append('status', filterStatus);
        if (searchQuery) params.append('search', searchQuery);

        const res = await fetch(`/api/admin/chat?${params.toString()}`);
        const data = await res.json();

        if (data.success) {
          setSessions(data.sessions || []);
          if (data.stats) setStats(data.stats);

          // If there is an active session selected, update its state
          if (selectedSessionId) {
            const current = (data.sessions || []).find((s) => s.sessionId === selectedSessionId);
            if (current) {
              const prevCount = prevMessagesCountRef.current;
              const newCount = current.messages ? current.messages.length : 0;
              if (newCount > prevCount && prevCount > 0) {
                const lastMsg = current.messages[current.messages.length - 1];
                if (lastMsg.sender === 'user') {
                  playNotificationSound();
                }
              }
              prevMessagesCountRef.current = newCount;
              setActiveSession(current);
            }
          }
        }
      } catch (err) {
        console.error('Error fetching chat sessions:', err);
      } finally {
        setLoading(false);
        if (isManual) setRefreshing(false);
      }
    },
    [filterStatus, searchQuery, selectedSessionId, playNotificationSound]
  );

  // Initial load and polling every 2.5 seconds
  useEffect(() => {
    fetchSessions();
    const interval = setInterval(() => {
      fetchSessions(false);
    }, 2500);

    return () => clearInterval(interval);
  }, [fetchSessions]);

  // Scroll to bottom when active session messages change
  useEffect(() => {
    if (selectedSessionId && messagesEndRef.current) {
      messagesEndRef.current.scrollIntoView({ behavior: 'smooth' });
    }
  }, [activeSession?.messages, selectedSessionId]);

  // Open a specific user chat from grid
  const handleOpenChat = async (session) => {
    setSelectedSessionId(session.sessionId);
    setActiveSession(session);
    prevMessagesCountRef.current = session.messages ? session.messages.length : 0;

    if (session.unreadAdminCount > 0) {
      try {
        await fetch('/api/admin/chat', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ action: 'mark_read', sessionId: session.sessionId }),
        });

        // Notify layout to update badge
        window.dispatchEvent(new CustomEvent('innotech_notifications_updated'));

        setSessions((prev) =>
          prev.map((s) => (s.sessionId === session.sessionId ? { ...s, unreadAdminCount: 0 } : s))
        );
        setStats((prev) => ({
          ...prev,
          unread: Math.max(0, prev.unread - (session.unreadAdminCount || 0)),
        }));
      } catch (e) {}
    }
  };

  // Back to User Grid view
  const handleBackToGrid = () => {
    setSelectedSessionId(null);
    setActiveSession(null);
  };

  // Send admin reply
  const handleSendMessage = async (e) => {
    if (e) e.preventDefault();
    if (!inputText.trim() || !selectedSessionId || sending) return;

    const text = inputText.trim();
    setInputText('');
    setSending(true);

    try {
      const res = await fetch('/api/admin/chat', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          action: 'reply',
          sessionId: selectedSessionId,
          messageText: text,
          senderName: 'Innotech Support Team',
        }),
      });

      const data = await res.json();
      if (data.success && data.newMessage) {
        setActiveSession((prev) => {
          if (!prev) return prev;
          const updatedMessages = [...(prev.messages || []), data.newMessage];
          return {
            ...prev,
            messages: updatedMessages,
            lastMessage: text,
            lastMessageAt: new Date(),
          };
        });

        setSessions((prev) =>
          prev.map((s) =>
            s.sessionId === selectedSessionId
              ? {
                  ...s,
                  lastMessage: text,
                  lastMessageAt: new Date(),
                  messages: [...(s.messages || []), data.newMessage],
                }
              : s
          )
        );
      }
    } catch (err) {
      console.error('Error sending reply:', err);
    } finally {
      setSending(false);
    }
  };

  // Change conversation status (active <-> resolved)
  const handleToggleStatus = async () => {
    if (!activeSession) return;
    const newStatus = activeSession.status === 'resolved' ? 'active' : 'resolved';

    try {
      const res = await fetch('/api/admin/chat', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          action: 'set_status',
          sessionId: activeSession.sessionId,
          newStatus,
        }),
      });

      if (res.ok) {
        setActiveSession((prev) => ({ ...prev, status: newStatus }));
        setSessions((prev) =>
          prev.map((s) => (s.sessionId === activeSession.sessionId ? { ...s, status: newStatus } : s))
        );
        setStats((prev) => ({
          ...prev,
          active: newStatus === 'active' ? prev.active + 1 : Math.max(0, prev.active - 1),
          resolved: newStatus === 'resolved' ? prev.resolved + 1 : Math.max(0, prev.resolved - 1),
        }));
      }
    } catch (e) {}
  };

  // Confirm delete
  const handleDeleteSession = async () => {
    if (!sessionToDelete) return;

    try {
      const res = await fetch(`/api/admin/chat?sessionId=${sessionToDelete.sessionId}`, {
        method: 'DELETE',
      });

      if (res.ok) {
        const remaining = sessions.filter((s) => s.sessionId !== sessionToDelete.sessionId);
        setSessions(remaining);
        if (selectedSessionId === sessionToDelete.sessionId) {
          setSelectedSessionId(null);
          setActiveSession(null);
        }
        setDeleteModalOpen(false);
        setSessionToDelete(null);
        window.dispatchEvent(new CustomEvent('innotech_notifications_updated'));
      }
    } catch (e) {}
  };

  // Format timestamp helper
  const formatTime = (dateStr) => {
    if (!dateStr) return '';
    const date = new Date(dateStr);
    return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
  };

  const formatDateLabel = (dateStr) => {
    if (!dateStr) return '';
    const date = new Date(dateStr);
    const today = new Date();
    if (date.toDateString() === today.toDateString()) {
      return `Today, ${formatTime(dateStr)}`;
    }
    return date.toLocaleDateString([], { month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' });
  };

  const getAvatarColor = (name) => {
    const colors = ['#0E63FF', '#10B981', '#8B5CF6', '#F59E0B', '#EC4899', '#3B82F6', '#14B8A6'];
    const charCode = name ? name.charCodeAt(0) : 0;
    return colors[charCode % colors.length];
  };

  return (
    <div style={{ maxWidth: '1440px', margin: '0 auto', display: 'flex', flexDirection: 'column', gap: '20px', minHeight: 'calc(100vh - 120px)' }}>
      {/* ── Top Header & Metrics Bar ── */}
      <div
        style={{
          display: 'flex',
          justifyContent: 'space-between',
          alignItems: 'center',
          flexWrap: 'wrap',
          gap: '15px',
          backgroundColor: '#ffffff',
          padding: '16px 24px',
          borderRadius: '12px',
          border: '1px solid #ECEEF3',
          boxShadow: '0 2px 10px rgba(0,0,0,0.02)',
        }}
      >
        <div style={{ display: 'flex', alignItems: 'center', gap: '12px' }}>
          {selectedSessionId && (
            <button
              onClick={handleBackToGrid}
              style={{
                display: 'inline-flex',
                alignItems: 'center',
                gap: '6px',
                padding: '8px 14px',
                backgroundColor: '#EEF2FF',
                color: '#0E63FF',
                border: '1px solid #C7D2FE',
                borderRadius: '8px',
                fontWeight: '700',
                fontSize: '13px',
                cursor: 'pointer',
                transition: 'all 0.2s',
              }}
            >
              <ArrowLeft size={16} />
              <span>Back to Users Grid</span>
            </button>
          )}

          <div
            style={{
              width: '42px',
              height: '42px',
              borderRadius: '10px',
              backgroundColor: '#EEF2FF',
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'center',
              color: '#0E63FF',
            }}
          >
            <MessageSquare size={22} />
          </div>
          <div>
            <h1 style={{ fontSize: '20px', fontWeight: '800', color: '#171151', margin: '0 0 2px' }}>
              {selectedSessionId && activeSession
                ? `Live Chat with ${activeSession.userName}`
                : 'Real-Time Support Chat Console'}
            </h1>
            <p style={{ fontSize: '12px', color: '#64748B', margin: 0 }}>
              {selectedSessionId
                ? `${activeSession?.userCity || ''} • ${activeSession?.userPhone || ''}`
                : 'View customer inquiries grid • Click on any user card to start chatting in real time'}
            </p>
          </div>
        </div>

        {/* Live Counters & Tools */}
        <div style={{ display: 'flex', alignItems: 'center', gap: '10px', flexWrap: 'wrap' }}>
          <div
            style={{
              padding: '6px 14px',
              borderRadius: '8px',
              backgroundColor: '#F8FAFC',
              border: '1px solid #E2E8F0',
              fontSize: '12px',
              fontWeight: '700',
              color: '#475569',
              display: 'flex',
              alignItems: 'center',
              gap: '6px',
            }}
          >
            <span>Total Users:</span>
            <span style={{ color: '#171151', fontSize: '13px' }}>{stats.total}</span>
          </div>

          <div
            style={{
              padding: '6px 14px',
              borderRadius: '8px',
              backgroundColor: stats.unread > 0 ? '#FEE2E2' : '#F8FAFC',
              border: `1px solid ${stats.unread > 0 ? '#FECACA' : '#E2E8F0'}`,
              fontSize: '12px',
              fontWeight: '700',
              color: stats.unread > 0 ? '#EF4444' : '#475569',
              display: 'flex',
              alignItems: 'center',
              gap: '6px',
            }}
          >
            {stats.unread > 0 && (
              <span
                style={{
                  width: '8px',
                  height: '8px',
                  borderRadius: '50%',
                  backgroundColor: '#EF4444',
                  animation: 'pulse 1.5s infinite',
                }}
              />
            )}
            <span>New Messages:</span>
            <span style={{ fontSize: '13px' }}>{stats.unread}</span>
          </div>

          <div
            style={{
              padding: '6px 14px',
              borderRadius: '8px',
              backgroundColor: '#ECFDF5',
              border: '1px solid #A7F3D0',
              fontSize: '12px',
              fontWeight: '700',
              color: '#059669',
              display: 'flex',
              alignItems: 'center',
              gap: '6px',
            }}
          >
            <span>Active:</span>
            <span style={{ fontSize: '13px' }}>{stats.active}</span>
          </div>

          {/* Sound Toggle */}
          <button
            onClick={() => setSoundEnabled(!soundEnabled)}
            title={soundEnabled ? 'Mute audio alerts' : 'Enable audio alerts'}
            style={{
              padding: '8px 12px',
              backgroundColor: soundEnabled ? '#EEF2FF' : '#F1F5F9',
              color: soundEnabled ? '#0E63FF' : '#94A3B8',
              border: '1px solid #E2E8F0',
              borderRadius: '8px',
              cursor: 'pointer',
              display: 'flex',
              alignItems: 'center',
              gap: '6px',
              fontSize: '12px',
              fontWeight: '600',
            }}
          >
            {soundEnabled ? <Volume2 size={16} /> : <VolumeX size={16} />}
            <span className="d-none d-sm-inline">{soundEnabled ? 'Audio On' : 'Muted'}</span>
          </button>

          {/* Sync Button */}
          <button
            onClick={() => fetchSessions(true)}
            disabled={refreshing}
            style={{
              padding: '8px 14px',
              backgroundColor: '#0E63FF',
              color: '#ffffff',
              border: 'none',
              borderRadius: '8px',
              cursor: 'pointer',
              display: 'flex',
              alignItems: 'center',
              gap: '6px',
              fontSize: '12px',
              fontWeight: '600',
            }}
          >
            <RefreshCw size={14} className={refreshing ? 'spin-anim' : ''} />
            <span>Sync</span>
          </button>
        </div>
      </div>

      {/* ═══════════════════════════════════════════════════════════════
          VIEW 1: USERS CHAT GRID (When no conversation is open)
         ═══════════════════════════════════════════════════════════════ */}
      {!selectedSessionId ? (
        <div style={{ display: 'flex', flexDirection: 'column', gap: '16px' }}>
          {/* Filter & Search Bar */}
          <div
            style={{
              backgroundColor: '#ffffff',
              padding: '16px 20px',
              borderRadius: '12px',
              border: '1px solid #ECEEF3',
              display: 'flex',
              justifyContent: 'space-between',
              alignItems: 'center',
              flexWrap: 'wrap',
              gap: '12px',
            }}
          >
            {/* Filter Tabs */}
            <div style={{ display: 'flex', gap: '8px', flexWrap: 'wrap' }}>
              {[
                { id: 'all', label: 'All Users', count: stats.total },
                { id: 'unread', label: 'New Unread Messages', count: stats.unread, highlight: true },
                { id: 'active', label: 'Active Chats', count: stats.active },
                { id: 'resolved', label: 'Resolved', count: stats.resolved },
              ].map((f) => {
                const isActive = filterStatus === f.id;
                return (
                  <button
                    key={f.id}
                    onClick={() => setFilterStatus(f.id)}
                    style={{
                      padding: '8px 16px',
                      borderRadius: '8px',
                      border: 'none',
                      fontSize: '13px',
                      fontWeight: '700',
                      cursor: 'pointer',
                      backgroundColor: isActive ? '#0E63FF' : '#F1F5F9',
                      color: isActive ? '#ffffff' : '#475569',
                      transition: 'all 0.15s',
                      display: 'flex',
                      alignItems: 'center',
                      gap: '6px',
                    }}
                  >
                    <span>{f.label}</span>
                    {f.count !== undefined && (
                      <span
                        style={{
                          padding: '2px 7px',
                          borderRadius: '10px',
                          backgroundColor: isActive ? '#ffffff' : f.highlight && f.count > 0 ? '#EF4444' : '#E2E8F0',
                          color: isActive ? '#0E63FF' : f.highlight && f.count > 0 ? '#ffffff' : '#475569',
                          fontSize: '11px',
                          fontWeight: '800',
                        }}
                      >
                        {f.count}
                      </span>
                    )}
                  </button>
                );
              })}
            </div>

            {/* Search Input */}
            <div style={{ position: 'relative', minWidth: '280px', flex: '1', maxWidth: '380px' }}>
              <Search
                size={16}
                color="#94A3B8"
                style={{ position: 'absolute', left: '12px', top: '50%', transform: 'translateY(-50%)' }}
              />
              <input
                type="text"
                value={searchQuery}
                onChange={(e) => setSearchQuery(e.target.value)}
                placeholder="Search user name, phone, city..."
                style={{
                  width: '100%',
                  padding: '9px 12px 9px 36px',
                  borderRadius: '8px',
                  border: '1px solid #D1D6E0',
                  fontSize: '13px',
                  boxSizing: 'border-box',
                  backgroundColor: '#ffffff',
                  outline: 'none',
                }}
              />
            </div>
          </div>

          {/* Grid of User Cards */}
          {loading ? (
            <div style={{ padding: '60px 20px', textAlign: 'center', backgroundColor: '#ffffff', borderRadius: '12px' }}>
              <div className="spinner-border text-primary mb-3" style={{ width: '2.5rem', height: '2.5rem' }} role="status"></div>
              <div style={{ fontSize: '14px', fontWeight: '600', color: '#64748B' }}>Loading user conversations grid...</div>
            </div>
          ) : sessions.length === 0 ? (
            <div
              style={{
                padding: '60px 20px',
                textAlign: 'center',
                backgroundColor: '#ffffff',
                borderRadius: '12px',
                border: '1px solid #ECEEF3',
              }}
            >
              <div
                style={{
                  width: '64px',
                  height: '64px',
                  borderRadius: '50%',
                  backgroundColor: '#EEF2FF',
                  color: '#0E63FF',
                  display: 'flex',
                  alignItems: 'center',
                  justifyContent: 'center',
                  margin: '0 auto 16px',
                }}
              >
                <MessageSquare size={30} />
              </div>
              <h3 style={{ fontSize: '18px', fontWeight: '800', color: '#171151', margin: '0 0 6px' }}>
                No Support Conversations Found
              </h3>
              <p style={{ fontSize: '13px', color: '#64748B', maxWidth: '420px', margin: '0 auto' }}>
                When website visitors start a chat on the website, their cards will show up in this live grid.
              </p>
            </div>
          ) : (
            <div
              style={{
                display: 'grid',
                gridTemplateColumns: 'repeat(auto-fill, minmax(360px, 1fr))',
                gap: '20px',
              }}
            >
              {sessions.map((session) => {
                const hasUnread = (session.unreadAdminCount || 0) > 0;
                const avatarColor = getAvatarColor(session.userName);
                const firstLetter = session.userName ? session.userName.charAt(0).toUpperCase() : 'U';

                return (
                  <div
                    key={session.sessionId}
                    onClick={() => handleOpenChat(session)}
                    style={{
                      backgroundColor: '#ffffff',
                      borderRadius: '14px',
                      border: hasUnread ? '2px solid #0E63FF' : '1px solid #ECEEF3',
                      boxShadow: hasUnread
                        ? '0 8px 24px rgba(14, 99, 255, 0.12)'
                        : '0 4px 14px rgba(0,0,0,0.03)',
                      padding: '20px',
                      display: 'flex',
                      flexDirection: 'column',
                      justifyContent: 'space-between',
                      cursor: 'pointer',
                      transition: 'all 0.2s cubic-bezier(0.16, 1, 0.3, 1)',
                      position: 'relative',
                    }}
                    onMouseEnter={(e) => {
                      e.currentTarget.style.transform = 'translateY(-3px)';
                      e.currentTarget.style.boxShadow = '0 12px 28px rgba(23, 17, 81, 0.1)';
                    }}
                    onMouseLeave={(e) => {
                      e.currentTarget.style.transform = 'translateY(0)';
                      e.currentTarget.style.boxShadow = hasUnread
                        ? '0 8px 24px rgba(14, 99, 255, 0.12)'
                        : '0 4px 14px rgba(0,0,0,0.03)';
                    }}
                  >
                    {/* Top Row: Avatar, Name, Status & Unread Badge */}
                    <div>
                      <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'flex-start', marginBottom: '14px' }}>
                        <div style={{ display: 'flex', alignItems: 'center', gap: '12px' }}>
                          <div
                            style={{
                              width: '48px',
                              height: '48px',
                              borderRadius: '50%',
                              backgroundColor: avatarColor,
                              color: '#ffffff',
                              display: 'flex',
                              alignItems: 'center',
                              justifyContent: 'center',
                              fontWeight: '800',
                              fontSize: '18px',
                              flexShrink: 0,
                              boxShadow: `0 4px 10px ${avatarColor}40`,
                            }}
                          >
                            {firstLetter}
                          </div>

                          <div>
                            <h3 style={{ fontSize: '16px', fontWeight: '800', color: '#171151', margin: '0 0 3px' }}>
                              {session.userName}
                            </h3>
                            <div style={{ display: 'flex', alignItems: 'center', gap: '8px' }}>
                              <span
                                style={{
                                  fontSize: '11px',
                                  padding: '2px 8px',
                                  borderRadius: '10px',
                                  fontWeight: '700',
                                  backgroundColor: session.status === 'resolved' ? '#DCFCE7' : '#EFF6FF',
                                  color: session.status === 'resolved' ? '#166534' : '#1D4ED8',
                                }}
                              >
                                {session.status === 'resolved' ? '✓ Resolved' : '● Live Active'}
                              </span>
                            </div>
                          </div>
                        </div>

                        {/* Unread Alert Badge */}
                        {hasUnread && (
                          <div
                            style={{
                              padding: '4px 10px',
                              borderRadius: '14px',
                              backgroundColor: '#EF4444',
                              color: '#ffffff',
                              fontSize: '11px',
                              fontWeight: '800',
                              display: 'flex',
                              alignItems: 'center',
                              gap: '4px',
                              boxShadow: '0 3px 8px rgba(239, 68, 68, 0.4)',
                              animation: 'pulse 2s infinite',
                            }}
                          >
                            <Sparkles size={12} />
                            <span>{session.unreadAdminCount} New</span>
                          </div>
                        )}
                      </div>

                      {/* User Info Chips (Phone & City) */}
                      <div
                        style={{
                          display: 'flex',
                          alignItems: 'center',
                          gap: '10px',
                          padding: '8px 12px',
                          backgroundColor: '#F8FAFC',
                          borderRadius: '8px',
                          border: '1px solid #E2E8F0',
                          marginBottom: '14px',
                          flexWrap: 'wrap',
                        }}
                      >
                        <div style={{ display: 'flex', alignItems: 'center', gap: '5px', fontSize: '12px', color: '#0E63FF', fontWeight: '700' }}>
                          <Phone size={13} />
                          <span>{session.userPhone}</span>
                        </div>
                        <span style={{ color: '#CBD5E1' }}>•</span>
                        <div style={{ display: 'flex', alignItems: 'center', gap: '4px', fontSize: '12px', color: '#475569', fontWeight: '600' }}>
                          <MapPin size={13} color="#64748B" />
                          <span>{session.userCity}</span>
                        </div>
                      </div>

                      {/* Last Message Preview Bubble */}
                      <div
                        style={{
                          backgroundColor: hasUnread ? '#EFF6FF' : '#FAFCFF',
                          padding: '12px 14px',
                          borderRadius: '10px',
                          border: `1px solid ${hasUnread ? '#BFDBFE' : '#ECEEF3'}`,
                          marginBottom: '16px',
                        }}
                      >
                        <div style={{ fontSize: '11px', color: '#94A3B8', fontWeight: '600', marginBottom: '4px' }}>
                          Last Message:
                        </div>
                        <div
                          style={{
                            fontSize: '13px',
                            color: hasUnread ? '#1E293B' : '#475569',
                            fontWeight: hasUnread ? '700' : '400',
                            lineHeight: '1.4',
                            display: '-webkit-box',
                            WebkitLineClamp: 2,
                            WebkitBoxOrient: 'vertical',
                            overflow: 'hidden',
                          }}
                        >
                          {session.lastMessage || 'Conversation started'}
                        </div>
                      </div>
                    </div>

                    {/* Bottom Row: Time & Open Chat Button */}
                    <div
                      style={{
                        display: 'flex',
                        alignItems: 'center',
                        justifyContent: 'space-between',
                        paddingTop: '12px',
                        borderTop: '1px solid #ECEEF3',
                      }}
                    >
                      <span style={{ fontSize: '11px', color: '#94A3B8', display: 'flex', alignItems: 'center', gap: '4px' }}>
                        <Clock size={12} /> {formatDateLabel(session.lastMessageAt || session.createdAt)}
                      </span>

                      <button
                        type="button"
                        onClick={(e) => {
                          e.stopPropagation();
                          handleOpenChat(session);
                        }}
                        style={{
                          padding: '8px 16px',
                          borderRadius: '8px',
                          backgroundColor: hasUnread ? '#0E63FF' : '#171151',
                          color: '#ffffff',
                          border: 'none',
                          fontSize: '12px',
                          fontWeight: '700',
                          cursor: 'pointer',
                          display: 'flex',
                          alignItems: 'center',
                          gap: '6px',
                          boxShadow: hasUnread ? '0 4px 12px rgba(14, 99, 255, 0.3)' : 'none',
                          transition: 'all 0.2s',
                        }}
                      >
                        <MessageSquare size={13} />
                        <span>Chat Now</span>
                        <ChevronRight size={13} />
                      </button>
                    </div>
                  </div>
                );
              })}
            </div>
          )}
        </div>
      ) : (
        /* ═══════════════════════════════════════════════════════════════
            VIEW 2: DEDICATED LIVE CHAT WINDOW (Opened when card clicked)
           ═══════════════════════════════════════════════════════════════ */
        <div
          style={{
            backgroundColor: '#ffffff',
            borderRadius: '14px',
            border: '1px solid #ECEEF3',
            boxShadow: '0 6px 20px rgba(0,0,0,0.04)',
            display: 'flex',
            flexDirection: 'column',
            height: 'calc(100vh - 220px)',
            overflow: 'hidden',
          }}
        >
          {/* Active Conversation Header */}
          <div
            style={{
              padding: '16px 24px',
              borderBottom: '1px solid #ECEEF3',
              display: 'flex',
              justifyContent: 'space-between',
              alignItems: 'center',
              backgroundColor: '#FAFCFF',
              flexWrap: 'wrap',
              gap: '12px',
            }}
          >
            <div style={{ display: 'flex', alignItems: 'center', gap: '14px' }}>
              <div
                style={{
                  width: '46px',
                  height: '46px',
                  borderRadius: '50%',
                  backgroundColor: getAvatarColor(activeSession?.userName),
                  color: '#ffffff',
                  display: 'flex',
                  alignItems: 'center',
                  justifyContent: 'center',
                  fontWeight: '800',
                  fontSize: '18px',
                }}
              >
                {activeSession?.userName?.charAt(0).toUpperCase() || 'U'}
              </div>

              <div>
                <div style={{ display: 'flex', alignItems: 'center', gap: '8px' }}>
                  <h2 style={{ fontSize: '17px', fontWeight: '800', color: '#171151', margin: 0 }}>
                    {activeSession?.userName}
                  </h2>
                  <span
                    style={{
                      fontSize: '11px',
                      padding: '2px 8px',
                      borderRadius: '12px',
                      fontWeight: '700',
                      backgroundColor: activeSession?.status === 'resolved' ? '#DCFCE7' : '#EFF6FF',
                      color: activeSession?.status === 'resolved' ? '#166534' : '#1D4ED8',
                    }}
                  >
                    {activeSession?.status === 'resolved' ? '✓ Resolved' : '● Live Active'}
                  </span>
                </div>

                <div style={{ display: 'flex', alignItems: 'center', gap: '14px', marginTop: '4px', fontSize: '12px', color: '#64748B' }}>
                  <a
                    href={`tel:${activeSession?.userPhone}`}
                    style={{ color: '#0E63FF', fontWeight: '700', textDecoration: 'none', display: 'flex', alignItems: 'center', gap: '4px' }}
                  >
                    <Phone size={12} /> {activeSession?.userPhone}
                  </a>
                  <span style={{ display: 'flex', alignItems: 'center', gap: '3px' }}>
                    <MapPin size={12} /> {activeSession?.userCity}
                  </span>
                  <span style={{ display: 'flex', alignItems: 'center', gap: '3px', color: '#94A3B8' }}>
                    <Clock size={12} /> Session ID: {activeSession?.sessionId}
                  </span>
                </div>
              </div>
            </div>

            {/* Action Buttons */}
            <div style={{ display: 'flex', alignItems: 'center', gap: '8px' }}>
              <button
                onClick={handleToggleStatus}
                style={{
                  padding: '8px 14px',
                  borderRadius: '8px',
                  border: '1px solid #D1D6E0',
                  backgroundColor: activeSession?.status === 'resolved' ? '#FEF3C7' : '#ECFDF5',
                  color: activeSession?.status === 'resolved' ? '#92400E' : '#047857',
                  fontWeight: '700',
                  fontSize: '12px',
                  cursor: 'pointer',
                  display: 'flex',
                  alignItems: 'center',
                  gap: '6px',
                }}
              >
                {activeSession?.status === 'resolved' ? <RotateCcw size={14} /> : <CheckCircle2 size={14} />}
                <span>{activeSession?.status === 'resolved' ? 'Reopen Conversation' : 'Mark as Resolved'}</span>
              </button>

              <button
                onClick={() => {
                  setSessionToDelete(activeSession);
                  setDeleteModalOpen(true);
                }}
                title="Delete conversation"
                style={{
                  padding: '8px 12px',
                  borderRadius: '8px',
                  border: '1px solid #FECACA',
                  backgroundColor: '#FEF2F2',
                  color: '#DC2626',
                  fontWeight: '600',
                  fontSize: '12px',
                  cursor: 'pointer',
                  display: 'flex',
                  alignItems: 'center',
                  gap: '4px',
                }}
              >
                <Trash2 size={14} />
                <span>Delete</span>
              </button>

              <button
                onClick={handleBackToGrid}
                style={{
                  padding: '8px 14px',
                  borderRadius: '8px',
                  border: '1px solid #D1D6E0',
                  backgroundColor: '#ffffff',
                  color: '#171151',
                  fontWeight: '700',
                  fontSize: '12px',
                  cursor: 'pointer',
                  display: 'flex',
                  alignItems: 'center',
                  gap: '6px',
                }}
              >
                <LayoutGrid size={14} />
                <span>Grid View</span>
              </button>
            </div>
          </div>

          {/* Message Stream */}
          <div
            style={{
              flex: 1,
              overflowY: 'auto',
              padding: '24px',
              backgroundColor: '#F8FAFC',
              display: 'flex',
              flexDirection: 'column',
              gap: '14px',
            }}
          >
            {activeSession?.messages && activeSession.messages.length > 0 ? (
              activeSession.messages.map((msg, index) => {
                const isUser = msg.sender === 'user';
                const isSystem = msg.sender === 'system';

                if (isSystem) {
                  return (
                    <div
                      key={msg.id || index}
                      style={{
                        alignSelf: 'center',
                        maxWidth: '85%',
                        backgroundColor: '#EEF2FF',
                        border: '1px solid #C7D2FE',
                        color: '#3730A3',
                        padding: '10px 18px',
                        borderRadius: '20px',
                        fontSize: '12px',
                        textAlign: 'center',
                        boxShadow: '0 1px 4px rgba(0,0,0,0.03)',
                      }}
                    >
                      <div style={{ fontWeight: '700', marginBottom: '2px', display: 'flex', alignItems: 'center', justifyContent: 'center', gap: '4px' }}>
                        <Sparkles size={13} color="#4F46E5" />{' '}
                        {msg.id?.includes('autoreply')
                          ? 'Automated Auto-Reply'
                          : msg.id?.includes('welcome')
                          ? 'Automated Greeting'
                          : msg.senderName || 'System Notice'}
                      </div>
                      <div>{msg.text}</div>
                      <div style={{ fontSize: '10px', color: '#6B7280', marginTop: '4px' }}>{formatTime(msg.timestamp)}</div>
                    </div>
                  );
                }

                return (
                  <div
                    key={msg.id || index}
                    style={{
                      display: 'flex',
                      flexDirection: 'column',
                      alignItems: isUser ? 'flex-start' : 'flex-end',
                      maxWidth: '75%',
                      alignSelf: isUser ? 'flex-start' : 'flex-end',
                    }}
                  >
                    <div style={{ fontSize: '11px', fontWeight: '700', color: isUser ? '#0E63FF' : '#171151', marginBottom: '4px' }}>
                      {isUser ? activeSession.userName : 'Innotech Support Team'}
                    </div>

                    <div
                      style={{
                        padding: '12px 18px',
                        borderRadius: isUser ? '4px 18px 18px 18px' : '18px 4px 18px 18px',
                        backgroundColor: isUser ? '#ffffff' : '#0E63FF',
                        color: isUser ? '#171151' : '#ffffff',
                        fontSize: '14px',
                        lineHeight: '1.5',
                        border: isUser ? '1px solid #E2E8F0' : 'none',
                        boxShadow: isUser ? '0 2px 8px rgba(0,0,0,0.04)' : '0 4px 14px rgba(14,99,255,0.25)',
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
                        marginTop: '4px',
                        display: 'flex',
                        alignItems: 'center',
                        gap: '4px',
                      }}
                    >
                      <span>{formatTime(msg.timestamp)}</span>
                      {!isUser && (
                        <span title="Delivered">
                          <CheckCheck size={13} color="#0E63FF" />
                        </span>
                      )}
                    </div>
                  </div>
                );
              })
            ) : (
              <div style={{ textAlign: 'center', color: '#94A3B8', margin: 'auto' }}>
                No messages yet in this session.
              </div>
            )}
            <div ref={messagesEndRef} />
          </div>

          {/* Quick Replies Picker */}
          <div
            style={{
              padding: '8px 16px',
              backgroundColor: '#ffffff',
              borderTop: '1px solid #ECEEF3',
              overflowX: 'auto',
              whiteSpace: 'nowrap',
              display: 'flex',
              gap: '8px',
            }}
          >
            <span style={{ fontSize: '11px', fontWeight: '700', color: '#64748B', display: 'flex', alignItems: 'center', gap: '4px', flexShrink: 0 }}>
              <Zap size={13} color="#0E63FF" /> Quick Templates:
            </span>
            {QUICK_REPLIES.map((reply, i) => (
              <button
                key={i}
                type="button"
                onClick={() => setInputText(reply)}
                style={{
                  padding: '4px 10px',
                  borderRadius: '14px',
                  border: '1px solid #E2E8F0',
                  backgroundColor: '#F8FAFC',
                  color: '#334155',
                  fontSize: '11px',
                  cursor: 'pointer',
                  flexShrink: 0,
                  maxWidth: '240px',
                  overflow: 'hidden',
                  textOverflow: 'ellipsis',
                  whiteSpace: 'nowrap',
                }}
                title={reply}
              >
                {reply}
              </button>
            ))}
          </div>

          {/* Message Input Box */}
          <form
            onSubmit={handleSendMessage}
            style={{
              padding: '16px 20px',
              borderTop: '1px solid #ECEEF3',
              backgroundColor: '#ffffff',
              display: 'flex',
              gap: '12px',
              alignItems: 'center',
            }}
          >
            <input
              type="text"
              value={inputText}
              onChange={(e) => setInputText(e.target.value)}
              placeholder={`Reply to ${activeSession?.userName}... (Press Enter to send)`}
              disabled={sending}
              style={{
                flex: 1,
                padding: '13px 18px',
                borderRadius: '10px',
                border: '1.5px solid #D1D6E0',
                fontSize: '14px',
                outline: 'none',
                color: '#171151',
              }}
            />

            <button
              type="submit"
              disabled={sending || !inputText.trim()}
              style={{
                padding: '13px 24px',
                backgroundColor: inputText.trim() ? '#0E63FF' : '#CBD5E1',
                color: '#ffffff',
                border: 'none',
                borderRadius: '10px',
                fontSize: '14px',
                fontWeight: '700',
                cursor: inputText.trim() ? 'pointer' : 'not-allowed',
                display: 'flex',
                alignItems: 'center',
                gap: '8px',
                boxShadow: inputText.trim() ? '0 4px 14px rgba(14,99,255,0.3)' : 'none',
                transition: 'all 0.2s',
              }}
            >
              <span>{sending ? 'Sending...' : 'Send'}</span>
              <Send size={16} />
            </button>
          </form>
        </div>
      )}

      {/* ── Delete Confirmation Modal ── */}
      {deleteModalOpen && sessionToDelete && (
        <div
          style={{
            position: 'fixed',
            inset: 0,
            backgroundColor: 'rgba(0,0,0,0.5)',
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'center',
            zIndex: 999,
            padding: '20px',
          }}
        >
          <div
            style={{
              backgroundColor: '#ffffff',
              borderRadius: '14px',
              padding: '24px',
              maxWidth: '420px',
              width: '100%',
              boxShadow: '0 20px 40px rgba(0,0,0,0.2)',
            }}
          >
            <div style={{ display: 'flex', alignItems: 'center', gap: '12px', marginBottom: '14px' }}>
              <div
                style={{
                  width: '42px',
                  height: '42px',
                  borderRadius: '50%',
                  backgroundColor: '#FEE2E2',
                  color: '#DC2626',
                  display: 'flex',
                  alignItems: 'center',
                  justifyContent: 'center',
                }}
              >
                <Trash2 size={20} />
              </div>
              <h3 style={{ fontSize: '16px', fontWeight: '800', color: '#171151', margin: 0 }}>
                Delete Chat Conversation?
              </h3>
            </div>

            <p style={{ fontSize: '13px', color: '#64748B', lineHeight: '1.5', margin: '0 0 20px' }}>
              Are you sure you want to permanently delete the conversation with{' '}
              <strong>{sessionToDelete.userName}</strong> ({sessionToDelete.userPhone})? This action cannot be undone.
            </p>

            <div style={{ display: 'flex', justifyContent: 'flex-end', gap: '10px' }}>
              <button
                type="button"
                onClick={() => {
                  setDeleteModalOpen(false);
                  setSessionToDelete(null);
                }}
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
                onClick={handleDeleteSession}
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
                Yes, Delete
              </button>
            </div>
          </div>
        </div>
      )}

      <style jsx global>{`
        @keyframes pulse {
          0% {
            opacity: 1;
            transform: scale(1);
          }
          50% {
            opacity: 0.4;
            transform: scale(1.2);
          }
          100% {
            opacity: 1;
            transform: scale(1);
          }
        }
        .spin-anim {
          animation: spin 1s linear infinite;
        }
        @keyframes spin {
          from {
            transform: rotate(0deg);
          }
          to {
            transform: rotate(360deg);
          }
        }
      `}</style>
    </div>
  );
}
