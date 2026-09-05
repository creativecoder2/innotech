@extends('admin.layouts.master')

@section('title', 'Login Logs & Active Sessions')
@section('header_title', 'Security & Session Tracking')

@section('content')

    <!-- TOP HEADER -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h4 class="mb-1 text-dark fw-bold">Login History & Real-Time Active Sessions</h4>
            <p class="text-muted mb-0">Monitor active administrator logins, connected devices, geographic locations, and security audit logs.</p>
        </div>
        <div class="d-flex gap-2">
            @if(Auth::id() === 1 || Auth::user()->role === 'Super Admin')
                <form action="{{ route('admin.logs.clear_old') }}" method="POST" onsubmit="return confirm('Are you sure you want to prune inactive logs older than 30 days?');">
                    @csrf
                    <button type="submit" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center gap-1">
                        <i class="fa-solid fa-broom"></i> Clean Old Logs (>30 Days)
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- FLASH MESSAGES -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 p-3 mb-4 shadow-sm" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-3 p-3 mb-4 shadow-sm" role="alert">
            <i class="fa-solid fa-triangle-exclamation me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- METRICS OVERVIEW -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-sm-6">
            <div class="admin-card p-3 d-flex align-items-center gap-3">
                <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 50px; height: 50px; background: rgba(14, 99, 255, 0.1); color: #0E63FF; font-size: 20px;">
                    <i class="fa-solid fa-display"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-1 small fw-semibold">Active Sessions</h6>
                    <h3 class="mb-0 fw-bold text-dark">{{ $totalActiveSessions }} <span class="fs-6 fw-normal text-muted">devices</span></h3>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6">
            <div class="admin-card p-3 d-flex align-items-center gap-3">
                <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 50px; height: 50px; background: rgba(16, 185, 129, 0.1); color: #10b981; font-size: 20px;">
                    <i class="fa-solid fa-signal-stream fa-fade"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-1 small fw-semibold">Online Right Now</h6>
                    <h3 class="mb-0 fw-bold text-success d-flex align-items-center gap-2">
                        {{ $onlineNowCount }} 
                        <span class="online-indicator-pill">Online</span>
                    </h3>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6">
            <div class="admin-card p-3 d-flex align-items-center gap-3">
                <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 50px; height: 50px; background: rgba(4, 140, 91, 0.1); color: #048C5B; font-size: 20px;">
                    <i class="fa-solid fa-user-check"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-1 small fw-semibold">Successful Logins Today</h6>
                    <h3 class="mb-0 fw-bold text-dark">{{ $loginsToday }}</h3>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6">
            <div class="admin-card p-3 d-flex align-items-center gap-3">
                <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 50px; height: 50px; background: rgba(239, 68, 68, 0.1); color: #ef4444; font-size: 20px;">
                    <i class="fa-solid fa-shield-xmark"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-1 small fw-semibold">Failed Attempts Today</h6>
                    <h3 class="mb-0 fw-bold {{ $failedToday > 0 ? 'text-danger' : 'text-dark' }}">{{ $failedToday }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- ==========================================
         GRID 1: REAL-TIME ACTIVE SESSIONS & DEVICES
         ========================================== -->
    <div class="admin-card mb-5">
        <div class="p-3 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <h5 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                    <i class="fa-solid fa-network-wired text-primary"></i> 
                    Currently Connected Devices & Real-Time Presence
                </h5>
                <small class="text-muted">Shows where each admin is currently logged in, from which device, IP, and location.</small>
            </div>
            <span class="badge bg-primary px-3 py-1.5 rounded-pill">{{ $activeSessions->count() }} Live Sessions</span>
        </div>

        <div class="table-responsive">
            <table class="table table-custom align-middle mb-0">
                <thead>
                    <tr>
                        <th>Administrator</th>
                        <th>Status</th>
                        <th>Device & OS</th>
                        <th>Browser</th>
                        <th>Location & IP Address</th>
                        <th>Last Activity</th>
                        <th width="140" class="text-end">Remote Control</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($activeSessions as $session)
                        @php
                            $isOnline = $session->is_online;
                            $isCurrent = $session->session_id === $currentSessionId;
                            $isRootAdmin = ($session->user_id === 1) || ($session->user_id === ($rootAdminId ?? 1));
                            $canRevoke = !$isCurrent && !$isRootAdmin && (Auth::id() === 1 || Auth::id() === ($rootAdminId ?? 1) || (Auth::user()->role === 'Super Admin' && optional($session->user)->role !== 'Super Admin'));
                        @endphp
                        <tr class="{{ $isCurrent ? 'bg-primary-subtle bg-opacity-10' : '' }}">
                            <td>
                                <div class="d-flex align-items-center gap-2.5">
                                    <div class="position-relative">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold shadow-sm" 
                                             style="width: 38px; height: 38px; font-size: 14px; background: linear-gradient(135deg, #002244, #0E63FF);">
                                            {{ strtoupper(substr($session->user->name ?? 'A', 0, 1)) }}
                                        </div>
                                        <span class="position-absolute bottom-0 end-0 p-1 {{ $isOnline ? 'bg-success' : 'bg-secondary' }} border border-white rounded-circle" style="width: 10px; height: 10px;" title="{{ $isOnline ? 'Online Now' : 'Idle / Inactive' }}"></span>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark d-flex align-items-center gap-1.5">
                                            {{ $session->user->name ?? 'Unknown User' }}
                                            @if($isRootAdmin)
                                                <span class="badge bg-warning text-dark border border-warning" style="font-size: 10px;">
                                                    <i class="fa-solid fa-crown me-0.5"></i> Main Super Admin
                                                </span>
                                            @endif
                                            @if($isCurrent)
                                                <span class="badge bg-primary text-white" style="font-size: 10px;">This Device</span>
                                            @endif
                                        </div>
                                        <small class="text-muted">{{ $session->user->email ?? $session->email_or_phone }}</small>
                                    </div>
                                </div>
                            </td>

                            <td>
                                @if($isOnline)
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1 font-weight-semibold d-inline-flex align-items-center gap-1.5">
                                        <span class="pulse-dot"></span> Online Now
                                    </span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary border px-2.5 py-1 font-weight-semibold">
                                        <i class="fa-solid fa-moon me-1"></i> Idle / Inactive
                                    </span>
                                @endif
                            </td>

                            <td>
                                <div class="fw-medium text-dark d-flex align-items-center gap-1.5">
                                    @if($session->device_type === 'Mobile')
                                        <i class="fa-solid fa-mobile-screen text-primary"></i>
                                    @elseif($session->device_type === 'Tablet')
                                        <i class="fa-solid fa-tablet-screen-button text-info"></i>
                                    @else
                                        <i class="fa-solid fa-laptop text-secondary"></i>
                                    @endif
                                    <span>{{ $session->os ?: 'Unknown OS' }}</span>
                                </div>
                                <small class="text-muted">{{ $session->device_type ?: 'Desktop' }}</small>
                            </td>

                            <td>
                                <div class="fw-medium text-dark d-flex align-items-center gap-1.5">
                                    @if(str_contains($session->browser, 'Chrome'))
                                        <i class="fa-brands fa-chrome text-danger"></i>
                                    @elseif(str_contains($session->browser, 'Edge'))
                                        <i class="fa-brands fa-edge text-primary"></i>
                                    @elseif(str_contains($session->browser, 'Firefox'))
                                        <i class="fa-brands fa-firefox-browser text-warning"></i>
                                    @elseif(str_contains($session->browser, 'Safari'))
                                        <i class="fa-brands fa-safari text-info"></i>
                                    @else
                                        <i class="fa-solid fa-globe text-muted"></i>
                                    @endif
                                    <span>{{ $session->browser ?: 'Browser' }}</span>
                                </div>
                            </td>

                            <td>
                                <div class="fw-bold text-dark" style="font-size: 13.5px;">
                                    <i class="fa-solid fa-location-dot text-danger me-1"></i> {{ $session->location ?: 'Unknown Location' }}
                                </div>
                                <small class="text-muted font-monospace">{{ $session->ip_address }}</small>
                            </td>

                            <td>
                                <div class="text-dark fw-medium small">
                                    {{ $session->last_activity_at ? $session->last_activity_at->diffForHumans() : 'N/A' }}
                                </div>
                                <small class="text-muted">In: {{ $session->created_at ? $session->created_at->format('h:i A') : '' }}</small>
                            </td>

                            <td class="text-end">
                                @if($isCurrent)
                                    <span class="badge bg-light text-muted border px-2 py-1 small">Current Session</span>
                                @elseif($isRootAdmin)
                                    <span class="badge bg-warning-subtle text-dark border border-warning px-2.5 py-1 small fw-semibold" title="Protected: Main Super Admin session cannot be revoked by anyone">
                                        <i class="fa-solid fa-shield-halved text-warning me-1"></i> Protected
                                    </span>
                                @elseif($canRevoke)
                                    <form action="{{ route('admin.logs.revoke_session', $session->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to remotely terminate this session for {{ $session->user->name ?? 'User' }}? They will be logged out immediately.');">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-danger px-2.5 py-1 d-inline-flex align-items-center gap-1 shadow-sm" title="Revoke and force logout this device">
                                            <i class="fa-solid fa-power-off"></i> Revoke
                                        </button>
                                    </form>
                                @else
                                    <span class="badge bg-light text-muted border px-2 py-1 small">Restricted</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                <i class="fa-solid fa-laptop-slash fs-2 mb-2 opacity-50"></i>
                                <p class="mb-0 fw-semibold">No active sessions currently tracked.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>


    <!-- ==========================================
         GRID 2: CHRONOLOGICAL LOGIN HISTORY (PAGINATED)
         ========================================== -->
    <div class="admin-card">
        <div class="p-3 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <h5 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                    <i class="fa-solid fa-clock-rotate-left text-success"></i> 
                    Full Login History & Security Audit Logs
                </h5>
                <small class="text-muted">Every individual login attempt, logout, and security event recorded chronologically.</small>
            </div>
            <span class="badge bg-light text-dark border px-3 py-1.5">{{ $logs->total() }} Total Event Logs</span>
        </div>

        <!-- SEARCH & FILTER CONTROLS -->
        <div class="p-3 bg-light border-bottom">
            <form method="GET" action="{{ route('admin.logs.index') }}" class="row g-2 align-items-center">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0" placeholder="Search by IP, user, location, browser..." value="{{ request('search') }}">
                    </div>
                </div>

                <div class="col-md-3">
                    <select name="user_id" class="form-select" onchange="this.form.submit()">
                        <option value="">All Administrators</option>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>{{ $u->name }} ({{ $u->email }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <select name="status" class="form-select" onchange="this.form.submit()">
                        <option value="">All Statuses</option>
                        <option value="success" {{ request('status') === 'success' ? 'selected' : '' }}>Successful Logins</option>
                        <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed Attempts</option>
                        <option value="logged_out" {{ request('status') === 'logged_out' ? 'selected' : '' }}>Normal Logouts</option>
                        <option value="revoked" {{ request('status') === 'revoked' ? 'selected' : '' }}>Revoked Sessions</option>
                    </select>
                </div>

                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100"><i class="fa-solid fa-filter me-1"></i> Filter</button>
                    @if(request()->hasAny(['search', 'user_id', 'status']))
                        <a href="{{ route('admin.logs.index') }}" class="btn btn-light border" title="Reset Filters"><i class="fa-solid fa-rotate-left"></i></a>
                    @endif
                </div>
            </form>
        </div>

        <!-- HISTORY TABLE -->
        <div class="table-responsive">
            <table class="table table-custom align-middle mb-0">
                <thead>
                    <tr>
                        <th width="60">#</th>
                        <th>User Account</th>
                        <th>Method</th>
                        <th>IP Address & Location</th>
                        <th>Device / OS</th>
                        <th>Browser</th>
                        <th>Status</th>
                        <th>Timestamp</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr class="{{ $log->status === 'failed' ? 'bg-danger-subtle bg-opacity-10' : '' }}">
                            <td class="text-muted fw-bold">{{ $loop->iteration + ($logs->currentPage() - 1) * $logs->perPage() }}</td>
                            
                            <td>
                                @if($log->user)
                                    <div class="fw-bold text-dark">{{ $log->user->name }}</div>
                                    <small class="text-muted">{{ $log->user->email }}</small>
                                @else
                                    <div class="fw-bold text-danger">Failed User Attempt</div>
                                    <small class="text-muted font-monospace">{{ $log->email_or_phone }}</small>
                                @endif
                            </td>

                            <td>
                                <span class="badge {{ $log->login_method === 'Phone' ? 'bg-info text-dark' : 'bg-light text-dark border' }} px-2 py-1">
                                    <i class="fa-solid {{ $log->login_method === 'Phone' ? 'fa-phone' : 'fa-envelope' }} me-1"></i> {{ $log->login_method ?: 'Email' }}
                                </span>
                            </td>

                            <td>
                                <div class="fw-bold text-dark" style="font-size: 13.5px;">
                                    <i class="fa-solid fa-location-dot text-danger me-1"></i> {{ $log->location ?: 'Unknown' }}
                                </div>
                                <small class="text-muted font-monospace">{{ $log->ip_address }}</small>
                            </td>

                            <td>
                                <div class="fw-medium text-dark">
                                    @if($log->device_type === 'Mobile')
                                        <i class="fa-solid fa-mobile-screen text-primary me-1"></i>
                                    @elseif($log->device_type === 'Tablet')
                                        <i class="fa-solid fa-tablet-screen-button text-info me-1"></i>
                                    @else
                                        <i class="fa-solid fa-laptop text-secondary me-1"></i>
                                    @endif
                                    {{ $log->os ?: 'Unknown OS' }}
                                </div>
                                <small class="text-muted">{{ $log->device_type ?: 'Desktop' }}</small>
                            </td>

                            <td>
                                <span class="text-dark fw-medium">{{ $log->browser ?: 'Browser' }}</span>
                            </td>

                            <td>
                                @if($log->status === 'success')
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1 font-weight-semibold">
                                        <i class="fa-solid fa-check me-1"></i> Logged In
                                    </span>
                                @elseif($log->status === 'failed')
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2.5 py-1 font-weight-semibold">
                                        <i class="fa-solid fa-xmark me-1"></i> Failed Attempt
                                    </span>
                                @elseif($log->status === 'logged_out')
                                    <span class="badge bg-secondary-subtle text-secondary border px-2.5 py-1 font-weight-semibold">
                                        <i class="fa-solid fa-right-from-bracket me-1"></i> Logged Out
                                    </span>
                                @elseif($log->status === 'revoked')
                                    <span class="badge bg-warning-subtle text-dark border border-warning-subtle px-2.5 py-1 font-weight-semibold">
                                        <i class="fa-solid fa-ban me-1"></i> Revoked
                                    </span>
                                @endif
                            </td>

                            <td>
                                <div class="text-dark fw-medium small">
                                    {{ $log->created_at ? $log->created_at->format('M d, Y h:i A') : 'N/A' }}
                                </div>
                                <small class="text-muted">{{ $log->created_at ? $log->created_at->diffForHumans() : '' }}</small>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-shield-halved fs-1 text-muted opacity-50 mb-2"></i>
                                <p class="mb-0 fw-semibold">No login history records found matching criteria.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-3 border-top d-flex flex-column flex-md-row align-items-center justify-content-between gap-2 bg-light bg-opacity-50">
            <div class="small text-muted">
                Showing <span class="fw-bold text-dark">{{ $logs->firstItem() ?? 0 }}</span> to <span class="fw-bold text-dark">{{ $logs->lastItem() ?? 0 }}</span> of <span class="fw-bold text-dark">{{ $logs->total() }}</span> total audit logs
            </div>
            @if($logs->hasPages())
                <div class="pagination-container m-0">
                    {{ $logs->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- STYLING FOR PULSING DOT AND PILL -->
    <style>
        .online-indicator-pill {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            background-color: #DCFCE7;
            color: #15803D;
            border: 1px solid #86EFAC;
            border-radius: 20px;
            padding: 2px 8px;
        }
        .pulse-dot {
            display: inline-block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: #10B981;
            box-shadow: 0 0 0 rgba(16, 185, 129, 0.4);
            animation: pulseDot 1.8s infinite;
        }
        @keyframes pulseDot {
            0% {
                box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
            }
            70% {
                box-shadow: 0 0 0 8px rgba(16, 185, 129, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(16, 185, 129, 0);
            }
        }
    </style>

@endsection
