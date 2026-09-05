<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\Slider;
use App\Models\Service;
use App\Models\Blog;
use App\Models\Testimonial;
use App\Models\Partner;
use App\Models\GalleryItem;
use App\Models\TeamMember;
use App\Models\Inquiry;
use App\Models\BlogComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        $sliders = Slider::where('is_active', true)->orderBy('order', 'asc')->get();
        $services = Service::where('is_active', true)->orderBy('order', 'asc')->get();
        $galleryItems = GalleryItem::where('is_active', true)->orderBy('order', 'asc')->get();
        $teamMembers = TeamMember::where('is_active', true)->orderBy('order', 'asc')->get();
        $blogs = Blog::where('is_published', true)->orderBy('published_at', 'desc')->take(6)->get();
        $testimonials = Testimonial::where('is_active', true)->orderBy('order', 'asc')->get();
        $partners = Partner::where('is_active', true)->orderBy('order', 'asc')->get();

        return view('home', compact(
            'sliders',
            'services',
            'galleryItems',
            'teamMembers',
            'blogs',
            'testimonials',
            'partners'
        ));
    }

    public function services()
    {
        $services = Service::where('is_active', true)->orderBy('order', 'asc')->get();
        return view('services', compact('services'));
    }

    public function serviceDetail($slug)
    {
        $service = Service::where('slug', $slug)->firstOrFail();
        $allServices = Service::where('is_active', true)->orderBy('order', 'asc')->get();
        return view('service_detail', compact('service', 'allServices'));
    }

    public function blog(Request $request)
    {
        $search = trim($request->input('search', ''));
        $categoryFilter = trim($request->input('category', ''));
        $tagFilter = trim($request->input('tag', ''));

        $query = Blog::where('is_published', true);

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('summary', 'LIKE', "%{$search}%")
                  ->orWhere('content', 'LIKE', "%{$search}%")
                  ->orWhere('author', 'LIKE', "%{$search}%")
                  ->orWhere('tags', 'LIKE', "%{$search}%");
            });
        }

        if ($categoryFilter !== '') {
            $query->where('category', $categoryFilter);
        }

        if ($tagFilter !== '') {
            $query->where('tags', 'LIKE', "%{$tagFilter}%");
        }

        $blogs = $query->paginate(3)->withQueryString();

        // Dynamic categories with real counts
        $categories = Blog::where('is_published', true)
            ->select('category', DB::raw('count(*) as count'))
            ->groupBy('category')
            ->orderBy('count', 'desc')
            ->get();

        // Recent 4 blogs for sidebar
        $recentBlogs = Blog::where('is_published', true)
            ->orderBy('published_at', 'desc')
            ->take(4)
            ->get();

        // Dynamic tags from published blogs
        $popularTags = Blog::where('is_published', true)
            ->whereNotNull('tags')
            ->pluck('tags')
            ->flatMap(function ($tags) {
                return array_map('trim', explode(',', $tags));
            })
            ->filter()
            ->unique()
            ->take(12)
            ->values();

        if ($popularTags->isEmpty()) {
            $popularTags = collect(['Covid-19', 'Biomedical', 'Critical Care', 'Lab Testing', 'Medicine', 'Surgery', 'Diagnostic', 'Quality']);
        }

        return view('blog', compact(
            'blogs',
            'categories',
            'recentBlogs',
            'popularTags',
            'search',
            'categoryFilter',
            'tagFilter'
        ));
    }

    public function blogDetail($slug)
    {
        $blog = Blog::where('slug', $slug)->firstOrFail();

        // Increment view count
        $blog->increment('views');

        // Previous & Next navigation
        $prevBlog = Blog::where('is_published', true)
            ->where('id', '<', $blog->id)
            ->orderBy('id', 'desc')
            ->first();

        $nextBlog = Blog::where('is_published', true)
            ->where('id', '>', $blog->id)
            ->orderBy('id', 'asc')
            ->first();

        // Recent posts excluding current
        $recentBlogs = Blog::where('id', '!=', $blog->id)
            ->where('is_published', true)
            ->orderBy('published_at', 'desc')
            ->take(4)
            ->get();

        // Categories with counts
        $categories = Blog::where('is_published', true)
            ->select('category', DB::raw('count(*) as count'))
            ->groupBy('category')
            ->orderBy('count', 'desc')
            ->get();

        // Popular Tags
        $popularTags = Blog::where('is_published', true)
            ->whereNotNull('tags')
            ->pluck('tags')
            ->flatMap(function ($tags) {
                return array_map('trim', explode(',', $tags));
            })
            ->filter()
            ->unique()
            ->take(12)
            ->values();

        if ($popularTags->isEmpty()) {
            $popularTags = collect(['Covid-19', 'Biomedical', 'Critical Care', 'Lab Testing', 'Medicine', 'Surgery', 'Diagnostic', 'Quality']);
        }

        return view('blog_detail', compact(
            'blog',
            'prevBlog',
            'nextBlog',
            'recentBlogs',
            'categories',
            'popularTags'
        ));
    }

    public function blogComment(Request $request, $slug)
    {
        $blog = Blog::where('slug', $slug)->firstOrFail();

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:150',
            'phone' => 'nullable|string|max:50',
            'website' => 'nullable|string|max:150',
            'comment' => 'required|string|max:2000',
        ]);

        // Store as pending comment awaiting admin approval
        BlogComment::create([
            'blog_id' => $blog->id,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'website' => $validated['website'] ?? null,
            'comment' => $validated['comment'],
            'status' => 'pending',
            'is_approved' => false,
        ]);

        // Also create notification lead in inquiries
        Inquiry::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'service_interested' => 'Blog: ' . $blog->category,
            'subject' => 'New Comment on Blog (Pending Approval): ' . \Illuminate\Support\Str::limit($blog->title, 50),
            'message' => "Comment submitted by {$validated['name']} ({$validated['email']}):\n\n\"{$validated['comment']}\"\n\nPlease review and approve/reject in Admin Panel -> Blog Comments.",
            'status' => 'unread',
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Thank you! Your comment has been submitted and will appear on the website once approved by an administrator.'
            ]);
        }

        return redirect()->back()->with('comment_success', 'Thank you! Your comment has been submitted and will appear on the website once approved by an administrator.');
    }

    public function about()
    {
        $testimonials = Testimonial::where('is_active', true)->orderBy('order', 'asc')->get();
        $partners = Partner::where('is_active', true)->orderBy('order', 'asc')->get();
        $teamMembers = TeamMember::where('is_active', true)->orderBy('order', 'asc')->get();
        return view('about', compact('testimonials', 'partners', 'teamMembers'));
    }

    public function contact()
    {
        return view('contact');
    }

    public function gallery()
    {
        $galleryItems = GalleryItem::where('is_active', true)->orderBy('order', 'asc')->get();
        $categories = $galleryItems->pluck('category')->filter()->unique()->values();
        return view('gallery', compact('galleryItems', 'categories'));
    }

    public function team()
    {
        if (\App\Models\Setting::get('section_team_enabled', '1') != '1') {
            abort(404);
        }
        $teamMembers = TeamMember::where('is_active', true)->orderBy('order', 'asc')->get();
        return view('team', compact('teamMembers'));
    }

    public function teamDetail($slug)
    {
        if (\App\Models\Setting::get('section_team_enabled', '1') != '1') {
            abort(404);
        }
        $member = TeamMember::where('slug', $slug)->orWhere('id', $slug)->firstOrFail();
        $otherTeamMembers = TeamMember::where('id', '!=', $member->id)->where('is_active', true)->orderBy('order', 'asc')->take(4)->get();
        return view('team_detail', compact('member', 'otherTeamMembers'));
    }
}
