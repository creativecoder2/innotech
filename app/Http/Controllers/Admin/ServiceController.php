<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Helpers\UploadHelper;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    public function index()
    {
        return redirect()->route('admin.home_sections.index');
    }

    public function create()
    {
        return view('admin.services.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'icon' => 'nullable|string|max:100',
            'short_description' => 'required|string',
            'description' => 'nullable|string',
            'process_title' => 'nullable|string|max:255',
            'banner_subtitle' => 'nullable|string|max:255',
            'features' => 'nullable|string',
            'steps_title' => 'nullable|string|max:255',
            'steps_description' => 'nullable|string',
            'step_1_title' => 'nullable|string|max:255',
            'step_1_points' => 'nullable|string',
            'step_2_title' => 'nullable|string|max:255',
            'step_2_points' => 'nullable|string',
            'step_3_title' => 'nullable|string|max:255',
            'step_3_points' => 'nullable|string',
            'step_4_title' => 'nullable|string|max:255',
            'step_4_points' => 'nullable|string',
            'research_title' => 'nullable|string|max:255',
            'research_description' => 'nullable|string',
            'bottom_link_text' => 'nullable|string|max:255',
            'bottom_link_url' => 'nullable|string|max:255',
            'is_featured' => 'nullable',
            'is_active' => 'nullable',
            'order' => 'nullable|integer',
        ]);

        $validated['slug'] = Str::slug($request->title);
        // Ensure unique slug
        $originalSlug = $validated['slug'];
        $count = 1;
        while (Service::where('slug', $validated['slug'])->exists()) {
            $validated['slug'] = $originalSlug . '-' . $count++;
        }

        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_active'] = $request->has('is_active') ? true : ($request->exists('is_active') ? false : true);
        $validated['order'] = $request->input('order', 0);

        if ($request->hasFile('image')) {
            $validated['image'] = UploadHelper::uploadImage($request->file('image'), 'uploads/services');
        }
        if ($request->hasFile('image_2')) {
            $validated['image_2'] = UploadHelper::uploadImage($request->file('image_2'), 'uploads/services');
        }
        if ($request->hasFile('banner_image')) {
            $validated['banner_image'] = UploadHelper::uploadImage($request->file('banner_image'), 'uploads/services');
        }
        if ($request->hasFile('research_image')) {
            $validated['research_image'] = UploadHelper::uploadImage($request->file('research_image'), 'uploads/services');
        }

        $service = Service::create($validated);

        return redirect()->route('admin.services.edit', $service->id)->with('success', 'Medical Service added successfully with full detail page data!');
    }

    public function edit(Service $service)
    {
        return view('admin.services.edit', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'icon' => 'nullable|string|max:100',
            'short_description' => 'required|string',
            'description' => 'nullable|string',
            'process_title' => 'nullable|string|max:255',
            'banner_subtitle' => 'nullable|string|max:255',
            'features' => 'nullable|string',
            'steps_title' => 'nullable|string|max:255',
            'steps_description' => 'nullable|string',
            'step_1_title' => 'nullable|string|max:255',
            'step_1_points' => 'nullable|string',
            'step_2_title' => 'nullable|string|max:255',
            'step_2_points' => 'nullable|string',
            'step_3_title' => 'nullable|string|max:255',
            'step_3_points' => 'nullable|string',
            'step_4_title' => 'nullable|string|max:255',
            'step_4_points' => 'nullable|string',
            'research_title' => 'nullable|string|max:255',
            'research_description' => 'nullable|string',
            'bottom_link_text' => 'nullable|string|max:255',
            'bottom_link_url' => 'nullable|string|max:255',
            'is_featured' => 'nullable',
            'is_active' => 'nullable',
            'order' => 'nullable|integer',
        ]);

        $validated['slug'] = Str::slug($request->title);
        // Ensure unique slug excluding current service
        $originalSlug = $validated['slug'];
        $count = 1;
        while (Service::where('slug', $validated['slug'])->where('id', '!=', $service->id)->exists()) {
            $validated['slug'] = $originalSlug . '-' . $count++;
        }

        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_active'] = $request->has('is_active');
        $validated['order'] = $request->input('order', 0);

        if ($request->hasFile('image')) {
            $validated['image'] = UploadHelper::uploadImage($request->file('image'), 'uploads/services');
        }
        if ($request->hasFile('image_2')) {
            $validated['image_2'] = UploadHelper::uploadImage($request->file('image_2'), 'uploads/services');
        }
        if ($request->hasFile('banner_image')) {
            $validated['banner_image'] = UploadHelper::uploadImage($request->file('banner_image'), 'uploads/services');
        }
        if ($request->hasFile('research_image')) {
            $validated['research_image'] = UploadHelper::uploadImage($request->file('research_image'), 'uploads/services');
        }

        $service->update($validated);

        return redirect()->route('admin.services.edit', $service->id)->with('success', 'Medical Service and Detail Page updated successfully!');
    }

    public function destroy(Service $service)
    {
        $service->delete();
        return redirect()->route('admin.home_sections.index')->with('success', 'Service deleted successfully.');
    }
}

