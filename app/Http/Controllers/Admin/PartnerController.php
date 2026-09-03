<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Helpers\UploadHelper;
use App\Models\Partner;
use Illuminate\Http\Request;

class PartnerController extends Controller
{
    public function index()
    {
        return redirect()->route('admin.home_sections.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'url' => 'nullable|string|max:255',
            'order' => 'integer',
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = UploadHelper::uploadImage($request->file('logo'), 'uploads/partners');
        }

        Partner::create($validated);
        return back()->with('success', 'Brand Partner added successfully!');
    }

    public function destroy(Partner $partner)
    {
        $partner->delete();
        return back()->with('success', 'Partner removed successfully.');
    }
}
