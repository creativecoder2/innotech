<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Models\AdminLoginLog;
use App\Models\AdminActionLog;
use App\Helpers\GeoIpHelper;

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

            // 2. Throttle updating last_activity_at to once per minute for presence
            $cacheKey = 'admin_act_' . md5($sessionId);
            if (!Cache::has($cacheKey)) {
                Cache::put($cacheKey, true, 60);

                if ($activeLog && $activeLog->is_active_session) {
                    $activeLog->update(['last_activity_at' => now()]);
                }
            }
        }

        $response = $next($request);

        // 3. Admin Operational Actions & Error Tracking
        if (Auth::check()) {
            $this->logAdminAction($request, $response);
        }

        return $response;
    }

    /**
     * Record the admin action, path, payload, and status/error details
     */
    protected function logAdminAction(Request $request, $response): void
    {
        try {
            $path = trim($request->path(), '/');

            // Exclude noise/polling/read-heavy endpoints
            if (
                $request->is('admin/notifications/unread-count') ||
                $request->is('admin/live-chat/unread*') ||
                $request->is('admin/analytics/realtime') ||
                $request->is('assets/*')
            ) {
                return;
            }

            $method = strtoupper($request->method());
            $isMutating = in_array($method, ['POST', 'PUT', 'PATCH', 'DELETE']);

            // Only log mutating actions OR distinct section GET page views (ignore background AJAX partials)
            if (!$isMutating && ($request->ajax() || $request->wantsJson())) {
                return;
            }

            $user = Auth::user();
            $statusCode = method_exists($response, 'getStatusCode') ? $response->getStatusCode() : 200;

            // Determine status & error messages
            $status = 'success';
            $errorMessage = null;

            if ($request->hasSession()) {
                if ($request->session()->has('errors')) {
                    $status = 'failed';
                    $bag = $request->session()->get('errors');
                    if (is_object($bag) && method_exists($bag, 'all')) {
                        $errorMessage = implode(' | ', $bag->all());
                    }
                } elseif ($request->session()->has('error')) {
                    $status = 'failed';
                    $errorMessage = (string) $request->session()->get('error');
                }
            }

            if ($statusCode >= 400) {
                $status = ($statusCode === 422) ? 'failed' : 'error';
                if (empty($errorMessage)) {
                    $errorMessage = "HTTP {$statusCode} error occurred.";
                }
            }

            // Determine Module & Human-Friendly Action Description
            list($module, $actionDescription) = $this->resolveModuleAndAction($request, $method, $status);

            // Sanitize payload parameters
            $sanitizedData = null;
            if ($isMutating) {
                $allInputs = $request->except([
                    'password', 'password_confirmation', 'plain_password', 'current_password',
                    '_token', '_method'
                ]);

                // Flatten uploaded files to descriptions
                foreach ($allInputs as $k => $v) {
                    if ($request->hasFile($k)) {
                        $file = $request->file($k);
                        $allInputs[$k] = is_array($file) 
                            ? count($file) . ' files uploaded' 
                            : 'File: ' . $file->getClientOriginalName() . ' (' . round($file->getSize() / 1024, 1) . ' KB)';
                    }
                }
                $sanitizedData = !empty($allInputs) ? $allInputs : null;
            }

            $ip = $request->ip();
            $userAgent = $request->userAgent() ?: '';

            AdminActionLog::create([
                'user_id' => $user->id,
                'admin_name' => $user->name,
                'admin_role' => $user->role ?: 'Administrator',
                'module' => $module,
                'action' => $actionDescription,
                'method' => $method,
                'url' => '/' . $path,
                'ip_address' => $ip,
                'location' => GeoIpHelper::getLocation($ip),
                'device_type' => GeoIpHelper::getDeviceType($userAgent),
                'browser' => GeoIpHelper::getBrowser($userAgent),
                'os' => GeoIpHelper::getOs($userAgent),
                'status' => $status,
                'status_code' => $statusCode,
                'error_message' => $errorMessage,
                'request_data' => $sanitizedData,
            ]);

        } catch (\Throwable $e) {
            // Never fail the user's request if logging encounters an issue
            \Log::warning('TrackAdminActivity error: ' . $e->getMessage());
        }
    }

    /**
     * Map request to Module and Description
     */
    protected function resolveModuleAndAction(Request $request, string $method, string $status): array
    {
        $path = $request->path();
        $module = 'System';
        $action = "Accessed /{$path}";

        if (str_contains($path, 'admin/products')) {
            $module = 'Products';
            if ($method === 'POST') {
                $action = str_contains($path, 'delete') ? 'Deleted Product' : (str_contains($path, 'update') ? 'Updated Product' : 'Created New Product');
            } elseif ($method === 'DELETE') {
                $action = 'Deleted Product';
            } else {
                $action = str_contains($path, 'create') ? 'Opened New Product Form' : (str_contains($path, 'edit') ? 'Opened Product Edit Form' : 'Viewed Products Catalog');
            }
        } elseif (str_contains($path, 'admin/settings')) {
            $module = 'Settings';
            $action = ($method === 'POST') ? 'Updated Global Settings' : 'Viewed Global Settings';
        } elseif (str_contains($path, 'admin/users')) {
            $module = 'Users & Roles';
            if ($method === 'POST') {
                if (str_contains($path, 'toggle-status')) {
                    $action = 'Toggled Admin Account Status';
                } elseif (str_contains($path, 'delete')) {
                    $action = 'Deleted Admin Account';
                } elseif (str_contains($path, 'update')) {
                    $action = 'Updated Admin User Details';
                } else {
                    $action = 'Created New Administrator';
                }
            } elseif ($method === 'DELETE') {
                $action = 'Deleted Admin Account';
            } else {
                $action = 'Viewed Admin Users List';
            }
        } elseif (str_contains($path, 'admin/logs')) {
            $module = 'Security Logs';
            if (str_contains($path, 'revoke')) {
                $action = 'Revoked Active Device Session';
            } elseif (str_contains($path, 'clean')) {
                $action = 'Cleared Inactive Logs';
            } else {
                $action = 'Viewed Security & Session Tracking';
            }
        } elseif (str_contains($path, 'admin/home-sections')) {
            $module = 'Home Sections';
            $action = ($method === 'POST') ? 'Updated Home Page Section' : 'Managed Home Page Sections';
        } elseif (str_contains($path, 'admin/blogs')) {
            $module = 'Articles & Research';
            $action = ($method === 'POST') ? 'Saved / Updated Blog Article' : 'Viewed Blog Articles';
        } elseif (str_contains($path, 'admin/blog-comments')) {
            $module = 'Blog Comments';
            $action = ($method === 'POST') ? 'Updated Blog Comment Status' : 'Viewed Blog Comments';
        } elseif (str_contains($path, 'admin/services')) {
            $module = 'Services';
            $action = ($method === 'POST') ? 'Updated Medical Service' : 'Viewed Services List';
        } elseif (str_contains($path, 'admin/companies')) {
            $module = 'Companies & Brands';
            $action = ($method === 'POST') ? 'Saved Brand / Company' : 'Managed Companies & Brands';
        } elseif (str_contains($path, 'admin/inquiries')) {
            $module = 'Inquiries & Quotes';
            $action = 'Viewed Customer Inquiries & Quotes';
        } elseif (str_contains($path, 'admin/profile')) {
            $module = 'Admin Profile';
            $action = ($method === 'POST') ? 'Updated Profile Credentials' : 'Viewed Admin Profile';
        } elseif (str_contains($path, 'admin/dashboard')) {
            $module = 'Dashboard';
            $action = 'Viewed Main Analytics Dashboard';
        } elseif (str_contains($path, 'admin/live-chat')) {
            $module = 'Live Support Chat';
            $action = ($method === 'POST') ? 'Replied to Live Chat Message' : 'Viewed Live Support Chat';
        }

        if ($status === 'failed') {
            $action .= ' (Failed / Validation Error)';
        }

        return [$module, $action];
    }
}
