'use client';

import { useEffect } from 'react';
import { usePathname } from 'next/navigation';

export default function TemplateScripts() {
  const pathname = usePathname();

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
        script.async = false;
        script.onload = () => resolve();
        script.onerror = () => resolve(); // Graceful on error
        document.body.appendChild(script);
      });
    };

    const initTemplate = async () => {
      try {
        await loadScript('/assets/js/jquery.js');
        await loadScript('/assets/js/bootstrap.bundle.min.js');
        await loadScript('/assets/js/swiper-bundle.js');
        await loadScript('/assets/js/magnific-popup.js');
        await loadScript('/assets/js/wow.js');

        if (window.$) {
          const $ = window.$;

          // Data background
          $('[data-background]').each(function () {
            $(this).css('background-image', 'url(' + $(this).attr('data-background') + ')');
          });

          // WOW.js
          if (window.WOW) {
            try {
              new window.WOW({
                boxClass: 'wow',
                animateClass: 'animated',
                offset: 0,
                mobile: true,
                live: true,
              }).init();
            } catch (e) {
              console.warn('WOW notice:', e);
            }
          }

          // Preloader fadeout
          $('#preloadertp').delay(200).fadeOut('slow');
        }
      } catch (err) {
        console.warn('Template script notice:', err);
      }
    };

    const timer = setTimeout(() => {
      initTemplate();
    }, 0);

    return () => clearTimeout(timer);
  }, [pathname]);

  return null;
}
