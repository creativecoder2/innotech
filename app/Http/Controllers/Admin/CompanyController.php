<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Helpers\UploadHelper;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CompanyController extends Controller
{
    public function index()
    {
        $companies = Company::withCount('products')->orderBy('order', 'asc')->orderBy('id', 'desc')->paginate(15);
        return view('admin.companies.index', compact('companies'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'country' => 'nullable|string|max:100',
            'website' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:2000',
            'order' => 'nullable|integer',
            'is_active' => 'nullable',
        ]);

        $slug = Str::slug($validated['name']);
        $originalSlug = $slug;
        $counter = 1;
        while (Company::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter++;
        }

        Company::create([
            'name' => $validated['name'],
            'slug' => $slug,
            'country' => $validated['country'] ?? null,
            'website' => $validated['website'] ?? null,
            'description' => $validated['description'] ?? null,
            'order' => $request->input('order', 0),
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.companies.index')->with('success', 'Company manufacturer added successfully!');
    }

    public function update(Request $request, Company $company)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'country' => 'nullable|string|max:100',
            'website' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:2000',
            'order' => 'nullable|integer',
            'is_active' => 'nullable',
        ]);

        $data = [
            'name' => $validated['name'],
            'country' => $validated['country'] ?? null,
            'website' => $validated['website'] ?? null,
            'description' => $validated['description'] ?? null,
            'order' => $request->input('order', 0),
            'is_active' => $request->has('is_active'),
        ];

        $company->update($data);

        return redirect()->route('admin.companies.index')->with('success', 'Company details updated successfully!');
    }

    public function destroy(Company $company)
    {
        $company->delete();
        return redirect()->route('admin.companies.index')->with('success', 'Company deleted successfully!');
    }

    public function toggle(Company $company)
    {
        $company->is_active = !$company->is_active;
        $company->save();

        return response()->json([
            'success' => true,
            'is_active' => $company->is_active,
            'message' => 'Company status updated!'
        ]);
    }
}
