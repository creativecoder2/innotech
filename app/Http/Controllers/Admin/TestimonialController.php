<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function index()
    {
        return redirect()->route('admin.home_sections.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'designation' => 'nullable|string|max:255',
            'hospital' => 'nullable|string|max:255',
            'content' => 'required|string',
            'rating' => 'integer|min:1|max:5',
            'order' => 'integer',
        ]);

        if ($request->hasFile('avatar')) {
            $validated['avatar'] = \App\Helpers\UploadHelper::uploadImage($request->file('avatar'), 'uploads/testimonials');
        } else {
            $validated['avatar'] = 'assets/img/icon/testi-ava-01.jpg';
        }

        Testimonial::create($validated);
        return back()->with('success', 'Testimonial added successfully!');
    }

    public function update(Request $request, Testimonial $testimonial)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'designation' => 'nullable|string|max:255',
            'hospital' => 'nullable|string|max:255',
            'content' => 'required|string',
            'rating' => 'integer|min:1|max:5',
            'order' => 'integer',
        ]);

        if ($request->hasFile('avatar')) {
            $validated['avatar'] = \App\Helpers\UploadHelper::uploadImage($request->file('avatar'), 'uploads/testimonials');
        }

        $testimonial->update($validated);
        return back()->with('success', 'Testimonial updated successfully!');
    }

    public function destroy(Testimonial $testimonial)
    {
        $testimonial->delete();
        return back()->with('success', 'Testimonial deleted successfully.');
    }
}
