import mongoose from 'mongoose';

const SiteConfigSchema = new mongoose.Schema(
  {
    // 1. Header & Help Desk
    header: {
      enabled: { type: Boolean, default: true },
      helpDeskPhone: { type: String, default: '+92 331 6699992' },
      emergencyPhone: { type: String, default: '+92 331 6699992' },
    },

    // 2. Hero Banner
    hero: {
      enabled: { type: Boolean, default: true },
      subTitle: { type: String, default: 'Welcome to Innotech Medical Pvt Ltd' },
      title: { type: String, default: 'Innovating Health Care with Advance Technologies' },
      description: {
        type: String,
        default:
          'Innotech Medical Pvt Ltd is Growing distributor of top-quality medical equipment across Pakistan At Innotech Medical, we are dedicated to bridging the gap between world-class medical innovation and Pakistan’s healthcare sector. As a leading provider of advanced biomedical technologies, diagnostic systems, and specialized clinical equipment, we empower healthcare institutions to deliver accurate diagnoses and superior patient care.',
      },
      btn1Text: { type: String, default: 'Contact with Us' },
      btn1Link: { type: String, default: '/contact' },
      btn2Text: { type: String, default: 'About us' },
      btn2Link: { type: String, default: '/about' },
      videoUrl: { type: String, default: 'https://www.youtube.com/embed/d8w5SICzzxc' },
      badge1Text: { type: String, default: '100% Customer Satisfaction' },
      badge2Text: { type: String, default: 'Help and Acess is Our Mission' },
      badge3Text: { type: String, default: '100% Quality Laboratory service' },
    },

    // 3. Services Section
    servicesSection: {
      enabled: { type: Boolean, default: true },
      subTitle: { type: String, default: 'our Services' },
      title: { type: String, default: 'Service Area' },
      searchPlaceholder: { type: String, default: 'What are you looking for?' },
    },

    // 4. About Us Section
    about: {
      enabled: { type: Boolean, default: true },
      experienceYears: { type: String, default: '7' },
      experienceText: { type: String, default: 'Years of Experience' },
      subTitle: { type: String, default: 'Welcome to Innotech Medical Pvt Ltd' },
      title: { type: String, default: 'Innovating Healthcare with Advance Technologies' },
      tagline: {
        type: String,
        default:
          '—Empowering hospitals, diagnostic labs, and surgical suites with world-class technology and end-to-end engineering support.',
      },
      description: {
        type: String,
        default:
          'Innotech Medical Pvt Ltd is Established & Reputable distributor of top-quality medical equipment across Pakistan. From state-of-the-art Medical Devices and Surgical Disposable solutions to comprehensive turnkey hospital projects, our commitment goes beyond equipment distribution. We provide end-to-end technical support, regulatory compliance, and seamless integration, ensuring that healthcare providers across the nation have access to reliable, cutting-edge medical technologies.',
      },
      points: {
        type: [String],
        default: [
          'Critical Care & ICU Equipment',
          'Advanced Diagnostic & Lab Instruments',
          'Operating Room & General Medical Solutions',
          'Turnkey Projects & Technical Support',
        ],
      },
      btn1Text: { type: String, default: 'Our HIstory' },
      btn1Link: { type: String, default: '/about' },
      btn2Text: { type: String, default: 'About us' },
      btn2Link: { type: String, default: '/about' },
    },

    // 5. Counters Section
    counters: {
      enabled: { type: Boolean, default: true },
      item1Number: { type: String, default: '1492' },
      item1Title: { type: String, default: 'Laboratories in 100+ states' },
      item2Number: { type: String, default: '152' },
      item2Title: { type: String, default: 'Laboratory specialists' },
      item3Number: { type: String, default: '1022' },
      item3Title: { type: String, default: 'Material collection points' },
      item4Number: { type: String, default: '24332' },
      item4Title: { type: String, default: 'Patients diagnosed in 2022' },
    },

    // 6. Gallery Section & Dedicated Gallery Page
    gallerySection: {
      enabled: { type: Boolean, default: true },
      subTitle: { type: String, default: 'Work Gallery' },
      title: { type: String, default: 'INNOTECH Gallery' },
      description: {
        type: String,
        default:
          'Explore our advanced medical equipment installations, diagnostic technologies, and turnkey hospital projects across Pakistan.',
      },
      btnText: { type: String, default: 'Explore More' },
      btnLink: { type: String, default: '/gallery' },
      // Dedicated Gallery Page (/gallery) Banner & Section Settings
      bannerImage: { type: String, default: '/assets/img/banner/breadcrumb-01.jpg' },
      bannerTitle: { type: String, default: 'INNOTECH Gallery' },
      bannerSubTitle: {
        type: String,
        default:
          'Explore our advanced medical equipment installations, diagnostic technologies, and turnkey hospital projects across Pakistan.',
      },
      bannerBreadcrumb: { type: String, default: 'Gallery' },
      pageSectionSubTitle: { type: String, default: 'WORK GALLERY' },
      pageSectionTitle: { type: String, default: 'Precision Medical & Laboratory Works' },
      pageDescription: {
        type: String,
        default:
          'Discover our portfolio of FDA, CE, and ISO certified biomedical device deployments, hospital turnkey setups, and laboratory automation.',
      },
      items: [
        {
          id: Number,
          title: String,
          tag: String,
          image: String,
          link: String,
          enabled: { type: Boolean, default: true },
        },
      ],
    },

    // 7. Why Choose Us Section
    whyChooseUs: {
      enabled: { type: Boolean, default: true },
      subTitle: { type: String, default: 'Our Specialists' },
      title: { type: String, default: 'Why Choose Us' },
      bottomCtaText: { type: String, default: 'Scientific Research Laboratories:' },
      bottomCtaLinkText: { type: String, default: 'Contact Us' },
      bottomCtaLink: { type: String, default: '/contact' },
      features: [
        {
          title: String,
          description: String,
          iconClass: String,
          iconTheme: String,
          enabled: { type: Boolean, default: true },
        },
      ],
    },

    // 8. Appointment Section
    appointment: {
      enabled: { type: Boolean, default: true },
      title: { type: String, default: 'GET IN TOUCH WITH US' },
      emergencyText: { type: String, default: '24/7 Emergency Service :' },
      emergencyPhone: { type: String, default: '+92 3316699992' },
    },

    // 9. Team Section
    teamSection: {
      enabled: { type: Boolean, default: true },
      subTitle: { type: String, default: 'Our Team' },
      title: { type: String, default: 'Meet Specialist' },
    },

    // 9b. Team Pages Banners
    teamPages: {
      allTeams: {
        bannerImage: { type: String, default: '/assets/img/banner/breadcrumb-01.jpg' },
        bannerTitle: { type: String, default: 'Meet Specialist' },
        bannerBreadcrumb: { type: String, default: 'Our Team' },
        sectionSubTitle: { type: String, default: 'OUR TEAM' },
        sectionTitle: { type: String, default: 'Our Specialist' },
      },
      teamDetails: {
        bannerImage: { type: String, default: '/assets/img/banner/breadcrumb-01.jpg' },
        bannerTitle: { type: String, default: 'Team Details' },
        bannerBreadcrumb: { type: String, default: 'Our Team' },
      },
    },

    // 10. Testimonials Section
    testimonialSection: {
      enabled: { type: Boolean, default: true },
      subTitle: { type: String, default: 'Testimonial' },
      title: { type: String, default: 'Customer Feedback' },
    },

    // 11. Brands Section
    brandsSection: {
      enabled: { type: Boolean, default: true },
      items: [
        {
          id: Number,
          image: String,
          alt: String,
          enabled: { type: Boolean, default: true },
        },
      ],
    },

    // 12. CTA Section
    ctaSection: {
      enabled: { type: Boolean, default: true },
      title: { type: String, default: 'Looking for a best laboratory Service' },
      phone: { type: String, default: '+92 331 6699992' },
    },

    // 13. Blog Section
    blogSection: {
      enabled: { type: Boolean, default: true },
      subTitle: { type: String, default: 'Waht’s New' },
      title: { type: String, default: 'Blog & Article' },
    },

    // 14. Footer
    footer: {
      enabled: { type: Boolean, default: true },
      description: {
        type: String,
        default:
          'Innotech Medical Pvt Ltd is Growing distributor of top-quality medical equipment across Pakistan At Innotech Medical.',
      },
      address: {
        type: String,
        default:
          '1st Floor, Plot: A-301, Sardar Ali Sabri Road, Block-2, Gulshan e Iqbal, Karachi, Sindh, Pakistan.',
      },
      phone: { type: String, default: '+92 331 6699992' },
      email: { type: String, default: 'info@innotecmedical.org' },
      officeHours: { type: String, default: '10AM - 6PM' },
      weekendText: { type: String, default: 'Sat/Sund - Wekend Day' },
      newsletterTitle: { type: String, default: 'Subscribe Newslatter' },
      newsletterDesc: {
        type: String,
        default:
          'Stay updated with the latest biomedical innovations, equipment releases, and healthcare technology insights across Pakistan.',
      },
      copyrightText: {
        type: String,
        default: '© Copyright ©2026 - 2027 INNOTECH MEDICAL Pvt Ltd. All Rights Reserved',
      },
      socialLinks: {
        facebook: { type: String, default: 'https://facebook.com/innotechmedical' },
        twitter: { type: String, default: 'https://twitter.com/innotechmedical' },
        linkedin: { type: String, default: 'https://linkedin.com/company/innotech-medical' },
        instagram: { type: String, default: '' },
        youtube: { type: String, default: '' },
        skype: { type: String, default: '' },
      },
    },

    // 15. General Settings (Site Name, Tagline, Logos, Favicon, Admin Branding)
    generalSettings: {
      siteName: { type: String, default: 'INNOTECH MEDICAL PVT LTD' },
      siteTagline: { type: String, default: 'Innovating Healthcare with Advance Technologies' },
      mainLogo: { type: String, default: '/assets/img/logo/logo.png' },
      whiteLogo: { type: String, default: '/assets/img/logo/white-logo.png' },
      favicon: { type: String, default: '/assets/img/logo/favicon.png' },
      adminName: { type: String, default: 'INNOTECH Admin Portal' },
      adminLogo: { type: String, default: '/assets/img/logo/white-logo.png' },
    },

    // 16. SEO & Search Engine Metadata Settings
    seoSettings: {
      metaTitle: {
        type: String,
        default: 'INNOTECH MEDICAL PVT LTD - Innovating Healthcare with Advance Technologies',
      },
      metaDescription: {
        type: String,
        default:
          'Innotech Medical Pvt Ltd is a leading distributor of top-quality medical equipment, diagnostic systems, and specialized clinical solutions across Pakistan.',
      },
      metaKeywords: {
        type: String,
        default:
          'medical equipment, diagnostic systems, laboratory supplies, hospital devices, Innotech Medical, Pakistan healthcare, turnkey hospital projects, biomedical engineering',
      },
      metaAuthor: { type: String, default: 'Innotech Medical Pvt Ltd' },
      metaImage: { type: String, default: '/assets/img/banner/breadcrumb-01.jpg' },
      canonicalUrl: { type: String, default: 'https://innotechmedical.org' },
      googleAnalyticsId: { type: String, default: '' },
      robotsIndex: { type: String, default: 'index, follow' },
    },

    // 17. Live Chat Widget Settings
    chatWidget: {
      enabled: { type: Boolean, default: true },
      title: { type: String, default: 'Innotech Live Support' },
      subtitle: { type: String, default: 'Typically replies within minutes' },
      welcomeMessage: {
        type: String,
        default: 'Welcome to Innotech Medical Support! How can we assist you with our medical equipment and services today?',
      },
      autoReplyEnabled: { type: Boolean, default: true },
      autoReplyMessage: {
        type: String,
        default: 'Thank you for reaching out! Our support team has received your message and will respond to you shortly. For urgent assistance, please call +92 331 6699992.',
      },
      agentName: { type: String, default: 'Innotech Support Team' },
      agentAvatar: { type: String, default: '/assets/img/logo/favicon.png' },
    },

    // 18. WhatsApp Floating Widget Settings
    whatsappWidget: {
      enabled: { type: Boolean, default: true },
      phoneNumber: { type: String, default: '+92 331 6699992' },
      defaultMessage: {
        type: String,
        default: 'Hello Innotech Medical, I would like to inquire about your medical equipment and solutions.',
      },
      tooltipText: { type: String, default: 'Chat with us on WhatsApp' },
    },
  },
  { timestamps: true }
);

export default mongoose.models.SiteConfig || mongoose.model('SiteConfig', SiteConfigSchema);
