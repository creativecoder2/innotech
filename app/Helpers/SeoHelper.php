<?php

namespace App\Helpers;

use App\Models\Setting;
use Illuminate\Support\Str;

class SeoHelper
{
    /**
     * Compute clean canonical URL without tracking query parameters
     */
    public static function canonicalUrl(): string
    {
        return url()->current();
    }

    /**
     * Compute page title with dynamic separator and site name
     */
    public static function title(?string $pageTitle = null): string
    {
        $siteTitle = Setting::get('site_title', 'INNOTECH MEDICAL PVT LTD');
        $tagline = Setting::get('seo_meta_tagline', 'Innovating Healthcare With Advanced Technologies');
        $separator = Setting::get('seo_title_separator', '|');

        if (empty($pageTitle) || $pageTitle === $siteTitle) {
            $defaultTitle = Setting::get('seo_meta_title');
            if (!empty($defaultTitle)) {
                return $defaultTitle;
            }
            return !empty($tagline) ? "{$siteTitle} {$separator} {$tagline}" : $siteTitle;
        }

        return "{$pageTitle} {$separator} {$siteTitle}";
    }

    /**
     * Compute meta description, ensuring optimal length (150-160 chars)
     */
    public static function description(?string $desc = null): string
    {
        if (empty($desc)) {
            $desc = Setting::get('seo_meta_description', Setting::get('site_tagline', 'Leading provider of hospital medical equipment, clinical laboratory analyzers, ICU monitoring systems, and accredited biomedical calibration services.'));
        }

        $clean = strip_tags($desc);
        $clean = preg_replace('/\s+/', ' ', $clean);
        return Str::limit(trim($clean), 160);
    }

    /**
     * Compute meta keywords
     */
    public static function keywords(?string $keywords = null): string
    {
        if (!empty($keywords)) {
            return $keywords;
        }
        return Setting::get('seo_meta_keywords', 'medical equipment, biomedical engineering, ICU monitors, laboratory calibration, hospital supply, surgical instruments, Pakistan healthcare, ISO 13485');
    }

    /**
     * Compute absolute URL for Open Graph & Twitter share image
     */
    public static function ogImage(?string $image = null): string
    {
        if (!empty($image)) {
            if (Str::startsWith($image, ['http://', 'https://'])) {
                return $image;
            }
            return asset($image);
        }

        $defaultImage = Setting::get('seo_og_image', Setting::get('logo_path', 'assets/img/logo/logo.png'));
        if (Str::startsWith($defaultImage, ['http://', 'https://'])) {
            return $defaultImage;
        }
        return asset($defaultImage);
    }

    /**
     * Get robots directive
     */
    public static function robots(?string $robots = null): string
    {
        if (!empty($robots)) {
            return $robots;
        }
        return Setting::get('seo_meta_robots', 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1');
    }

    /**
     * Generate Schema.org JSON-LD Structured Data
     */
    public static function schemaJsonLd(array $customSchema = []): string
    {
        $siteTitle = Setting::get('site_title', 'INNOTECH MEDICAL PVT LTD');
        $siteUrl = url('/');
        $logo = asset(Setting::get('logo_path', 'assets/img/logo/logo.png'));
        $phone = Setting::get('helpdesk_phone', '+92 331 6699992');
        $email = Setting::get('helpdesk_email', 'info@innotechmedical.pk');
        $address = Setting::get('contact_address', 'Lahore, Pakistan');
        $schemaType = Setting::get('seo_schema_type', 'MedicalBusiness');
        $specialty = Setting::get('seo_schema_specialty', 'Biomedical Engineering & Medical Equipment Supply');

        $baseOrgSchema = [
            '@context' => 'https://schema.org',
            '@type' => $schemaType,
            '@id' => $siteUrl . '/#organization',
            'name' => $siteTitle,
            'url' => $siteUrl,
            'logo' => [
                '@type' => 'ImageObject',
                'url' => $logo,
            ],
            'description' => self::description(),
            'medicalSpecialty' => $specialty,
            'telephone' => $phone,
            'email' => $email,
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => $address,
                'addressCountry' => 'PK',
            ],
            'contactPoint' => [
                '@type' => 'ContactPoint',
                'telephone' => $phone,
                'contactType' => 'customer service',
                'availableLanguage' => ['English', 'Urdu'],
            ],
            'sameAs' => array_filter([
                Setting::get('social_linkedin'),
                Setting::get('social_facebook'),
                Setting::get('social_twitter'),
                Setting::get('social_youtube'),
                Setting::get('social_instagram'),
            ]),
        ];

        $schemas = [$baseOrgSchema];

        if (!empty($customSchema)) {
            $schemas[] = $customSchema;
        }

        return json_encode(['@context' => 'https://schema.org', '@graph' => $schemas], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }
}
