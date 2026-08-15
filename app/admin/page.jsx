'use client';

import React, { useState, useEffect } from 'react';
import Link from 'next/link';
import {
  Users,
  Activity,
  FileText,
  CalendarCheck,
  ArrowUpRight,
  ExternalLink,
  Clock,
  Eye,
  TrendingUp,
  BarChart3,
  Globe,
  Compass,
  Layers,
  RefreshCw,
  MailCheck,
  PhoneCall,
  ChevronLeft,
  ChevronRight,
  Sparkles,
} from 'lucide-react';

export default function AdminDashboardPage() {
  const [analytics, setAnalytics] = useState(null);
  const [loading, setLoading] = useState(true);
  const [refreshing, setRefreshing] = useState(false);

  // Pagination states
  const [topPagesPage, setTopPagesPage] = useState(1);
  const [dailyPage, setDailyPage] = useState(1);
  const [activityPage, setActivityPage] = useState(1);
  const pageSize = 5;

  useEffect(() => {
    fetchAnalytics();
    const interval = setInterval(fetchAnalytics, 15000); // Auto refresh every 15 seconds
    return () => clearInterval(interval);
  }, []);

  const fetchAnalytics = async () => {
    try {
      const res = await fetch('/api/admin/analytics');
      const data = await res.json();
      if (data.success) {
        setAnalytics(data);
      }
    } catch (err) {
      console.error('Error fetching admin analytics:', err);
    } finally {
      setLoading(false);
      setRefreshing(false);
    }
  };

  const handleManualRefresh = () => {
    setRefreshing(true);
    fetchAnalytics();
  };

  const summary = analytics?.summary || {
    todayVisitors: 1,
    todayPageViews: 6,
    totalUniqueVisitors: 6,
    totalPageViews: 6,
    avgTimeOnSite: '2m 45s',
    mostPopularPage: { path: '/', name: 'Home Page (Main Landing)', views: 1 },
  };

  const dailyTraffic = analytics?.dailyTraffic || [];
  const topPages = analytics?.topPages || [];
  const recentActivity = analytics?.recentActivity || [];

  // Paginated Slices
  const paginatedTopPages = topPages.slice((topPagesPage - 1) * pageSize, topPagesPage * pageSize);
  const totalTopPagesCount = Math.ceil(topPages.length / pageSize) || 1;

  const paginatedDailyTraffic = dailyTraffic.slice((dailyPage - 1) * pageSize, dailyPage * pageSize);
  const totalDailyPagesCount = Math.ceil(dailyTraffic.length / pageSize) || 1;

  const paginatedActivity = recentActivity.slice((activityPage - 1) * pageSize, activityPage * pageSize);
  const totalActivityPagesCount = Math.ceil(recentActivity.length / pageSize) || 1;

  return (
    <div style={{ fontFamily: "'Archivo', sans-serif" }}>
      {/* Header Banner */}
      <div
        style={{
          display: 'flex',
          justifyContent: 'space-between',
          alignItems: 'center',
          flexWrap: 'wrap',
          gap: '16px',
          marginBottom: '28px',
        }}
      >
        <div>
          <div style={{ display: 'flex', alignItems: 'center', gap: '8px' }}>
            <h1 style={{ fontSize: '24px', fontWeight: '800', color: '#171151', margin: 0 }}>
              Live Traffic & Web Analytics
            </h1>
            <span
              style={{
                display: 'inline-flex',
                alignItems: 'center',
                gap: '5px',
                padding: '3px 10px',
                borderRadius: '20px',
                fontSize: '11px',
                fontWeight: '700',
                backgroundColor: '#E7FAF6',
                color: '#0B9748',
              }}
            >
              <span
                style={{
                  width: '6px',
                  height: '6px',
                  borderRadius: '50%',
                  backgroundColor: '#0B9748',
                  display: 'inline-block',
                }}
              ></span>
              Real-time Active
            </span>
          </div>
          <p style={{ fontSize: '13px', color: '#64748B', margin: '4px 0 0' }}>
            Daily visitor logs, average dwell duration per page, and most visited URLs.
          </p>
        </div>

        <div style={{ display: 'flex', gap: '10px' }}>
          <button
            onClick={handleManualRefresh}
            disabled={refreshing}
            style={{
              display: 'inline-flex',
              alignItems: 'center',
              gap: '6px',
              padding: '10px 16px',
              backgroundColor: '#ffffff',
              color: '#171151',
              borderRadius: '8px',
              border: '1px solid #D1D6E0',
              fontSize: '13px',
              fontWeight: '600',
              cursor: refreshing ? 'not-allowed' : 'pointer',
              boxShadow: '0 1px 3px rgba(0,0,0,0.04)',
            }}
          >
            <RefreshCw size={14} className={refreshing ? 'animate-spin' : ''} />
            <span>{refreshing ? 'Refreshing...' : 'Refresh Stats'}</span>
          </button>
          <Link
            href="/"
            target="_blank"
            style={{
              display: 'inline-flex',
              alignItems: 'center',
              gap: '6px',
              padding: '10px 18px',
              backgroundColor: '#0E63FF',
              color: '#ffffff',
              borderRadius: '8px',
              fontSize: '13px',
              fontWeight: '600',
              textDecoration: 'none',
              boxShadow: '0 4px 12px rgba(14, 99, 255, 0.25)',
            }}
          >
            <span>Live Website</span>
            <ExternalLink size={14} />
          </Link>
        </div>
      </div>

      {/* 1. Top Key Metric Cards */}
      <div
        style={{
          display: 'grid',
          gridTemplateColumns: 'repeat(auto-fit, minmax(220px, 1fr))',
          gap: '18px',
          marginBottom: '28px',
        }}
      >
        {/* Today's Visitors */}
        <div
          style={{
            backgroundColor: '#ffffff',
            borderRadius: '12px',
            padding: '20px',
            border: '1px solid #ECEEF3',
            boxShadow: '0 2px 8px rgba(0,0,0,0.02)',
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'space-between',
          }}
        >
          <div>
            <div style={{ fontSize: '12px', color: '#64748B', fontWeight: '600', textTransform: 'uppercase', letterSpacing: '0.5px' }}>
              Today&apos;s Visitors
            </div>
            <div style={{ fontSize: '26px', fontWeight: '800', color: '#171151', marginTop: '6px' }}>
              {summary.todayVisitors} <span style={{ fontSize: '12px', color: '#0B9748', fontWeight: '600' }}>Live</span>
            </div>
            <div style={{ fontSize: '12px', color: '#8A879F', marginTop: '2px' }}>
              {summary.todayPageViews} hits recorded today
            </div>
          </div>
          <div
            style={{
              width: '46px',
              height: '46px',
              borderRadius: '12px',
              backgroundColor: '#EFF6FF',
              color: '#0E63FF',
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'center',
            }}
          >
            <Users size={22} />
          </div>
        </div>

        {/* Total Page Views */}
        <div
          style={{
            backgroundColor: '#ffffff',
            borderRadius: '12px',
            padding: '20px',
            border: '1px solid #ECEEF3',
            boxShadow: '0 2px 8px rgba(0,0,0,0.02)',
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'space-between',
          }}
        >
          <div>
            <div style={{ fontSize: '12px', color: '#64748B', fontWeight: '600', textTransform: 'uppercase', letterSpacing: '0.5px' }}>
              Total Page Views
            </div>
            <div style={{ fontSize: '26px', fontWeight: '800', color: '#171151', marginTop: '6px' }}>
              {summary.totalPageViews}
            </div>
            <div style={{ fontSize: '12px', color: '#8A879F', marginTop: '2px' }}>
              Across {summary.totalUniqueVisitors} unique users
            </div>
          </div>
          <div
            style={{
              width: '46px',
              height: '46px',
              borderRadius: '12px',
              backgroundColor: '#E7FAF6',
              color: '#0B9748',
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'center',
            }}
          >
            <Eye size={22} />
          </div>
        </div>

        {/* Average Time on Page */}
        <div
          style={{
            backgroundColor: '#ffffff',
            borderRadius: '12px',
            padding: '20px',
            border: '1px solid #ECEEF3',
            boxShadow: '0 2px 8px rgba(0,0,0,0.02)',
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'space-between',
          }}
        >
          <div>
            <div style={{ fontSize: '12px', color: '#64748B', fontWeight: '600', textTransform: 'uppercase', letterSpacing: '0.5px' }}>
              Avg. Time on Page
            </div>
            <div style={{ fontSize: '26px', fontWeight: '800', color: '#171151', marginTop: '6px' }}>
              {summary.avgTimeOnSite}
            </div>
            <div style={{ fontSize: '12px', color: '#8A879F', marginTop: '2px' }}>
              Average stay duration
            </div>
          </div>
          <div
            style={{
              width: '46px',
              height: '46px',
              borderRadius: '12px',
              backgroundColor: '#FEEAF1',
              color: '#F72A75',
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'center',
            }}
          >
            <Clock size={22} />
          </div>
        </div>

        {/* Contact Inquiries Received */}
        <Link
          href="/admin/contact"
          style={{
            backgroundColor: '#ffffff',
            borderRadius: '12px',
            padding: '20px',
            border: '1px solid #ECEEF3',
            boxShadow: '0 2px 8px rgba(0,0,0,0.02)',
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'space-between',
            textDecoration: 'none',
            transition: 'transform 0.2s ease, box-shadow 0.2s ease',
          }}
        >
          <div>
            <div style={{ fontSize: '12px', color: '#64748B', fontWeight: '600', textTransform: 'uppercase', letterSpacing: '0.5px' }}>
              Contact Inquiries
            </div>
            <div style={{ fontSize: '26px', fontWeight: '800', color: '#171151', marginTop: '6px' }}>
              {summary.totalInquiries || 0}
            </div>
            <div style={{ fontSize: '12px', color: (summary.unreadInquiries || 0) > 0 ? '#EF4444' : '#0B9748', fontWeight: '600', marginTop: '2px' }}>
              {(summary.unreadInquiries || 0) > 0 ? `${summary.unreadInquiries} New Unread` : 'All Caught Up'}
            </div>
          </div>
          <div
            style={{
              width: '46px',
              height: '46px',
              borderRadius: '12px',
              backgroundColor: '#FEE2E2',
              color: '#EF4444',
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'center',
            }}
          >
            <PhoneCall size={22} />
          </div>
        </Link>

        {/* Newsletter Subscribers */}
        <Link
          href="/admin/newsletter"
          style={{
            backgroundColor: '#ffffff',
            borderRadius: '12px',
            padding: '20px',
            border: '1px solid #ECEEF3',
            boxShadow: '0 2px 8px rgba(0,0,0,0.02)',
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'space-between',
            textDecoration: 'none',
            transition: 'transform 0.2s ease, box-shadow 0.2s ease',
          }}
        >
          <div>
            <div style={{ fontSize: '12px', color: '#64748B', fontWeight: '600', textTransform: 'uppercase', letterSpacing: '0.5px' }}>
              Newsletter Subscribers
            </div>
            <div style={{ fontSize: '26px', fontWeight: '800', color: '#171151', marginTop: '6px' }}>
              {summary.totalSubscribers || 0}
            </div>
            <div style={{ fontSize: '12px', color: '#10B981', fontWeight: '600', marginTop: '2px' }}>
              Captured Email Leads
            </div>
          </div>
          <div
            style={{
              width: '46px',
              height: '46px',
              borderRadius: '12px',
              backgroundColor: '#D1FAE5',
              color: '#10B981',
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'center',
            }}
          >
            <MailCheck size={22} />
          </div>
        </Link>

        {/* Top Visited Route */}
        <div
          style={{
            backgroundColor: '#ffffff',
            borderRadius: '12px',
            padding: '20px',
            border: '1px solid #ECEEF3',
            boxShadow: '0 2px 8px rgba(0,0,0,0.02)',
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'space-between',
          }}
        >
          <div style={{ maxWidth: '140px' }}>
            <div style={{ fontSize: '12px', color: '#64748B', fontWeight: '600', textTransform: 'uppercase', letterSpacing: '0.5px' }}>
              Top Visited Route
            </div>
            <div
              style={{
                fontSize: '16px',
                fontWeight: '800',
                color: '#171151',
                marginTop: '6px',
                whiteSpace: 'nowrap',
                overflow: 'hidden',
                textOverflow: 'ellipsis',
              }}
              title={summary.mostPopularPage?.name || 'Home Page'}
            >
              {summary.mostPopularPage?.name || 'Home Page'}
            </div>
            <div style={{ fontSize: '12px', color: '#0E63FF', fontWeight: '600', marginTop: '2px' }}>
              {summary.mostPopularPage?.views || 1} Total Views
            </div>
          </div>
          <div
            style={{
              width: '46px',
              height: '46px',
              borderRadius: '12px',
              backgroundColor: '#ECF9FF',
              color: '#42BFFF',
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'center',
            }}
          >
            <TrendingUp size={22} />
          </div>
        </div>
      </div>

      {/* 2. Main Analytics Grid: Top Visited Pages & Daily Traffic */}
      <div
        style={{
          display: 'grid',
          gridTemplateColumns: 'repeat(auto-fit, minmax(400px, 1fr))',
          gap: '24px',
          marginBottom: '28px',
        }}
      >
        {/* Left: Top Visited Web Pages Ranked */}
        <div
          style={{
            backgroundColor: '#ffffff',
            borderRadius: '16px',
            padding: '24px',
            border: '1px solid #ECEEF3',
            boxShadow: '0 4px 14px rgba(0,0,0,0.02)',
            display: 'flex',
            flexDirection: 'column',
          }}
        >
          <div
            style={{
              display: 'flex',
              justifyContent: 'space-between',
              alignItems: 'center',
              marginBottom: '20px',
              borderBottom: '1px solid #F1F5F9',
              paddingBottom: '14px',
            }}
          >
            <div>
              <h2 style={{ fontSize: '17px', fontWeight: '800', color: '#171151', margin: 0 }}>
                Top Visited Web Pages
              </h2>
              <p style={{ fontSize: '12px', color: '#64748B', margin: '2px 0 0' }}>
                Ranked by user views, unique visitors, and average dwell time
              </p>
            </div>
            <span
              style={{
                fontSize: '12px',
                fontWeight: '700',
                padding: '4px 10px',
                backgroundColor: '#EFF6FF',
                color: '#0E63FF',
                borderRadius: '8px',
              }}
            >
              {topPages.length} Total Routes
            </span>
          </div>

          <div style={{ flex: 1, overflowX: 'auto' }}>
            <table style={{ width: '100%', borderCollapse: 'collapse', fontSize: '13px', minWidth: '550px' }}>
              <thead>
                <tr style={{ color: '#8A879F', borderBottom: '1px solid #ECEEF3', textAlign: 'left' }}>
                  <th style={{ padding: '10px 8px', width: '32px' }}>#</th>
                  <th style={{ padding: '10px 8px' }}>Page Name & URL</th>
                  <th style={{ padding: '10px 8px', whiteSpace: 'nowrap', width: '120px' }}>Views (Unique)</th>
                  <th style={{ padding: '10px 8px', whiteSpace: 'nowrap', width: '140px' }}>Average Dwell Time</th>
                  <th style={{ padding: '10px 8px', width: '90px', whiteSpace: 'nowrap' }}>Share %</th>
                </tr>
              </thead>
              <tbody>
                {paginatedTopPages.map((page, idx) => {
                  const globalIdx = (topPagesPage - 1) * pageSize + idx;
                  return (
                    <tr
                      key={page.path}
                      style={{
                        borderBottom: '1px solid #F8FAFC',
                        backgroundColor: globalIdx === 0 ? 'rgba(14, 99, 255, 0.02)' : 'transparent',
                      }}
                    >
                      <td style={{ padding: '12px 8px', fontWeight: '800', color: globalIdx < 3 ? '#0E63FF' : '#8A879F' }}>
                        {globalIdx + 1}
                      </td>
                      <td style={{ padding: '12px 8px' }}>
                        <div style={{ fontWeight: '700', color: '#171151', fontSize: '13px', lineHeight: '1.4' }}>
                          {page.name}
                        </div>
                        <a
                          href={page.path}
                          target="_blank"
                          rel="noreferrer"
                          style={{
                            display: 'inline-flex',
                            alignItems: 'center',
                            gap: '4px',
                            color: '#0E63FF',
                            fontSize: '11px',
                            textDecoration: 'none',
                            marginTop: '2px',
                          }}
                        >
                          <code>{page.path}</code>
                          <ExternalLink size={10} style={{ flexShrink: 0 }} />
                        </a>
                      </td>
                      <td style={{ padding: '12px 8px', whiteSpace: 'nowrap' }}>
                        <strong style={{ color: '#171151', fontSize: '13px' }}>{page.views}</strong>
                        <span style={{ fontSize: '11px', color: '#8A879F', marginLeft: '4px' }}>
                          ({page.uniqueVisitors} unique)
                        </span>
                      </td>
                      <td style={{ padding: '12px 8px', whiteSpace: 'nowrap' }}>
                        <span
                          style={{
                            display: 'inline-flex',
                            alignItems: 'center',
                            gap: '5px',
                            padding: '4px 9px',
                            borderRadius: '6px',
                            backgroundColor: '#FFF7ED',
                            color: '#EA580C',
                            fontWeight: '700',
                            fontSize: '12px',
                            whiteSpace: 'nowrap',
                          }}
                        >
                          <Clock size={12} style={{ flexShrink: 0 }} />
                          <span>{page.avgDurationFormatted}</span>
                        </span>
                      </td>
                      <td style={{ padding: '12px 8px', whiteSpace: 'nowrap' }}>
                        <div style={{ display: 'flex', alignItems: 'center', gap: '8px' }}>
                          <div
                            style={{
                              flex: 1,
                              height: '6px',
                              backgroundColor: '#F1F5F9',
                              borderRadius: '3px',
                              overflow: 'hidden',
                              minWidth: '35px',
                            }}
                          >
                            <div
                              style={{
                                width: `${Math.min(page.percentage || 15, 100)}%`,
                                height: '100%',
                                backgroundColor: globalIdx === 0 ? '#0E63FF' : '#10B981',
                                borderRadius: '3px',
                              }}
                            ></div>
                          </div>
                          <span style={{ fontSize: '11px', fontWeight: '700', color: '#475569', minWidth: '24px' }}>
                            {page.percentage}%
                          </span>
                        </div>
                      </td>
                    </tr>
                  );
                })}
              </tbody>
            </table>
          </div>

          {/* Pagination Controls */}
          {topPages.length > pageSize && (
            <div
              style={{
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'space-between',
                paddingTop: '16px',
                borderTop: '1px solid #F1F5F9',
                marginTop: 'auto',
              }}
            >
              <span style={{ fontSize: '12px', color: '#64748B' }}>
                Showing {(topPagesPage - 1) * pageSize + 1} to {Math.min(topPagesPage * pageSize, topPages.length)} of {topPages.length} entries
              </span>
              <div style={{ display: 'flex', alignItems: 'center', gap: '6px' }}>
                <button
                  type="button"
                  disabled={topPagesPage === 1}
                  onClick={() => setTopPagesPage((p) => Math.max(p - 1, 1))}
                  style={{
                    padding: '6px 10px',
                    borderRadius: '6px',
                    border: '1px solid #D1D6E0',
                    backgroundColor: topPagesPage === 1 ? '#F8FAFC' : '#ffffff',
                    color: topPagesPage === 1 ? '#CBD5E1' : '#171151',
                    cursor: topPagesPage === 1 ? 'not-allowed' : 'pointer',
                    display: 'flex',
                    alignItems: 'center',
                    fontSize: '12px',
                    fontWeight: '600',
                  }}
                >
                  <ChevronLeft size={14} />
                  <span>Previous</span>
                </button>
                <span style={{ fontSize: '12px', fontWeight: '700', padding: '0 8px', color: '#171151' }}>
                  {topPagesPage} / {totalTopPagesCount}
                </span>
                <button
                  type="button"
                  disabled={topPagesPage >= totalTopPagesCount}
                  onClick={() => setTopPagesPage((p) => Math.min(p + 1, totalTopPagesCount))}
                  style={{
                    padding: '6px 10px',
                    borderRadius: '6px',
                    border: '1px solid #D1D6E0',
                    backgroundColor: topPagesPage >= totalTopPagesCount ? '#F8FAFC' : '#ffffff',
                    color: topPagesPage >= totalTopPagesCount ? '#CBD5E1' : '#171151',
                    cursor: topPagesPage >= totalTopPagesCount ? 'not-allowed' : 'pointer',
                    display: 'flex',
                    alignItems: 'center',
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

        {/* Right: Daily Visitor Traffic Log */}
        <div
          style={{
            backgroundColor: '#ffffff',
            borderRadius: '16px',
            padding: '24px',
            border: '1px solid #ECEEF3',
            boxShadow: '0 4px 14px rgba(0,0,0,0.02)',
            display: 'flex',
            flexDirection: 'column',
          }}
        >
          <div
            style={{
              display: 'flex',
              justifyContent: 'space-between',
              alignItems: 'center',
              marginBottom: '20px',
              borderBottom: '1px solid #F1F5F9',
              paddingBottom: '14px',
            }}
          >
            <div>
              <h2 style={{ fontSize: '17px', fontWeight: '800', color: '#171151', margin: 0 }}>
                Daily Traffic & Visitor Logs
              </h2>
              <p style={{ fontSize: '12px', color: '#64748B', margin: '2px 0 0' }}>
                Traffic analytics across recorded date ranges
              </p>
            </div>
            <BarChart3 size={18} color="#0E63FF" />
          </div>

          {/* Daily Bar Chart Visualization */}
          <div style={{ marginBottom: '20px' }}>
            <div
              style={{
                display: 'grid',
                gridTemplateColumns: `repeat(${dailyTraffic.length || 7}, 1fr)`,
                gap: '8px',
                alignItems: 'flex-end',
                height: '130px',
                padding: '10px 0',
                borderBottom: '1px dashed #E2E8F0',
              }}
            >
              {dailyTraffic.map((day) => {
                const maxViews = Math.max(...dailyTraffic.map((d) => d.pageViews || 1), 10);
                const heightPercent = Math.max(Math.round(((day.pageViews || 1) / maxViews) * 100), 15);
                return (
                  <div
                    key={day.date}
                    style={{
                      display: 'flex',
                      flexDirection: 'column',
                      alignItems: 'center',
                      height: '100%',
                      justifyContent: 'flex-end',
                    }}
                  >
                    <span style={{ fontSize: '10px', fontWeight: '700', color: '#0E63FF', marginBottom: '4px' }}>
                      {day.uniqueVisitors || 0}
                    </span>
                    <div
                      style={{
                        width: '100%',
                        maxWidth: '32px',
                        height: `${heightPercent}%`,
                        backgroundColor: '#0E63FF',
                        borderRadius: '6px 6px 0 0',
                        transition: 'height 0.3s ease',
                      }}
                      title={`${day.dayName}: ${day.uniqueVisitors} visitors, ${day.pageViews} hits`}
                    ></div>
                    <span style={{ fontSize: '10px', color: '#8A879F', marginTop: '6px', whiteSpace: 'nowrap' }}>
                      {day.dayName?.split(' ')[0] || ''}
                    </span>
                  </div>
                );
              })}
            </div>
          </div>

          {/* Daily Detailed Table */}
          <div style={{ flex: 1, overflowY: 'auto' }}>
            <table style={{ width: '100%', borderCollapse: 'collapse', fontSize: '13px' }}>
              <thead>
                <tr style={{ color: '#8A879F', borderBottom: '1px solid #ECEEF3', textAlign: 'left' }}>
                  <th style={{ padding: '8px 6px' }}>Date</th>
                  <th style={{ padding: '8px 6px' }}>Unique Visitors</th>
                  <th style={{ padding: '8px 6px' }}>Page Hits</th>
                  <th style={{ padding: '8px 6px', textAlign: 'right' }}>Status</th>
                </tr>
              </thead>
              <tbody>
                {paginatedDailyTraffic.map((day) => (
                  <tr key={day.date} style={{ borderBottom: '1px solid #F8FAFC' }}>
                    <td style={{ padding: '8px 6px', fontWeight: '600', color: '#171151' }}>
                      {day.dayName || day.date}
                    </td>
                    <td style={{ padding: '8px 6px', color: '#0E63FF', fontWeight: '700' }}>
                      {day.uniqueVisitors} visitors
                    </td>
                    <td style={{ padding: '8px 6px', color: '#475569' }}>
                      {day.pageViews} hits
                    </td>
                    <td style={{ padding: '8px 6px', textAlign: 'right' }}>
                      <span
                        style={{
                          fontSize: '10px',
                          fontWeight: '700',
                          padding: '2px 6px',
                          borderRadius: '4px',
                          backgroundColor: day.uniqueVisitors > 0 ? '#E7FAF6' : '#F1F5F9',
                          color: day.uniqueVisitors > 0 ? '#0B9748' : '#8A879F',
                        }}
                      >
                        {day.uniqueVisitors > 0 ? 'Active' : 'No Traffic'}
                      </span>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>

          {/* Daily Table Pagination */}
          {dailyTraffic.length > pageSize && (
            <div
              style={{
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'space-between',
                paddingTop: '14px',
                borderTop: '1px solid #F1F5F9',
                marginTop: 'auto',
              }}
            >
              <span style={{ fontSize: '11px', color: '#64748B' }}>
                Page {dailyPage} of {totalDailyPagesCount}
              </span>
              <div style={{ display: 'flex', alignItems: 'center', gap: '6px' }}>
                <button
                  type="button"
                  disabled={dailyPage === 1}
                  onClick={() => setDailyPage((p) => Math.max(p - 1, 1))}
                  style={{
                    padding: '4px 8px',
                    borderRadius: '6px',
                    border: '1px solid #D1D6E0',
                    backgroundColor: dailyPage === 1 ? '#F8FAFC' : '#ffffff',
                    color: dailyPage === 1 ? '#CBD5E1' : '#171151',
                    cursor: dailyPage === 1 ? 'not-allowed' : 'pointer',
                    fontSize: '11px',
                    fontWeight: '600',
                  }}
                >
                  Prev
                </button>
                <button
                  type="button"
                  disabled={dailyPage >= totalDailyPagesCount}
                  onClick={() => setDailyPage((p) => Math.min(p + 1, totalDailyPagesCount))}
                  style={{
                    padding: '4px 8px',
                    borderRadius: '6px',
                    border: '1px solid #D1D6E0',
                    backgroundColor: dailyPage >= totalDailyPagesCount ? '#F8FAFC' : '#ffffff',
                    color: dailyPage >= totalDailyPagesCount ? '#CBD5E1' : '#171151',
                    cursor: dailyPage >= totalDailyPagesCount ? 'not-allowed' : 'pointer',
                    fontSize: '11px',
                    fontWeight: '600',
                  }}
                >
                  Next
                </button>
              </div>
            </div>
          )}
        </div>
      </div>

      {/* 3. Real-Time Live Activity Feed & Quick Actions */}
      <div
        style={{
          display: 'grid',
          gridTemplateColumns: 'repeat(auto-fit, minmax(320px, 1fr))',
          gap: '24px',
        }}
      >
        {/* Live Visitor Feed */}
        <div
          style={{
            backgroundColor: '#ffffff',
            borderRadius: '16px',
            padding: '24px',
            border: '1px solid #ECEEF3',
            display: 'flex',
            flexDirection: 'column',
          }}
        >
          <div
            style={{
              display: 'flex',
              justifyContent: 'space-between',
              alignItems: 'center',
              marginBottom: '16px',
              borderBottom: '1px solid #F1F5F9',
              paddingBottom: '12px',
            }}
          >
            <h3 style={{ fontSize: '16px', fontWeight: '800', color: '#171151', margin: 0 }}>
              Real-Time Visitor Dwell Log
            </h3>
            <span style={{ fontSize: '11px', color: '#0B9748', fontWeight: '700' }}>● Live Stream</span>
          </div>

          <div style={{ display: 'flex', flexDirection: 'column', gap: '10px', flex: 1 }}>
            {paginatedActivity.map((act, i) => (
              <div
                key={i}
                style={{
                  display: 'flex',
                  alignItems: 'center',
                  justifyContent: 'space-between',
                  padding: '12px 14px',
                  backgroundColor: '#F8FAFC',
                  borderRadius: '10px',
                  border: '1px solid #F1F5F9',
                  gap: '12px',
                }}
              >
                <div style={{ display: 'flex', alignItems: 'center', gap: '12px', flex: 1, minWidth: 0, overflow: 'hidden' }}>
                  <div
                    style={{
                      width: '34px',
                      height: '34px',
                      borderRadius: '50%',
                      backgroundColor: '#EFF6FF',
                      color: '#0E63FF',
                      display: 'flex',
                      alignItems: 'center',
                      justifyContent: 'center',
                      fontSize: '12px',
                      fontWeight: '700',
                      flexShrink: 0,
                    }}
                  >
                    V
                  </div>
                  <div style={{ minWidth: 0, flex: 1 }}>
                    <div
                      style={{
                        fontSize: '13px',
                        fontWeight: '700',
                        color: '#171151',
                        whiteSpace: 'nowrap',
                        overflow: 'hidden',
                        textOverflow: 'ellipsis',
                      }}
                      title={act.name}
                    >
                      {act.name}
                    </div>
                    <div style={{ fontSize: '11px', color: '#64748B', whiteSpace: 'nowrap', overflow: 'hidden', textOverflow: 'ellipsis', marginTop: '2px' }}>
                      {act.visitorId} • <code style={{ color: '#0E63FF' }}>{act.path}</code>
                    </div>
                  </div>
                </div>

                <div style={{ textAlign: 'right', flexShrink: 0, display: 'flex', flexDirection: 'column', alignItems: 'flex-end' }}>
                  <span
                    style={{
                      display: 'inline-flex',
                      alignItems: 'center',
                      gap: '4px',
                      fontSize: '11px',
                      fontWeight: '700',
                      padding: '3px 8px',
                      borderRadius: '6px',
                      backgroundColor: '#FEEAF1',
                      color: '#F72A75',
                      whiteSpace: 'nowrap',
                    }}
                  >
                    <Clock size={11} style={{ flexShrink: 0 }} />
                    <span>{act.durationFormatted}</span>
                  </span>
                  <div style={{ fontSize: '11px', color: '#8A879F', marginTop: '3px', whiteSpace: 'nowrap' }}>
                    {act.timeAgo}
                  </div>
                </div>
              </div>
            ))}
          </div>

          {/* Activity Stream Pagination */}
          {recentActivity.length > pageSize && (
            <div
              style={{
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'space-between',
                paddingTop: '14px',
                borderTop: '1px solid #F1F5F9',
                marginTop: '14px',
              }}
            >
              <span style={{ fontSize: '11px', color: '#64748B' }}>
                Page {activityPage} of {totalActivityPagesCount}
              </span>
              <div style={{ display: 'flex', alignItems: 'center', gap: '6px' }}>
                <button
                  type="button"
                  disabled={activityPage === 1}
                  onClick={() => setActivityPage((p) => Math.max(p - 1, 1))}
                  style={{
                    padding: '4px 8px',
                    borderRadius: '6px',
                    border: '1px solid #D1D6E0',
                    backgroundColor: activityPage === 1 ? '#F8FAFC' : '#ffffff',
                    color: activityPage === 1 ? '#CBD5E1' : '#171151',
                    cursor: activityPage === 1 ? 'not-allowed' : 'pointer',
                    fontSize: '11px',
                    fontWeight: '600',
                  }}
                >
                  Prev
                </button>
                <button
                  type="button"
                  disabled={activityPage >= totalActivityPagesCount}
                  onClick={() => setActivityPage((p) => Math.min(p + 1, totalActivityPagesCount))}
                  style={{
                    padding: '4px 8px',
                    borderRadius: '6px',
                    border: '1px solid #D1D6E0',
                    backgroundColor: activityPage >= totalActivityPagesCount ? '#F8FAFC' : '#ffffff',
                    color: activityPage >= totalActivityPagesCount ? '#CBD5E1' : '#171151',
                    cursor: activityPage >= totalActivityPagesCount ? 'not-allowed' : 'pointer',
                    fontSize: '11px',
                    fontWeight: '600',
                  }}
                >
                  Next
                </button>
              </div>
            </div>
          )}
        </div>

        {/* Quick Management Shortcuts */}
        <div
          style={{
            backgroundColor: '#ffffff',
            borderRadius: '16px',
            padding: '24px',
            border: '1px solid #ECEEF3',
          }}
        >
          <h3 style={{ fontSize: '16px', fontWeight: '800', color: '#171151', margin: '0 0 16px' }}>
            Quick Management Links
          </h3>
          <div style={{ display: 'flex', flexDirection: 'column', gap: '10px' }}>
            <Link
              href="/admin/blogs"
              style={{
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'space-between',
                padding: '12px 16px',
                backgroundColor: '#F8FAFC',
                borderRadius: '8px',
                textDecoration: 'none',
                color: '#171151',
                fontWeight: '600',
                border: '1px solid #E2E8F0',
              }}
            >
              <div style={{ display: 'flex', alignItems: 'center', gap: '10px' }}>
                <FileText size={18} color="#0E63FF" />
                <span>Manage Blog Articles & Comments Approval</span>
              </div>
              <ArrowUpRight size={16} color="#8A879F" />
            </Link>

            <Link
              href="/admin/contact"
              style={{
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'space-between',
                padding: '12px 16px',
                backgroundColor: '#F8FAFC',
                borderRadius: '8px',
                textDecoration: 'none',
                color: '#171151',
                fontWeight: '600',
                border: '1px solid #E2E8F0',
              }}
            >
              <div style={{ display: 'flex', alignItems: 'center', gap: '10px' }}>
                <PhoneCall size={18} color="#EF4444" />
                <span>View Contact Inquiries & Quotations</span>
              </div>
              <ArrowUpRight size={16} color="#8A879F" />
            </Link>

            <Link
              href="/admin/newsletter"
              style={{
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'space-between',
                padding: '12px 16px',
                backgroundColor: '#F8FAFC',
                borderRadius: '8px',
                textDecoration: 'none',
                color: '#171151',
                fontWeight: '600',
                border: '1px solid #E2E8F0',
              }}
            >
              <div style={{ display: 'flex', alignItems: 'center', gap: '10px' }}>
                <MailCheck size={18} color="#10B981" />
                <span>Newsletter Subscribers & CSV Export</span>
              </div>
              <ArrowUpRight size={16} color="#8A879F" />
            </Link>

            <Link
              href="/admin/home"
              style={{
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'space-between',
                padding: '12px 16px',
                backgroundColor: '#F8FAFC',
                borderRadius: '8px',
                textDecoration: 'none',
                color: '#171151',
                fontWeight: '600',
                border: '1px solid #E2E8F0',
              }}
            >
              <div style={{ display: 'flex', alignItems: 'center', gap: '10px' }}>
                <Compass size={18} color="#D97706" />
                <span>Customize Home Page Sections & Hero Banner</span>
              </div>
              <ArrowUpRight size={16} color="#8A879F" />
            </Link>
          </div>
        </div>
      </div>
    </div>
  );
}
