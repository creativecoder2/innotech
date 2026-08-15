import React from 'react';
import PublicLayoutWrapper from '@/components/PublicLayoutWrapper';
import { fallbackSiteConfig } from '@/lib/data';
import { getLocalStore } from '@/lib/storage';
import connectToDatabase from '@/lib/db';
import SiteConfig from '@/models/SiteConfig';

export const dynamic = 'force-dynamic';
export const revalidate = 0;

export async function generateMetadata() {
  let siteConfig = fallbackSiteConfig;

  try {
    const local = getLocalStore();
    if (local?.config) {
      siteConfig = local.config;
    }

    const conn = await connectToDatabase();
    if (conn) {
      const dbConfig = await SiteConfig.findOne().lean();
      if (dbConfig) {
        siteConfig = dbConfig;
      }
    }
  } catch (e) {
    console.error('Error fetching dynamic metadata in RootLayout:', e);
  }

  const general = siteConfig.generalSettings || fallbackSiteConfig.generalSettings || {};
  const seo = siteConfig.seoSettings || fallbackSiteConfig.seoSettings || {};

  const siteName = general.siteName || 'INNOTECH MEDICAL PVT LTD';
  const tagline = general.siteTagline || 'Innovating Healthcare with Advance Technologies';
  const metaTitle = seo.metaTitle || `${siteName} - ${tagline}`;
  const metaDesc =
    seo.metaDescription ||
    'Innotech Medical Pvt Ltd is a leading distributor of top-quality medical equipment, diagnostic systems, and specialized clinical solutions across Pakistan.';
  const metaKeywords =
    seo.metaKeywords ||
    'medical equipment, diagnostic systems, laboratory supplies, hospital devices, Innotech Medical, Pakistan healthcare';
  const favicon = general.favicon || '/assets/img/logo/favicon.png';
  const metaImage = seo.metaImage || '/assets/img/banner/breadcrumb-01.jpg';
  const canonicalUrl = seo.canonicalUrl || 'https://innotechmedical.org';
  const authorName = seo.metaAuthor || siteName;
  const shouldIndex = !seo.robotsIndex || !seo.robotsIndex.toLowerCase().includes('noindex');
  const shouldFollow = !seo.robotsIndex || !seo.robotsIndex.toLowerCase().includes('nofollow');

  return {
    title: {
      default: metaTitle,
      template: `%s | ${siteName}`,
    },
    description: metaDesc,
    keywords: metaKeywords,
    authors: [{ name: authorName }],
    creator: authorName,
    publisher: siteName,
    metadataBase: new URL(canonicalUrl.startsWith('http') ? canonicalUrl : `https://${canonicalUrl}`),
    alternates: {
      canonical: '/',
    },
    icons: {
      icon: favicon,
      shortcut: favicon,
      apple: favicon,
    },
    openGraph: {
      title: metaTitle,
      description: metaDesc,
      url: canonicalUrl,
      siteName: siteName,
      images: [
        {
          url: metaImage,
          width: 1200,
          height: 630,
          alt: siteName,
        },
      ],
      type: 'website',
      locale: 'en_US',
    },
    twitter: {
      card: 'summary_large_image',
      title: metaTitle,
      description: metaDesc,
      images: [metaImage],
    },
    robots: {
      index: shouldIndex,
      follow: shouldFollow,
      googleBot: {
        index: shouldIndex,
        follow: shouldFollow,
      },
    },
  };
}

export default function RootLayout({ children }) {
  return (
    <html lang="en">
      <head>
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossOrigin="anonymous" />
        <link
          href="https://fonts.googleapis.com/css2?family=Archivo:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400;1,600&family=Days+One&display=swap"
          rel="stylesheet"
        />
        {/* Core & Vendor CSS in original exact sequence */}
        <link rel="stylesheet" href="/assets/css/bootstrap.min.css" />
        <link rel="stylesheet" href="/assets/css/animate.css" />
        <link rel="stylesheet" href="/assets/css/swiper-bundle.css" />
        <link rel="stylesheet" href="/assets/css/slick.css" />
        <link rel="stylesheet" href="/assets/css/magnific-popup.css" />
        <link rel="stylesheet" href="/assets/css/webfonts.css" />
        <link rel="stylesheet" href="/assets/css/font-awesome-pro.css" />
        <link rel="stylesheet" href="/assets/css/meanmenu.css" />
        <link rel="stylesheet" href="/assets/css/nice-select.css" />
        <link rel="stylesheet" href="/assets/css/flaticon.css" />
        <link rel="stylesheet" href="/assets/css/spacing.css" />
        <link rel="stylesheet" href="/assets/css/style.css" />
      </head>
      <body>
        <PublicLayoutWrapper>{children}</PublicLayoutWrapper>
      </body>
    </html>
  );
}
