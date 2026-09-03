<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin Dashboard') - Innotech Medical Admin Portal</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset(\App\Models\Setting::get('favicon_path', 'assets/img/logo/favicon.png')) }}">

    <!-- Google Fonts & Theme Styles -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- CSS Dependencies from Website Theme -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/font-awesome-pro.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/flaticon.css') }}">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <style>
        :root {
            --primary: #0E63FF;
            --primary-dark: #094ecc;
            --primary-light: #EBF2FE;
            --dark-navy: #002244;
            --sidebar-bg: #0A192F;
            --sidebar-hover: #172A45;
            --text-dark: #1E293B;
            --text-muted: #64748B;
            --bg-body: #F4F7FB;
            --card-border: #E2E8F0;
        }

        /* Universal Typography Across Whole Admin Portal (Excluding Icons) */
        body, html, input, button, select, textarea, .form-control, .form-select, .btn, .table, h1, h2, h3, h4, h5, h6, .nav-link, .dropdown-menu, .card, .admin-card, .sidebar-menu, .nav-item-link, p, label {
            font-family: 'Outfit', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            letter-spacing: -0.01em;
        }

        /* Protect Font Awesome Pro & Flaticon Icon Fonts */
        i[class*="fa-"], [class*="fa-"], [class^="fa-"], .fa, .fas, .far, .fab, .fal, .fad, .fat, [class*="flaticon-"],
        i[class*="fa-"]::before, [class*="fa-"]::before, [class^="fa-"]::before, .fa::before, .fas::before, .far::before, .fab::before, .fal::before, .fad::before, .fat::before, [class*="flaticon-"]::before {
            font-family: var(--fa-style-family, "Font Awesome 6 Pro") !important;
        }
        .fa-brands, .fa-brands::before, .fab, .fab::before {
            font-family: "Font Awesome 6 Brands" !important;
        }

        body {
            font-family: 'Outfit', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--bg-body);
            color: var(--text-dark);
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            font-size: 14px;
            line-height: 1.55;
        }

        h1, .h1 { font-size: 23px; font-weight: 700; color: var(--dark-navy); }
        h2, .h2 { font-size: 20px; font-weight: 700; color: var(--dark-navy); }
        h3, .h3 { font-size: 18px; font-weight: 700; color: var(--dark-navy); }
        h4, .h4 { font-size: 16.5px; font-weight: 600; color: var(--dark-navy); }
        h5, .h5 { font-size: 15.5px; font-weight: 600; color: var(--dark-navy); }
        h6, .h6 { font-size: 14px; font-weight: 600; color: var(--dark-navy); }

        .form-label {
            font-size: 13.5px;
            font-weight: 600;
            color: #334155;
            margin-bottom: 6px;
        }

        .form-control, .form-select {
            font-size: 13.5px;
            border-radius: 8px;
            border-color: #CBD5E1;
            padding: 9px 14px;
            color: #1E293B;
        }

        .form-control:focus, .form-select:focus {
            border-color: #0E63FF;
            box-shadow: 0 0 0 3px rgba(14, 99, 255, 0.12);
        }

        .btn {
            font-size: 13.5px;
            font-weight: 600;
            border-radius: 8px;
        }

        /* Sidebar Styling */
        .admin-sidebar {
            width: 270px;
            background-color: var(--sidebar-bg);
            min-height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            z-index: 1000;
            transition: all 0.3s ease;
            box-shadow: 4px 0 20px rgba(0,0,0,0.06);
            display: flex;
            flex-direction: column;
        }

        .sidebar-brand {
            padding: 24px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .sidebar-brand img {
            max-height: 42px;
            max-width: 170px;
            object-fit: contain;
        }

        .sidebar-menu {
            padding: 20px 12px;
            flex-grow: 1;
            overflow-y: auto;
        }

        .menu-header {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #64748B;
            padding: 12px 14px 6px;
        }

        .nav-item-link {
            display: flex;
            align-items: center;
            padding: 11px 16px;
            color: #94A3B8;
            border-radius: 8px;
            font-weight: 500;
            font-size: 14px;
            text-decoration: none;
            margin-bottom: 4px;
            transition: all 0.2s ease;
        }

        .nav-item-link i {
            width: 22px;
            font-size: 16px;
            margin-right: 12px;
            color: #64748B;
            transition: color 0.2s ease;
        }

        .nav-item-link:hover {
            color: #FFFFFF;
            background-color: var(--sidebar-hover);
        }

        .nav-item-link:hover i {
            color: var(--primary);
        }

        .nav-item-link.active {
            color: #FFFFFF;
            background: linear-gradient(90deg, #0E63FF 0%, #002244 100%);
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(14, 99, 255, 0.3);
        }

        .nav-item-link.active i {
            color: #FFFFFF;
        }

        .badge-count {
            margin-left: auto;
            background-color: #EF4444;
            color: #fff;
            font-size: 11px;
            padding: 2px 8px;
            border-radius: 10px;
            font-weight: 700;
        }

        .badge-highlight {
            margin-left: auto;
            background: linear-gradient(135deg, #0E63FF, #00D2FF);
            color: #fff;
            font-size: 10px;
            padding: 2px 7px;
            border-radius: 6px;
            font-weight: 800;
            text-transform: uppercase;
        }

        /* Main Content Wrapper */
        .admin-main {
            margin-left: 270px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease;
        }

        /* Top Header */
        .admin-header {
            background-color: #FFFFFF;
            height: 72px;
            padding: 0 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid var(--card-border);
            position: sticky;
            top: 0;
            z-index: 99;
            box-shadow: 0 2px 10px rgba(0,0,0,0.02);
        }

        .header-left h1 {
            font-size: 20px;
            font-weight: 700;
            color: var(--dark-navy);
            margin: 0;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .btn-view-site {
            background-color: var(--primary-light);
            color: var(--primary);
            font-weight: 600;
            font-size: 13px;
            padding: 8px 16px;
            border-radius: 8px;
            text-decoration: none;
            border: 1px solid rgba(14, 99, 255, 0.15);
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s ease;
        }

        .btn-view-site:hover {
            background-color: var(--primary);
            color: #fff;
        }

        .user-dropdown-btn {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 6px 12px;
            border-radius: 8px;
            border: 1px solid var(--card-border);
            background: #fff;
            color: var(--text-dark);
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, #0E63FF 0%, #002244 100%);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 14px;
        }

        /* Content Area */
        .admin-body {
            padding: 30px;
            flex-grow: 1;
        }

        /* Card Styles */
        .admin-card {
            background: #FFFFFF;
            border-radius: 12px;
            border: 1px solid var(--card-border);
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
            margin-bottom: 24px;
            overflow: hidden;
        }

        .admin-card-header {
            padding: 20px 24px;
            border-bottom: 1px solid var(--card-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .admin-card-header h5 {
            font-size: 17px;
            font-weight: 700;
            color: var(--dark-navy);
            margin: 0;
        }

        .admin-card-body {
            padding: 24px;
        }

        /* Primary Button */
        .btn-theme {
            background-color: var(--primary);
            color: #FFFFFF;
            font-weight: 600;
            padding: 9px 20px;
            border-radius: 8px;
            border: none;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s ease;
        }

        .btn-theme:hover {
            background-color: var(--primary-dark);
            color: #FFFFFF;
            box-shadow: 0 4px 12px rgba(14, 99, 255, 0.25);
        }

        /* Tables */
        .table-custom {
            margin: 0;
        }

        .table-custom th {
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            color: #64748B;
            background: #F8FAFC;
            padding: 14px 16px;
            border-bottom: 1px solid var(--card-border);
        }

        .table-custom td {
            padding: 14px 16px;
            vertical-align: middle;
            font-size: 14px;
            border-bottom: 1px solid #F1F5F9;
        }

        .badge-status {
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-status.active {
            background: #DCFCE7;
            color: #15803D;
        }

        .badge-status.unread {
            background: #FEE2E2;
            color: #B91C1C;
        }

        .badge-status.read {
            background: #FEF3C7;
            color: #B45309;
        }

        .badge-status.replied {
            background: #DBEAFE;
            color: #1D4ED8;
        }

        @media(max-width: 991px) {
            .admin-sidebar {
                left: -270px;
            }
            .admin-sidebar.active {
                left: 0;
            }
        /* Modern Admin Pagination Styles */
        .pagination {
            margin-bottom: 0;
            gap: 4px;
        }
        .pagination .page-item .page-link {
            border-radius: 6px !important;
            border: 1px solid #E2E8F0;
            color: var(--text-dark);
            padding: 6px 12px;
            font-size: 13px;
            font-weight: 500;
            transition: all 0.2s ease;
        }
        .pagination .page-item.active .page-link {
            background-color: var(--primary);
            border-color: var(--primary);
            color: #ffffff;
            box-shadow: 0 2px 6px rgba(14, 99, 255, 0.25);
        }
        .pagination .page-item.disabled .page-link {
            background-color: #F8FAFC;
            color: #94A3B8;
            border-color: #E2E8F0;
        }
        .pagination .page-item .page-link:hover:not(.active) {
            background-color: var(--primary-light);
            color: var(--primary);
            border-color: var(--primary);
        }
    </style>
    @stack('styles')
</head>
<body>

    <!-- Sidebar Navigation -->
    <aside class="admin-sidebar" id="adminSidebar">
        <div class="sidebar-brand">
            <a href="{{ route('admin.dashboard') }}">
                <img src="{{ asset('assets/img/logo/white-logo.png') }}" alt="Innotech Medical">
            </a>
            <button class="btn btn-sm text-white d-lg-none" id="sidebarCloseBtn"><i class="fa-solid fa-times"></i></button>
        </div>

        <div class="sidebar-menu">
            <div class="menu-header">Overview</div>
            <a href="{{ route('admin.dashboard') }}" class="nav-item-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-grid-2"></i> Dashboard
            </a>

            <!-- Centralized Home Page Sections Manager -->
            <a href="{{ route('admin.home_sections.index') }}" class="nav-item-link {{ request()->routeIs('admin.home_sections.*') ? 'active' : '' }}">
                <i class="fa-solid fa-layer-group"></i> Home Page Manager
                <span class="badge-highlight">All Sections</span>
            </a>

            <div class="menu-header">Products Catalog</div>
            <a href="{{ route('admin.companies.index') }}" class="nav-item-link {{ request()->routeIs('admin.companies.*') ? 'active' : '' }}">
                <i class="fa-solid fa-industry"></i> Companies & Brands
                @php $companiesCount = \App\Models\Company::count(); @endphp
                <span class="badge-highlight">{{ $companiesCount }} Brands</span>
            </a>
            <a href="{{ route('admin.products.index') }}" class="nav-item-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                <i class="fa-solid fa-boxes-stacked"></i> Products Catalog
                @php $productsCount = \App\Models\Product::count(); @endphp
                <span class="badge-count bg-primary text-white" style="background-color: var(--primary) !important;">{{ $productsCount }}</span>
            </a>

            <div class="menu-header">Pages & Content</div>
            <a href="{{ route('admin.blogs.index') }}" class="nav-item-link {{ request()->routeIs('admin.blogs.*') ? 'active' : '' }}">
                <i class="fa-solid fa-newspaper"></i> Articles & Research
            </a>
            <a href="{{ route('admin.blog_comments.index') }}" class="nav-item-link {{ request()->routeIs('admin.blog_comments.*') ? 'active' : '' }}">
                <i class="fa-solid fa-comments"></i> Blog Comments
                @php $pendingComments = \App\Models\BlogComment::where('status', 'pending')->count(); @endphp
                <span class="badge-count bg-warning text-dark {{ $pendingComments > 0 ? '' : 'd-none' }}" id="commentsBadge">{{ $pendingComments }}</span>
            </a>
            <a href="{{ route('admin.pages.index') }}" class="nav-item-link {{ request()->routeIs('admin.pages.*') ? 'active' : '' }}">
                <i class="fa-solid fa-file-contract"></i> Custom Pages & Policies
                <span class="badge-highlight">T&C / Privacy</span>
            </a>

            <div class="menu-header">Leads & Support</div>
            <a href="{{ route('admin.live_chat.index') }}" class="nav-item-link {{ request()->routeIs('admin.live_chat.*') ? 'active' : '' }}">
                <i class="fa-solid fa-headset"></i> Live Support Chat
                @php $unreadChat = \App\Models\ChatConversation::sum('unread_admin'); @endphp
                <span class="badge-count bg-danger text-white {{ $unreadChat > 0 ? '' : 'd-none' }}" id="chatBadge">{{ $unreadChat }}</span>
            </a>
            <a href="{{ route('admin.inquiries.index') }}" class="nav-item-link {{ request()->routeIs('admin.inquiries.*') ? 'active' : '' }}">
                <i class="fa-solid fa-envelope-open-text"></i> Inquiries & Quotes
                @php $unreadCount = \App\Models\Inquiry::where('status', 'unread')->count(); @endphp
                <span class="badge-count {{ $unreadCount > 0 ? '' : 'd-none' }}" id="inquiriesUnreadBadge">{{ $unreadCount }}</span>
            </a>
            <a href="{{ route('admin.settings.index') }}" class="nav-item-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                <i class="fa-solid fa-sliders"></i> Global Settings
            </a>
            <a href="{{ route('admin.profile') }}" class="nav-item-link {{ request()->routeIs('admin.profile') ? 'active' : '' }}">
                <i class="fa-solid fa-user-shield"></i> Admin Profile
            </a>

            <div class="menu-header">Session</div>
            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" class="nav-item-link w-100 text-start border-0 bg-transparent text-danger">
                    <i class="fa-solid fa-right-from-bracket text-danger"></i> Sign Out
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Wrapper -->
    <div class="admin-main">
        <!-- Top Navbar -->
        <header class="admin-header">
            <div class="header-left d-flex align-items-center gap-3">
                <button class="btn btn-light d-lg-none" id="sidebarToggleBtn"><i class="fa-solid fa-bars"></i></button>
                <h1>@yield('header_title', 'Admin Portal')</h1>
            </div>

            <div class="header-right">
                <!-- Desktop Alerts Trigger & Status -->
                <button type="button" id="enableDesktopNotifBtn" class="btn btn-sm btn-outline-warning d-none align-items-center gap-1 fw-bold shadow-sm" style="border-radius: 8px; font-size: 12px; padding: 7px 12px;">
                    <i class="fa-solid fa-bell-ring fa-shake text-warning"></i> <span>Allow Desktop Alerts</span>
                </button>

                <button type="button" id="testDesktopNotifBtn" class="btn btn-light rounded-circle shadow-sm position-relative d-none align-items-center justify-content-center border" title="Desktop Alerts Active (Click to Test Outside Alert)" style="width: 38px; height: 38px;">
                    <i class="fa-solid fa-bell text-primary" style="font-size: 16px;"></i>
                    <span class="position-absolute top-0 start-100 translate-middle p-1 bg-success border border-white rounded-circle" style="margin-left: -6px; margin-top: 6px;"></span>
                </button>

                <a href="{{ url('/') }}" target="_blank" class="btn-view-site">
                    <i class="fa-solid fa-external-link"></i> Live Website
                </a>

                <div class="dropdown">
                    <a href="#" class="user-dropdown-btn dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="user-avatar">{{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}</div>
                        <span class="d-none d-md-inline">{{ Auth::user()->name ?? 'Administrator' }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2" style="border-radius: 8px;">
                        <li><a class="dropdown-item py-2" href="{{ route('admin.profile') }}"><i class="fa-solid fa-user-gear mr-10 text-muted"></i> Account Settings</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('admin.logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item py-2 text-danger"><i class="fa-solid fa-power-off mr-10"></i> Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </header>

        <!-- Main Body -->
        <main class="admin-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 8px;">
                    <i class="fa-solid fa-circle-check mr-10"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 8px;">
                    <i class="fa-solid fa-triangle-exclamation mr-10"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(isset($errors) && $errors->any())
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 8px;">
                    <ul class="mb-0">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('assets/js/jquery.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $('#sidebarToggleBtn').on('click', function() {
            $('#adminSidebar').toggleClass('active');
        });
        $('#sidebarCloseBtn').on('click', function() {
            $('#adminSidebar').removeClass('active');
        });

        // Global Submit Button Spinner on Every Form Submit
        $(document).on('submit', 'form:not(.no-auto-spinner)', function(e) {
            const form = $(this);
            if (this.checkValidity && !this.checkValidity()) {
                return;
            }
            const submitBtn = form.find('button[type="submit"]:visible').first();
            if (submitBtn.length && !submitBtn.data('spinning')) {
                submitBtn.data('spinning', true);
                const currentWidth = submitBtn.outerWidth();
                submitBtn.css('min-width', currentWidth + 'px');
                submitBtn.html('<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Processing...');
                setTimeout(function() {
                    submitBtn.prop('disabled', true);
                }, 10);
            }
        });

        // ==========================================
        // REAL-TIME OUTSIDE-THE-BROWSER NOTIFICATIONS
        // ==========================================
        let lastNotificationCheck = Math.floor(Date.now() / 1000);
        let audioContext = null;
        let swRegistration = null;

        // Register Service Worker for Native Windows 10/11 Toast Notifications
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('{{ asset("sw.js") }}')
                .then(function(reg) {
                    swRegistration = reg;
                    console.log('Notification Service Worker active');
                })
                .catch(function(err) {
                    console.warn('SW register error:', err);
                });
        }

        // Initialize / Resume Web Audio Chime
        function playAlertChime() {
            try {
                if (!audioContext) {
                    audioContext = new (window.AudioContext || window.webkitAudioContext)();
                }
                if (audioContext.state === 'suspended') {
                    audioContext.resume();
                }

                const now = audioContext.currentTime;
                const osc = audioContext.createOscillator();
                const gain = audioContext.createGain();

                osc.connect(gain);
                gain.connect(audioContext.destination);

                // Pleasant 3-tone harmonic chime: E5 -> G#5 -> B5
                osc.type = 'sine';
                osc.frequency.setValueAtTime(659.25, now);
                osc.frequency.setValueAtTime(830.61, now + 0.12);
                osc.frequency.setValueAtTime(987.77, now + 0.24);

                gain.gain.setValueAtTime(0.35, now);
                gain.gain.exponentialRampToValueAtTime(0.01, now + 0.65);

                osc.start(now);
                osc.stop(now + 0.65);
            } catch(e) {}
        }

        // Setup Desktop Notification Permissions
        const enableBtn = $('#enableDesktopNotifBtn');
        const testBtn = $('#testDesktopNotifBtn');

        function updateNotifPermissionUI() {
            if (!("Notification" in window)) {
                enableBtn.addClass('d-none');
                testBtn.addClass('d-none');
                return;
            }

            if (Notification.permission === 'granted') {
                enableBtn.addClass('d-none').removeClass('d-inline-flex');
                testBtn.removeClass('d-none').addClass('d-inline-flex');
            } else if (Notification.permission === 'denied') {
                enableBtn.removeClass('d-none').addClass('d-inline-flex')
                    .html('<i class="fa-solid fa-bell-slash text-danger"></i> <span>Alerts Blocked</span>')
                    .prop('title', 'Please allow notifications in browser URL bar (Lock icon -> Site Settings)');
                testBtn.addClass('d-none').removeClass('d-inline-flex');
            } else {
                enableBtn.removeClass('d-none').addClass('d-inline-flex');
                testBtn.addClass('d-none').removeClass('d-inline-flex');
            }
        }

        enableBtn.on('click', function() {
            if ("Notification" in window) {
                Notification.requestPermission().then(function(permission) {
                    updateNotifPermissionUI();
                    if (permission === 'granted') {
                        fireTestAlert();
                    }
                });
            }
        });

        // Click Bell Icon to trigger an instant outside-the-browser test notification
        testBtn.on('click', function(e) {
            e.preventDefault();
            if (Notification.permission !== 'granted') {
                Notification.requestPermission().then(function(perm) {
                    updateNotifPermissionUI();
                    if (perm === 'granted') {
                        fireTestAlert();
                    } else {
                        if (window.Swal) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Desktop Notifications Blocked',
                                html: 'Your browser is currently blocking desktop notifications.<br><br>Please click the <b>Lock / Tune Icon 🔒</b> in your browser URL bar and set <b>Notifications</b> to <b>Allow</b>.',
                                confirmButtonText: 'Understood'
                            });
                        }
                    }
                });
                return;
            }
            fireTestAlert();
        });

        function fireTestAlert() {
            showDesktopNotification({
                id: 'test_alert_' + Date.now(),
                type: 'inquiry',
                title: '🏥 New Contact Inquiry: Dr. Farhan Qureshi',
                body: 'Urgent quotation requested for ICU Patient Monitor & Ventilators. (Click this banner to open inquiry)',
                url: '{{ route("admin.inquiries.index") }}'
            });

            if (window.Swal) {
                Swal.fire({
                    toast: true,
                    position: 'bottom-end',
                    icon: 'success',
                    title: 'Desktop Notification Dispatched!',
                    text: 'A native notification banner has been sent to your operating system desktop.',
                    showConfirmButton: false,
                    timer: 5000
                });
            }
        }

        // Trigger Outside-the-browser System Notification
        function showDesktopNotification(event) {
            playAlertChime();

            // 1. Outside-the-browser Native System Notification (Windows Desktop Toast)
            if ("Notification" in window && Notification.permission === 'granted') {
                try {
                    const iconUrl = new URL('{{ asset("assets/img/logo/favicon.png") }}', window.location.href).href;
                    const notifOptions = {
                        body: event.body,
                        icon: iconUrl,
                        badge: iconUrl,
                        tag: event.id || 'innotech_alert_' + Date.now(),
                        data: {
                            url: event.url
                        },
                        requireInteraction: true, // Stays visible outside the browser on desktop until clicked
                        silent: false
                    };

                    // Use Service Worker showNotification if available (official Windows OS bridge)
                    if (swRegistration && 'showNotification' in swRegistration) {
                        swRegistration.showNotification(event.title, notifOptions);
                    } else if ('serviceWorker' in navigator) {
                        navigator.serviceWorker.ready.then(function(reg) {
                            reg.showNotification(event.title, notifOptions);
                        }).catch(function() {
                            fallbackNativeNotification(event.title, notifOptions, event.url);
                        });
                    } else {
                        fallbackNativeNotification(event.title, notifOptions, event.url);
                    }
                } catch(err) {
                    console.error('Notification error:', err);
                }
            }

            // 2. In-Browser Interactive Toast
            if (window.Swal) {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: event.type === 'newsletter' ? 'success' : (event.type === 'chat' ? 'info' : 'warning'),
                    title: event.title,
                    text: event.body,
                    showConfirmButton: true,
                    confirmButtonText: '<i class="fa-solid fa-arrow-up-right-from-square me-1"></i> Open Page',
                    confirmButtonColor: '#0E63FF',
                    showCancelButton: true,
                    cancelButtonText: 'Dismiss',
                    timer: 8000,
                    timerProgressBar: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = event.url;
                    }
                });
            }
        }

        function fallbackNativeNotification(title, options, targetUrl) {
            try {
                const notif = new Notification(title, options);
                notif.onclick = function(e) {
                    e.preventDefault();
                    window.focus();
                    window.location.href = targetUrl;
                    notif.close();
                };
            } catch(e) {}
        }

        // Initialize permission UI on load
        updateNotifPermissionUI();

        // 3. Realtime Polling Loop (Every 5 seconds)
        setInterval(function() {
            fetch('{{ route("admin.notifications.check") }}?last_check=' + lastNotificationCheck)
                .then(res => res.json())
                .then(data => {
                    if (!data || !data.success) return;

                    // Update timestamp pointer
                    lastNotificationCheck = data.server_time || Math.floor(Date.now() / 1000);

                    // Update Badge Counts in Real-Time
                    if (data.badges) {
                        // Inquiries
                        const inqBadge = $('#inquiriesUnreadBadge');
                        if (data.badges.inquiries > 0) {
                            inqBadge.text(data.badges.inquiries).removeClass('d-none');
                        } else {
                            inqBadge.addClass('d-none');
                        }

                        // Comments
                        const comBadge = $('#commentsBadge');
                        if (data.badges.comments > 0) {
                            comBadge.text(data.badges.comments).removeClass('d-none');
                        } else {
                            comBadge.addClass('d-none');
                        }

                        // Live Chats
                        const chatBadge = $('#chatBadge');
                        if (data.badges.chats > 0) {
                            chatBadge.text(data.badges.chats).removeClass('d-none');
                        } else {
                            chatBadge.addClass('d-none');
                        }
                    }

                    // Trigger alert for each new event
                    if (data.events && data.events.length > 0) {
                        data.events.forEach(function(evt) {
                            showDesktopNotification(evt);
                        });
                    }
                })
                .catch(() => {});
        }, 5000);
    </script>
    <script src="{{ asset('assets/js/fix-webm-duration.js') }}"></script>
    @stack('scripts')
</body>
</html>
