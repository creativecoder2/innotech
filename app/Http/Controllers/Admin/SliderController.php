<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use App\Helpers\UploadHelper;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    public function index()
    {
        return redirect()->route('admin.home_sections.index');
    }

    public function create()
    {
        return view('admin.sliders.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'badge' => 'nullable|string|max:255',
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:500',
            'btn_text' => 'nullable|string|max:100',
            'btn_link' => 'nullable|string|max:255',
            'btn_secondary_text' => 'nullable|string|max:100',
            'btn_secondary_link' => 'nullable|string|max:255',
            'order' => 'integer',
            'is_active' => 'boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072'
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['order'] = $request->input('order', 0);

        if ($request->hasFile('image')) {
            $validated['image'] = UploadHelper::uploadImage($request->file('image'), 'uploads/sliders');
        }

        Slider::create($validated);

        return redirect()->route('admin.sliders.index')->with('success', 'Hero Banner / Slider created successfully!');
    }

    public function edit(Slider $slider)
    {
        return view('admin.sliders.edit', compact('slider'));
    }

    public function update(Request $request, Slider $slider)
    {
        $validated = $request->validate([
            'badge' => 'nullable|string|max:255',
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:500',
            'btn_text' => 'nullable|string|max:100',
            'btn_link' => 'nullable|string|max:255',
            'btn_secondary_text' => 'nullable|string|max:100',
            'btn_secondary_link' => 'nullable|string|max:255',
            'order' => 'integer',
            'is_active' => 'boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072'
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['order'] = $request->input('order', 0);

        if ($request->hasFile('image')) {
            $validated['image'] = UploadHelper::uploadImage($request->file('image'), 'uploads/sliders');
        }

        $slider->update($validated);

        return redirect()->route('admin.home_sections.index')->with('success', 'Hero Banner updated successfully!');
    }

    public function destroy(Slider $slider)
    {
        $slider->delete();
        return redirect()->route('admin.home_sections.index')->with('success', 'Banner removed successfully.');
    }
}
