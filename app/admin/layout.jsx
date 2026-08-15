'use client';

import React, { useState, useEffect } from 'react';
import Link from 'next/link';
import { usePathname, useRouter } from 'next/navigation';
import {
  LayoutDashboard,
  Home,
  Award,
  PhoneCall,
  FlaskConical,
  BookOpen,
  KeyRound,
  Settings,
  LogOut,
  ExternalLink,
  Menu,
  X,
  ShieldCheck,
  MailCheck,
  Layers,
  MessageSquare,
  Briefcase,
  ChevronDown,
  LayoutTemplate,
  PanelLeftClose,
  PanelLeftOpen,
} from 'lucide-react';

export default function AdminLayout({ children }) {
  const pathname = usePathname() || '';
  const isLoginPage = pathname === '/admin/login';
  const router = useRouter();
  const [sidebarOpen, setSidebarOpen] = useState(false); // Mobile drawer
  const [isCollapsed, setIsCollapsed] = useState(false); // Desktop collapse
  const [isAuth, setIsAuth] = useState(false);
  const [openDropdowns, setOpenDropdowns] = useState({ services: true });
  const [adminUser, setAdminUser] = useState({ name: 'Admin', email: 'admin@innotech.com' });
  const [adminBranding, setAdminBranding] = useState({
    adminName: 'INNOTECH Admin Portal',
    adminLogo: '/assets/img/logo/white-logo.png',
  });
  const [notifications, setNotifications] = useState({
    inquiries: 0,
    pendingComments: 0,
    subscribers: 0,
    chats: 0,
  });

  const toggleDropdown = (id) => {
    setOpenDropdowns((prev) => ({
      ...prev,
      [id]: !prev[id],
    }));
  };

  const fetchBranding = async () => {
    try {
      const res = await fetch('/api/admin/site-config');
      const data = await res.json();
      if (data.data?.generalSettings) {
        setAdminBranding({
          adminName: data.data.generalSettings.adminName || 'INNOTECH Admin Portal',
          adminLogo:
            data.data.generalSettings.adminLogo ||
            data.data.generalSettings.whiteLogo ||
            '/assets/img/logo/white-logo.png',
        });
      }
    } catch (e) {}
  };

  const fetchNotifications = async () => {
    if (isLoginPage) return;
    try {
      const res = await fetch('/api/admin/notifications');
      const data = await res.json();
      if (data.success && data.counts) {
        setNotifications(data.counts);
      }
    } catch (e) {}
  };

  useEffect(() => {
    fetchBranding();
    const handleUpdate = () => {
      fetchNotifications();
      fetchBranding();
    };
    window.addEventListener('innotech_notifications_updated', handleUpdate);
    return () => window.removeEventListener('innotech_notifications_updated', handleUpdate);
  }, []);

  useEffect(() => {
    if (isLoginPage) {
      setIsAuth(true);
      return;
    }
    checkAuth();
    fetchNotifications();

    if (pathname === '/admin/newsletter') {
      fetch('/api/admin/notifications', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ type: 'subscribers' }),
      }).then(() => {
        setNotifications((prev) => ({ ...prev, subscribers: 0 }));
      });
    }

    // Auto-open services dropdown if on a services route
    if (pathname.startsWith('/admin/services')) {
      setOpenDropdowns((prev) => ({ ...prev, services: true }));
    }

    const interval = setInterval(fetchNotifications, 8000);
    return () => clearInterval(interval);
  }, [pathname, isLoginPage]);

  const checkAuth = async () => {
    if (isLoginPage) {
      setIsAuth(true);
      return;
    }

    if (typeof window !== 'undefined') {
      const savedUser = localStorage.getItem('innotech_admin_user');
      if (savedUser) {
        try {
          setAdminUser(JSON.parse(savedUser));
          setIsAuth(true);
          return;
        } catch (e) {}
      }
    }

    try {
      const res = await fetch('/api/auth/me');
      const data = await res.json();
      if (res.ok && data.authenticated) {
        setIsAuth(true);
        if (data.user) setAdminUser(data.user);
      } else {
        router.push('/admin/login');
      }
    } catch (err) {
      router.push('/admin/login');
    }
  };

  const handleLogout = async () => {
    try {
      await fetch('/api/auth/logout', { method: 'POST' });
      router.push('/admin/login');
    } catch (e) {
      router.push('/admin/login');
    }
  };

  if (isLoginPage) {
    return <div className="admin-root">{children}</div>;
  }

  if (!isAuth) {
    return (
      <div
        style={{
          display: 'flex',
          justifyContent: 'center',
          alignItems: 'center',
          minHeight: '100vh',
          backgroundColor: '#171151',
          color: '#ffffff',
          fontFamily: "'Archivo', sans-serif",
        }}
      >
        <div style={{ textAlign: 'center' }}>
          <div
            className="spinner-border text-primary mb-3"
            style={{ width: '3rem', height: '3rem', color: '#0E63FF' }}
            role="status"
          ></div>
          <p style={{ fontWeight: 600 }}>Loading Innotech Admin Portal...</p>
        </div>
      </div>
    );
  }

  // Logically Organized Navigation Menu with Services Dropdown
  const navSections = [
    {
      title: 'Overview & Communication',
      items: [
        { name: 'Dashboard', href: '/admin', icon: LayoutDashboard },
        {
          name: 'Live Chat Support',
          href: '/admin/chat',
          icon: MessageSquare,
          badge: notifications.chats > 0 ? `${notifications.chats} New` : null,
          badgeColor: '#0E63FF',
          badgeBg: '#E0E7FF',
        },
        {
          name: 'Contact Inquiries',
          href: '/admin/contact',
          icon: PhoneCall,
          badge: notifications.inquiries > 0 ? `${notifications.inquiries} New` : null,
          badgeColor: '#EF4444',
          badgeBg: '#FEE2E2',
        },
        {
          name: 'Newsletter Subscribers',
          href: '/admin/newsletter',
          icon: MailCheck,
          badge: notifications.subscribers > 0 ? `${notifications.subscribers}` : null,
          badgeColor: '#10B981',
          badgeBg: '#D1FAE5',
        },
      ],
    },
    {
      title: 'Page Builder & Content',
      items: [
        { name: 'Header & Navigation', href: '/admin/header', icon: Menu },
        { name: 'Home Page Editor', href: '/admin/home', icon: Home },
        { name: 'About Us Page', href: '/admin/about', icon: Award },
        {
          name: 'Services & Products',
          id: 'services',
          icon: Briefcase,
          hasDropdown: true,
          subItems: [
            { name: 'Services Page Settings', href: '/admin/services-page', icon: LayoutTemplate },
            { name: 'Services Inner Pages', href: '/admin/services', icon: Layers },
          ],
        },
        {
          name: 'Blog & Articles',
          href: '/admin/blogs',
          icon: BookOpen,
          badge: notifications.pendingComments > 0 ? `${notifications.pendingComments} New` : null,
          badgeColor: '#D97706',
          badgeBg: '#FEF3C7',
        },
        { name: 'Research & Innovations', href: '/admin/research', icon: FlaskConical },
        { name: 'Legal & Policies', href: '/admin/legal', icon: ShieldCheck },
      ],
    },
    {
      title: 'System & Security',
      items: [
        { name: 'General Settings', href: '/admin/settings', icon: Settings },
        { name: 'Change Password', href: '/admin/change-password', icon: KeyRound },
      ],
    },
  ];

  const sidebarWidth = isCollapsed ? '78px' : '270px';

  return (
    <div
      className="admin-root"
      style={{
        display: 'flex',
        minHeight: '100vh',
        backgroundColor: '#F2F5FA',
        fontFamily: "'Archivo', sans-serif",
      }}
    >
      {/* Mobile Backdrop Overlay */}
      {sidebarOpen && (
        <div
          onClick={() => setSidebarOpen(false)}
          style={{
            position: 'fixed',
            inset: 0,
            backgroundColor: 'rgba(15, 23, 42, 0.65)',
            zIndex: 95,
            backdropFilter: 'blur(2px)',
          }}
          className="d-xl-none"
        />
      )}

      {/* Sidebar (Desktop & Mobile) */}
      <aside
        className={`admin-sidebar ${sidebarOpen ? 'open' : ''} ${isCollapsed ? 'collapsed' : ''}`}
        style={{
          width: sidebarWidth,
          backgroundColor: '#171151',
          color: '#ffffff',
          display: 'flex',
          flexDirection: 'column',
          position: 'fixed',
          top: 0,
          bottom: 0,
          left: 0,
          zIndex: 100,
          transition: 'width 0.3s cubic-bezier(0.4, 0, 0.2, 1), transform 0.3s cubic-bezier(0.4, 0, 0.2, 1)',
          boxShadow: '4px 0 24px rgba(0,0,0,0.12)',
          overflow: 'hidden',
        }}
      >
        {/* Logo Section */}
        <div
          style={{
            padding: isCollapsed ? '20px 12px' : '22px 18px',
            borderBottom: '1px solid rgba(255,255,255,0.08)',
            display: 'flex',
            alignItems: 'center',
            justifyContent: isCollapsed ? 'center' : 'space-between',
            minHeight: '70px',
            boxSizing: 'border-box',
          }}
        >
          {!isCollapsed ? (
            <Link href="/admin" style={{ textDecoration: 'none', display: 'flex', alignItems: 'center' }}>
              <img
                src={adminBranding.adminLogo || '/assets/img/logo/white-logo.png'}
                alt={adminBranding.adminName || 'Innotech Admin'}
                style={{ maxHeight: '36px', maxWidth: '170px', objectFit: 'contain' }}
              />
            </Link>
          ) : (
            <Link href="/admin" style={{ textDecoration: 'none', display: 'flex', justifyContent: 'center' }} title="Innotech Admin">
              <img
                src="/assets/img/logo/favicon.png"
                alt="Logo"
                style={{ width: '32px', height: '32px', objectFit: 'contain' }}
              />
            </Link>
          )}

          {/* Mobile Close Button */}
          <button
            onClick={() => setSidebarOpen(false)}
            className="d-xl-none"
            style={{
              background: 'transparent',
              border: 'none',
              color: '#ffffff',
              cursor: 'pointer',
              padding: '6px',
            }}
            aria-label="Close sidebar menu"
          >
            <X size={20} />
          </button>
        </div>

        {/* User Pill */}
        {!isCollapsed ? (
          <div
            style={{
              padding: '14px 16px',
              margin: '14px 14px 6px',
              backgroundColor: 'rgba(255,255,255,0.05)',
              borderRadius: '10px',
              border: '1px solid rgba(255,255,255,0.08)',
            }}
          >
            <div style={{ fontSize: '11px', textTransform: 'uppercase', color: '#10D0A1', fontWeight: 700, letterSpacing: '0.5px' }}>
              System Administrator
            </div>
            <div style={{ fontSize: '13px', fontWeight: 600, color: '#ffffff', marginTop: '2px' }}>
              {adminUser?.name || 'Admin'}
            </div>
            <div style={{ fontSize: '11px', color: '#A9B7D1', marginTop: '1px', wordBreak: 'break-all' }}>
              {adminUser?.email || 'admin@innotech.com'}
            </div>
          </div>
        ) : (
          <div style={{ textAlign: 'center', padding: '12px 0 6px' }} title={`Logged in: ${adminUser?.name || 'Admin'}`}>
            <div
              style={{
                width: '38px',
                height: '38px',
                borderRadius: '50%',
                backgroundColor: 'rgba(16, 208, 161, 0.15)',
                color: '#10D0A1',
                display: 'inline-flex',
                alignItems: 'center',
                justifyContent: 'center',
                fontWeight: 700,
                fontSize: '14px',
                border: '1px solid rgba(16, 208, 161, 0.3)',
              }}
            >
              {(adminUser?.name || 'A').charAt(0).toUpperCase()}
            </div>
          </div>
        )}

        {/* Navigation Items Organized by Sections */}
        <nav style={{ flex: 1, padding: isCollapsed ? '12px 8px' : '14px 12px', overflowY: 'auto', overflowX: 'hidden' }}>
          {navSections.map((section, sIdx) => (
            <div key={section.title} style={{ marginBottom: isCollapsed ? '16px' : '20px' }}>
              {!isCollapsed ? (
                <div
                  style={{
                    fontSize: '11px',
                    textTransform: 'uppercase',
                    color: '#8A879F',
                    fontWeight: 700,
                    padding: '0 10px 8px',
                    letterSpacing: '0.8px',
                  }}
                >
                  {section.title}
                </div>
              ) : (
                sIdx > 0 && (
                  <div
                    style={{
                      height: '1px',
                      backgroundColor: 'rgba(255,255,255,0.08)',
                      margin: '10px 8px',
                    }}
                  />
                )
              )}

              <ul style={{ listStyle: 'none', padding: 0, margin: 0, display: 'flex', flexDirection: 'column', gap: '4px' }}>
                {section.items.map((item) => {
                  const Icon = item.icon;

                  // Check if item has a dropdown sub-menu
                  if (item.hasDropdown && item.subItems) {
                    const isAnySubActive = item.subItems.some((sub) => pathname === sub.href);
                    const isOpen = openDropdowns[item.id] ?? false;

                    return (
                      <li key={item.name} style={{ display: 'flex', flexDirection: 'column' }}>
                        {/* Parent Dropdown Button */}
                        <button
                          type="button"
                          onClick={() => {
                            if (isCollapsed) {
                              setIsCollapsed(false);
                              setOpenDropdowns((prev) => ({ ...prev, [item.id]: true }));
                            } else {
                              toggleDropdown(item.id);
                            }
                          }}
                          title={isCollapsed ? item.name : undefined}
                          style={{
                            display: 'flex',
                            alignItems: 'center',
                            justifyContent: isCollapsed ? 'center' : 'space-between',
                            padding: isCollapsed ? '11px 0' : '10px 14px',
                            borderRadius: '8px',
                            border: 'none',
                            fontSize: '13.5px',
                            fontWeight: isAnySubActive ? 700 : 500,
                            color: isAnySubActive ? '#ffffff' : '#A9B7D1',
                            backgroundColor: isAnySubActive && !isOpen ? 'rgba(14, 99, 255, 0.2)' : 'transparent',
                            transition: 'all 0.2s ease',
                            cursor: 'pointer',
                            width: '100%',
                            textAlign: 'left',
                          }}
                        >
                          <div style={{ display: 'flex', alignItems: 'center', gap: '11px' }}>
                            <Icon size={18} color={isAnySubActive ? '#10D0A1' : '#A9B7D1'} style={{ flexShrink: 0 }} />
                            {!isCollapsed && <span>{item.name}</span>}
                          </div>

                          {!isCollapsed && (
                            <ChevronDown
                              size={15}
                              style={{
                                transform: isOpen ? 'rotate(180deg)' : 'rotate(0deg)',
                                transition: 'transform 0.25s ease',
                                color: '#A9B7D1',
                              }}
                            />
                          )}
                        </button>

                        {/* Sub-menu Items */}
                        {!isCollapsed && isOpen && (
                          <ul
                            style={{
                              listStyle: 'none',
                              padding: '4px 0 4px 14px',
                              margin: '4px 0 0 10px',
                              borderLeft: '2px solid rgba(255,255,255,0.12)',
                              display: 'flex',
                              flexDirection: 'column',
                              gap: '4px',
                            }}
                          >
                            {item.subItems.map((sub) => {
                              const SubIcon = sub.icon;
                              const isSubActive = pathname === sub.href;
                              return (
                                <li key={sub.name}>
                                  <Link
                                    href={sub.href}
                                    onClick={() => setSidebarOpen(false)}
                                    style={{
                                      display: 'flex',
                                      alignItems: 'center',
                                      gap: '10px',
                                      padding: '8px 12px',
                                      borderRadius: '6px',
                                      textDecoration: 'none',
                                      fontSize: '13px',
                                      fontWeight: isSubActive ? 700 : 500,
                                      color: isSubActive ? '#ffffff' : '#A9B7D1',
                                      backgroundColor: isSubActive ? '#0E63FF' : 'rgba(255,255,255,0.03)',
                                      transition: 'all 0.2s ease',
                                    }}
                                  >
                                    <SubIcon size={15} color={isSubActive ? '#ffffff' : '#A9B7D1'} style={{ flexShrink: 0 }} />
                                    <span>{sub.name}</span>
                                  </Link>
                                </li>
                              );
                            })}
                          </ul>
                        )}
                      </li>
                    );
                  }

                  const isActive = pathname === item.href;
                  return (
                    <li key={item.name}>
                      <Link
                        href={item.href}
                        onClick={() => setSidebarOpen(false)}
                        title={isCollapsed ? item.name : undefined}
                        style={{
                          display: 'flex',
                          alignItems: 'center',
                          justifyContent: isCollapsed ? 'center' : 'space-between',
                          padding: isCollapsed ? '11px 0' : '10px 14px',
                          borderRadius: '8px',
                          textDecoration: 'none',
                          fontSize: '13.5px',
                          fontWeight: isActive ? 700 : 500,
                          color: isActive ? '#ffffff' : '#A9B7D1',
                          backgroundColor: isActive ? '#0E63FF' : 'transparent',
                          transition: 'all 0.2s ease',
                          position: 'relative',
                        }}
                      >
                        <div style={{ display: 'flex', alignItems: 'center', gap: '11px' }}>
                          <Icon size={18} color={isActive ? '#ffffff' : '#A9B7D1'} style={{ flexShrink: 0 }} />
                          {!isCollapsed && <span>{item.name}</span>}
                        </div>

                        {!isCollapsed && item.badge && (
                          <span
                            style={{
                              padding: '2px 7px',
                              borderRadius: '10px',
                              fontSize: '11px',
                              fontWeight: '800',
                              backgroundColor: isActive ? '#ffffff' : item.badgeBg || '#FEF3C7',
                              color: isActive ? '#0E63FF' : item.badgeColor || '#D97706',
                              letterSpacing: '0.3px',
                              boxShadow: '0 1px 4px rgba(0,0,0,0.1)',
                            }}
                          >
                            {item.badge}
                          </span>
                        )}

                        {isCollapsed && item.badge && (
                          <span
                            style={{
                              position: 'absolute',
                              top: '6px',
                              right: '12px',
                              width: '8px',
                              height: '8px',
                              borderRadius: '50%',
                              backgroundColor: item.badgeColor || '#EF4444',
                            }}
                          />
                        )}
                      </Link>
                    </li>
                  );
                })}
              </ul>
            </div>
          ))}
        </nav>

        {/* Bottom Actions & Collapse Toggle */}
        <div style={{ padding: isCollapsed ? '12px 8px' : '14px', borderTop: '1px solid rgba(255,255,255,0.08)' }}>
          {/* Desktop Collapse / Expand Toggle */}
          <button
            onClick={() => setIsCollapsed(!isCollapsed)}
            className="d-none d-xl-flex"
            title={isCollapsed ? 'Expand Sidebar' : 'Collapse Sidebar'}
            style={{
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'center',
              gap: '8px',
              width: '100%',
              padding: isCollapsed ? '9px 0' : '9px 12px',
              backgroundColor: 'rgba(255,255,255,0.08)',
              color: '#10D0A1',
              border: 'none',
              borderRadius: '6px',
              fontSize: '12.5px',
              fontWeight: 600,
              cursor: 'pointer',
              marginBottom: '8px',
              transition: 'background 0.2s ease',
            }}
          >
            {isCollapsed ? <PanelLeftOpen size={16} /> : <PanelLeftClose size={16} />}
            {!isCollapsed && <span>Collapse Menu</span>}
          </button>

          <Link
            href="/"
            target="_blank"
            title={isCollapsed ? 'View Live Website' : undefined}
            style={{
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'center',
              gap: '8px',
              width: '100%',
              padding: isCollapsed ? '9px 0' : '9px 12px',
              backgroundColor: 'rgba(255,255,255,0.05)',
              color: '#ffffff',
              borderRadius: '6px',
              fontSize: '12.5px',
              fontWeight: 600,
              textDecoration: 'none',
              marginBottom: '8px',
            }}
          >
            <ExternalLink size={15} />
            {!isCollapsed && <span>View Website</span>}
          </Link>
          <button
            onClick={handleLogout}
            title={isCollapsed ? 'Sign Out' : undefined}
            style={{
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'center',
              gap: '8px',
              width: '100%',
              padding: isCollapsed ? '9px 0' : '9px 12px',
              backgroundColor: '#F72A75',
              color: '#ffffff',
              border: 'none',
              borderRadius: '6px',
              fontSize: '12.5px',
              fontWeight: 600,
              cursor: 'pointer',
            }}
          >
            <LogOut size={15} />
            {!isCollapsed && <span>Sign Out</span>}
          </button>
        </div>
      </aside>

      {/* Main Content Area */}
      <div
        style={{
          flex: 1,
          marginLeft: sidebarWidth,
          display: 'flex',
          flexDirection: 'column',
          minWidth: 0,
          transition: 'margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1)',
        }}
        className={`admin-main-wrapper ${isCollapsed ? 'collapsed' : ''}`}
      >
        {/* Top Navbar */}
        <header
          style={{
            height: '70px',
            backgroundColor: '#ffffff',
            borderBottom: '1px solid #ECEEF3',
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'space-between',
            padding: '0 24px',
            position: 'sticky',
            top: 0,
            zIndex: 90,
          }}
        >
          <div style={{ display: 'flex', alignItems: 'center', gap: '14px' }}>
            {/* Mobile Open Toggle */}
            <button
              onClick={() => setSidebarOpen(!sidebarOpen)}
              className="d-xl-none"
              style={{
                background: 'transparent',
                border: 'none',
                color: '#171151',
                cursor: 'pointer',
                padding: '6px',
                display: 'flex',
                alignItems: 'center',
              }}
              aria-label="Toggle navigation menu"
            >
              <Menu size={22} />
            </button>

            {/* Desktop Collapse Toggle */}
            <button
              onClick={() => setIsCollapsed(!isCollapsed)}
              className="d-none d-xl-flex"
              style={{
                background: '#F1F4F9',
                border: '1px solid #E2E8F0',
                color: '#171151',
                cursor: 'pointer',
                padding: '8px 10px',
                borderRadius: '8px',
                alignItems: 'center',
                gap: '6px',
                fontSize: '13px',
                fontWeight: 600,
                transition: 'all 0.2s ease',
              }}
              aria-label="Toggle sidebar collapse"
            >
              {isCollapsed ? <PanelLeftOpen size={17} color="#0E63FF" /> : <PanelLeftClose size={17} color="#0E63FF" />}
              <span style={{ color: '#475569' }}>{isCollapsed ? 'Expand' : 'Menu'}</span>
            </button>

            <h2 style={{ fontSize: '17px', fontWeight: '700', color: '#171151', margin: 0 }}>
              Innotech Medical Admin Control Center
            </h2>
          </div>

          <div style={{ display: 'flex', alignItems: 'center', gap: '14px' }}>
            <div
              style={{
                display: 'inline-flex',
                alignItems: 'center',
                gap: '6px',
                padding: '6px 12px',
                borderRadius: '20px',
                backgroundColor: '#E7FAF6',
                color: '#0b9748',
                fontSize: '12px',
                fontWeight: 600,
              }}
            >
              <ShieldCheck size={14} />
              <span className="d-none d-sm-inline">Dynamic Mode Active</span>
            </div>
            <Link
              href="/"
              target="_blank"
              style={{
                padding: '8px 14px',
                backgroundColor: '#0E63FF',
                color: '#fff',
                borderRadius: '6px',
                fontSize: '13px',
                fontWeight: 600,
                textDecoration: 'none',
                display: 'inline-flex',
                alignItems: 'center',
                gap: '6px',
              }}
            >
              <ExternalLink size={14} />
              <span className="d-none d-md-inline">Live Site</span>
            </Link>
          </div>
        </header>

        {/* Dynamic Page Content */}
        <main style={{ padding: '28px', flex: 1 }}>{children}</main>
      </div>

      <style jsx global>{`
        .admin-root,
        .admin-root * {
          font-family: 'Archivo', sans-serif !important;
        }
        @media (max-width: 1199px) {
          .admin-sidebar {
            transform: translateX(-100%) !important;
            width: 270px !important;
          }
          .admin-sidebar.open {
            transform: translateX(0) !important;
          }
          .admin-main-wrapper {
            margin-left: 0 !important;
          }
        }
      `}</style>
    </div>
  );
}
