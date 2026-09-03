<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Inquiry;
use App\Models\Blog;
use App\Models\Slider;
use App\Models\Testimonial;
use App\Models\Partner;
use App\Models\ChatConversation;
use App\Models\BlogComment;
use App\Models\VisitorLog;
use App\Models\PageAnalytic;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $todayDate = now()->toDateString();
        $yesterdayDate = now()->subDay()->toDateString();

        // 1. Daily Unique Visitors (Counted once per IP per day)
        $todayUniqueVisitors = VisitorLog::where('visit_date', $todayDate)
            ->distinct('ip_address')
            ->count('ip_address');

        $yesterdayUniqueVisitors = VisitorLog::where('visit_date', $yesterdayDate)
            ->distinct('ip_address')
            ->count('ip_address');

        $visitorDiff = $todayUniqueVisitors - $yesterdayUniqueVisitors;
        $visitorGrowthPercent = $yesterdayUniqueVisitors > 0
            ? round(($visitorDiff / $yesterdayUniqueVisitors) * 100, 1)
            : ($todayUniqueVisitors > 0 ? 100 : 0);

        // 2. Pageviews & Dwell Time
        $todayPageViews = VisitorLog::where('visit_date', $todayDate)->count();
        $totalPageViews = VisitorLog::count();
        $avgDurationSeconds = (int) round(VisitorLog::where('duration_seconds', '>', 0)->avg('duration_seconds') ?: 0);
        $avgDwellFormatted = $avgDurationSeconds > 0 ? $this->formatDuration($avgDurationSeconds) : '0s';

        // 3. Lead Conversions KPI
        // Newsletters
        $totalNewsletters = Inquiry::where(function ($q) {
            $q->where('name', 'Newsletter Subscriber')
              ->orWhere('message', 'LIKE', '%newsletter%')
              ->orWhere('service_interested', 'Newsletter');
        })->count();

        // Contact Inquiries & Quotes
        $totalInquiries = Inquiry::where(function ($q) {
            $q->where('name', '!=', 'Newsletter Subscriber')
              ->where('message', 'NOT LIKE', '%newsletter%')
              ->where(function($sub) {
                  $sub->whereNull('service_interested')->orWhere('service_interested', '!=', 'Newsletter');
              });
        })->count();

        $unreadInquiries = Inquiry::where('status', 'unread')
            ->where('name', '!=', 'Newsletter Subscriber')
            ->count();

        // Live Chats
        $totalLiveChats = class_exists(ChatConversation::class) ? ChatConversation::count() : 0;
        $activeLiveChats = class_exists(ChatConversation::class) ? ChatConversation::where('status', 'active')->count() : 0;

        // Blog Comments
        $totalComments = class_exists(BlogComment::class) ? BlogComment::count() : 0;
        $pendingComments = class_exists(BlogComment::class) ? BlogComment::where('status', 'pending')->count() : 0;

        // General Site Content Stats
        $stats = [
            'total_services' => Service::count(),
            'total_inquiries' => $totalInquiries,
            'unread_inquiries' => $unreadInquiries,
            'total_newsletters' => $totalNewsletters,
            'total_live_chats' => $totalLiveChats,
            'active_live_chats' => $activeLiveChats,
            'total_comments' => $totalComments,
            'pending_comments' => $pendingComments,
            'total_blogs' => Blog::count(),
            'total_sliders' => Slider::count(),
            'today_unique_visitors' => $todayUniqueVisitors,
            'yesterday_unique_visitors' => $yesterdayUniqueVisitors,
            'visitor_growth_percent' => $visitorGrowthPercent,
            'today_page_views' => $todayPageViews,
            'total_page_views' => $totalPageViews,
            'avg_dwell_time' => $avgDwellFormatted,
        ];

        // 4. Past 14 Days Traffic Trend for Area Spline Chart
        $chartLabels = [];
        $chartUniqueVisitors = [];
        $chartPageViews = [];

        for ($i = 13; $i >= 0; $i--) {
            $d = now()->subDays($i);
            $dString = $d->toDateString();
            $chartLabels[] = $i === 0 ? 'Today' : $d->format('M d');

            $dailyUnique = VisitorLog::where('visit_date', $dString)
                ->distinct('ip_address')
                ->count('ip_address');

            $dailyViews = VisitorLog::where('visit_date', $dString)->count();

            $chartUniqueVisitors[] = $dailyUnique;
            $chartPageViews[] = $dailyViews;
        }

        $trafficChart = [
            'labels' => $chartLabels,
            'unique_visitors' => $chartUniqueVisitors,
            'page_views' => $chartPageViews,
        ];

        // 5. Top Visited Pages Leaderboard (Clicks, Unique Visitors, Dwell Time)
        $topPages = PageAnalytic::orderBy('total_views', 'desc')->take(7)->get();
        $maxViews = $topPages->first() ? max(1, $topPages->first()->total_views) : 1;

        // 6. Device Breakdown for Doughnut Chart
        $desktopCount = VisitorLog::where('device_type', 'Desktop')->count();
        $mobileCount = VisitorLog::where('device_type', 'Mobile')->count();
        $tabletCount = VisitorLog::where('device_type', 'Tablet')->count();
        $rawTotal = $desktopCount + $mobileCount + $tabletCount;
        $deviceTotal = max(1, $rawTotal);

        $deviceStats = [
            'desktop' => $desktopCount,
            'mobile' => $mobileCount,
            'tablet' => $tabletCount,
            'desktop_pct' => $rawTotal > 0 ? round(($desktopCount / $deviceTotal) * 100) : 0,
            'mobile_pct' => $rawTotal > 0 ? round(($mobileCount / $deviceTotal) * 100) : 0,
            'tablet_pct' => $rawTotal > 0 ? round(($tabletCount / $deviceTotal) * 100) : 0,
            'total' => $rawTotal,
        ];

        // 7. Recent Inquiries and Live Chats
        $recentInquiries = Inquiry::orderBy('created_at', 'desc')->take(6)->get();
        $recentChats = class_exists(ChatConversation::class)
            ? ChatConversation::with('latestMessage')->orderBy('updated_at', 'desc')->take(5)->get()
            : collect();

        return view('admin.dashboard', compact(
            'stats',
            'trafficChart',
            'topPages',
            'maxViews',
            'deviceStats',
            'recentInquiries',
            'recentChats'
        ));
    }

    private function formatDuration(int $seconds): string
    {
        if ($seconds < 60) {
            return "{$seconds}s";
        }
        $m = floor($seconds / 60);
        $s = $seconds % 60;
        return "{$m}m " . ($s > 0 ? "{$s}s" : '');
    }
}
