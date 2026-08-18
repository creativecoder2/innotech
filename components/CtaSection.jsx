import React from 'react';

export default function CtaSection({ config = {} }) {
  const cta = config.ctaSection || {};
  const title = cta.title || 'Looking for a best laboratory Service';
  const phone = cta.phone || '+92 331 6699992';
  const bgImage = cta.bgImage || cta.image || '/assets/img/shape/shape-bg-03.png';
  const thumbImage = cta.thumbImage || cta.rightImage;

  return (
    <section className="cta-area">
      <div className="container">
        <div className="row">
          <div className="col-lg-12">
            <div
              className="cta-bg theme-light-bg pt-65 pb-70 p-relative overflow-hidden"
              style={{
                backgroundImage: `url('${bgImage}')`,
                backgroundSize: 'cover',
                backgroundPosition: 'center right',
              }}
            >
              {thumbImage && (
                <div
                  className="cta-thumb-overlay d-none d-md-block"
                  style={{
                    position: 'absolute',
                    right: 0,
                    top: 0,
                    bottom: 0,
                    width: '45%',
                    height: '100%',
                    pointerEvents: 'none',
                    zIndex: 1,
                  }}
                >
                  <img
                    src={thumbImage}
                    alt="CTA Showcase"
                    style={{
                      width: '100%',
                      height: '100%',
                      objectFit: 'cover',
                    }}
                  />
                </div>
              )}
              <div className="cta-content ml-90" style={{ position: 'relative', zIndex: 2 }}>
                <h2 className="cta-title mb-35">{title}</h2>
                <a href={`tel:${phone.replace(/\s+/g, '')}`} className="tp-cta-btn">
                  <svg
                    width="14"
                    height="19"
                    viewBox="0 0 14 19"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg"
                  >
                    <circle cx="2" cy="2" r="2" fill="white" />
                    <circle cx="7" cy="2" r="2" fill="white" />
                    <circle cx="12" cy="2" r="2" fill="white" />
                    <circle cx="12" cy="7" r="2" fill="white" />
                    <circle cx="12" cy="12" r="2" fill="white" />
                    <circle cx="7" cy="7" r="2" fill="white" />
                    <circle cx="7" cy="12" r="2" fill="white" />
                    <circle cx="7" cy="17" r="2" fill="white" />
                    <circle cx="2" cy="7" r="2" fill="white" />
                    <circle cx="2" cy="12" r="2" fill="white" />
                  </svg>
                  <span>Call :</span>
                  {phone}
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  );
}
