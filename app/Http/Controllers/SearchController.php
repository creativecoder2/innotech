<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\Service;
use App\Models\Product;
use App\Models\Company;
use App\Models\Blog;
use App\Models\TeamMember;
use App\Models\GalleryItem;
use App\Models\Page;
use App\Models\NavMenu;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SearchController extends Controller
{
    /**
     * Return JSON suggestions for live autocomplete, strictly limited to active site content.
     */
    public function suggest(Request $request)
    {
        $rawQuery = trim($request->input('q', $request->input('query', '')));

        if (mb_strlen($rawQuery) < 2) {
            return response()->json([
                'query' => $rawQuery,
                'total' => 0,
                'suggestions' => [],
                'counts' => [
                    'services' => 0,
                    'blogs' => 0,
                    'team' => 0,
                    'gallery' => 0,
                    'pages' => 0,
                ]
            ]);
        }

        $query = $rawQuery;

        // Check if sections are active on the site
        $servicesEnabled = (Setting::get('section_services_enabled', '1') == '1');
        $blogEnabled     = (Setting::get('section_blog_enabled', '1') == '1');
        $teamEnabled     = (Setting::get('section_team_enabled', '1') == '1');
        $galleryEnabled  = (Setting::get('section_gallery_enabled', '1') == '1');

        $suggestions = [];

        // 1. ACTIVE MEDICAL PRODUCTS
        $productsQuery = Product::with('company')->where('is_active', true)
            ->where(function ($q) use ($query) {
                $q->where('title', 'LIKE', "%{$query}%")
                  ->orWhere('sku', 'LIKE', "%{$query}%")
                  ->orWhere('short_description', 'LIKE', "%{$query}%")
                  ->orWhereHas('company', function ($cq) use ($query) {
                      $cq->where('name', 'LIKE', "%{$query}%");
                  });
            });
        $productsCount = (clone $productsQuery)->count();
        $products = $productsQuery->orderBy('order', 'asc')->take(4)->get();

        foreach ($products as $prd) {
            $img = $prd->image ?: 'assets/img/shop/shop-01.jpg';
            $suggestions[] = [
                'id' => $prd->id,
                'title' => $prd->title,
                'type' => 'product',
                'type_label' => 'Medical Equipment',
                'badge_class' => 'badge-service',
                'badge_bg' => '#E0F2FE',
                'badge_color' => '#0284C7',
                'image' => asset($img),
                'url' => route('product.detail', $prd->slug),
                'subtitle' => $prd->company ? $prd->company->name : 'Equipment',
                'snippet' => Str::limit(strip_tags($prd->short_description ?: $prd->description), 75),
            ];
        }

        // 2. ACTIVE SERVICES
        $servicesCount = 0;
        if ($servicesEnabled) {
            $servicesQuery = Service::where('is_active', true)
                ->where(function ($q) use ($query) {
                    $q->where('title', 'LIKE', "%{$query}%")
                      ->orWhere('category', 'LIKE', "%{$query}%")
                      ->orWhere('short_description', 'LIKE', "%{$query}%");
                });
            $servicesCount = (clone $servicesQuery)->count();
            $services = $servicesQuery->orderBy('order', 'asc')->take(4)->get();

            foreach ($services as $srv) {
                $img = $srv->image ?: ($srv->banner_image ?: 'assets/img/services/services-thumb-01.jpg');
                $suggestions[] = [
                    'id' => $srv->id,
                    'title' => $srv->title,
                    'type' => 'service',
                    'type_label' => 'Service',
                    'badge_class' => 'badge-service',
                    'badge_bg' => '#EBF7EE',
                    'badge_color' => '#10B981',
                    'image' => asset($img),
                    'url' => route('service.detail', $srv->slug),
                    'subtitle' => $srv->category ?: 'Medical Service',
                    'snippet' => Str::limit(strip_tags($srv->short_description ?: $srv->description), 75),
                ];
            }
        }

        // 2. ACTIVE PUBLISHED BLOGS
        $blogsCount = 0;
        if ($blogEnabled) {
            $blogsQuery = Blog::where('is_published', true)
                ->where('status', 'published')
                ->where(function ($q) use ($query) {
                    $q->where('title', 'LIKE', "%{$query}%")
                      ->orWhere('category', 'LIKE', "%{$query}%")
                      ->orWhere('tags', 'LIKE', "%{$query}%")
                      ->orWhere('summary', 'LIKE', "%{$query}%");
                });
            $blogsCount = (clone $blogsQuery)->count();
            $blogs = $blogsQuery->orderBy('published_at', 'desc')->take(4)->get();

            foreach ($blogs as $b) {
                $img = $b->image ?: 'assets/img/blog/blog-thumb-01.jpg';
                $suggestions[] = [
                    'id' => $b->id,
                    'title' => $b->title,
                    'type' => 'blog',
                    'type_label' => 'Research & Blog',
                    'badge_class' => 'badge-blog',
                    'badge_bg' => '#EEF2FF',
                    'badge_color' => '#4F46E5',
                    'image' => asset($img),
                    'url' => route('blog.detail', $b->slug),
                    'subtitle' => $b->category ?: 'Medical News',
                    'snippet' => Str::limit(strip_tags($b->summary ?: $b->content), 75),
                ];
            }
        }

        // 3. ACTIVE SPECIALISTS & DOCTORS
        $teamCount = 0;
        if ($teamEnabled) {
            $teamQuery = TeamMember::where('is_active', true)
                ->where(function ($q) use ($query) {
                    $q->where('name', 'LIKE', "%{$query}%")
                      ->orWhere('designation', 'LIKE', "%{$query}%")
                      ->orWhere('expertise', 'LIKE', "%{$query}%")
                      ->orWhere('skills', 'LIKE', "%{$query}%");
                });
            $teamCount = (clone $teamQuery)->count();
            $team = $teamQuery->orderBy('order', 'asc')->take(4)->get();

            foreach ($team as $tm) {
                $img = $tm->image ?: 'assets/img/team/team-thumb-01.jpg';
                $suggestions[] = [
                    'id' => $tm->id,
                    'title' => $tm->name,
                    'type' => 'team',
                    'type_label' => 'Specialist',
                    'badge_class' => 'badge-team',
                    'badge_bg' => '#F5F3FF',
                    'badge_color' => '#8B5CF6',
                    'image' => asset($img),
                    'url' => route('specialist.detail', $tm->slug ?: $tm->id),
                    'subtitle' => $tm->designation ?: ($tm->expertise ?: 'Medical Specialist'),
                    'snippet' => Str::limit(strip_tags($tm->bio ?: $tm->personal_experience), 75),
                ];
            }
        }

        // 4. ACTIVE GALLERY ITEMS
        $galleryCount = 0;
        if ($galleryEnabled) {
            $galleryQuery = GalleryItem::where('is_active', true)
                ->where(function ($q) use ($query) {
                    $q->where('title', 'LIKE', "%{$query}%")
                      ->orWhere('category', 'LIKE', "%{$query}%");
                });
            $galleryCount = (clone $galleryQuery)->count();
            $gallery = $galleryQuery->orderBy('order', 'asc')->take(3)->get();

            foreach ($gallery as $g) {
                $img = $g->image ?: 'assets/img/gallery/gallery-thumb-01.jpg';
                $suggestions[] = [
                    'id' => $g->id,
                    'title' => $g->title,
                    'type' => 'gallery',
                    'type_label' => 'Equipment & Lab',
                    'badge_class' => 'badge-gallery',
                    'badge_bg' => '#FEF3C7',
                    'badge_color' => '#D97706',
                    'image' => asset($img),
                    'url' => $g->link ?: url('/gallery'),
                    'subtitle' => $g->category ?: 'Gallery Showcase',
                    'snippet' => 'Scientific equipment & laboratory visualization.',
                ];
            }
        }

        // 5. ACTIVE PAGES (Title/Subtitle matching ONLY so irrelevant legal body text is excluded)
        $pagesList = [];

        // Active Static Pages check
        $staticSitePages = collect([
            ['title' => 'About Us - Innotech Medical', 'subtitle' => 'Company Mission, History & Facilities', 'url' => url('/about'), 'keywords' => ['about', 'company', 'mission', 'facilities', 'who we are'], 'active' => (Setting::get('section_about_enabled', '1') == '1')],
            ['title' => 'Contact Us & Help Desk', 'subtitle' => 'Book Appointment & Inquiries', 'url' => url('/contact'), 'keywords' => ['contact', 'helpdesk', 'phone', 'email', 'appointment', 'address', 'support'], 'active' => true],
            ['title' => 'Our Specialists & Healthcare Experts', 'subtitle' => 'Specialized Medical Consultants', 'url' => url('/specialists'), 'keywords' => ['specialist', 'specialists', 'doctor', 'doctors', 'consultant'], 'active' => $teamEnabled],
            ['title' => 'Medical Equipment & Laboratory Gallery', 'subtitle' => 'Biomedical Showcase', 'url' => url('/gallery'), 'keywords' => ['gallery', 'equipment', 'laboratory', 'photos', 'showcase'], 'active' => $galleryEnabled],
            ['title' => 'Blog & Clinical Research Articles', 'subtitle' => 'Latest Medical Insights & News', 'url' => url('/blog'), 'keywords' => ['blog', 'research', 'article', 'news', 'publication', 'insights'], 'active' => $blogEnabled],
        ]);

        foreach ($staticSitePages as $sPage) {
            if ($sPage['active']) {
                $matches = (stripos($sPage['title'], $query) !== false) ||
                           (stripos($sPage['subtitle'], $query) !== false) ||
                           collect($sPage['keywords'])->contains(function($kw) use ($query) {
                               return stripos($kw, $query) !== false || stripos($query, $kw) !== false;
                           });
                if ($matches) {
                    $pagesList[] = [
                        'id' => 'static_' . Str::slug($sPage['title']),
                        'title' => $sPage['title'],
                        'type' => 'page',
                        'type_label' => 'Site Page',
                        'badge_class' => 'badge-page',
                        'badge_bg' => '#F1F5F9',
                        'badge_color' => '#475569',
                        'image' => asset('assets/img/icon/title-icon.png'),
                        'url' => $sPage['url'],
                        'subtitle' => $sPage['subtitle'],
                        'snippet' => 'Official website section of Innotech Medical Pvt Ltd.',
                    ];
                }
            }
        }

        // Active Custom Published Pages (Match on title / subtitle ONLY)
        $customPages = Page::where('is_published', true)
            ->where(function ($q) use ($query) {
                $q->where('title', 'LIKE', "%{$query}%")
                  ->orWhere('subtitle', 'LIKE', "%{$query}%");
            })
            ->orderBy('order', 'asc')
            ->take(3)
            ->get();

        foreach ($customPages as $p) {
            $pagesList[] = [
                'id' => $p->id,
                'title' => $p->title,
                'type' => 'page',
                'type_label' => 'Information Page',
                'badge_class' => 'badge-page',
                'badge_bg' => '#F1F5F9',
                'badge_color' => '#475569',
                'image' => asset('assets/img/icon/title-icon.png'),
                'url' => route('page.show', $p->slug),
                'subtitle' => $p->subtitle ?: 'Official Company Policy',
                'snippet' => Str::limit(strip_tags($p->content), 75),
            ];
        }

        $pagesCount = count($pagesList);
        foreach (array_slice($pagesList, 0, 3) as $pItem) {
            $suggestions[] = $pItem;
        }

        $totalCount = $servicesCount + $blogsCount + $teamCount + $galleryCount + $pagesCount;
        $displaySuggestions = array_slice($suggestions, 0, 8);

        return response()->json([
            'query' => $query,
            'total' => $totalCount,
            'suggestions' => $displaySuggestions,
            'counts' => [
                'services' => $servicesCount,
                'blogs' => $blogsCount,
                'team' => $teamCount,
                'gallery' => $galleryCount,
                'pages' => $pagesCount,
            ]
        ]);
    }

    /**
     * Display full search results page strictly limited to active site content.
     */
    public function index(Request $request)
    {
        $query = trim($request->input('query', $request->input('q', '')));
        $activeTab = $request->input('type', 'all');

        // Check if sections are active on the site
        $servicesEnabled = (Setting::get('section_services_enabled', '1') == '1');
        $blogEnabled     = (Setting::get('section_blog_enabled', '1') == '1');
        $teamEnabled     = (Setting::get('section_team_enabled', '1') == '1');
        $galleryEnabled  = (Setting::get('section_gallery_enabled', '1') == '1');

        $services = collect();
        $blogs = collect();
        $team = collect();
        $gallery = collect();
        $pages = collect();

        $servicesCount = 0;
        $blogsCount = 0;
        $teamCount = 0;
        $galleryCount = 0;
        $pagesCount = 0;

        if ($query !== '') {
            // 1. Services (Strictly Active)
            if ($servicesEnabled) {
                $srvQuery = Service::where('is_active', true)
                    ->where(function ($q) use ($query) {
                        $q->where('title', 'LIKE', "%{$query}%")
                          ->orWhere('category', 'LIKE', "%{$query}%")
                          ->orWhere('short_description', 'LIKE', "%{$query}%");
                    });
                $servicesCount = (clone $srvQuery)->count();
                $services = $srvQuery->orderBy('order', 'asc')->get();
            }

            // 2. Blogs (Strictly Active & Published)
            if ($blogEnabled) {
                $blgQuery = Blog::where('is_published', true)
                    ->where('status', 'published')
                    ->where(function ($q) use ($query) {
                        $q->where('title', 'LIKE', "%{$query}%")
                          ->orWhere('category', 'LIKE', "%{$query}%")
                          ->orWhere('tags', 'LIKE', "%{$query}%")
                          ->orWhere('summary', 'LIKE', "%{$query}%");
                    });
                $blogsCount = (clone $blgQuery)->count();
                $blogs = $blgQuery->orderBy('published_at', 'desc')->get();
            }

            // 3. Team Members (Strictly Active)
            if ($teamEnabled) {
                $tmQuery = TeamMember::where('is_active', true)
                    ->where(function ($q) use ($query) {
                        $q->where('name', 'LIKE', "%{$query}%")
                          ->orWhere('designation', 'LIKE', "%{$query}%")
                          ->orWhere('expertise', 'LIKE', "%{$query}%")
                          ->orWhere('skills', 'LIKE', "%{$query}%");
                    });
                $teamCount = (clone $tmQuery)->count();
                $team = $tmQuery->orderBy('order', 'asc')->get();
            }

            // 4. Gallery Items (Strictly Active)
            if ($galleryEnabled) {
                $galQuery = GalleryItem::where('is_active', true)
                    ->where(function ($q) use ($query) {
                        $q->where('title', 'LIKE', "%{$query}%")
                          ->orWhere('category', 'LIKE', "%{$query}%");
                    });
                $galleryCount = (clone $galQuery)->count();
                $gallery = $galQuery->orderBy('order', 'asc')->get();
            }

            // 5. Pages (Active Pages & Published Custom Pages Title/Subtitle match)
            $pagesList = collect();

            $staticSitePages = collect([
                ['title' => 'About Us - Innotech Medical', 'subtitle' => 'Company Mission, History & Facilities', 'url' => url('/about'), 'keywords' => ['about', 'company', 'mission', 'facilities', 'who we are'], 'active' => (Setting::get('section_about_enabled', '1') == '1')],
                ['title' => 'Contact Us & Help Desk', 'subtitle' => 'Book Appointment & Inquiries', 'url' => url('/contact'), 'keywords' => ['contact', 'helpdesk', 'phone', 'email', 'appointment', 'address', 'support'], 'active' => true],
                ['title' => 'Our Specialists & Healthcare Experts', 'subtitle' => 'Specialized Medical Consultants', 'url' => url('/specialists'), 'keywords' => ['specialist', 'specialists', 'doctor', 'doctors', 'consultant'], 'active' => $teamEnabled],
                ['title' => 'Medical Equipment & Laboratory Gallery', 'subtitle' => 'Biomedical Showcase', 'url' => url('/gallery'), 'keywords' => ['gallery', 'equipment', 'laboratory', 'photos', 'showcase'], 'active' => $galleryEnabled],
                ['title' => 'Blog & Clinical Research Articles', 'subtitle' => 'Latest Medical Insights & News', 'url' => url('/blog'), 'keywords' => ['blog', 'research', 'article', 'news', 'publication', 'insights'], 'active' => $blogEnabled],
            ]);

            foreach ($staticSitePages as $sPage) {
                if ($sPage['active']) {
                    $matches = (stripos($sPage['title'], $query) !== false) ||
                               (stripos($sPage['subtitle'], $query) !== false) ||
                               collect($sPage['keywords'])->contains(function($kw) use ($query) {
                                   return stripos($kw, $query) !== false || stripos($query, $kw) !== false;
                               });
                    if ($matches) {
                        $pagesList->push((object)[
                            'id' => 'static_' . Str::slug($sPage['title']),
                            'title' => $sPage['title'],
                            'subtitle' => $sPage['subtitle'],
                            'url' => $sPage['url'],
                            'content' => 'Explore the official ' . $sPage['title'] . ' section on Innotech Medical Pvt Ltd.',
                            'is_custom' => false
                        ]);
                    }
                }
            }

            $customPages = Page::where('is_published', true)
                ->where(function ($q) use ($query) {
                    $q->where('title', 'LIKE', "%{$query}%")
                      ->orWhere('subtitle', 'LIKE', "%{$query}%");
                })
                ->orderBy('order', 'asc')
                ->get();

            foreach ($customPages as $cp) {
                $pagesList->push((object)[
                    'id' => $cp->id,
                    'title' => $cp->title,
                    'subtitle' => $cp->subtitle,
                    'url' => route('page.show', $cp->slug),
                    'content' => $cp->content,
                    'is_custom' => true
                ]);
            }

            $pages = $pagesList;
            $pagesCount = $pages->count();
        }

        $totalResults = $servicesCount + $blogsCount + $teamCount + $galleryCount + $pagesCount;

        return view('search', compact(
            'query',
            'activeTab',
            'services',
            'blogs',
            'team',
            'gallery',
            'pages',
            'servicesCount',
            'blogsCount',
            'teamCount',
            'galleryCount',
            'pagesCount',
            'totalResults'
        ));
    }
}
