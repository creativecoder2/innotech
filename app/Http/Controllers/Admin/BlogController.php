<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Helpers\UploadHelper;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $query = Blog::query();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('title', 'LIKE', "%{$s}%")
                  ->orWhere('author', 'LIKE', "%{$s}%")
                  ->orWhere('category', 'LIKE', "%{$s}%")
                  ->orWhere('tags', 'LIKE', "%{$s}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $blogs = $query->orderBy('published_at', 'desc')->paginate(10)->withQueryString();
        $categories = Blog::select('category')->distinct()->pluck('category');

        return view('admin.blogs.index', compact('blogs', 'categories'));
    }

    public function create()
    {
        return view('admin.blogs.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'tags' => 'nullable|string|max:255',
            'author' => 'required|string|max:100',
            'summary' => 'required|string|max:600',
            'content' => 'required|string',
            'status' => 'required|in:draft,published',
            'published_at' => 'nullable|date',
            'video_url' => 'nullable|string|max:255',
            'quote' => 'nullable|string',
            'quote_author' => 'nullable|string|max:150',
            'approach_title' => 'nullable|string|max:255',
            'approach_text' => 'nullable|string',
            'approach_points' => 'nullable|string',
            'views' => 'nullable|integer|min:0',
        ]);

        $validated['slug'] = Str::slug($request->title);
        // Ensure slug uniqueness
        $slugBase = $validated['slug'];
        $count = 1;
        while (Blog::where('slug', $validated['slug'])->exists()) {
            $validated['slug'] = $slugBase . '-' . $count++;
        }

        $validated['published_at'] = $request->published_at ?: now();
        $validated['is_published'] = ($request->status === 'published');
        $validated['views'] = $request->input('views', rand(120, 850));

        // Featured Image
        if ($request->hasFile('image')) {
            $validated['image'] = UploadHelper::uploadImage($request->file('image'), 'uploads/blogs');
        }

        // Meta Sub-Image 1
        if ($request->hasFile('meta_image_1')) {
            $validated['meta_image_1'] = UploadHelper::uploadImage($request->file('meta_image_1'), 'uploads/blogs');
        }

        // Meta Sub-Image 2
        if ($request->hasFile('meta_image_2')) {
            $validated['meta_image_2'] = UploadHelper::uploadImage($request->file('meta_image_2'), 'uploads/blogs');
        }

        // Slider Gallery Images
        if ($request->hasFile('slider_images')) {
            $sliderList = [];
            foreach ($request->file('slider_images') as $sFile) {
                if ($sFile && $sFile->isValid()) {
                    $sliderList[] = UploadHelper::uploadImage($sFile, 'uploads/blogs/gallery');
                }
            }
            if (!empty($sliderList)) {
                $validated['slider_images'] = $sliderList;
            }
        }

        Blog::create($validated);

        return redirect()->route('admin.blogs.index')->with('success', 'Article / Blog post published successfully!');
    }

    public function edit(Blog $blog)
    {
        return view('admin.blogs.edit', compact('blog'));
    }

    public function update(Request $request, Blog $blog)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'tags' => 'nullable|string|max:255',
            'author' => 'required|string|max:100',
            'summary' => 'required|string|max:600',
            'content' => 'required|string',
            'status' => 'required|in:draft,published',
            'published_at' => 'nullable|date',
            'video_url' => 'nullable|string|max:255',
            'quote' => 'nullable|string',
            'quote_author' => 'nullable|string|max:150',
            'approach_title' => 'nullable|string|max:255',
            'approach_text' => 'nullable|string',
            'approach_points' => 'nullable|string',
            'views' => 'nullable|integer|min:0',
        ]);

        if ($blog->title !== $request->title) {
            $slugBase = Str::slug($request->title);
            $slug = $slugBase;
            $count = 1;
            while (Blog::where('slug', $slug)->where('id', '!=', $blog->id)->exists()) {
                $slug = $slugBase . '-' . $count++;
            }
            $validated['slug'] = $slug;
        }

        $validated['is_published'] = ($request->status === 'published');

        // Featured Image
        if ($request->hasFile('image')) {
            $validated['image'] = UploadHelper::uploadImage($request->file('image'), 'uploads/blogs');
        }

        // Meta Image 1
        if ($request->hasFile('meta_image_1')) {
            $validated['meta_image_1'] = UploadHelper::uploadImage($request->file('meta_image_1'), 'uploads/blogs');
        }

        // Meta Image 2
        if ($request->hasFile('meta_image_2')) {
            $validated['meta_image_2'] = UploadHelper::uploadImage($request->file('meta_image_2'), 'uploads/blogs');
        }

        // Slider Gallery Images
        if ($request->hasFile('slider_images')) {
            $sliderList = is_array($blog->slider_images) ? $blog->slider_images : [];
            foreach ($request->file('slider_images') as $sFile) {
                if ($sFile && $sFile->isValid()) {
                    $sliderList[] = UploadHelper::uploadImage($sFile, 'uploads/blogs/gallery');
                }
            }
            $validated['slider_images'] = $sliderList;
        }

        $blog->update($validated);

        return redirect()->route('admin.blogs.index')->with('success', 'Article updated successfully!');
    }

    public function toggle(Blog $blog)
    {
        $newStatus = ($blog->status === 'published') ? 'draft' : 'published';
        $blog->update([
            'status' => $newStatus,
            'is_published' => ($newStatus === 'published')
        ]);

        return response()->json([
            'success' => true,
            'status' => $newStatus,
            'message' => 'Status updated to ' . ucfirst($newStatus)
        ]);
    }

    public function destroy(Blog $blog)
    {
        $blog->delete();
        return redirect()->route('admin.blogs.index')->with('success', 'Article deleted successfully.');
    }
}
