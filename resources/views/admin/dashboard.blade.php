@extends('admin.layouts.master')

@section('title', 'Analytics & Executive Dashboard')
@section('header_title', 'Analytics & Executive Dashboard')

@section('content')

    <!-- 1. TOP STATS ROW: Traffic & Visitors -->
    <div class="row g-3 mb-4">
        <!-- Daily Unique Visitors -->
        <div class="col-xl-3 col-md-6">
            <div class="admin-card p-3 mb-0 h-100 shadow-sm border-0 position-relative overflow-hidden" style="background: linear-gradient(135deg, #FFFFFF 0%, #F8FAFC 100%); border-left: 4px solid #0E63FF !important;">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="text-uppercase text-muted fw-bold" style="font-size: 11px; letter-spacing: 0.5px;">Daily Unique Visitors</span>
                        <h2 class="my-1 font-weight-bold text-dark" style="font-size: 28px;">{{ number_format($stats['today_unique_visitors']) }}</h2>
                        <div class="d-flex align-items-center gap-1 mt-2">
                            @if($stats['visitor_growth_percent'] >= 0)
                                <span class="badge bg-success-subtle text-success border border-success-subtle font-weight-bold" style="font-size: 11px;">
                                    <i class="fa-solid fa-arrow-trend-up me-1"></i> +{{ $stats['visitor_growth_percent'] }}%
                                </span>
                            @else
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle font-weight-bold" style="font-size: 11px;">
                                    <i class="fa-solid fa-arrow-trend-down me-1"></i> {{ $stats['visitor_growth_percent'] }}%
                                </span>
                            @endif
                            <small class="text-muted" style="font-size: 11px;">vs yesterday ({{ $stats['yesterday_unique_visitors'] }})</small>
                        </div>
                    </div>
                    <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: rgba(14, 99, 255, 0.1); color: #0E63FF; font-size: 22px;">
                        <i class="fa-solid fa-users"></i>
                    </div>
                </div>
                <div class="mt-2 pt-2 border-top text-muted" style="font-size: 11px;">
                    <i class="fa-solid fa-fingerprint me-1 text-primary"></i> Counted 1 time per user/IP daily
                </div>
            </div>
        </div>

        <!-- Total Page Clicks & Views -->
        <div class="col-xl-3 col-md-6">
            <div class="admin-card p-3 mb-0 h-100 shadow-sm border-0 position-relative overflow-hidden" style="background: linear-gradient(135deg, #FFFFFF 0%, #F8FAFC 100%); border-left: 4px solid #8B5CF6 !important;">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="text-uppercase text-muted fw-bold" style="font-size: 11px; letter-spacing: 0.5px;">Today Page Views</span>
                        <h2 class="my-1 font-weight-bold text-dark" style="font-size: 28px;">{{ number_format($stats['today_page_views']) }}</h2>
                        <div class="d-flex align-items-center gap-1 mt-2">
                            <span class="badge bg-purple-subtle text-purple border border-purple-subtle font-weight-bold" style="font-size: 11px; background: #F3E8FF; color: #7C3AED;">
                                <i class="fa-solid fa-chart-line me-1"></i> {{ number_format($stats['total_page_views']) }} Total
                            </span>
                            <small class="text-muted" style="font-size: 11px;">all-time clicks</small>
                        </div>
                    </div>
                    <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: rgba(139, 92, 246, 0.1); color: #8B5CF6; font-size: 22px;">
                        <i class="fa-solid fa-computer-mouse"></i>
                    </div>
                </div>
                <div class="mt-2 pt-2 border-top text-muted" style="font-size: 11px;">
                    <i class="fa-solid fa-arrow-pointer me-1 text-purple" style="color: #8B5CF6;"></i> Total page requests tracked
                </div>
            </div>
        </div>

        <!-- Average Time Spent (Dwell Time) -->
        <div class="col-xl-3 col-md-6">
            <div class="admin-card p-3 mb-0 h-100 shadow-sm border-0 position-relative overflow-hidden" style="background: linear-gradient(135deg, #FFFFFF 0%, #F8FAFC 100%); border-left: 4px solid #06B6D4 !important;">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="text-uppercase text-muted fw-bold" style="font-size: 11px; letter-spacing: 0.5px;">Avg. Page Dwell Time</span>
                        <h2 class="my-1 font-weight-bold text-dark" style="font-size: 28px;">{{ $stats['avg_dwell_time'] }}</h2>
                        <div class="d-flex align-items-center gap-1 mt-2">
                            <span class="badge bg-info-subtle text-info border border-info-subtle font-weight-bold" style="font-size: 11px;">
                                <i class="fa-solid fa-clock me-1"></i> High Engagement
                            </span>
                        </div>
                    </div>
                    <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: rgba(6, 182, 212, 0.1); color: #06B6D4; font-size: 22px;">
                        <i class="fa-solid fa-stopwatch"></i>
                    </div>
                </div>
                <div class="mt-2 pt-2 border-top text-muted" style="font-size: 11px;">
                    <i class="fa-solid fa-user-clock me-1 text-info"></i> How long users stayed on pages
                </div>
            </div>
        </div>

        <!-- Total Healthcare Inquiries & Quotes -->
        <div class="col-xl-3 col-md-6">
            <div class="admin-card p-3 mb-0 h-100 shadow-sm border-0 position-relative overflow-hidden" style="background: linear-gradient(135deg, #FFFFFF 0%, #F8FAFC 100%); border-left: 4px solid #DC2626 !important;">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="text-uppercase text-muted fw-bold" style="font-size: 11px; letter-spacing: 0.5px;">Contact Inquiries & Quotes</span>
                        <h2 class="my-1 font-weight-bold text-dark" style="font-size: 28px;">{{ number_format($stats['total_inquiries']) }}</h2>
                        <div class="d-flex align-items-center gap-1 mt-2">
                            @if($stats['unread_inquiries'] > 0)
                                <span class="badge bg-danger text-white font-weight-bold" style="font-size: 11px;">
                                    {{ $stats['unread_inquiries'] }} Unread
                                </span>
                            @else
                                <span class="badge bg-success-subtle text-success border border-success-subtle font-weight-bold" style="font-size: 11px;">
                                    All Read
                                </span>
                            @endif
                            <small class="text-muted" style="font-size: 11px;">hospital lead requests</small>
                        </div>
                    </div>
                    <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: rgba(220, 38, 38, 0.1); color: #DC2626; font-size: 22px;">
                        <i class="fa-solid fa-envelope-open-text"></i>
                    </div>
                </div>
                <div class="mt-2 pt-2 border-top text-muted" style="font-size: 11px;">
                    <a href="{{ route('admin.inquiries.index') }}" class="text-decoration-none font-weight-bold" style="color: #DC2626;">
                        View all inquiries <i class="fa-solid fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. LEAD CONVERSIONS ROW: Newsletters, Live Chats, Comments, Services -->
    <div class="row g-3 mb-4">
        <!-- Newsletters -->
        <div class="col-xl-3 col-md-6">
            <div class="admin-card p-3 mb-0 h-100 shadow-sm border-0 d-flex align-items-center" style="border-left: 3px solid #16A34A !important;">
                <div class="rounded-circle d-flex align-items-center justify-content-center mr-15" style="width: 50px; height: 50px; background: #DCFCE7; color: #16A34A; font-size: 20px;">
                    <i class="fa-solid fa-paper-plane"></i>
                </div>
                <div>
                    <h4 class="mb-0 font-weight-bold text-dark">{{ number_format($stats['total_newsletters']) }}</h4>
                    <span class="text-muted small fw-semibold">Newsletter Subscribers</span>
                    <div class="small text-success mt-1" style="font-size: 11px;">From Footer & Offcanvas</div>
                </div>
            </div>
        </div>

        <!-- Live Chats -->
        <div class="col-xl-3 col-md-6">
            <div class="admin-card p-3 mb-0 h-100 shadow-sm border-0 d-flex align-items-center" style="border-left: 3px solid #0284C7 !important;">
                <div class="rounded-circle d-flex align-items-center justify-content-center mr-15" style="width: 50px; height: 50px; background: #E0F2FE; color: #0284C7; font-size: 20px;">
                    <i class="fa-solid fa-comments"></i>
                </div>
                <div>
                    <h4 class="mb-0 font-weight-bold text-dark">{{ number_format($stats['total_live_chats']) }}</h4>
                    <span class="text-muted small fw-semibold">Live Support Chats</span>
                    <div class="small mt-1" style="font-size: 11px; color: #0284C7;">
                        {{ $stats['active_live_chats'] }} Active Sessions
                    </div>
                </div>
            </div>
        </div>

        <!-- Blog Comments -->
        <div class="col-xl-3 col-md-6">
            <div class="admin-card p-3 mb-0 h-100 shadow-sm border-0 d-flex align-items-center" style="border-left: 3px solid #F59E0B !important;">
                <div class="rounded-circle d-flex align-items-center justify-content-center mr-15" style="width: 50px; height: 50px; background: #FEF3C7; color: #D97706; font-size: 20px;">
                    <i class="fa-solid fa-comment-dots"></i>
                </div>
                <div>
                    <h4 class="mb-0 font-weight-bold text-dark">{{ number_format($stats['total_comments']) }}</h4>
                    <span class="text-muted small fw-semibold">Blog Article Comments</span>
                    <div class="small text-warning mt-1" style="font-size: 11px;">
                        {{ $stats['pending_comments'] }} Moderation Pending
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Services & Devices -->
        <div class="col-xl-3 col-md-6">
            <div class="admin-card p-3 mb-0 h-100 shadow-sm border-0 d-flex align-items-center" style="border-left: 3px solid #0E63FF !important;">
                <div class="rounded-circle d-flex align-items-center justify-content-center mr-15" style="width: 50px; height: 50px; background: #EBF2FE; color: #0E63FF; font-size: 20px;">
                    <i class="fa-solid fa-stethoscope"></i>
                </div>
                <div>
                    <h4 class="mb-0 font-weight-bold text-dark">{{ $stats['total_services'] }}</h4>
                    <span class="text-muted small fw-semibold">Medical Equipment Catalog</span>
                    <div class="small text-primary mt-1" style="font-size: 11px;">
                        <a href="{{ route('admin.home_sections.index') }}" class="text-decoration-none fw-semibold">Home Page Manager <i class="fa-solid fa-arrow-right ms-1" style="font-size: 10px;"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. INTERACTIVE CHARTS ROW: Traffic Trend & Device Breakdown -->
    <div class="row g-3 mb-4">
        <!-- 14-Day Traffic Spline Chart -->
        <div class="col-xl-8">
            <div class="admin-card h-100 mb-0 shadow-sm border-0">
                <div class="admin-card-header d-flex flex-wrap justify-content-between align-items-center py-3">
                    <div>
                        <h5 class="mb-0 fw-bold text-dark">
                            <i class="fa-solid fa-chart-area text-primary me-2"></i> Visitor Traffic & Pageviews Trend
                        </h5>
                        <small class="text-muted">Daily unique visitors (1 count per user/day) vs total page views over past 14 days</small>
                    </div>
                    <div class="d-flex align-items-center gap-3 mt-2 mt-sm-0">
                        <span class="badge d-inline-flex align-items-center gap-1 px-2 py-1" style="background: rgba(14, 99, 255, 0.1); color: #0E63FF;">
                            <span style="display:inline-block; width:8px; height:8px; border-radius:50%; background:#0E63FF;"></span> Unique Visitors
                        </span>
                        <span class="badge d-inline-flex align-items-center gap-1 px-2 py-1" style="background: rgba(139, 92, 246, 0.1); color: #8B5CF6;">
                            <span style="display:inline-block; width:8px; height:8px; border-radius:50%; background:#8B5CF6;"></span> Page Views
                        </span>
                    </div>
                </div>
                <div class="card-body p-3">
                    <div style="height: 320px; position: relative;">
                        <canvas id="trafficTrendChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Device & Platform Breakdown Doughnut Chart -->
        <div class="col-xl-4">
            <div class="admin-card h-100 mb-0 shadow-sm border-0">
                <div class="admin-card-header py-3">
                    <h5 class="mb-0 fw-bold text-dark">
                        <i class="fa-solid fa-mobile-screen-button text-success me-2"></i> Visitors By Device
                    </h5>
                    <small class="text-muted">Desktop vs Mobile vs Tablet</small>
                </div>
                <div class="card-body p-3 d-flex flex-column justify-content-center">
                    <div style="height: 200px; position: relative;" class="mb-3">
                        <canvas id="deviceChart"></canvas>
                    </div>
                    <!-- Device Legend List -->
                    <div class="mt-2 pt-2 border-top">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="small fw-semibold d-flex align-items-center">
                                <i class="fa-solid fa-desktop me-2 text-primary"></i> Desktop
                            </span>
                            <span class="badge bg-light text-dark border fw-bold">{{ $deviceStats['desktop_pct'] }}% ({{ number_format($deviceStats['desktop']) }})</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="small fw-semibold d-flex align-items-center">
                                <i class="fa-solid fa-mobile-screen me-2 text-success"></i> Mobile
                            </span>
                            <span class="badge bg-light text-dark border fw-bold">{{ $deviceStats['mobile_pct'] }}% ({{ number_format($deviceStats['mobile']) }})</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="small fw-semibold d-flex align-items-center">
                                <i class="fa-solid fa-tablet-screen-button me-2 text-warning"></i> Tablet
                            </span>
                            <span class="badge bg-light text-dark border fw-bold">{{ $deviceStats['tablet_pct'] }}% ({{ number_format($deviceStats['tablet']) }})</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 4. TOP PAGES LEADERBOARD & RECENT LEADS -->
    <div class="row g-3">
        <!-- Top Visited Pages Leaderboard -->
        <div class="col-xl-7">
            <div class="admin-card h-100 mb-0 shadow-sm border-0">
                <div class="admin-card-header d-flex justify-content-between align-items-center py-3">
                    <div>
                        <h5 class="mb-0 fw-bold text-dark">
                            <i class="fa-solid fa-fire text-danger me-2"></i> Most Visited & Clicked Pages
                        </h5>
                        <small class="text-muted">Where users go most & how long they stay (Dwell Time)</small>
                    </div>
                    <span class="badge bg-light text-muted border px-2 py-1">Top {{ count($topPages) }} Pages</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover table-custom align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th style="width: 45%;">Page Route / Content</th>
                                <th style="width: 25%;">Clicks & Traffic</th>
                                <th style="width: 15%; text-align: center;">Unique</th>
                                <th style="width: 15%; text-align: right;">Avg Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topPages as $page)
                                @php
                                    $pct = round(($page->total_views / $maxViews) * 100);
                                @endphp
                                <tr>
                                    <td>
                                        <div class="fw-bold text-dark text-truncate" style="max-width: 280px;" title="{{ $page->page_title ?: $page->page_url }}">
                                            {{ $page->page_title ?: $page->page_url }}
                                        </div>
                                        <a href="{{ url($page->page_url) }}" target="_blank" class="small text-muted text-decoration-none font-monospace" style="font-size: 11px;">
                                            {{ $page->page_url }} <i class="fa-solid fa-arrow-up-right-from-square" style="font-size: 9px;"></i>
                                        </a>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-between align-items-center small mb-1">
                                            <span class="fw-bold text-primary">{{ number_format($page->total_views) }} views</span>
                                            <span class="text-muted" style="font-size: 10px;">{{ $pct }}%</span>
                                        </div>
                                        <div class="progress" style="height: 5px; background: #F1F5F9;">
                                            <div class="progress-bar" role="progressbar" style="width: {{ $pct }}%; background: linear-gradient(90deg, #0E63FF, #8B5CF6); border-radius: 4px;"></div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-secondary-subtle text-secondary fw-semibold">
                                            {{ number_format($page->unique_visitors) }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <span class="badge bg-info-subtle text-info border border-info-subtle fw-bold" style="font-size: 11px;">
                                            <i class="fa-solid fa-clock me-1"></i> {{ $page->formatted_avg_duration }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">
                                        <i class="fa-solid fa-chart-simple fs-4 mb-2 d-block"></i>
                                        No page analytics tracked yet. Realtime tracking is active!
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Recent Leads & Direct Messages -->
        <div class="col-xl-5">
            <div class="admin-card h-100 mb-0 shadow-sm border-0">
                <div class="admin-card-header d-flex justify-content-between align-items-center py-3">
                    <h5 class="mb-0 fw-bold text-dark">
                        <i class="fa-solid fa-inbox text-primary me-2"></i> Recent Inquiries & Quotes
                    </h5>
                    <a href="{{ route('admin.inquiries.index') }}" class="small text-decoration-none font-weight-bold text-primary">View All ({{ $stats['total_inquiries'] }})</a>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($recentInquiries as $inquiry)
                            <div class="list-group-item p-3 border-bottom">
                                <div class="d-flex justify-content-between align-items-start mb-1">
                                    <h6 class="mb-0 fw-bold text-dark" style="font-size: 13.5px;">
                                        {{ $inquiry->name }}
                                    </h6>
                                    @if($inquiry->status === 'unread')
                                        <span class="badge bg-danger text-white" style="font-size: 10px;">Unread</span>
                                    @else
                                        <span class="badge bg-light text-muted border" style="font-size: 10px;">{{ ucfirst($inquiry->status) }}</span>
                                    @endif
                                </div>
                                <div class="text-muted small text-truncate mb-2" style="font-size: 12px;">
                                    {{ $inquiry->message }}
                                </div>
                                <div class="d-flex justify-content-between align-items-center text-muted" style="font-size: 11px;">
                                    <span><i class="fa-solid fa-envelope me-1"></i> {{ $inquiry->email }}</span>
                                    <span>{{ $inquiry->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4 text-muted">
                                <i class="fa-solid fa-envelope-circle-check fs-4 mb-2 d-block"></i>
                                No recent inquiries.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<!-- Chart.js 4.4.1 -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
$(document).ready(function() {
    // 1. Traffic Trend Area Spline Chart
    const trendCtx = document.getElementById('trafficTrendChart').getContext('2d');
    
    // Gradients
    const gradientBlue = trendCtx.createLinearGradient(0, 0, 0, 300);
    gradientBlue.addColorStop(0, 'rgba(14, 99, 255, 0.28)');
    gradientBlue.addColorStop(1, 'rgba(14, 99, 255, 0.00)');

    const gradientPurple = trendCtx.createLinearGradient(0, 0, 0, 300);
    gradientPurple.addColorStop(0, 'rgba(139, 92, 246, 0.22)');
    gradientPurple.addColorStop(1, 'rgba(139, 92, 246, 0.00)');

    const trafficChart = new Chart(trendCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($trafficChart['labels']) !!},
            datasets: [
                {
                    label: 'Unique Visitors (Daily)',
                    data: {!! json_encode($trafficChart['unique_visitors']) !!},
                    borderColor: '#0E63FF',
                    backgroundColor: gradientBlue,
                    borderWidth: 2.5,
                    fill: true,
                    tension: 0.35,
                    pointBackgroundColor: '#0E63FF',
                    pointBorderColor: '#FFFFFF',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                },
                {
                    label: 'Page Views',
                    data: {!! json_encode($trafficChart['page_views']) !!},
                    borderColor: '#8B5CF6',
                    backgroundColor: gradientPurple,
                    borderWidth: 2,
                    borderDash: [4, 4],
                    fill: true,
                    tension: 0.35,
                    pointBackgroundColor: '#8B5CF6',
                    pointBorderColor: '#FFFFFF',
                    pointBorderWidth: 2,
                    pointRadius: 3,
                    pointHoverRadius: 5,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            plugins: {
                legend: {
                    display: false,
                },
                tooltip: {
                    backgroundColor: '#0F172A',
                    titleColor: '#FFFFFF',
                    bodyColor: '#F8FAFC',
                    padding: 12,
                    cornerRadius: 8,
                    boxPadding: 4,
                    usePointStyle: true,
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false,
                    },
                    ticks: {
                        color: '#64748B',
                        font: {
                            size: 11,
                            family: 'Inter, sans-serif'
                        }
                    }
                },
                y: {
                    beginAtZero: true,
                    grid: {
                        color: '#F1F5F9',
                        drawBorder: false,
                    },
                    ticks: {
                        color: '#64748B',
                        font: {
                            size: 11,
                        },
                        precision: 0
                    }
                }
            }
        }
    });

    // 2. Device Breakdown Doughnut Chart
    const deviceCtx = document.getElementById('deviceChart').getContext('2d');
    const deviceChart = new Chart(deviceCtx, {
        type: 'doughnut',
        data: {
            labels: ['Desktop', 'Mobile', 'Tablet'],
            datasets: [{
                data: [
                    {{ $deviceStats['desktop'] }},
                    {{ $deviceStats['mobile'] }},
                    {{ $deviceStats['tablet'] }}
                ],
                backgroundColor: ['#0E63FF', '#10B981', '#F59E0B'],
                borderWidth: 3,
                borderColor: '#FFFFFF',
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '72%',
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: '#0F172A',
                    padding: 10,
                    cornerRadius: 6,
                }
            }
        }
    });
});
</script>
@endpush
