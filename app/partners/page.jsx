import React from 'react';
import BrandSection from '@/components/BrandSection';
import WhyChooseUs from '@/components/WhyChooseUs';
import { fallbackBrands } from '@/lib/data';

export const metadata = {
  title: 'Our Partners - INNOTECH MEDICAL PVT LTD',
  description: 'Global medical device manufacturers and healthcare partners of Innotech Medical.',
};

export default function PartnersPage() {
  return (
    <div className="pt-100">
      <div className="container text-center mb-60">
        <h1 className="tp-section__title">Our Global Partners</h1>
        <p className="mt-15">Collaborating with the world&apos;s most trusted biomedical manufacturers.</p>
      </div>
      <BrandSection brands={fallbackBrands} />
      <WhyChooseUs />
    </div>
  );
}
