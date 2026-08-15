import { NextResponse } from 'next/server';
import connectToDatabase from '@/lib/db';
import PageView from '@/models/Analytics';
import Inquiry from '@/models/Inquiry';
import Subscriber from '@/models/Subscriber';
import Blog from '@/models/Blog';
import Comment from '@/models/Comment';
import { getLocalStore } from '@/lib/storage';
import { fallbackAnalytics, fallbackInquiries, fallbackSubscribers, fallbackBlogList, fallbackComments } from '@/lib/data';

function formatDuration(seconds) {
  if (!seconds || seconds < 1) return '5s';
  const mins = Math.floor(seconds / 60);
  const secs = Math.round(seconds % 60);
  if (mins === 0) return `${secs}s`;
  return `${mins}m ${secs}s`;
}

function getPageName(path, title) {
  if (path === '/') return 'Home Page (Main Landing)';
  if (path === '/about') return 'About Us';
  if (path === '/services') return 'Biomedical & Turnkey Services';
  if (path === '/contact') return 'Contact Us';
  if (path === '/blog') return 'Blog Directory';
  if (path.startsWith('/blog/')) {
    const slug = path.replace('/blog/', '');
    let cleanTitle = title && !title.startsWith('/') ? title.replace(/^Blog:\s*/i, '') : slug.replace(/-/g, ' ');
    cleanTitle = cleanTitle
      .replace(/\s*-\s*INNOTECH MEDICAL.*$/i, '')
      .replace(/\s*-\s*Innotech.*$/i, '')
      .trim();
    return `Article: ${cleanTitle}`;
  }
  return title || path;
}

export async function GET() {
  try {
    const localStore = getLocalStore();
    const analytics = localStore.analytics || fallbackAnalytics;
    let pageViews = Array.isArray(analytics.pageViews) ? analytics.pageViews : [];

    const inquiriesList = Array.isArray(localStore.inquiries) ? localStore.inquiries : fallbackInquiries;
    const subscribersList = Array.isArray(localStore.subscribers) ? localStore.subscribers : fallbackSubscribers;
    const blogList = localStore.blogPage?.items || fallbackBlogList;
    const commentsList = Array.isArray(localStore.comments) ? localStore.comments : fallbackComments;

    let totalInquiries = inquiriesList.length;
    let unreadInquiries = inquiriesList.filter((i) => i.status === 'unread' || !i.status).length;
    let totalSubscribers = subscribersList.length;
    let totalBlogs = blogList.length;
    let totalComments = commentsList.length;

    // Try fetching from MongoDB if available
    try {
      const conn = await connectToDatabase();
      if (conn) {
        const [dbViews, dbInquiries, dbUnreadInq, dbSubscribers, dbBlogs, dbComments] = await Promise.all([
          PageView.find().sort({ createdAt: -1 }).limit(500).lean(),
          Inquiry.countDocuments(),
          Inquiry.countDocuments({ status: 'unread' }),
          Subscriber.countDocuments({ status: 'active' }),
          Blog.countDocuments(),
          Comment.countDocuments(),
        ]);

        if (dbViews && dbViews.length > 0) {
          pageViews = dbViews.map((p) => ({
            ...p,
            _id: p._id.toString(),
            createdAt: p.createdAt ? new Date(p.createdAt).toISOString() : new Date().toISOString(),
          }));
        }
        if (dbInquiries !== undefined && dbInquiries > 0) totalInquiries = dbInquiries;
        if (dbUnreadInq !== undefined) unreadInquiries = dbUnreadInq;
        if (dbSubscribers !== undefined && dbSubscribers > 0) totalSubscribers = dbSubscribers;
        if (dbBlogs !== undefined && dbBlogs > 0) totalBlogs = dbBlogs;
        if (dbComments !== undefined && dbComments > 0) totalComments = dbComments;
      }
    } catch (e) {}

    const todayDate = new Date().toISOString().split('T')[0];

    // 1. Calculate Today & Overall Visitor Metrics
    const todayViews = pageViews.filter((p) => p.date === todayDate || p.createdAt?.startsWith(todayDate));
    const todayUniqueVisitors = new Set(todayViews.map((p) => p.visitorId)).size;
    const allUniqueVisitors = new Set(pageViews.map((p) => p.visitorId)).size;
    const totalViewsCount = pageViews.length;

    const totalDuration = pageViews.reduce((acc, curr) => acc + (curr.duration || 10), 0);
    const avgDurationSeconds = totalViewsCount > 0 ? Math.round(totalDuration / totalViewsCount) : 45;

    // 2. Daily Traffic Breakdown (Last 7 Days)
    const dailyMap = {};
    for (let i = 6; i >= 0; i--) {
      const d = new Date();
      d.setDate(d.getDate() - i);
      const dateStr = d.toISOString().split('T')[0];
      const dayName = d.toLocaleDateString('en-US', { weekday: 'short', month: 'short', day: 'numeric' });
      dailyMap[dateStr] = {
        date: dateStr,
        dayName,
        views: 0,
        visitors: new Set(),
      };
    }

    pageViews.forEach((pv) => {
      const d = pv.date || pv.createdAt?.split('T')[0];
      if (dailyMap[d]) {
        dailyMap[d].views += 1;
        dailyMap[d].visitors.add(pv.visitorId);
      }
    });

    const dailyTraffic = Object.values(dailyMap).map((item) => ({
      date: item.date,
      dayName: item.dayName,
      pageViews: item.views,
      uniqueVisitors: item.visitors.size,
    }));

    // 3. Top Most Visited Pages Ranked with Duration Stats
    const pageMap = {};

    pageViews.forEach((pv) => {
      const p = pv.path || '/';
      if (!pageMap[p]) {
        pageMap[p] = {
          path: p,
          title: pv.pageTitle || p,
          views: 0,
          visitors: new Set(),
          totalDuration: 0,
          isBlog: pv.isBlog || p.startsWith('/blog/'),
        };
      }
      pageMap[p].views += 1;
      pageMap[p].visitors.add(pv.visitorId);
      pageMap[p].totalDuration += pv.duration || 15;
    });

    const topPages = Object.values(pageMap)
      .map((p) => {
        const avgSecs = p.views > 0 ? Math.round(p.totalDuration / p.views) : 20;
        const percentage = totalViewsCount > 0 ? Math.round((p.views / totalViewsCount) * 100) : 0;
        return {
          path: p.path,
          name: getPageName(p.path, p.title),
          views: p.views,
          uniqueVisitors: p.visitors.size,
          avgDurationSeconds: avgSecs,
          avgDurationFormatted: formatDuration(avgSecs),
          percentage: Math.max(percentage, 5),
          isBlog: p.isBlog,
        };
      })
      .sort((a, b) => b.views - a.views);

    // 4. Recent Activity Stream
    const recentActivity = pageViews.slice(0, 15).map((p) => ({
      visitorId: p.visitorId ? `Visitor #${p.visitorId.slice(-4)}` : 'Anonymous Visitor',
      path: p.path,
      name: getPageName(p.path, p.pageTitle),
      durationFormatted: formatDuration(p.duration || 10),
      timeAgo: p.createdAt ? new Date(p.createdAt).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) : 'Just now',
    }));

    return NextResponse.json({
      success: true,
      summary: {
        todayVisitors: todayUniqueVisitors || 1,
        todayPageViews: todayViews.length || 1,
        totalUniqueVisitors: allUniqueVisitors || 1,
        totalPageViews: totalViewsCount || 1,
        avgTimeOnSite: formatDuration(avgDurationSeconds),
        mostPopularPage: topPages[0] || { path: '/', name: 'Home Page', views: 1 },
        totalInquiries,
        unreadInquiries,
        totalSubscribers,
        totalBlogs,
        totalComments,
      },
      dailyTraffic,
      topPages,
      recentActivity,
    });
  } catch (error) {
    console.error('Admin analytics GET error:', error);
    return NextResponse.json({ success: false, message: error.message }, { status: 500 });
  }
}
