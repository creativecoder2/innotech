<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NavMenu;
use App\Models\Service;
use App\Models\Blog;

class NavMenuController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'page_route' => 'required|string',
        ]);

        $url = $this->resolveUrl($request->page_route, $request->custom_url, $request->service_slug, $request->blog_slug);

        NavMenu::create([
            'title' => $request->title,
            'url' => $url,
            'page_route' => $request->page_route,
            'parent_id' => $request->parent_id ?: null,
            'order' => (int)$request->order,
            'show_on_home' => $request->has('show_on_home'),
            'show_on_inner' => $request->has('show_on_inner'),
            'is_active' => $request->has('is_active'),
            'target_blank' => $request->has('target_blank'),
        ]);

        return redirect()->back()->with('success', 'Navigation menu item created successfully!');
    }

    public function update(Request $request, $id)
    {
        $menu = NavMenu::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'page_route' => 'required|string',
        ]);

        $url = $this->resolveUrl($request->page_route, $request->custom_url, $request->service_slug, $request->blog_slug);

        $menu->title = $request->title;
        $menu->url = $url;
        $menu->page_route = $request->page_route;
        $menu->parent_id = ($request->parent_id && $request->parent_id != $menu->id) ? $request->parent_id : null;
        $menu->order = (int)$request->order;
        $menu->show_on_home = $request->has('show_on_home');
        $menu->show_on_inner = $request->has('show_on_inner');
        $menu->is_active = $request->has('is_active');
        $menu->target_blank = $request->has('target_blank');
        $menu->save();

        return redirect()->back()->with('success', 'Navigation menu item updated successfully!');
    }

    public function destroy($id)
    {
        $menu = NavMenu::findOrFail($id);
        $menu->delete();

        return redirect()->back()->with('success', 'Navigation menu item deleted successfully!');
    }

    public function toggle(Request $request, $id)
    {
        $menu = NavMenu::findOrFail($id);
        $field = $request->field; // 'is_active', 'show_on_home', 'show_on_inner'

        if (in_array($field, ['is_active', 'show_on_home', 'show_on_inner'])) {
            $menu->$field = !$menu->$field;
            $menu->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Menu visibility updated.',
                'new_val' => $menu->$field
            ]);
        }

        return response()->json(['status' => 'error', 'message' => 'Invalid field.'], 400);
    }

    private function resolveUrl($pageRoute, $customUrl, $serviceSlug = null, $blogSlug = null)
    {
        switch ($pageRoute) {
            case 'home':
                return '/';
            case 'about':
                return '/about';
            case 'gallery':
                return '/gallery';
            case 'contact':
                return '/contact';
            case 'partners':
                return '/#brand-section';
            case 'services_all':
                return '/#services-section';
            case 'service_single':
                return $serviceSlug ? '/services/' . $serviceSlug : '/#services-section';
            case 'blogs_all':
                return '/#blog-section';
            case 'blog_single':
                return $blogSlug ? '/blog/' . $blogSlug : '/#blog-section';
            case 'dropdown_parent':
                return '#';
            case 'custom':
            default:
                return $customUrl ?: '#';
        }
    }
}
