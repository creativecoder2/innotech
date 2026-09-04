@extends('admin.layouts.master')

@section('title', 'Customer Inquiries & Quotes')
@section('header_title', 'Inquiries & Quote Requests')

@push('styles')
<style>
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
    .table-actions-group .btn-reply:hover {
        background: #F0FDF4;
        color: #16A34A;
        border-color: #BBF7D0;
    }
    .table-actions-group .btn-view:hover {
        background: #EFF6FF;
        color: #0E63FF;
        border-color: #BFDBFE;
    }
    .table-actions-group .btn-delete:hover {
        background: #FEF2F2;
        color: #DC2626;
        border-color: #FECACA;
    }
</style>
@endpush

@section('content')

    <div class="admin-card">
        <div class="admin-card-header d-flex justify-content-between align-items-center bg-light">
            <h6 class="fw-bold mb-0"><i class="fa-solid fa-envelope-open-text text-primary me-2"></i> All Inquiries & Newsletter Leads ({{ $inquiries->total() }})</h6>
            <span class="text-muted small">Live inquiries, quote requests, and newsletter subscribers.</span>
        </div>

        <div class="table-responsive">
            <table class="table table-custom align-middle mb-0">
                <thead>
                    <tr>
                        <th width="45" class="text-center">#</th>
                        <th>Client Details</th>
                        <th>Phone / WhatsApp</th>
                        <th>Service / Type</th>
                        <th>Subject / Query Snippet</th>
                        <th>Status</th>
                        <th>Received</th>
                        <th width="140" class="text-end" style="white-space: nowrap;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($inquiries as $inquiry)
                        <tr class="{{ $inquiry->status === 'unread' ? 'table-warning bg-opacity-10' : '' }}">
                            <td class="text-center fw-semibold text-muted">{{ $loop->iteration + ($inquiries->currentPage() - 1) * $inquiries->perPage() }}</td>
                            <td>
                                <strong class="text-dark">{{ $inquiry->name }}</strong><br>
                                <small class="text-muted"><a href="mailto:{{ $inquiry->email }}" class="text-decoration-none text-primary">{{ $inquiry->email }}</a></small>
                            </td>
                            <td>
                                @if($inquiry->phone)
                                    <a href="tel:{{ $inquiry->phone }}" class="text-decoration-none text-dark"><i class="fa-solid fa-phone text-success me-1"></i> {{ $inquiry->phone }}</a>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-light text-primary border">
                                    {{ $inquiry->service_interested ?: ($inquiry->name === 'Newsletter Subscriber' ? 'Newsletter' : 'General') }}
                                </span>
                            </td>
                            <td>
                                <span class="text-truncate d-inline-block" style="max-width: 250px;" title="{{ $inquiry->subject ?: $inquiry->message }}">
                                    {{ $inquiry->subject ?: $inquiry->message }}
                                </span>
                            </td>
                            <td>
                                <span class="badge-status {{ $inquiry->status }}">{{ ucfirst($inquiry->status) }}</span>
                            </td>
                            <td><small class="text-muted">{{ $inquiry->created_at->format('M d, Y h:i A') }}</small></td>
                            <td class="text-end" style="white-space: nowrap;">
                                <div class="table-actions-group">
                                    <button type="button" class="btn btn-reply text-success open-reply-modal" 
                                            data-inquiry='@json($inquiry)' title="Send Quick Reply Email">
                                        <i class="fa-solid fa-reply"></i>
                                    </button>
                                    <a href="{{ route('admin.inquiries.show', $inquiry->id) }}" class="btn btn-view text-primary" title="View Full Details">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    <form action="{{ route('admin.inquiries.destroy', $inquiry->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this inquiry?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-delete text-danger" title="Delete Inquiry">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-inbox fs-2 mb-2 d-block text-secondary opacity-50"></i>
                                No customer inquiries found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($inquiries->hasPages())
            <div class="p-3 border-top d-flex justify-content-between align-items-center bg-light">
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
                            <i class="fa-solid fa-check me-1"></i> Send & Mark as Replied
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    $(document).on('click', '.open-reply-modal', function() {
        const inq = $(this).data('inquiry');
        if (!inq) return;

        $('#replyInquiryForm').attr('action', `{{ url('admin/inquiries') }}/${inq.id}/reply`);
        $('#reply_name').val(inq.name);
        $('#reply_email').val(inq.email);

        let defaultSubject = '';
        let defaultBody = '';

        if (inq.name === 'Newsletter Subscriber' || inq.message.includes('newsletter')) {
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
