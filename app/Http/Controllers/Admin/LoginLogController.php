<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminLoginLog;
use App\Models\AdminActionLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class LoginLogController extends Controller
{
    /**
     * Display Active Sessions and Login History Grids.
     */
    public function index(Request $request)
    {
        // 1. Metric Counters
        $totalActiveSessions = AdminLoginLog::where('is_active_session', true)->count();
        $onlineNowCount = AdminLoginLog::online()->count();
        $loginsToday = AdminLoginLog::whereDate('created_at', today())->where('status', 'success')->count();
        $failedToday = AdminLoginLog::whereDate('created_at', today())->where('status', 'failed')->count();

        // 2. Active Sessions Grid
        $activeSessions = AdminLoginLog::with('user')
            ->where('is_active_session', true)
            ->orderBy('last_activity_at', 'desc')
            ->get();

        // 3. Chronological History Logs with Filters & Pagination
        $query = AdminLoginLog::with('user');

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('ip_address', 'like', "%{$search}%")
                  ->orWhere('email_or_phone', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%")
                  ->orWhere('browser', 'like', "%{$search}%")
                  ->orWhere('os', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $logs = $query->orderBy('id', 'desc')->paginate(20)->withQueryString();
        $users = User::orderBy('name')->get();
        $currentSessionId = $request->session()->getId();
        $rootAdminId = User::orderBy('id', 'asc')->value('id') ?: 1;

        // 4. Admin Operational Actions & Error Audit Trail
        $actionQuery = AdminActionLog::with('user');

        if ($request->filled('action_user_id')) {
            $actionQuery->where('user_id', $request->action_user_id);
        }

        if ($request->filled('action_module')) {
            $actionQuery->where('module', $request->action_module);
        }

        if ($request->filled('action_status')) {
            $actionQuery->where('status', $request->action_status);
        }

        if ($request->filled('action_search')) {
            $actionSearch = trim($request->action_search);
            $actionQuery->where(function ($q) use ($actionSearch) {
                $q->where('action', 'like', "%{$actionSearch}%")
                  ->orWhere('module', 'like', "%{$actionSearch}%")
                  ->orWhere('url', 'like', "%{$actionSearch}%")
                  ->orWhere('admin_name', 'like', "%{$actionSearch}%")
                  ->orWhere('error_message', 'like', "%{$actionSearch}%")
                  ->orWhere('ip_address', 'like', "%{$actionSearch}%");
            });
        }

        $actionLogs = $actionQuery->orderBy('id', 'desc')->paginate(15, ['*'], 'action_page')->withQueryString();

        // Action Metrics
        $totalActionsToday = AdminActionLog::whereDate('created_at', today())->count();
        $totalUpdatesToday = AdminActionLog::whereDate('created_at', today())->whereIn('method', ['POST', 'PUT', 'PATCH', 'DELETE'])->count();
        $totalErrorsToday = AdminActionLog::whereDate('created_at', today())->whereIn('status', ['failed', 'error'])->count();

        $actionModules = AdminActionLog::distinct()->pluck('module')->filter()->values();

        return view('admin.logs.index', compact(
            'totalActiveSessions',
            'onlineNowCount',
            'loginsToday',
            'failedToday',
            'activeSessions',
            'logs',
            'users',
            'currentSessionId',
            'rootAdminId',
            'actionLogs',
            'totalActionsToday',
            'totalUpdatesToday',
            'totalErrorsToday',
            'actionModules'
        ));
    }

    /**
     * Remotely revoke / terminate an active session.
     */
    public function revokeSession(Request $request, $id)
    {
        $log = AdminLoginLog::with('user')->findOrFail($id);

        // 1. Cannot revoke your own current session from this button
        if ($log->session_id === $request->session()->getId()) {
            return back()->with('error', 'You cannot revoke your own current session. Use the Sign Out button instead.');
        }

        // 2. Main Super Admin session CANNOT be revoked by ANYONE
        $rootAdminId = User::orderBy('id', 'asc')->value('id') ?: 1;
        $isRootAdminSession = ($log->user_id === 1) || ($log->user_id === $rootAdminId);

        if ($isRootAdminSession) {
            return back()->with('error', 'Security Violation: The Primary Super Administrator session is protected and cannot be revoked by anyone.');
        }

        // 3. Only the Primary Super Admin (ID 1) or authorized Super Admin can revoke sessions
        if (Auth::id() !== 1 && Auth::user()->role !== 'Super Admin') {
            return back()->with('error', 'Permission Denied: Only Super Administrators can revoke active sessions.');
        }

        // 4. Secondary admins cannot revoke another Super Admin's session
        if (Auth::id() !== 1 && $log->user && $log->user->role === 'Super Admin') {
            return back()->with('error', 'Permission Denied: You cannot revoke another Super Administrator\'s session.');
        }

        $log->update([
            'is_active_session' => false,
            'status' => 'revoked',
            'logged_out_at' => now(),
        ]);

        // If sessions table exists, delete it
        if (Schema::hasTable('sessions') && $log->session_id) {
            DB::table('sessions')->where('id', $log->session_id)->delete();
        }

        $userName = $log->user ? $log->user->name : 'Administrator';
        return back()->with('success', "Active session for '{$userName}' ({$log->device_type} - {$log->ip_address}) has been revoked.");
    }

    /**
     * Clear old history logs (older than 30 days).
     */
    public function clearOldLogs(Request $request)
    {
        if (Auth::id() !== 1 && Auth::user()->role !== 'Super Admin') {
            return back()->with('error', 'Only Super Administrators can clear log history.');
        }

        $count = AdminLoginLog::where('is_active_session', false)
            ->where('created_at', '<', now()->subDays(30))
            ->delete();

        return back()->with('success', "Cleaned {$count} inactive log records older than 30 days.");
    }
}
