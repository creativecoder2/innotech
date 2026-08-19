'use client';

import { useState, useEffect } from 'react';
import { usePathname } from 'next/navigation';

export default function PageLoader() {
  const pathname = usePathname();
  const [loading, setLoading] = useState(false);

  useEffect(() => {
    // When pathname changes (navigation completed)
    setLoading(true);
    const timer = setTimeout(() => {
      setLoading(false);
    }, 400);

    return () => clearTimeout(timer);
  }, [pathname]);

  return (
    <>
      <style jsx global>{`
        @keyframes tp-nprogress-bar {
          0%   { width: 0%; opacity: 1; }
          40%  { width: 65%; opacity: 1; }
          80%  { width: 90%; opacity: 1; }
          100% { width: 100%; opacity: 0; }
        }

        .tp-route-progress {
          position: fixed;
          top: 0;
          left: 0;
          right: 0;
          height: 3px;
          z-index: 999999;
          pointer-events: none;
        }

        .tp-route-progress-bar {
          height: 100%;
          background: linear-gradient(90deg, #0e63ff 0%, #10d0a1 50%, #0e63ff 100%);
          box-shadow: 0 0 10px #0e63ff, 0 0 5px #10d0a1;
          border-radius: 0 3px 3px 0;
          animation: tp-nprogress-bar 0.4s ease-out forwards;
        }
      `}</style>

      {loading && (
        <div className="tp-route-progress" aria-hidden="true">
          <div className="tp-route-progress-bar" />
        </div>
      )}
    </>
  );
}
