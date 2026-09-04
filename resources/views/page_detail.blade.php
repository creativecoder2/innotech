@extends('layouts.app')

@section('title', $page->title . ' - ' . \App\Models\Setting::get('site_title', 'INNOTECH MEDICAL PVT LTD'))

@push('styles')
<style>
    .page-content-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 50px 45px;
        box-shadow: 0 10px 30px rgba(23, 17, 81, 0.05);
        border: 1px solid #ECEEF3;
    }
    .page-content-card h2, .page-content-card h3 {
        color: #171151;
        font-weight: 700;
        margin-top: 30px;
        margin-bottom: 15px;
    }
    .page-content-card h2:first-child, .page-content-card h3:first-child {
        margin-top: 0;
    }
    .page-content-card p {
        color: #64748B;
        font-size: 16px;
        line-height: 1.8;
        margin-bottom: 20px;
    }
    .page-content-card ul, .page-content-card ol {
        color: #64748B;
        font-size: 15px;
        line-height: 1.8;
        padding-left: 20px;
        margin-bottom: 25px;
    }
    .page-sidebar-widget {
        background: #ffffff;
        border-radius: 14px;
        padding: 30px;
        border: 1px solid #ECEEF3;
        box-shadow: 0 8px 24px rgba(23, 17, 81, 0.04);
        margin-bottom: 30px;
    }
    .page-sidebar-widget h5 {
        font-size: 18px;
        font-weight: 700;
        color: #171151;
        margin-bottom: 20px;
        padding-bottom: 12px;
        border-bottom: 2px solid #F1F5F9;
    }
    .page-nav-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .page-nav-list li {
        margin-bottom: 10px;
    }
    .page-nav-list li a {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 16px;
        border-radius: 8px;
        color: #1E293B;
        font-weight: 500;
        text-decoration: none;
        background: #F8FAFC;
        transition: all 0.25s ease;
    }
    .page-nav-list li a:hover, .page-nav-list li a.active {
        background: #0E63FF;
        color: #ffffff;
        transform: translateX(4px);
    }
</style>
@endpush

@section('content')

@php
    $bannerImage = 'assets/img/banner/breadcrumb-01.jpg';
    $bannerTitle = $page->title;
    $bannerSubtitle = $page->subtitle;

    if ($page->slug === 'terms-and-conditions' || $page->slug === 'terms') {
        $bannerImage = \App\Models\Setting::get('terms_banner_image', \App\Models\Setting::get('pages_banner_image', 'assets/img/banner/breadcrumb-01.jpg'));
        $bannerTitle = \App\Models\Setting::get('terms_banner_title', $page->title);
        $bannerSubtitle = \App\Models\Setting::get('terms_banner_subtitle', $page->subtitle ?: 'Legal Agreement & Terms of Service');
    } elseif ($page->slug === 'privacy-policy' || $page->slug === 'privacy') {
        $bannerImage = \App\Models\Setting::get('privacy_banner_image', \App\Models\Setting::get('pages_banner_image', 'assets/img/banner/breadcrumb-01.jpg'));
        $bannerTitle = \App\Models\Setting::get('privacy_banner_title', $page->title);
        $bannerSubtitle = \App\Models\Setting::get('privacy_banner_subtitle', $page->subtitle ?: 'Data Protection & Privacy Policy');
    } else {
        $bannerImage = \App\Models\Setting::get('pages_banner_image', 'assets/img/banner/breadcrumb-01.jpg');
    }
@endphp

<main>
    <!-- Breadcrumb Area -->
    <section class="breadcrumb__area pt-100 pb-120 breadcrumb__overlay" data-background="{{ asset($bannerImage) }}">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-xl-7 col-lg-7 col-md-12 col-12">
                    <div class="tp-breadcrumb">
                        <h2 class="tp-breadcrumb__title">{{ $bannerTitle }}</h2>
                        @if($bannerSubtitle)
                            <p class="text-white opacity-75 mt-2 mb-0 fs-6">{{ $bannerSubtitle }}</p>
                        @endif
                    </div>
                </div>
                <div class="col-xl-5 col-lg-5 col-md-12 col-12">
                    <div class="tp-breadcrumb__link serv-md d-flex justify-content-lg-end">
                        <span><a href="{{ url('/') }}">Home</a> / <span>{{ $bannerTitle }}</span></span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Page Content Area -->
    <section class="page-detail-area pt-100 pb-100 grey-bg">
        <div class="container">
            <div class="row">
                <!-- Main Content Body -->
                <div class="col-lg-8 col-md-12 mb-40">
                    <div class="page-content-card">
                        @if(!$page->is_published)
                            <div class="alert alert-warning mb-4">
                                <i class="fa-solid fa-triangle-exclamation me-2"></i> <strong>Draft Mode:</strong> This page is currently unpublished and visible only to administrators.
                            </div>
                        @endif

                        <div class="page-body-content">
                            {!! $page->content !!}
                        </div>

                        <div class="mt-5 pt-4 border-top d-flex flex-wrap justify-content-between align-items-center text-muted small">
                            <span><i class="fa-regular fa-clock me-1"></i> Last updated: {{ $page->updated_at->format('M d, Y') }}</span>
                            <span>INNOTECH MEDICAL PVT LTD</span>
                        </div>
                    </div>
                </div>

                <!-- Sidebar Area -->
                <div class="col-lg-4 col-md-12">
                    @if(isset($allFooterPages) && $allFooterPages->count() > 0)
                        <div class="page-sidebar-widget">
                            <h5><i class="fa-solid fa-file-lines text-primary me-2"></i> Institutional & Legal</h5>
                            <ul class="page-nav-list">
                                @foreach($allFooterPages as $fPage)
                                    <li>
                                        <a href="{{ route('page.show', $fPage->slug) }}" class="{{ $fPage->id === $page->id ? 'active' : '' }}">
                                            <span>{{ $fPage->title }}</span>
                                            <i class="fa-solid fa-chevron-right fs-6"></i>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Need Assistance Widget -->
                    <div class="page-sidebar-widget text-center bg-primary text-white" style="background: linear-gradient(135deg, #0E63FF 0%, #002244 100%);">
                        <div class="mb-3">
                            <i class="fa-solid fa-headset fs-1 text-white opacity-75"></i>
                        </div>
                        <h5 class="text-white border-0 pb-0">Need Institutional Support?</h5>
                        <p class="text-white opacity-75 small mb-4">Our biomedical engineering and compliance desk is available to assist you with equipment inquiries and contracts.</p>
                        <a href="{{ url('/contact') }}" class="btn btn-light text-primary fw-bold px-4 py-2 rounded-pill">
                            Contact Help Desk
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

@endsection
