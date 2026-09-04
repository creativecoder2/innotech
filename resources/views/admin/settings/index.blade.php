@extends('admin.layouts.master')

@section('title', 'Website Settings')
@section('header_title', 'Website Settings & Configuration')

@push('styles')
<style>
    /* Sleek Custom Settings Nav Pills */
    .settings-nav-pills {
        border-bottom: 1px solid #E2E8F0;
        padding-bottom: 12px;
        gap: 8px;
        display: flex;
        flex-wrap: wrap;
    }
    .settings-nav-pills .nav-link {
        color: #475569;
        font-weight: 600;
        font-size: 13.5px;
        padding: 8px 16px;
        border-radius: 8px;
        border: 1px solid #E2E8F0;
        background-color: #F8FAFC;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
    }
    .settings-nav-pills .nav-link:hover {
        background-color: #EFF6FF;
        color: #0E63FF;
        border-color: #BFDBFE;
    }
    .settings-nav-pills .nav-link.active {
        background-color: #0E63FF !important;
        color: #FFFFFF !important;
        border-color: #0E63FF !important;
        box-shadow: 0 4px 10px rgba(14, 99, 255, 0.2);
    }
    
    /* Upload Preview Box */
    .upload-preview-box {
        background: #F8FAFC;
        border: 1px solid #E2E8F0;
        border-radius: 10px;
        padding: 14px;
        display: flex;
        align-items: center;
        gap: 16px;
    }
    .upload-thumb {
        background: #FFFFFF;
        border: 1px solid #CBD5E1;
        border-radius: 8px;
        padding: 6px 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 80px;
    }

    /* Inner Page Banner Card */
    .banner-setting-card {
        background: #F8FAFC;
        border: 1px solid #E2E8F0;
        border-radius: 10px;
        padding: 18px;
        margin-bottom: 18px;
        transition: all 0.2s ease;
    }
    .banner-setting-card:hover {
        border-color: #CBD5E1;
        box-shadow: 0 2px 8px rgba(0,0,0,0.03);
    }

    /* Counter Setting Box */
    .counter-setting-box {
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-radius: 8px;
        padding: 14px;
    }
</style>
@endpush

@section('content')

    <div class="admin-card">
        <div class="admin-card-header d-flex justify-content-between align-items-center bg-light">
            <h6 class="fw-bold mb-0">
                <i class="fa-solid fa-sliders text-primary me-2"></i> Dynamic Configuration & Contact Details
            </h6>
            <span class="text-muted small">Update branding, banners, contact details, social links, and footer.</span>
        </div>
        <div class="admin-card-body p-4">
            <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" id="settingsForm">
                @csrf

                <!-- Sleek Settings Tabs Navigation -->
                <ul class="nav settings-nav-pills mb-4" id="settingsTabs" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active" id="general-tab" data-bs-toggle="pill" data-bs-target="#general" type="button" role="tab">
                            <i class="fa-solid fa-gear me-2"></i> General & Branding
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" id="inner-banners-tab" data-bs-toggle="pill" data-bs-target="#inner-banners" type="button" role="tab">
                            <i class="fa-solid fa-panorama me-2"></i> Inner Pages Banners
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" id="contact-tab" data-bs-toggle="pill" data-bs-target="#contact" type="button" role="tab">
                            <i class="fa-solid fa-headset me-2"></i> Helpdesk & Contact
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" id="social-tab" data-bs-toggle="pill" data-bs-target="#social" type="button" role="tab">
                            <i class="fa-solid fa-share-nodes me-2"></i> Social Media
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" id="stats-tab" data-bs-toggle="pill" data-bs-target="#stats" type="button" role="tab">
                            <i class="fa-solid fa-chart-pie me-2"></i> About & Stats Counters
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" id="footer-tab" data-bs-toggle="pill" data-bs-target="#footer" type="button" role="tab">
                            <i class="fa-solid fa-shoe-prints me-2"></i> Footer Info
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" id="services-why-tab" data-bs-toggle="pill" data-bs-target="#services-why" type="button" role="tab">
                            <i class="fa-solid fa-square-check me-2"></i> Services: Why Choose Us
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" id="seo-tab" data-bs-toggle="pill" data-bs-target="#seo" type="button" role="tab" style="border: 1px solid #F59E0B;">
                            <i class="fa-solid fa-crown text-warning me-2"></i> SEO & Search Ranking (VIP)
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" id="email-tab" data-bs-toggle="pill" data-bs-target="#email" type="button" role="tab" style="border: 1px solid #10B981;">
                            <i class="fa-solid fa-envelope-circle-check text-success me-2"></i> Email & SMTP Diagnostics
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="settingsTabContent">
                    <!-- Tab 1: General & Branding -->
                    <div class="tab-pane fade show active" id="general" role="tabpanel">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Website Title / Company Name <span class="text-danger">*</span></label>
                                <input type="text" name="site_title" class="form-control" value="{{ $settings['site_title']->value ?? 'INNOTECH MEDICAL PVT LTD' }}" required>
                                <small class="text-muted">Displayed in browser title bar, header, and search engines.</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Site Tagline / Slogan</label>
                                <input type="text" name="site_tagline" class="form-control" value="{{ $settings['site_tagline']->value ?? '' }}" placeholder="Innovating Health Care with Advance Technologies">
                                <small class="text-muted">Primary slogan displayed under the logo in header drawer.</small>
                            </div>

                            <!-- Primary Logo Card -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Primary Website Logo</label>
                                <div class="upload-preview-box">
                                    <div class="upload-thumb">
                                        <img src="{{ asset($settings['logo_path']->value ?? 'assets/img/logo/logo.png') }}" alt="Logo" style="max-height: 44px; max-width: 140px; object-fit: contain;">
                                    </div>
                                    <div class="flex-grow-1">
                                        <input type="file" name="logo_path" class="form-control form-control-sm mb-1" accept="image/*,.jfif,.png,.jpg,.jpeg,.webp,.svg,.gif,.avif,.bmp">
                                        <small class="text-muted d-block">Recommended: Transparent PNG/SVG (180x50px).</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Favicon Card -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Website Favicon</label>
                                <div class="upload-preview-box">
                                    <div class="upload-thumb" style="min-width: 54px; width: 54px; height: 54px;">
                                        <img src="{{ asset($settings['favicon_path']->value ?? 'assets/img/logo/favicon.png') }}" alt="Favicon" style="max-height: 32px; max-width: 32px; object-fit: contain;">
                                    </div>
                                    <div class="flex-grow-1">
                                        <input type="file" name="favicon_path" class="form-control form-control-sm mb-1" accept="image/*,.jfif,.png,.jpg,.jpeg,.webp,.svg,.gif,.avif,.bmp,.ico">
                                        <small class="text-muted d-block">Square icon: 32x32px or 64x64px (.png, .ico).</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tab 2: Inner Pages Banners -->
                    <div class="tab-pane fade" id="inner-banners" role="tabpanel">
                        <div class="alert alert-info py-2 px-3 mb-4 rounded-3 d-flex align-items-center">
                            <i class="fa-solid fa-circle-info text-info fs-5 me-2"></i>
                            <small class="text-dark">Manage custom top breadcrumb banner backgrounds, titles, and subtitles for all inner pages across the website.</small>
                        </div>

                        <!-- 1. About Us Page Banner -->
                        <div class="banner-setting-card">
                            <h6 class="fw-bold text-primary mb-3"><i class="fa-solid fa-address-card me-2"></i> 1. About Us Page Banner</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Page Heading / Title</label>
                                    <input type="text" name="about_banner_title" class="form-control" value="{{ $settings['about_banner_title']->value ?? 'About us' }}" placeholder="About us">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Breadcrumb Subtitle</label>
                                    <input type="text" name="about_banner_subtitle" class="form-control" value="{{ $settings['about_banner_subtitle']->value ?? 'Innotech : About Us' }}" placeholder="Innotech : About Us">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Banner Background Image</label>
                                    <div class="upload-preview-box">
                                        @if(isset($settings['about_banner_image']->value))
                                            <div class="upload-thumb" style="min-width: 120px;">
                                                <img src="{{ asset($settings['about_banner_image']->value) }}" alt="About Banner" style="height: 50px; width: 110px; object-fit: cover;" class="rounded">
                                            </div>
                                        @endif
                                        <div class="flex-grow-1">
                                            <input type="file" name="about_banner_image" class="form-control form-control-sm mb-1" accept="image/*">
                                            <small class="text-muted">Recommended resolution: 1920x350px. (Leave blank to keep current).</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 2. Services & Solutions Main Page Banner -->
                        <div class="banner-setting-card">
                            <h6 class="fw-bold text-primary mb-3"><i class="fa-solid fa-stethoscope me-2"></i> 2. Services & Solutions Main Page Banner</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Page Heading / Title</label>
                                    <input type="text" name="services_banner_title" class="form-control" value="{{ $settings['services_banner_title']->value ?? 'Services & Solutions' }}" placeholder="Services & Solutions">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Breadcrumb Subtitle</label>
                                    <input type="text" name="services_banner_subtitle" class="form-control" value="{{ $settings['services_banner_subtitle']->value ?? 'Innotech : Services' }}" placeholder="Innotech : Services">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Banner Background Image</label>
                                    <div class="upload-preview-box">
                                        @if(isset($settings['services_banner_image']->value))
                                            <div class="upload-thumb" style="min-width: 120px;">
                                                <img src="{{ asset($settings['services_banner_image']->value) }}" alt="Services Banner" style="height: 50px; width: 110px; object-fit: cover;" class="rounded">
                                            </div>
                                        @endif
                                        <div class="flex-grow-1">
                                            <input type="file" name="services_banner_image" class="form-control form-control-sm mb-1" accept="image/*">
                                            <small class="text-muted">Recommended resolution: 1920x350px. (Leave blank to keep current).</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 3. Service Detail Page Banner -->
                        <div class="banner-setting-card">
                            <h6 class="fw-bold text-primary mb-3"><i class="fa-solid fa-briefcase-medical me-2"></i> 3. Service Detail Page Default Banner</h6>
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label">Service Detail Banner Background Image</label>
                                    <div class="upload-preview-box">
                                        @if(isset($settings['service_detail_banner_image']->value))
                                            <div class="upload-thumb" style="min-width: 120px;">
                                                <img src="{{ asset($settings['service_detail_banner_image']->value) }}" alt="Service Detail Banner" style="height: 50px; width: 110px; object-fit: cover;" class="rounded">
                                            </div>
                                        @endif
                                        <div class="flex-grow-1">
                                            <input type="file" name="service_detail_banner_image" class="form-control form-control-sm mb-1" accept="image/*">
                                            <small class="text-muted">Default banner applied across all service detail pages (1920x350px). Note: Individual services can also have custom banners from Services Manager.</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 4. Medical Products & Equipment Catalog Banner -->
                        <div class="banner-setting-card">
                            <h6 class="fw-bold text-primary mb-3"><i class="fa-solid fa-laptop-medical me-2"></i> 4. Medical Products Catalog Banner</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Page Heading / Title</label>
                                    <input type="text" name="products_banner_title" class="form-control" value="{{ $settings['products_banner_title']->value ?? 'Medical Products & Equipment' }}" placeholder="Medical Products & Equipment">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Breadcrumb Subtitle</label>
                                    <input type="text" name="products_banner_subtitle" class="form-control" value="{{ $settings['products_banner_subtitle']->value ?? 'Innotech : Products' }}" placeholder="Innotech : Products">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Banner Background Image</label>
                                    <div class="upload-preview-box">
                                        @if(isset($settings['products_banner_image']->value))
                                            <div class="upload-thumb" style="min-width: 120px;">
                                                <img src="{{ asset($settings['products_banner_image']->value) }}" alt="Products Banner" style="height: 50px; width: 110px; object-fit: cover;" class="rounded">
                                            </div>
                                        @endif
                                        <div class="flex-grow-1">
                                            <input type="file" name="products_banner_image" class="form-control form-control-sm mb-1" accept="image/*">
                                            <small class="text-muted">Recommended resolution: 1920x350px. (Leave blank to keep current).</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 5. Product Detail Page Banner -->
                        <div class="banner-setting-card">
                            <h6 class="fw-bold text-primary mb-3"><i class="fa-solid fa-microscope me-2"></i> 5. Product Detail Page Default Banner</h6>
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label">Product Detail Banner Background Image</label>
                                    <div class="upload-preview-box">
                                        @if(isset($settings['product_detail_banner_image']->value))
                                            <div class="upload-thumb" style="min-width: 120px;">
                                                <img src="{{ asset($settings['product_detail_banner_image']->value) }}" alt="Product Detail Banner" style="height: 50px; width: 110px; object-fit: cover;" class="rounded">
                                            </div>
                                        @endif
                                        <div class="flex-grow-1">
                                            <input type="file" name="product_detail_banner_image" class="form-control form-control-sm mb-1" accept="image/*">
                                            <small class="text-muted">Default banner applied across all individual product detail pages (1920x350px).</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 6. Work Gallery Page Banner -->
                        <div class="banner-setting-card">
                            <h6 class="fw-bold text-primary mb-3"><i class="fa-solid fa-images me-2"></i> 6. Work Gallery Page Banner</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Page Heading / Title</label>
                                    <input type="text" name="gallery_banner_title" class="form-control" value="{{ $settings['gallery_banner_title']->value ?? 'Work Gallery' }}" placeholder="Work Gallery">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Breadcrumb Subtitle</label>
                                    <input type="text" name="gallery_banner_subtitle" class="form-control" value="{{ $settings['gallery_banner_subtitle']->value ?? 'Innotech : Gallery' }}" placeholder="Innotech : Gallery">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Banner Background Image</label>
                                    <div class="upload-preview-box">
                                        @if(isset($settings['gallery_banner_image']->value))
                                            <div class="upload-thumb" style="min-width: 120px;">
                                                <img src="{{ asset($settings['gallery_banner_image']->value) }}" alt="Gallery Banner" style="height: 50px; width: 110px; object-fit: cover;" class="rounded">
                                            </div>
                                        @endif
                                        <div class="flex-grow-1">
                                            <input type="file" name="gallery_banner_image" class="form-control form-control-sm mb-1" accept="image/*">
                                            <small class="text-muted">Recommended resolution: 1920x350px. (Leave blank to keep current).</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 7. Contact Page Banner -->
                        <div class="banner-setting-card">
                            <h6 class="fw-bold text-primary mb-3"><i class="fa-solid fa-headset me-2"></i> 7. Contact Page Banner</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Page Heading / Title</label>
                                    <input type="text" name="contact_banner_title" class="form-control" value="{{ $settings['contact_banner_title']->value ?? 'Contact us' }}" placeholder="Contact us">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Breadcrumb Subtitle</label>
                                    <input type="text" name="contact_banner_subtitle" class="form-control" value="{{ $settings['contact_banner_subtitle']->value ?? 'Innotech : Contact' }}" placeholder="Innotech : Contact">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Banner Background Image</label>
                                    <div class="upload-preview-box">
                                        @if(isset($settings['contact_banner_image']->value))
                                            <div class="upload-thumb" style="min-width: 120px;">
                                                <img src="{{ asset($settings['contact_banner_image']->value) }}" alt="Contact Banner" style="height: 50px; width: 110px; object-fit: cover;" class="rounded">
                                            </div>
                                        @endif
                                        <div class="flex-grow-1">
                                            <input type="file" name="contact_banner_image" class="form-control form-control-sm mb-1" accept="image/*">
                                            <small class="text-muted">Recommended resolution: 1920x350px. (Leave blank to keep current).</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 8. Blog & Research Page Banner -->
                        <div class="banner-setting-card">
                            <h6 class="fw-bold text-primary mb-3"><i class="fa-solid fa-newspaper me-2"></i> 8. Blog & Medical Research Main Page Banner</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Page Heading / Title</label>
                                    <input type="text" name="blog_banner_title" class="form-control" value="{{ $settings['blog_banner_title']->value ?? 'Blog & Medical Research' }}" placeholder="Blog & Medical Research">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Breadcrumb Subtitle</label>
                                    <input type="text" name="blog_banner_subtitle" class="form-control" value="{{ $settings['blog_banner_subtitle']->value ?? 'Innotech : Blog Details' }}" placeholder="Innotech : Blog Details">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Banner Background Image</label>
                                    <div class="upload-preview-box">
                                        @if(isset($settings['blog_banner_image']->value))
                                            <div class="upload-thumb" style="min-width: 120px;">
                                                <img src="{{ asset($settings['blog_banner_image']->value) }}" alt="Blog Banner" style="height: 50px; width: 110px; object-fit: cover;" class="rounded">
                                            </div>
                                        @endif
                                        <div class="flex-grow-1">
                                            <input type="file" name="blog_banner_image" class="form-control form-control-sm mb-1" accept="image/*">
                                            <small class="text-muted">Recommended resolution: 1920x350px. (Leave blank to keep current).</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 9. Blog / Article Detail Page Banner -->
                        <div class="banner-setting-card">
                            <h6 class="fw-bold text-primary mb-3"><i class="fa-solid fa-newspaper me-2"></i> 9. Blog / Article Detail Page Default Banner</h6>
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label">Blog Detail Banner Background Image</label>
                                    <div class="upload-preview-box">
                                        @if(isset($settings['blog_detail_banner_image']->value))
                                            <div class="upload-thumb" style="min-width: 120px;">
                                                <img src="{{ asset($settings['blog_detail_banner_image']->value) }}" alt="Blog Detail Banner" style="height: 50px; width: 110px; object-fit: cover;" class="rounded">
                                            </div>
                                        @endif
                                        <div class="flex-grow-1">
                                            <input type="file" name="blog_detail_banner_image" class="form-control form-control-sm mb-1" accept="image/*">
                                            <small class="text-muted">Default banner applied across all individual blog & research article detail pages (1920x350px).</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 10. Team / Specialists Main Page Banner -->
                        <div class="banner-setting-card">
                            <h6 class="fw-bold text-primary mb-3"><i class="fa-solid fa-user-doctor me-2"></i> 10. Team / Specialists Main Page Banner</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Page Heading / Title</label>
                                    <input type="text" name="team_banner_title" class="form-control" value="{{ $settings['team_banner_title']->value ?? 'Our Team & Specialists' }}" placeholder="Our Team & Specialists">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Breadcrumb Subtitle</label>
                                    <input type="text" name="team_banner_subtitle" class="form-control" value="{{ $settings['team_banner_subtitle']->value ?? 'Innotech : Our Team' }}" placeholder="Innotech : Our Team">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Banner Background Image</label>
                                    <div class="upload-preview-box">
                                        @if(isset($settings['team_banner_image']->value))
                                            <div class="upload-thumb" style="min-width: 120px;">
                                                <img src="{{ asset($settings['team_banner_image']->value) }}" alt="Team Banner" style="height: 50px; width: 110px; object-fit: cover;" class="rounded">
                                            </div>
                                        @endif
                                        <div class="flex-grow-1">
                                            <input type="file" name="team_banner_image" class="form-control form-control-sm mb-1" accept="image/*">
                                            <small class="text-muted">Recommended resolution: 1920x350px. (Leave blank to keep current).</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 11. Specialist Profile Detail Page Banner -->
                        <div class="banner-setting-card">
                            <h6 class="fw-bold text-primary mb-3"><i class="fa-solid fa-id-card-clip me-2"></i> 11. Specialist Profile Detail Page Default Banner</h6>
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label class="form-label">Breadcrumb Subtitle</label>
                                    <input type="text" name="specialist_detail_banner_subtitle" class="form-control" value="{{ $settings['specialist_detail_banner_subtitle']->value ?? 'Specialist Profile' }}" placeholder="Specialist Profile">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Specialist Detail Banner Background Image</label>
                                    <div class="upload-preview-box">
                                        @if(isset($settings['specialist_detail_banner_image']->value))
                                            <div class="upload-thumb" style="min-width: 120px;">
                                                <img src="{{ asset($settings['specialist_detail_banner_image']->value) }}" alt="Specialist Detail Banner" style="height: 50px; width: 110px; object-fit: cover;" class="rounded">
                                            </div>
                                        @endif
                                        <div class="flex-grow-1">
                                            <input type="file" name="specialist_detail_banner_image" class="form-control form-control-sm mb-1" accept="image/*">
                                            <small class="text-muted">Default banner applied across all individual doctor & specialist profile pages (1920x350px).</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 12. Terms & Conditions Page Banner -->
                        <div class="banner-setting-card">
                            <h6 class="fw-bold text-primary mb-3"><i class="fa-solid fa-file-contract me-2"></i> 12. Terms & Conditions Page Banner</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Page Heading / Title</label>
                                    <input type="text" name="terms_banner_title" class="form-control" value="{{ $settings['terms_banner_title']->value ?? 'Terms & Conditions' }}" placeholder="Terms & Conditions">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Breadcrumb Subtitle</label>
                                    <input type="text" name="terms_banner_subtitle" class="form-control" value="{{ $settings['terms_banner_subtitle']->value ?? 'Innotech : Terms & Conditions' }}" placeholder="Innotech : Terms & Conditions">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Banner Background Image</label>
                                    <div class="upload-preview-box">
                                        @if(isset($settings['terms_banner_image']->value))
                                            <div class="upload-thumb" style="min-width: 120px;">
                                                <img src="{{ asset($settings['terms_banner_image']->value) }}" alt="Terms Banner" style="height: 50px; width: 110px; object-fit: cover;" class="rounded">
                                            </div>
                                        @endif
                                        <div class="flex-grow-1">
                                            <input type="file" name="terms_banner_image" class="form-control form-control-sm mb-1" accept="image/*">
                                            <small class="text-muted">Recommended resolution: 1920x350px. (Leave blank to keep current).</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 13. Privacy Policy Page Banner -->
                        <div class="banner-setting-card">
                            <h6 class="fw-bold text-primary mb-3"><i class="fa-solid fa-shield-halved me-2"></i> 13. Privacy Policy Page Banner</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Page Heading / Title</label>
                                    <input type="text" name="privacy_banner_title" class="form-control" value="{{ $settings['privacy_banner_title']->value ?? 'Privacy Policy' }}" placeholder="Privacy Policy">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Breadcrumb Subtitle</label>
                                    <input type="text" name="privacy_banner_subtitle" class="form-control" value="{{ $settings['privacy_banner_subtitle']->value ?? 'Innotech : Privacy Policy' }}" placeholder="Innotech : Privacy Policy">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Banner Background Image</label>
                                    <div class="upload-preview-box">
                                        @if(isset($settings['privacy_banner_image']->value))
                                            <div class="upload-thumb" style="min-width: 120px;">
                                                <img src="{{ asset($settings['privacy_banner_image']->value) }}" alt="Privacy Banner" style="height: 50px; width: 110px; object-fit: cover;" class="rounded">
                                            </div>
                                        @endif
                                        <div class="flex-grow-1">
                                            <input type="file" name="privacy_banner_image" class="form-control form-control-sm mb-1" accept="image/*">
                                            <small class="text-muted">Recommended resolution: 1920x350px. (Leave blank to keep current).</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 14. Other Custom Pages Default Banner -->
                        <div class="banner-setting-card">
                            <h6 class="fw-bold text-primary mb-3"><i class="fa-solid fa-file-lines me-2"></i> 14. Other Custom Policy / Info Pages Default Banner</h6>
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label">Default Banner Background Image for Custom Pages</label>
                                    <div class="upload-preview-box">
                                        @if(isset($settings['pages_banner_image']->value))
                                            <div class="upload-thumb" style="min-width: 120px;">
                                                <img src="{{ asset($settings['pages_banner_image']->value) }}" alt="Custom Pages Banner" style="height: 50px; width: 110px; object-fit: cover;" class="rounded">
                                            </div>
                                        @endif
                                        <div class="flex-grow-1">
                                            <input type="file" name="pages_banner_image" class="form-control form-control-sm mb-1" accept="image/*">
                                            <small class="text-muted">Default banner applied to all other custom pages. (Recommended resolution: 1920x350px).</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tab 3: Helpdesk & Contact -->
                    <div class="tab-pane fade" id="contact" role="tabpanel">
                        <!-- 1. General Contact & Helpdesk Details -->
                        <div class="banner-setting-card mb-4">
                            <h6 class="fw-bold text-primary mb-3">
                                <i class="fa-solid fa-phone-volume me-2"></i> 1. Contact Numbers, Emails & Working Hours
                            </h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label"><i class="fa-solid fa-phone text-success me-1"></i> Help Desk Phone (Primary & Header)</label>
                                    <input type="text" name="helpdesk_phone" class="form-control" value="{{ $settings['helpdesk_phone']->value ?? '+92 331 6699992' }}" placeholder="+92 331 6699992">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label"><i class="fa-solid fa-phone-volume text-danger me-1"></i> Emergency Hotline / Secondary Phone</label>
                                    <input type="text" name="emergency_phone" class="form-control" value="{{ $settings['emergency_phone']->value ?? '' }}" placeholder="+92 300 1234567">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label"><i class="fa-solid fa-envelope text-primary me-1"></i> Primary Support Email</label>
                                    <input type="email" name="support_email" class="form-control" value="{{ $settings['support_email']->value ?? 'info@innotechmed.com' }}" placeholder="info@innotechmed.com">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label"><i class="fa-solid fa-file-invoice text-info me-1"></i> Sales & Tenders Email</label>
                                    <input type="email" name="sales_email" class="form-control" value="{{ $settings['sales_email']->value ?? 'sales@innotechmedical.com' }}" placeholder="sales@innotechmedical.com">
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label"><i class="fa-solid fa-clock text-warning me-1"></i> Working Hours</label>
                                    <input type="text" name="working_hours" class="form-control" value="{{ $settings['working_hours']->value ?? 'Monday - Saturday: 10:00 AM - 6:00 PM' }}" placeholder="Monday - Saturday: 10:00 AM - 6:00 PM">
                                </div>
                            </div>
                        </div>

                        <!-- 2. Physical Address & Interactive Google Map -->
                        <div class="banner-setting-card mb-4">
                            <h6 class="fw-bold text-primary mb-3">
                                <i class="fa-solid fa-map-location-dot me-2"></i> 2. Head Office Address & Google Map
                            </h6>
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label"><i class="fa-solid fa-location-dot text-danger me-1"></i> Head Office Address (Displayed in Header, Footer & Contact Cards)</label>
                                    <textarea name="office_address" class="form-control" rows="2" placeholder="1st Floor, Plot: A-301, Sardar Ali Sabri Road, Block-2, Gulshan e Iqbal, Karachi, Sindh, Pakistan.">{{ $settings['office_address']->value ?? '1st Floor, Plot: A-301, Sardar Ali Sabri Road, Block-2, Gulshan e Iqbal, Karachi, Sindh, Pakistan.' }}</textarea>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">
                                        <i class="fa-solid fa-map text-primary me-1"></i> Google Maps Embed URL or Iframe Code
                                    </label>
                                    <textarea name="contact_map_iframe" class="form-control font-monospace" rows="3" placeholder="https://maps.google.com/maps?q=... or <iframe src='...'></iframe>">{{ $settings['contact_map_iframe']->value ?? '' }}</textarea>
                                    <small class="text-muted d-block mt-1">
                                        <i class="fa-solid fa-circle-info text-info me-1"></i> <strong>Automatic Fallback:</strong> If you leave this empty, the website will automatically generate and display a Google Map pointing directly to your <strong>Head Office Address</strong> above!
                                    </small>
                                </div>

                                @php
                                    $adminMapRaw = trim($settings['contact_map_iframe']->value ?? '');
                                    $adminMapSrc = '';
                                    if (!empty($adminMapRaw)) {
                                        if (preg_match('/src=["\']([^"\']+)["\']/', $adminMapRaw, $matches)) {
                                            $adminMapSrc = $matches[1];
                                        } else {
                                            $adminMapSrc = $adminMapRaw;
                                        }
                                    }
                                    if (empty($adminMapSrc)) {
                                        $adminAddr = $settings['office_address']->value ?? '1st Floor, Plot: A-301, Sardar Ali Sabri Road, Block-2, Gulshan e Iqbal, Karachi, Sindh, Pakistan.';
                                        $adminMapSrc = 'https://maps.google.com/maps?q=' . urlencode($adminAddr) . '&t=&z=16&ie=UTF8&iwloc=&output=embed';
                                    }
                                @endphp

                                <div class="col-12 mt-2">
                                    <label class="form-label small fw-bold text-muted">Current Live Map Preview on Website:</label>
                                    <div class="rounded-3 overflow-hidden border" style="height: 250px; background: #f8fafc;">
                                        <iframe src="{{ $adminMapSrc }}" width="100%" height="250" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 3. Contact Us Page In-Page Form Text & Content -->
                        <div class="banner-setting-card mb-4">
                            <h6 class="fw-bold text-primary mb-3">
                                <i class="fa-solid fa-message me-2"></i> 3. Contact Page Inquiry Form Headings
                            </h6>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">Form Small Subtitle / Badge</label>
                                    <input type="text" name="contact_form_subtitle" class="form-control" value="{{ $settings['contact_form_subtitle']->value ?? 'SEND US AN INQUIRY' }}" placeholder="SEND US AN INQUIRY">
                                </div>
                                <div class="col-md-8">
                                    <label class="form-label">Form Main Heading</label>
                                    <input type="text" name="contact_form_title" class="form-control" value="{{ $settings['contact_form_title']->value ?? 'Ready to Upgrade Your Hospital or Laboratory?' }}" placeholder="Ready to Upgrade Your Hospital or Laboratory?">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Form Description / Helper Paragraph</label>
                                    <textarea name="contact_form_description" class="form-control" rows="2" placeholder="Leave your project requirements, equipment inquiries, or technical support requests below. Our biomedical specialists will assist you immediately.">{{ $settings['contact_form_description']->value ?? 'Leave your project requirements, equipment inquiries, or technical support requests below. Our biomedical specialists will assist you immediately.' }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- 4. Contact Us Top Page Banner (Breadcrumb) -->
                        <div class="banner-setting-card">
                            <h6 class="fw-bold text-primary mb-3">
                                <i class="fa-solid fa-image me-2"></i> 4. Contact Us Page Top Banner (Breadcrumb)
                            </h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Page Heading / Title</label>
                                    <input type="text" name="contact_banner_title" class="form-control" value="{{ $settings['contact_banner_title']->value ?? 'Contact us' }}" placeholder="Contact us">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Breadcrumb Subtitle</label>
                                    <input type="text" name="contact_banner_subtitle" class="form-control" value="{{ $settings['contact_banner_subtitle']->value ?? 'Innotech : Contact' }}" placeholder="Innotech : Contact">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Banner Background Image</label>
                                    <div class="upload-preview-box">
                                        @if(isset($settings['contact_banner_image']->value))
                                            <div class="upload-thumb" style="min-width: 120px;">
                                                <img src="{{ asset($settings['contact_banner_image']->value) }}" alt="Contact Banner" style="height: 50px; width: 110px; object-fit: cover;" class="rounded">
                                            </div>
                                        @endif
                                        <div class="flex-grow-1">
                                            <input type="file" name="contact_banner_image" class="form-control form-control-sm mb-1" accept="image/*">
                                            <small class="text-muted">Recommended resolution: 1920x350px. (Leave blank to keep current).</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tab 4: Social Media -->
                    <div class="tab-pane fade" id="social" role="tabpanel">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label"><i class="fa-brands fa-facebook text-primary me-2"></i> Facebook URL</label>
                                <input type="url" name="facebook_url" class="form-control" value="{{ $settings['facebook_url']->value ?? '' }}" placeholder="https://facebook.com/...">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><i class="fa-brands fa-twitter text-info me-2"></i> Twitter / X URL</label>
                                <input type="url" name="twitter_url" class="form-control" value="{{ $settings['twitter_url']->value ?? '' }}" placeholder="https://twitter.com/...">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><i class="fa-brands fa-linkedin text-primary me-2"></i> LinkedIn URL</label>
                                <input type="url" name="linkedin_url" class="form-control" value="{{ $settings['linkedin_url']->value ?? '' }}" placeholder="https://linkedin.com/in/...">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><i class="fa-brands fa-instagram text-danger me-2"></i> Instagram URL</label>
                                <input type="url" name="instagram_url" class="form-control" value="{{ $settings['instagram_url']->value ?? '' }}" placeholder="https://instagram.com/...">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><i class="fa-brands fa-youtube text-danger me-2"></i> YouTube Channel</label>
                                <input type="url" name="youtube_url" class="form-control" value="{{ $settings['youtube_url']->value ?? '' }}" placeholder="https://youtube.com/@...">
                            </div>
                        </div>
                    </div>

                    <!-- Tab 5: About & Stats Counters -->
                    <div class="tab-pane fade" id="stats" role="tabpanel">
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label">About Section Small Badge</label>
                                <input type="text" name="about_badge" class="form-control" value="{{ $settings['about_badge']->value ?? 'ABOUT INNOTECH MEDICAL' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Years of Experience Number Badge</label>
                                <input type="text" name="about_experience_years" class="form-control" value="{{ $settings['about_experience_years']->value ?? '15+' }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label">About Section Main Heading</label>
                                <input type="text" name="about_heading" class="form-control" value="{{ $settings['about_heading']->value ?? '' }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label">About Detailed Description</label>
                                <textarea name="about_description" class="form-control" rows="4">{{ $settings['about_description']->value ?? '' }}</textarea>
                            </div>
                        </div>

                        <h6 class="fw-bold text-primary mb-3"><i class="fa-solid fa-arrow-up-1-9 me-2"></i> Homepage 4 Numerical Stats Counters</h6>
                        <div class="row g-3">
                            <div class="col-md-3">
                                <div class="counter-setting-box">
                                    <label class="form-label small fw-bold text-muted">Counter 1 Value</label>
                                    <input type="text" name="stat_clients_count" class="form-control form-control-sm mb-2" value="{{ $settings['stat_clients_count']->value ?? '500+' }}">
                                    <label class="form-label small fw-bold text-muted">Counter 1 Label</label>
                                    <input type="text" name="stat_clients_label" class="form-control form-control-sm" value="{{ $settings['stat_clients_label']->value ?? 'Hospitals Equipped' }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="counter-setting-box">
                                    <label class="form-label small fw-bold text-muted">Counter 2 Value</label>
                                    <input type="text" name="stat_devices_count" class="form-control form-control-sm mb-2" value="{{ $settings['stat_devices_count']->value ?? '12,500+' }}">
                                    <label class="form-label small fw-bold text-muted">Counter 2 Label</label>
                                    <input type="text" name="stat_devices_label" class="form-control form-control-sm" value="{{ $settings['stat_devices_label']->value ?? 'Devices Installed' }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="counter-setting-box">
                                    <label class="form-label small fw-bold text-muted">Counter 3 Value</label>
                                    <input type="text" name="stat_engineers_count" class="form-control form-control-sm mb-2" value="{{ $settings['stat_engineers_count']->value ?? '50+' }}">
                                    <label class="form-label small fw-bold text-muted">Counter 3 Label</label>
                                    <input type="text" name="stat_engineers_label" class="form-control form-control-sm" value="{{ $settings['stat_engineers_label']->value ?? 'Biomedical Engineers' }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="counter-setting-box">
                                    <label class="form-label small fw-bold text-muted">Counter 4 Value</label>
                                    <input type="text" name="stat_support_count" class="form-control form-control-sm mb-2" value="{{ $settings['stat_support_count']->value ?? '24/7' }}">
                                    <label class="form-label small fw-bold text-muted">Counter 4 Label</label>
                                    <input type="text" name="stat_support_label" class="form-control form-control-sm" value="{{ $settings['stat_support_label']->value ?? 'Emergency Support' }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tab 6: Footer Info -->
                    <div class="tab-pane fade" id="footer" role="tabpanel">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-semibold">Footer About Paragraph</label>
                                <textarea name="footer_about" class="form-control" rows="3">{{ $settings['footer_about']->value ?? '' }}</textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Copyright Line</label>
                                <input type="text" name="copyright_text" class="form-control" value="{{ $settings['copyright_text']->value ?? '' }}">
                            </div>
                        </div>
                    </div>

                    <!-- Tab 7: Services Page Why Choose Us Section -->
                    <div class="tab-pane fade" id="services-why" role="tabpanel">
                        <div class="alert alert-info d-flex align-items-center mb-4">
                            <i class="fa-solid fa-circle-info fs-4 me-3"></i>
                            <div>
                                <strong>Services Page Customization</strong><br>
                                Customize the "Why Choose Us" section shown on the <code>/services</code> page, including main headings, 4 feature benefit boxes, and bottom consultation CTA banner.
                            </div>
                        </div>

                        <!-- Section Header -->
                        <div class="card border mb-4 shadow-sm">
                            <div class="card-header bg-light fw-bold">
                                <i class="fa-solid fa-heading text-primary me-2"></i> Section Heading & Subtitle
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Section Subtitle</label>
                                        <input type="text" name="services_why_choose_subtitle" class="form-control" value="{{ $settings['services_why_choose_subtitle']->value ?? 'Why Choose Us' }}">
                                        <small class="text-muted">Top pill title (e.g., Why Choose Us)</small>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Section Main Title</label>
                                        <input type="text" name="services_why_choose_title" class="form-control" value="{{ $settings['services_why_choose_title']->value ?? 'Biomedical Excellence & Reliability' }}">
                                        <small class="text-muted">Main white header text</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 4 Feature Cards -->
                        <div class="card border mb-4 shadow-sm">
                            <div class="card-header bg-light fw-bold">
                                <i class="fa-solid fa-cubes text-primary me-2"></i> 4 Feature Benefit Cards
                            </div>
                            <div class="card-body">
                                <div class="row g-4">
                                    <!-- Card 1 -->
                                    <div class="col-md-6">
                                        <div class="p-3 border rounded-3 bg-light">
                                            <div class="d-flex align-items-center mb-3">
                                                <i class="flaticon-microscope fs-4 text-primary me-2"></i>
                                                <h6 class="fw-bold text-primary mb-0">Feature 1 (High Quality Equipment)</h6>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label small fw-semibold">Title</label>
                                                <input type="text" name="services_why_1_title" class="form-control" value="{{ $settings['services_why_1_title']->value ?? 'High Quality Equipment' }}">
                                            </div>
                                            <div>
                                                <label class="form-label small fw-semibold">Description</label>
                                                <textarea name="services_why_1_desc" class="form-control" rows="3">{{ $settings['services_why_1_desc']->value ?? 'ISO 13485 and CE certified biomedical systems built for accuracy.' }}</textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Card 2 -->
                                    <div class="col-md-6">
                                        <div class="p-3 border rounded-3 bg-light">
                                            <div class="d-flex align-items-center mb-3">
                                                <i class="flaticon-thinking fs-4 text-primary me-2"></i>
                                                <h6 class="fw-bold text-primary mb-0">Feature 2 (Rapid Field Response)</h6>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label small fw-semibold">Title</label>
                                                <input type="text" name="services_why_2_title" class="form-control" value="{{ $settings['services_why_2_title']->value ?? 'Rapid Field Response' }}">
                                            </div>
                                            <div>
                                                <label class="form-label small fw-semibold">Description</label>
                                                <textarea name="services_why_2_desc" class="form-control" rows="3">{{ $settings['services_why_2_desc']->value ?? 'Immediate calibration & servicing for critical care hospital wards.' }}</textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Card 3 -->
                                    <div class="col-md-6">
                                        <div class="p-3 border rounded-3 bg-light">
                                            <div class="d-flex align-items-center mb-3">
                                                <i class="flaticon-24-hours-1 fs-4 text-primary me-2"></i>
                                                <h6 class="fw-bold text-primary mb-0">Feature 3 (24/7 Biomedical Support)</h6>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label small fw-semibold">Title</label>
                                                <input type="text" name="services_why_3_title" class="form-control" value="{{ $settings['services_why_3_title']->value ?? '24/7 Biomedical Support' }}">
                                            </div>
                                            <div>
                                                <label class="form-label small fw-semibold">Description</label>
                                                <textarea name="services_why_3_desc" class="form-control" rows="3">{{ $settings['services_why_3_desc']->value ?? 'Dedicated clinical support desk with round-the-clock availability.' }}</textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Card 4 -->
                                    <div class="col-md-6">
                                        <div class="p-3 border rounded-3 bg-light">
                                            <div class="d-flex align-items-center mb-3">
                                                <i class="flaticon-team fs-4 text-primary me-2"></i>
                                                <h6 class="fw-bold text-primary mb-0">Feature 4 (Certified Expert Team)</h6>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label small fw-semibold">Title</label>
                                                <input type="text" name="services_why_4_title" class="form-control" value="{{ $settings['services_why_4_title']->value ?? 'Certified Expert Team' }}">
                                            </div>
                                            <div>
                                                <label class="form-label small fw-semibold">Description</label>
                                                <textarea name="services_why_4_desc" class="form-control" rows="3">{{ $settings['services_why_4_desc']->value ?? 'Factory-certified biomedical engineers and hospital project managers.' }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Bottom Banner / CTA -->
                        <div class="card border mb-4 shadow-sm">
                            <div class="card-header bg-light fw-bold">
                                <i class="fa-solid fa-bullhorn text-primary me-2"></i> Bottom Banner / Consultation Option
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-5">
                                        <label class="form-label fw-semibold">Banner Lead Text</label>
                                        <input type="text" name="services_why_banner_text" class="form-control" value="{{ $settings['services_why_banner_text']->value ?? 'Healthcare Infrastructure & Turnkey Hospital Engineering :' }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">Button / Link Label</label>
                                        <input type="text" name="services_why_banner_btn_text" class="form-control" value="{{ $settings['services_why_banner_btn_text']->value ?? 'Request Consultation' }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Button URL</label>
                                        <input type="text" name="services_why_banner_btn_url" class="form-control" value="{{ $settings['services_why_banner_btn_url']->value ?? '/contact' }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tab 8: SEO & Search Ranking (VIP Suite) -->
                    <div class="tab-pane fade" id="seo" role="tabpanel">
                        <!-- Top VIP Alert -->
                        <div class="alert alert-warning border-warning d-flex align-items-center mb-4 p-3 rounded-3 shadow-sm" style="background-color: #FFFBEB;">
                            <div class="me-3 fs-2 text-warning">
                                <i class="fa-solid fa-crown"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1 text-dark">VIP Search Engine Optimization & Webmaster Suite</h6>
                                <p class="mb-0 text-muted small">
                                    Configure dynamic metadata, social Open Graph sharing images, Google Search Console, Google Analytics 4, Schema.org rich snippets, and live XML Sitemap indexing.
                                </p>
                            </div>
                        </div>

                        <!-- 1. Global Meta & Title Engine -->
                        <div class="card border mb-4 shadow-sm">
                            <div class="card-header bg-light d-flex justify-content-between align-items-center py-3">
                                <span class="fw-bold text-dark">
                                    <i class="fa-solid fa-magnifying-glass text-primary me-2"></i> Search Engine Titles & Directives
                                </span>
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-1">Google SERP Ready</span>
                            </div>
                            <div class="card-body p-4">
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Global SEO Title Template</label>
                                        <input type="text" name="seo_meta_title" class="form-control" value="{{ $settings['seo_meta_title']->value ?? 'INNOTECH MEDICAL PVT LTD | Advanced Healthcare & Biomedical Solutions' }}">
                                        <small class="text-muted">Primary title for search engine result snippets.</small>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label fw-semibold">Title Separator</label>
                                        <input type="text" name="seo_title_separator" class="form-control text-center" value="{{ $settings['seo_title_separator']->value ?? '|' }}">
                                        <small class="text-muted">e.g. <code>|</code> or <code>-</code></small>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Search Engine Indexing (Robots)</label>
                                        @php $currRobots = $settings['seo_meta_robots']->value ?? 'index, follow'; @endphp
                                        <select name="seo_meta_robots" class="form-select">
                                            <option value="index, follow" {{ str_contains($currRobots, 'index') && !str_contains($currRobots, 'noindex') ? 'selected' : '' }}>Index, Follow (Recommended - Live Search)</option>
                                            <option value="noindex, nofollow" {{ str_contains($currRobots, 'noindex') ? 'selected' : '' }}>Noindex, Nofollow (Under Construction)</option>
                                            <option value="index, nofollow" {{ str_contains($currRobots, 'index') && str_contains($currRobots, 'nofollow') ? 'selected' : '' }}>Index, Nofollow</option>
                                        </select>
                                        <small class="text-muted">Instructions for Googlebot & Bingbot.</small>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label fw-semibold">Global Website Tagline</label>
                                        <input type="text" name="seo_meta_tagline" class="form-control" value="{{ $settings['seo_meta_tagline']->value ?? 'Innovating Healthcare With Advanced Technologies' }}">
                                    </div>
                                    <div class="col-md-12">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <label class="form-label fw-semibold mb-0">Global Meta Description</label>
                                            <span id="metaDescBadge" class="badge bg-secondary-subtle text-secondary" style="font-size: 11px;">0 / 160 chars</span>
                                        </div>
                                        <textarea name="seo_meta_description" id="metaDescTextarea" class="form-control" rows="3" placeholder="Write a compelling summary between 140-160 characters...">{{ $settings['seo_meta_description']->value ?? 'Leading provider of hospital medical equipment, clinical laboratory analyzers, ICU monitoring systems, and accredited biomedical calibration services in Pakistan.' }}</textarea>
                                        <small class="text-muted">Displayed under your title in Google search results. Aim for 140 to 160 characters.</small>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label fw-semibold">Global Meta Keywords (Comma Separated)</label>
                                        <input type="text" name="seo_meta_keywords" class="form-control" value="{{ $settings['seo_meta_keywords']->value ?? 'medical equipment, biomedical engineering, ICU monitors, laboratory calibration, hospital supply, surgical instruments, Pakistan healthcare, ISO 13485' }}">
                                        <small class="text-muted">Separate terms with commas (e.g. <code>ICU Monitors, Biomedical Calibration, ISO 13485</code>).</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 2. Social Media Open Graph & Twitter Cards -->
                        <div class="card border mb-4 shadow-sm">
                            <div class="card-header bg-light d-flex justify-content-between align-items-center py-3">
                                <span class="fw-bold text-dark">
                                    <i class="fa-solid fa-share-nodes text-success me-2"></i> Social Share Cards (Open Graph / WhatsApp / Facebook / LinkedIn / Twitter)
                                </span>
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1">Rich Preview Active</span>
                            </div>
                            <div class="card-body p-4">
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Default Social Share Image (OG Image)</label>
                                        <input type="file" name="seo_og_image" class="form-control mb-2" accept="image/*">
                                        <small class="text-muted d-block mb-3">Recommended dimensions: <strong>1200 x 630 pixels</strong> (PNG or JPG) for high-resolution WhatsApp and Facebook link cards.</small>
                                        
                                        @php $currentOgImage = $settings['seo_og_image']->value ?? 'assets/img/logo/logo.png'; @endphp
                                        <div class="p-2 border rounded-3 bg-light d-inline-flex align-items-center gap-3">
                                            <img src="{{ asset($currentOgImage) }}" alt="OG Preview" style="max-height: 50px; max-width: 140px; object-fit: contain;">
                                            <div class="small">
                                                <span class="text-muted d-block">Current Share Image</span>
                                                <code style="font-size: 11px;">{{ $currentOgImage }}</code>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">Twitter Card Display</label>
                                        @php $currTwitterCard = $settings['seo_twitter_card']->value ?? 'summary_large_image'; @endphp
                                        <select name="seo_twitter_card" class="form-select">
                                            <option value="summary_large_image" {{ $currTwitterCard === 'summary_large_image' ? 'selected' : '' }}>Large Image (summary_large_image)</option>
                                            <option value="summary" {{ $currTwitterCard === 'summary' ? 'selected' : '' }}>Standard Thumbnail (summary)</option>
                                        </select>
                                        <small class="text-muted">Recommended: Large Image.</small>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">Twitter / X Site Handle</label>
                                        <input type="text" name="seo_twitter_handle" class="form-control" value="{{ $settings['seo_twitter_handle']->value ?? '@InnotechMedical' }}">
                                        <small class="text-muted">e.g. <code>@InnotechMedical</code></small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 3. Google Search Console & Analytics Tracking -->
                        <div class="card border mb-4 shadow-sm">
                            <div class="card-header bg-light py-3 fw-bold text-dark">
                                <i class="fa-solid fa-chart-pie text-info me-2"></i> Webmaster Verification & Tracking Codes
                            </div>
                            <div class="card-body p-4">
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Google Search Console Verification Token</label>
                                        <input type="text" name="seo_google_verification" class="form-control font-monospace" value="{{ $settings['seo_google_verification']->value ?? '' }}" placeholder="e.g. abc123XYZ456...">
                                        <small class="text-muted">Enter the verification meta tag value from Google Search Console.</small>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">Google Analytics 4 (GA4) ID</label>
                                        <input type="text" name="seo_google_analytics" class="form-control font-monospace" value="{{ $settings['seo_google_analytics']->value ?? '' }}" placeholder="e.g. G-XXXXXXXXXX">
                                        <small class="text-muted">Automatically loads gtag.js</small>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">Google Tag Manager (GTM) ID</label>
                                        <input type="text" name="seo_google_tag_manager" class="form-control font-monospace" value="{{ $settings['seo_google_tag_manager']->value ?? '' }}" placeholder="e.g. GTM-XXXXXXX">
                                        <small class="text-muted">Optional GTM container ID</small>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Custom Header Scripts (Injected in <code>&lt;head&gt;</code>)</label>
                                        <textarea name="seo_header_scripts" class="form-control font-monospace small" rows="4" placeholder="<!-- Paste custom Meta Pixel, Hotjar, or tracking scripts here -->">{{ $settings['seo_header_scripts']->value ?? '' }}</textarea>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Custom Footer Scripts (Injected before <code>&lt;/body&gt;</code>)</label>
                                        <textarea name="seo_footer_scripts" class="form-control font-monospace small" rows="4" placeholder="<!-- Paste tracking or chat scripts here -->">{{ $settings['seo_footer_scripts']->value ?? '' }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 4. Schema.org Rich Snippets -->
                        <div class="card border mb-4 shadow-sm">
                            <div class="card-header bg-light py-3 fw-bold text-dark">
                                <i class="fa-solid fa-microchip text-primary me-2"></i> Schema.org Structured Data (Google Rich Snippets)
                            </div>
                            <div class="card-body p-4">
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Organization Schema Type</label>
                                        @php $currSchema = $settings['seo_schema_type']->value ?? 'MedicalBusiness'; @endphp
                                        <select name="seo_schema_type" class="form-select">
                                            <option value="MedicalBusiness" {{ $currSchema === 'MedicalBusiness' ? 'selected' : '' }}>MedicalBusiness (Recommended for Medical Equipment & Services)</option>
                                            <option value="MedicalOrganization" {{ $currSchema === 'MedicalOrganization' ? 'selected' : '' }}>MedicalOrganization</option>
                                            <option value="Organization" {{ $currSchema === 'Organization' ? 'selected' : '' }}>Standard Corporate Organization</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">Medical Specialty / Focus</label>
                                        <input type="text" name="seo_schema_specialty" class="form-control" value="{{ $settings['seo_schema_specialty']->value ?? 'Biomedical Engineering & Medical Equipment Supply' }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 5. XML Sitemap & Robots Live Inspection -->
                        <div class="card border shadow-sm" style="background: #F0FDF4; border-color: #BBF7D0 !important;">
                            <div class="card-header bg-transparent py-3 fw-bold text-success d-flex justify-content-between align-items-center">
                                <span><i class="fa-solid fa-sitemap me-2"></i> Live XML Sitemap & Crawl Directives</span>
                                <span class="badge bg-success text-white">Auto-Updating</span>
                            </div>
                            <div class="card-body p-4">
                                <p class="text-secondary small mb-3">
                                    Your dynamic XML sitemap automatically lists all active services, published articles, specialists, and customer pages with priority scoring and last modified timestamps for Google.
                                </p>
                                <div class="d-flex flex-wrap gap-3">
                                    <a href="{{ url('/sitemap.xml') }}" target="_blank" class="btn btn-outline-success btn-sm fw-semibold">
                                        <i class="fa-solid fa-external-link me-1"></i> Open Live XML Sitemap (<code>/sitemap.xml</code>)
                                    </a>
                                    <a href="{{ url('/robots.txt') }}" target="_blank" class="btn btn-outline-dark btn-sm fw-semibold">
                                        <i class="fa-solid fa-robot me-1"></i> Open Live Robots.txt (<code>/robots.txt</code>)
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tab 9: Email & SMTP Diagnostics -->
                    <div class="tab-pane fade" id="email" role="tabpanel">
                        <div class="row g-4">
                            <!-- 1. Current Mail Configuration Status -->
                            <div class="col-12">
                                <div class="card border shadow-sm">
                                    <div class="card-header bg-light py-3 d-flex justify-content-between align-items-center">
                                        <h6 class="fw-bold mb-0 text-dark">
                                            <i class="fa-solid fa-server text-primary me-2"></i> Current Live Mail Configuration (.env)
                                        </h6>
                                        <span class="badge bg-primary-subtle text-primary border">Auto-Detected</span>
                                    </div>
                                    <div class="card-body p-4">
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <div class="p-3 bg-light rounded border">
                                                    <div class="text-muted small fw-semibold">Default Mailer</div>
                                                    <div class="fw-bold fs-6 text-dark mt-1">
                                                        <span class="badge bg-info text-dark">{{ config('mail.default', 'smtp') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="p-3 bg-light rounded border">
                                                    <div class="text-muted small fw-semibold">SMTP Host & Port</div>
                                                    <div class="fw-bold fs-6 text-dark mt-1">
                                                        {{ config('mail.mailers.smtp.host', '127.0.0.1') }}:{{ config('mail.mailers.smtp.port', 465) }}
                                                        <span class="badge bg-secondary ms-1">{{ strtoupper(config('mail.mailers.smtp.encryption', 'ssl') ?: 'NONE') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="p-3 bg-light rounded border">
                                                    <div class="text-muted small fw-semibold">SMTP Username / Account</div>
                                                    <div class="fw-bold fs-6 text-dark mt-1 text-truncate" title="{{ config('mail.mailers.smtp.username') }}">
                                                        {{ config('mail.mailers.smtp.username') ?: 'Not configured' }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="p-3 bg-light rounded border">
                                                    <div class="text-muted small fw-semibold">From Address & Name</div>
                                                    <div class="fw-bold fs-6 text-dark mt-1">
                                                        {{ config('mail.from.address') }} <small class="text-muted fw-normal">({{ config('mail.from.name') }})</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="p-3 bg-light rounded border">
                                                    <div class="text-muted small fw-semibold">cPanel SSL Peer Verification</div>
                                                    <div class="fw-bold fs-6 mt-1">
                                                        @if(config('mail.mailers.smtp.verify_peer') === false)
                                                            <span class="text-success"><i class="fa-solid fa-circle-check me-1"></i> Disabled (Compatible / Self-Signed SSL Allowed)</span>
                                                        @else
                                                            <span class="text-warning"><i class="fa-solid fa-triangle-exclamation me-1"></i> Strict Verification Enabled</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 2. Interactive Live Test Email Form -->
                            <div class="col-12">
                                <div class="card border shadow-sm border-primary-subtle">
                                    <div class="card-header bg-primary text-white py-3 d-flex justify-content-between align-items-center">
                                        <h6 class="fw-bold mb-0">
                                            <i class="fa-solid fa-paper-plane me-2"></i> Send Live Test Email (Diagnose SMTP & Server Delivery)
                                        </h6>
                                        <small class="opacity-75">Tests live socket connection & dispatch</small>
                                    </div>
                                    <div class="card-body p-4">
                                        <p class="text-secondary small mb-3">
                                            Enter any email address (your personal email or client email) to test whether your live server can successfully connect to the mail server and deliver emails without errors.
                                        </p>
                                        
                                        <div class="row g-2 align-items-center">
                                            <div class="col-md-8">
                                                <div class="input-group">
                                                    <span class="input-group-text bg-light"><i class="fa-solid fa-at text-muted"></i></span>
                                                    <input type="email" id="testRecipientEmail" class="form-control" 
                                                           value="{{ $settings['support_email']->value ?? (config('mail.from.address') ?: 'info@innotechmed.com') }}" 
                                                           placeholder="recipient@example.com" required>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <button type="button" id="btnRunEmailTest" class="btn btn-primary w-100 fw-semibold">
                                                    <i class="fa-solid fa-bolt me-1"></i> Send Test Email Now
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Live Diagnostic Output Console -->
                                        <div id="testEmailResultBox" class="d-none mt-4">
                                            <div class="p-3 rounded" id="testEmailAlert" style="font-family: monospace; font-size: 13px; line-height: 1.6;">
                                                <div id="testEmailMessage" class="fw-bold mb-2"></div>
                                                <div id="testEmailLogs" style="background: rgba(0,0,0,0.04); padding: 10px; border-radius: 6px; white-space: pre-wrap;"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 3. cPanel Hosting Quick Troubleshooting Guide -->
                            <div class="col-12">
                                <div class="card border shadow-sm" style="background: #F8FAFC;">
                                    <div class="card-header bg-transparent py-3 fw-bold text-dark d-flex justify-content-between align-items-center">
                                        <span><i class="fa-solid fa-lightbulb text-warning me-2"></i> Live Server (cPanel) Email Configuration Guide</span>
                                        <span class="badge bg-secondary text-white">Troubleshooting</span>
                                    </div>
                                    <div class="card-body p-4">
                                        <p class="small text-secondary mb-3">
                                            Agar live server par newsletter ya inquiry reply emails nahi ja rahi hon, toh aam taur par in 4 wajohat mein se koi ek hoti hai:
                                        </p>

                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <div class="p-3 bg-white border rounded h-100">
                                                    <h6 class="fw-bold text-dark mb-1">
                                                        <span class="badge bg-primary me-1">Option 1</span> cPanel SMTP with SSL (Port 465)
                                                    </h6>
                                                    <p class="small text-muted mb-2">Agar aapka cPanel custom domain SSL use karta hai:</p>
                                                    <pre class="bg-light p-2 rounded small mb-0 border" style="font-size: 11.5px;"><code>MAIL_MAILER=smtp
MAIL_HOST=mail.innotechmed.com
MAIL_PORT=465
MAIL_USERNAME=info@innotechmed.com
MAIL_PASSWORD=your_actual_password
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS="info@innotechmed.com"
MAIL_FROM_NAME="INNOTECH MEDICAL PVT LTD"
MAIL_VERIFY_PEER=false</code></pre>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="p-3 bg-white border rounded h-100">
                                                    <h6 class="fw-bold text-dark mb-1">
                                                        <span class="badge bg-success me-1">Option 2</span> cPanel SMTP with TLS (Port 587 - Recommended)
                                                    </h6>
                                                    <p class="small text-muted mb-2">Bohat se hosting providers Port 465 block karte hain, jabkay 587 khula hota hai:</p>
                                                    <pre class="bg-light p-2 rounded small mb-0 border" style="font-size: 11.5px;"><code>MAIL_MAILER=smtp
MAIL_HOST=mail.innotechmed.com
MAIL_PORT=587
MAIL_USERNAME=info@innotechmed.com
MAIL_PASSWORD=your_actual_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="info@innotechmed.com"
MAIL_FROM_NAME="INNOTECH MEDICAL PVT LTD"
MAIL_VERIFY_PEER=false</code></pre>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="p-3 bg-white border rounded h-100">
                                                    <h6 class="fw-bold text-dark mb-1">
                                                        <span class="badge bg-info text-dark me-1">Option 3</span> Localhost Internal SMTP (Port 25)
                                                    </h6>
                                                    <p class="small text-muted mb-2">Agar website aur email dono usi cPanel server par hain:</p>
                                                    <pre class="bg-light p-2 rounded small mb-0 border" style="font-size: 11.5px;"><code>MAIL_MAILER=smtp
MAIL_HOST=localhost
MAIL_PORT=25
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="info@innotechmed.com"
MAIL_FROM_NAME="INNOTECH MEDICAL PVT LTD"</code></pre>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="p-3 bg-white border rounded h-100">
                                                    <h6 class="fw-bold text-dark mb-1">
                                                        <span class="badge bg-warning text-dark me-1">Option 4</span> Native Linux Sendmail (Guaranteed Delivery)
                                                    </h6>
                                                    <p class="small text-muted mb-2">cPanel ka built-in MTA jo bina kisi port ya password ke email send karta hai:</p>
                                                    <pre class="bg-light p-2 rounded small mb-0 border" style="font-size: 11.5px;"><code>MAIL_MAILER=sendmail
MAIL_SENDMAIL_PATH="/usr/sbin/sendmail -bs -i"
MAIL_FROM_ADDRESS="info@innotechmed.com"
MAIL_FROM_NAME="INNOTECH MEDICAL PVT LTD"</code></pre>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="alert alert-warning mt-3 mb-0 d-flex align-items-center gap-2 small">
                                            <i class="fa-solid fa-triangle-exclamation fs-5 text-warning flex-shrink-0"></i>
                                            <div>
                                                <strong>Ahem Tareen Point:</strong> Jab bhi aap live server par <code>.env</code> file change karein, cPanel terminal ya SSH mein <code>php artisan optimize:clear</code> zaroor chalayein taakay puraana cached configuration clear ho jaye!
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card Footer with Save Button -->
                <div class="mt-4 pt-3 border-top d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary px-4 py-2 fw-semibold shadow-sm">
                        <i class="fa-solid fa-floppy-disk me-2"></i> Save All Settings
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Tab persistence across page refresh/save
        const storageKey = 'innotech_admin_settings_tab';
        const urlParams = new URLSearchParams(window.location.search);
        const queryTab = urlParams.get('tab');
        const savedTab = queryTab || localStorage.getItem(storageKey);

        if (savedTab) {
            const targetTrigger = document.querySelector(`#settingsTabs button[data-bs-target="#${savedTab}"]`);
            if (targetTrigger) {
                const tabInstance = bootstrap.Tab.getOrCreateInstance(targetTrigger);
                tabInstance.show();
            }
        }

        $('#settingsTabs button[data-bs-toggle="pill"]').on('shown.bs.tab', function(e) {
            const targetId = $(e.target).attr('data-bs-target').replace('#', '');
            localStorage.setItem(storageKey, targetId);
            
            const newUrl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?tab=' + targetId;
            window.history.replaceState({ path: newUrl }, '', newUrl);
        });

        $('#settingsForm').on('submit', function() {
            const activeTabId = $('#settingsTabs .nav-link.active').attr('data-bs-target').replace('#', '');
            let action = $(this).attr('action');
            if (action.indexOf('?') === -1) {
                $(this).attr('action', action + '?tab=' + activeTabId);
            }
        });

        // Live Meta Description Character Counter
        function updateMetaDescCount() {
            const len = $('#metaDescTextarea').val().length;
            const badge = $('#metaDescBadge');
            badge.text(len + ' / 160 chars');
            if (len >= 130 && len <= 160) {
                badge.removeClass('bg-secondary-subtle bg-danger-subtle text-secondary text-danger').addClass('bg-success-subtle text-success');
            } else if (len > 160) {
                badge.removeClass('bg-secondary-subtle bg-success-subtle text-secondary text-success').addClass('bg-danger-subtle text-danger');
            } else {
                badge.removeClass('bg-success-subtle bg-danger-subtle text-success text-danger').addClass('bg-secondary-subtle text-secondary');
            }
        }
        $('#metaDescTextarea').on('input propertychange', updateMetaDescCount);
        updateMetaDescCount();

        // Interactive Live Test Email Dispatcher
        $('#btnRunEmailTest').on('click', function(e) {
            e.preventDefault();
            const btn = $(this);
            const emailInput = $('#testRecipientEmail');
            const targetEmail = emailInput.val().trim();
            const resultBox = $('#testEmailResultBox');
            const alertBox = $('#testEmailAlert');
            const msgBox = $('#testEmailMessage');
            const logsBox = $('#testEmailLogs');

            if (!targetEmail) {
                alert('Please enter a destination email address to run test.');
                emailInput.focus();
                return;
            }

            const origHtml = btn.html();
            btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span> Testing Live Connection...');
            resultBox.removeClass('d-none');
            alertBox.removeClass('alert-success alert-danger alert-warning bg-success-subtle bg-danger-subtle bg-warning-subtle text-success text-danger text-dark')
                    .addClass('bg-info-subtle text-dark border border-info');
            msgBox.html('<i class="fa-solid fa-spinner fa-spin me-2"></i> Connecting to mail server and attempting transmission...');
            logsBox.text('Initiating diagnostic test...\nContacting configured mailer...');

            $.ajax({
                url: "{{ route('admin.settings.test_email') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    test_email: targetEmail
                },
                dataType: "json",
                success: function(res) {
                    btn.prop('disabled', false).html(origHtml);
                    alertBox.removeClass('bg-info-subtle text-dark border-info');

                    if (res.success) {
                        alertBox.addClass('bg-success-subtle text-success border border-success');
                        msgBox.html('<i class="fa-solid fa-circle-check text-success me-2"></i> ' + res.message);
                    } else {
                        alertBox.addClass('bg-danger-subtle text-danger border border-danger');
                        msgBox.html('<i class="fa-solid fa-triangle-exclamation text-danger me-2"></i> ' + res.message);
                    }

                    if (res.logs && res.logs.length) {
                        logsBox.text(res.logs.join('\n'));
                    } else {
                        logsBox.text('No detailed logs returned.');
                    }
                },
                error: function(xhr) {
                    btn.prop('disabled', false).html(origHtml);
                    alertBox.removeClass('bg-info-subtle text-dark border-info')
                            .addClass('bg-danger-subtle text-danger border border-danger');
                    let errMsg = 'Network or server error encountered during test.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errMsg = xhr.responseJSON.message;
                    }
                    msgBox.html('<i class="fa-solid fa-circle-xmark text-danger me-2"></i> ' + errMsg);
                    logsBox.text('HTTP ' + xhr.status + ': ' + xhr.statusText);
                }
            });
        });
    });
</script>
@endpush
