'use client';

import { useEffect, useRef } from 'react';
import { usePathname } from 'next/navigation';

export default function AnalyticsTracker() {
  const pathname = usePathname() || '/';
  const startTimeRef = useRef(Date.now());
  const pathRef = useRef(pathname);

  // Initialize or retrieve persistent visitor ID
  const getVisitorId = () => {
    if (typeof window === 'undefined') return 'server_visitor';
    let vid = localStorage.getItem('innotech_visitor_id');
    if (!vid) {
      vid = 'v_' + Math.random().toString(36).substring(2, 9) + '_' + Date.now().toString(36);
      localStorage.setItem('innotech_visitor_id', vid);
    }
    return vid;
  };

  const sendDurationUpdate = (currentPath, durationSecs) => {
    if (typeof window === 'undefined' || !currentPath || currentPath.startsWith('/admin')) return;
    const vid = getVisitorId();
    const payload = JSON.stringify({
      visitorId: vid,
      path: currentPath,
      duration: Math.max(durationSecs, 5),
    });

    if (navigator.sendBeacon) {
      navigator.sendBeacon('/api/analytics/track', payload);
    } else {
      fetch('/api/analytics/track', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: payload,
        keepalive: true,
      }).catch(() => {});
    }
  };

  useEffect(() => {
    // Skip tracking for admin routes
    if (pathname.startsWith('/admin')) return;

    const vid = getVisitorId();
    const isBlog = pathname.startsWith('/blog/') && pathname !== '/blog';
    const blogSlug = isBlog ? pathname.replace('/blog/', '') : '';

    startTimeRef.current = Date.now();
    pathRef.current = pathname;

    // Defer analytics ping slightly so initial user interaction is prioritized
    const trackTimer = setTimeout(() => {
      fetch('/api/analytics/track', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          visitorId: vid,
          path: pathname,
          pageTitle: typeof document !== 'undefined' ? document.title : pathname,
          isBlog,
          blogSlug,
          duration: 5,
        }),
      })
        .then((res) => res.json())
        .then((data) => {
          if (data.success && isBlog && data.views) {
            // Dispatch custom event to update dynamic view counter on the page
            window.dispatchEvent(
              new CustomEvent('innotech_blog_views_updated', {
                detail: { slug: blogSlug, views: data.views },
              })
            );
          }
        })
        .catch(() => {});
    }, 1500);

    // Periodic heartbeat to track active dwell time every 25s
    const heartbeat = setInterval(() => {
      const elapsedSeconds = Math.floor((Date.now() - startTimeRef.current) / 1000);
      if (elapsedSeconds > 5) {
        sendDurationUpdate(pathname, elapsedSeconds);
      }
    }, 25000);

    const handleBeforeUnload = () => {
      const elapsedSeconds = Math.floor((Date.now() - startTimeRef.current) / 1000);
      sendDurationUpdate(pathname, elapsedSeconds);
    };

    window.addEventListener('beforeunload', handleBeforeUnload);

    return () => {
      clearInterval(heartbeat);
      window.removeEventListener('beforeunload', handleBeforeUnload);
      const elapsedSeconds = Math.floor((Date.now() - startTimeRef.current) / 1000);
      sendDurationUpdate(pathname, elapsedSeconds);
    };
  }, [pathname]);

  return null;
}
