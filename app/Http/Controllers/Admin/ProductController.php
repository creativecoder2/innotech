<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Helpers\UploadHelper;
use App\Models\Product;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search = trim($request->input('search', ''));
        $companyFilter = $request->input('company_id');

        $query = Product::with('company');

        if (!empty($companyFilter)) {
            $query->where('company_id', $companyFilter);
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('sku', 'LIKE', "%{$search}%")
                  ->orWhere('short_description', 'LIKE', "%{$search}%");
            });
        }

        $products = $query->orderBy('order', 'asc')->orderBy('id', 'desc')->paginate(15)->withQueryString();
        $companies = Company::orderBy('name', 'asc')->get();

        return view('admin.products.index', compact('products', 'companies', 'search', 'companyFilter'));
    }

    public function create()
    {
        $companies = Company::where('is_active', true)->orderBy('name', 'asc')->get();
        return view('admin.products.create', compact('companies'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'company_id' => 'nullable|exists:companies,id',
            'sku' => 'nullable|string|max:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
            'short_description' => 'nullable|string|max:2000',
            'description' => 'nullable|string',
            'key_features' => 'nullable|string',
            'order' => 'nullable|integer',
            'is_featured' => 'nullable',
            'is_active' => 'nullable',
        ]);

        $slug = Str::slug($validated['title']);
        $originalSlug = $slug;
        $count = 1;
        while (Product::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = UploadHelper::uploadImage($request->file('image'), 'uploads/products');
        }

        // Format key features as JSON array from lines
        $featuresArray = [];
        if (!empty($validated['key_features'])) {
            $rawLines = preg_split("/\r\n|\n|\r/", $validated['key_features']);
            foreach ($rawLines as $line) {
                $line = trim($line, " \t\n\r\0\x0B-•*");
                if (!empty($line)) {
                    $featuresArray[] = $line;
                }
            }
        }

        Product::create([
            'title' => $validated['title'],
            'slug' => $slug,
            'company_id' => $validated['company_id'] ?: null,
            'sku' => $validated['sku'] ?? null,
            'image' => $imagePath,
            'short_description' => $validated['short_description'] ?? null,
            'description' => $validated['description'] ?? null,
            'key_features' => json_encode($featuresArray),
            'order' => $request->input('order', 0),
            'is_featured' => $request->has('is_featured'),
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Medical Product created successfully!');
    }

    public function edit(Product $product)
    {
        $companies = Company::orderBy('name', 'asc')->get();
        return view('admin.products.edit', compact('product', 'companies'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:products,slug,' . $product->id,
            'company_id' => 'nullable|exists:companies,id',
            'sku' => 'nullable|string|max:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
            'short_description' => 'nullable|string|max:2000',
            'description' => 'nullable|string',
            'key_features' => 'nullable|string',
            'order' => 'nullable|integer',
            'is_featured' => 'nullable',
            'is_active' => 'nullable',
        ]);

        $data = [
            'title' => $validated['title'],
            'slug' => Str::slug($validated['slug']),
            'company_id' => $validated['company_id'] ?: null,
            'sku' => $validated['sku'] ?? null,
            'short_description' => $validated['short_description'] ?? null,
            'description' => $validated['description'] ?? null,
            'order' => $request->input('order', 0),
            'is_featured' => $request->has('is_featured'),
            'is_active' => $request->has('is_active'),
        ];

        if ($request->hasFile('image')) {
            $data['image'] = UploadHelper::uploadImage($request->file('image'), 'uploads/products');
        }

        if (isset($validated['key_features'])) {
            $rawLines = preg_split("/\r\n|\n|\r/", $validated['key_features']);
            $featuresArray = [];
            foreach ($rawLines as $line) {
                $line = trim($line, " \t\n\r\0\x0B-•*");
                if (!empty($line)) {
                    $featuresArray[] = $line;
                }
            }
            $data['key_features'] = json_encode($featuresArray);
        }

        $product->update($data);

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully!');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully!');
    }

    public function toggle(Product $product)
    {
        $product->is_active = !$product->is_active;
        $product->save();

        return response()->json([
            'success' => true,
            'is_active' => $product->is_active,
            'message' => 'Product status updated!'
        ]);
    }
}
