import React from 'react';
import HeroBanner from '@/components/HeroBanner';
import ServicesSection from '@/components/ServicesSection';
import AboutSection from '@/components/AboutSection';
import CounterSection from '@/components/CounterSection';
import GallerySection from '@/components/GallerySection';
import WhyChooseUs from '@/components/WhyChooseUs';
import AppointmentForm from '@/components/AppointmentForm';
import TeamSection from '@/components/TeamSection';
import TestimonialSection from '@/components/TestimonialSection';
import BrandSection from '@/components/BrandSection';
import CtaSection from '@/components/CtaSection';
import BlogSection from '@/components/BlogSection';

import connectToDatabase from '@/lib/db';
import Service from '@/models/Service';
import Blog from '@/models/Blog';
import Team from '@/models/Team';
import Testimonial from '@/models/Testimonial';
import SiteConfig from '@/models/SiteConfig';
import {
  fallbackSiteConfig,
  fallbackServices,
  fallbackGallery,
  fallbackTeam,
  fallbackTestimonials,
  fallbackBlogs,
  fallbackBrands,
} from '@/lib/data';
import { getLocalStore } from '@/lib/storage';

export const revalidate = 60;

async function getHomeData() {
  const localStore = getLocalStore();

  let dbConfig = localStore?.config || fallbackSiteConfig;
  let dbServices = localStore?.services || fallbackServices;
  let dbBlogs = localStore?.blogs || fallbackBlogs;
  let dbTeam = localStore?.team || fallbackTeam;
  let dbTestimonials = localStore?.testimonials || fallbackTestimonials;
  let dbGallery = localStore?.gallery || fallbackGallery;
  let dbBrands = localStore?.brands || fallbackBrands;

  // Background non-blocking sync
  connectToDatabase().then(async (conn) => {
    if (!conn) return;
    try {
      const [fetchedConfig, fetchedServices, fetchedBlogs, fetchedTeam, fetchedTestimonials] =
        await Promise.all([
          SiteConfig.findOne().sort({ createdAt: -1 }).lean(),
          Service.find().sort({ order: 1 }).lean(),
          Blog.find().sort({ createdAt: -1 }).limit(10).lean(),
          Team.find().sort({ order: 1 }).lean(),
          Testimonial.find().sort({ order: 1 }).lean(),
        ]);
      if (fetchedConfig) saveLocalStore({ config: fetchedConfig });
    } catch (_) {}
  }).catch(() => {});

  const activeConfig = dbConfig ? JSON.parse(JSON.stringify(dbConfig)) : fallbackSiteConfig;

  // 1. Services items
  const finalServices =
    dbServices && dbServices.length > 0
      ? JSON.parse(JSON.stringify(dbServices))
      : activeConfig.services && activeConfig.services.length > 0
      ? activeConfig.services
      : fallbackServices;

  // 2. Gallery items
  const finalGallery =
    activeConfig.gallerySection?.items && activeConfig.gallerySection.items.length > 0
      ? activeConfig.gallerySection.items
      : dbGallery && dbGallery.length > 0
      ? dbGallery
      : fallbackGallery;

  // 3. Team items
  const finalTeam =
    activeConfig.teamSection?.members && activeConfig.teamSection.members.length > 0
      ? activeConfig.teamSection.members
      : dbTeam && dbTeam.length > 0
      ? JSON.parse(JSON.stringify(dbTeam))
      : fallbackTeam;

  // 4. Testimonials items
  const finalTestimonials =
    activeConfig.testimonialSection?.items && activeConfig.testimonialSection.items.length > 0
      ? activeConfig.testimonialSection.items
      : dbTestimonials && dbTestimonials.length > 0
      ? JSON.parse(JSON.stringify(dbTestimonials))
      : fallbackTestimonials;

  // 5. Blog items (Unified from Blog Page & Admin Blogs manager)
  const finalBlogs =
    localStore?.blogPage?.items && localStore.blogPage.items.length > 0
      ? localStore.blogPage.items
      : activeConfig.blogSection?.items && activeConfig.blogSection.items.length > 0
      ? activeConfig.blogSection.items
      : dbBlogs && dbBlogs.length > 0
      ? JSON.parse(JSON.stringify(dbBlogs))
      : fallbackBlogs;

  // 6. Brand items
  const finalBrands =
    activeConfig.brandsSection?.items && activeConfig.brandsSection.items.length > 0
      ? activeConfig.brandsSection.items
      : dbBrands && dbBrands.length > 0
      ? dbBrands
      : fallbackBrands;

  return {
    config: activeConfig,
    services: finalServices,
    gallery: finalGallery,
    team: finalTeam,
    testimonials: finalTestimonials,
    blogs: finalBlogs,
    brands: finalBrands,
  };
}

export default async function HomePage() {
  const { config, services, gallery, team, testimonials, blogs, brands } = await getHomeData();

  return (
    <>
      {/* 1. Hero Banner Area (Conditional Enable/Disable) */}
      {config.hero?.enabled !== false && <HeroBanner config={config} />}

      {/* 2. Services Area with dynamic search and filtering */}
      {config.servicesSection?.enabled !== false && (
        <ServicesSection config={config} initialServices={services} />
      )}

      {/* 3. About Company & Experience */}
      {config.about?.enabled !== false && <AboutSection config={config} />}

      {/* 4. Statistics Counters */}
      {config.counters?.enabled !== false && <CounterSection config={config} />}

      {/* 5. Work Gallery Showcase */}
      {config.gallerySection?.enabled !== false && (
        <GallerySection config={config} galleryItems={gallery} />
      )}

      {/* 6. Why Choose Us Area */}
      {config.whyChooseUs?.enabled !== false && <WhyChooseUs config={config} />}

      {/* 7. Interactive Appointment & Inquiries Form */}
      {config.appointment?.enabled !== false && <AppointmentForm config={config} />}

      {/* 8. Medical Specialist Team */}
      {config.teamSection?.enabled !== false && (
        <TeamSection config={config} teamMembers={team} />
      )}

      {/* 9. Customer Feedback & Testimonials */}
      {config.testimonialSection?.enabled !== false && (
        <TestimonialSection config={config} testimonials={testimonials} />
      )}

      {/* 10. Partner Brands */}
      {config.brandsSection?.enabled !== false && (
        <BrandSection config={config} brands={brands} />
      )}

      {/* 11. Quick CTA Banner */}
      {config.ctaSection?.enabled !== false && <CtaSection config={config} />}

      {/* 12. Medical Articles & Blog */}
      {config.blogSection?.enabled !== false && (
        <BlogSection config={config} blogs={blogs} />
      )}
    </>
  );
}
