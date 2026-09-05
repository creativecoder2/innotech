<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Models\AdminLoginLog;

class TrackAdminActivity
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $sessionId = $request->session()->getId();

            // 1. Check if this session was revoked remotely by a Super Admin
            $activeLog = AdminLoginLog::where('session_id', $sessionId)->latest('id')->first();
            if ($activeLog && !$activeLog->is_active_session && $activeLog->status === 'revoked') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')->withErrors([
                    'login' => 'Your session was remotely terminated by a Super Administrator.'
                ]);
            }

            // 2. Throttle updating last_activity_at to once per minute
            $cacheKey = 'admin_act_' . md5($sessionId);
            if (!Cache::has($cacheKey)) {
                Cache::put($cacheKey, true, 60);

                if ($activeLog && $activeLog->is_active_session) {
                    $activeLog->update(['last_activity_at' => now()]);
                }
            }
        }

        return $next($request);
    }
}
