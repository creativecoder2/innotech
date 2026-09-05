<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\UploadHelper;
use App\Models\Setting;
use App\Models\Service;
use App\Models\GalleryItem;
use App\Models\TeamMember;
use App\Models\Testimonial;
use App\Models\Partner;
use App\Models\Blog;
use App\Models\NavMenu;
use Illuminate\Support\Str;

class HomeSectionsController extends Controller
{
    public function index(Request $request)
    {
        $settings = Setting::getAllAsArray();

        $navMenus = NavMenu::whereNull('parent_id')->with('children')->orderBy('order', 'asc')->get();
        $allNavMenus = NavMenu::orderBy('order', 'asc')->get();
        $allServices = Service::orderBy('title', 'asc')->get();
        $allBlogs = Blog::orderBy('title', 'asc')->get();

        $services = Service::orderBy('order', 'asc')->paginate(10, ['*'], 'services_page');
        $galleryItems = GalleryItem::orderBy('order', 'asc')->paginate(12, ['*'], 'gallery_page');
        $teamMembers = TeamMember::orderBy('order', 'asc')->paginate(8, ['*'], 'team_page');
        $testimonials = Testimonial::orderBy('order', 'asc')->paginate(6, ['*'], 'testimonial_page');
        $partners = Partner::orderBy('order', 'asc')->paginate(12, ['*'], 'partner_page');
        $blogs = Blog::orderBy('created_at', 'desc')->paginate(6, ['*'], 'blog_page');

        return view('admin.home_sections.index', compact(
            'settings',
            'navMenus',
            'allNavMenus',
            'allServices',
            'allBlogs',
            'services',
            'galleryItems',
            'teamMembers',
            'testimonials',
            'partners',
            'blogs'
        ));
    }

    public function saveSection(Request $request)
    {
        try {
            $data = $request->except(['_token', 'active_tab', 'files']);

            $fileKeys = [
                'logo_path',
                'banner_image',
                'about_image',
                'about_shape_1',
                'about_shape_2',
                'about_mission_img1',
                'about_mission_img2',
                'about_value_img1',
                'about_value_img2',
                'appointment_image',
                'footer_logo',
                'banner_video_file',
                'about_banner_image',
                'gallery_banner_image',
                'contact_banner_image',
                'blog_banner_image',
                'services_banner_image',
                'team_banner_image',
                'sidebar_logo',
                'sidebar_gallery_img_1',
                'sidebar_gallery_img_2',
                'sidebar_gallery_img_3',
                'banner_slider_images'
            ];
            
            // Remove file keys from standard data array so unselected file inputs do NOT overwrite existing image/video paths
            foreach ($fileKeys as $fKey) {
                unset($data[$fKey]);
            }

            // Handle multiple banner slider image uploads
            if ($request->hasFile('banner_slider_images')) {
                $sliderFiles = $request->file('banner_slider_images');
                if (is_array($sliderFiles)) {
                    $existingSlider = json_decode(Setting::get('banner_slider_images', '[]'), true) ?: [];
                    foreach ($sliderFiles as $sFile) {
                        if ($sFile && $sFile->isValid()) {
                            $path = UploadHelper::uploadImage($sFile, 'uploads/sections');
                            $existingSlider[] = $path;
                        }
                    }
                    Setting::set('banner_slider_images', json_encode(array_values($existingSlider)));
                }
            }

            $uploadedImages = [];
            // Handle file uploads with universal format support
            foreach ($request->allFiles() as $key => $file) {
                if ($key === 'banner_slider_images') {
                    continue;
                }
                if ($file && $file->isValid()) {
                    if ($key === 'banner_video_file') {
                        $path = UploadHelper::uploadFile($file, 'uploads/videos');
                    } else {
                        $path = UploadHelper::uploadImage($file, 'uploads/sections');
                    }
                    $data[$key] = $path;
                    $uploadedImages[$key] = asset($path);
                }
            }

            foreach ($data as $key => $value) {
                if ($value !== null) {
                    Setting::set($key, $value);
                }
            }

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Section settings and images saved successfully!',
                    'uploaded_images' => $uploadedImages,
                    'data' => $data
                ]);
            }

            return redirect()->route('admin.home_sections.index', ['tab' => $request->get('active_tab', 'banner')])
                ->with('success', 'Section settings and images saved successfully!');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to update section: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Error updating section: ' . $e->getMessage());
        }
    }

    public function toggleSection(Request $request)
    {
        $request->validate([
            'section_key' => 'required|string',
            'status' => 'required',
        ]);

        $key = $request->section_key;
        $status = $request->status ? '1' : '0';

        Setting::set($key, $status);

        return response()->json([
            'status' => 'success',
            'message' => 'Section visibility toggled successfully!',
            'new_status' => $status
        ]);
    }

    // GALLERY CRUD
    public function storeGallery(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
        ]);

        $imagePath = 'assets/img/gallery/gal-thum-01.jpg';
        if ($request->hasFile('image')) {
            $imagePath = UploadHelper::uploadImage($request->file('image'), 'uploads/gallery');
        }

        GalleryItem::create([
            'title' => $request->title,
            'category' => $request->category,
            'image' => $imagePath,
            'link' => $request->link,
            'order' => (int)$request->order,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.home_sections.index', ['tab' => 'gallery'])
            ->with('success', 'Gallery item added successfully!');
    }

    public function updateGallery(Request $request, $id)
    {
        $item = GalleryItem::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('image')) {
            $item->image = UploadHelper::uploadImage($request->file('image'), 'uploads/gallery');
        }

        $item->title = $request->title;
        $item->category = $request->category;
        $item->link = $request->link;
        $item->order = (int)$request->order;
        $item->is_active = $request->has('is_active');
        $item->save();

        return redirect()->route('admin.home_sections.index', ['tab' => 'gallery'])
            ->with('success', 'Gallery item updated successfully!');
    }

    public function deleteGallery($id)
    {
        $item = GalleryItem::findOrFail($id);
        $item->delete();

        return redirect()->route('admin.home_sections.index', ['tab' => 'gallery'])
            ->with('success', 'Gallery item deleted successfully!');
    }

    // TEAM CRUD
    public function storeTeam(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
        ]);

        $imagePath = 'assets/img/team/team-thumb-01.jpg';
        if ($request->hasFile('image')) {
            $imagePath = UploadHelper::uploadImage($request->file('image'), 'uploads/team');
        }

        $slug = $request->slug ? Str::slug($request->slug) : Str::slug($request->name);
        // Ensure unique slug
        $count = TeamMember::where('slug', $slug)->count();
        if ($count > 0) {
            $slug = $slug . '-' . time();
        }

        TeamMember::create([
            'name' => $request->name,
            'slug' => $slug,
            'designation' => $request->designation,
            'expertise' => $request->expertise,
            'experience' => $request->experience,
            'email' => $request->email,
            'phone' => $request->phone,
            'bio' => $request->bio,
            'personal_experience' => $request->personal_experience,
            'skills' => $request->skills,
            'education' => $request->education,
            'awards' => $request->awards,
            'image' => $imagePath,
            'youtube_url' => $request->youtube_url,
            'twitter_url' => $request->twitter_url,
            'facebook_url' => $request->facebook_url,
            'instagram_url' => $request->instagram_url,
            'pinterest_url' => $request->pinterest_url,
            'skype_url' => $request->skype_url,
            'order' => (int)$request->order,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.home_sections.index', ['tab' => 'team'])
            ->with('success', 'Specialist team member added successfully!');
    }

    public function updateTeam(Request $request, $id)
    {
        $member = TeamMember::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
        ]);

        if ($request->hasFile('image')) {
            $member->image = UploadHelper::uploadImage($request->file('image'), 'uploads/team');
        }

        if ($request->slug) {
            $member->slug = Str::slug($request->slug);
        } elseif (empty($member->slug)) {
            $member->slug = Str::slug($request->name);
        }

        $member->name = $request->name;
        $member->designation = $request->designation;
        $member->expertise = $request->expertise;
        $member->experience = $request->experience;
        $member->email = $request->email;
        $member->phone = $request->phone;
        $member->bio = $request->bio;
        $member->personal_experience = $request->personal_experience;
        $member->skills = $request->skills;
        $member->education = $request->education;
        $member->awards = $request->awards;
        $member->youtube_url = $request->youtube_url;
        $member->twitter_url = $request->twitter_url;
        $member->facebook_url = $request->facebook_url;
        $member->instagram_url = $request->instagram_url;
        $member->pinterest_url = $request->pinterest_url;
        $member->skype_url = $request->skype_url;
        $member->order = (int)$request->order;
        $member->is_active = $request->has('is_active');
        $member->save();

        return redirect()->route('admin.home_sections.index', ['tab' => 'team'])
            ->with('success', 'Specialist team member updated successfully!');
    }

    public function deleteTeam($id)
    {
        $member = TeamMember::findOrFail($id);
        $member->delete();

        return redirect()->route('admin.home_sections.index', ['tab' => 'team'])
            ->with('success', 'Team member deleted successfully!');
    }

    // TESTIMONIALS CRUD
    public function storeTestimonial(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $avatarPath = 'assets/img/icon/testi-ava-01.jpg';
        if ($request->hasFile('avatar')) {
            $avatarPath = UploadHelper::uploadImage($request->file('avatar'), 'uploads/testimonials');
        }

        Testimonial::create([
            'name' => $request->name,
            'designation' => $request->designation,
            'hospital' => $request->hospital,
            'content' => $request->content,
            'rating' => (int)($request->rating ?: 5),
            'avatar' => $avatarPath,
            'order' => (int)$request->order,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.home_sections.index', ['tab' => 'testimonial'])
            ->with('success', 'Testimonial added successfully!');
    }

    public function updateTestimonial(Request $request, $id)
    {
        $test = Testimonial::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        if ($request->hasFile('avatar')) {
            $test->avatar = UploadHelper::uploadImage($request->file('avatar'), 'uploads/testimonials');
        }

        $test->name = $request->name;
        $test->designation = $request->designation;
        $test->hospital = $request->hospital;
        $test->content = $request->content;
        $test->rating = (int)($request->rating ?: 5);
        $test->order = (int)$request->order;
        $test->is_active = $request->has('is_active');
        $test->save();

        return redirect()->route('admin.home_sections.index', ['tab' => 'testimonial'])
            ->with('success', 'Testimonial updated successfully!');
    }

    public function deleteTestimonial($id)
    {
        $test = Testimonial::findOrFail($id);
        $test->delete();

        return redirect()->route('admin.home_sections.index', ['tab' => 'testimonial'])
            ->with('success', 'Testimonial deleted successfully!');
    }

    // PARTNERS CRUD
    public function storePartner(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $logoPath = 'assets/img/brand/brand-01.png';
        if ($request->hasFile('logo')) {
            $logoPath = UploadHelper::uploadImage($request->file('logo'), 'uploads/partners');
        }

        Partner::create([
            'name' => $request->name,
            'url' => $request->url,
            'logo' => $logoPath,
            'order' => (int)$request->order,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.home_sections.index', ['tab' => 'partner'])
            ->with('success', 'Brand partner added successfully!');
    }

    public function updatePartner(Request $request, $id)
    {
        $partner = Partner::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        if ($request->hasFile('logo')) {
            $partner->logo = UploadHelper::uploadImage($request->file('logo'), 'uploads/partners');
        }

        $partner->name = $request->name;
        $partner->url = $request->url;
        $partner->order = (int)$request->order;
        $partner->is_active = $request->has('is_active');
        $partner->save();

        return redirect()->route('admin.home_sections.index', ['tab' => 'partner'])
            ->with('success', 'Brand partner updated successfully!');
    }

    public function deletePartner($id)
    {
        $partner = Partner::findOrFail($id);
        $partner->delete();

        return redirect()->route('admin.home_sections.index', ['tab' => 'partner'])
            ->with('success', 'Partner removed successfully!');
    }

    // LIVE AJAX TOGGLE FOR ALL SECTIONS
    public function toggleItem(Request $request)
    {
        $type = $request->get('type');
        $id = $request->get('id');
        $field = $request->get('field', 'is_active');

        switch ($type) {
            case 'service':
                $item = Service::findOrFail($id);
                break;
            case 'gallery':
                $item = GalleryItem::findOrFail($id);
                break;
            case 'team':
                $item = TeamMember::findOrFail($id);
                break;
            case 'testimonial':
                $item = Testimonial::findOrFail($id);
                break;
            case 'partner':
                $item = Partner::findOrFail($id);
                break;
            case 'blog':
                $item = Blog::findOrFail($id);
                $field = 'is_published';
                break;
            default:
                return response()->json(['status' => 'error', 'message' => 'Invalid item type'], 400);
        }

        $item->{$field} = !$item->{$field};
        $item->save();

        return response()->json([
            'status' => 'success',
            'new_status' => (bool)$item->{$field},
            'message' => 'Status updated successfully!'
        ]);
    }

    /**
     * Delete a single image from the banner hero slider
     */
    public function deleteBannerSliderImage(Request $request)
    {
        $imagePath = $request->input('image_path');
        if (!empty($imagePath)) {
            $sliderImages = json_decode(Setting::get('banner_slider_images', '[]'), true) ?: [];
            $sliderImages = array_values(array_filter($sliderImages, function ($img) use ($imagePath) {
                return $img !== $imagePath;
            }));
            Setting::set('banner_slider_images', json_encode($sliderImages));

            // Delete physical file if inside uploads directory
            $fullPath = public_path($imagePath);
            if (file_exists($fullPath) && str_contains($imagePath, 'uploads/')) {
                @unlink($fullPath);
            }

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Banner slider image deleted successfully!',
                    'remaining_images' => $sliderImages,
                ]);
            }

            return back()->with('success', 'Banner slider image deleted successfully!');
        }

        return back()->with('error', 'Image path is missing.');
    }
}
