@extends('admin.layouts.master')

@section('title', 'Live Support Chat Desk')
@section('header_title', 'Live Support Chat Desk')

@push('styles')
<style>
    .chat-console-wrapper {
        background: #ffffff;
        border-radius: 14px;
        border: 1px solid #E2E8F0;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        display: flex;
        height: calc(100vh - 190px);
        min-height: 600px;
        overflow: hidden;
    }

    /* Left Sidebar: Conversations List */
    .chat-sidebar {
        width: 350px;
        border-right: 1px solid #E2E8F0;
        display: flex;
        flex-direction: column;
        background: #F8FAFC;
    }

    .chat-sidebar-header {
        padding: 14px 16px;
        background: #ffffff;
        border-bottom: 1px solid #E2E8F0;
    }

    .chat-conversations-list {
        flex: 1;
        overflow-y: auto;
    }

    .chat-conv-item {
        padding: 14px 16px;
        border-bottom: 1px solid #EDF2F7;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        gap: 12px;
        background: #ffffff;
        position: relative;
    }

    .chat-conv-item:hover {
        background: #F1F5F9;
    }

    .chat-conv-item.active {
        background: #EFF6FF;
        border-left: 4px solid #0E63FF;
    }

    .chat-conv-avatar {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background: linear-gradient(135deg, #0E63FF 0%, #0044CC 100%);
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 15px;
        flex-shrink: 0;
    }

    .chat-conv-info {
        flex: 1;
        min-width: 0;
    }

    .chat-conv-name {
        font-weight: 600;
        color: #1E293B;
        font-size: 13.5px;
        margin-bottom: 2px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .chat-conv-snippet {
        color: #64748B;
        font-size: 12px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        margin-bottom: 5px;
    }

    .chat-conv-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 11px;
        color: #94A3B8;
    }

    /* Right Main Panel: Active Conversation */
    .chat-main-panel {
        flex: 1;
        display: flex;
        flex-direction: column;
        background: #ffffff;
    }

    .chat-main-header {
        padding: 14px 20px;
        background: #ffffff;
        border-bottom: 1px solid #E2E8F0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        min-height: 70px;
    }

    .chat-message-stream {
        flex: 1;
        padding: 20px;
        overflow-y: auto;
        background: #F8FAFC;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .admin-msg-bubble {
        max-width: 72%;
        padding: 11px 16px;
        border-radius: 14px;
        font-size: 13.5px;
        line-height: 1.5;
        position: relative;
        word-wrap: break-word;
    }

    .admin-msg-bubble.user {
        align-self: flex-start;
        background: #ffffff;
        color: #1E293B;
        border: 1px solid #E2E8F0;
        border-bottom-left-radius: 3px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.03);
    }

    .admin-msg-bubble.admin {
        align-self: flex-end;
        background: #0E63FF;
        color: #ffffff;
        border-bottom-right-radius: 3px;
        box-shadow: 0 4px 12px rgba(14, 99, 255, 0.18);
    }

    .admin-msg-bubble.bot {
        align-self: center;
        background: #EFF6FF;
        color: #1E40AF;
        border: 1px solid #DBEAFE;
        font-size: 12.5px;
        max-width: 85%;
        text-align: center;
        border-radius: 10px;
    }

    .admin-msg-meta {
        font-size: 10.5px;
        margin-top: 4px;
        opacity: 0.75;
        display: block;
        text-align: right;
    }

    .admin-msg-bubble.user .admin-msg-meta {
        text-align: left;
    }

    .chat-reply-bar {
        padding: 14px 20px;
        background: #ffffff;
        border-top: 1px solid #E2E8F0;
    }

    /* Voice Notes & Recording Pulse */
    .recording-pulse-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #EF4444;
        animation: recPulse 1s infinite alternate;
        display: inline-block;
    }
    @keyframes recPulse {
        from { opacity: 0.3; transform: scale(0.85); }
        to { opacity: 1; transform: scale(1.2); }
    }

    .voice-bubble-wrapper {
        min-width: 230px;
        max-width: 300px;
        background: rgba(255, 255, 255, 0.15);
        padding: 8px 10px;
        border-radius: 12px;
    }
    .admin-msg-bubble.user .voice-bubble-wrapper {
        background: #f8fafc;
    }
    .chat-audio-ctrl {
        width: 100%;
        height: 38px;
        border-radius: 20px;
        outline: none;
        display: block;
    }
</style>
@endpush

@section('content')

    <!-- Top Action Bar -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
        <div>
            <h5 class="fw-bold mb-1">
                <i class="fa-solid fa-headset text-primary me-2"></i> Live Support Chat Desk
                <span class="badge bg-danger rounded-pill ms-2 fs-6" id="totalUnreadPill">{{ $unreadTotal }} Unread</span>
            </h5>
            <small class="text-muted">Real-time inquiries and chat messages from visitors across Pakistan.</small>
        </div>

        <div class="d-flex align-items-center gap-2">
            <!-- Sound Alert Toggle -->
            <button type="button" class="btn btn-sm btn-outline-secondary" id="toggleSoundBtn" title="Toggle Sound Chime">
                <i class="fa-solid fa-volume-high text-success me-1" id="soundIcon"></i>
                <span id="soundText">Sound Alert: ON</span>
            </button>

            <!-- Settings Modal Button -->
            <button type="button" class="btn btn-sm btn-primary fw-semibold" data-bs-toggle="modal" data-bs-target="#chatSettingsModal">
                <i class="fa-solid fa-sliders me-1"></i> Chat & WhatsApp Settings
            </button>
        </div>
    </div>

    <!-- Main Live Chat Console -->
    <div class="chat-console-wrapper">
        
        <!-- Left: Conversations List -->
        <div class="chat-sidebar">
            <div class="chat-sidebar-header">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                    <input type="text" id="searchChatInput" class="form-control bg-light border-start-0" placeholder="Search by name, phone...">
                </div>
            </div>

            <div class="chat-conversations-list" id="conversationsContainer">
                @forelse($conversations as $conv)
                    <div class="chat-conv-item {{ $loop->first ? 'active' : '' }}" data-id="{{ $conv->id }}" onclick="selectConversation({{ $conv->id }})">
                        <div class="chat-conv-avatar">
                            {{ strtoupper(substr($conv->name, 0, 1)) }}
                        </div>
                        <div class="chat-conv-info">
                            <div class="chat-conv-name">
                                <span class="text-truncate">{{ $conv->name }}</span>
                                <small class="text-muted fw-normal chat-conv-time" style="font-size: 11px;">
                                    {{ $conv->last_message_at ? $conv->last_message_at->diffForHumans(null, true) : $conv->created_at->diffForHumans(null, true) }}
                                </small>
                            </div>
                            <div class="chat-conv-snippet text-truncate">
                                @if($conv->latestMessage && $conv->latestMessage->sender_type === 'admin')
                                    <strong class="text-primary">You: </strong>
                                @endif
                                @if($conv->latestMessage && $conv->latestMessage->type === 'audio')
                                    🎤 Voice note
                                @else
                                    {{ $conv->latestMessage ? $conv->latestMessage->message : 'Started inquiry...' }}
                                @endif
                            </div>
                            <div class="chat-conv-meta">
                                <span><i class="fa-solid fa-phone me-1"></i>{{ $conv->phone }}</span>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="chat-conv-badge-area">
                                        @if($conv->unread_admin > 0)
                                            <span class="badge bg-danger rounded-pill unread-badge">{{ $conv->unread_admin }} new</span>
                                        @else
                                            <span class="badge bg-light text-secondary border">{{ ucfirst($conv->status) }}</span>
                                        @endif
                                    </div>
                                    <button type="button" class="btn btn-link text-danger p-0 border-0 btn-delete-item" onclick="deleteConversation(event, {{ $conv->id }})" title="Delete Conversation" style="opacity: 0.6;">
                                        <i class="fa-solid fa-trash-can" style="font-size: 12px;"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5 text-muted">
                        <i class="fa-solid fa-comments fs-1 mb-2 text-secondary opacity-50 d-block"></i>
                        No conversations yet.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Right: Active Chat View & Reply Bar -->
        <div class="chat-main-panel" id="chatMainPanel">
            <!-- Active Chat Header -->
            <div class="chat-main-header" id="activeChatHeader">
                <div class="d-flex align-items-center gap-3">
                    <div class="chat-conv-avatar" id="activeAvatar">
                        @if($conversations->first())
                            {{ strtoupper(substr($conversations->first()->name, 0, 1)) }}
                        @else
                            ?
                        @endif
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0 text-dark" id="activeCustomerName">
                            {{ $conversations->first() ? $conversations->first()->name : 'Select a conversation' }}
                        </h6>
                        <div class="d-flex align-items-center gap-3 text-muted small mt-1">
                            <span id="activeCustomerPhone">
                                <i class="fa-solid fa-phone text-primary me-1"></i>{{ $conversations->first() ? $conversations->first()->phone : '—' }}
                            </span>
                            <span id="activeCustomerEmail">
                                <i class="fa-solid fa-envelope text-primary me-1"></i>{{ $conversations->first() && $conversations->first()->email ? $conversations->first()->email : 'No email' }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <a href="#" id="activeWhatsAppDirect" target="_blank" class="btn btn-sm btn-outline-success">
                        <i class="fa-brands fa-whatsapp me-1"></i> WhatsApp
                    </a>
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="btnToggleStatus" onclick="toggleActiveStatus()">
                        <i class="fa-solid fa-box-archive me-1"></i> Close Chat
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-danger" id="btnDeleteActiveChat" onclick="deleteActiveConversation()" title="Permanently Delete This Chat">
                        <i class="fa-solid fa-trash-can me-1"></i> Delete
                    </button>
                </div>
            </div>

            <!-- Messages Stream -->
            <div class="chat-message-stream" id="messagesStream">
                <div class="text-center py-5 text-muted" id="chatLoadingState">
                    <span class="spinner-border spinner-border-sm me-2"></span> Loading conversation...
                </div>
            </div>

            <!-- Reply Bar -->
            <div class="chat-reply-bar">
                <form id="adminReplyForm" class="d-flex align-items-center gap-2 m-0">
                    @csrf
                    <input type="text" id="adminReplyInput" class="form-control rounded-pill px-4" placeholder="Type a reply to customer..." autocomplete="off">
                    
                    <!-- Admin Voice Record Button -->
                    <button type="button" id="btnAdminRecordVoice" class="btn btn-outline-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; flex-shrink: 0;" title="Record Voice Message">
                        <i class="fa-solid fa-microphone"></i>
                    </button>

                    <button type="submit" id="btnAdminSend" class="btn btn-primary px-4 rounded-pill fw-semibold" style="white-space: nowrap;">
                        <i class="fa-solid fa-paper-plane me-1"></i> Send Reply
                    </button>
                </form>

                <!-- Admin Active Recording Bar -->
                <div id="adminVoiceRecordingBar" class="d-none align-items-center justify-content-between px-3 py-2 bg-light rounded-pill border">
                    <div class="d-flex align-items-center gap-3">
                        <span class="recording-pulse-dot"></span>
                        <span id="adminRecordTimer" class="fw-bold text-danger fs-6">00:00</span>
                        <span class="text-muted small">Recording voice message to customer...</span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <button type="button" id="btnAdminCancelVoice" class="btn btn-sm btn-outline-danger rounded-pill px-3" title="Cancel & Discard">
                            <i class="fa-solid fa-trash-can me-1"></i> Cancel
                        </button>
                        <button type="button" id="btnAdminSendVoice" class="btn btn-sm btn-success rounded-pill px-4 fw-semibold" title="Send Voice Message">
                            <i class="fa-solid fa-paper-plane me-1"></i> Send Voice
                        </button>
                    </div>
                </div>

                <!-- Admin Chat Closed Banner -->
                <div id="adminChatClosedBanner" class="d-none align-items-center justify-content-between px-4 py-2 bg-light rounded-pill border">
                    <div class="d-flex align-items-center gap-2 text-muted small">
                        <i class="fa-solid fa-lock text-warning fs-5"></i>
                        <span>This conversation is currently <strong>closed</strong>. Re-open the chat to send messages to the customer.</span>
                    </div>
                    <button type="button" class="btn btn-sm btn-primary rounded-pill px-3 py-1 fw-semibold" onclick="toggleActiveStatus()">
                        <i class="fa-solid fa-rotate-left me-1"></i> Re-open Chat
                    </button>
                </div>
            </div>
        </div>

    </div>

    <!-- Live Chat & WhatsApp Settings Modal -->
    <div class="modal fade" id="chatSettingsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg rounded-3">
                <form action="{{ route('admin.live_chat.settings') }}" method="POST">
                    @csrf
                    <div class="modal-header bg-light py-3 px-4">
                        <h5 class="modal-title fw-bold">
                            <i class="fa-solid fa-sliders text-primary me-2"></i> Live Chat & WhatsApp Configuration
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body p-4">
                        <div class="row g-4">
                            
                            <!-- WhatsApp Configuration -->
                            <div class="col-12">
                                <div class="p-3 border rounded-3 bg-light">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h6 class="fw-bold mb-0 text-success">
                                            <i class="fa-brands fa-whatsapp me-2 fs-5"></i> Floating WhatsApp Button
                                        </h6>
                                        <div class="form-check form-switch m-0">
                                            <input class="form-check-input" type="checkbox" name="whatsapp_enabled" id="whatsapp_enabled" value="1" {{ $settings['whatsapp_enabled'] == '1' ? 'checked' : '' }} style="width: 44px; height: 22px;">
                                            <label class="form-check-label fw-semibold ms-2" for="whatsapp_enabled">Show on Website</label>
                                        </div>
                                    </div>

                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label small fw-semibold">WhatsApp Number (with country code)</label>
                                            <input type="text" name="whatsapp_phone" class="form-control form-control-sm" value="{{ $settings['whatsapp_phone'] }}" placeholder="+92 331 6699992" required>
                                            <small class="text-muted">Direct phone number clicked by website visitors.</small>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label small fw-semibold">Default Pre-filled WhatsApp Text</label>
                                            <input type="text" name="whatsapp_default_message" class="form-control form-control-sm" value="{{ $settings['whatsapp_default_message'] }}" required>
                                            <small class="text-muted">Message already typed when visitor opens WhatsApp.</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Live Chat Configuration -->
                            <div class="col-12">
                                <div class="p-3 border rounded-3 bg-light">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h6 class="fw-bold mb-0 text-primary">
                                            <i class="fa-solid fa-headset me-2 fs-5"></i> Live Support Chat Widget
                                        </h6>
                                        <div class="form-check form-switch m-0">
                                            <input class="form-check-input" type="checkbox" name="chat_enabled" id="chat_enabled" value="1" {{ $settings['chat_enabled'] == '1' ? 'checked' : '' }} style="width: 44px; height: 22px;">
                                            <label class="form-check-label fw-semibold ms-2" for="chat_enabled">Show on Website</label>
                                        </div>
                                    </div>

                                    <div class="row g-3">
                                        <div class="col-12">
                                            <label class="form-label small fw-semibold">
                                                <i class="fa-solid fa-robot text-primary me-1"></i> Message 1: Automatic Welcome Greeting (Starts Chat)
                                            </label>
                                            <textarea name="chat_welcome_message" class="form-control form-control-sm" rows="2" required>{{ $settings['chat_welcome_message'] }}</textarea>
                                            <small class="text-muted">Sent 3 seconds after visitor fills pre-chat inquiry details.</small>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label small fw-semibold">
                                                <i class="fa-solid fa-heart text-danger me-1"></i> Message 2: Automatic Thank You / Acknowledgement (Second Message)
                                            </label>
                                            <textarea name="chat_second_message" class="form-control form-control-sm" rows="2" required>{{ $settings['chat_second_message'] }}</textarea>
                                            <small class="text-muted">Sent 3 seconds after visitor submits their 2nd message in chat.</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="modal-footer bg-light py-3 px-4">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary px-4 fw-semibold">
                            <i class="fa-solid fa-floppy-disk me-1"></i> Save Configuration
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    const chatApiBase = '{{ url("admin/live-chat") }}';
    let currentConversationId = {{ $conversations->first() ? $conversations->first()->id : 'null' }};
    let soundEnabled = true;
    let knownUnreadCount = {{ $unreadTotal }};
    let feedInterval = null;

    // Web Audio Harmonious Chime
    function playChimeAlert() {
        if (!soundEnabled) return;
        try {
            const ctx = new (window.AudioContext || window.webkitAudioContext)();
            
            // Tone 1
            const osc1 = ctx.createOscillator();
            const gain1 = ctx.createGain();
            osc1.connect(gain1);
            gain1.connect(ctx.destination);
            osc1.frequency.setValueAtTime(523.25, ctx.currentTime); // C5
            gain1.gain.setValueAtTime(0.25, ctx.currentTime);
            gain1.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.3);
            osc1.start(ctx.currentTime);
            osc1.stop(ctx.currentTime + 0.3);

            // Tone 2
            const osc2 = ctx.createOscillator();
            const gain2 = ctx.createGain();
            osc2.connect(gain2);
            gain2.connect(ctx.destination);
            osc2.frequency.setValueAtTime(659.25, ctx.currentTime + 0.12); // E5
            gain2.gain.setValueAtTime(0.25, ctx.currentTime + 0.12);
            gain2.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.5);
            osc2.start(ctx.currentTime + 0.12);
            osc2.stop(ctx.currentTime + 0.5);
        } catch(e) {}
    }

    // Sound toggle
    $('#toggleSoundBtn').on('click', function() {
        soundEnabled = !soundEnabled;
        if (soundEnabled) {
            $('#soundIcon').removeClass('fa-volume-xmark text-danger').addClass('fa-volume-high text-success');
            $('#soundText').text('Sound Alert: ON');
            playChimeAlert();
        } else {
            $('#soundIcon').removeClass('fa-volume-high text-success').addClass('fa-volume-xmark text-danger');
            $('#soundText').text('Sound Alert: OFF');
        }
    });

    // Select Conversation
    function selectConversation(id) {
        currentConversationId = id;
        $('.chat-conv-item').removeClass('active');
        $(`.chat-conv-item[data-id="${id}"]`).addClass('active');

        // Clear unread badge in item
        $(`.chat-conv-item[data-id="${id}"] .unread-badge`).remove();

        loadMessages(id);
    }

    // Load Messages for active conversation
    function loadMessages(id) {
        if (!id) return;

        fetch(`${chatApiBase}/${id}/messages`)
            .then(res => res.json())
            .then(data => {
                const conv = data.conversation;
                const messages = data.messages;

                // Update Header
                $('#activeAvatar').text(conv.name.charAt(0).toUpperCase());
                $('#activeCustomerName').text(conv.name);
                $('#activeCustomerPhone').html(`<i class="fa-solid fa-phone text-primary me-1"></i><a href="tel:${conv.phone}" class="text-decoration-none text-muted">${conv.phone}</a>`);
                $('#activeCustomerEmail').html(`<i class="fa-solid fa-envelope text-primary me-1"></i>${conv.email || 'No email'}`);
                
                const cleanPhone = conv.phone.replace(/[^0-9]/g, '');
                $('#activeWhatsAppDirect').attr('href', `https://wa.me/${cleanPhone}`);
                
                // Toggle Closed State UI for Admin
                if (conv.status === 'closed') {
                    $('#btnToggleStatus').html('<i class="fa-solid fa-rotate-left me-1"></i> Re-open').removeClass('btn-outline-secondary').addClass('btn-outline-success');
                    $('#adminReplyForm').addClass('d-none');
                    $('#adminVoiceRecordingBar').addClass('d-none').removeClass('d-flex');
                    $('#adminChatClosedBanner').removeClass('d-none').addClass('d-flex');
                } else {
                    $('#btnToggleStatus').html('<i class="fa-solid fa-box-archive me-1"></i> Close Chat').removeClass('btn-outline-success').addClass('btn-outline-secondary');
                    $('#adminChatClosedBanner').addClass('d-none').removeClass('d-flex');
                    $('#adminReplyForm').removeClass('d-none');
                }
                
                $('#adminReplyInput').attr('placeholder', `Type a reply to ${conv.name}...`);

                // Render Messages
                const stream = $('#messagesStream');
                stream.empty();

                if (!messages || messages.length === 0) {
                    stream.html('<div class="text-center py-5 text-muted">No messages yet.</div>');
                    return;
                }

                messages.forEach(msg => {
                    const dateObj = new Date(msg.created_at);
                    const timeStr = dateObj.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                    let bubble = '';

                    let msgContent = `<div>${escapeHtml(msg.message)}</div>`;
                    if (msg.type === 'audio' || msg.attachment) {
                        const assetRoot = '{{ asset("") }}';
                        const audioSrc = (msg.attachment_url) ? msg.attachment_url : (msg.attachment && msg.attachment.startsWith('http') ? msg.attachment : `${assetRoot}${msg.attachment}`);
                        msgContent = `
                            <div class="voice-bubble-wrapper">
                                <div class="d-flex align-items-center gap-1 mb-1">
                                    <i class="fa-solid fa-microphone me-1 ${msg.sender_type === 'admin' ? 'text-white' : 'text-primary'}"></i>
                                    <span class="small fw-bold">${msg.sender_type === 'admin' ? 'Admin Voice Note' : 'Customer Voice Note'}</span>
                                </div>
                                <audio controls preload="metadata" class="chat-audio-ctrl" src="${audioSrc}"></audio>
                                ${msg.message && msg.message !== 'Voice message' && msg.message !== 'Voice Note' && msg.message !== 'Voice message from support' ? `<div class="mt-1 small">${escapeHtml(msg.message)}</div>` : ''}
                            </div>
                        `;
                    }

                    if (msg.sender_type === 'user') {
                        bubble = `
                            <div class="admin-msg-bubble user">
                                <span class="fw-bold text-primary small d-block mb-1"><i class="fa-solid fa-user me-1"></i> ${escapeHtml(conv.name)}:</span>
                                ${msgContent}
                                <span class="admin-msg-meta">${timeStr}</span>
                            </div>
                        `;
                    } else if (msg.sender_type === 'admin') {
                        bubble = `
                            <div class="admin-msg-bubble admin">
                                ${msgContent}
                                <span class="admin-msg-meta">${timeStr} &bull; <i class="fa-solid fa-check-double"></i></span>
                            </div>
                        `;
                    } else {
                        bubble = `
                            <div class="admin-msg-bubble bot">
                                <i class="fa-solid fa-robot me-1"></i> <strong>Innotech Assistant:</strong> ${escapeHtml(msg.message)}
                                <span class="small d-block text-muted mt-1">${timeStr}</span>
                            </div>
                        `;
                    }
                    stream.append(bubble);
                });

                // Fix duration display for WebM audio
                stream.find('audio').each(function() {
                    const aud = this;
                    aud.addEventListener('loadedmetadata', function() {
                        if (aud.duration === Infinity || isNaN(aud.duration) || aud.duration === 0) {
                            aud.currentTime = 1e101;
                            aud.ontimeupdate = function() {
                                aud.ontimeupdate = null;
                                aud.currentTime = 0;
                            };
                        }
                    });
                });

                stream.scrollTop(stream[0].scrollHeight);
            })
            .catch(err => {
                $('#messagesStream').html('<div class="text-center py-5 text-danger"><i class="fa-solid fa-triangle-exclamation me-1"></i> Could not load messages. Click conversation again.</div>');
            });
    }

    // Escape HTML
    function escapeHtml(text) {
        return $('<div>').text(text).html();
    }

    // Format seconds to MM:SS
    function formatTime(seconds) {
        const m = Math.floor(seconds / 60).toString().padStart(2, '0');
        const s = Math.floor(seconds % 60).toString().padStart(2, '0');
        return `${m}:${s}`;
    }

    // Admin Reply (Text)
    $('#adminReplyForm').on('submit', function(e) {
        e.preventDefault();
        const text = $('#adminReplyInput').val().trim();
        if (!text || !currentConversationId) return;

        $('#adminReplyInput').val('');
        $('#btnAdminSend').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Sending...');

        fetch(`${chatApiBase}/${currentConversationId}/reply`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ message: text, _token: '{{ csrf_token() }}' })
        })
        .then(res => res.json())
        .then(data => {
            $('#btnAdminSend').prop('disabled', false).html('<i class="fa-solid fa-paper-plane me-1"></i> Send Reply');
            if (data.status === 'success') {
                onAdminMessageSent(text);
                loadMessages(currentConversationId);
            }
        })
        .catch(() => {
            $('#btnAdminSend').prop('disabled', false).html('<i class="fa-solid fa-paper-plane me-1"></i> Send Reply');
        });
    });

    // Admin Voice Recording Engine
    let adminMediaRecorder = null;
    let adminAudioChunks = [];
    let adminRecordInterval = null;
    let adminRecordSeconds = 0;
    let adminRecordStartTime = 0;
    let adminAudioStream = null;

    $('#btnAdminRecordVoice').on('click', async function() {
        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            alert('Audio recording is not supported in this browser. Please use Chrome or Edge.');
            return;
        }

        try {
            adminAudioStream = await navigator.mediaDevices.getUserMedia({ audio: true });
            adminAudioChunks = [];
            adminMediaRecorder = new MediaRecorder(adminAudioStream);

            adminMediaRecorder.ondataavailable = function(e) {
                if (e.data && e.data.size > 0) {
                    adminAudioChunks.push(e.data);
                }
            };

            adminMediaRecorder.onstop = function() {
                if (adminAudioStream) {
                    adminAudioStream.getTracks().forEach(track => track.stop());
                }
            };

            adminRecordStartTime = Date.now();
            adminMediaRecorder.start();

            // Toggle UI
            $('#adminReplyForm').addClass('d-none');
            $('#adminVoiceRecordingBar').removeClass('d-none').addClass('d-flex');

            adminRecordSeconds = 0;
            $('#adminRecordTimer').text('00:00');
            clearInterval(adminRecordInterval);
            adminRecordInterval = setInterval(function() {
                adminRecordSeconds++;
                $('#adminRecordTimer').text(formatTime(adminRecordSeconds));
            }, 1000);

        } catch(err) {
            console.error(err);
            alert('Microphone permission was denied or not found. Please allow microphone access.');
        }
    });

    $('#btnAdminCancelVoice').on('click', function() {
        stopAdminRecording(false);
    });

    $('#btnAdminSendVoice').on('click', function() {
        stopAdminRecording(true);
    });

    function stopAdminRecording(shouldSend) {
        clearInterval(adminRecordInterval);

        if (!adminMediaRecorder) {
            revertAdminVoiceUI();
            return;
        }

        if (adminMediaRecorder.state !== 'inactive') {
            const recordedDurationMs = Math.max(1000, Date.now() - (adminRecordStartTime || (Date.now() - adminRecordSeconds * 1000)));
            adminMediaRecorder.addEventListener('stop', function() {
                if (shouldSend && adminAudioChunks.length > 0) {
                    const rawBlob = new Blob(adminAudioChunks, { type: 'audio/webm' });
                    if (window.fixWebmDuration) {
                        window.fixWebmDuration(rawBlob, recordedDurationMs, function(fixedBlob) {
                            sendAdminAudioPayload(fixedBlob);
                        });
                    } else {
                        sendAdminAudioPayload(rawBlob);
                    }
                }
                adminAudioChunks = [];
            }, { once: true });

            adminMediaRecorder.stop();
        }

        revertAdminVoiceUI();
    }

    function revertAdminVoiceUI() {
        if (adminAudioStream) {
            adminAudioStream.getTracks().forEach(track => track.stop());
            adminAudioStream = null;
        }
        $('#adminVoiceRecordingBar').addClass('d-none').removeClass('d-flex');
        $('#adminReplyForm').removeClass('d-none');
    }

    function sendAdminAudioPayload(blob) {
        if (!currentConversationId) return;

        const formData = new FormData();
        formData.append('audio', blob, 'admin_voice.webm');
        formData.append('_token', '{{ csrf_token() }}');

        $('#btnAdminSend').prop('disabled', true);

        fetch(`${chatApiBase}/${currentConversationId}/reply`, {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            $('#btnAdminSend').prop('disabled', false);
            if (data.status === 'success') {
                onAdminMessageSent('🎤 Voice note');
                loadMessages(currentConversationId);
            }
        })
        .catch(() => {
            $('#btnAdminSend').prop('disabled', false);
        });
    }

    // Toggle Status (Close / Active)
    function toggleActiveStatus() {
        if (!currentConversationId) return;

        fetch(`${chatApiBase}/${currentConversationId}/toggle-status`, {
            method: 'POST',
            headers: { 
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            loadMessages(currentConversationId);
            pollFeed();
        });
    }

    // Helper: update local sidebar item immediately when Admin sends reply
    function onAdminMessageSent(textSnippet) {
        const item = $(`.chat-conv-item[data-id="${currentConversationId}"]`);
        if (item.length) {
            item.find('.chat-conv-snippet').html(`<strong class="text-primary">You: </strong>${escapeHtml(textSnippet)}`);
            item.find('.chat-conv-time').text('Just now');
            $('#conversationsContainer').prepend(item);
        }
    }

    // Real-time update and re-ordering of left sidebar conversation list
    function updateSidebarConversations(conversations) {
        if (!conversations || !Array.isArray(conversations)) return;
        const container = $('#conversationsContainer');

        conversations.forEach(conv => {
            let item = $(`.chat-conv-item[data-id="${conv.id}"]`);
            const snippetText = conv.last_message ? escapeHtml(conv.last_message) : 'Started inquiry...';
            const senderPrefix = conv.last_message_sender === 'admin' ? '<strong class="text-primary">You: </strong>' : '';
            const unreadHtml = (conv.unread_admin > 0)
                ? `<span class="badge bg-danger rounded-pill unread-badge">${conv.unread_admin} new</span>`
                : `<span class="badge bg-light text-secondary border">${conv.status ? conv.status.charAt(0).toUpperCase() + conv.status.slice(1) : 'Active'}</span>`;

            if (item.length) {
                // Update in place without losing focus or active state
                item.find('.chat-conv-snippet').html(senderPrefix + snippetText);
                item.find('.chat-conv-time').text(conv.last_message_time);
                item.find('.chat-conv-badge-area').html(unreadHtml);
            } else {
                // Add new incoming conversation item
                const newItemHtml = `
                    <div class="chat-conv-item" data-id="${conv.id}" onclick="selectConversation(${conv.id})">
                        <div class="chat-conv-avatar">
                            ${conv.name ? conv.name.charAt(0).toUpperCase() : '?'}
                        </div>
                        <div class="chat-conv-info">
                            <div class="chat-conv-name">
                                <span class="text-truncate">${escapeHtml(conv.name)}</span>
                                <small class="text-muted fw-normal chat-conv-time" style="font-size: 11px;">
                                    ${conv.last_message_time}
                                </small>
                            </div>
                            <div class="chat-conv-snippet text-truncate">
                                ${senderPrefix}${snippetText}
                            </div>
                            <div class="chat-conv-meta">
                                <span><i class="fa-solid fa-phone me-1"></i>${escapeHtml(conv.phone)}</span>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="chat-conv-badge-area">
                                        ${unreadHtml}
                                    </div>
                                    <button type="button" class="btn btn-link text-danger p-0 border-0 btn-delete-item" onclick="deleteConversation(event, ${conv.id})" title="Delete Conversation" style="opacity: 0.6;">
                                        <i class="fa-solid fa-trash-can" style="font-size: 12px;"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                container.prepend(newItemHtml);
            }
        });

        // Reorder items in DOM according to conversations array
        conversations.forEach(conv => {
            const el = $(`.chat-conv-item[data-id="${conv.id}"]`);
            if (el.length) {
                container.append(el);
            }
        });
    }

    // Delete a conversation (from sidebar or active header)
    function deleteConversation(e, id) {
        if (e) {
            e.stopPropagation();
            e.preventDefault();
        }
        if (!id) return;

        Swal.fire({
            title: 'Delete Conversation?',
            text: 'Are you sure you want to permanently delete this conversation and all its messages? This action cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, delete conversation',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`${chatApiBase}/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        $(`.chat-conv-item[data-id="${id}"]`).remove();
                        if (data.unread_total !== undefined) {
                            $('#totalUnreadPill').text(`${data.unread_total} Unread`);
                        }

                        // If deleted was currently selected conversation
                        if (currentConversationId == id) {
                            const firstItem = $('.chat-conv-item').first();
                            if (firstItem.length) {
                                selectConversation(firstItem.data('id'));
                            } else {
                                currentConversationId = null;
                                $('#activeCustomerName').text('No active conversations');
                                $('#activeCustomerPhone').text('—');
                                $('#activeCustomerEmail').text('—');
                                $('#messagesStream').html('<div class="text-center py-5 text-muted">All conversations have been removed.</div>');
                            }
                        }

                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: 'Conversation deleted successfully.',
                            showConfirmButton: false,
                            timer: 3000
                        });
                    }
                })
                .catch(() => {
                    Swal.fire('Error', 'Failed to delete conversation.', 'error');
                });
            }
        });
    }

    function deleteActiveConversation() {
        if (currentConversationId) {
            deleteConversation(null, currentConversationId);
        }
    }

    // Polling Feed for New Inquiries & Messages
    function pollFeed() {
        fetch('{{ route("admin.live_chat.feed") }}')
            .then(res => res.json())
            .then(data => {
                if (data.unread_total > knownUnreadCount) {
                    playChimeAlert();
                    knownUnreadCount = data.unread_total;
                }
                $('#totalUnreadPill').text(`${data.unread_total} Unread`);

                // Real-time update of left sidebar conversation list!
                if (data.conversations) {
                    updateSidebarConversations(data.conversations);
                }

                // Update active chat if messages arrived
                if (currentConversationId) {
                    loadMessages(currentConversationId);
                }
            })
            .catch(() => {});
    }

    // Start intervals
    $(document).ready(function() {
        if (currentConversationId) {
            loadMessages(currentConversationId);
        }
        feedInterval = setInterval(pollFeed, 5000);
    });

    // Search filter
    $('#searchChatInput').on('keyup', function() {
        const query = $(this).val().toLowerCase();
        $('.chat-conv-item').each(function() {
            const text = $(this).text().toLowerCase();
            $(this).toggle(text.indexOf(query) > -1);
        });
    });
</script>
@endpush
