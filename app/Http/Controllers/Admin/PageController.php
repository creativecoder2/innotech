<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PageController extends Controller
{
    /**
     * Display a listing of custom pages with template presets grid.
     */
    public function index()
    {
        $pages = Page::orderBy('order', 'asc')->paginate(10);

        // Pre-configured templates to display in the creation grid
        $templates = [
            'terms' => [
                'name' => 'Terms & Conditions',
                'icon' => 'fa-solid fa-file-contract',
                'color' => '#0E63FF',
                'bg' => 'rgba(14, 99, 255, 0.08)',
                'desc' => 'Institutional equipment supply contracts, liability, and compliance terms.',
                'title' => 'Terms and Conditions',
                'slug' => 'terms-and-conditions',
                'subtitle' => 'Institutional Supply & Service Agreement',
                'footer_placement' => 'bottom_bar',
                'content' => "<h3>1. Scope of Agreement</h3>\n<p>These terms govern the procurement, supply, technical installation, and preventative maintenance of biomedical equipment and clinical devices provided by INNOTECH MEDICAL PVT LTD across Pakistan.</p>\n<h3>2. Warranties & Preventative Maintenance</h3>\n<p>All supplied equipment is covered under manufacturer warranties. Maintenance is performed strictly by certified biomedical engineers in compliance with international calibration benchmarks.</p>\n<h3>3. Operational Handover & Training</h3>\n<p>Innotech engineers provide full commissioning, hospital safety checks, and hands-on staff training upon equipment handover.</p>"
            ],
            'privacy' => [
                'name' => 'Privacy Policy',
                'icon' => 'fa-solid fa-shield-halved',
                'color' => '#0b9748',
                'bg' => 'rgba(11, 151, 72, 0.08)',
                'desc' => 'Clinical confidentiality, customer data protection, and inquiry security.',
                'title' => 'Privacy Policy',
                'slug' => 'privacy-policy',
                'subtitle' => 'Data Protection & Clinical Privacy Statement',
                'footer_placement' => 'bottom_bar',
                'content' => "<h3>1. Commitment to Privacy</h3>\n<p>INNOTECH MEDICAL PVT LTD is committed to protecting the privacy of healthcare professionals, institutional procurement officers, and diagnostic centers accessing our platforms and services.</p>\n<h3>2. Use of Information</h3>\n<p>Information collected via quote requests or inquiries is used solely for technical support, procurement verification, and service scheduling.</p>\n<h3>3. Data Integrity & Security</h3>\n<p>We employ enterprise-grade security protocols to protect sensitive hospital records and institutional technical data.</p>"
            ],
            'refund' => [
                'name' => 'Refund & Warranty Policy',
                'icon' => 'fa-solid fa-rotate-left',
                'color' => '#F72A75',
                'bg' => 'rgba(247, 42, 117, 0.08)',
                'desc' => 'Equipment warranty coverage, replacement protocol, and claims procedure.',
                'title' => 'Refund & Warranty Policy',
                'slug' => 'refund-policy',
                'subtitle' => 'Biomedical Equipment Warranty & Claims Policy',
                'footer_placement' => 'useful_links',
                'content' => "<h3>1. Warranty Terms</h3>\n<p>Every diagnostic device, critical care unit, and laboratory instrument supplied by Innotech Medical includes a formal OEM warranty.</p>\n<h3>2. Replacement Protocol</h3>\n<p>Should any unit exhibit manufacturer defects, our emergency biomedical inspection unit provides rapid replacement or immediate component repair.</p>"
            ],
            'shipping' => [
                'name' => 'Shipping & Delivery Policy',
                'icon' => 'fa-solid fa-truck-medical',
                'color' => '#179BF0',
                'bg' => 'rgba(23, 155, 240, 0.08)',
                'desc' => 'Nationwide hospital logistics, cold-chain transport, and white-glove setup.',
                'title' => 'Shipping & Delivery',
                'slug' => 'shipping-policy',
                'subtitle' => 'Nationwide Hospital Logistics & Handling',
                'footer_placement' => 'useful_links',
                'content' => "<h3>1. Secure Nationwide Transportation</h3>\n<p>We ensure shock-absorbent, climate-controlled transport of high-precision diagnostic and surgical hardware across all provinces in Pakistan.</p>\n<h3>2. On-Site Inspection & Installation</h3>\n<p>Deliveries to hospitals and diagnostic laboratories include uncrating, room-of-choice placement, and physical inspection by certified technicians.</p>"
            ],
            'custom' => [
                'name' => 'Custom Blank Page',
                'icon' => 'fa-solid fa-pen-nib',
                'color' => '#6B7280',
                'bg' => 'rgba(107, 114, 128, 0.08)',
                'desc' => 'Create a custom announcement, compliance document, or company page from scratch.',
                'title' => '',
                'slug' => '',
                'subtitle' => '',
                'footer_placement' => 'useful_links',
                'content' => "<h3>Page Section Heading</h3>\n<p>Enter your custom page content here. You can use standard HTML tags such as headings, paragraphs, and lists.</p>"
            ]
        ];

        return view('admin.pages.index', compact('pages', 'templates'));
    }

    /**
     * Store a newly created custom page.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:pages,slug',
            'subtitle' => 'nullable|string|max:255',
            'template_type' => 'nullable|string',
            'content' => 'nullable|string',
            'meta_description' => 'nullable|string',
            'show_in_footer' => 'nullable|boolean',
            'footer_placement' => 'required|in:bottom_bar,useful_links,both',
            'order' => 'nullable|integer',
            'is_published' => 'nullable|boolean',
        ]);

        $validated['slug'] = !empty($validated['slug']) ? Str::slug($validated['slug']) : Str::slug($validated['title']);
        $validated['show_in_footer'] = $request->has('show_in_footer') ? 1 : 0;
        $validated['is_published'] = $request->has('is_published') ? 1 : 0;
        $validated['order'] = $validated['order'] ?? (Page::max('order') + 1);

        Page::create($validated);

        return redirect()->route('admin.pages.index')->with('success', 'Customer page created successfully!');
    }

    /**
     * Update the specified custom page.
     */
    public function update(Request $request, Page $page)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:pages,slug,' . $page->id,
            'subtitle' => 'nullable|string|max:255',
            'template_type' => 'nullable|string',
            'content' => 'nullable|string',
            'meta_description' => 'nullable|string',
            'show_in_footer' => 'nullable|boolean',
            'footer_placement' => 'required|in:bottom_bar,useful_links,both',
            'order' => 'nullable|integer',
            'is_published' => 'nullable|boolean',
        ]);

        $validated['slug'] = Str::slug($validated['slug']);
        $validated['show_in_footer'] = $request->has('show_in_footer') ? 1 : 0;
        $validated['is_published'] = $request->has('is_published') ? 1 : 0;

        $page->update($validated);

        return redirect()->route('admin.pages.index')->with('success', 'Page "' . $page->title . '" updated successfully!');
    }

    /**
     * Remove the specified custom page.
     */
    public function destroy(Page $page)
    {
        $title = $page->title;
        $page->delete();

        return redirect()->route('admin.pages.index')->with('success', 'Page "' . $title . '" deleted successfully!');
    }

    /**
     * Quick AJAX toggle for publication or footer visibility.
     */
    public function toggle(Request $request, Page $page)
    {
        $field = $request->get('field');
        if (in_array($field, ['is_published', 'show_in_footer'])) {
            $page->$field = !$page->$field;
            $page->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Status updated successfully!',
                'new_value' => $page->$field
            ]);
        }

        return response()->json(['status' => 'error', 'message' => 'Invalid field'], 400);
    }
}
