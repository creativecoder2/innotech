<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VisitorLog;
use App\Models\PageAnalytic;
use Illuminate\Support\Str;

class AnalyticsController extends Controller
{
    /**
     * Parse device type from User-Agent
     */
    private function getDeviceType(string $userAgent): string
    {
        if (preg_match('/(tablet|ipad|playbook|silk)|(android(?!.*mobi))/i', $userAgent)) {
            return 'Tablet';
        }
        if (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android|iemobile)/i', $userAgent)) {
            return 'Mobile';
        }
        return 'Desktop';
    }

    /**
     * Parse browser name from User-Agent
     */
    private function getBrowser(string $userAgent): string
    {
        if (str_contains($userAgent, 'Edg')) return 'Edge';
        if (str_contains($userAgent, 'Chrome')) return 'Chrome';
        if (str_contains($userAgent, 'Safari')) return 'Safari';
        if (str_contains($userAgent, 'Firefox')) return 'Firefox';
        if (str_contains($userAgent, 'Opera') || str_contains($userAgent, 'OPR')) return 'Opera';
        return 'Other';
    }

    /**
     * Parse platform / OS from User-Agent
     */
    private function getPlatform(string $userAgent): string
    {
        if (str_contains($userAgent, 'Windows')) return 'Windows';
        if (str_contains($userAgent, 'Android')) return 'Android';
        if (str_contains($userAgent, 'iPhone') || str_contains($userAgent, 'iPad')) return 'iOS';
        if (str_contains($userAgent, 'Macintosh') || str_contains($userAgent, 'Mac OS X')) return 'macOS';
        if (str_contains($userAgent, 'Linux')) return 'Linux';
        return 'Other';
    }

    /**
     * Register page hit ping
     */
    public function ping(Request $request)
    {
        $pageUrl = $request->input('page', '/');
        $pageTitle = $request->input('title', 'Innotech Medical');

        // Clean URL
        $path = parse_url($pageUrl, PHP_URL_PATH) ?: '/';

        // Ignore admin pages from visitor analytics
        if (Str::startsWith($path, '/admin') || Str::startsWith($path, '/api/')) {
            return response()->json(['status' => 'ignored']);
        }

        $ip = $request->ip();
        $userAgent = $request->userAgent() ?: '';
        $sessionId = ($request->hasSession()) ? $request->session()->getId() : md5($ip . ($request->header('User-Agent') ?: ''));
        $today = now()->toDateString();

        $device = $this->getDeviceType($userAgent);
        $browser = $this->getBrowser($userAgent);
        $platform = $this->getPlatform($userAgent);

        // Strictly prevent count increment on page refresh:
        // Check if this user (IP or session) has ALREADY been recorded on this page today
        $alreadyLoggedToday = VisitorLog::where('visit_date', $today)
            ->where('page_url', $path)
            ->where(function ($q) use ($ip, $sessionId) {
                $q->where('ip_address', $ip);
                if ($sessionId) {
                    $q->orWhere('session_id', $sessionId);
                }
            })
            ->first();

        if ($alreadyLoggedToday) {
            // User already counted today for this page - do not duplicate log or view count
            return response()->json([
                'status' => 'already_counted_today',
                'log_id' => $alreadyLoggedToday->id,
            ]);
        }

        // Record individual log ONLY ONCE per user per day
        $log = VisitorLog::create([
            'ip_address' => $ip,
            'session_id' => $sessionId,
            'visit_date' => $today,
            'device_type' => $device,
            'browser' => $browser,
            'platform' => $platform,
            'page_url' => $path,
            'page_title' => Str::limit($pageTitle, 200),
            'duration_seconds' => 0,
        ]);

        // Aggregate in page_analytics - increment ONLY on initial visit today
        $analytic = PageAnalytic::firstOrNew(['page_url' => $path]);
        $isFirstVisitForUser = !VisitorLog::where('page_url', $path)
            ->where('ip_address', $ip)
            ->where('id', '!=', $log->id)
            ->exists();

        if ($pageTitle && empty($analytic->page_title)) {
            $analytic->page_title = Str::limit($pageTitle, 200);
        }

        $analytic->total_views = ($analytic->total_views ?: 0) + 1;
        if ($isFirstVisitForUser) {
            $analytic->unique_visitors = ($analytic->unique_visitors ?: 0) + 1;
        }
        $analytic->last_visited_at = now();
        $analytic->save();

        return response()->json([
            'status' => 'recorded',
            'log_id' => $log->id,
        ]);
    }

    /**
     * Register time spent / dwell duration upon leave
     */
    public function leave(Request $request)
    {
        $data = json_decode($request->getContent(), true) ?: $request->all();
        $logId = $data['log_id'] ?? null;
        $duration = (int) ($data['duration'] ?? 0);

        if (!$logId || $duration <= 0) {
            return response()->json(['status' => 'skipped']);
        }

        // Cap duration at 1 hour to prevent runaway idle tabs
        $duration = min($duration, 3600);

        $log = VisitorLog::find($logId);
        if ($log) {
            $log->update(['duration_seconds' => $duration]);

            $analytic = PageAnalytic::where('page_url', $log->page_url)->first();
            if ($analytic && $analytic->total_views > 0) {
                $analytic->total_duration_seconds += $duration;
                $analytic->avg_duration_seconds = (int) round($analytic->total_duration_seconds / $analytic->total_views);
                $analytic->save();
            }
        }

        return response()->json(['status' => 'updated']);
    }
}
