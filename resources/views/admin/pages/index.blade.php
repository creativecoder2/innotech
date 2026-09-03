@extends('admin.layouts.master')

@section('title', 'Custom Pages & Legal Policies')
@section('header_title', 'Custom Pages Manager')

@push('styles')
<!-- Summernote Lite CSS for WYSIWYG Text Editor -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.css" rel="stylesheet">
<style>
    /* Sleek Template Presets Cards */
    .template-card {
        background: #ffffff;
        border: 1px solid #E2E8F0;
        border-radius: 12px;
        padding: 18px 16px;
        transition: all 0.25s ease;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        height: 100%;
        position: relative;
    }
    .template-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 22px rgba(14, 99, 255, 0.08);
        border-color: #0E63FF;
    }
    .template-icon-box {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        margin-bottom: 12px;
    }
    .template-card h6 {
        font-size: 14px;
        font-weight: 700;
        color: #1E293B;
        margin-bottom: 6px;
    }
    .template-card p {
        font-size: 12px;
        color: #64748B;
        line-height: 1.4;
        margin-bottom: 14px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        min-height: 34px;
    }
    .badge-placement {
        font-size: 11.5px;
        font-weight: 600;
        padding: 5px 10px;
        border-radius: 6px;
    }

    /* Polished Table Action Buttons */
    .table-actions-group {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        white-space: nowrap;
    }
    .table-actions-group .btn {
        width: 32px;
        height: 32px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        font-size: 13px;
        border: 1px solid #E2E8F0;
        background: #FFFFFF;
        transition: all 0.2s ease;
    }
    .table-actions-group .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 5px rgba(0,0,0,0.08);
    }
    .table-actions-group .btn-view:hover {
        background: #EFF6FF;
        color: #0E63FF;
        border-color: #BFDBFE;
    }
    .table-actions-group .btn-edit:hover {
        background: #F0FDF4;
        color: #16A34A;
        border-color: #BBF7D0;
    }
    .table-actions-group .btn-delete:hover {
        background: #FEF2F2;
        color: #DC2626;
        border-color: #FECACA;
    }

    /* Summernote Styling Tweaks */
    .note-editor.note-frame {
        border: 1px solid #CBD5E1;
        border-radius: 10px;
        overflow: hidden;
    }
    .note-toolbar {
        background: #F8FAFC !important;
        border-bottom: 1px solid #E2E8F0 !important;
        padding: 8px 10px !important;
    }
    .note-btn {
        background: #FFFFFF !important;
        border: 1px solid #E2E8F0 !important;
        color: #334155 !important;
        border-radius: 6px !important;
        padding: 4px 8px !important;
        font-size: 12.5px !important;
    }
    .note-btn:hover {
        background: #EFF6FF !important;
        color: #0E63FF !important;
    }
    .note-editable {
        background: #FFFFFF;
        font-family: inherit;
        font-size: 14px;
        line-height: 1.6;
        color: #1E293B;
    }
</style>
@endpush

@section('content')

    <!-- Top Template Presets Grid -->
    <div class="mb-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
            <div>
                <h5 class="fw-bold mb-1"><i class="fa-solid fa-shapes text-primary me-2"></i> Ready-made Policy & Content Templates</h5>
                <p class="text-muted small mb-0">Select a pre-built healthcare legal template to quickly generate a page with sample terms, or create a custom one.</p>
            </div>
            <button type="button" class="btn btn-primary btn-sm px-3 py-2 fw-semibold rounded-3 shadow-sm" onclick="openPageModal('custom')">
                <i class="fa-solid fa-plus me-1"></i> New Custom Page
            </button>
        </div>

        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-xl-5 g-3">
            @foreach($templates as $key => $tmpl)
                <div class="col">
                    <div class="template-card">
                        <div>
                            <div class="template-icon-box" style="background-color: {{ $tmpl['bg'] }}; color: {{ $tmpl['color'] }};">
                                <i class="{{ $tmpl['icon'] }}"></i>
                            </div>
                            <h6>{{ $tmpl['name'] }}</h6>
                            <p title="{{ $tmpl['desc'] }}">{{ $tmpl['desc'] }}</p>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary w-100 fw-semibold py-1 rounded-2" onclick="openTemplateModal('{{ $key }}')">
                            <i class="fa-solid fa-wand-magic-sparkles me-1"></i> Use Template
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Created Pages Data Table -->
    <div class="admin-card">
        <div class="admin-card-header d-flex justify-content-between align-items-center bg-light">
            <h6 class="fw-bold mb-0"><i class="fa-solid fa-list-check text-primary me-2"></i> All Created Pages ({{ $pages->total() }})</h6>
            <span class="text-muted small">Manage live policies, terms, and footer links.</span>
        </div>

        <div class="table-responsive">
            <table class="table table-custom align-middle mb-0">
                <thead>
                    <tr>
                        <th width="45" class="text-center">#</th>
                        <th style="min-width: 240px;">Page Title & URL Slug</th>
                        <th width="140" class="text-center">Template Type</th>
                        <th width="200">Footer Placement</th>
                        <th width="130" class="text-center">Status</th>
                        <th width="130">Last Modified</th>
                        <th width="140" class="text-end" style="white-space: nowrap;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pages as $page)
                        <tr>
                            <td class="text-center fw-semibold text-muted">{{ $loop->iteration + ($pages->currentPage() - 1) * $pages->perPage() }}</td>
                            <td>
                                <div class="fw-bold text-dark fs-6">{{ $page->title }}</div>
                                <small class="text-muted">
                                    <a href="{{ route('page.show', $page->slug) }}" target="_blank" class="text-decoration-none text-primary fw-medium">
                                        /page/{{ $page->slug }} <i class="fa-solid fa-arrow-up-right-from-square small ms-1" style="font-size: 10px;"></i>
                                    </a>
                                </small>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-light text-dark text-capitalize border px-2 py-1">
                                    {{ $page->template_type ?: 'Custom' }}
                                </span>
                            </td>
                            <td>
                                @if(!$page->show_in_footer)
                                    <span class="badge bg-secondary badge-placement">Hidden from Footer</span>
                                @elseif($page->footer_placement === 'bottom_bar')
                                    <span class="badge bg-info text-dark badge-placement"><i class="fa-solid fa-shoe-prints me-1"></i> Footer Bottom Bar</span>
                                @elseif($page->footer_placement === 'useful_links')
                                    <span class="badge bg-primary badge-placement"><i class="fa-solid fa-link me-1"></i> Useful Links Widget</span>
                                @else
                                    <span class="badge bg-success badge-placement"><i class="fa-solid fa-globe me-1"></i> Both Placements</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($page->is_published)
                                    <span class="badge bg-success bg-opacity-10 text-success px-2 py-1 fw-semibold"><i class="fa-solid fa-circle-check me-1"></i> Published</span>
                                @else
                                    <span class="badge bg-warning bg-opacity-10 text-warning px-2 py-1 fw-semibold"><i class="fa-solid fa-circle-pause me-1"></i> Draft</span>
                                @endif
                            </td>
                            <td>
                                <small class="text-muted">{{ $page->updated_at->format('M d, Y') }}</small>
                            </td>
                            <td class="text-end" style="white-space: nowrap;">
                                <div class="table-actions-group">
                                    <a href="{{ route('page.show', $page->slug) }}" target="_blank" class="btn btn-view text-primary" title="View Live Page">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    <button type="button" class="btn btn-edit text-success edit-page-btn" 
                                            data-page='@json($page)' title="Edit Page Content">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
                                    <form action="{{ route('admin.pages.destroy', $page->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this page?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-delete text-danger" title="Delete Page">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-file-circle-question fs-2 d-block mb-2 text-secondary opacity-50"></i>
                                No custom pages created yet. Click one of the templates above to create your first policy!
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($pages->hasPages())
            <div class="p-3 border-top d-flex justify-content-between align-items-center bg-light">
                <span class="text-muted small">Showing {{ $pages->firstItem() }} to {{ $pages->lastItem() }} of {{ $pages->total() }} pages</span>
                {{ $pages->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

    <!-- Create Page Modal (Wider modal-xl with Rich Text Editor) -->
    <div class="modal fade" id="createPageModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content border-0 shadow-lg rounded-3">
                <form action="{{ route('admin.pages.store') }}" method="POST" id="createPageForm">
                    @csrf
                    <input type="hidden" name="template_type" id="create_template_type" value="custom">

                    <div class="modal-header bg-light py-3 px-4">
                        <h5 class="modal-title fw-bold" id="createModalTitle">
                            <i class="fa-solid fa-file-circle-plus text-primary me-2"></i> Create Customer Page
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <!-- Title & Slug -->
                            <div class="col-md-7">
                                <label class="form-label fw-semibold text-dark">Page Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" id="create_title" class="form-control" required placeholder="e.g. Terms and Conditions">
                            </div>
                            <div class="col-md-5">
                                <label class="form-label fw-semibold text-dark">URL Slug <small class="text-muted">(auto-generated if empty)</small></label>
                                <input type="text" name="slug" id="create_slug" class="form-control" placeholder="e.g. terms-and-conditions">
                            </div>

                            <!-- Subtitle & Footer Placement -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-dark">Banner Subtitle / Tagline</label>
                                <input type="text" name="subtitle" id="create_subtitle" class="form-control" placeholder="Short description displayed under title in page header banner">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-dark"><i class="fa-solid fa-shoe-prints text-info me-1"></i> Footer Placement Location</label>
                                <select name="footer_placement" id="create_footer_placement" class="form-select">
                                    <option value="bottom_bar">Footer Bottom Bar (Next to Copyright)</option>
                                    <option value="useful_links">Useful Links Widget (Footer Column 2)</option>
                                    <option value="both">Both (Bottom Bar & Useful Links)</option>
                                </select>
                            </div>

                            <!-- Settings Card: Switches -->
                            <div class="col-12">
                                <div class="p-3 border rounded-3 bg-light d-flex flex-wrap align-items-center justify-content-between gap-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="form-check form-switch m-0">
                                            <input class="form-check-input" type="checkbox" name="show_in_footer" id="create_show_in_footer" value="1" checked style="width: 44px; height: 22px;">
                                        </div>
                                        <label class="form-check-label fw-semibold cursor-pointer mb-0" for="create_show_in_footer">
                                            Show Link in Website Footer
                                        </label>
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="form-check form-switch m-0">
                                            <input class="form-check-input" type="checkbox" name="is_published" id="create_is_published" value="1" checked style="width: 44px; height: 22px;">
                                        </div>
                                        <label class="form-check-label fw-semibold cursor-pointer mb-0" for="create_is_published">
                                            Publish Page Live Immediately
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- WYSIWYG Rich Text Editor -->
                            <div class="col-12">
                                <label class="form-label fw-semibold text-dark mb-1">
                                    <i class="fa-solid fa-file-lines text-primary me-1"></i> Page Content (Rich Text Editor)
                                </label>
                                <textarea name="content" id="create_content" class="summernote-editor"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer bg-light py-3 px-4">
                        <button type="button" class="btn btn-outline-secondary px-3" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary px-4 fw-semibold">
                            <i class="fa-solid fa-check me-1"></i> Save & Create Page
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Page Modal (Wider modal-xl with Rich Text Editor) -->
    <div class="modal fade" id="editPageModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content border-0 shadow-lg rounded-3">
                <form action="" method="POST" id="editPageForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="template_type" id="edit_template_type">

                    <div class="modal-header bg-light py-3 px-4">
                        <h5 class="modal-title fw-bold">
                            <i class="fa-solid fa-pen-to-square text-primary me-2"></i> Edit Customer Page
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <!-- Title & Slug -->
                            <div class="col-md-7">
                                <label class="form-label fw-semibold text-dark">Page Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" id="edit_title" class="form-control" required>
                            </div>
                            <div class="col-md-5">
                                <label class="form-label fw-semibold text-dark">URL Slug <span class="text-danger">*</span></label>
                                <input type="text" name="slug" id="edit_slug" class="form-control" required>
                            </div>

                            <!-- Subtitle & Footer Placement -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-dark">Banner Subtitle / Tagline</label>
                                <input type="text" name="subtitle" id="edit_subtitle" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-dark"><i class="fa-solid fa-shoe-prints text-info me-1"></i> Footer Placement Location</label>
                                <select name="footer_placement" id="edit_footer_placement" class="form-select">
                                    <option value="bottom_bar">Footer Bottom Bar (Next to Copyright)</option>
                                    <option value="useful_links">Useful Links Widget (Footer Column 2)</option>
                                    <option value="both">Both (Bottom Bar & Useful Links)</option>
                                </select>
                            </div>

                            <!-- Settings Card: Switches -->
                            <div class="col-12">
                                <div class="p-3 border rounded-3 bg-light d-flex flex-wrap align-items-center justify-content-between gap-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="form-check form-switch m-0">
                                            <input class="form-check-input" type="checkbox" name="show_in_footer" id="edit_show_in_footer" value="1" style="width: 44px; height: 22px;">
                                        </div>
                                        <label class="form-check-label fw-semibold cursor-pointer mb-0" for="edit_show_in_footer">
                                            Show Link in Website Footer
                                        </label>
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="form-check form-switch m-0">
                                            <input class="form-check-input" type="checkbox" name="is_published" id="edit_is_published" value="1" style="width: 44px; height: 22px;">
                                        </div>
                                        <label class="form-check-label fw-semibold cursor-pointer mb-0" for="edit_is_published">
                                            Published Live
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- WYSIWYG Rich Text Editor -->
                            <div class="col-12">
                                <label class="form-label fw-semibold text-dark mb-1">
                                    <i class="fa-solid fa-file-lines text-primary me-1"></i> Page Content (Rich Text Editor)
                                </label>
                                <textarea name="content" id="edit_content" class="summernote-editor"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer bg-light py-3 px-4">
                        <button type="button" class="btn btn-outline-secondary px-3" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary px-4 fw-semibold">
                            <i class="fa-solid fa-floppy-disk me-1"></i> Update Page
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<!-- Summernote Lite JS -->
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.js"></script>

<script>
    const templatesData = @json($templates);

    $(document).ready(function() {
        // Initialize Summernote Rich Text Editor
        $('.summernote-editor').summernote({
            placeholder: 'Write your page content here...',
            tabsize: 2,
            height: 280,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'hr']],
                ['view', ['fullscreen', 'codeview']]
            ]
        });
    });

    function openTemplateModal(key) {
        const tmpl = templatesData[key];
        if (!tmpl) return;

        $('#createModalTitle').html(`<i class="${tmpl.icon} me-2" style="color: ${tmpl.color}"></i> Create ${tmpl.name}`);
        $('#create_template_type').val(key);
        $('#create_title').val(tmpl.title);
        $('#create_slug').val(tmpl.slug);
        $('#create_subtitle').val(tmpl.subtitle);
        $('#create_footer_placement').val(tmpl.footer_placement);
        $('#create_show_in_footer').prop('checked', true);
        $('#create_is_published').prop('checked', true);

        // Load content into Summernote Rich Text Editor
        $('#create_content').summernote('code', tmpl.content || '');

        const modal = new bootstrap.Modal(document.getElementById('createPageModal'));
        modal.show();
    }

    function openPageModal() {
        openTemplateModal('custom');
    }

    $(document).on('click', '.edit-page-btn', function() {
        const page = $(this).data('page');
        if (!page) return;

        $('#editPageForm').attr('action', `{{ url('admin/pages') }}/${page.id}`);
        $('#edit_template_type').val(page.template_type || 'custom');
        $('#edit_title').val(page.title);
        $('#edit_slug').val(page.slug);
        $('#edit_subtitle').val(page.subtitle || '');
        $('#edit_footer_placement').val(page.footer_placement || 'bottom_bar');
        $('#edit_show_in_footer').prop('checked', !!page.show_in_footer);
        $('#edit_is_published').prop('checked', !!page.is_published);

        // Load content into Summernote Rich Text Editor
        $('#edit_content').summernote('code', page.content || '');

        const modal = new bootstrap.Modal(document.getElementById('editPageModal'));
        modal.show();
    });
</script>
@endpush
