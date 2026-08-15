import React from 'react';
import AboutPageCompanySection from '@/components/AboutPageCompanySection';
import CounterSection from '@/components/CounterSection';
import WhyChooseUs from '@/components/WhyChooseUs';
import AboutTabsSection from '@/components/AboutTabsSection';
import TeamSection from '@/components/TeamSection';
import { fallbackAboutPage, fallbackTeam } from '@/lib/data';
import { getLocalStore } from '@/lib/storage';

export const dynamic = 'force-dynamic';
export const revalidate = 0;

export const metadata = {
  title: 'About Us - INNOTECH MEDICAL PVT LTD',
  description:
    'Innotech Medical Pvt Ltd is a premier distributor of advanced biomedical equipment and clinical technologies across Pakistan.',
};

async function getAboutData() {
  try {
    const local = getLocalStore();
    return {
      aboutData: local.aboutPage || fallbackAboutPage,
      team: local.team || fallbackTeam,
    };
  } catch (error) {
    return {
      aboutData: fallbackAboutPage,
      team: fallbackTeam,
    };
  }
}

export default async function AboutPage() {
  const { aboutData, team } = await getAboutData();
  const banner = aboutData.banner || fallbackAboutPage.banner;

  return (
    <>
      {/* 1. Breadcrumb Area */}
      {banner.enabled !== false && (
        <section
          className="breadcrumb__area pt-100 pb-120 breadcrumb__overlay"
          style={{
            backgroundImage: `url(${banner.bgImage || '/assets/img/banner/breadcrumb-01.jpg'})`,
            backgroundSize: 'cover',
            backgroundPosition: 'center',
          }}
        >
          <div className="container">
            <div className="row align-items-center">
              <div className="col-xl-7 col-lg-12 col-md-12 col-12">
                <div className="tp-breadcrumb">
                  <h2 className="tp-breadcrumb__title">{banner.title || 'About us'}</h2>
                  {banner.subTitle && (
                    <p style={{ color: '#E2E8F0', marginTop: '10px', fontSize: '16px', fontWeight: '500' }}>
                      {banner.subTitle}
                    </p>
                  )}
                </div>
              </div>
            </div>
          </div>
        </section>
      )}

      {/* 2. About Company Area */}
      {aboutData.about?.enabled !== false && (
        <AboutPageCompanySection config={{ about: aboutData.about || fallbackAboutPage.about }} />
      )}

      {/* 3. Counter Statistics Area */}
      {aboutData.counters?.enabled !== false && (
        <CounterSection config={{ counters: aboutData.counters || fallbackAboutPage.counters }} />
      )}

      {/* 4. Why Choose Us Area */}
      {aboutData.whyChooseUs?.enabled !== false && (
        <WhyChooseUs config={{ whyChooseUs: aboutData.whyChooseUs || fallbackAboutPage.whyChooseUs }} />
      )}

      {/* 5. Process, Mission & Value Tabs Area */}
      {aboutData.tabsSection?.enabled !== false && (
        <AboutTabsSection config={aboutData} />
      )}

      {/* 6. Specialists Team Area */}
      {aboutData.teamSection?.enabled !== false && (
        <TeamSection
          teamMembers={team}
          config={{ teamSection: aboutData.teamSection || fallbackAboutPage.teamSection }}
        />
      )}
    </>
  );
}

