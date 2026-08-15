import { NextResponse } from 'next/server';
import connectToDatabase from '@/lib/db';
import PageView from '@/models/Analytics';
import Blog from '@/models/Blog';
import { getLocalStore, saveLocalStore } from '@/lib/storage';
import { fallbackBlogList, fallbackAnalytics } from '@/lib/data';

export async function POST(req) {
  try {
    const body = await req.json();
    const { visitorId, path, pageTitle, duration = 0, isBlog = false, blogSlug = '' } = body;

    if (!visitorId || !path) {
      return NextResponse.json({ success: false, message: 'visitorId and path required' }, { status: 400 });
    }

    const todayDate = new Date().toISOString().split('T')[0];
    const localStore = getLocalStore();
    const analytics = localStore.analytics || fallbackAnalytics;
    let pageViews = Array.isArray(analytics.pageViews) ? analytics.pageViews : [];
    let uniqueBlogViews = analytics.uniqueBlogViews || {};

    let currentViews = 0;
    let isNewBlogView = false;

    // Handle Unique Blog View Count
    if (isBlog && blogSlug) {
      if (!uniqueBlogViews[blogSlug]) {
        uniqueBlogViews[blogSlug] = [];
      }

      // Check if this visitorId has already viewed this blog article
      const alreadyViewed = uniqueBlogViews[blogSlug].includes(visitorId);

      // Get current blog items
      let blogItems = localStore.blogPage?.items || fallbackBlogList;
      let targetBlog = blogItems.find((b) => b.slug === blogSlug || b._id === blogSlug);

      if (targetBlog) {
        if (!alreadyViewed) {
          // Increment unique view count
          uniqueBlogViews[blogSlug].push(visitorId);
          isNewBlogView = true;

          const prevViews = typeof targetBlog.views === 'number' ? targetBlog.views : parseInt(targetBlog.views) || 0;
          targetBlog.views = prevViews + 1;

          if (!targetBlog.viewedVisitors) targetBlog.viewedVisitors = [];
          targetBlog.viewedVisitors.push(visitorId);

          saveLocalStore({
            blogPage: {
              ...localStore.blogPage,
              items: blogItems,
            },
          });

          // Sync to MongoDB
          try {
            const conn = await connectToDatabase();
            if (conn) {
              await Blog.findOneAndUpdate(
                { slug: blogSlug },
                {
                  $inc: { views: 1 },
                  $addToSet: { viewedVisitors: visitorId },
                }
              );
            }
          } catch (dbErr) {}
        }
        currentViews = targetBlog.views || 0;
      }
    }

    // Record / Update PageView Session Duration
    const existingEntryIdx = pageViews.findIndex(
      (p) => p.visitorId === visitorId && p.path === path && p.date === todayDate
    );

    if (existingEntryIdx >= 0) {
      // Update duration if provided
      if (duration > 0) {
        pageViews[existingEntryIdx].duration = Math.max(pageViews[existingEntryIdx].duration || 0, duration);
      }
    } else {
      // Add new PageView log
      pageViews.unshift({
        visitorId,
        path,
        pageTitle: pageTitle || path,
        duration: duration || 5,
        isBlog: Boolean(isBlog),
        blogSlug: blogSlug || '',
        date: todayDate,
        createdAt: new Date().toISOString(),
      });
    }

    // Keep pageViews history capped at latest 500 for fast performance
    if (pageViews.length > 500) {
      pageViews = pageViews.slice(0, 500);
    }

    saveLocalStore({
      analytics: {
        pageViews,
        uniqueBlogViews,
      },
    });

    // Sync PageView to MongoDB
    try {
      const conn = await connectToDatabase();
      if (conn) {
        if (duration > 0) {
          await PageView.findOneAndUpdate(
            { visitorId, path, date: todayDate },
            { $max: { duration } },
            { upsert: true, new: true }
          );
        } else {
          await PageView.create({
            visitorId,
            path,
            pageTitle: pageTitle || path,
            duration: 5,
            isBlog: Boolean(isBlog),
            blogSlug: blogSlug || '',
            date: todayDate,
          });
        }
      }
    } catch (dbErr) {}

    return NextResponse.json({
      success: true,
      views: currentViews,
      isNewBlogView,
    });
  } catch (error) {
    console.error('Analytics tracking error:', error);
    return NextResponse.json({ success: false, message: error.message }, { status: 500 });
  }
}
