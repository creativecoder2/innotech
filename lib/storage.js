import fs from 'fs';
import path from 'path';
import {
  fallbackSiteConfig,
  fallbackServices,
  fallbackGallery,
  fallbackTeam,
  fallbackTestimonials,
  fallbackBlogs,
  fallbackBrands,
  fallbackAboutPage,
  fallbackContactPage,
  fallbackResearchPage,
  fallbackBlogPage,
  fallbackHeaderConfig,
  fallbackTermsPage,
  fallbackPrivacyPage,
  fallbackServicesPage,
  fallbackInquiries,
  fallbackComments,
  fallbackSubscribers,
  fallbackAnalytics,
} from './data';

const DATA_DIR = path.join(process.cwd(), 'data');
const DATA_FILE = path.join(DATA_DIR, 'site-data.json');

const defaultInitialData = {
  config: fallbackSiteConfig,
  headerConfig: fallbackHeaderConfig,
  services: fallbackServices,
  gallery: fallbackGallery,
  team: fallbackTeam,
  testimonials: fallbackTestimonials,
  blogs: fallbackBlogs,
  brands: fallbackBrands,
  aboutPage: fallbackAboutPage,
  contactPage: fallbackContactPage,
  researchPage: fallbackResearchPage,
  blogPage: fallbackBlogPage,
  servicesPage: fallbackServicesPage,
  termsPage: fallbackTermsPage,
  privacyPage: fallbackPrivacyPage,
  inquiries: fallbackInquiries,
  comments: fallbackComments,
  subscribers: fallbackSubscribers,
  analytics: fallbackAnalytics,
  chatSessions: [],
};

// Ensure data file exists and return merged data
export function getLocalStore() {
  try {
    if (!fs.existsSync(DATA_DIR)) {
      fs.mkdirSync(DATA_DIR, { recursive: true });
    }
    if (!fs.existsSync(DATA_FILE)) {
      fs.writeFileSync(DATA_FILE, JSON.stringify(defaultInitialData, null, 2), 'utf-8');
      return defaultInitialData;
    }
    const raw = fs.readFileSync(DATA_FILE, 'utf-8');
    const parsed = JSON.parse(raw);

    const rawHeader = parsed.headerConfig || parsed.config?.header || {};
    const headerConfig = {
      ...fallbackHeaderConfig,
      ...rawHeader,
      menuItems:
        rawHeader.menuItems && Array.isArray(rawHeader.menuItems) && rawHeader.menuItems.length > 0
          ? rawHeader.menuItems
          : fallbackHeaderConfig.menuItems,
    };

    const rawConfig = parsed.config || {};
    const config = {
      ...fallbackSiteConfig,
      ...rawConfig,
      chatWidget: {
        ...fallbackSiteConfig.chatWidget,
        ...(rawConfig.chatWidget || {}),
      },
      whatsappWidget: {
        ...fallbackSiteConfig.whatsappWidget,
        ...(rawConfig.whatsappWidget || {}),
      },
    };

    return {
      ...defaultInitialData,
      ...parsed,
      config,
      headerConfig,
      inquiries: parsed.inquiries || fallbackInquiries,
      aboutPage: parsed.aboutPage || fallbackAboutPage,
      contactPage: parsed.contactPage || fallbackContactPage,
      researchPage: parsed.researchPage || fallbackResearchPage,
      blogPage: parsed.blogPage?.items?.length ? parsed.blogPage : fallbackBlogPage,
      servicesPage: parsed.servicesPage || fallbackServicesPage,
      termsPage: parsed.termsPage || fallbackTermsPage,
      privacyPage: parsed.privacyPage || fallbackPrivacyPage,
      blogs: parsed.blogs?.length ? parsed.blogs : (parsed.blogPage?.items?.length ? parsed.blogPage.items : fallbackBlogList),
      comments: parsed.comments || fallbackComments,
      subscribers: parsed.subscribers || fallbackSubscribers,
      analytics: parsed.analytics || fallbackAnalytics,
      chatSessions: Array.isArray(parsed.chatSessions) ? parsed.chatSessions : [],
    };
  } catch (error) {
    console.error('Error reading local site-data.json:', error);
    return defaultInitialData;
  }
}

export function saveLocalStore(updatedFields) {
  try {
    if (!fs.existsSync(DATA_DIR)) {
      fs.mkdirSync(DATA_DIR, { recursive: true });
    }
    const current = getLocalStore();
    const merged = {
      ...current,
      ...updatedFields,
    };
    fs.writeFileSync(DATA_FILE, JSON.stringify(merged, null, 2), 'utf-8');
    return merged;
  } catch (error) {
    console.error('Error writing to local site-data.json:', error);
    return null;
  }
}
