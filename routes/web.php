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
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\LoginLogController as AdminLoginLogController;

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
Route::get('/gallery/{id}', [HomeController::class, 'galleryDetail'])->name('gallery.detail');
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
Route::middleware(['auth', \App\Http\Middleware\TrackAdminActivity::class])->prefix('admin')->name('admin.')->group(function () {
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
    Route::post('home-sections/banner-slider-image/delete', [AdminHomeSectionsController::class, 'deleteBannerSliderImage'])->name('home_sections.delete_banner_slider_image');

    // Navigation Menu Management
    Route::post('nav-menus', [AdminNavMenuController::class, 'store'])->name('nav_menus.store');
    Route::put('nav-menus/{id}', [AdminNavMenuController::class, 'update'])->name('nav_menus.update');
    Route::delete('nav-menus/{id}', [AdminNavMenuController::class, 'destroy'])->name('nav_menus.destroy');
    Route::post('nav-menus/{id}/toggle', [AdminNavMenuController::class, 'toggle'])->name('nav_menus.toggle');

    // Gallery CRUD (via Home Manager)
    Route::post('home-sections/gallery', [AdminHomeSectionsController::class, 'storeGallery'])->name('home_sections.gallery.store');
    Route::put('home-sections/gallery/{id}', [AdminHomeSectionsController::class, 'updateGallery'])->name('home_sections.gallery.update');
    Route::delete('home-sections/gallery/{id}', [AdminHomeSectionsController::class, 'deleteGallery'])->name('home_sections.gallery.destroy');
    Route::post('home-sections/gallery/{id}/delete-image', [AdminHomeSectionsController::class, 'deleteGalleryInnerImage'])->name('home_sections.gallery.delete_image');

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
    Route::post('inquiries/bulk-delete', [AdminInquiryController::class, 'bulkDelete'])->name('inquiries.bulk_delete');
    Route::post('inquiries/delete-all', [AdminInquiryController::class, 'deleteAll'])->name('inquiries.delete_all');
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

    // Administrators & Roles Management CRUD
    Route::resource('users', AdminUserController::class)->except(['create', 'show', 'edit']);
    Route::post('users/{user}/toggle-status', [AdminUserController::class, 'toggleStatus'])->name('users.toggle_status');

    // Login Logs & Active Sessions Tracking
    Route::get('logs', [AdminLoginLogController::class, 'index'])->name('logs.index');
    Route::post('logs/{id}/revoke', [AdminLoginLogController::class, 'revokeSession'])->name('logs.revoke_session');
    Route::post('logs/clear-old', [AdminLoginLogController::class, 'clearOldLogs'])->name('logs.clear_old');
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

// Browser optimization & cache utility (executes all 4 artisan commands on browser hit)
Route::get('/optimize', function () {
    @set_time_limit(300);
    @ini_set('max_execution_time', '300');
    @ini_set('memory_limit', '512M');
    if (function_exists('ignore_user_abort')) {
        @ignore_user_abort(true);
    }

    $commands = [
        'optimize:clear' => 'Clearing compiled services, cache, views, and routes...',
        'config:cache'   => 'Caching configuration files for high-speed boot...',
        'route:cache'    => 'Compiling and caching route registrations...',
        'view:cache'     => 'Pre-compiling Blade templates into bytecode...',
    ];

    $results = [];
    $allSuccessful = true;

    foreach ($commands as $cmd => $desc) {
        try {
            $exitCode = \Illuminate\Support\Facades\Artisan::call($cmd);
            $rawOutput = trim(\Illuminate\Support\Facades\Artisan::output());
            $results[$cmd] = [
                'status'  => $exitCode === 0 ? 'success' : 'warning',
                'desc'    => $desc,
                'output'  => $rawOutput ?: 'Completed successfully.',
            ];
            if ($exitCode !== 0) {
                $allSuccessful = false;
            }
        } catch (\Throwable $e) {
            $allSuccessful = false;
            $results[$cmd] = [
                'status'  => 'error',
                'desc'    => $desc,
                'output'  => 'Exception: ' . $e->getMessage(),
            ];
        }
    }

    $overallColor = $allSuccessful ? '#10B981' : '#F59E0B';
    $badgeBg = $allSuccessful ? '#ECFDF5' : '#FFFBEB';
    $badgeText = $allSuccessful ? '#065F46' : '#92400E';
    $title = $allSuccessful ? 'All Optimization Commands Executed Successfully!' : 'Optimization Completed with Warnings';

    $html = '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Innotech - Artisan Optimization Engine</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; }
        body { background: #0F172A; color: #E2E8F0; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 25px; }
        .card { width: 100%; max-width: 760px; background: #1E293B; border: 1px solid #334155; border-radius: 16px; box-shadow: 0 20px 40px rgba(0,0,0,0.4); overflow: hidden; }
        .card-header { padding: 30px; border-bottom: 1px solid #334155; text-align: center; background: radial-gradient(circle at top, #1e3a5f 0%, #1e293b 80%); }
        .card-header .icon { font-size: 46px; margin-bottom: 10px; }
        .card-header h1 { font-size: 22px; font-weight: 700; color: #FFFFFF; margin-bottom: 8px; }
        .card-header p { font-size: 14px; color: #94A3B8; }
        .card-body { padding: 25px 30px; }
        .command-item { background: #0F172A; border: 1px solid #334155; border-radius: 10px; padding: 16px; margin-bottom: 14px; transition: all 0.2s ease; }
        .command-item:hover { border-color: #475569; }
        .command-title-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px; }
        .command-name { font-family: "Courier New", Courier, monospace; font-size: 14px; font-weight: 700; color: #38BDF8; }
        .badge { padding: 3px 10px; border-radius: 9999px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }
        .badge-success { background: #064E3B; color: #34D399; border: 1px solid #059669; }
        .badge-warning { background: #78350F; color: #FBBF24; border: 1px solid #D97706; }
        .badge-error { background: #7F1D1D; color: #F87171; border: 1px solid #DC2626; }
        .command-desc { font-size: 12px; color: #94A3B8; margin-bottom: 10px; }
        .command-output { background: #020617; border: 1px solid #1E293B; border-radius: 6px; padding: 10px 14px; font-family: "Courier New", Courier, monospace; font-size: 12px; color: #CBD5E1; white-space: pre-wrap; word-break: break-all; max-height: 120px; overflow-y: auto; }
        .card-footer { padding: 20px 30px; background: #0F172A; border-top: 1px solid #334155; display: flex; flex-wrap: wrap; justify-content: center; gap: 12px; }
        .btn { padding: 10px 22px; border-radius: 8px; font-size: 13px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s ease; border: none; cursor: pointer; }
        .btn-primary { background: #0284C7; color: #FFFFFF; }
        .btn-primary:hover { background: #0369A1; }
        .btn-outline { background: #1E293B; color: #E2E8F0; border: 1px solid #475569; }
        .btn-outline:hover { background: #334155; }
    </style>
</head>
<body>
    <div class="card">
        <div class="card-header">
            <div class="icon">' . ($allSuccessful ? '⚡' : '⚠️') . '</div>
            <h1>' . $title . '</h1>
            <p>Executed commands: <code>optimize:clear</code> &bull; <code>config:cache</code> &bull; <code>route:cache</code> &bull; <code>view:cache</code></p>
        </div>
        <div class="card-body">';

    foreach ($results as $cmd => $info) {
        $badgeClass = 'badge-' . $info['status'];
        $badgeLabel = strtoupper($info['status']);
        $html .= '<div class="command-item">
            <div class="command-title-row">
                <span class="command-name">php artisan ' . htmlspecialchars($cmd) . '</span>
                <span class="badge ' . $badgeClass . '">' . $badgeLabel . '</span>
            </div>
            <div class="command-desc">' . htmlspecialchars($info['desc']) . '</div>
            <div class="command-output">' . htmlspecialchars($info['output']) . '</div>
        </div>';
    }

    $html .= '</div>
        <div class="card-footer">
            <a href="' . url('/optimize') . '" class="btn btn-primary">🔄 Re-run All Commands</a>
            <a href="' . url('/') . '" class="btn btn-outline" target="_blank">🌐 Open Website</a>
            <a href="' . url('/admin') . '" class="btn btn-outline" target="_blank">🛡️ Admin Panel</a>
        </div>
    </div>
</body>
</html>';

    return response($html, 200)->header('Content-Type', 'text/html');
});

// Alias route: /clear-cache redirects or runs /optimize
Route::get('/clear-cache', function () {
    return redirect('/optimize');
});


