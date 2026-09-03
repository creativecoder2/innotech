@extends('admin.layouts.master')

@section('title', 'Inquiry Details')
@section('header_title', 'Inquiry from ' . $inquiry->name)

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="{{ route('admin.inquiries.index') }}" class="btn btn-outline-secondary">
            <i class="fa-solid fa-arrow-left mr-5"></i> Back to Inquiries
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="admin-card">
                <div class="admin-card-header">
                    <h5><i class="fa-solid fa-envelope-open text-primary mr-10"></i> Inquiry Content</h5>
                    <span class="badge-status {{ $inquiry->status }}">{{ ucfirst($inquiry->status) }}</span>
                </div>
                <div class="admin-card-body">
                    <div class="mb-4">
                        <h6 class="text-muted small text-uppercase font-weight-bold">Subject / Hospital Name</h6>
                        <h5 style="color: #002244; font-weight: 700;">{{ $inquiry->subject ?: 'General Innotech Inquiry' }}</h5>
                    </div>

                    <div class="mb-4">
                        <h6 class="text-muted small text-uppercase font-weight-bold">Service / Equipment Interested In</h6>
                        <span class="badge bg-light text-primary px-3 py-2" style="font-size: 14px;">{{ $inquiry->service_interested ?: 'General Inquiry' }}</span>
                    </div>

                    <div class="mb-4">
                        <h6 class="text-muted small text-uppercase font-weight-bold">Message & Requirements</h6>
                        <div class="p-3 rounded bg-light" style="font-size: 15px; line-height: 1.8; color: #1e293b;">
                            {!! nl2br(e($inquiry->message)) !!}
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="button" class="btn-theme open-reply-modal" data-inquiry='@json($inquiry)'>
                            <i class="fa-solid fa-reply me-1"></i> Compose Reply Popup
                        </button>
                        @if($inquiry->phone)
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $inquiry->phone) }}" target="_blank" class="btn btn-success" style="background-color: #25D366; border: none; font-weight: 600;">
                                <i class="fa-brands fa-whatsapp me-1"></i> WhatsApp Client
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="admin-card">
                <div class="admin-card-header">
                    <h5>Client Information</h5>
                </div>
                <div class="admin-card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-3">
                            <small class="text-muted d-block">Full Name</small>
                            <strong style="color: #002244;">{{ $inquiry->name }}</strong>
                        </li>
                        <li class="mb-3">
                            <small class="text-muted d-block">Email Address</small>
                            <a href="mailto:{{ $inquiry->email }}" class="text-decoration-none text-primary">{{ $inquiry->email }}</a>
                        </li>
                        <li class="mb-3">
                            <small class="text-muted d-block">Phone / WhatsApp</small>
                            <strong>{{ $inquiry->phone ?: 'Not provided' }}</strong>
                        </li>
                        <li class="mb-3">
                            <small class="text-muted d-block">Received On</small>
                            <span>{{ $inquiry->created_at->format('d M Y, h:i A') }}</span>
                        </li>
                    </ul>

                    <hr>

                    <form action="{{ route('admin.inquiries.status', $inquiry->id) }}" method="POST">
                        @csrf
                        <label class="form-label font-weight-bold">Update Lead Status</label>
                        <select name="status" class="form-select mb-3">
                            <option value="unread" {{ $inquiry->status === 'unread' ? 'selected' : '' }}>Unread</option>
                            <option value="read" {{ $inquiry->status === 'read' ? 'selected' : '' }}>Read</option>
                            <option value="replied" {{ $inquiry->status === 'replied' ? 'selected' : '' }}>Replied / In Progress</option>
                        </select>
                        <button type="submit" class="btn btn-outline-primary w-100 font-weight-bold">Update Status</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Email Reply Modal Popup -->
    <div class="modal fade" id="replyInquiryModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg rounded-3">
                <form action="{{ route('admin.inquiries.reply', $inquiry->id) }}" method="POST" id="replyInquiryForm">
                    @csrf
                    <div class="modal-header bg-light py-3 px-4">
                        <h5 class="modal-title fw-bold">
                            <i class="fa-solid fa-paper-plane text-primary me-2"></i> Reply to {{ $inquiry->name }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold text-dark">Client Name</label>
                                <input type="text" value="{{ $inquiry->name }}" class="form-control form-control-sm bg-light" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold text-dark">Recipient Email Address</label>
                                <input type="email" id="reply_email" value="{{ $inquiry->email }}" class="form-control form-control-sm bg-light" readonly>
                            </div>

                            <div class="col-12">
                                <label class="form-label small fw-semibold text-dark">Subject <span class="text-danger">*</span></label>
                                @php
                                    $defaultSubject = $inquiry->name === 'Newsletter Subscriber' ? 'Welcome to INNOTECH MEDICAL PVT LTD - Newsletter Subscription' : 'Re: ' . ($inquiry->subject ?: 'Medical Equipment Inquiry') . ' - INNOTECH MEDICAL PVT LTD';
                                @endphp
                                <input type="text" name="subject" id="reply_subject" value="{{ $defaultSubject }}" class="form-control" required>
                            </div>

                            <div class="col-12">
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <label class="form-label small fw-semibold text-dark mb-0">
                                        <i class="fa-solid fa-file-lines text-primary me-1"></i> Response Message Body (Pre-filled with Innotech Medical Template)
                                    </label>
                                    <span class="badge bg-primary-subtle text-primary border px-2 py-1 small">
                                        <i class="fa-solid fa-paper-plane me-1"></i> From: {{ config('mail.from.address', 'info@innotechmed.com') }}
                                    </span>
                                </div>
                                @php
                                    if ($inquiry->name === 'Newsletter Subscriber' || str_contains(strtolower($inquiry->message), 'newsletter')) {
                                        $defaultBody = "Dear Valued Healthcare Partner,\n\nThank you for subscribing to the INNOTECH MEDICAL PVT LTD newsletter!\n\nAs Pakistan's growing distributor of advanced biomedical instrumentation, diagnostic systems, and critical care solutions, we are committed to keeping healthcare professionals and institutions equipped with the latest clinical technologies.\n\nYou will now receive periodic updates regarding our equipment releases, preventative maintenance insights, and turnkey hospital solutions.\n\nIf you have an immediate equipment or technical requirement, please feel free to contact our biomedical desk at:\n- Phone / WhatsApp: +92 331 6699992\n- Support Email: info@innotechmed.com\n- Office Address: 1st Floor, Plot: A-301, Sardar Ali Sabri Road, Block-2, Gulshan e Iqbal, Karachi, Sindh, Pakistan.\n\nWarm regards,\nCustomer Engagement & Technical Desk\nINNOTECH MEDICAL PVT LTD\nwww.innotechmed.com";
                                    } else {
                                        $defaultBody = "Dear {$inquiry->name},\n\nThank you for reaching out to INNOTECH MEDICAL PVT LTD regarding your biomedical and clinical equipment requirements.\n\nOur biomedical engineering department has received your request:\n\"{$inquiry->message}\"\n\nWe would be pleased to assist you with official technical specifications, quotation, hospital installation, and OEM warranty details for your facility. A dedicated technical consultant has been assigned to your request and can arrange an on-site demonstration or formal meeting.\n\nPlease let us know your preferred time or feel free to reach our administrative desk directly at:\n- Help Desk: +92 331 6699992\n- Email: info@innotechmed.com\n- Office: 1st Floor, Plot: A-301, Sardar Ali Sabri Road, Block-2, Gulshan e Iqbal, Karachi, Pakistan.\n\nBest regards,\nBiomedical Support & Sales Team\nINNOTECH MEDICAL PVT LTD\nwww.innotechmed.com";
                                    }
                                @endphp
                                <textarea name="reply_body" id="reply_body" class="form-control" rows="10" required>{{ $defaultBody }}</textarea>
                                <small class="text-muted">You can edit or customize this response before sending. The reply will be delivered directly from info@innotechmed.com.</small>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer bg-light py-3 px-4 d-flex justify-content-between">
                        @php
                            $mailtoUrl = "mailto:" . rawurlencode($inquiry->email) . "?subject=" . rawurlencode($defaultSubject) . "&body=" . rawurlencode($defaultBody);
                        @endphp
                        <a href="{{ $mailtoUrl }}" id="replyMailtoBtn" class="btn btn-outline-success">
                            <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> Open in Outlook / Gmail
                        </a>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary px-4 fw-semibold">
                                <i class="fa-solid fa-check me-1"></i> Send & Mark as Replied
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    $(document).on('click', '.open-reply-modal', function() {
        const modal = new bootstrap.Modal(document.getElementById('replyInquiryModal'));
        modal.show();
    });

    $('#reply_subject, #reply_body').on('input', function() {
        const email = $('#reply_email').val();
        const subject = $('#reply_subject').val();
        const body = $('#reply_body').val();
        const mailtoUrl = `mailto:${encodeURIComponent(email)}?subject=${encodeURIComponent(subject)}&body=${encodeURIComponent(body)}`;
        $('#replyMailtoBtn').attr('href', mailtoUrl);
    });
</script>
@endpush
