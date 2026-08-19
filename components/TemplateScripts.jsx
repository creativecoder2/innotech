'use client';

import { useEffect, useRef } from 'react';
import { usePathname } from 'next/navigation';

export default function TemplateScripts() {
  const pathname = usePathname();
  const scriptsLoaded = useRef(false);

  useEffect(() => {
    // If admin route, do not run public template scripts
    if (pathname?.startsWith('/admin')) return;

    const loadScript = (src) => {
      return new Promise((resolve) => {
        if (document.querySelector(`script[src="${src}"]`)) {
          resolve();
          return;
        }
        const script = document.createElement('script');
        script.src = src;
        script.async = true;
        script.onload = () => resolve();
        script.onerror = () => resolve(); // Graceful on error
        document.body.appendChild(script);
      });
    };

    const initTemplate = async () => {
      try {
        if (!scriptsLoaded.current) {
          await loadScript('/assets/js/jquery.js');
          await loadScript('/assets/js/bootstrap.bundle.min.js');
          await loadScript('/assets/js/swiper-bundle.js');
          await loadScript('/assets/js/magnific-popup.js');
          await loadScript('/assets/js/wow.js');
          scriptsLoaded.current = true;
        }

        if (typeof window !== 'undefined' && window.$) {
          const $ = window.$;

          // Data background
          $('[data-background]').each(function () {
            const bg = $(this).attr('data-background');
            if (bg) $(this).css('background-image', 'url(' + bg + ')');
          });

          // WOW.js initialization
          if (window.WOW) {
            try {
              new window.WOW({
                boxClass: 'wow',
                animateClass: 'animated',
                offset: 0,
                mobile: true,
                live: false, // Don't run continuous mutation observer
              }).init();
            } catch (_) {}
          }
        }
      } catch (err) {
        // Silently catch template notices
      }
    };

    // Run in next idle frame so main thread is never blocked during navigation
    const idleId = typeof window !== 'undefined' && window.requestIdleCallback
      ? window.requestIdleCallback(() => initTemplate())
      : setTimeout(initTemplate, 60);

    return () => {
      if (typeof window !== 'undefined' && window.cancelIdleCallback && typeof idleId === 'number') {
        window.cancelIdleCallback(idleId);
      } else {
        clearTimeout(idleId);
      }
    };
  }, [pathname]);

  return null;
}
