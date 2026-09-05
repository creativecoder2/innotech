@extends('admin.layouts.master')

@section('title', 'Customer Inquiries & Quotes')
@section('header_title', 'Inquiries & Quote Requests')

@push('styles')
<style>
    .inquiry-metric-card {
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-radius: 12px;
        padding: 16px 20px;
        display: flex;
        align-items: center;
        gap: 16px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.03);
        transition: all 0.25s ease;
    }
    .inquiry-metric-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0,0,0,0.06);
    }
    .metric-icon-box {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }
    .client-avatar {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: #EFF6FF;
        color: #0E63FF;
        font-weight: 700;
        font-size: 13px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        border: 1.5px solid #DBEAFE;
    }
    .table-actions-group {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
    }
    .table-actions-group .btn-action {
        width: 34px;
        height: 34px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        font-size: 13px;
        border: 1px solid #E2E8F0;
        background: #FFFFFF;
        transition: all 0.2s ease;
        cursor: pointer;
    }
    .table-actions-group .btn-action:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 6px rgba(0,0,0,0.08);
    }
    .btn-reply:hover {
        background: #F0FDF4 !important;
        color: #16A34A !important;
        border-color: #BBF7D0 !important;
    }
    .btn-view:hover {
        background: #EFF6FF !important;
        color: #0E63FF !important;
        border-color: #BFDBFE !important;
    }
    .btn-delete:hover {
        background: #FEF2F2 !important;
        color: #DC2626 !important;
        border-color: #FECACA !important;
    }
    .badge-inquiry-unread {
        background: #FEF3C7;
        color: #B45309;
        border: 1px solid #FDE68A;
        font-size: 11px;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 20px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    .badge-inquiry-unread::before {
        content: "";
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #D97706;
        display: inline-block;
        animation: pulseDot 1.5s infinite;
    }
    .badge-inquiry-read {
        background: #EFF6FF;
        color: #1D4ED8;
        border: 1px solid #BFDBFE;
        font-size: 11px;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 20px;
    }
    .badge-inquiry-replied {
        background: #ECFDF5;
        color: #047857;
        border: 1px solid #A7F3D0;
        font-size: 11px;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 20px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    @keyframes pulseDot {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.4; transform: scale(1.3); }
    }
    .filter-pill-btn {
        padding: 6px 14px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        color: #64748B;
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s;
    }
    .filter-pill-btn:hover, .filter-pill-btn.active {
        background: #0E63FF;
        color: #FFFFFF;
        border-color: #0E63FF;
    }
    .filter-pill-btn.active .badge-count {
        background: rgba(255, 255, 255, 0.25);
        color: #FFFFFF;
    }
    .badge-count {
        font-size: 11px;
        padding: 2px 7px;
        border-radius: 12px;
        background: #F1F5F9;
        color: #475569;
    }
</style>
@endpush

@section('content')

    <!-- Top Metric Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="inquiry-metric-card">
                <div class="metric-icon-box bg-primary-subtle text-primary">
                    <i class="fa-solid fa-inbox"></i>
                </div>
                <div>
                    <span class="text-muted small d-block">Total Inquiries</span>
                    <h4 class="mb-0 fw-bold text-dark" id="statTotalCount">{{ $stats['total'] }}</h4>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="inquiry-metric-card">
                <div class="metric-icon-box bg-warning-subtle text-warning">
                    <i class="fa-solid fa-envelope"></i>
                </div>
                <div>
                    <span class="text-muted small d-block">Unread Leads</span>
                    <h4 class="mb-0 fw-bold text-warning" id="statUnreadCount">{{ $stats['unread'] }}</h4>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="inquiry-metric-card">
                <div class="metric-icon-box bg-info-subtle text-info">
                    <i class="fa-solid fa-envelope-open-text"></i>
                </div>
                <div>
                    <span class="text-muted small d-block">Under Review</span>
                    <h4 class="mb-0 fw-bold text-info" id="statReadCount">{{ $stats['read'] }}</h4>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="inquiry-metric-card">
                <div class="metric-icon-box bg-success-subtle text-success">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
                <div>
                    <span class="text-muted small d-block">Replied / Resolved</span>
                    <h4 class="mb-0 fw-bold text-success" id="statRepliedCount">{{ $stats['replied'] }}</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Card -->
    <div class="admin-card">
        <!-- Card Header with Filters & Bulk Actions -->
        <div class="p-3 border-bottom bg-white rounded-top-3">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
                <div class="d-flex align-items-center gap-2">
                    <h6 class="fw-bold mb-0 text-dark fs-5">
                        <i class="fa-solid fa-envelope-open-text text-primary me-2"></i> Inquiries & Quotes
                    </h6>
                    <span class="badge bg-light text-muted border px-2.5 py-1 font-monospace" id="headerCountBadge">
                        {{ $inquiries->total() }} Records
                    </span>
                </div>

                <!-- Bulk Action Controls -->
                <div class="d-flex flex-wrap align-items-center gap-2">
                    <button type="button" class="btn btn-sm btn-outline-danger d-none" id="btnBulkDelete">
                        <i class="fa-solid fa-trash-can me-1"></i> Delete Selected (<span id="bulkSelectedCount">0</span>)
                    </button>
                    @if($stats['total'] > 0)
                        <button type="button" class="btn btn-sm btn-danger px-3 shadow-sm" id="btnDeleteAll">
                            <i class="fa-solid fa-trash-can me-1"></i> Delete All Inquiries
                        </button>
                    @endif
                </div>
            </div>

            <!-- Search Bar & Filter Tabs -->
            <div class="row g-2 align-items-center justify-content-between">
                <!-- Status Filter Pills -->
                <div class="col-12 col-md-auto d-flex flex-wrap gap-1.5">
                    @php
                        $currentStatus = request('status', '');
                    @endphp
                    <a href="{{ route('admin.inquiries.index', array_merge(request()->except(['status', 'page']), [])) }}" 
                       class="filter-pill-btn {{ empty($currentStatus) ? 'active' : '' }}">
                        All <span class="badge-count">{{ $stats['total'] }}</span>
                    </a>
                    <a href="{{ route('admin.inquiries.index', array_merge(request()->except(['status', 'page']), ['status' => 'unread'])) }}" 
                       class="filter-pill-btn {{ $currentStatus === 'unread' ? 'active' : '' }}">
                        Unread <span class="badge-count">{{ $stats['unread'] }}</span>
                    </a>
                    <a href="{{ route('admin.inquiries.index', array_merge(request()->except(['status', 'page']), ['status' => 'read'])) }}" 
                       class="filter-pill-btn {{ $currentStatus === 'read' ? 'active' : '' }}">
                        Read <span class="badge-count">{{ $stats['read'] }}</span>
                    </a>
                    <a href="{{ route('admin.inquiries.index', array_merge(request()->except(['status', 'page']), ['status' => 'replied'])) }}" 
                       class="filter-pill-btn {{ $currentStatus === 'replied' ? 'active' : '' }}">
                        Replied <span class="badge-count">{{ $stats['replied'] }}</span>
                    </a>
                </div>

                <!-- Live Search Form -->
                <div class="col-12 col-md-4 col-lg-3">
                    <form action="{{ route('admin.inquiries.index') }}" method="GET" class="d-flex gap-1">
                        @if(request('status'))
                            <input type="hidden" name="status" value="{{ request('status') }}">
                        @endif
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                            <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Search name, email, phone..." value="{{ request('search') }}">
                            @if(request('search'))
                                <a href="{{ route('admin.inquiries.index', request()->except(['search', 'page'])) }}" class="btn btn-outline-secondary border-start-0" title="Clear Search">
                                    <i class="fa-solid fa-xmark"></i>
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Table View -->
        <div class="table-responsive">
            <table class="table table-custom align-middle mb-0">
                <thead class="bg-light text-muted">
                    <tr>
                        <th width="35" class="text-center">
                            <input type="checkbox" class="form-check-input" id="selectAllInquiries" title="Select All On This Page">
                        </th>
                        <th width="45" class="text-center">#</th>
                        <th>Client Information</th>
                        <th>Phone / Contact</th>
                        <th>Service / Category</th>
                        <th>Subject & Query Snippet</th>
                        <th>Status</th>
                        <th>Received On</th>
                        <th width="120" class="text-end" style="white-space: nowrap;">Actions</th>
                    </tr>
                </thead>
                <tbody id="inquiriesTableBody">
                    @forelse($inquiries as $inquiry)
                        @php
                            $initials = '';
                            $words = explode(' ', trim($inquiry->name));
                            foreach(array_slice($words, 0, 2) as $w) {
                                $initials .= strtoupper(substr($w, 0, 1));
                            }
                            if (empty($initials)) $initials = 'IQ';
                        @endphp
                        <tr class="inquiry-row {{ $inquiry->status === 'unread' ? 'table-warning bg-opacity-10' : '' }}" 
                            id="inquiryRow-{{ $inquiry->id }}" 
                            data-inquiry-id="{{ $inquiry->id }}"
                            data-status="{{ $inquiry->status }}">
                            <td class="text-center">
                                <input type="checkbox" class="form-check-input inquiry-checkbox" value="{{ $inquiry->id }}">
                            </td>
                            <td class="text-center fw-semibold text-muted">
                                {{ $loop->iteration + ($inquiries->currentPage() - 1) * $inquiries->perPage() }}
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2.5">
                                    <div class="client-avatar">
                                        {{ $initials }}
                                    </div>
                                    <div>
                                        <strong class="text-dark d-block fw-semibold">{{ $inquiry->name }}</strong>
                                        <a href="mailto:{{ $inquiry->email }}" class="text-muted small text-decoration-none hover-primary">
                                            <i class="fa-regular fa-envelope me-1"></i>{{ $inquiry->email }}
                                        </a>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($inquiry->phone)
                                    <div class="d-flex align-items-center gap-2">
                                        <a href="tel:{{ $inquiry->phone }}" class="text-dark fw-medium small text-decoration-none">
                                            {{ $inquiry->phone }}
                                        </a>
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $inquiry->phone) }}" 
                                           target="_blank" 
                                           class="btn btn-xs btn-outline-success py-0 px-1.5 rounded" 
                                           title="Chat on WhatsApp"
                                           style="font-size: 11px;">
                                            <i class="fa-brands fa-whatsapp"></i>
                                        </a>
                                    </div>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-light text-primary border font-weight-500">
                                    {{ $inquiry->service_interested ?: ($inquiry->name === 'Newsletter Subscriber' ? 'Newsletter' : 'General Inquiry') }}
                                </span>
                            </td>
                            <td>
                                <span class="text-truncate d-inline-block text-dark small" style="max-width: 260px;" title="{{ $inquiry->subject ?: $inquiry->message }}">
                                    @if($inquiry->subject)
                                        <strong>{{ $inquiry->subject }}</strong> — 
                                    @endif
                                    {{ Str::limit($inquiry->message, 65) }}
                                </span>
                            </td>
                            <td>
                                @if($inquiry->status === 'unread')
                                    <span class="badge-inquiry-unread">Unread</span>
                                @elseif($inquiry->status === 'read')
                                    <span class="badge-inquiry-read"><i class="fa-regular fa-eye me-1"></i>Read</span>
                                @else
                                    <span class="badge-inquiry-replied"><i class="fa-solid fa-check"></i>Replied</span>
                                @endif
                            </td>
                            <td>
                                <small class="text-dark d-block fw-medium">{{ $inquiry->created_at->format('M d, Y') }}</small>
                                <small class="text-muted" style="font-size: 11px;">{{ $inquiry->created_at->format('h:i A') }}</small>
                            </td>
                            <td class="text-end" style="white-space: nowrap;">
                                <div class="table-actions-group">
                                    <button type="button" class="btn-action btn-reply text-success open-reply-modal" 
                                            data-inquiry='@json($inquiry)' title="Send Email Reply">
                                        <i class="fa-solid fa-reply"></i>
                                    </button>
                                    <a href="{{ route('admin.inquiries.show', $inquiry->id) }}" class="btn-action btn-view text-primary" title="View Full Details">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    <button type="button" class="btn-action btn-delete text-danger" 
                                            onclick="deleteInquiry({{ $inquiry->id }}, this)" 
                                            title="Delete Inquiry">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr id="emptyRowPlaceholder">
                            <td colspan="9" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-inbox fs-2 mb-2 d-block text-secondary opacity-50"></i>
                                No customer inquiries found matching the criteria.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($inquiries->hasPages())
            <div class="p-3 border-top d-flex flex-wrap justify-content-between align-items-center bg-light gap-2">
                <span class="text-muted small">Showing {{ $inquiries->firstItem() }} to {{ $inquiries->lastItem() }} of {{ $inquiries->total() }} leads</span>
                {{ $inquiries->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

    <!-- Email Reply Modal Popup -->
    <div class="modal fade" id="replyInquiryModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg rounded-3">
                <form action="" method="POST" id="replyInquiryForm">
                    @csrf
                    <div class="modal-header bg-light py-3 px-4">
                        <h5 class="modal-title fw-bold">
                            <i class="fa-solid fa-paper-plane text-primary me-2"></i> Reply to Inquiry / Lead
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold text-dark">Client Name</label>
                                <input type="text" id="reply_name" class="form-control form-control-sm bg-light" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold text-dark">Recipient Email Address</label>
                                <input type="email" id="reply_email" class="form-control form-control-sm bg-light" readonly>
                            </div>

                            <div class="col-12">
                                <label class="form-label small fw-semibold text-dark">Subject <span class="text-danger">*</span></label>
                                <input type="text" name="subject" id="reply_subject" class="form-control" required>
                            </div>

                            <div class="col-12">
                                <label class="form-label small fw-semibold text-dark">
                                    <i class="fa-solid fa-file-lines text-primary me-1"></i> Response Message Body (Pre-filled with Innotech Medical Template)
                                </label>
                                <textarea name="reply_body" id="reply_body" class="form-control" rows="10" required></textarea>
                                <small class="text-muted">You can edit or customize this response before sending.</small>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer bg-light py-3 px-4 d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary px-4 fw-semibold">
                            <i class="fa-solid fa-paper-plane me-1"></i> Send & Mark as Replied
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    // 1. Single Inquiry AJAX Delete (No Page Reload)
    window.deleteInquiry = function(id, btnElement) {
        Swal.fire({
            title: 'Delete Inquiry?',
            text: "Yeh customer inquiry permanently delete ho jayegi.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#EF4444',
            cancelButtonColor: '#64748B',
            confirmButtonText: '<i class="fa-solid fa-trash me-1"></i> Yes, Delete',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                const row = $(btnElement).closest('tr');
                const wasUnread = row.data('status') === 'unread';

                $.ajax({
                    url: `{{ url('admin/inquiries') }}/${id}`,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        row.fadeOut(300, function() {
                            $(this).remove();
                            recalcMetrics(1, wasUnread);
                        });

                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: response.message || 'Inquiry deleted successfully.',
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 2500
                        });
                    },
                    error: function(xhr) {
                        Swal.fire('Error', xhr.responseJSON?.message || 'Failed to delete inquiry.', 'error');
                    }
                });
            }
        });
    };

    // 2. Select All Checkbox Handler
    $('#selectAllInquiries').on('change', function() {
        const isChecked = $(this).is(':checked');
        $('.inquiry-checkbox').prop('checked', isChecked);
        updateBulkButtonState();
    });

    $(document).on('change', '.inquiry-checkbox', function() {
        const total = $('.inquiry-checkbox').length;
        const checked = $('.inquiry-checkbox:checked').length;
        $('#selectAllInquiries').prop('checked', total > 0 && total === checked);
        updateBulkButtonState();
    });

    function updateBulkButtonState() {
        const checkedCount = $('.inquiry-checkbox:checked').length;
        $('#bulkSelectedCount').text(checkedCount);
        if (checkedCount > 0) {
            $('#btnBulkDelete').removeClass('d-none');
        } else {
            $('#btnBulkDelete').addClass('d-none');
        }
    }

    // 3. Bulk Delete Selected AJAX (No Page Reload)
    $('#btnBulkDelete').on('click', function() {
        const selectedIds = $('.inquiry-checkbox:checked').map(function() {
            return $(this).val();
        }).get();

        if (selectedIds.length === 0) return;

        Swal.fire({
            title: `Delete ${selectedIds.length} Inquiries?`,
            text: `Selected ${selectedIds.length} inquiries permanently delete ho jayengi.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#EF4444',
            cancelButtonColor: '#64748B',
            confirmButtonText: `<i class="fa-solid fa-trash me-1"></i> Yes, Delete (${selectedIds.length})`,
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                const btn = $('#btnBulkDelete');
                btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin me-1"></i> Deleting...');

                $.ajax({
                    url: "{{ route('admin.inquiries.bulk_delete') }}",
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        ids: selectedIds
                    },
                    success: function(response) {
                        btn.prop('disabled', false);
                        $('#btnBulkDelete').addClass('d-none');
                        $('#selectAllInquiries').prop('checked', false);

                        let unreadRemoved = 0;
                        selectedIds.forEach(id => {
                            const row = $(`#inquiryRow-${id}`);
                            if (row.data('status') === 'unread') unreadRemoved++;
                            row.fadeOut(300, function() {
                                $(this).remove();
                            });
                        });

                        setTimeout(function() {
                            recalcMetrics(selectedIds.length, unreadRemoved);
                        }, 350);

                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: response.message,
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 2500
                        });
                    },
                    error: function(xhr) {
                        btn.prop('disabled', false).html('<i class="fa-solid fa-trash-can me-1"></i> Delete Selected');
                        Swal.fire('Error', xhr.responseJSON?.message || 'Failed to delete selected inquiries.', 'error');
                    }
                });
            }
        });
    });

    // 4. Delete ALL Inquiries AJAX (No Page Reload)
    $('#btnDeleteAll').on('click', function() {
        Swal.fire({
            title: 'Delete ALL Customer Inquiries?',
            text: "Kya aap waqai database se tamam inquiries ek sath delete karna chahte hain? Yeh action wapas nahi kiya ja sakega!",
            icon: 'error',
            showCancelButton: true,
            confirmButtonColor: '#DC2626',
            cancelButtonColor: '#64748B',
            confirmButtonText: '<i class="fa-solid fa-trash-can me-1"></i> Yes, Delete All Records',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                const btn = $(this);
                btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin me-1"></i> Deleting All...');

                $.ajax({
                    url: "{{ route('admin.inquiries.delete_all') }}",
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        $('#btnBulkDelete').addClass('d-none');
                        $('#btnDeleteAll').addClass('d-none');
                        
                        $('#inquiriesTableBody tr').fadeOut(300, function() {
                            $(this).remove();
                        });

                        setTimeout(function() {
                            $('#inquiriesTableBody').html(`
                                <tr id="emptyRowPlaceholder">
                                    <td colspan="9" class="text-center py-5 text-muted">
                                        <i class="fa-solid fa-inbox fs-2 mb-2 d-block text-secondary opacity-50"></i>
                                        No customer inquiries found.
                                    </td>
                                </tr>
                            `);
                            $('#statTotalCount').text('0');
                            $('#statUnreadCount').text('0');
                            $('#statReadCount').text('0');
                            $('#statRepliedCount').text('0');
                            $('#headerCountBadge').text('0 Records');
                        }, 350);

                        Swal.fire({
                            icon: 'success',
                            title: 'All Deleted!',
                            text: response.message,
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000
                        });
                    },
                    error: function(xhr) {
                        btn.prop('disabled', false).html('<i class="fa-solid fa-trash-can me-1"></i> Delete All Inquiries');
                        Swal.fire('Error', xhr.responseJSON?.message || 'Failed to delete all inquiries.', 'error');
                    }
                });
            }
        });
    });

    // Recalculate frontend stats smoothly
    function recalcMetrics(deletedCount, unreadCount) {
        const remainingRows = $('.inquiry-row').length;
        $('#headerCountBadge').text(remainingRows + ' Records');

        let total = parseInt($('#statTotalCount').text()) || 0;
        total = Math.max(0, total - deletedCount);
        $('#statTotalCount').text(total);

        if (unreadCount) {
            let unread = parseInt($('#statUnreadCount').text()) || 0;
            unread = Math.max(0, unread - (typeof unreadCount === 'number' ? unreadCount : 1));
            $('#statUnreadCount').text(unread);
        }

        if (remainingRows === 0) {
            $('#btnDeleteAll').addClass('d-none');
            $('#inquiriesTableBody').html(`
                <tr id="emptyRowPlaceholder">
                    <td colspan="9" class="text-center py-5 text-muted">
                        <i class="fa-solid fa-inbox fs-2 mb-2 d-block text-secondary opacity-50"></i>
                        No customer inquiries found.
                    </td>
                </tr>
            `);
        }
    }

    // 5. Open Reply Email Modal
    $(document).on('click', '.open-reply-modal', function() {
        const inq = $(this).data('inquiry');
        if (!inq) return;

        $('#replyInquiryForm').attr('action', `{{ url('admin/inquiries') }}/${inq.id}/reply`);
        $('#reply_name').val(inq.name);
        $('#reply_email').val(inq.email);

        let defaultSubject = '';
        let defaultBody = '';

        if (inq.name === 'Newsletter Subscriber' || (inq.message && inq.message.toLowerCase().includes('newsletter'))) {
            defaultSubject = 'Welcome to INNOTECH MEDICAL PVT LTD - Newsletter Subscription';
            defaultBody = `Dear Valued Healthcare Partner,

Thank you for subscribing to the INNOTECH MEDICAL PVT LTD newsletter!

As Pakistan's growing distributor of advanced biomedical instrumentation, diagnostic systems, and critical care solutions, we are committed to keeping healthcare professionals and institutions equipped with the latest clinical technologies.

You will now receive periodic updates regarding our equipment releases, preventative maintenance insights, and turnkey hospital solutions.

If you have an immediate equipment or technical requirement, please feel free to contact our biomedical desk at:
- Phone / WhatsApp: +92 331 6699992
- Support Email: info@innotechmed.com
- Office Address: 1st Floor, Plot: A-301, Sardar Ali Sabri Road, Block-2, Gulshan e Iqbal, Karachi, Sindh, Pakistan.

Warm regards,
Customer Engagement & Technical Desk
INNOTECH MEDICAL PVT LTD
www.innotechmed.com`;
        } else {
            const subjectSnippet = inq.subject ? inq.subject : (inq.service_interested ? inq.service_interested : 'Medical Equipment Inquiry');
            defaultSubject = `Re: ${subjectSnippet} - INNOTECH MEDICAL PVT LTD`;
            defaultBody = `Dear ${inq.name},

Thank you for reaching out to INNOTECH MEDICAL PVT LTD regarding your biomedical and clinical equipment requirements.

Our biomedical engineering department has received your request:
"${inq.message}"

We would be pleased to assist you with official technical specifications, quotation, hospital installation, and OEM warranty details for your facility. A dedicated technical consultant has been assigned to your request and can arrange an on-site demonstration or formal meeting.

Please let us know your preferred time or feel free to reach our administrative desk directly at:
- Help Desk: +92 331 6699992
- Email: info@innotechmed.com
- Office: 1st Floor, Plot: A-301, Sardar Ali Sabri Road, Block-2, Gulshan e Iqbal, Karachi, Pakistan.

Best regards,
Biomedical Support & Sales Team
INNOTECH MEDICAL PVT LTD
www.innotechmed.com`;
        }

        $('#reply_subject').val(defaultSubject);
        $('#reply_body').val(defaultBody);

        const modal = new bootstrap.Modal(document.getElementById('replyInquiryModal'));
        modal.show();
    });
</script>
@endpush
