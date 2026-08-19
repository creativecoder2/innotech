import React from 'react';
import { fallbackBlogPage, fallbackBlogList } from '@/lib/data';
import { getLocalStore } from '@/lib/storage';
import BlogListingClient from '@/components/BlogListingClient';

export const revalidate = 60;

export const metadata = {
  title: 'Blog & Clinical Insights - INNOTECH MEDICAL PVT LTD',
  description: 'Latest biomedical engineering news, hospital project updates, and healthcare equipment articles.',
};

async function getBlogData() {
  try {
    const local = getLocalStore();
    const blogPage = local.blogPage || fallbackBlogPage;
    return {
      banner: blogPage.banner || fallbackBlogPage.banner,
      items: blogPage.items?.length ? blogPage.items : fallbackBlogList,
    };
  } catch (e) {
    return {
      banner: fallbackBlogPage.banner,
      items: fallbackBlogList,
    };
  }
}

export default async function BlogPage() {
  const { banner, items } = await getBlogData();
  const activeBlogs = items.filter((b) => b.enabled !== false);
  const categories = Array.from(new Set(activeBlogs.map((b) => b.category).filter(Boolean)));

  return (
    <BlogListingClient
      banner={banner}
      initialBlogs={activeBlogs}
      categories={categories}
    />
  );
}

