<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function show($slug)
    {
        $page = Page::where('slug', $slug)->firstOrFail();

        // If unpublished and user is not admin, abort 404
        if (!$page->is_published && !auth()->check()) {
            abort(404);
        }

        $allFooterPages = Page::published()->where('show_in_footer', true)->get();

        return view('page_detail', compact('page', 'allFooterPages'));
    }
}
