'use client';

import { useEffect } from 'react';
import { usePathname } from 'next/navigation';

export default function TemplateScripts() {
  const pathname = usePathname();

  useEffect(() => {
    // Pure lightweight Vanilla JS background applier (0ms, 0 external script dependencies)
    const applyBackgrounds = () => {
      if (typeof document === 'undefined') return;
      document.querySelectorAll('[data-background]').forEach((el) => {
        const bg = el.getAttribute('data-background');
        if (bg && !el.style.backgroundImage) {
          el.style.backgroundImage = `url(${bg.startsWith('/') ? bg : '/' + bg})`;
        }
      });
    };

    applyBackgrounds();
  }, [pathname]);

  return null;
}
