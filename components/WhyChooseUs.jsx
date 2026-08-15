import React from 'react';
import Link from 'next/link';

export default function WhyChooseUs({ config = {} }) {
  const whyConfig = config.whyChooseUs || {};
  const subTitle = whyConfig.subTitle || 'Our Specialists';
  const title = whyConfig.title || 'Why Choose Us';
  const bottomCtaText = whyConfig.bottomCtaText || 'Scientific Research Laboratories:';
  const bottomCtaLinkText = whyConfig.bottomCtaLinkText || 'Contact Us';
  const bottomCtaLink = whyConfig.bottomCtaLink || '/contact';

  const defaultFeatures = [
    {
      title: 'Global Standards & Quality',
      description:
        'Delivering FDA, CE, and ISO certified medical equipment from global principal brands, ensuring maximum clinical accuracy and patient safety.',
      iconClass: 'flaticon-microscope',
      iconTheme: 'blue',
      enabled: true,
      mlClass: 'ml-15',
      delay: '.2s',
    },
    {
      title: 'Swift Turnkey Delivery',
      description:
        'Streamlined procurement and rapid installation, delivering complete healthcare projects with minimal turnaround time and total precision.',
      iconClass: 'flaticon-thinking',
      iconTheme: 'pink',
      enabled: true,
      mlClass: 'ml-35',
      delay: '.4s',
    },
    {
      title: '24/7 Emergency Support',
      description:
        'Round-the-clock technical coverage and rapid dispatch troubleshooting to eliminate critical equipment downtime in ICUs and OTs.',
      iconClass: 'flaticon-24-hours-1',
      iconTheme: 'green',
      enabled: true,
      mlClass: 'ml-55',
      delay: '.6s',
    },
    {
      title: 'Certified Biomedical Experts',
      description:
        'Backed by OEM-trained engineers executing precision calibration, complex repairs, and proactive maintenance to global standards.',
      iconClass: 'flaticon-team',
      iconTheme: 'sky',
      enabled: true,
      mlClass: 'ml-75',
      delay: '.8s',
    },
  ];

  const features =
    whyConfig.features && whyConfig.features.length > 0 ? whyConfig.features : defaultFeatures;

  const activeFeatures = features.filter((f) => f.enabled !== false);

  return (
    <section className="choose-area theme-bg-2 pt-120 pb-130">
      <div className="container">
        <div className="row">
          <div className="col-lg-12">
            <div className="tp-section text-center">
              <span className="tp-section__sub-title left-line right-line mb-25">{subTitle}</span>
              <h3 className="tp-section__title title-white mb-85">{title}</h3>
            </div>
          </div>
        </div>
        <div className="row">
          {activeFeatures.map((item, idx) => {
            const iconColorClass =
              item.iconTheme === 'pink'
                ? 'pink-icon'
                : item.iconTheme === 'green'
                ? 'green-icon'
                : item.iconTheme === 'sky'
                ? 'sky-icon'
                : '';

            const mlClass = item.mlClass || (idx === 0 ? 'ml-15' : idx === 1 ? 'ml-35' : idx === 2 ? 'ml-55' : 'ml-75');
            const delay = item.delay || `.${(idx + 1) * 2}s`;

            return (
              <div key={idx} className="col-xl-3 col-md-6">
                <div className={`tp-choose__item ${mlClass} mb-100 wow fadeInUp`} data-wow-delay={delay}>
                  <div className={`tp-choose__icon ${iconColorClass} mb-40`}>
                    <i className={item.iconClass || 'flaticon-microscope'}></i>
                  </div>
                  <div className="tp-choose__content">
                    <h4 className="tp-choose__title mb-20">{item.title}</h4>
                    <p>{item.description}</p>
                  </div>
                </div>
              </div>
            );
          })}
        </div>
        <div className="row text-center">
          <div className="col-lg-12">
            <div className="tp-choose-option">
              <span>
                {bottomCtaText}{' '}
                <Link href={bottomCtaLink}>
                  {bottomCtaLinkText}
                  <i className="fa-solid fa-arrow-right ml-2"></i>
                </Link>
              </span>
            </div>
          </div>
        </div>
      </div>
    </section>
  );
}
