<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Product;
use App\Models\Inquiry;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display the products catalog grid with company filtering
     */
    public function index(Request $request, $companySlug = null)
    {
        $search = trim($request->input('search', ''));
        $activeCompanySlug = $companySlug ?: trim($request->input('company', ''));

        $selectedCompany = null;
        if (!empty($activeCompanySlug)) {
            $selectedCompany = Company::where('slug', $activeCompanySlug)->where('is_active', true)->first();
        }

        $query = Product::with('company')->where('is_active', true);

        if ($selectedCompany) {
            $query->where('company_id', $selectedCompany->id);
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('sku', 'LIKE', "%{$search}%")
                  ->orWhere('short_description', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%")
                  ->orWhereHas('company', function ($cq) use ($search) {
                      $cq->where('name', 'LIKE', "%{$search}%")
                         ->orWhere('country', 'LIKE', "%{$search}%");
                  });
            });
        }

        $products = $query->orderBy('order', 'asc')->orderBy('id', 'desc')->paginate(12)->withQueryString();

        // All active companies with counts of active products
        $companies = Company::where('is_active', true)
            ->withCount(['activeProducts' => function ($q) {
                $q->where('is_active', true);
            }])
            ->orderBy('order', 'asc')
            ->get();

        $totalProductsCount = Product::where('is_active', true)->count();

        if ($request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            $gridHtml = view('products._grid', compact('products', 'selectedCompany', 'search'))->render();
            $metaHtml = view('products._meta', compact('products', 'selectedCompany', 'search'))->render();

            return response()->json([
                'success' => true,
                'html' => $gridHtml,
                'meta_html' => $metaHtml,
                'title' => ($selectedCompany ? $selectedCompany->name . ' Products' : 'Medical Products & Equipment') . ' | ' . Setting::get('site_title', 'INNOTECH MEDICAL PVT LTD'),
                'breadcrumb_title' => $selectedCompany ? $selectedCompany->name . ' Products' : 'Medical Products & Equipment',
                'selected_company_slug' => $selectedCompany ? $selectedCompany->slug : '',
                'total' => $products->total(),
            ]);
        }

        return view('products.index', compact(
            'products',
            'companies',
            'selectedCompany',
            'search',
            'totalProductsCount'
        ));
    }

    /**
     * Display the product detail page matching the user's design mockup
     */
    public function show($slug)
    {
        $product = Product::with('company')
            ->where('slug', $slug)
            ->firstOrFail();

        // 1. Fetch related products strictly from the same company / brand
        $relatedProducts = collect();
        if ($product->company_id) {
            $relatedProducts = Product::with('company')
                ->where('is_active', true)
                ->where('company_id', $product->company_id)
                ->where('id', '!=', $product->id)
                ->orderBy('order', 'asc')
                ->take(4)
                ->get();
        }

        // 2. If no other products from same company, search strictly for same equipment type by specific title keywords
        if ($relatedProducts->isEmpty()) {
            $cleanTitle = preg_replace('/[^a-zA-Z0-9\s]/', ' ', strtolower($product->title));
            $words = array_filter(explode(' ', $cleanTitle));
            $stopWords = [
                'with', 'unit', 'high', 'plus', 'model', 'from', 'best', 'dual', 'series', 
                'system', 'digital', 'device', 'standard', 'medical', 'hospital', 'auto', 
                'door', 'glass', 'tft', 'inch', '400w', 'easy', 'care'
            ];
            $keywords = array_values(array_filter($words, function ($w) use ($stopWords) {
                return strlen($w) >= 4 && !in_array($w, $stopWords);
            }));

            if (!empty($keywords)) {
                $relatedProducts = Product::with('company')
                    ->where('is_active', true)
                    ->where('id', '!=', $product->id)
                    ->where(function ($q) use ($keywords) {
                        foreach ($keywords as $kw) {
                            $q->orWhere('title', 'like', "%{$kw}%");
                        }
                    })
                    ->orderBy('order', 'asc')
                    ->take(4)
                    ->get();
            }
        }

        return view('products.show', compact('product', 'relatedProducts'));
    }

    /**
     * Handle "Request a Demo" inquiry form submission
     */
    public function demoRequest(Request $request, $id)
    {
        $product = Product::with('company')->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:150',
            'phone' => 'required|string|max:50',
            'hospital' => 'nullable|string|max:150',
            'message' => 'nullable|string|max:1500',
        ]);

        $manufacturerText = $product->company ? $product->company->name : 'N/A';
        $fullMessage = "Demo / Quote Request for: {$product->title}\n"
                     . "Manufacturer: {$manufacturerText}\n"
                     . "Hospital / Clinic: " . ($validated['hospital'] ?? 'Not specified') . "\n\n"
                     . "User Notes:\n" . ($validated['message'] ?? 'Client requested an in-person or live technical demonstration.');

        $inq = Inquiry::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'service_interested' => 'Demo: ' . Str::limit($product->title, 40),
            'subject' => 'Request a Demo: ' . $product->title,
            'message' => $fullMessage,
            'status' => 'unread',
        ]);

        // Safely dispatch acknowledgement & admin alert
        try {
            \App\Helpers\MailHelper::sendInquiryAcknowledgement($inq);
            \App\Helpers\MailHelper::sendAdminInquiryAlert($inq);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Demo request auto email failed: ' . $e->getMessage());
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Thank you! Your demo request for "' . $product->title . '" has been received. Our biomedical team will reach out shortly.',
            ]);
        }

        return redirect()->back()->with('demo_success', 'Thank you! Your demo request for "' . $product->title . '" has been received. Our clinical specialist will contact you.');
    }
}
