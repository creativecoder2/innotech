import React from 'react';
import Link from 'next/link';
import { fallbackServicesPage } from '@/lib/data';
import { getLocalStore } from '@/lib/storage';
import ServiceSupportForm from '@/components/ServiceSupportForm';

export const revalidate = 60;

export const metadata = {
  title: 'Services - INNOTECH MEDICAL PVT LTD',
  description:
    'Explore our comprehensive biomedical services, diagnostic testing support, and healthcare equipment engineering across Pakistan.',
};

async function getServicesPageData() {
  try {
    const local = getLocalStore();
    return local?.servicesPage || fallbackServicesPage;
  } catch (error) {
    return fallbackServicesPage;
  }
}

export default async function ServicesPage() {
  const data = await getServicesPageData();
  const banner = data.banner || fallbackServicesPage.banner;
  const servicesSection = data.servicesSection || fallbackServicesPage.servicesSection;
  const chooseSection = data.chooseSection || fallbackServicesPage.chooseSection;
  const supportSection = data.supportSection || fallbackServicesPage.supportSection;

  const serviceItems =
    servicesSection.items && servicesSection.items.length > 0
      ? servicesSection.items
      : fallbackServicesPage.servicesSection.items;

  const chooseItems =
    chooseSection.items && chooseSection.items.length > 0
      ? chooseSection.items
      : fallbackServicesPage.chooseSection.items;

  return (
    <main>
      {/* ── 1. BREADCRUMB BANNER ── */}
      {banner.enabled !== false && (
        <section
          className="breadcrumb__area pt-100 pb-120 breadcrumb__overlay"
          style={{
            backgroundImage: `url(${banner.bgImage || '/assets/img/banner/breadcrumb-01.jpg'})`,
            backgroundPosition: 'center',
            backgroundSize: 'cover',
            backgroundRepeat: 'no-repeat',
          }}
        >
          <div className="container">
            <div className="row align-items-center">
              <div className="col-lg-6 col-md-7 col-12">
                <div className="tp-breadcrumb">
                  <h2 className="tp-breadcrumb__title">{banner.title || 'Our Services'}</h2>
                </div>
              </div>
              <div className="col-lg-6 col-md-5 col-12">
                <div className="tp-breadcrumb__link d-flex align-items-center">
                  <span>
                    <Link href="/">Home</Link> : <span>{banner.title || 'Services'}</span>
                  </span>
                </div>
              </div>
            </div>
          </div>
        </section>
      )}

      {/* ── 2. SERVICES AREA ── */}
      {servicesSection.enabled !== false && (
        <section
          className="services-area pt-120 pb-90 grey-bg"
          style={{
            backgroundImage: `url(${servicesSection.bgImage || '/assets/img/shape/shape-bg-01.png'})`,
            backgroundRepeat: 'no-repeat',
          }}
          data-background={servicesSection.bgImage || 'assets/img/shape/shape-bg-01.png'}
        >
          <div className="container">
            <div className="row text-center">
              <div className="col-lg-12 col-md-12 col-12">
                <div className="tp-section">
                  <span className="tp-section__sub-title left-line right-line mb-20">
                    {servicesSection.subTitle || 'our Services'}
                  </span>
                  <h3 className="tp-section__title mb-70">
                    {servicesSection.title || 'Service Area'}
                  </h3>
                </div>
              </div>
            </div>

            <div className="row">
              {serviceItems
                .filter((item) => item.enabled !== false)
                .map((item, idx) => {
                  const iconTheme = item.iconTheme || 'default';
                  const iconClass =
                    iconTheme === 'pink'
                      ? 'pink-icon'
                      : iconTheme === 'green'
                      ? 'green-icon'
                      : iconTheme === 'sky'
                      ? 'sky-icon'
                      : '';

                  const btnHexaClass =
                    iconTheme === 'pink'
                      ? 'btn-hexa pink-hexa'
                      : iconTheme === 'green'
                      ? 'btn-hexa green-hexa'
                      : iconTheme === 'sky'
                      ? 'btn-hexa sky-hexa'
                      : 'btn-hexa';

                  const delay = `.${(idx % 3) * 2 + 2}s`;

                  // Resolve dynamic service detail link
                  let detailLink = item.link;
                  if (!detailLink || detailLink === '/services' || detailLink === '#' || detailLink === 'services-details.html') {
                    const slug = (item.title || '')
                      .toLowerCase()
                      .replace(/[^a-z0-9]+/g, '-')
                      .replace(/(^-|-$)/g, '');
                    detailLink = `/services/${slug || 'hemoglobin-test'}`;
                  }

                  return (
                    <div key={item.id || idx} className="col-xl-4 col-md-6 mb-40 d-flex">
                      <div
                        className="services-item mb-0 wow fadeInUp"
                        data-wow-delay={delay}
                        style={{
                          height: '100%',
                          width: '100%',
                          display: 'flex',
                          flexDirection: 'column',
                          justifyContent: 'space-between',
                        }}
                      >
                        <div>
                          <div className={`services-item__icon ${iconClass} mb-30`}>
                            <i className={item.iconClass || 'flaticon-hemoglobin-test-meter'}></i>
                          </div>
                          <div className="services-item__content">
                            <h4
                              className="services-item__tp-title tp-srv-title mb-30"
                              style={{ minHeight: '52px', display: 'flex', alignItems: 'center' }}
                            >
                              <Link href={detailLink}>{item.title}</Link>
                            </h4>
                            <p style={{ marginBottom: '25px' }}>{item.description}</p>
                          </div>
                        </div>
                        <div className="services-item__btn" style={{ marginTop: 'auto' }}>
                          <Link className={btnHexaClass} href={detailLink}>
                            <i></i>
                            {item.btnText || 'Read More'}
                          </Link>
                        </div>
                      </div>
                    </div>
                  );
                })}
            </div>
          </div>
        </section>
      )}

      {/* ── 3. CHOOSE AREA / WHY CHOOSE US ── */}
      {chooseSection.enabled !== false && (
        <section className="choose-area theme-bg pt-120 pb-130">
          <div className="container">
            <div className="row">
              <div className="col-lg-12">
                <div className="tp-section text-center">
                  <span className="tp-section__sub-title left-line right-line mb-25">
                    {chooseSection.subTitle || 'Our Specialists'}
                  </span>
                  <h3 className="tp-section__title title-white mb-85">
                    {chooseSection.title || 'Why Choose Us'}
                  </h3>
                </div>
              </div>
            </div>

            <div className="row">
              {chooseItems.map((item, idx) => {
                const iconTheme = item.iconTheme || 'default';
                const iconClass =
                  iconTheme === 'pink'
                    ? 'pink-icon'
                    : iconTheme === 'green'
                    ? 'green-icon'
                    : iconTheme === 'sky'
                    ? 'sky-icon'
                    : '';

                const mlClass = item.mlClass || (idx === 0 ? 'ml-15' : idx === 1 ? 'ml-35' : idx === 2 ? 'ml-55' : 'ml-75');

                return (
                  <div key={item.id || idx} className="col-xl-3 col-md-6">
                    <div className={`tp-choose__item ${mlClass} mb-100 wow fadeInUp`} data-wow-delay={item.delay || '.2s'}>
                      <div className={`tp-choose__icon ${iconClass} mb-40`}>
                        <i className={item.iconClass || 'flaticon-microscope'}></i>
                      </div>
                      <div className="tp-choose__content">
                        <h4
                          className="tp-choose__title mb-20"
                          dangerouslySetInnerHTML={{ __html: item.title }}
                        />
                        <p dangerouslySetInnerHTML={{ __html: item.description }} />
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
                    {chooseSection.bottomText || 'Laboratories Used For Scientific Research :'}{' '}
                    <Link href={chooseSection.bottomLink || '/contact'}>
                      {chooseSection.bottomLinkText || 'Take Many Forms'}
                      <i className="fa-solid fa-arrow-right"></i>
                    </Link>
                  </span>
                </div>
              </div>
            </div>
          </div>
        </section>
      )}

      {/* ── 4. SUPPORT AREA / NEED ANY HELP FORM ── */}
      {supportSection.enabled !== false && (
        <section className="support-area grey-bg pt-125 pb-130">
          <div className="container">
            <div className="row text-center">
              <div className="col-lg-12 col-md-12 col-12">
                <div className="tp-section">
                  <span className="tp-section__sub-title left-line right-line mb-20">
                    {supportSection.subTitle || 'Get in touch'}
                  </span>
                  <h3 className="tp-section__title mb-70">
                    {supportSection.title || 'Need Any Help'}
                  </h3>
                </div>
              </div>
            </div>

            <div className="row justify-content-center">
              <div className="col-lg-10 col-md-12 col-12">
                <ServiceSupportForm
                  tagline={supportSection.tagline || 'Direct Contact with us'}
                  btnText={supportSection.btnText || 'Send Message'}
                />
              </div>
            </div>
          </div>
        </section>
      )}
    </main>
  );
}
