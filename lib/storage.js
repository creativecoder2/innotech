import fs from 'fs';
import path from 'path';
import { revalidatePath } from 'next/cache';
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

// In-Memory RAM Cache to eliminate synchronous disk I/O latency
let memoryStoreCache = null;
let lastCacheTime = 0;
const CACHE_TTL_MS = 10000; // 10s RAM cache

export function getLocalStore() {
  const now = Date.now();
  if (memoryStoreCache && now - lastCacheTime < CACHE_TTL_MS) {
    return memoryStoreCache;
  }

  try {
    if (!fs.existsSync(DATA_DIR)) {
      fs.mkdirSync(DATA_DIR, { recursive: true });
    }
    if (!fs.existsSync(DATA_FILE)) {
      fs.writeFileSync(DATA_FILE, JSON.stringify(defaultInitialData, null, 2), 'utf-8');
      memoryStoreCache = defaultInitialData;
      lastCacheTime = now;
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

    const result = {
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

    memoryStoreCache = result;
    lastCacheTime = now;
    return result;
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

    // Update RAM cache immediately
    memoryStoreCache = merged;
    lastCacheTime = Date.now();

    // Async non-blocking file write
    fs.writeFile(DATA_FILE, JSON.stringify(merged, null, 2), 'utf-8', (err) => {
      if (err) console.error('Error saving site-data.json:', err);
    });

    try {
      revalidatePath('/', 'layout');
    } catch (e) {
      // Ignored if outside Next context
    }

    return merged;
  } catch (error) {
    console.error('Error writing to local site-data.json:', error);
    return null;
  }
}
