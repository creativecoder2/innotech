<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use App\Models\Service;
use App\Models\Product;
use App\Models\Company;
use App\Models\Blog;
use App\Models\TeamMember;
use App\Models\Page;
use App\Models\Setting;

class SeoController extends Controller
{
    /**
     * Generate dynamic XML Sitemap compliant with Google & Bing standards
     */
    public function sitemap()
    {
        $urls = [];

        // 1. Core Primary Static Pages
        $urls[] = [
            'loc' => url('/'),
            'lastmod' => now()->toAtomString(),
            'changefreq' => 'daily',
            'priority' => '1.0'
        ];

        $urls[] = [
            'loc' => url('/about'),
            'lastmod' => now()->subDays(2)->toAtomString(),
            'changefreq' => 'weekly',
            'priority' => '0.8'
        ];

        $urls[] = [
            'loc' => url('/products'),
            'lastmod' => now()->toAtomString(),
            'changefreq' => 'daily',
            'priority' => '0.95'
        ];

        $urls[] = [
            'loc' => url('/blog'),
            'lastmod' => now()->toAtomString(),
            'changefreq' => 'daily',
            'priority' => '0.9'
        ];

        if (\App\Models\Setting::get('section_team_enabled', '1') == '1') {
            $urls[] = [
                'loc' => url('/specialists'),
                'lastmod' => now()->subDays(5)->toAtomString(),
                'changefreq' => 'weekly',
                'priority' => '0.7'
            ];
        }

        $urls[] = [
            'loc' => url('/gallery'),
            'lastmod' => now()->subDays(7)->toAtomString(),
            'changefreq' => 'monthly',
            'priority' => '0.7'
        ];

        $urls[] = [
            'loc' => url('/contact'),
            'lastmod' => now()->subDays(3)->toAtomString(),
            'changefreq' => 'monthly',
            'priority' => '0.8'
        ];

        // 2. Active Medical Products
        $products = Product::where('is_active', true)->orderBy('updated_at', 'desc')->get();
        foreach ($products as $prd) {
            $urls[] = [
                'loc' => route('product.detail', $prd->slug),
                'lastmod' => ($prd->updated_at ?? now())->toAtomString(),
                'changefreq' => 'weekly',
                'priority' => '0.90'
            ];
        }

        // 3. Active Companies / Manufacturers
        $companies = Company::where('is_active', true)->orderBy('updated_at', 'desc')->get();
        foreach ($companies as $comp) {
            $urls[] = [
                'loc' => route('products', ['company' => $comp->slug]),
                'lastmod' => ($comp->updated_at ?? now())->toAtomString(),
                'changefreq' => 'weekly',
                'priority' => '0.85'
            ];
        }

        // 4. Legacy Services
        $services = Service::where('is_active', true)->orderBy('updated_at', 'desc')->get();
        foreach ($services as $srv) {
            $urls[] = [
                'loc' => route('service.detail', $srv->slug),
                'lastmod' => ($srv->updated_at ?? now())->toAtomString(),
                'changefreq' => 'weekly',
                'priority' => '0.80'
            ];
        }

        // 3. Published Blogs & Research Articles
        $blogs = Blog::where('is_published', true)->orderBy('updated_at', 'desc')->get();
        foreach ($blogs as $b) {
            $urls[] = [
                'loc' => route('blog.detail', $b->slug),
                'lastmod' => ($b->updated_at ?? $b->published_at ?? now())->toAtomString(),
                'changefreq' => 'weekly',
                'priority' => '0.80'
            ];
        }

        // 4. Active Specialists
        if (\App\Models\Setting::get('section_team_enabled', '1') == '1') {
            $team = TeamMember::where('is_active', true)->orderBy('updated_at', 'desc')->get();
            foreach ($team as $m) {
                $urls[] = [
                    'loc' => route('specialist.detail', $m->slug ?: $m->id),
                    'lastmod' => ($m->updated_at ?? now())->toAtomString(),
                    'changefreq' => 'monthly',
                    'priority' => '0.70'
                ];
            }
        }

        // 5. Published Custom Pages (Privacy, Terms, Certifications, etc.)
        $pages = Page::where('is_published', true)->orderBy('updated_at', 'desc')->get();
        foreach ($pages as $p) {
            $urls[] = [
                'loc' => route('page.show', $p->slug),
                'lastmod' => ($p->updated_at ?? now())->toAtomString(),
                'changefreq' => 'monthly',
                'priority' => '0.60'
            ];
        }

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"' . PHP_EOL;
        $xml .= '        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"' . PHP_EOL;
        $xml .= '        xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">' . PHP_EOL;

        foreach ($urls as $u) {
            $xml .= '    <url>' . PHP_EOL;
            $xml .= '        <loc>' . htmlspecialchars($u['loc'], ENT_XML1, 'UTF-8') . '</loc>' . PHP_EOL;
            $xml .= '        <lastmod>' . $u['lastmod'] . '</lastmod>' . PHP_EOL;
            $xml .= '        <changefreq>' . $u['changefreq'] . '</changefreq>' . PHP_EOL;
            $xml .= '        <priority>' . $u['priority'] . '</priority>' . PHP_EOL;
            $xml .= '    </url>' . PHP_EOL;
        }

        $xml .= '</urlset>';

        return response($xml, 200, [
            'Content-Type' => 'application/xml; charset=utf-8',
            'X-Robots-Tag' => 'noindex, follow'
        ]);
    }

    /**
     * Generate dynamic robots.txt directive
     */
    public function robots()
    {
        $robotsDirective = Setting::get('seo_meta_robots', 'index, follow');
        $sitemapUrl = url('/sitemap.xml');

        $lines = [];
        $lines[] = '# Innotech Medical Pvt Ltd - Robots Control';
        $lines[] = 'User-agent: *';

        if (strpos($robotsDirective, 'noindex') !== false) {
            $lines[] = 'Disallow: /';
        } else {
            $lines[] = 'Allow: /';
            $lines[] = 'Disallow: /admin/';
            $lines[] = 'Disallow: /admin/*';
            $lines[] = 'Disallow: /live-chat/feed';
            $lines[] = 'Disallow: /search?*';
        }

        $lines[] = '';
        $lines[] = '# XML Sitemap Location';
        $lines[] = 'Sitemap: ' . $sitemapUrl;

        return response(implode(PHP_EOL, $lines), 200, [
            'Content-Type' => 'text/plain; charset=utf-8'
        ]);
    }
}
