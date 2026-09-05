@extends('admin.layouts.master')

@section('title', 'Home Page Sections Manager')
@section('header_title', 'Home Page Sections Manager')

@push('styles')
<style>
    /* Section Manager Custom Styles */
    .manager-header-card {
        background: linear-gradient(135deg, #0A192F 0%, #0E63FF 100%);
        border-radius: 12px;
        color: #fff;
        padding: 24px 30px;
        margin-bottom: 25px;
        box-shadow: 0 10px 25px rgba(14, 99, 255, 0.15);
    }

    .nav-tabs-custom {
        border-bottom: none;
        gap: 8px;
        background: #fff;
        padding: 10px;
        border-radius: 12px;
        border: 1px solid var(--card-border);
        margin-bottom: 25px;
        display: flex;
        flex-wrap: wrap;
    }

    .nav-tabs-custom .nav-link {
        border: none;
        border-radius: 8px;
        padding: 10px 16px;
        font-weight: 600;
        font-size: 13px;
        color: #64748B;
        background: #F8FAFC;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s ease;
        position: relative;
    }

    .nav-tabs-custom .nav-link:hover {
        background: #EBF2FE;
        color: var(--primary);
    }

    .nav-tabs-custom .nav-link.active {
        background: var(--primary);
        color: #FFFFFF !important;
        box-shadow: 0 4px 12px rgba(14, 99, 255, 0.3);
    }

    .nav-tabs-custom .nav-link.active i {
        color: #fff;
    }

    /* Unsaved Dirty Badge */
    .unsaved-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background-color: #F59E0B;
        display: none;
        box-shadow: 0 0 6px #F59E0B;
    }

    .nav-link.dirty .unsaved-dot {
        display: inline-block;
    }

    .nav-link.dirty {
        border: 1px dashed #F59E0B !important;
    }

    /* Section Control Header */
    .section-ctrl-bar {
        background: #F8FAFC;
        border: 1px solid var(--card-border);
        border-radius: 10px;
        padding: 16px 20px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    /* Toggle Switch */
    .form-switch .form-check-input {
        width: 48px;
        height: 24px;
        cursor: pointer;
    }

    .form-switch .form-check-input:checked {
        background-color: #10B981;
        border-color: #10B981;
    }

    .section-badge-status {
        font-size: 12px;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 6px;
    }

    .section-badge-status.enabled {
        background: #DCFCE7;
        color: #15803D;
    }

    .section-badge-status.disabled {
        background: #FEE2E2;
        color: #B91C1C;
    }

    /* Floating Save Bar */
    .save-sticky-bar {
        position: sticky;
        bottom: 20px;
        background: rgba(10, 25, 47, 0.95);
        backdrop-filter: blur(8px);
        padding: 14px 24px;
        border-radius: 12px;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: space-between;
        z-index: 900;
        box-shadow: 0 10px 30px rgba(0,0,0,0.25);
        margin-top: 25px;
    }

    .card-inner-box {
        background: #F8FAFC;
        border: 1px solid #E2E8F0;
        border-radius: 8px;
        padding: 18px;
        margin-bottom: 18px;
    }

    .form-label {
        font-size: 13px;
        font-weight: 600;
        color: #334155;
        margin-bottom: 6px;
    }

    .form-control, .form-select {
        border: 1px solid #CBD5E1;
        border-radius: 8px;
        font-size: 14px;
        padding: 9px 14px;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(14, 99, 255, 0.15);
    }

    .modal {
        z-index: 1065 !important;
    }
    .modal-backdrop {
        z-index: 1055 !important;
    }
</style>
@endpush

@section('content')

<!-- Header Banner -->
<div class="manager-header-card d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
    <div>
        <h3 class="fw-bold mb-1"><i class="fa-solid fa-layer-group me-2"></i> Home Page Sections Manager</h3>
        <p class="mb-0 text-white-50" style="font-size: 14px;">Manage, edit, customize, or toggle visibility for every single component on your homepage.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ url('/') }}" target="_blank" class="btn btn-light btn-sm fw-bold px-3 py-2">
            <i class="fa-solid fa-eye me-1"></i> Preview Live
        </a>
        <button type="button" class="btn btn-warning btn-sm fw-bold px-3 py-2" id="saveAllSectionsBtn">
            <i class="fa-solid fa-floppy-disks me-1"></i> Save All Sections
        </button>
    </div>
</div>

<!-- Tabs Navigation -->
<ul class="nav nav-tabs-custom" id="homeSectionsTabs" role="tablist">
    <li class="nav-item">
        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-header" type="button">
            <i class="fa-solid fa-compass"></i> Header & Top
            <span class="unsaved-dot"></span>
        </button>
    </li>
    <li class="nav-item">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-banner" type="button">
            <i class="fa-solid fa-flag"></i> Hero Banner
            <span class="unsaved-dot"></span>
        </button>
    </li>
    <li class="nav-item">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-services" type="button">
            <i class="fa-solid fa-stethoscope"></i> Services
            <span class="unsaved-dot"></span>
        </button>
    </li>
    <li class="nav-item">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-about" type="button">
            <i class="fa-solid fa-building"></i> About & Exp
            <span class="unsaved-dot"></span>
        </button>
    </li>
    <li class="nav-item">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-counters" type="button">
            <i class="fa-solid fa-calculator"></i> Counters
            <span class="unsaved-dot"></span>
        </button>
    </li>
    <li class="nav-item">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-gallery" type="button">
            <i class="fa-solid fa-images"></i> Gallery
            <span class="unsaved-dot"></span>
        </button>
    </li>
    <li class="nav-item">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-choose" type="button">
            <i class="fa-solid fa-award"></i> Why Choose Us
            <span class="unsaved-dot"></span>
        </button>
    </li>
    <li class="nav-item">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-appointment" type="button">
            <i class="fa-solid fa-calendar-check"></i> Appointment
            <span class="unsaved-dot"></span>
        </button>
    </li>
    <li class="nav-item">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-team" type="button">
            <i class="fa-solid fa-user-doctor"></i> Specialists Team
            <span class="unsaved-dot"></span>
        </button>
    </li>
    <li class="nav-item">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-testimonials" type="button">
            <i class="fa-solid fa-comments"></i> Testimonials
            <span class="unsaved-dot"></span>
        </button>
    </li>
    <li class="nav-item">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-brands" type="button">
            <i class="fa-solid fa-handshake"></i> Brand Partners
            <span class="unsaved-dot"></span>
        </button>
    </li>
    <li class="nav-item">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-cta" type="button">
            <i class="fa-solid fa-phone-volume"></i> CTA Call Bar
            <span class="unsaved-dot"></span>
        </button>
    </li>
    <li class="nav-item">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-blog" type="button">
            <i class="fa-solid fa-newspaper"></i> Blog / Articles
            <span class="unsaved-dot"></span>
        </button>
    </li>
    <li class="nav-item">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-footer" type="button">
            <i class="fa-solid fa-shoe-prints"></i> Footer
            <span class="unsaved-dot"></span>
        </button>
    </li>
</ul>

<!-- Tabs Content -->
<div class="tab-content" id="homeSectionsTabContent">

    <!-- 1. HEADER & TOP NAVIGATION -->
    <div class="tab-pane fade show active" id="tab-header" role="tabpanel">
        <form class="section-form mb-4" action="{{ route('admin.home_sections.save') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="active_tab" value="header">
            
            <div class="admin-card">
                <div class="admin-card-header">
                    <h5><i class="fa-solid fa-compass text-primary me-2"></i> Header & Top Bar Info</h5>
                </div>
                <div class="admin-card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Help Desk Phone Number</label>
                            <input type="text" name="helpdesk_phone" class="form-control" value="{{ $settings['helpdesk_phone'] ?? '+92 331 6699992' }}">
                            <small class="text-muted">Displayed in the header top right button on both desktop & mobile.</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Office Working Hours</label>
                            <input type="text" name="working_hours" class="form-control" value="{{ $settings['working_hours'] ?? 'Monday - Friday 10:00 am - 06:00 Pm' }}">
                            <small class="text-muted">Displayed in the inner page header top bar.</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Support Email Address</label>
                            <input type="email" name="support_email" class="form-control" value="{{ $settings['support_email'] ?? 'info@innotecmedical.org' }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Office Physical Address</label>
                            <input type="text" name="office_address" class="form-control" value="{{ $settings['office_address'] ?? '1st Floor, Plot: A-301, Sardar Ali Sabri Road, Block-2, Gulshan e Iqbal, Karachi, Sindh, Pakistan.' }}">
                        </div>

                        <div class="col-12 mb-3">
                            <label class="form-label fw-bold"><i class="fa-solid fa-image text-primary me-1"></i> Website Main Logo (Header & Mobile)</label>
                            <div class="d-flex align-items-center gap-3 p-3 border rounded-3 bg-light">
                                <img src="{{ asset($settings['logo_path'] ?? 'assets/img/logo/logo.png') }}" alt="Current Logo" class="border bg-white p-2 rounded-2" style="max-height: 50px; max-width: 180px; object-fit: contain;">
                                <div class="flex-grow-1">
                                    <input type="file" name="logo_path" class="form-control" accept="image/*,.jfif,.png,.jpg,.jpeg,.webp,.svg,.gif,.avif,.bmp">
                                    <small class="text-muted">Recommended: PNG / SVG with transparent background (e.g. 180x50px).</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="admin-card-header bg-light d-flex justify-content-between align-items-center">
                    <span class="text-muted fs-6"><i class="fa-solid fa-circle-info me-1"></i> Changes take effect immediately upon saving.</span>
                    <button type="submit" class="btn btn-theme save-section-btn">
                        <i class="fa-solid fa-floppy-disk me-1"></i> Save Header Info
                    </button>
                </div>
            </div>
        </form>

        <!-- DYNAMIC NAVIGATION MENU BUILDER GRID -->
        <div class="admin-card">
            <div class="admin-card-header d-flex flex-wrap align-items-center justify-content-between gap-3" style="background: #F8FAFC; border-bottom: 1px solid #E2E8F0; padding: 18px 24px;">
                <div>
                    <h5 class="mb-1 fw-bold text-dark"><i class="fa-solid fa-bars-staggered text-primary me-2"></i> Header Navigation Menu Manager</h5>
                    <small class="text-muted fs-6">Control exact menus, names, ordering, page links, dropdowns, and home vs inner header visibility.</small>
                </div>
                <button type="button" class="btn btn-primary btn-sm px-3 py-2 fw-semibold rounded-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#addMenuModal">
                    <i class="fa-solid fa-plus me-1"></i> Add Menu Item
                </button>
            </div>
            <div class="table-responsive">
                <table class="table align-middle mb-0" style="border-collapse: separate; border-spacing: 0;">
                    <thead style="background: #F1F5F9; color: #475569; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;">
                        <tr>
                            <th class="ps-4 py-3" style="min-width: 220px;">Menu Label</th>
                            <th class="py-3" style="min-width: 220px;">Target Link / Destination</th>
                            <th class="py-3" style="min-width: 130px;">Parent Level</th>
                            <th class="py-3 text-center" style="width: 80px;">Order</th>
                            <th class="py-3 text-center" style="min-width: 120px;">Home Header</th>
                            <th class="py-3 text-center" style="min-width: 120px;">Inner Header</th>
                            <th class="py-3 text-center" style="min-width: 100px;">Status</th>
                            <th class="pe-4 py-3 text-end" style="min-width: 110px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody style="font-size: 13px;">
                        @forelse($navMenus as $menu)
                            <!-- Parent Item Row -->
                            <tr style="background: #FFFFFF; border-bottom: 1px solid #F1F5F9; transition: background 0.2s;" class="menu-row hover-bg">
                                <td class="ps-4 py-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge bg-primary-subtle text-primary p-2 rounded-2"><i class="fa-solid fa-folder-tree"></i></span>
                                        <div>
                                            <span class="fw-bold text-dark fs-6">{{ $menu->title }}</span>
                                            @if($menu->children->count() > 0)
                                                <span class="badge bg-info-subtle text-info border border-info-subtle rounded-pill ms-1" style="font-size: 10px;">{{ $menu->children->count() }} Sub-links</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3">
                                    <div class="d-flex flex-column gap-1">
                                        <code class="text-primary bg-light px-2 py-1 rounded text-truncate d-inline-block" style="max-width: 260px; font-size: 12px;">{{ $menu->url ?: '#' }}</code>
                                        <span class="badge bg-secondary-subtle text-secondary rounded-1 align-self-start" style="font-size: 10px;">{{ strtoupper($menu->page_route ?: 'CUSTOM') }}</span>
                                    </div>
                                </td>
                                <td class="py-3">
                                    <span class="badge bg-light text-secondary border px-2 py-1 rounded-pill"><i class="fa-solid fa-layer-group me-1"></i> Top Level</span>
                                </td>
                                <td class="py-3 text-center">
                                    <span class="badge bg-light text-dark border px-2 py-1 rounded fw-bold">{{ $menu->order }}</span>
                                </td>
                                <td class="py-3 text-center">
                                    <button type="button" class="btn btn-sm {{ $menu->show_on_home ? 'btn-success text-white' : 'btn-light text-muted border' }} toggle-menu-btn rounded-pill px-3 py-1 shadow-none" data-id="{{ $menu->id }}" data-field="show_on_home" style="font-size: 11px; font-weight: 600;">
                                        {{ $menu->show_on_home ? '✓ Visible' : '✕ Hidden' }}
                                    </button>
                                </td>
                                <td class="py-3 text-center">
                                    <button type="button" class="btn btn-sm {{ $menu->show_on_inner ? 'btn-info text-white' : 'btn-light text-muted border' }} toggle-menu-btn rounded-pill px-3 py-1 shadow-none" data-id="{{ $menu->id }}" data-field="show_on_inner" style="font-size: 11px; font-weight: 600;">
                                        {{ $menu->show_on_inner ? '✓ Visible' : '✕ Hidden' }}
                                    </button>
                                </td>
                                <td class="py-3 text-center">
                                    <button type="button" class="btn btn-sm {{ $menu->is_active ? 'btn-primary' : 'btn-danger' }} toggle-menu-btn rounded-pill px-3 py-1 shadow-none" data-id="{{ $menu->id }}" data-field="is_active" style="font-size: 11px; font-weight: 600;">
                                        {{ $menu->is_active ? 'Active' : 'Disabled' }}
                                    </button>
                                </td>
                                <td class="pe-4 py-3 text-end text-nowrap">
                                    <button type="button" class="btn btn-sm btn-light border text-primary rounded-2 px-2 py-1 me-1 shadow-none" data-bs-toggle="modal" data-bs-target="#editMenuModal{{ $menu->id }}" title="Edit Menu Item">
                                        <i class="fa-solid fa-pen"></i> Edit
                                    </button>
                                    <form action="{{ route('admin.nav_menus.destroy', $menu->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this menu item and any sub-menus?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-light border text-danger rounded-2 px-2 py-1 shadow-none" title="Delete Menu Item"><i class="fa-solid fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Sub Items Rows (Children) -->
                            @if($menu->children->count() > 0)
                                @foreach($menu->children as $child)
                                    <tr style="background: #F8FAFC; border-bottom: 1px solid #EEF2F6;" class="menu-row">
                                        <td class="ps-5 py-2">
                                            <div class="d-flex align-items-center gap-2">
                                                <i class="fa-solid fa-turn-down-right text-muted" style="transform: rotate(0deg); opacity: 0.6;"></i>
                                                <span class="badge bg-white text-dark border p-1 rounded"><i class="fa-solid fa-link text-muted"></i></span>
                                                <span class="fw-semibold text-dark">{{ $child->title }}</span>
                                            </div>
                                        </td>
                                        <td class="py-2">
                                            <div class="d-flex flex-column gap-1">
                                                <code class="text-secondary bg-white border px-2 py-1 rounded text-truncate d-inline-block" style="max-width: 260px; font-size: 11px;">{{ $child->url }}</code>
                                                <span class="badge bg-light text-muted border rounded-1 align-self-start" style="font-size: 9px;">{{ strtoupper($child->page_route ?: 'CUSTOM') }}</span>
                                            </div>
                                        </td>
                                        <td class="py-2">
                                            <span class="badge bg-white text-dark border px-2 py-1 rounded-pill" style="font-size: 11px;"><i class="fa-solid fa-turn-up text-muted me-1"></i> {{ $menu->title }}</span>
                                        </td>
                                        <td class="py-2 text-center">
                                            <span class="badge bg-white text-dark border px-2 py-1 rounded fw-bold">{{ $child->order }}</span>
                                        </td>
                                        <td class="py-2 text-center">
                                            <button type="button" class="btn btn-sm {{ $child->show_on_home ? 'btn-success text-white' : 'btn-light text-muted border' }} toggle-menu-btn rounded-pill px-3 py-1 shadow-none" data-id="{{ $child->id }}" data-field="show_on_home" style="font-size: 11px; font-weight: 600;">
                                                {{ $child->show_on_home ? '✓ Visible' : '✕ Hidden' }}
                                            </button>
                                        </td>
                                        <td class="py-2 text-center">
                                            <button type="button" class="btn btn-sm {{ $child->show_on_inner ? 'btn-info text-white' : 'btn-light text-muted border' }} toggle-menu-btn rounded-pill px-3 py-1 shadow-none" data-id="{{ $child->id }}" data-field="show_on_inner" style="font-size: 11px; font-weight: 600;">
                                                {{ $child->show_on_inner ? '✓ Visible' : '✕ Hidden' }}
                                            </button>
                                        </td>
                                        <td class="py-2 text-center">
                                            <button type="button" class="btn btn-sm {{ $child->is_active ? 'btn-primary' : 'btn-danger' }} toggle-menu-btn rounded-pill px-3 py-1 shadow-none" data-id="{{ $child->id }}" data-field="is_active" style="font-size: 11px; font-weight: 600;">
                                                {{ $child->is_active ? 'Active' : 'Disabled' }}
                                            </button>
                                        </td>
                                        <td class="pe-4 py-2 text-end text-nowrap">
                                            <button type="button" class="btn btn-sm btn-white border text-primary rounded-2 px-2 py-1 me-1 shadow-none" data-bs-toggle="modal" data-bs-target="#editMenuModal{{ $child->id }}" title="Edit Sub-menu">
                                                <i class="fa-solid fa-pen"></i> Edit
                                            </button>
                                            <form action="{{ route('admin.nav_menus.destroy', $child->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this sub-menu item?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-white border text-danger rounded-2 px-2 py-1 shadow-none" title="Delete Sub-menu"><i class="fa-solid fa-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-5">
                                    <i class="fa-solid fa-folder-open fs-2 mb-2 d-block text-muted"></i>
                                    No navigation menu items found. Click "+ Add Menu Item" to create one.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- OFF-CANVAS SIDEBAR DRAWER MANAGER -->
        <form class="section-form mt-4" action="{{ route('admin.home_sections.save') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="active_tab" value="header">

            <div class="admin-card">
                <div class="admin-card-header bg-light d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-1 fw-bold text-dark"><i class="fa-solid fa-bars text-primary me-2"></i> Off-Canvas Sidebar Menu & Drawer Settings</h5>
                        <small class="text-muted">Control the slide-in drawer on the right side (tagline, contact info, newsletter, and Instagram photos).</small>
                    </div>
                    <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 fw-semibold">Slide-in Menu</span>
                </div>
                <div class="admin-card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Mission Statement / Tagline</label>
                            <textarea name="sidebar_mission" class="form-control" rows="2">{{ $settings['sidebar_mission'] ?? 'Our mission is to ensure the generation of accurate and precise findings.' }}</textarea>
                            <small class="text-muted">Displayed under the logo inside the slide-in menu drawer.</small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Contact Section Title</label>
                            <input type="text" name="sidebar_contact_title" class="form-control" value="{{ $settings['sidebar_contact_title'] ?? 'Contact Us' }}">
                            <small class="text-muted">Heading for the contact address, phone, and email list.</small>
                        </div>

                        <div class="col-12"><hr class="my-2 text-muted"></div>

                        <!-- Newsletter in Drawer -->
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="form-label fw-semibold mb-0">Newsletter Box Title</label>
                                <div class="form-check form-switch m-0">
                                    <input type="hidden" name="sidebar_newsletter_enabled" value="0">
                                    <input class="form-check-input" type="checkbox" name="sidebar_newsletter_enabled" value="1" {{ ($settings['sidebar_newsletter_enabled'] ?? '1') == '1' ? 'checked' : '' }}>
                                </div>
                            </div>
                            <input type="text" name="sidebar_newsletter_title" class="form-control" value="{{ $settings['sidebar_newsletter_title'] ?? 'Get Update' }}">
                        </div>

                        <!-- Instagram Gallery in Drawer -->
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="form-label fw-semibold mb-0">Instagram Gallery Title</label>
                                <div class="form-check form-switch m-0">
                                    <input type="hidden" name="sidebar_gallery_enabled" value="0">
                                    <input class="form-check-input" type="checkbox" name="sidebar_gallery_enabled" value="1" {{ ($settings['sidebar_gallery_enabled'] ?? '1') == '1' ? 'checked' : '' }}>
                                </div>
                            </div>
                            <input type="text" name="sidebar_gallery_title" class="form-control" value="{{ $settings['sidebar_gallery_title'] ?? 'Check Instagram Post' }}">
                        </div>

                        <div class="col-12 mt-3">
                            <label class="form-label fw-semibold"><i class="fa-brands fa-instagram text-danger me-1"></i> Drawer Instagram / Showcase 3 Photos</label>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="p-2 border rounded bg-light d-flex align-items-center gap-2">
                                        <img src="{{ asset($settings['sidebar_gallery_img_1'] ?? 'assets/img/blog/blog-in-01.jpg') }}" alt="" style="width: 50px; height: 50px; object-fit: cover; border-radius: 6px;">
                                        <div class="flex-grow-1">
                                            <label class="small text-muted d-block mb-1">Photo 1</label>
                                            <input type="file" name="sidebar_gallery_img_1" class="form-control form-control-sm" accept="image/*">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-2 border rounded bg-light d-flex align-items-center gap-2">
                                        <img src="{{ asset($settings['sidebar_gallery_img_2'] ?? 'assets/img/blog/blog-in-02.jpg') }}" alt="" style="width: 50px; height: 50px; object-fit: cover; border-radius: 6px;">
                                        <div class="flex-grow-1">
                                            <label class="small text-muted d-block mb-1">Photo 2</label>
                                            <input type="file" name="sidebar_gallery_img_2" class="form-control form-control-sm" accept="image/*">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-2 border rounded bg-light d-flex align-items-center gap-2">
                                        <img src="{{ asset($settings['sidebar_gallery_img_3'] ?? 'assets/img/blog/blog-in-03.jpg') }}" alt="" style="width: 50px; height: 50px; object-fit: cover; border-radius: 6px;">
                                        <div class="flex-grow-1">
                                            <label class="small text-muted d-block mb-1">Photo 3</label>
                                            <input type="file" name="sidebar_gallery_img_3" class="form-control form-control-sm" accept="image/*">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="admin-card-header bg-light d-flex justify-content-between align-items-center">
                    <span class="text-muted fs-6"><i class="fa-solid fa-circle-info me-1"></i> Saves off-canvas sidebar tagline, contacts, and showcase images.</span>
                    <button type="submit" class="btn btn-theme save-section-btn">
                        <i class="fa-solid fa-floppy-disk me-1"></i> Save Sidebar Drawer Info
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- 2. HERO BANNER SECTION -->
    <div class="tab-pane fade" id="tab-banner" role="tabpanel">
        <form class="section-form" action="{{ route('admin.home_sections.save') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="active_tab" value="banner">
            
            <div class="admin-card">
                <div class="admin-card-header">
                    <h5><i class="fa-solid fa-flag text-primary me-2"></i> Hero Banner Section Settings</h5>
                </div>
                <div class="admin-card-body">
                    <!-- Section Visibility Toggle -->
                    <div class="section-ctrl-bar">
                        <div>
                            <strong class="d-block">Section Visibility on Home Page</strong>
                            <small class="text-muted">Turn on or off the Hero Banner section completely.</small>
                        </div>
                        <div class="form-check form-switch m-0">
                            <input type="hidden" name="section_banner_enabled" value="0">
                            <input class="form-check-input section-toggle-switch" type="checkbox" name="section_banner_enabled" value="1" id="switch_banner" {{ ($settings['section_banner_enabled'] ?? '1') == '1' ? 'checked' : '' }}>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Banner Small Badge / Subtitle</label>
                            <input type="text" name="banner_badge" class="form-control" value="{{ $settings['banner_badge'] ?? 'Welcome to Innotech Medical Pvt Ltd' }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Video Source Type</label>
                            <select name="banner_video_type" id="bannerVideoTypeSelect" class="form-select">
                                <option value="url" {{ ($settings['banner_video_type'] ?? 'url') == 'url' ? 'selected' : '' }}>🔗 YouTube / External Video URL</option>
                                <option value="upload" {{ ($settings['banner_video_type'] ?? 'url') == 'upload' ? 'selected' : '' }}>📁 Upload Video File (MP4 / WebM)</option>
                            </select>
                        </div>

                        <!-- YouTube / External URL Box -->
                        <div class="col-12 mb-3 {{ ($settings['banner_video_type'] ?? 'url') == 'upload' ? 'd-none' : '' }}" id="bannerVideoUrlBox">
                            <label class="form-label fw-semibold"><i class="fa-brands fa-youtube text-danger me-1"></i> YouTube / Video URL</label>
                            <input type="text" name="banner_video_url" class="form-control" value="{{ $settings['banner_video_url'] ?? 'https://www.youtube.com/watch?v=d8w5SICzzxc' }}" placeholder="https://www.youtube.com/watch?v=...">
                            <small class="text-muted">Enter full YouTube, Vimeo, or web video stream link.</small>
                        </div>

                        <!-- Video File Upload Box -->
                        <div class="col-12 mb-3 {{ ($settings['banner_video_type'] ?? 'url') == 'upload' ? '' : 'd-none' }}" id="bannerVideoFileBox">
                            <label class="form-label fw-semibold"><i class="fa-solid fa-video text-primary me-1"></i> Upload Video File (MP4, WebM, OGG)</label>
                            <div class="p-3 border rounded-3 bg-light">
                                @if(!empty($settings['banner_video_file']))
                                    <div class="mb-2">
                                        <label class="d-block text-muted small mb-1">Current Active Video:</label>
                                        <video controls style="max-height: 140px; max-width: 100%; border-radius: 6px; background: #000;">
                                            <source src="{{ asset($settings['banner_video_file']) }}" type="video/mp4">
                                            Your browser does not support HTML5 video.
                                        </video>
                                    </div>
                                @endif
                                <input type="file" name="banner_video_file" class="form-control" accept="video/*,.mp4,.webm,.ogg,.mov">
                                <small class="text-muted">Supported formats: MP4, WebM, OGG, MOV. Recommended: MP4 (H.264).</small>
                            </div>
                        </div>

                        @php
                            $sliderImages = json_decode($settings['banner_slider_images'] ?? '[]', true) ?: [];
                        @endphp
                        <div class="col-12 mb-4">
                            <div class="p-3.5 border rounded-3 bg-white shadow-sm" style="border-left: 4px solid #0E63FF !important;">
                                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
                                    <label class="form-label fw-bold text-dark mb-0 fs-6">
                                        <i class="fa-solid fa-images text-primary me-1.5"></i> Banner Hero Slider Images (Multiple Images Autoplay)
                                    </label>
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-1.5 rounded-pill font-weight-semibold" id="sliderImagesCountBadge">
                                        {{ count($sliderImages) }} Slider {{ count($sliderImages) === 1 ? 'Image' : 'Images' }} Active
                                    </span>
                                </div>
                                <p class="text-muted small mb-3">
                                    Yahan multiple images ek sath select karke upload kar sakte hain. Website ke hero section mein right-side par ye images auto-play slider ke sath chalengi aur mouse le jaane par Left/Right navigation arrows aayenge.
                                </p>

                                <!-- Multiple Upload Input -->
                                <div class="mb-3 bg-light p-3 rounded-3 border">
                                    <label class="form-label small fw-bold text-dark mb-1">Upload New Slider Images (Multiple Allowed):</label>
                                    <input type="file" name="banner_slider_images[]" class="form-control" multiple accept="image/*,.jfif,.png,.jpg,.jpeg,.webp,.svg,.gif,.avif,.bmp">
                                    <small class="text-muted d-block mt-1">
                                        <i class="fa-solid fa-circle-info text-primary me-1"></i> Tip: Hold <strong>Ctrl</strong> (or <strong>Cmd</strong> on Mac) to select multiple images from your computer at once.
                                    </small>
                                </div>

                                <!-- Current Slider Images Gallery Preview -->
                                <div id="sliderImagesGalleryContainer">
                                    @if(count($sliderImages) > 0)
                                        <label class="form-label small fw-bold text-dark mb-2">Active Slider Images (Slide Order):</label>
                                        <div class="row g-3" id="activeSliderImagesRow">
                                            @foreach($sliderImages as $idx => $sImg)
                                                <div class="col-6 col-sm-4 col-md-3 col-lg-2 slider-image-item">
                                                    <div class="card h-100 border position-relative shadow-sm rounded-3 overflow-hidden">
                                                        <span class="position-absolute top-0 start-0 m-1.5 badge bg-dark bg-opacity-75 text-white" style="font-size: 10px; z-index: 2;">
                                                            #{{ $idx + 1 }}
                                                        </span>
                                                        <div class="d-flex align-items-center justify-content-center bg-light p-1" style="height: 110px;">
                                                            <img src="{{ asset($sImg) }}" alt="Slider Image" class="img-fluid rounded-2" style="max-height: 100px; max-width: 100%; object-fit: cover;">
                                                        </div>
                                                        <div class="card-footer bg-white p-1.5 border-top text-center">
                                                            <button type="button" 
                                                                    class="btn btn-xs btn-outline-danger w-100 py-1 d-flex align-items-center justify-content-center gap-1 rounded-2"
                                                                    onclick="deleteSliderImage('{{ $sImg }}', this)">
                                                                <i class="fa-solid fa-trash-can" style="font-size: 11px;"></i> <span style="font-size: 11px;">Delete</span>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="p-3 bg-light rounded-3 text-center border border-dashed" id="noSliderImagesNotice">
                                            <i class="fa-solid fa-photo-film text-muted fs-3 mb-1"></i>
                                            <p class="text-muted small mb-0">Abhi koi multiple slider image upload nahi hui. Default single banner image use ho rahi hai.</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Single Default Fallback Image -->
                        <div class="col-12 mb-3">
                            <label class="form-label fw-semibold text-muted small"><i class="fa-solid fa-image me-1"></i> Default / Single Fallback Banner Image</label>
                            <div class="d-flex align-items-center gap-3 p-2.5 border rounded-3 bg-light">
                                <img src="{{ asset($settings['banner_image'] ?? 'assets/img/banner/banner-01.png') }}" alt="Current Banner" class="border bg-white p-2 rounded-2" style="max-height: 60px; max-width: 100px; object-fit: contain;">
                                <div class="flex-grow-1">
                                    <input type="file" name="banner_image" class="form-control form-control-sm" accept="image/*,.jfif,.png,.jpg,.jpeg,.webp,.svg,.gif,.avif,.bmp">
                                    <small class="text-muted" style="font-size: 11px;">Fallback image used if no multiple slider images are active.</small>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 mb-3">
                            <label class="form-label">Main Headline</label>
                            <input type="text" name="banner_title" class="form-control" value="{{ $settings['banner_title'] ?? 'Innovating Health Care with Advance Technologies' }}">
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Description Paragraph</label>
                            <textarea name="banner_description" class="form-control" rows="3">{{ str_replace(['â€™', '’'], "'", $settings['banner_description'] ?? "Innotech Medical Pvt Ltd is a growing distributor of top-quality medical equipment across Pakistan. We are dedicated to bridging the gap between world-class medical innovation and Pakistan's healthcare sector.") }}</textarea>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Primary Button Text</label>
                            <input type="text" name="banner_btn_text" class="form-control" value="{{ $settings['banner_btn_text'] ?? 'Contact with Us' }}">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Primary Button Link</label>
                            <input type="text" name="banner_btn_link" class="form-control" value="{{ $settings['banner_btn_link'] ?? '/contact' }}">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Secondary Button Text</label>
                            <input type="text" name="banner_btn2_text" class="form-control" value="{{ $settings['banner_btn2_text'] ?? 'About us' }}">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Secondary Button Link</label>
                            <input type="text" name="banner_btn2_link" class="form-control" value="{{ $settings['banner_btn2_link'] ?? '/about' }}">
                        </div>

                        <div class="col-12 mt-3">
                            <h6 class="fw-bold mb-3"><i class="fa-solid fa-shapes text-primary me-2"></i> 3 Feature Highlight Boxes</h6>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Feature Box 1</label>
                            <input type="text" name="banner_feature_1" class="form-control" value="{{ $settings['banner_feature_1'] ?? '100% Customer Satisfaction' }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Feature Box 2</label>
                            <input type="text" name="banner_feature_2" class="form-control" value="{{ $settings['banner_feature_2'] ?? 'Help and Acess is Our Mission' }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Feature Box 3</label>
                            <input type="text" name="banner_feature_3" class="form-control" value="{{ $settings['banner_feature_3'] ?? '100% Quality Laboratory service' }}">
                        </div>
                    </div>
                </div>
                <div class="admin-card-header bg-light d-flex justify-content-between align-items-center">
                    <span class="text-muted fs-6"><i class="fa-solid fa-circle-info me-1"></i> Banner settings sync with hero layout.</span>
                    <button type="submit" class="btn btn-theme save-section-btn">
                        <i class="fa-solid fa-floppy-disk me-1"></i> Save Banner Section
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- 3. SERVICES SECTION & CRUD -->
    <div class="tab-pane fade" id="tab-services" role="tabpanel">
        <!-- Section Settings Form -->
        <form class="section-form mb-4" action="{{ route('admin.home_sections.save') }}" method="POST">
            @csrf
            <input type="hidden" name="active_tab" value="services">
            
            <div class="admin-card">
                <div class="admin-card-header">
                    <h5><i class="fa-solid fa-stethoscope text-primary me-2"></i> Services Section Header & Search</h5>
                </div>
                <div class="admin-card-body">
                    <div class="section-ctrl-bar">
                        <div>
                            <strong class="d-block">Section Visibility on Home Page</strong>
                            <small class="text-muted">Show or hide the entire Medical Services carousel.</small>
                        </div>
                        <div class="form-check form-switch m-0">
                            <input type="hidden" name="section_services_enabled" value="0">
                            <input class="form-check-input section-toggle-switch" type="checkbox" name="section_services_enabled" value="1" {{ ($settings['section_services_enabled'] ?? '1') == '1' ? 'checked' : '' }}>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Section Subtitle</label>
                            <input type="text" name="services_subtitle" class="form-control" value="{{ $settings['services_subtitle'] ?? 'our Services' }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Section Heading</label>
                            <input type="text" name="services_title" class="form-control" value="{{ $settings['services_title'] ?? 'Service Area' }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Search Input Placeholder</label>
                            <input type="text" name="services_search_placeholder" class="form-control" value="{{ $settings['services_search_placeholder'] ?? 'What are you looking for?' }}">
                        </div>
                    </div>
                </div>
                <div class="admin-card-header bg-light d-flex justify-content-between align-items-center">
                    <span class="text-muted fs-6"><i class="fa-solid fa-circle-info me-1"></i> Update section headers and titles.</span>
                    <button type="submit" class="btn btn-theme save-section-btn">
                        <i class="fa-solid fa-floppy-disk me-1"></i> Save Section Titles
                    </button>
                </div>
            </div>
        </form>

        <!-- Services Items List & CRUD -->
        <div class="admin-card">
            <div class="admin-card-header">
                <h5><i class="fa-solid fa-list-check text-primary me-2"></i> Medical Services Items ({{ $services->total() }})</h5>
                <a href="{{ route('admin.services.create') }}" class="btn btn-theme btn-sm">
                    <i class="fa-solid fa-plus me-1"></i> Add New Service
                </a>
            </div>
            <div class="table-responsive">
                <table class="table table-custom">
                    <thead>
                        <tr>
                            <th>Icon</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Order</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($services as $serv)
                            <tr>
                                <td><i class="{{ $serv->icon ?: 'flaticon-hemoglobin-test-meter' }} fs-4 text-primary"></i></td>
                                <td class="fw-bold">{{ $serv->title }}</td>
                                <td><span class="badge bg-light text-dark">{{ $serv->category ?: 'General' }}</span></td>
                                <td>{{ $serv->order }}</td>
                                <td>
                                    <button type="button" class="btn btn-sm py-1 px-3 rounded-pill fw-semibold item-ajax-toggle {{ $serv->is_active ? 'btn-success text-white' : 'btn-light text-muted border' }}" data-type="service" data-id="{{ $serv->id }}">
                                        {{ $serv->is_active ? 'Active' : 'Inactive' }}
                                    </button>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('admin.services.edit', $serv->id) }}" class="btn btn-sm btn-light text-primary me-1"><i class="fa-solid fa-pen"></i></a>
                                    <form action="{{ route('admin.services.destroy', $serv->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this service?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-light text-danger"><i class="fa-solid fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">No services added yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($services->hasPages())
                <div class="p-3 border-top d-flex justify-content-end">
                    {{ $services->appends(['tab' => 'services'])->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- 4. ABOUT US & EXPERIENCE SECTION -->
    <div class="tab-pane fade" id="tab-about" role="tabpanel">
        <form class="section-form" action="{{ route('admin.home_sections.save') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="active_tab" value="about">
            
            <!-- 1. Main Intro & Experience Counter -->
            <div class="admin-card mb-4">
                <div class="admin-card-header bg-light">
                    <h5><i class="fa-solid fa-building text-primary me-2"></i> 1. About Us Intro, Story & Experience Badge</h5>
                </div>
                <div class="admin-card-body p-4">
                    <div class="section-ctrl-bar mb-4">
                        <div>
                            <strong class="d-block">Section Visibility on Home Page</strong>
                            <small class="text-muted">Show or hide the About Us section and Experience badge on homepage.</small>
                        </div>
                        <div class="form-check form-switch m-0">
                            <input type="hidden" name="section_about_enabled" value="0">
                            <input class="form-check-input section-toggle-switch" type="checkbox" name="section_about_enabled" value="1" {{ ($settings['section_about_enabled'] ?? '1') == '1' ? 'checked' : '' }}>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Experience Count (Number for Animation)</label>
                            <input type="text" name="about_experience_years" class="form-control fw-bold text-success fs-5" value="{{ $settings['about_experience_years'] ?? '7' }}" placeholder="7">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Experience Subtext</label>
                            <input type="text" name="about_experience_label" class="form-control" value="{{ $settings['about_experience_label'] ?? 'Years of Experience' }}" placeholder="Years of Experience">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Section Badge / Subtitle</label>
                            <input type="text" name="about_badge" class="form-control" value="{{ $settings['about_badge'] ?? 'ABOUT INNOTECH MEDICAL' }}">
                        </div>

                        <div class="col-md-8 mb-3">
                            <label class="form-label fw-semibold">Main Heading</label>
                            <input type="text" name="about_heading" class="form-control" value="{{ $settings['about_heading'] ?? 'Innovating Healthcare with Advance Technologies' }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Mission Link Text</label>
                            <input type="text" name="about_link_text" class="form-control" value="{{ $settings['about_link_text'] ?? 'Read our Mission & Vision' }}">
                        </div>

                        <div class="col-12 mb-3">
                            <label class="form-label fw-semibold">Detailed Description</label>
                            <textarea name="about_description" class="form-control" rows="4">{{ $settings['about_description'] ?? 'At Innotech Medical, we are dedicated to bridging the gap between world-class medical innovation and Pakistan’s healthcare sector. As a leading provider of advanced biomedical technologies, diagnostic systems, and specialized clinical equipment, we empower healthcare institutions to deliver accurate diagnoses and superior patient care.' }}</textarea>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold"><i class="fa-solid fa-image text-primary me-1"></i> Main Large Photo</label>
                            <div class="d-flex align-items-center gap-3 p-2 border rounded bg-light">
                                <img src="{{ asset($settings['about_image'] ?? 'assets/img/about/about-bg-01.png') }}" alt="" style="width: 50px; height: 50px; object-fit: cover; border-radius: 6px;">
                                <div class="flex-grow-1">
                                    <input type="file" name="about_image" class="form-control form-control-sm" accept="image/*,.jfif,.png,.jpg,.jpeg,.webp,.svg,.gif,.avif,.bmp">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold"><i class="fa-solid fa-image text-primary me-1"></i> Shape Photo 1</label>
                            <div class="d-flex align-items-center gap-3 p-2 border rounded bg-light">
                                <img src="{{ asset($settings['about_shape_1'] ?? 'assets/img/about/about-bg-05.jpg') }}" alt="" style="width: 50px; height: 50px; object-fit: cover; border-radius: 6px;">
                                <div class="flex-grow-1">
                                    <input type="file" name="about_shape_1" class="form-control form-control-sm" accept="image/*,.jfif,.png,.jpg,.jpeg,.webp,.svg,.gif,.avif,.bmp">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold"><i class="fa-solid fa-image text-primary me-1"></i> Shape Photo 2</label>
                            <div class="d-flex align-items-center gap-3 p-2 border rounded bg-light">
                                <img src="{{ asset($settings['about_shape_2'] ?? 'assets/img/about/about-bg-06.jpg') }}" alt="" style="width: 50px; height: 50px; object-fit: cover; border-radius: 6px;">
                                <div class="flex-grow-1">
                                    <input type="file" name="about_shape_2" class="form-control form-control-sm" accept="image/*,.jfif,.png,.jpg,.jpeg,.webp,.svg,.gif,.avif,.bmp">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. Interactive Workflow Tabs (Process, Mission, Value) -->
            <div class="admin-card mb-4">
                <div class="admin-card-header bg-light">
                    <h5><i class="fa-solid fa-arrows-split-up-and-left text-primary me-2"></i> 2. Interactive Tabs: Our Process, Mission & Value</h5>
                </div>
                <div class="admin-card-body p-4">
                    <!-- Tab 1: Process -->
                    <h6 class="fw-bold text-dark border-bottom pb-2 mb-3"><i class="fa-solid fa-diagram-project text-success me-1"></i> Tab 1: Our Process (3 Steps)</h6>
                    <div class="row mb-4">
                        <div class="col-12 mb-3">
                            <label class="form-label fw-semibold">Process Top Description</label>
                            <input type="text" name="about_process_desc" class="form-control" value="{{ $settings['about_process_desc'] ?? 'Your trusted partner for medical equipment procurement, turnkey installations, and technical integration across Pakistan.' }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Step 1 Title</label>
                            <input type="text" name="about_step_1_title" class="form-control mb-2" value="{{ $settings['about_step_1_title'] ?? 'Consultation & Proposal' }}">
                            <label class="form-label small text-muted">Step 1 Description</label>
                            <textarea name="about_step_1_desc" class="form-control" rows="2">{{ $settings['about_step_1_desc'] ?? 'Understanding facility requirements to recommend compliant, cost-effective medical equipment solutions.' }}</textarea>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Step 2 Title</label>
                            <input type="text" name="about_step_2_title" class="form-control mb-2" value="{{ $settings['about_step_2_title'] ?? 'Seamless Deployment' }}">
                            <label class="form-label small text-muted">Step 2 Description</label>
                            <textarea name="about_step_2_desc" class="form-control" rows="2">{{ $settings['about_step_2_desc'] ?? 'Rapid procurement, physical installation, and precise site calibration by certified biomedical engineers.' }}</textarea>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Step 3 Title</label>
                            <input type="text" name="about_step_3_title" class="form-control mb-2" value="{{ $settings['about_step_3_title'] ?? 'Integration & Support' }}">
                            <label class="form-label small text-muted">Step 3 Description</label>
                            <textarea name="about_step_3_desc" class="form-control" rows="2">{{ $settings['about_step_3_desc'] ?? 'Comprehensive staff application training alongside 24/7 technical support and routine maintenance.' }}</textarea>
                        </div>
                    </div>

                    <!-- Tab 2: Mission -->
                    <h6 class="fw-bold text-dark border-bottom pb-2 mb-3"><i class="fa-solid fa-bullseye text-danger me-1"></i> Tab 2: Our Mission</h6>
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Mission Heading</label>
                            <input type="text" name="about_mission_title" class="form-control mb-2" value="{{ $settings['about_mission_title'] ?? 'Our Mission is to Give You Always the Best Clinical Results.' }}">
                            <label class="form-label fw-semibold">Mission Narrative</label>
                            <textarea name="about_mission_desc" class="form-control" rows="3">{{ $settings['about_mission_desc'] ?? 'To enhance the quality of healthcare across Pakistan by delivering state-of-the-art medical devices, advanced diagnostic technologies, and uncompromised technical support to hospitals and laboratories.' }}</textarea>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label fw-semibold">Mission Image 1</label>
                            <div class="d-flex align-items-center gap-2 p-2 border rounded bg-light mb-2">
                                <img src="{{ asset($settings['about_mission_img1'] ?? 'assets/img/tab/tab-thumb-03.jpg') }}" alt="" style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;">
                                <input type="file" name="about_mission_img1" class="form-control form-control-sm" accept="image/*,.jfif,.png,.jpg,.jpeg,.webp,.svg,.gif,.avif,.bmp">
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label fw-semibold">Mission Image 2</label>
                            <div class="d-flex align-items-center gap-2 p-2 border rounded bg-light mb-2">
                                <img src="{{ asset($settings['about_mission_img2'] ?? 'assets/img/tab/tab-thumb-04.jpg') }}" alt="" style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;">
                                <input type="file" name="about_mission_img2" class="form-control form-control-sm" accept="image/*,.jfif,.png,.jpg,.jpeg,.webp,.svg,.gif,.avif,.bmp">
                            </div>
                        </div>
                    </div>

                    <!-- Tab 3: Value -->
                    <h6 class="fw-bold text-dark border-bottom pb-2 mb-3"><i class="fa-solid fa-gem text-info me-1"></i> Tab 3: Our Value</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Value Heading</label>
                            <input type="text" name="about_value_title" class="form-control mb-2" value="{{ $settings['about_value_title'] ?? 'Trusted by Leading Clinical Facilities' }}">
                            <label class="form-label fw-semibold">Value Narrative</label>
                            <textarea name="about_value_desc" class="form-control" rows="3">{{ $settings['about_value_desc'] ?? 'To become Pakistan’s premier and most trusted B2B healthcare partner, driving innovation in biomedical engineering and empowering institutions with futuristic medical solutions.' }}</textarea>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label fw-semibold">Value Image 1</label>
                            <div class="d-flex align-items-center gap-2 p-2 border rounded bg-light mb-2">
                                <img src="{{ asset($settings['about_value_img1'] ?? 'assets/img/tab/tab-thumb-01.jpg') }}" alt="" style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;">
                                <input type="file" name="about_value_img1" class="form-control form-control-sm" accept="image/*,.jfif,.png,.jpg,.jpeg,.webp,.svg,.gif,.avif,.bmp">
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label fw-semibold">Value Image 2</label>
                            <div class="d-flex align-items-center gap-2 p-2 border rounded bg-light mb-2">
                                <img src="{{ asset($settings['about_value_img2'] ?? 'assets/img/tab/tab-thumb-02.jpg') }}" alt="" style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;">
                                <input type="file" name="about_value_img2" class="form-control form-control-sm" accept="image/*,.jfif,.png,.jpg,.jpeg,.webp,.svg,.gif,.avif,.bmp">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. Why Choose Us (4 Pillar Features) -->
            <div class="admin-card mb-4">
                <div class="admin-card-header bg-light">
                    <h5><i class="fa-solid fa-award text-primary me-2"></i> 3. Why Choose Us (4 Clinical Feature Pillars)</h5>
                </div>
                <div class="admin-card-body p-4">
                    <div class="row mb-3">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Section Subtitle</label>
                            <input type="text" name="about_choose_subtitle" class="form-control" value="{{ $settings['about_choose_subtitle'] ?? 'Our Specialists' }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Section Title</label>
                            <input type="text" name="about_choose_title" class="form-control" value="{{ $settings['about_choose_title'] ?? 'Why Choose Us' }}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Pillar 1 Title</label>
                            <input type="text" name="about_why_1_title" class="form-control mb-2" value="{{ $settings['about_why_1_title'] ?? 'Global Standards & Quality' }}">
                            <textarea name="about_why_1_desc" class="form-control" rows="2">{{ $settings['about_why_1_desc'] ?? 'Delivering FDA, CE, and ISO certified medical equipment from global principal brands, ensuring maximum clinical accuracy and patient safety.' }}</textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Pillar 2 Title</label>
                            <input type="text" name="about_why_2_title" class="form-control mb-2" value="{{ $settings['about_why_2_title'] ?? 'Swift Turnkey Delivery' }}">
                            <textarea name="about_why_2_desc" class="form-control" rows="2">{{ $settings['about_why_2_desc'] ?? 'Streamlined procurement and rapid installation, delivering complete healthcare projects with minimal turnaround time and total precision.' }}</textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Pillar 3 Title</label>
                            <input type="text" name="about_why_3_title" class="form-control mb-2" value="{{ $settings['about_why_3_title'] ?? '24/7 Emergency Support' }}">
                            <textarea name="about_why_3_desc" class="form-control" rows="2">{{ $settings['about_why_3_desc'] ?? 'Round-the-clock technical coverage and rapid dispatch troubleshooting to eliminate critical equipment downtime in ICUs and OTs.' }}</textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Pillar 4 Title</label>
                            <input type="text" name="about_why_4_title" class="form-control mb-2" value="{{ $settings['about_why_4_title'] ?? 'Certified Biomedical Experts' }}">
                            <textarea name="about_why_4_desc" class="form-control" rows="2">{{ $settings['about_why_4_desc'] ?? 'Backed by OEM-trained engineers executing precision calibration, complex repairs, and proactive maintenance to global standards.' }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="admin-card-header bg-light d-flex justify-content-between align-items-center p-3">
                    <span class="text-muted fs-6"><i class="fa-solid fa-circle-info me-1"></i> Saves all About Us page sections, workflows & images.</span>
                    <button type="submit" class="btn btn-theme save-section-btn px-4">
                        <i class="fa-solid fa-floppy-disk me-1"></i> Save About & Experience Content
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- 5. NUMERICAL COUNTERS SECTION -->
    <div class="tab-pane fade" id="tab-counters" role="tabpanel">
        <form class="section-form" action="{{ route('admin.home_sections.save') }}" method="POST">
            @csrf
            <input type="hidden" name="active_tab" value="counters">
            
            <div class="admin-card">
                <div class="admin-card-header">
                    <h5><i class="fa-solid fa-calculator text-primary me-2"></i> 4 Numerical Counters Settings</h5>
                </div>
                <div class="admin-card-body">
                    <div class="section-ctrl-bar">
                        <div>
                            <strong class="d-block">Section Visibility on Home Page</strong>
                            <small class="text-muted">Show or hide the 4 animated statistical counter cards.</small>
                        </div>
                        <div class="form-check form-switch m-0">
                            <input type="hidden" name="section_counter_enabled" value="0">
                            <input class="form-check-input section-toggle-switch" type="checkbox" name="section_counter_enabled" value="1" {{ ($settings['section_counter_enabled'] ?? '1') == '1' ? 'checked' : '' }}>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Counter 1 -->
                        <div class="col-md-6 col-xl-3 mb-3">
                            <div class="card-inner-box">
                                <h6 class="fw-bold text-primary mb-3"><i class="fa-solid fa-hashtag me-1"></i> Counter 1 (Blue)</h6>
                                <div class="mb-3">
                                    <label class="form-label">Number Value</label>
                                    <input type="number" name="counter_1_number" class="form-control fw-bold" value="{{ $settings['counter_1_number'] ?? '1492' }}">
                                </div>
                                <div>
                                    <label class="form-label">Label Description</label>
                                    <input type="text" name="counter_1_title" class="form-control" value="{{ $settings['counter_1_title'] ?? 'Laboratories in 100+ states' }}">
                                </div>
                            </div>
                        </div>

                        <!-- Counter 2 -->
                        <div class="col-md-6 col-xl-3 mb-3">
                            <div class="card-inner-box">
                                <h6 class="fw-bold text-danger mb-3"><i class="fa-solid fa-hashtag me-1"></i> Counter 2 (Pink)</h6>
                                <div class="mb-3">
                                    <label class="form-label">Number Value</label>
                                    <input type="number" name="counter_2_number" class="form-control fw-bold" value="{{ $settings['counter_2_number'] ?? '152' }}">
                                </div>
                                <div>
                                    <label class="form-label">Label Description</label>
                                    <input type="text" name="counter_2_title" class="form-control" value="{{ $settings['counter_2_title'] ?? 'Laboratory specialists' }}">
                                </div>
                            </div>
                        </div>

                        <!-- Counter 3 -->
                        <div class="col-md-6 col-xl-3 mb-3">
                            <div class="card-inner-box">
                                <h6 class="fw-bold text-info mb-3"><i class="fa-solid fa-hashtag me-1"></i> Counter 3 (Sky Blue)</h6>
                                <div class="mb-3">
                                    <label class="form-label">Number Value</label>
                                    <input type="number" name="counter_3_number" class="form-control fw-bold" value="{{ $settings['counter_3_number'] ?? '1022' }}">
                                </div>
                                <div>
                                    <label class="form-label">Label Description</label>
                                    <input type="text" name="counter_3_title" class="form-control" value="{{ $settings['counter_3_title'] ?? 'Material collection points' }}">
                                </div>
                            </div>
                        </div>

                        <!-- Counter 4 -->
                        <div class="col-md-6 col-xl-3 mb-3">
                            <div class="card-inner-box">
                                <h6 class="fw-bold text-success mb-3"><i class="fa-solid fa-hashtag me-1"></i> Counter 4 (Green)</h6>
                                <div class="mb-3">
                                    <label class="form-label">Number Value</label>
                                    <input type="number" name="counter_4_number" class="form-control fw-bold" value="{{ $settings['counter_4_number'] ?? '24332' }}">
                                </div>
                                <div>
                                    <label class="form-label">Label Description</label>
                                    <input type="text" name="counter_4_title" class="form-control" value="{{ $settings['counter_4_title'] ?? 'Patients diagnosed in 2022' }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="admin-card-header bg-light d-flex justify-content-between align-items-center">
                    <span class="text-muted fs-6"><i class="fa-solid fa-circle-info me-1"></i> Numbers animate with CounterUp.js.</span>
                    <button type="submit" class="btn btn-theme save-section-btn">
                        <i class="fa-solid fa-floppy-disk me-1"></i> Save Counter Numbers
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- 6. GALLERY SECTION & CRUD -->
    <div class="tab-pane fade" id="tab-gallery" role="tabpanel">
        <form class="section-form mb-4" action="{{ route('admin.home_sections.save') }}" method="POST">
            @csrf
            <input type="hidden" name="active_tab" value="gallery">
            
            <div class="admin-card">
                <div class="admin-card-header">
                    <h5><i class="fa-solid fa-images text-primary me-2"></i> Work Gallery Section Titles</h5>
                </div>
                <div class="admin-card-body">
                    <div class="section-ctrl-bar">
                        <div>
                            <strong class="d-block">Section Visibility on Home Page</strong>
                            <small class="text-muted">Show or hide the Work Gallery Swiper slider.</small>
                        </div>
                        <div class="form-check form-switch m-0">
                            <input type="hidden" name="section_gallery_enabled" value="0">
                            <input class="form-check-input section-toggle-switch" type="checkbox" name="section_gallery_enabled" value="1" {{ ($settings['section_gallery_enabled'] ?? '1') == '1' ? 'checked' : '' }}>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Section Subtitle</label>
                            <input type="text" name="gallery_subtitle" class="form-control" value="{{ $settings['gallery_subtitle'] ?? 'Work Gallery' }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Section Heading</label>
                            <input type="text" name="gallery_title" class="form-control" value="{{ $settings['gallery_title'] ?? 'INNOTECH Gallery' }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Explore Button Text</label>
                            <input type="text" name="gallery_btn_text" class="form-control" value="{{ $settings['gallery_btn_text'] ?? 'Explore More' }}">
                        </div>
                    </div>
                </div>
                <div class="admin-card-header bg-light d-flex justify-content-between align-items-center">
                    <span class="text-muted fs-6"><i class="fa-solid fa-circle-info me-1"></i> Updates gallery section headings.</span>
                    <button type="submit" class="btn btn-theme save-section-btn">
                        <i class="fa-solid fa-floppy-disk me-1"></i> Save Gallery Titles
                    </button>
                </div>
            </div>
        </form>

        <!-- Gallery Items CRUD -->
        <div class="admin-card">
            <div class="admin-card-header">
                <h5><i class="fa-solid fa-image text-primary me-2"></i> Gallery Items ({{ $galleryItems->total() }})</h5>
                <button type="button" class="btn btn-theme btn-sm" data-bs-toggle="modal" data-bs-target="#addGalleryModal">
                    <i class="fa-solid fa-plus me-1"></i> Add Gallery Item
                </button>
            </div>
            <div class="table-responsive">
                <table class="table table-custom">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Title</th>
                            <th>Category / Tag</th>
                            <th>Order</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($galleryItems as $item)
                            <tr>
                                <td>
                                    <img src="{{ asset($item->image) }}" alt="" style="width: 60px; height: 45px; object-fit: cover; border-radius: 6px;">
                                </td>
                                <td class="fw-bold">{{ $item->title }}</td>
                                <td><span class="badge bg-light text-primary">{{ $item->category ?: 'General' }}</span></td>
                                <td>{{ $item->order }}</td>
                                <td>
                                    <button type="button" class="btn btn-sm py-1 px-3 rounded-pill fw-semibold item-ajax-toggle {{ $item->is_active ? 'btn-success text-white' : 'btn-light text-muted border' }}" data-type="gallery" data-id="{{ $item->id }}">
                                        {{ $item->is_active ? 'Active' : 'Inactive' }}
                                    </button>
                                </td>
                                <td class="text-end">
                                    <button type="button" class="btn btn-sm btn-light text-primary me-1" data-bs-toggle="modal" data-bs-target="#editGalleryModal{{ $item->id }}"><i class="fa-solid fa-pen"></i></button>
                                    <form action="{{ route('admin.home_sections.gallery.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this gallery item?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-light text-danger"><i class="fa-solid fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">No gallery items added yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($galleryItems->hasPages())
                <div class="p-3 border-top d-flex justify-content-end">
                    {{ $galleryItems->appends(['tab' => 'gallery'])->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- 7. WHY CHOOSE US SECTION -->
    <div class="tab-pane fade" id="tab-choose" role="tabpanel">
        <form class="section-form" action="{{ route('admin.home_sections.save') }}" method="POST">
            @csrf
            <input type="hidden" name="active_tab" value="choose">
            
            <div class="admin-card">
                <div class="admin-card-header">
                    <h5><i class="fa-solid fa-award text-primary me-2"></i> Why Choose Us Section Configuration</h5>
                </div>
                <div class="admin-card-body">
                    <div class="section-ctrl-bar">
                        <div>
                            <strong class="d-block">Section Visibility on Home Page</strong>
                            <small class="text-muted">Show or hide the dark navy Why Choose Us section.</small>
                        </div>
                        <div class="form-check form-switch m-0">
                            <input type="hidden" name="section_choose_enabled" value="0">
                            <input class="form-check-input section-toggle-switch" type="checkbox" name="section_choose_enabled" value="1" {{ ($settings['section_choose_enabled'] ?? '1') == '1' ? 'checked' : '' }}>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Section Subtitle</label>
                            <input type="text" name="choose_subtitle" class="form-control" value="{{ $settings['choose_subtitle'] ?? 'Our Specialists' }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Section Heading</label>
                            <input type="text" name="choose_title" class="form-control" value="{{ $settings['choose_title'] ?? 'Why Choose Us' }}">
                        </div>

                        <!-- Card 1 -->
                        <div class="col-md-6 mb-3">
                            <div class="card-inner-box">
                                <h6 class="fw-bold text-primary mb-2">Feature Card 1 (Microscope Icon)</h6>
                                <div class="mb-2">
                                    <label class="form-label">Card 1 Title</label>
                                    <input type="text" name="choose_card1_title" class="form-control" value="{{ $settings['choose_card1_title'] ?? 'Global Standards & Quality' }}">
                                </div>
                                <div>
                                    <label class="form-label">Card 1 Description</label>
                                    <textarea name="choose_card1_desc" class="form-control" rows="2">{{ $settings['choose_card1_desc'] ?? 'Delivering FDA, CE, and ISO certified medical equipment from global principal brands, ensuring maximum clinical accuracy and patient safety.' }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Card 2 -->
                        <div class="col-md-6 mb-3">
                            <div class="card-inner-box">
                                <h6 class="fw-bold text-danger mb-2">Feature Card 2 (Thinking Icon)</h6>
                                <div class="mb-2">
                                    <label class="form-label">Card 2 Title</label>
                                    <input type="text" name="choose_card2_title" class="form-control" value="{{ $settings['choose_card2_title'] ?? 'Swift Turnkey Delivery' }}">
                                </div>
                                <div>
                                    <label class="form-label">Card 2 Description</label>
                                    <textarea name="choose_card2_desc" class="form-control" rows="2">{{ $settings['choose_card2_desc'] ?? 'Streamlined procurement and rapid installation, delivering complete healthcare projects with minimal turnaround time and total precision.' }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Card 3 -->
                        <div class="col-md-6 mb-3">
                            <div class="card-inner-box">
                                <h6 class="fw-bold text-success mb-2">Feature Card 3 (24 Hours Icon)</h6>
                                <div class="mb-2">
                                    <label class="form-label">Card 3 Title</label>
                                    <input type="text" name="choose_card3_title" class="form-control" value="{{ $settings['choose_card3_title'] ?? '24/7 Emergency Support' }}">
                                </div>
                                <div>
                                    <label class="form-label">Card 3 Description</label>
                                    <textarea name="choose_card3_desc" class="form-control" rows="2">{{ $settings['choose_card3_desc'] ?? 'Round-the-clock technical coverage and rapid dispatch troubleshooting to eliminate critical equipment downtime in ICUs and OTs.' }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Card 4 -->
                        <div class="col-md-6 mb-3">
                            <div class="card-inner-box">
                                <h6 class="fw-bold text-info mb-2">Feature Card 4 (Team Icon)</h6>
                                <div class="mb-2">
                                    <label class="form-label">Card 4 Title</label>
                                    <input type="text" name="choose_card4_title" class="form-control" value="{{ $settings['choose_card4_title'] ?? 'Certified Biomedical Experts' }}">
                                </div>
                                <div>
                                    <label class="form-label">Card 4 Description</label>
                                    <textarea name="choose_card4_desc" class="form-control" rows="2">{{ $settings['choose_card4_desc'] ?? 'Backed by OEM-trained engineers executing precision calibration, complex repairs, and proactive maintenance to global standards.' }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Bottom Option Text</label>
                            <input type="text" name="choose_bottom_text" class="form-control" value="{{ $settings['choose_bottom_text'] ?? 'Scientific Research Laboratories:' }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Bottom Option Link Button</label>
                            <input type="text" name="choose_bottom_btn_text" class="form-control" value="{{ $settings['choose_bottom_btn_text'] ?? 'Contact Us' }}">
                        </div>
                    </div>
                </div>
                <div class="admin-card-header bg-light d-flex justify-content-between align-items-center">
                    <span class="text-muted fs-6"><i class="fa-solid fa-circle-info me-1"></i> Customizes all 4 feature blocks.</span>
                    <button type="submit" class="btn btn-theme save-section-btn">
                        <i class="fa-solid fa-floppy-disk me-1"></i> Save Why Choose Us
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- 8. APPOINTMENT / CONTACT SECTION -->
    <div class="tab-pane fade" id="tab-appointment" role="tabpanel">
        <form class="section-form" action="{{ route('admin.home_sections.save') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="active_tab" value="appointment">
            
            <div class="admin-card">
                <div class="admin-card-header">
                    <h5><i class="fa-solid fa-calendar-check text-primary me-2"></i> Appointment & Lead Form Section</h5>
                </div>
                <div class="admin-card-body">
                    <div class="section-ctrl-bar">
                        <div>
                            <strong class="d-block">Section Visibility on Home Page</strong>
                            <small class="text-muted">Show or hide the Appointment Booking banner and contact form.</small>
                        </div>
                        <div class="form-check form-switch m-0">
                            <input type="hidden" name="section_appointment_enabled" value="0">
                            <input class="form-check-input section-toggle-switch" type="checkbox" name="section_appointment_enabled" value="1" {{ ($settings['section_appointment_enabled'] ?? '1') == '1' ? 'checked' : '' }}>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 mb-3">
                            <label class="form-label fw-bold"><i class="fa-solid fa-image text-primary me-1"></i> Appointment Left Side Image</label>
                            <div class="d-flex align-items-center gap-3 p-3 border rounded-3 bg-light">
                                <img src="{{ asset($settings['appointment_image'] ?? 'assets/img/banner/appoinment-01.jpg') }}" alt="Current Appointment" class="border bg-white p-2 rounded-2" style="max-height: 80px; max-width: 120px; object-fit: contain;">
                                <div class="flex-grow-1">
                                    <input type="file" name="appointment_image" class="form-control" accept="image/*,.jfif,.png,.jpg,.jpeg,.webp,.svg,.gif,.avif,.bmp">
                                    <small class="text-muted">Upload doctor / appointment banner photo (e.g. 600x600px).</small>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Form Title</label>
                            <input type="text" name="appointment_title" class="form-control" value="{{ $settings['appointment_title'] ?? 'GET IN TOUCH WITH US' }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Emergency Service Phone Number</label>
                            <input type="text" name="appointment_phone" class="form-control" value="{{ $settings['appointment_phone'] ?? '+92 331 6699992' }}">
                        </div>
                    </div>
                </div>
                <div class="admin-card-header bg-light d-flex justify-content-between align-items-center">
                    <span class="text-muted fs-6"><i class="fa-solid fa-circle-info me-1"></i> Saves appointment form titles.</span>
                    <button type="submit" class="btn btn-theme save-section-btn">
                        <i class="fa-solid fa-floppy-disk me-1"></i> Save Appointment Section
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- 9. MEET SPECIALISTS / TEAM SECTION & CRUD -->
    <div class="tab-pane fade" id="tab-team" role="tabpanel">
        <form class="section-form mb-4" action="{{ route('admin.home_sections.save') }}" method="POST">
            @csrf
            <input type="hidden" name="active_tab" value="team">
            
            <div class="admin-card">
                <div class="admin-card-header">
                    <h5><i class="fa-solid fa-user-doctor text-primary me-2"></i> Meet Specialists Section Titles</h5>
                </div>
                <div class="admin-card-body">
                    <div class="section-ctrl-bar">
                        <div>
                            <strong class="d-block">Section Visibility on Home Page</strong>
                            <small class="text-muted">Show or hide the Team Specialists carousel.</small>
                        </div>
                        <div class="form-check form-switch m-0">
                            <input type="hidden" name="section_team_enabled" value="0">
                            <input class="form-check-input section-toggle-switch" type="checkbox" name="section_team_enabled" value="1" {{ ($settings['section_team_enabled'] ?? '1') == '1' ? 'checked' : '' }}>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Section Subtitle</label>
                            <input type="text" name="team_subtitle" class="form-control" value="{{ $settings['team_subtitle'] ?? 'Our Team' }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Section Heading</label>
                            <input type="text" name="team_title" class="form-control" value="{{ $settings['team_title'] ?? 'Meet Specialist' }}">
                        </div>
                    </div>
                </div>
                <div class="admin-card-header bg-light d-flex justify-content-between align-items-center">
                    <span class="text-muted fs-6"><i class="fa-solid fa-circle-info me-1"></i> Saves team section titles.</span>
                    <button type="submit" class="btn btn-theme save-section-btn">
                        <i class="fa-solid fa-floppy-disk me-1"></i> Save Team Titles
                    </button>
                </div>
            </div>
        </form>

        <!-- Team Members List & CRUD -->
        <div class="admin-card">
            <div class="admin-card-header">
                <h5><i class="fa-solid fa-users text-primary me-2"></i> Specialist Doctors & Engineers ({{ $teamMembers->total() }})</h5>
                <button type="button" class="btn btn-theme btn-sm" data-bs-toggle="modal" data-bs-target="#addTeamModal">
                    <i class="fa-solid fa-plus me-1"></i> Add Specialist Member
                </button>
            </div>
            <div class="table-responsive">
                <table class="table table-custom">
                    <thead>
                        <tr>
                            <th>Photo</th>
                            <th>Name</th>
                            <th>Specialization / Role</th>
                            <th>Order</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($teamMembers as $member)
                            <tr>
                                <td>
                                    <img src="{{ asset($member->image ?: 'assets/img/team/team-thumb-01.jpg') }}" alt="" style="width: 45px; height: 45px; object-fit: cover; border-radius: 50%;">
                                </td>
                                <td class="fw-bold">{{ $member->name }}</td>
                                <td><span class="badge bg-light text-dark">{{ $member->designation }}</span></td>
                                <td>{{ $member->order }}</td>
                                <td>
                                    <button type="button" class="btn btn-sm py-1 px-3 rounded-pill fw-semibold item-ajax-toggle {{ $member->is_active ? 'btn-success text-white' : 'btn-light text-muted border' }}" data-type="team" data-id="{{ $member->id }}">
                                        {{ $member->is_active ? 'Active' : 'Inactive' }}
                                    </button>
                                </td>
                                <td class="text-end">
                                    <button type="button" class="btn btn-sm btn-light text-primary me-1" data-bs-toggle="modal" data-bs-target="#editTeamModal{{ $member->id }}"><i class="fa-solid fa-pen"></i></button>
                                    <form action="{{ route('admin.home_sections.team.destroy', $member->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this team member?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-light text-danger"><i class="fa-solid fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">No team members added yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($teamMembers->hasPages())
                <div class="p-3 border-top d-flex justify-content-end">
                    {{ $teamMembers->appends(['tab' => 'team'])->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- 10. TESTIMONIALS SECTION & CRUD -->
    <div class="tab-pane fade" id="tab-testimonials" role="tabpanel">
        <form class="section-form mb-4" action="{{ route('admin.home_sections.save') }}" method="POST">
            @csrf
            <input type="hidden" name="active_tab" value="testimonials">
            
            <div class="admin-card">
                <div class="admin-card-header">
                    <h5><i class="fa-solid fa-comments text-primary me-2"></i> Testimonials Section Titles</h5>
                </div>
                <div class="admin-card-body">
                    <div class="section-ctrl-bar">
                        <div>
                            <strong class="d-block">Section Visibility on Home Page</strong>
                            <small class="text-muted">Show or hide the Customer Feedback Testimonials slider.</small>
                        </div>
                        <div class="form-check form-switch m-0">
                            <input type="hidden" name="section_testimonial_enabled" value="0">
                            <input class="form-check-input section-toggle-switch" type="checkbox" name="section_testimonial_enabled" value="1" {{ ($settings['section_testimonial_enabled'] ?? '1') == '1' ? 'checked' : '' }}>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Section Subtitle</label>
                            <input type="text" name="testimonial_subtitle" class="form-control" value="{{ $settings['testimonial_subtitle'] ?? 'Testimonial' }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Section Heading</label>
                            <input type="text" name="testimonial_title" class="form-control" value="{{ $settings['testimonial_title'] ?? 'Customer Feedback' }}">
                        </div>
                    </div>
                </div>
                <div class="admin-card-header bg-light d-flex justify-content-between align-items-center">
                    <span class="text-muted fs-6"><i class="fa-solid fa-circle-info me-1"></i> Saves testimonial headers.</span>
                    <button type="submit" class="btn btn-theme save-section-btn">
                        <i class="fa-solid fa-floppy-disk me-1"></i> Save Testimonial Titles
                    </button>
                </div>
            </div>
        </form>

        <!-- Testimonials List & CRUD -->
        <div class="admin-card">
            <div class="admin-card-header">
                <h5><i class="fa-solid fa-comment-dots text-primary me-2"></i> Client Reviews ({{ $testimonials->total() }})</h5>
                <button type="button" class="btn btn-theme btn-sm" data-bs-toggle="modal" data-bs-target="#addTestimonialModal">
                    <i class="fa-solid fa-plus me-1"></i> Add Testimonial Review
                </button>
            </div>
            <div class="table-responsive">
                <table class="table table-custom">
                    <thead>
                        <tr>
                            <th>Avatar</th>
                            <th>Client Name</th>
                            <th>Designation / Hospital</th>
                            <th>Rating</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($testimonials as $t)
                            <tr>
                                <td>
                                    <img src="{{ asset($t->avatar ?: 'assets/img/icon/testi-ava-01.jpg') }}" alt="" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
                                </td>
                                <td class="fw-bold">{{ $t->name }}</td>
                                <td>{{ $t->designation }} {{ $t->hospital ? '('.$t->hospital.')' : '' }}</td>
                                <td>
                                    <span class="text-warning">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fa-{{ $i <= ($t->rating ?: 5) ? 'solid' : 'regular' }} fa-star" style="font-size: 11px;"></i>
                                        @endfor
                                    </span>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm py-1 px-3 rounded-pill fw-semibold item-ajax-toggle {{ $t->is_active ? 'btn-success text-white' : 'btn-light text-muted border' }}" data-type="testimonial" data-id="{{ $t->id }}">
                                        {{ $t->is_active ? 'Active' : 'Inactive' }}
                                    </button>
                                </td>
                                <td class="text-end">
                                    <button type="button" class="btn btn-sm btn-light text-primary me-1" data-bs-toggle="modal" data-bs-target="#editTestimonialModal{{ $t->id }}"><i class="fa-solid fa-pen"></i></button>
                                    <form action="{{ route('admin.home_sections.testimonials.destroy', $t->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this review?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-light text-danger"><i class="fa-solid fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">No testimonials added yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($testimonials->hasPages())
                <div class="p-3 border-top d-flex justify-content-end">
                    {{ $testimonials->appends(['tab' => 'testimonials'])->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- 11. BRAND PARTNERS SECTION & CRUD -->
    <div class="tab-pane fade" id="tab-brands" role="tabpanel">
        <form class="section-form mb-4" action="{{ route('admin.home_sections.save') }}" method="POST">
            @csrf
            <input type="hidden" name="active_tab" value="brands">
            
            <div class="admin-card">
                <div class="admin-card-header">
                    <h5><i class="fa-solid fa-handshake text-primary me-2"></i> Brand Partners Carousel Settings</h5>
                </div>
                <div class="admin-card-body">
                    <div class="section-ctrl-bar">
                        <div>
                            <strong class="d-block">Section Visibility on Home Page</strong>
                            <small class="text-muted">Show or hide the Brand Partners logo carousel.</small>
                        </div>
                        <div class="form-check form-switch m-0">
                            <input type="hidden" name="section_brand_enabled" value="0">
                            <input class="form-check-input section-toggle-switch" type="checkbox" name="section_brand_enabled" value="1" {{ ($settings['section_brand_enabled'] ?? '1') == '1' ? 'checked' : '' }}>
                        </div>
                    </div>
                </div>
                <div class="admin-card-header bg-light d-flex justify-content-between align-items-center">
                    <span class="text-muted fs-6"><i class="fa-solid fa-circle-info me-1"></i> Toggles brand partners slider on/off.</span>
                    <button type="submit" class="btn btn-theme save-section-btn">
                        <i class="fa-solid fa-floppy-disk me-1"></i> Save Brands Visibility
                    </button>
                </div>
            </div>
        </form>

        <!-- Brands List & CRUD -->
        <div class="admin-card">
            <div class="admin-card-header">
                <h5><i class="fa-solid fa-certificate text-primary me-2"></i> Partner Principal Logos ({{ $partners->total() }})</h5>
                <button type="button" class="btn btn-theme btn-sm" data-bs-toggle="modal" data-bs-target="#addPartnerModal">
                    <i class="fa-solid fa-plus me-1"></i> Add Brand Partner
                </button>
            </div>
            <div class="table-responsive">
                <table class="table table-custom">
                    <thead>
                        <tr>
                            <th>Logo</th>
                            <th>Partner Name</th>
                            <th>Website Link</th>
                            <th>Order</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($partners as $p)
                            <tr>
                                <td>
                                    <div class="p-1 bg-light rounded border d-inline-block">
                                        <img src="{{ asset($p->logo) }}" alt="" style="max-height: 30px; max-width: 90px; object-fit: contain;">
                                    </div>
                                </td>
                                <td class="fw-bold">{{ $p->name }}</td>
                                <td><a href="{{ $p->url }}" target="_blank" class="text-primary">{{ $p->url ?: 'None' }}</a></td>
                                <td>{{ $p->order }}</td>
                                <td>
                                    <button type="button" class="btn btn-sm py-1 px-3 rounded-pill fw-semibold item-ajax-toggle {{ $p->is_active ? 'btn-success text-white' : 'btn-light text-muted border' }}" data-type="partner" data-id="{{ $p->id }}">
                                        {{ $p->is_active ? 'Active' : 'Inactive' }}
                                    </button>
                                </td>
                                <td class="text-end">
                                    <button type="button" class="btn btn-sm btn-light text-primary me-1" data-bs-toggle="modal" data-bs-target="#editPartnerModal{{ $p->id }}"><i class="fa-solid fa-pen"></i></button>
                                    <form action="{{ route('admin.home_sections.partners.destroy', $p->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this partner?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-light text-danger"><i class="fa-solid fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">No partners added yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($partners->hasPages())
                <div class="p-3 border-top d-flex justify-content-end">
                    {{ $partners->appends(['tab' => 'brands'])->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- 12. CTA CALL BAR SECTION -->
    <div class="tab-pane fade" id="tab-cta" role="tabpanel">
        <form class="section-form" action="{{ route('admin.home_sections.save') }}" method="POST">
            @csrf
            <input type="hidden" name="active_tab" value="cta">
            
            <div class="admin-card">
                <div class="admin-card-header">
                    <h5><i class="fa-solid fa-phone-volume text-primary me-2"></i> Call-To-Action (CTA) Banner</h5>
                </div>
                <div class="admin-card-body">
                    <div class="section-ctrl-bar">
                        <div>
                            <strong class="d-block">Section Visibility on Home Page</strong>
                            <small class="text-muted">Show or hide the Call-To-Action highlight strip.</small>
                        </div>
                        <div class="form-check form-switch m-0">
                            <input type="hidden" name="section_cta_enabled" value="0">
                            <input class="form-check-input section-toggle-switch" type="checkbox" name="section_cta_enabled" value="1" {{ ($settings['section_cta_enabled'] ?? '1') == '1' ? 'checked' : '' }}>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">CTA Headline</label>
                            <input type="text" name="cta_title" class="form-control" value="{{ $settings['cta_title'] ?? 'Looking for a best lebatory Service' }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">CTA Direct Phone Number</label>
                            <input type="text" name="cta_phone" class="form-control" value="{{ $settings['cta_phone'] ?? '+92 331 6699992' }}">
                        </div>
                    </div>
                </div>
                <div class="admin-card-header bg-light d-flex justify-content-between align-items-center">
                    <span class="text-muted fs-6"><i class="fa-solid fa-circle-info me-1"></i> Saves CTA headline and direct call number.</span>
                    <button type="submit" class="btn btn-theme save-section-btn">
                        <i class="fa-solid fa-floppy-disk me-1"></i> Save CTA Section
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- 13. BLOG & ARTICLES SECTION & CRUD -->
    <div class="tab-pane fade" id="tab-blog" role="tabpanel">
        <form class="section-form mb-4" action="{{ route('admin.home_sections.save') }}" method="POST">
            @csrf
            <input type="hidden" name="active_tab" value="blog">
            
            <div class="admin-card">
                <div class="admin-card-header">
                    <h5><i class="fa-solid fa-newspaper text-primary me-2"></i> Blog & Research Articles Section Titles</h5>
                </div>
                <div class="admin-card-body">
                    <div class="section-ctrl-bar">
                        <div>
                            <strong class="d-block">Section Visibility on Home Page</strong>
                            <small class="text-muted">Show or hide the Latest News & Research slider.</small>
                        </div>
                        <div class="form-check form-switch m-0">
                            <input type="hidden" name="section_blog_enabled" value="0">
                            <input class="form-check-input section-toggle-switch" type="checkbox" name="section_blog_enabled" value="1" {{ ($settings['section_blog_enabled'] ?? '1') == '1' ? 'checked' : '' }}>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Section Subtitle</label>
                            <input type="text" name="blog_subtitle" class="form-control" value="{{ $settings['blog_subtitle'] ?? 'Waht’s New' }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Section Heading</label>
                            <input type="text" name="blog_title" class="form-control" value="{{ $settings['blog_title'] ?? 'Blog & Article' }}">
                        </div>
                    </div>
                </div>
                <div class="admin-card-header bg-light d-flex justify-content-between align-items-center">
                    <span class="text-muted fs-6"><i class="fa-solid fa-circle-info me-1"></i> Saves blog section title.</span>
                    <button type="submit" class="btn btn-theme save-section-btn">
                        <i class="fa-solid fa-floppy-disk me-1"></i> Save Blog Titles
                    </button>
                </div>
            </div>
        </form>

        <!-- Blogs List & CRUD -->
        <div class="admin-card">
            <div class="admin-card-header">
                <h5><i class="fa-solid fa-newspaper text-primary me-2"></i> Published Articles ({{ $blogs->total() }})</h5>
                <a href="{{ route('admin.blogs.create') }}" class="btn btn-theme btn-sm">
                    <i class="fa-solid fa-plus me-1"></i> Write New Article
                </a>
            </div>
            <div class="table-responsive">
                <table class="table table-custom">
                    <thead>
                        <tr>
                            <th>Thumbnail</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Author</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($blogs as $b)
                            <tr>
                                <td>
                                    <img src="{{ asset($b->image ?: 'assets/img/blog/blog-thumb-01.jpg') }}" alt="" style="width: 50px; height: 35px; object-fit: cover; border-radius: 4px;">
                                </td>
                                <td class="fw-bold">{{ Str::limit($b->title, 45) }}</td>
                                <td><span class="badge bg-light text-dark">{{ $b->category }}</span></td>
                                <td>{{ $b->author }}</td>
                                <td>
                                    <button type="button" class="btn btn-sm py-1 px-3 rounded-pill fw-semibold item-ajax-toggle {{ $b->is_published ? 'btn-success text-white' : 'btn-light text-muted border' }}" data-type="blog" data-id="{{ $b->id }}">
                                        {{ $b->is_published ? 'Published' : 'Draft' }}
                                    </button>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('admin.blogs.edit', $b->id) }}" class="btn btn-sm btn-light text-primary me-1"><i class="fa-solid fa-pen"></i></a>
                                    <form action="{{ route('admin.blogs.destroy', $b->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this blog post?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-light text-danger"><i class="fa-solid fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">No blog articles found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($blogs->hasPages())
                <div class="p-3 border-top d-flex justify-content-end">
                    {{ $blogs->appends(['tab' => 'blog'])->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- 14. FOOTER SECTION -->
    <div class="tab-pane fade" id="tab-footer" role="tabpanel">
        <form class="section-form" action="{{ route('admin.home_sections.save') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="active_tab" value="footer">
            
            <div class="admin-card">
                <div class="admin-card-header">
                    <h5><i class="fa-solid fa-shoe-prints text-primary me-2"></i> Website Footer Configuration</h5>
                </div>
                <div class="admin-card-body">
                    <div class="section-ctrl-bar">
                        <div>
                            <strong class="d-block">Section Visibility on Home Page</strong>
                            <small class="text-muted">Show or hide the bottom footer area.</small>
                        </div>
                        <div class="form-check form-switch m-0">
                            <input type="hidden" name="section_footer_enabled" value="0">
                            <input class="form-check-input section-toggle-switch" type="checkbox" name="section_footer_enabled" value="1" {{ ($settings['section_footer_enabled'] ?? '1') == '1' ? 'checked' : '' }}>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 mb-3">
                            <label class="form-label fw-bold"><i class="fa-solid fa-image text-primary me-1"></i> Footer Logo (Optional - Defaults to Main Logo)</label>
                            <div class="d-flex align-items-center gap-3 p-3 border rounded-3 bg-light">
                                <img src="{{ asset($settings['footer_logo'] ?? $settings['logo_path'] ?? 'assets/img/logo/logo.png') }}" alt="Current Footer Logo" class="border bg-white p-2 rounded-2" style="max-height: 50px; max-width: 180px; object-fit: contain;">
                                <div class="flex-grow-1">
                                    <input type="file" name="footer_logo" class="form-control" accept="image/*,.jfif,.png,.jpg,.jpeg,.webp,.svg,.gif,.avif,.bmp">
                                    <small class="text-muted">Recommended: White / transparent PNG logo for dark footer background.</small>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 mb-3">
                            <label class="form-label">Footer Short About Summary</label>
                            <textarea name="footer_about" class="form-control" rows="3">{{ $settings['footer_about'] ?? 'Innotech Medical Pvt Ltd is Growing distributor of top-quality medical equipment across Pakistan At Innotech Medical.' }}</textarea>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Copyright Text</label>
                            <input type="text" name="copyright_text" class="form-control" value="{{ $settings['copyright_text'] ?? '© Copyright ©2026 - 2027 INNOTECH MEDICAL Pvt Ltd. All Rights Reserved' }}">
                        </div>

                        <div class="col-12 mt-2">
                            <h6 class="fw-bold mb-3"><i class="fa-solid fa-share-nodes text-primary me-2"></i> Social Media Links</h6>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label"><i class="fa-brands fa-youtube text-danger me-1"></i> YouTube URL</label>
                            <input type="text" name="youtube_url" class="form-control" value="{{ $settings['youtube_url'] ?? 'https://youtube.com' }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label"><i class="fa-brands fa-twitter text-info me-1"></i> Twitter / X URL</label>
                            <input type="text" name="twitter_url" class="form-control" value="{{ $settings['twitter_url'] ?? 'https://twitter.com' }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label"><i class="fa-brands fa-facebook-f text-primary me-1"></i> Facebook URL</label>
                            <input type="text" name="facebook_url" class="form-control" value="{{ $settings['facebook_url'] ?? 'https://facebook.com' }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label"><i class="fa-brands fa-instagram text-danger me-1"></i> Instagram URL</label>
                            <input type="text" name="instagram_url" class="form-control" value="{{ $settings['instagram_url'] ?? ($settings['linkedin_url'] ?? 'https://instagram.com') }}">
                        </div>
                    </div>
                </div>
                <div class="admin-card-header bg-light d-flex justify-content-between align-items-center">
                    <span class="text-muted fs-6"><i class="fa-solid fa-circle-info me-1"></i> Saves footer description and social handles.</span>
                    <button type="submit" class="btn btn-theme save-section-btn">
                        <i class="fa-solid fa-floppy-disk me-1"></i> Save Footer Section
                    </button>
                </div>
            </div>
        </form>
    </div>

</div>

<!-- Sticky Floating Save Bar -->
<div class="save-sticky-bar d-none" id="globalSaveBar">
    <div class="d-flex align-items-center gap-2">
        <span class="badge bg-warning text-dark"><i class="fa-solid fa-circle-exclamation me-1"></i> Unsaved Changes</span>
        <span class="text-white-50 fs-6">You have modified section settings that have not been saved yet.</span>
    </div>
    <div class="d-flex gap-2">
        <button type="button" class="btn btn-outline-light btn-sm" id="discardChangesBtn">
            <i class="fa-solid fa-rotate-left me-1"></i> Discard
        </button>
        <button type="button" class="btn btn-primary btn-sm fw-bold px-3" id="stickySaveAllBtn">
            <i class="fa-solid fa-floppy-disks me-1"></i> Save All Changes
        </button>
    </div>
</div>

<!-- Add Gallery Modal -->
<div class="modal fade" id="addGalleryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form action="{{ route('admin.home_sections.gallery.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold"><i class="fa-solid fa-plus text-primary me-2"></i> Add Gallery Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" name="title" class="form-control" required placeholder="e.g. COVID ANALYSIS">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Category / Tag</label>
                        <input type="text" name="category" class="form-control" placeholder="e.g. Radiologist">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Upload Image</label>
                        <input type="file" name="image" class="form-control" required accept="image/*,.jfif,.png,.jpg,.jpeg,.webp,.svg,.gif,.avif,.bmp">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Sort Order</label>
                        <input type="number" name="order" class="form-control" value="0">
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" checked id="galleryActive">
                        <label class="form-check-label" for="galleryActive">Active Status</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-theme">Add Item</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Team Member Modal -->
<div class="modal fade" id="addTeamModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow">
            <form action="{{ route('admin.home_sections.team.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold"><i class="fa-solid fa-plus text-primary me-2"></i> Add Specialist Doctor / Biomedical Engineer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" required placeholder="e.g. Cameron Williamson">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">URL Slug (auto-generated if empty)</label>
                            <input type="text" name="slug" class="form-control" placeholder="e.g. cameron-williamson">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Designation / Role <span class="text-danger">*</span></label>
                            <input type="text" name="designation" class="form-control" required placeholder="e.g. Chief Biomedical Engineer">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Area of Expertise</label>
                            <input type="text" name="expertise" class="form-control" placeholder="e.g. Diagnostic & Radiology Systems">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Experience</label>
                            <input type="text" name="experience" class="form-control" placeholder="e.g. 15+ Years">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Email Address</label>
                            <input type="email" name="email" class="form-control" placeholder="cameron@innotechmedical.com">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Contact Phone</label>
                            <input type="text" name="phone" class="form-control" placeholder="+92 331 6699992">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Photo Upload</label>
                            <input type="file" name="image" class="form-control" accept="image/*,.jfif,.png,.jpg,.jpeg,.webp,.svg,.gif,.avif,.bmp">
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label fw-semibold">Short Summary / Bio</label>
                            <textarea name="bio" class="form-control" rows="2" placeholder="Brief 1-2 sentence overview of their role and clinical background..."></textarea>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label fw-semibold">Professional Background & Clinical Experience (Long)</label>
                            <textarea name="personal_experience" class="form-control" rows="3" placeholder="Detailed career overview, hospital installations, engineering accomplishments..."></textarea>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Key Skills (1 per line)</label>
                            <textarea name="skills" class="form-control" rows="4" placeholder="Diagnostic Calibration&#10;Equipment Planning&#10;ICU Maintenance"></textarea>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Education (1 per line)</label>
                            <textarea name="education" class="form-control" rows="4" placeholder="Ph.D. Biomedical Engineering&#10;M.Sc. Clinical Systems&#10;CCE Certification"></textarea>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Awards & Honors (1 per line)</label>
                            <textarea name="awards" class="form-control" rows="4" placeholder="Excellence in Engineering 2023&#10;National Healthcare Award"></textarea>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label fw-semibold">Facebook URL</label>
                            <input type="text" name="facebook_url" class="form-control" placeholder="https://facebook.com">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label fw-semibold">Twitter URL</label>
                            <input type="text" name="twitter_url" class="form-control" placeholder="https://twitter.com">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label fw-semibold">Instagram URL</label>
                            <input type="text" name="instagram_url" class="form-control" placeholder="https://instagram.com">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label fw-semibold">LinkedIn / Pinterest</label>
                            <input type="text" name="pinterest_url" class="form-control" placeholder="https://linkedin.com">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Sort Order</label>
                            <input type="number" name="order" class="form-control" value="0">
                        </div>
                        <div class="col-md-6 mb-3 d-flex align-items-end">
                            <div class="form-check form-switch w-100 p-3 border rounded bg-light d-flex justify-content-between align-items-center">
                                <label class="form-check-label fw-semibold mb-0" for="teamActive">Active Status</label>
                                <input class="form-check-input m-0" type="checkbox" name="is_active" value="1" checked id="teamActive" style="cursor: pointer; width: 2.5rem; height: 1.3rem;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-check me-1"></i> Add Specialist</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Navigation Menu Modal -->
<div class="modal fade" id="addMenuModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <form action="{{ route('admin.nav_menus.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-light border-bottom px-4 py-3">
                    <h5 class="modal-title fw-bold text-dark mb-0">
                        <i class="fa-solid fa-square-plus text-primary me-2"></i> Add Navigation Menu Item
                    </h5>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark"><i class="fa-solid fa-pen-nib text-muted me-1"></i> Menu Label / Title</label>
                        <input type="text" name="title" class="form-control form-control-lg fs-6" required placeholder="e.g. Products & Services, Careers, etc.">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark"><i class="fa-solid fa-link text-muted me-1"></i> Page Destination / Link Type</label>
                        <select name="page_route" class="form-select form-select-lg fs-6 page-route-select" required>
                            <option value="home">Home Page (/)</option>
                            <option value="about">About Us Page (/about)</option>
                            <option value="gallery">Gallery Page (/gallery)</option>
                            <option value="contact">Contact Us Page (/contact)</option>
                            <option value="partners">Brand Partners Section (/#brand-section)</option>
                            <option value="services_all">Services Section (/#services-section)</option>
                            <option value="service_single">Specific Service (Details Page)</option>
                            <option value="blogs_all">Blog Articles Section (/#blog-section)</option>
                            <option value="blog_single">Specific Blog Article</option>
                            <option value="dropdown_parent">Dropdown Parent Menu (No direct link #)</option>
                            <option value="custom">Custom Internal / External URL</option>
                        </select>
                    </div>

                    <!-- Dynamic Service Selector -->
                    <div class="mb-3 d-none service-selector-box">
                        <label class="form-label fw-semibold text-dark"><i class="fa-solid fa-stethoscope text-primary me-1"></i> Select Medical Service</label>
                        <select name="service_slug" class="form-select">
                            <option value="">-- Choose a Service --</option>
                            @foreach($allServices as $s)
                                <option value="{{ $s->slug }}">{{ $s->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Dynamic Blog Selector -->
                    <div class="mb-3 d-none blog-selector-box">
                        <label class="form-label fw-semibold text-dark"><i class="fa-solid fa-newspaper text-primary me-1"></i> Select Blog Article</label>
                        <select name="blog_slug" class="form-select">
                            <option value="">-- Choose an Article --</option>
                            @foreach($allBlogs as $b)
                                <option value="{{ $b->slug }}">{{ $b->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Custom URL Input -->
                    <div class="mb-3 d-none custom-url-box">
                        <label class="form-label fw-semibold text-dark"><i class="fa-solid fa-globe text-primary me-1"></i> Custom URL / Link</label>
                        <input type="text" name="custom_url" class="form-control" placeholder="e.g. https://example.com or /custom-page">
                    </div>

                    <div class="row">
                        <div class="col-md-7 mb-3">
                            <label class="form-label fw-semibold text-dark"><i class="fa-solid fa-folder-tree text-muted me-1"></i> Parent Menu</label>
                            <select name="parent_id" class="form-select">
                                <option value="">None (Top Level Menu)</option>
                                @foreach($navMenus as $pm)
                                    <option value="{{ $pm->id }}">{{ $pm->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-5 mb-3">
                            <label class="form-label fw-semibold text-dark"><i class="fa-solid fa-arrow-down-1-9 text-muted me-1"></i> Sort Order</label>
                            <input type="number" name="order" class="form-control" value="{{ $allNavMenus->count() + 1 }}">
                        </div>
                    </div>

                    <!-- Toggle Switches Cards Grid -->
                    <div class="mt-2">
                        <label class="form-label fw-semibold text-dark mb-2"><i class="fa-solid fa-sliders text-muted me-1"></i> Visibility & Behavior Options</label>
                        <div class="row g-2">
                            <div class="col-sm-6">
                                <div class="p-3 border rounded-3 bg-light d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="fw-semibold text-dark" style="font-size: 13px;"><i class="fa-solid fa-house text-success me-1"></i> Home Header</div>
                                        <small class="text-muted" style="font-size: 11px;">Show on homepage</small>
                                    </div>
                                    <div class="form-check form-switch m-0 ps-0">
                                        <input class="form-check-input ms-0" style="cursor: pointer; width: 2.5rem; height: 1.3rem;" type="checkbox" name="show_on_home" value="1" checked id="addShowHome">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="p-3 border rounded-3 bg-light d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="fw-semibold text-dark" style="font-size: 13px;"><i class="fa-solid fa-file-lines text-info me-1"></i> Inner Header</div>
                                        <small class="text-muted" style="font-size: 11px;">Show on inner pages</small>
                                    </div>
                                    <div class="form-check form-switch m-0 ps-0">
                                        <input class="form-check-input ms-0" style="cursor: pointer; width: 2.5rem; height: 1.3rem;" type="checkbox" name="show_on_inner" value="1" checked id="addShowInner">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="p-3 border rounded-3 bg-light d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="fw-semibold text-dark" style="font-size: 13px;"><i class="fa-solid fa-circle-check text-primary me-1"></i> Active Status</div>
                                        <small class="text-muted" style="font-size: 11px;">Enable link</small>
                                    </div>
                                    <div class="form-check form-switch m-0 ps-0">
                                        <input class="form-check-input ms-0" style="cursor: pointer; width: 2.5rem; height: 1.3rem;" type="checkbox" name="is_active" value="1" checked id="addIsActive">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="p-3 border rounded-3 bg-light d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="fw-semibold text-dark" style="font-size: 13px;"><i class="fa-solid fa-arrow-up-right-from-square text-secondary me-1"></i> Open New Tab</div>
                                        <small class="text-muted" style="font-size: 11px;">target="_blank"</small>
                                    </div>
                                    <div class="form-check form-switch m-0 ps-0">
                                        <input class="form-check-input ms-0" style="cursor: pointer; width: 2.5rem; height: 1.3rem;" type="checkbox" name="target_blank" value="1" id="addTargetBlank">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-top px-4 py-3 d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-outline-secondary px-3 py-2 rounded-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4 py-2 fw-semibold rounded-3 shadow-sm">
                        <i class="fa-solid fa-check me-1"></i> Create Menu Item
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Navigation Menu Modals -->
@foreach($allNavMenus as $editMenu)
<div class="modal fade" id="editMenuModal{{ $editMenu->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <form action="{{ route('admin.nav_menus.update', $editMenu->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header bg-light border-bottom px-4 py-3">
                    <h5 class="modal-title fw-bold text-dark mb-0">
                        <i class="fa-solid fa-pen-to-square text-primary me-2"></i> Edit Menu: <span class="text-primary">{{ $editMenu->title }}</span>
                    </h5>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark"><i class="fa-solid fa-pen-nib text-muted me-1"></i> Menu Label / Title</label>
                        <input type="text" name="title" class="form-control form-control-lg fs-6" value="{{ $editMenu->title }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark"><i class="fa-solid fa-link text-muted me-1"></i> Page Destination / Link Type</label>
                        <select name="page_route" class="form-select form-select-lg fs-6 page-route-select" required>
                            <option value="home" {{ $editMenu->page_route == 'home' ? 'selected' : '' }}>Home Page (/)</option>
                            <option value="about" {{ $editMenu->page_route == 'about' ? 'selected' : '' }}>About Us Page (/about)</option>
                            <option value="gallery" {{ $editMenu->page_route == 'gallery' ? 'selected' : '' }}>Gallery Page (/gallery)</option>
                            <option value="contact" {{ $editMenu->page_route == 'contact' ? 'selected' : '' }}>Contact Us Page (/contact)</option>
                            <option value="partners" {{ $editMenu->page_route == 'partners' ? 'selected' : '' }}>Brand Partners Section (/#brand-section)</option>
                            <option value="services_all" {{ $editMenu->page_route == 'services_all' ? 'selected' : '' }}>Services Section (/#services-section)</option>
                            <option value="service_single" {{ $editMenu->page_route == 'service_single' || $editMenu->page_route == 'service' ? 'selected' : '' }}>Specific Service (Details Page)</option>
                            <option value="blogs_all" {{ $editMenu->page_route == 'blogs_all' ? 'selected' : '' }}>Blog Articles Section (/#blog-section)</option>
                            <option value="blog_single" {{ $editMenu->page_route == 'blog_single' ? 'selected' : '' }}>Specific Blog Article</option>
                            <option value="dropdown_parent" {{ $editMenu->page_route == 'dropdown_parent' ? 'selected' : '' }}>Dropdown Parent Menu (No direct link #)</option>
                            <option value="custom" {{ $editMenu->page_route == 'custom' ? 'selected' : '' }}>Custom Internal / External URL</option>
                        </select>
                    </div>

                    <!-- Dynamic Service Selector -->
                    <div class="mb-3 {{ ($editMenu->page_route == 'service_single' || $editMenu->page_route == 'service') ? '' : 'd-none' }} service-selector-box">
                        <label class="form-label fw-semibold text-dark"><i class="fa-solid fa-stethoscope text-primary me-1"></i> Select Medical Service</label>
                        <select name="service_slug" class="form-select">
                            <option value="">-- Choose a Service --</option>
                            @foreach($allServices as $s)
                                <option value="{{ $s->slug }}" {{ str_contains($editMenu->url, $s->slug) ? 'selected' : '' }}>{{ $s->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Dynamic Blog Selector -->
                    <div class="mb-3 {{ $editMenu->page_route == 'blog_single' ? '' : 'd-none' }} blog-selector-box">
                        <label class="form-label fw-semibold text-dark"><i class="fa-solid fa-newspaper text-primary me-1"></i> Select Blog Article</label>
                        <select name="blog_slug" class="form-select">
                            <option value="">-- Choose an Article --</option>
                            @foreach($allBlogs as $b)
                                <option value="{{ $b->slug }}" {{ str_contains($editMenu->url, $b->slug) ? 'selected' : '' }}>{{ $b->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Custom URL Input -->
                    <div class="mb-3 {{ $editMenu->page_route == 'custom' ? '' : 'd-none' }} custom-url-box">
                        <label class="form-label fw-semibold text-dark"><i class="fa-solid fa-globe text-primary me-1"></i> Custom URL / Link</label>
                        <input type="text" name="custom_url" class="form-control" value="{{ $editMenu->url }}">
                    </div>

                    <div class="row">
                        <div class="col-md-7 mb-3">
                            <label class="form-label fw-semibold text-dark"><i class="fa-solid fa-folder-tree text-muted me-1"></i> Parent Menu</label>
                            <select name="parent_id" class="form-select">
                                <option value="">None (Top Level Menu)</option>
                                @foreach($navMenus as $pm)
                                    @if($pm->id != $editMenu->id)
                                        <option value="{{ $pm->id }}" {{ $editMenu->parent_id == $pm->id ? 'selected' : '' }}>{{ $pm->title }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-5 mb-3">
                            <label class="form-label fw-semibold text-dark"><i class="fa-solid fa-arrow-down-1-9 text-muted me-1"></i> Sort Order</label>
                            <input type="number" name="order" class="form-control" value="{{ $editMenu->order }}">
                        </div>
                    </div>

                    <!-- Toggle Switches Cards Grid -->
                    <div class="mt-2">
                        <label class="form-label fw-semibold text-dark mb-2"><i class="fa-solid fa-sliders text-muted me-1"></i> Visibility & Behavior Options</label>
                        <div class="row g-2">
                            <div class="col-sm-6">
                                <div class="p-3 border rounded-3 bg-light d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="fw-semibold text-dark" style="font-size: 13px;"><i class="fa-solid fa-house text-success me-1"></i> Home Header</div>
                                        <small class="text-muted" style="font-size: 11px;">Show on homepage</small>
                                    </div>
                                    <div class="form-check form-switch m-0 ps-0">
                                        <input class="form-check-input ms-0" style="cursor: pointer; width: 2.5rem; height: 1.3rem;" type="checkbox" name="show_on_home" value="1" {{ $editMenu->show_on_home ? 'checked' : '' }} id="editShowHome{{ $editMenu->id }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="p-3 border rounded-3 bg-light d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="fw-semibold text-dark" style="font-size: 13px;"><i class="fa-solid fa-file-lines text-info me-1"></i> Inner Header</div>
                                        <small class="text-muted" style="font-size: 11px;">Show on inner pages</small>
                                    </div>
                                    <div class="form-check form-switch m-0 ps-0">
                                        <input class="form-check-input ms-0" style="cursor: pointer; width: 2.5rem; height: 1.3rem;" type="checkbox" name="show_on_inner" value="1" {{ $editMenu->show_on_inner ? 'checked' : '' }} id="editShowInner{{ $editMenu->id }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="p-3 border rounded-3 bg-light d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="fw-semibold text-dark" style="font-size: 13px;"><i class="fa-solid fa-circle-check text-primary me-1"></i> Active Status</div>
                                        <small class="text-muted" style="font-size: 11px;">Enable link</small>
                                    </div>
                                    <div class="form-check form-switch m-0 ps-0">
                                        <input class="form-check-input ms-0" style="cursor: pointer; width: 2.5rem; height: 1.3rem;" type="checkbox" name="is_active" value="1" {{ $editMenu->is_active ? 'checked' : '' }} id="editIsActive{{ $editMenu->id }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="p-3 border rounded-3 bg-light d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="fw-semibold text-dark" style="font-size: 13px;"><i class="fa-solid fa-arrow-up-right-from-square text-secondary me-1"></i> Open New Tab</div>
                                        <small class="text-muted" style="font-size: 11px;">target="_blank"</small>
                                    </div>
                                    <div class="form-check form-switch m-0 ps-0">
                                        <input class="form-check-input ms-0" style="cursor: pointer; width: 2.5rem; height: 1.3rem;" type="checkbox" name="target_blank" value="1" {{ $editMenu->target_blank ? 'checked' : '' }} id="editTargetBlank{{ $editMenu->id }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-top px-4 py-3 d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-outline-secondary px-3 py-2 rounded-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4 py-2 fw-semibold rounded-3 shadow-sm">
                        <i class="fa-solid fa-check me-1"></i> Update Menu Item
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

<!-- Edit Gallery Modals -->
@foreach($galleryItems as $editGal)
<div class="modal fade" id="editGalleryModal{{ $editGal->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form action="{{ route('admin.home_sections.gallery.update', $editGal->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold text-dark"><i class="fa-solid fa-pen-to-square text-primary me-2"></i> Edit Gallery Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Title</label>
                        <input type="text" name="title" class="form-control" value="{{ $editGal->title }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Category / Tag</label>
                        <input type="text" name="category" class="form-control" value="{{ $editGal->category }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Current Image</label>
                        <div class="d-flex align-items-center gap-3 p-2 border rounded bg-light mb-2">
                            <img src="{{ asset($editGal->image) }}" alt="" style="max-height: 60px; max-width: 100px; object-fit: cover; border-radius: 4px;">
                            <div class="flex-grow-1">
                                <input type="file" name="image" class="form-control" accept="image/*,.jfif,.png,.jpg,.jpeg,.webp,.svg,.gif,.avif,.bmp">
                                <small class="text-muted">Upload new photo to replace</small>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Sort Order</label>
                        <input type="number" name="order" class="form-control" value="{{ $editGal->order }}">
                    </div>
                    <div class="form-check form-switch p-3 border rounded bg-light d-flex justify-content-between align-items-center">
                        <label class="form-check-label fw-semibold mb-0" for="galActive{{ $editGal->id }}">Active Status</label>
                        <input class="form-check-input m-0" type="checkbox" name="is_active" value="1" {{ $editGal->is_active ? 'checked' : '' }} id="galActive{{ $editGal->id }}" style="cursor: pointer; width: 2.5rem; height: 1.3rem;">
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-check me-1"></i> Update Item</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

<!-- Edit Team Member Modals -->
@foreach($teamMembers as $editMember)
<div class="modal fade" id="editTeamModal{{ $editMember->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow">
            <form action="{{ route('admin.home_sections.team.update', $editMember->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold text-dark"><i class="fa-solid fa-pen-to-square text-primary me-2"></i> Edit Specialist: {{ $editMember->name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ $editMember->name }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">URL Slug</label>
                            <input type="text" name="slug" class="form-control" value="{{ $editMember->slug }}" placeholder="cameron-williamson">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Specialization / Role <span class="text-danger">*</span></label>
                            <input type="text" name="designation" class="form-control" value="{{ $editMember->designation }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Area of Expertise</label>
                            <input type="text" name="expertise" class="form-control" value="{{ $editMember->expertise }}" placeholder="e.g. Diagnostic & Radiology Systems">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Experience</label>
                            <input type="text" name="experience" class="form-control" value="{{ $editMember->experience }}" placeholder="e.g. 15+ Years">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Email Address</label>
                            <input type="email" name="email" class="form-control" value="{{ $editMember->email }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Contact Phone</label>
                            <input type="text" name="phone" class="form-control" value="{{ $editMember->phone }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Photo</label>
                            <div class="d-flex align-items-center gap-3 p-2 border rounded bg-light">
                                <img src="{{ asset($editMember->image ?: 'assets/img/team/team-thumb-01.jpg') }}" alt="" style="width: 45px; height: 45px; border-radius: 50%; object-fit: cover;">
                                <div class="flex-grow-1">
                                    <input type="file" name="image" class="form-control form-control-sm" accept="image/*,.jfif,.png,.jpg,.jpeg,.webp,.svg,.gif,.avif,.bmp">
                                </div>
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label fw-semibold">Short Summary / Bio</label>
                            <textarea name="bio" class="form-control" rows="2">{{ $editMember->bio }}</textarea>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label fw-semibold">Professional Background & Clinical Experience (Long)</label>
                            <textarea name="personal_experience" class="form-control" rows="3">{{ $editMember->personal_experience }}</textarea>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Key Skills (1 per line)</label>
                            <textarea name="skills" class="form-control" rows="4">{{ $editMember->skills }}</textarea>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Education (1 per line)</label>
                            <textarea name="education" class="form-control" rows="4">{{ $editMember->education }}</textarea>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Awards & Honors (1 per line)</label>
                            <textarea name="awards" class="form-control" rows="4">{{ $editMember->awards }}</textarea>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label fw-semibold">Facebook URL</label>
                            <input type="text" name="facebook_url" class="form-control" value="{{ $editMember->facebook_url }}">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label fw-semibold">Twitter URL</label>
                            <input type="text" name="twitter_url" class="form-control" value="{{ $editMember->twitter_url }}">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label fw-semibold">Instagram URL</label>
                            <input type="text" name="instagram_url" class="form-control" value="{{ $editMember->instagram_url }}">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label fw-semibold">LinkedIn / Pinterest</label>
                            <input type="text" name="pinterest_url" class="form-control" value="{{ $editMember->pinterest_url ?: $editMember->skype_url }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Sort Order</label>
                            <input type="number" name="order" class="form-control" value="{{ $editMember->order }}">
                        </div>
                        <div class="col-md-6 mb-3 d-flex align-items-end">
                            <div class="form-check form-switch w-100 p-3 border rounded bg-light d-flex justify-content-between align-items-center">
                                <label class="form-check-label fw-semibold mb-0" for="teamActive{{ $editMember->id }}">Active Status</label>
                                <input class="form-check-input m-0" type="checkbox" name="is_active" value="1" {{ $editMember->is_active ? 'checked' : '' }} id="teamActive{{ $editMember->id }}" style="cursor: pointer; width: 2.5rem; height: 1.3rem;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <a href="{{ route('team.detail', $editMember->slug ?: $editMember->id) }}" target="_blank" class="btn btn-outline-primary"><i class="fa-solid fa-arrow-up-right-from-square me-1"></i> View Live Profile</a>
                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-check me-1"></i> Update Specialist</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

<!-- Add Testimonial Modal -->
<div class="modal fade" id="addTestimonialModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form action="{{ route('admin.home_sections.testimonials.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold text-dark"><i class="fa-solid fa-plus text-primary me-2"></i> Add Client Testimonial</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Client / Doctor Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" required placeholder="e.g. Dr. Salman Tariq">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Designation / Title</label>
                            <input type="text" name="designation" class="form-control" placeholder="e.g. Head of Cardiology">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Hospital / Institution</label>
                            <input type="text" name="hospital" class="form-control" placeholder="e.g. AKUH Karachi">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Star Rating (1 to 5)</label>
                        <select name="rating" class="form-select">
                            <option value="5" selected>⭐⭐⭐⭐⭐ (5 Stars)</option>
                            <option value="4">⭐⭐⭐⭐ (4 Stars)</option>
                            <option value="3">⭐⭐⭐ (3 Stars)</option>
                            <option value="2">⭐⭐ (2 Stars)</option>
                            <option value="1">⭐ (1 Star)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Review / Feedback Quote <span class="text-danger">*</span></label>
                        <textarea name="content" class="form-control" rows="3" required placeholder="Client review text..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Client Avatar / Photo</label>
                        <input type="file" name="avatar" class="form-control" accept="image/*,.jfif,.png,.jpg,.jpeg,.webp,.svg,.gif,.avif,.bmp">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Sort Order</label>
                        <input type="number" name="order" class="form-control" value="0">
                    </div>
                    <div class="form-check form-switch p-3 border rounded bg-light d-flex justify-content-between align-items-center">
                        <label class="form-check-label fw-semibold mb-0" for="addTestiActive">Active Status</label>
                        <input class="form-check-input m-0" type="checkbox" name="is_active" value="1" checked id="addTestiActive" style="cursor: pointer; width: 2.5rem; height: 1.3rem;">
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-check me-1"></i> Add Review</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Testimonial Modals -->
@foreach($testimonials as $editTesti)
<div class="modal fade" id="editTestimonialModal{{ $editTesti->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form action="{{ route('admin.home_sections.testimonials.update', $editTesti->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold text-dark"><i class="fa-solid fa-pen-to-square text-primary me-2"></i> Edit Testimonial: {{ $editTesti->name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Client Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ $editTesti->name }}" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Designation</label>
                            <input type="text" name="designation" class="form-control" value="{{ $editTesti->designation }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Hospital</label>
                            <input type="text" name="hospital" class="form-control" value="{{ $editTesti->hospital }}">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Star Rating</label>
                        <select name="rating" class="form-select">
                            <option value="5" {{ ($editTesti->rating ?: 5) == 5 ? 'selected' : '' }}>⭐⭐⭐⭐⭐ (5 Stars)</option>
                            <option value="4" {{ $editTesti->rating == 4 ? 'selected' : '' }}>⭐⭐⭐⭐ (4 Stars)</option>
                            <option value="3" {{ $editTesti->rating == 3 ? 'selected' : '' }}>⭐⭐⭐ (3 Stars)</option>
                            <option value="2" {{ $editTesti->rating == 2 ? 'selected' : '' }}>⭐⭐ (2 Stars)</option>
                            <option value="1" {{ $editTesti->rating == 1 ? 'selected' : '' }}>⭐ (1 Star)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Feedback Content <span class="text-danger">*</span></label>
                        <textarea name="content" class="form-control" rows="3" required>{{ $editTesti->content }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Client Avatar</label>
                        <div class="d-flex align-items-center gap-3 p-2 border rounded bg-light mb-2">
                            <img src="{{ asset($editTesti->avatar ?: 'assets/img/icon/testi-ava-01.jpg') }}" alt="" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
                            <div class="flex-grow-1">
                                <input type="file" name="avatar" class="form-control" accept="image/*,.jfif,.png,.jpg,.jpeg,.webp,.svg,.gif,.avif,.bmp">
                                <small class="text-muted">Upload to replace avatar</small>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Sort Order</label>
                        <input type="number" name="order" class="form-control" value="{{ $editTesti->order }}">
                    </div>
                    <div class="form-check form-switch p-3 border rounded bg-light d-flex justify-content-between align-items-center">
                        <label class="form-check-label fw-semibold mb-0" for="testiActive{{ $editTesti->id }}">Active Status</label>
                        <input class="form-check-input m-0" type="checkbox" name="is_active" value="1" {{ $editTesti->is_active ? 'checked' : '' }} id="testiActive{{ $editTesti->id }}" style="cursor: pointer; width: 2.5rem; height: 1.3rem;">
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-check me-1"></i> Update Review</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

<!-- Add Brand Partner Modal -->
<div class="modal fade" id="addPartnerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form action="{{ route('admin.home_sections.partners.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold text-dark"><i class="fa-solid fa-plus text-primary me-2"></i> Add Brand Partner</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Partner Brand Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" required placeholder="e.g. Siemens Healthineers">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Website Link / URL</label>
                        <input type="text" name="url" class="form-control" placeholder="https://example.com">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Logo Image <span class="text-danger">*</span></label>
                        <input type="file" name="logo" class="form-control" required accept="image/*,.jfif,.png,.jpg,.jpeg,.webp,.svg,.gif,.avif,.bmp">
                        <small class="text-muted">Recommended: PNG logo with transparent background</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Sort Order</label>
                        <input type="number" name="order" class="form-control" value="0">
                    </div>
                    <div class="form-check form-switch p-3 border rounded bg-light d-flex justify-content-between align-items-center">
                        <label class="form-check-label fw-semibold mb-0" for="addPartnerActive">Active Status</label>
                        <input class="form-check-input m-0" type="checkbox" name="is_active" value="1" checked id="addPartnerActive" style="cursor: pointer; width: 2.5rem; height: 1.3rem;">
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-check me-1"></i> Add Partner</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Brand Partner Modals -->
@foreach($partners as $editPartner)
<div class="modal fade" id="editPartnerModal{{ $editPartner->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form action="{{ route('admin.home_sections.partners.update', $editPartner->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold text-dark"><i class="fa-solid fa-pen-to-square text-primary me-2"></i> Edit Partner: {{ $editPartner->name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Partner Brand Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ $editPartner->name }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Website Link / URL</label>
                        <input type="text" name="url" class="form-control" value="{{ $editPartner->url }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Logo Image</label>
                        <div class="d-flex align-items-center gap-3 p-2 border rounded bg-light mb-2">
                            <img src="{{ asset($editPartner->logo) }}" alt="" style="max-height: 35px; max-width: 90px; object-fit: contain;">
                            <div class="flex-grow-1">
                                <input type="file" name="logo" class="form-control" accept="image/*,.jfif,.png,.jpg,.jpeg,.webp,.svg,.gif,.avif,.bmp">
                                <small class="text-muted">Upload new logo to replace</small>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Sort Order</label>
                        <input type="number" name="order" class="form-control" value="{{ $editPartner->order }}">
                    </div>
                    <div class="form-check form-switch p-3 border rounded bg-light d-flex justify-content-between align-items-center">
                        <label class="form-check-label fw-semibold mb-0" for="partnerActive{{ $editPartner->id }}">Active Status</label>
                        <input class="form-check-input m-0" type="checkbox" name="is_active" value="1" {{ $editPartner->is_active ? 'checked' : '' }} id="partnerActive{{ $editPartner->id }}" style="cursor: pointer; width: 2.5rem; height: 1.3rem;">
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-check me-1"></i> Update Partner</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // 1. Tab Persistence via URL, LocalStorage & Bootstrap 5
    function switchActiveTab(tabName) {
        if (!tabName) return;
        tabName = tabName.replace(/^#tab-/, '').replace(/^#/, '').replace(/^tab-/, '');
        const triggerEl = document.querySelector(`button[data-bs-target="#tab-${tabName}"]`);
        if (triggerEl) {
            const tabInstance = bootstrap.Tab.getOrCreateInstance(triggerEl);
            tabInstance.show();
            localStorage.setItem('innotech_admin_home_tab', tabName);
            
            const newUrl = new URL(window.location);
            newUrl.searchParams.set('tab', tabName);
            window.history.replaceState({}, '', newUrl);
        }
    }

    const urlParams = new URLSearchParams(window.location.search);
    const initialTab = urlParams.get('tab') || localStorage.getItem('innotech_admin_home_tab') || 'header';
    if (initialTab) {
        switchActiveTab(initialTab);
    }

    $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
        const targetId = $(e.target).attr('data-bs-target').replace('#tab-', '');
        localStorage.setItem('innotech_admin_home_tab', targetId);
        const newUrl = new URL(window.location);
        newUrl.searchParams.set('tab', targetId);
        window.history.replaceState({}, '', newUrl);
    });

    // 2. Unsaved Changes Dirty Tracker
    let dirtyTabs = new Set();

    $('.section-form input, .section-form textarea, .section-form select').on('input change', function() {
        const form = $(this).closest('.section-form');
        const tabPane = form.closest('.tab-pane');
        const tabId = tabPane.attr('id');
        const tabBtn = $(`button[data-bs-target="#${tabId}"]`);

        dirtyTabs.add(tabId);
        tabBtn.addClass('dirty');
        $('#globalSaveBar').removeClass('d-none');
    });

    // Instant Image Preview on File Selection
    $(document).on('change', 'input[type="file"]', function() {
        const input = this;
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const previewImg = $(input).closest('.d-flex, .modal-body, .mb-3').find('img').first();
                if (previewImg.length) {
                    previewImg.attr('src', e.target.result);
                }
            };
            reader.readAsDataURL(input.files[0]);
        }
    });

    // 3. Save Single Section Form via AJAX
    $('.section-form').on('submit', function(e) {
        e.preventDefault();
        const form = $(this);
        const submitBtn = form.find('.save-section-btn');
        const originalBtnHtml = submitBtn.html();
        const tabPane = form.closest('.tab-pane');
        const tabId = tabPane.attr('id');
        const tabBtn = $(`button[data-bs-target="#${tabId}"]`);

        submitBtn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin me-1"></i> Saving...');

        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: new FormData(this),
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                submitBtn.prop('disabled', false).html(originalBtnHtml);
                localStorage.setItem('innotech_admin_home_tab', tabId.replace('tab-', ''));
                
                dirtyTabs.delete(tabId);
                tabBtn.removeClass('dirty');
                if (dirtyTabs.size === 0) {
                    $('#globalSaveBar').addClass('d-none');
                }

                if (response.uploaded_images) {
                    $.each(response.uploaded_images, function(name, url) {
                        $(`input[name="${name}"]`).closest('.d-flex, .mb-3').find('img').attr('src', url);
                    });
                }

                Swal.fire({
                    icon: 'success',
                    title: 'Saved Successfully!',
                    text: response.message || 'Section settings and images updated.',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
            },
            error: function(xhr) {
                submitBtn.prop('disabled', false).html(originalBtnHtml);
                const errorMsg = xhr.responseJSON?.message || 'An error occurred while saving.';
                
                Swal.fire({
                    icon: 'error',
                    title: 'Saving Failed',
                    text: errorMsg,
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 4000,
                    timerProgressBar: true
                });
            }
        });
    });

    // 4. Save All Sections Simultaneously
    $('#saveAllSectionsBtn, #stickySaveAllBtn').on('click', function() {
        const btn = $(this);
        const originalHtml = btn.html();
        btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin me-1"></i> Saving All...');

        let formData = new FormData();
        $('.section-form').each(function() {
            const form = this;
            const formElements = $(form).serializeArray();
            $.each(formElements, function(i, field) {
                if (field.name !== '_token') {
                    formData.append(field.name, field.value);
                }
            });
            $(form).find('input[type="file"]').each(function() {
                if (this.files && this.files.length > 0) {
                    formData.append(this.name, this.files[0]);
                }
            });
        });

        $.ajax({
            url: "{{ route('admin.home_sections.save') }}",
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                btn.prop('disabled', false).html(originalHtml);
                
                dirtyTabs.clear();
                $('.nav-tabs-custom .nav-link').removeClass('dirty');
                $('#globalSaveBar').addClass('d-none');

                if (response.uploaded_images) {
                    $.each(response.uploaded_images, function(name, url) {
                        $(`input[name="${name}"]`).closest('.d-flex, .mb-3').find('img').attr('src', url);
                    });
                }

                Swal.fire({
                    icon: 'success',
                    title: 'All Sections Saved!',
                    text: 'All homepage sections and uploaded images have been successfully saved.',
                    confirmButtonColor: '#0E63FF'
                });
            },
            error: function(xhr) {
                btn.prop('disabled', false).html(originalHtml);
                const errorMsg = xhr.responseJSON?.message || 'Failed to save all sections.';
                
                Swal.fire({
                    icon: 'error',
                    title: 'Error Saving All',
                    text: errorMsg,
                    confirmButtonColor: '#EF4444'
                });
            }
        });
    });

    // 5. Discard Changes
    $('#discardChangesBtn').on('click', function() {
        Swal.fire({
            title: 'Discard Unsaved Changes?',
            text: 'Any modifications you made will be reloaded to their last saved values.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#EF4444',
            cancelButtonColor: '#64748B',
            confirmButtonText: 'Yes, Discard'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.reload();
            }
        });
    });

    // 6. Section Toggle Switch live feedback
    $('.section-toggle-switch').on('change', function() {
        const isChecked = $(this).is(':checked');
        
        Swal.fire({
            icon: isChecked ? 'info' : 'warning',
            title: isChecked ? 'Section Enabled' : 'Section Hidden',
            text: `Click "Save Section" or "Save All" to commit changes to the live site.`,
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2500
        });
    });

    // 7. Dynamic Page Route Selector in Add/Edit Menu Modals
    $(document).on('change', '.page-route-select', function() {
        const val = $(this).val();
        const modal = $(this).closest('.modal');

        modal.find('.service-selector-box').addClass('d-none');
        modal.find('.blog-selector-box').addClass('d-none');
        modal.find('.custom-url-box').addClass('d-none');

        if (val === 'service_single' || val === 'service') {
            modal.find('.service-selector-box').removeClass('d-none');
        } else if (val === 'blog_single') {
            modal.find('.blog-selector-box').removeClass('d-none');
        } else if (val === 'custom') {
            modal.find('.custom-url-box').removeClass('d-none');
        }
    });

    // 8. Navigation Menu Quick Toggle AJAX
    $(document).on('click', '.toggle-menu-btn', function() {
        const btn = $(this);
        const menuId = btn.data('id');
        const field = btn.data('field');
        const toggleBaseUrl = "{{ url('admin/nav-menus') }}";

        btn.prop('disabled', true);

        $.ajax({
            url: `${toggleBaseUrl}/${menuId}/toggle`,
            method: 'POST',
            data: { field: field },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                btn.prop('disabled', false);
                const newVal = response.new_val;
                if (field === 'show_on_home') {
                    if (newVal) {
                        btn.removeClass('btn-light text-muted border').addClass('btn-success text-white').text('✓ Visible');
                    } else {
                        btn.removeClass('btn-success text-white').addClass('btn-light text-muted border').text('✕ Hidden');
                    }
                } else if (field === 'show_on_inner') {
                    if (newVal) {
                        btn.removeClass('btn-light text-muted border').addClass('btn-info text-white').text('✓ Visible');
                    } else {
                        btn.removeClass('btn-info text-white').addClass('btn-light text-muted border').text('✕ Hidden');
                    }
                } else if (field === 'is_active') {
                    if (newVal) {
                        btn.removeClass('btn-danger').addClass('btn-primary').text('Active');
                    } else {
                        btn.removeClass('btn-primary').addClass('btn-danger').text('Disabled');
                    }
                }

                Swal.fire({
                    icon: 'success',
                    title: 'Status Updated!',
                    text: response.message,
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2000
                });
            },
            error: function(xhr) {
                btn.prop('disabled', false);
                Swal.fire({
                    icon: 'error',
                    title: 'Update Failed',
                    text: xhr.responseJSON?.message || 'Could not update menu status.',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2500
                });
            }
        });
    });

    // 9. Universal Item AJAX Toggle (Services, Gallery, Team, Testimonials, Partners, Blog)
    $(document).on('click', '.item-ajax-toggle', function() {
        const btn = $(this);
        const type = btn.data('type');
        const id = btn.data('id');
        const toggleItemUrl = "{{ route('admin.home_sections.toggle_item') }}";

        btn.prop('disabled', true);

        $.ajax({
            url: toggleItemUrl,
            method: 'POST',
            data: {
                type: type,
                id: id
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                btn.prop('disabled', false);
                const isOnline = response.new_status;

                if (type === 'blog') {
                    if (isOnline) {
                        btn.removeClass('btn-light text-muted border').addClass('btn-success text-white').text('Published');
                    } else {
                        btn.removeClass('btn-success text-white').addClass('btn-light text-muted border').text('Draft');
                    }
                } else {
                    if (isOnline) {
                        btn.removeClass('btn-light text-muted border').addClass('btn-success text-white').text('Active');
                    } else {
                        btn.removeClass('btn-success text-white').addClass('btn-light text-muted border').text('Inactive');
                    }
                }

                Swal.fire({
                    icon: 'success',
                    title: 'Status Updated!',
                    text: response.message,
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2000
                });
            },
            error: function(xhr) {
                btn.prop('disabled', false);
                Swal.fire({
                    icon: 'error',
                    title: 'Update Failed',
                    text: xhr.responseJSON?.message || 'Could not update status.',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2500
                });
            }
        });
    });

    // 10. Banner Video Type Switcher (URL vs File Upload)
    $('#bannerVideoTypeSelect').on('change', function() {
        const val = $(this).val();
        if (val === 'upload') {
            $('#bannerVideoUrlBox').addClass('d-none');
            $('#bannerVideoFileBox').removeClass('d-none');
        } else {
            $('#bannerVideoUrlBox').removeClass('d-none');
            $('#bannerVideoFileBox').addClass('d-none');
        }
    });
});

// 11. Delete Banner Hero Slider Image Handler
window.deleteSliderImage = function(imagePath, btnElement) {
    Swal.fire({
        title: 'Delete Slider Image?',
        text: "Yeh image home page hero slider se permanently remove ho jayegi.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="fa-solid fa-trash-can me-1"></i> Yes, Delete it',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            const btn = $(btnElement);
            btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i>');

            $.ajax({
                url: "{{ route('admin.home_sections.delete_banner_slider_image') }}",
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    image_path: imagePath
                },
                success: function(response) {
                    if (response.status === 'success') {
                        const itemCol = btn.closest('.slider-image-item');
                        itemCol.fadeOut(300, function() {
                            $(this).remove();
                            // Update remaining count badge
                            const count = $('#activeSliderImagesRow .slider-image-item').length;
                            $('#sliderImagesCountBadge').text(count + ' Slider ' + (count === 1 ? 'Image' : 'Images') + ' Active');
                            if (count === 0) {
                                $('#sliderImagesGalleryContainer').html(`
                                    <div class="p-3 bg-light rounded-3 text-center border border-dashed" id="noSliderImagesNotice">
                                        <i class="fa-solid fa-photo-film text-muted fs-3 mb-1"></i>
                                        <p class="text-muted small mb-0">Abhi koi multiple slider image upload nahi hui. Default single banner image use ho rahi hai.</p>
                                    </div>
                                `);
                            }
                        });

                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted',
                            text: 'Slider image deleted successfully!',
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 2500
                        });
                    } else {
                        btn.prop('disabled', false).html('<i class="fa-solid fa-trash-can"></i> Delete');
                        Swal.fire('Error', response.message || 'Could not delete image', 'error');
                    }
                },
                error: function() {
                    btn.prop('disabled', false).html('<i class="fa-solid fa-trash-can"></i> Delete');
                    Swal.fire('Error', 'Server error while deleting image.', 'error');
                }
            });
        }
    });
};
</script>
@endpush
