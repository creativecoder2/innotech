<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\HomeSectionsController as AdminHomeSectionsController;
use App\Http\Controllers\Admin\NavMenuController as AdminNavMenuController;
use App\Http\Controllers\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\Admin\SliderController as AdminSliderController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\Admin\InquiryController as AdminInquiryController;
use App\Http\Controllers\Admin\BlogController as AdminBlogController;
use App\Http\Controllers\Admin\BlogCommentController as AdminBlogCommentController;
use App\Http\Controllers\Admin\TestimonialController as AdminTestimonialController;
use App\Http\Controllers\Admin\PartnerController as AdminPartnerController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\Admin\PageController as AdminPageController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\Admin\LiveChatController as AdminLiveChatController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SeoController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\Admin\NotificationController as AdminNotificationController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Admin\CompanyController as AdminCompanyController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;

/*
|--------------------------------------------------------------------------
| Frontend Routes
|--------------------------------------------------------------------------
*/
// Dynamic SEO Webmaster Routes
Route::get('/sitemap.xml', [SeoController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [SeoController::class, 'robots'])->name('robots');

// Realtime Visitor & Dwell Time Analytics
Route::post('/api/analytics/ping', [AnalyticsController::class, 'ping'])->name('analytics.ping');
Route::post('/api/analytics/leave', [AnalyticsController::class, 'leave'])->name('analytics.leave');

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/gallery', [HomeController::class, 'gallery'])->name('gallery');
Route::get('/specialists', [HomeController::class, 'team'])->name('specialists');
Route::get('/specialists/{slug}', [HomeController::class, 'teamDetail'])->name('specialist.detail');
Route::get('/team', [HomeController::class, 'team'])->name('team');
Route::get('/team/{slug}', [HomeController::class, 'teamDetail'])->name('team.detail');
Route::get('/partners', [HomeController::class, 'team'])->name('partners');
Route::get('/partners/{slug}', [HomeController::class, 'teamDetail']);
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::post('/contact/store', [ContactController::class, 'store'])->name('contact.store');

// Medical Products & Equipment Catalog
Route::get('/products', [ProductController::class, 'index'])->name('products');
Route::get('/products/company/{companySlug}', [ProductController::class, 'index'])->name('products.company');
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('product.detail');
Route::post('/products/{id}/demo-request', [ProductController::class, 'demoRequest'])->name('product.demo_request');

// Services & Healthcare Solutions Routes
Route::get('/services', [HomeController::class, 'services'])->name('services');
Route::get('/services/{slug}', [HomeController::class, 'serviceDetail'])->name('service.detail');
Route::get('/blog', [HomeController::class, 'blog'])->name('blog');
Route::get('/blogs', function () { return redirect()->route('blog'); });
Route::get('/blog/{slug}', [HomeController::class, 'blogDetail'])->name('blog.detail');
Route::post('/blog/{slug}/comment', [HomeController::class, 'blogComment'])->name('blog.comment');
Route::get('/page/{slug}', [PageController::class, 'show'])->name('page.show');
Route::get('/terms', function () { return redirect()->route('page.show', 'terms-and-conditions'); });
Route::get('/privacy', function () { return redirect()->route('page.show', 'privacy-policy'); });

// Global Search Endpoints
Route::get('/search', [SearchController::class, 'index'])->name('search');
Route::get('/search/suggest', [SearchController::class, 'suggest'])->name('search.suggest');

// Live Chat Customer Endpoints
Route::post('/chat/start', [ChatController::class, 'start'])->name('chat.start');
Route::post('/chat/send', [ChatController::class, 'send'])->name('chat.send');
Route::get('/chat/poll', [ChatController::class, 'poll'])->name('chat.poll');
Route::get('/chat/restore', [ChatController::class, 'restore'])->name('chat.restore');

/*
|--------------------------------------------------------------------------
| Admin Authentication Routes
|--------------------------------------------------------------------------
*/
Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])->name('login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
Route::match(['get', 'post'], '/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

/*
|--------------------------------------------------------------------------
| Admin Protected Panel Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [AdminDashboardController::class, 'index']);

    // Realtime System & Desktop Notifications Check
    Route::get('notifications/check', [AdminNotificationController::class, 'check'])->name('notifications.check');

    // Profile
    Route::get('/profile', [AdminAuthController::class, 'profile'])->name('profile');
    Route::post('/profile', [AdminAuthController::class, 'updateProfile'])->name('profile.update');

    // UNIFIED HOME PAGE SECTIONS MANAGER
    Route::get('home-sections', [AdminHomeSectionsController::class, 'index'])->name('home_sections.index');
    Route::post('home-sections/save', [AdminHomeSectionsController::class, 'saveSection'])->name('home_sections.save');
    Route::post('home-sections/toggle', [AdminHomeSectionsController::class, 'toggleSection'])->name('home_sections.toggle');

    // Navigation Menu Management
    Route::post('nav-menus', [AdminNavMenuController::class, 'store'])->name('nav_menus.store');
    Route::put('nav-menus/{id}', [AdminNavMenuController::class, 'update'])->name('nav_menus.update');
    Route::delete('nav-menus/{id}', [AdminNavMenuController::class, 'destroy'])->name('nav_menus.destroy');
    Route::post('nav-menus/{id}/toggle', [AdminNavMenuController::class, 'toggle'])->name('nav_menus.toggle');

    // Gallery CRUD (via Home Manager)
    Route::post('home-sections/gallery', [AdminHomeSectionsController::class, 'storeGallery'])->name('home_sections.gallery.store');
    Route::put('home-sections/gallery/{id}', [AdminHomeSectionsController::class, 'updateGallery'])->name('home_sections.gallery.update');
    Route::delete('home-sections/gallery/{id}', [AdminHomeSectionsController::class, 'deleteGallery'])->name('home_sections.gallery.destroy');

    // Team CRUD (via Home Manager)
    Route::post('home-sections/team', [AdminHomeSectionsController::class, 'storeTeam'])->name('home_sections.team.store');
    Route::put('home-sections/team/{id}', [AdminHomeSectionsController::class, 'updateTeam'])->name('home_sections.team.update');
    Route::delete('home-sections/team/{id}', [AdminHomeSectionsController::class, 'deleteTeam'])->name('home_sections.team.destroy');

    // Testimonials CRUD (via Home Manager)
    Route::post('home-sections/testimonials', [AdminHomeSectionsController::class, 'storeTestimonial'])->name('home_sections.testimonials.store');
    Route::put('home-sections/testimonials/{id}', [AdminHomeSectionsController::class, 'updateTestimonial'])->name('home_sections.testimonials.update');
    Route::delete('home-sections/testimonials/{id}', [AdminHomeSectionsController::class, 'deleteTestimonial'])->name('home_sections.testimonials.destroy');

    // Partners CRUD (via Home Manager)
    Route::post('home-sections/partners', [AdminHomeSectionsController::class, 'storePartner'])->name('home_sections.partners.store');
    Route::put('home-sections/partners/{id}', [AdminHomeSectionsController::class, 'updatePartner'])->name('home_sections.partners.update');
    Route::delete('home-sections/partners/{id}', [AdminHomeSectionsController::class, 'deletePartner'])->name('home_sections.partners.destroy');

    // Universal Table Item AJAX Toggle
    Route::post('home-sections/toggle-item', [AdminHomeSectionsController::class, 'toggleItem'])->name('home_sections.toggle_item');

    // Companies & Manufacturers CRUD
    Route::post('companies/{company}/toggle', [AdminCompanyController::class, 'toggle'])->name('companies.toggle');
    Route::resource('companies', AdminCompanyController::class);

    // Products Catalog CRUD
    Route::post('products/{product}/toggle', [AdminProductController::class, 'toggle'])->name('products.toggle');
    Route::resource('products', AdminProductController::class);

    // Legacy Services / Medical Products CRUD
    Route::resource('services', AdminServiceController::class);

    // Hero Sliders / Banners CRUD
    Route::resource('sliders', AdminSliderController::class);

    // Blog / Research Articles CRUD
    Route::post('blogs/{blog}/toggle', [AdminBlogController::class, 'toggle'])->name('blogs.toggle');
    Route::resource('blogs', AdminBlogController::class);

    // Blog Comments Moderation
    Route::get('blog-comments', [AdminBlogCommentController::class, 'index'])->name('blog_comments.index');
    Route::post('blog-comments/{comment}/approve', [AdminBlogCommentController::class, 'approve'])->name('blog_comments.approve');
    Route::post('blog-comments/{comment}/reject', [AdminBlogCommentController::class, 'reject'])->name('blog_comments.reject');
    Route::delete('blog-comments/{comment}', [AdminBlogCommentController::class, 'destroy'])->name('blog_comments.destroy');

    // Inquiries / Leads Management
    Route::get('inquiries/unread-count', [AdminInquiryController::class, 'unreadCount'])->name('inquiries.unread_count');
    Route::get('inquiries', [AdminInquiryController::class, 'index'])->name('inquiries.index');
    Route::get('inquiries/{inquiry}', [AdminInquiryController::class, 'show'])->name('inquiries.show');
    Route::post('inquiries/{inquiry}/status', [AdminInquiryController::class, 'updateStatus'])->name('inquiries.status');
    Route::post('inquiries/{inquiry}/reply', [AdminInquiryController::class, 'reply'])->name('inquiries.reply');
    Route::delete('inquiries/{inquiry}', [AdminInquiryController::class, 'destroy'])->name('inquiries.destroy');

    // Settings Management
    Route::get('settings', [AdminSettingController::class, 'index'])->name('settings.index');
    Route::post('settings', [AdminSettingController::class, 'update'])->name('settings.update');
    Route::post('settings/test-email', [AdminSettingController::class, 'testEmail'])->name('settings.test_email');

    // Testimonials
    Route::get('testimonials', [AdminTestimonialController::class, 'index'])->name('testimonials.index');
    Route::post('testimonials', [AdminTestimonialController::class, 'store'])->name('testimonials.store');
    Route::put('testimonials/{testimonial}', [AdminTestimonialController::class, 'update'])->name('testimonials.update');
    Route::delete('testimonials/{testimonial}', [AdminTestimonialController::class, 'destroy'])->name('testimonials.destroy');

    // Partner Brands
    Route::get('partners', [AdminPartnerController::class, 'index'])->name('partners.index');
    Route::post('partners', [AdminPartnerController::class, 'store'])->name('partners.store');
    Route::delete('partners/{partner}', [AdminPartnerController::class, 'destroy'])->name('partners.destroy');

    // Custom Customer Pages (Legal, Policies, Custom Pages)
    Route::resource('pages', AdminPageController::class);
    Route::post('pages/{page}/toggle', [AdminPageController::class, 'toggle'])->name('pages.toggle');

    // Live Support Chat Console & Settings
    Route::get('live-chat', [AdminLiveChatController::class, 'index'])->name('live_chat.index');
    Route::get('live-chat/feed', [AdminLiveChatController::class, 'conversationsFeed'])->name('live_chat.feed');
    Route::get('live-chat/{id}/messages', [AdminLiveChatController::class, 'messages'])->name('live_chat.messages');
    Route::post('live-chat/{id}/reply', [AdminLiveChatController::class, 'reply'])->name('live_chat.reply');
    Route::post('live-chat/{id}/toggle-status', [AdminLiveChatController::class, 'toggleStatus'])->name('live_chat.toggle_status');
    Route::delete('live-chat/{id}', [AdminLiveChatController::class, 'destroy'])->name('live_chat.destroy');
    Route::post('live-chat/settings', [AdminLiveChatController::class, 'saveSettings'])->name('live_chat.settings');
});

// Uploads static fallback handler (ensures uploaded images are served reliably in cPanel/Apache/XAMPP)
Route::get('/uploads/{path}', function ($path) {
    $path = trim(str_replace('..', '', $path), '/\\');
    $file = public_path('uploads/' . $path);
    if (!file_exists($file)) {
        $file = base_path('uploads/' . $path);
    }
    if (file_exists($file) && !is_dir($file)) {
        $mime = mime_content_type($file) ?: 'application/octet-stream';
        return response()->file($file, ['Content-Type' => $mime]);
    }
    abort(404);
})->where('path', '.*');

// Browser cache clear utility (executes optimize:clear without needing cPanel terminal)
Route::get('/clear-cache', function () {
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
    $output = \Illuminate\Support\Facades\Artisan::output();
    return '<div style="font-family: Arial, sans-serif; max-width: 600px; margin: 50px auto; padding: 30px; border: 1.5px solid #86EFAC; background: #F0FDF4; border-radius: 12px; text-align: center; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">'
        . '<div style="font-size: 40px; margin-bottom: 10px;">✅</div>'
        . '<h2 style="color: #166534; margin: 0 0 10px;">System Cache Cleared Successfully!</h2>'
        . '<p style="color: #15803D; font-size: 14px; margin: 0 0 20px;">Configuration, routes, views, and compiled caches have been refreshed.</p>'
        . '<pre style="background: #ffffff; border: 1px solid #CBD5E1; padding: 12px; border-radius: 6px; text-align: left; font-size: 12px; color: #334155; max-height: 180px; overflow-y: auto;">' . htmlspecialchars($output) . '</pre>'
        . '<div style="margin-top: 20px; display: flex; justify-content: center; gap: 10px;">'
        . '<a href="' . url('/') . '" style="padding: 10px 20px; background: #0E63FF; color: #ffffff; text-decoration: none; border-radius: 6px; font-weight: 600; font-size: 13px;">Go to Website</a>'
        . '<a href="' . url('/admin') . '" style="padding: 10px 20px; background: #334155; color: #ffffff; text-decoration: none; border-radius: 6px; font-weight: 600; font-size: 13px;">Go to Admin Panel</a>'
        . '</div>'
        . '</div>';
});


