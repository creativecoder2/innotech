@php
    $chatEnabled = \App\Models\Setting::get('chat_enabled', '1') == '1';
    $whatsappEnabled = \App\Models\Setting::get('whatsapp_enabled', '1') == '1';
    $waPhone = preg_replace('/[^0-9]/', '', \App\Models\Setting::get('whatsapp_phone', '923316699992'));
    $waMessage = urlencode(\App\Models\Setting::get('whatsapp_default_message', 'Hello Innotech Medical, I would like to inquire about your medical equipment and services.'));
@endphp

@if($chatEnabled || $whatsappEnabled)
<div id="innotechFloatingWidgets" class="innotech-floating-stack">

    {{-- 1. Floating WhatsApp Button --}}
    @if($whatsappEnabled)
    <div class="floating-btn-wrap" data-bs-toggle="tooltip" data-bs-placement="left" title="Chat on WhatsApp">
        <a href="https://wa.me/{{ $waPhone }}?text={{ $waMessage }}" target="_blank" rel="noopener noreferrer" class="floating-action-btn btn-whatsapp" aria-label="WhatsApp Chat">
            <i class="fa-brands fa-whatsapp"></i>
            <span class="btn-pulse-wave"></span>
        </a>
    </div>
    @endif

    {{-- 2. Floating Live Chat Button & Tooltip --}}
    @if($chatEnabled)
    <div class="floating-btn-wrap position-relative">
        <!-- 6-Second Teaser / Message Tooltip Bubble -->
        <div id="liveChatTooltip" class="chat-floating-tooltip d-none">
            <button type="button" class="tooltip-close-btn" id="closeChatTooltipBtn" aria-label="Dismiss">
                <i class="fa-solid fa-xmark"></i>
            </button>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span class="tooltip-dot"></span>
                <strong class="text-primary" style="font-size: 12px;">Innotech Live Desk</strong>
            </div>
            <p class="mb-0 text-dark small" style="font-size: 12px; line-height: 1.35;" id="chatTooltipMessage">
                Need assistance? Chat with our biomedical team live!
            </p>
        </div>

        <button type="button" id="toggleLiveChatBtn" class="floating-action-btn btn-livechat" aria-label="Open Live Chat">
            <i class="fa-solid fa-comment-dots" id="chatOpenIcon"></i>
            <i class="fa-solid fa-xmark d-none" id="chatCloseIcon"></i>
            <span class="chat-unread-badge d-none" id="chatUnreadDot"></span>
        </button>
    </div>
    @endif

    {{-- Live Chat Popup Box --}}
    @if($chatEnabled)
    <div id="innotechChatBox" class="chat-popup-box d-none">
        <!-- Chat Header -->
        <div class="chat-header">
            <div class="d-flex align-items-center gap-2">
                <div class="chat-avatar">
                    <i class="fa-solid fa-user-doctor text-white"></i>
                    <span class="chat-online-dot"></span>
                </div>
                <div>
                    <h6 class="mb-0 text-white fw-bold fs-6">Innotech Live Desk</h6>
                    <small class="text-white-50" style="font-size: 11px;">Biomedical Support &bull; Online</small>
                </div>
            </div>
            <div class="d-flex align-items-center gap-1">
                <!-- Reset / Start New Chat Button -->
                <button type="button" class="btn btn-sm btn-icon-header" id="resetChatBtn" title="Reset & Start New Conversation">
                    <i class="fa-solid fa-arrow-rotate-right text-white"></i>
                </button>
                <!-- Minimize Button -->
                <button type="button" class="btn btn-sm btn-icon-header" id="minimizeChatBtn" title="Minimize Chat">
                    <i class="fa-solid fa-minus text-white"></i>
                </button>
                <!-- Close Button -->
                <button type="button" class="btn btn-sm btn-icon-header" id="closeChatBtn" title="Close Chat">
                    <i class="fa-solid fa-xmark text-white"></i>
                </button>
            </div>
        </div>

        <!-- Chat Body: Pre-Chat Registration Form -->
        <div id="chatPreFormArea" class="chat-body p-3">
            <div class="text-center py-2 mb-3">
                <div class="chat-welcome-icon mb-2">
                    <i class="fa-solid fa-headset text-primary fs-3"></i>
                </div>
                <h6 class="fw-bold mb-1 text-dark">Welcome to Innotech Medical</h6>
                <p class="text-muted small mb-0">Please enter your details to start a live conversation with our biomedical team.</p>
            </div>

            <form id="startChatForm">
                @csrf
                <div class="mb-2">
                    <label class="form-label small fw-semibold text-dark mb-1">Your Full Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="chat_name" class="form-control form-control-sm" placeholder="e.g. Dr. Salman Khan" required>
                </div>

                <div class="mb-2">
                    <label class="form-label small fw-semibold text-dark mb-1">Phone Number <span class="text-danger">*</span></label>
                    <input type="text" name="phone" id="chat_phone" class="form-control form-control-sm" placeholder="e.g. 0331 6699992" required>
                </div>

                <div class="mb-2">
                    <label class="form-label small fw-semibold text-dark mb-1">Email Address <span class="text-muted fw-normal">(Optional)</span></label>
                    <input type="email" name="email" id="chat_email" class="form-control form-control-sm" placeholder="e.g. doctor@hospital.com">
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold text-dark mb-1">Initial Query / Message <span class="text-danger">*</span></label>
                    <textarea name="message" id="chat_message" class="form-control form-control-sm" rows="3" placeholder="Describe equipment or service inquiry..." required></textarea>
                </div>

                <button type="submit" id="btnStartChat" class="btn btn-primary btn-sm w-100 py-2 fw-semibold rounded-3 shadow-sm">
                    <i class="fa-solid fa-paper-plane me-1"></i> Start Conversation
                </button>
            </form>
        </div>

        <!-- Chat Body: Message History Area -->
        <div id="chatMessagesArea" class="chat-body chat-messages-stream d-none">
            <div id="chatMessagesList" class="d-flex flex-column gap-2 p-3">
                <!-- Dynamic Message Bubbles appended here -->
            </div>
            <!-- Typing Indicator with Animated Bouncing Dots -->
            <div id="chatTypingIndicator" class="chat-typing px-3 py-2 text-muted small d-none">
                <span class="d-inline-flex align-items-center">
                    <i class="fa-solid fa-robot text-primary me-2"></i> Innotech Assistant is typing
                    <span class="chat-typing-dots">
                        <span></span><span></span><span></span>
                    </span>
                </span>
            </div>
        </div>

        <!-- Chat Footer: Send Input & Voice Note -->
        <div id="chatFooterArea" class="chat-footer p-2 border-top bg-white d-none">
            <!-- Normal Text & Voice Trigger Mode -->
            <form id="sendMessageForm" class="d-flex align-items-center gap-2 m-0">
                @csrf
                <input type="text" id="chatInputMessage" class="form-control form-control-sm rounded-pill px-3" placeholder="Type a message..." autocomplete="off">
                
                <!-- Voice Recording Trigger Button -->
                <button type="button" id="btnUserRecordVoice" class="btn btn-outline-primary btn-sm rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; flex-shrink: 0;" title="Record Voice Note">
                    <i class="fa-solid fa-microphone" style="font-size: 13px;"></i>
                </button>

                <!-- Send Text Button -->
                <button type="submit" id="btnSendMessage" class="btn btn-primary btn-sm rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; flex-shrink: 0;" title="Send Message">
                    <i class="fa-solid fa-paper-plane" style="font-size: 12px;"></i>
                </button>
            </form>

            <!-- Active Voice Recording State Bar (Displayed while recording) -->
            <div id="userVoiceRecordingBar" class="d-none align-items-center justify-content-between px-3 py-1 bg-light rounded-pill border">
                <div class="d-flex align-items-center gap-2">
                    <span class="recording-pulse-dot"></span>
                    <span id="userRecordTimer" class="fw-bold text-danger small">00:00</span>
                    <span class="small text-muted" style="font-size: 11.5px;">Recording audio...</span>
                </div>
                <div class="d-flex align-items-center gap-1">
                    <button type="button" id="btnUserCancelVoice" class="btn btn-sm btn-outline-danger rounded-circle p-0 d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;" title="Cancel & Discard">
                        <i class="fa-solid fa-trash-can" style="font-size: 11px;"></i>
                    </button>
                    <button type="button" id="btnUserSendVoice" class="btn btn-sm btn-success rounded-circle p-0 d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;" title="Send Voice Note">
                        <i class="fa-solid fa-paper-plane" style="font-size: 11px;"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

</div>

{{-- Styling for Floating Stack & Chat Widget --}}
<style>
    .innotech-floating-stack {
        position: fixed;
        bottom: 25px;
        right: 25px;
        z-index: 99999;
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 12px;
        font-family: inherit;
    }

    .floating-action-btn {
        width: 58px;
        height: 58px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        border: none;
        outline: none;
        cursor: pointer;
        position: relative;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.18);
        transition: transform 0.25s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.25s ease;
        color: #ffffff;
    }

    .floating-action-btn:hover {
        transform: scale(1.08);
        box-shadow: 0 12px 28px rgba(0, 0, 0, 0.25);
        color: #ffffff;
    }

    /* WhatsApp Button */
    .btn-whatsapp {
        background: linear-gradient(135deg, #25D366 0%, #128C7E 100%);
        font-size: 30px;
    }

    .btn-pulse-wave {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border-radius: 50%;
        border: 2px solid rgba(37, 211, 102, 0.6);
        animation: waPulse 2s infinite ease-out;
        pointer-events: none;
    }

    @keyframes waPulse {
        0% { transform: scale(1); opacity: 1; }
        100% { transform: scale(1.45); opacity: 0; }
    }

    /* Live Chat Button */
    .btn-livechat {
        background: linear-gradient(135deg, #0E63FF 0%, #0037A5 100%);
        font-size: 24px;
    }

    .chat-unread-badge {
        position: absolute;
        top: 0;
        right: 0;
        width: 14px;
        height: 14px;
        background-color: #EF4444;
        border: 2px solid #ffffff;
        border-radius: 50%;
        animation: badgePulse 1.5s infinite;
    }

    @keyframes badgePulse {
        0% { transform: scale(0.9); }
        50% { transform: scale(1.2); }
        100% { transform: scale(0.9); }
    }

    /* Popup Box */
    .chat-popup-box {
        position: absolute;
        bottom: 74px;
        right: 0;
        width: 370px;
        height: 520px;
        background: #ffffff;
        border-radius: 18px;
        box-shadow: 0 16px 40px rgba(14, 99, 255, 0.18), 0 4px 12px rgba(0, 0, 0, 0.08);
        display: flex;
        flex-direction: column;
        overflow: hidden;
        border: 1px solid #E2E8F0;
        animation: chatSlideUp 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }

    @keyframes chatSlideUp {
        from { opacity: 0; transform: translateY(20px) scale(0.95); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }

    .chat-header {
        background: linear-gradient(135deg, #0E63FF 0%, #0037A5 100%);
        padding: 14px 18px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .chat-avatar {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }

    .chat-online-dot {
        position: absolute;
        bottom: 1px;
        right: 1px;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #10B981;
        border: 2px solid #0E63FF;
    }

    .btn-icon-header {
        background: transparent;
        border: none;
        padding: 4px 6px;
        opacity: 0.85;
        transition: opacity 0.2s, transform 0.2s;
        border-radius: 4px;
    }

    .btn-icon-header:hover {
        opacity: 1;
        background: rgba(255, 255, 255, 0.15);
        transform: scale(1.05);
    }

    .chat-body {
        flex: 1;
        overflow-y: auto;
        background: #F8FAFC;
    }

    .chat-welcome-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: rgba(14, 99, 255, 0.1);
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    /* Bubbles */
    .chat-bubble {
        max-width: 82%;
        padding: 10px 14px;
        border-radius: 14px;
        font-size: 13.5px;
        line-height: 1.5;
        position: relative;
        word-wrap: break-word;
        animation: bubbleFadeIn 0.25s ease forwards;
    }

    @keyframes bubbleFadeIn {
        from { opacity: 0; transform: translateY(6px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .chat-bubble.user {
        align-self: flex-end;
        background: #0E63FF;
        color: #ffffff;
        border-bottom-right-radius: 3px;
    }

    .chat-bubble.admin {
        align-self: flex-start;
        background: #ffffff;
        color: #1E293B;
        border: 1px solid #E2E8F0;
        border-bottom-left-radius: 3px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.03);
    }

    .chat-bubble.bot {
        align-self: flex-start;
        background: #EFF6FF;
        color: #1E40AF;
        border: 1px solid #DBEAFE;
        border-bottom-left-radius: 3px;
    }

    .bubble-meta {
        font-size: 10px;
        margin-top: 4px;
        opacity: 0.75;
        display: block;
        text-align: right;
    }

    .chat-bubble.admin .bubble-meta, .chat-bubble.bot .bubble-meta {
        text-align: left;
    }

    /* Typing Bouncing Dots */
    .chat-typing-dots {
        display: inline-flex;
        align-items: center;
        gap: 3px;
        margin-left: 5px;
    }

    .chat-typing-dots span {
        width: 5px;
        height: 5px;
        background-color: #0E63FF;
        border-radius: 50%;
        display: inline-block;
        animation: chatBounce 1.2s infinite ease-in-out;
    }

    .chat-typing-dots span:nth-child(2) { animation-delay: 0.2s; }
    .chat-typing-dots span:nth-child(3) { animation-delay: 0.4s; }

    @keyframes chatBounce {
        0%, 80%, 100% { transform: scale(0.3); opacity: 0.4; }
        40% { transform: scale(1); opacity: 1; }
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
        min-width: 220px;
        max-width: 270px;
        background: rgba(255, 255, 255, 0.15);
        padding: 8px 10px;
        border-radius: 12px;
    }
    .chat-bubble.bot .voice-bubble-wrapper {
        background: #f1f5f9;
    }
    .chat-audio-ctrl {
        width: 100%;
        height: 38px;
        border-radius: 20px;
        outline: none;
        display: block;
    }

    /* 6-Second Teaser / Message Floating Tooltip */
    .chat-floating-tooltip {
        position: absolute;
        bottom: 5px;
        right: 70px;
        width: 250px;
        background: #ffffff;
        border-radius: 14px;
        padding: 12px 14px;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.15), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
        border: 1px solid #E2E8F0;
        z-index: 999990;
        cursor: pointer;
        animation: tooltipPop 0.35s cubic-bezier(0.16, 1, 0.3, 1);
        transition: all 0.3s ease;
    }
    .chat-floating-tooltip::after {
        content: '';
        position: absolute;
        bottom: 18px;
        right: -7px;
        width: 14px;
        height: 14px;
        background: #ffffff;
        transform: rotate(45deg);
        border-right: 1px solid #E2E8F0;
        border-top: 1px solid #E2E8F0;
    }
    @keyframes tooltipPop {
        from { opacity: 0; transform: translateX(10px) scale(0.95); }
        to { opacity: 1; transform: translateX(0) scale(1); }
    }
    .tooltip-close-btn {
        position: absolute;
        top: 6px;
        right: 8px;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: #f1f5f9;
        border: none;
        color: #64748b;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        cursor: pointer;
        transition: all 0.2s;
        z-index: 2;
    }
    .tooltip-close-btn:hover {
        background: #fee2e2;
        color: #ef4444;
    }
    .tooltip-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #22c55e;
        display: inline-block;
        box-shadow: 0 0 0 2px rgba(34, 197, 94, 0.2);
    }

    @media (max-width: 576px) {
        .innotech-floating-stack {
            bottom: 15px;
            right: 15px;
        }
        .chat-floating-tooltip {
            right: 60px;
            width: 210px;
            bottom: 0px;
        }
        .chat-popup-box {
            width: calc(100vw - 30px);
            height: 480px;
            right: 0;
            bottom: 70px;
        }
    }
</style>

{{-- Frontend Chat Client Engine --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggleBtn = document.getElementById('toggleLiveChatBtn');
    const chatBox = document.getElementById('innotechChatBox');
    const resetBtn = document.getElementById('resetChatBtn');
    const minimizeBtn = document.getElementById('minimizeChatBtn');
    const closeBtn = document.getElementById('closeChatBtn');
    const chatOpenIcon = document.getElementById('chatOpenIcon');
    const chatCloseIcon = document.getElementById('chatCloseIcon');
    const chatUnreadDot = document.getElementById('chatUnreadDot');

    const chatPreFormArea = document.getElementById('chatPreFormArea');
    const chatMessagesArea = document.getElementById('chatMessagesArea');
    const chatFooterArea = document.getElementById('chatFooterArea');
    const chatMessagesList = document.getElementById('chatMessagesList');
    const chatTypingIndicator = document.getElementById('chatTypingIndicator');

    const startChatForm = document.getElementById('startChatForm');
    const sendMessageForm = document.getElementById('sendMessageForm');
    const chatInputMessage = document.getElementById('chatInputMessage');
    const btnStartChat = document.getElementById('btnStartChat');
    const liveChatTooltip = document.getElementById('liveChatTooltip');
    const closeChatTooltipBtn = document.getElementById('closeChatTooltipBtn');
    const chatTooltipMessage = document.getElementById('chatTooltipMessage');

    let sessionToken = localStorage.getItem('innotech_chat_token') || null;
    let lastMessageId = 0;
    let pollInterval = null;
    let pendingDelayedMsgId = null;

    function showChatTooltip(customText = null) {
        if (!liveChatTooltip || !chatBox) return;
        if (!chatBox.classList.contains('d-none')) {
            hideChatTooltip();
            return;
        }
        if (customText && chatTooltipMessage) {
            chatTooltipMessage.innerText = customText;
        }
        liveChatTooltip.classList.remove('d-none');
    }

    function hideChatTooltip() {
        if (liveChatTooltip) {
            liveChatTooltip.classList.add('d-none');
        }
    }

    // Toggle Chat Window
    function toggleChat(forceOpen = null) {
        if (!chatBox) return;
        const isCurrentlyHidden = chatBox.classList.contains('d-none');
        const shouldOpen = forceOpen !== null ? forceOpen : isCurrentlyHidden;

        if (shouldOpen) {
            chatBox.classList.remove('d-none');
            chatOpenIcon.classList.add('d-none');
            chatCloseIcon.classList.remove('d-none');
            chatUnreadDot.classList.add('d-none');
            hideChatTooltip();
            localStorage.setItem('innotech_chat_open', '1');
            scrollToBottom();
            if (sessionToken) startPolling();
        } else {
            chatBox.classList.add('d-none');
            chatOpenIcon.classList.remove('d-none');
            chatCloseIcon.classList.add('d-none');
            localStorage.setItem('innotech_chat_open', '0');
        }
    }

    if (toggleBtn) {
        toggleBtn.addEventListener('click', function() { toggleChat(); });
    }
    if (minimizeBtn) {
        minimizeBtn.addEventListener('click', function() { toggleChat(false); });
    }
    if (closeBtn) {
        closeBtn.addEventListener('click', function() { toggleChat(false); });
    }

    // Reset / Start New Conversation Button
    if (resetBtn) {
        resetBtn.addEventListener('click', function() {
            if (confirm('Start a new conversation? Your current chat session will be cleared.')) {
                if (pollInterval) clearInterval(pollInterval);
                localStorage.removeItem('innotech_chat_token');
                sessionToken = null;
                lastMessageId = 0;
                pendingDelayedMsgId = null;

                // Clear message bubbles
                chatMessagesList.innerHTML = '';
                if (startChatForm) startChatForm.reset();
                if (chatInputMessage) chatInputMessage.value = '';

                // Switch back to pre-chat form
                chatMessagesArea.classList.add('d-none');
                chatFooterArea.classList.add('d-none');
                chatPreFormArea.classList.remove('d-none');
                chatTypingIndicator.classList.add('d-none');
            }
        });
    }

    function scrollToBottom() {
        if (chatMessagesArea) {
            chatMessagesArea.scrollTop = chatMessagesArea.scrollHeight;
        }
    }

    function appendMessage(msg) {
        if (!msg || !msg.id) return;
        if (document.getElementById('chat-msg-' + msg.id)) {
            return; // Avoid duplicate bubbles
        }

        if (msg.id > lastMessageId) {
            lastMessageId = msg.id;
        }

        const bubble = document.createElement('div');
        bubble.id = 'chat-msg-' + msg.id;
        bubble.className = `chat-bubble ${msg.sender_type}`;
        
        const dateObj = new Date(msg.created_at || Date.now());
        const timeStr = dateObj.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

        let senderLabel = '';
        if (msg.sender_type === 'admin') {
            senderLabel = '<span class="fw-bold" style="font-size: 11px;"><i class="fa-solid fa-shield-halved me-1"></i> Innotech Support:</span><br>';
        } else if (msg.sender_type === 'bot') {
            senderLabel = '<span class="fw-bold" style="font-size: 11px;"><i class="fa-solid fa-robot me-1"></i> Innotech Assistant:</span><br>';
        }

        let contentHtml = `<span>${escapeHtml(msg.message)}</span>`;
        if (msg.type === 'audio' || msg.attachment) {
            const assetRoot = '{{ asset("") }}';
            const audioSrc = (msg.attachment_url) ? msg.attachment_url : (msg.attachment && msg.attachment.startsWith('http') ? msg.attachment : `${assetRoot}${msg.attachment}`);
            contentHtml = `
                <div class="voice-bubble-wrapper">
                    <div class="d-flex align-items-center gap-1 mb-1">
                        <i class="fa-solid fa-microphone me-1 ${msg.sender_type === 'user' ? 'text-white' : 'text-primary'}"></i>
                        <span class="small fw-bold">${msg.sender_type === 'user' ? 'Voice Note' : 'Voice Message'}</span>
                    </div>
                    <audio controls preload="metadata" class="chat-audio-ctrl" src="${audioSrc}"></audio>
                    ${msg.message && msg.message !== 'Voice message' && msg.message !== 'Voice Note' && msg.message !== 'Voice message from support' ? `<div class="mt-1 small">${escapeHtml(msg.message)}</div>` : ''}
                </div>
            `;
        }

        bubble.innerHTML = `${senderLabel}${contentHtml}<span class="bubble-meta">${timeStr}</span>`;
        chatMessagesList.appendChild(bubble);

        // Fix duration display for WebM audio
        const audios = bubble.querySelectorAll('audio');
        audios.forEach(aud => {
            aud.addEventListener('loadedmetadata', function() {
                if (this.duration === Infinity || isNaN(this.duration) || this.duration === 0) {
                    this.currentTime = 1e101;
                    this.ontimeupdate = function() {
                        this.ontimeupdate = null;
                        this.currentTime = 0;
                    };
                }
            });
        });

        scrollToBottom();
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.innerText = text;
        return div.innerHTML;
    }

    function switchActiveChatMode() {
        chatPreFormArea.classList.add('d-none');
        chatMessagesArea.classList.remove('d-none');
        chatFooterArea.classList.remove('d-none');
        scrollToBottom();
    }

    // Play gentle incoming message notification chime
    function playChime() {
        try {
            const ctx = new (window.AudioContext || window.webkitAudioContext)();
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();
            osc.connect(gain);
            gain.connect(ctx.destination);
            osc.frequency.setValueAtTime(587.33, ctx.currentTime); // D5
            osc.frequency.setValueAtTime(880, ctx.currentTime + 0.1); // A5
            gain.gain.setValueAtTime(0.2, ctx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.35);
            osc.start(ctx.currentTime);
            osc.stop(ctx.currentTime + 0.35);
        } catch(e) {}
    }

    // 1. Session Persistence: Check if an active session already exists in localStorage
    if (sessionToken) {
        fetch(`{{ url('chat/restore') }}?session_token=${encodeURIComponent(sessionToken)}`)
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success' && data.messages && data.messages.length > 0) {
                    switchActiveChatMode();
                    data.messages.forEach(appendMessage);

                    if (data.conversation && data.conversation.status === 'closed') {
                        setWidgetChatClosedState(true);
                    } else {
                        setWidgetChatClosedState(false);
                    }

                    // Reopen window if user had it open before reload
                    if (localStorage.getItem('innotech_chat_open') === '1') {
                        toggleChat(true);
                    }
                    startPolling();
                }
            })
            .catch(() => {});
    }

    // 2. Start Chat Form Submission (Message #1 + 3-second delayed Welcome auto-reply)
    if (startChatForm) {
        startChatForm.addEventListener('submit', function(e) {
            e.preventDefault();
            btnStartChat.disabled = true;
            btnStartChat.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Connecting...';

            const payload = {
                name: document.getElementById('chat_name').value,
                phone: document.getElementById('chat_phone').value,
                email: document.getElementById('chat_email').value,
                message: document.getElementById('chat_message').value,
                _token: '{{ csrf_token() }}'
            };

            fetch('{{ route("chat.start") }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify(payload)
            })
            .then(res => res.json())
            .then(data => {
                btnStartChat.disabled = false;
                btnStartChat.innerHTML = '<i class="fa-solid fa-paper-plane me-1"></i> Start Conversation';

                if (data.status === 'success') {
                    sessionToken = data.session_token;
                    localStorage.setItem('innotech_chat_token', sessionToken);
                    switchActiveChatMode();

                    // Render user initial message immediately
                    if (data.user_message) {
                        appendMessage(data.user_message);
                    }

                    // Render Welcome Bot Auto-Reply exactly 3 SECONDS later with typing indicator
                    if (data.bot_message) {
                        pendingDelayedMsgId = data.bot_message.id;
                        chatTypingIndicator.classList.remove('d-none');
                        scrollToBottom();

                        setTimeout(function() {
                            chatTypingIndicator.classList.add('d-none');
                            appendMessage(data.bot_message);
                            playChime();
                            scrollToBottom();
                            pendingDelayedMsgId = null;
                        }, 3000);
                    }

                    startPolling();
                } else {
                    alert(data.message || 'Could not start chat. Please try again.');
                }
            })
            .catch(err => {
                btnStartChat.disabled = false;
                btnStartChat.innerHTML = '<i class="fa-solid fa-paper-plane me-1"></i> Start Conversation';
                alert('Network error. Please try again.');
            });
        });
    }

    // 3. Send message form (Subsequent messages + 3-second delayed Thank You on Msg #2)
    if (sendMessageForm) {
        sendMessageForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const text = chatInputMessage.value.trim();
            if (!text || !sessionToken) return;

            chatInputMessage.value = '';

            fetch('{{ route("chat.send") }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ session_token: sessionToken, message: text, _token: '{{ csrf_token() }}' })
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    // Render user message immediately
                    if (data.user_message) {
                        appendMessage(data.user_message);
                    }

                    // Render Thank You Bot Auto-Reply exactly 3 SECONDS later with typing indicator
                    if (data.bot_message) {
                        pendingDelayedMsgId = data.bot_message.id;
                        chatTypingIndicator.classList.remove('d-none');
                        scrollToBottom();

                        setTimeout(function() {
                            chatTypingIndicator.classList.add('d-none');
                            appendMessage(data.bot_message);
                            playChime();
                            scrollToBottom();
                            pendingDelayedMsgId = null;
                        }, 3000);
                    }
                }
            })
            .catch(() => {});
        });
    }

    // Helper: format seconds to MM:SS
    function formatTime(seconds) {
        const m = Math.floor(seconds / 60).toString().padStart(2, '0');
        const s = Math.floor(seconds % 60).toString().padStart(2, '0');
        return `${m}:${s}`;
    }

    // 4. Voice Recording Engine for User
    let userMediaRecorder = null;
    let userAudioChunks = [];
    let userRecordInterval = null;
    let userRecordSeconds = 0;
    let userRecordStartTime = 0;
    let userAudioStream = null;

    const btnUserRecordVoice = document.getElementById('btnUserRecordVoice');
    const userVoiceRecordingBar = document.getElementById('userVoiceRecordingBar');
    const userRecordTimer = document.getElementById('userRecordTimer');
    const btnUserCancelVoice = document.getElementById('btnUserCancelVoice');
    const btnUserSendVoice = document.getElementById('btnUserSendVoice');

    if (btnUserRecordVoice) {
        btnUserRecordVoice.addEventListener('click', async function() {
            if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                alert('Audio recording is not supported in your current browser. Please use a modern browser like Chrome or Edge.');
                return;
            }

            try {
                userAudioStream = await navigator.mediaDevices.getUserMedia({ audio: true });
                userAudioChunks = [];
                userMediaRecorder = new MediaRecorder(userAudioStream);

                userMediaRecorder.ondataavailable = function(e) {
                    if (e.data && e.data.size > 0) {
                        userAudioChunks.push(e.data);
                    }
                };

                userMediaRecorder.onstop = function() {
                    if (userAudioStream) {
                        userAudioStream.getTracks().forEach(track => track.stop());
                    }
                };

                userRecordStartTime = Date.now();
                userMediaRecorder.start();

                // Swap UI to recording bar
                sendMessageForm.classList.add('d-none');
                userVoiceRecordingBar.classList.remove('d-none');
                userVoiceRecordingBar.classList.add('d-flex');

                userRecordSeconds = 0;
                userRecordTimer.innerText = '00:00';
                clearInterval(userRecordInterval);
                userRecordInterval = setInterval(function() {
                    userRecordSeconds++;
                    userRecordTimer.innerText = formatTime(userRecordSeconds);
                }, 1000);

            } catch (err) {
                console.error(err);
                alert('Microphone permission was denied or not detected. Please enable microphone permissions in your browser.');
            }
        });
    }

    // Cancel Voice Recording
    if (btnUserCancelVoice) {
        btnUserCancelVoice.addEventListener('click', function() {
            stopUserRecording(false);
        });
    }

    // Send Voice Recording
    if (btnUserSendVoice) {
        btnUserSendVoice.addEventListener('click', function() {
            stopUserRecording(true);
        });
    }

    function stopUserRecording(shouldSend) {
        clearInterval(userRecordInterval);

        if (!userMediaRecorder) {
            revertUserVoiceUI();
            return;
        }

        if (userMediaRecorder.state !== 'inactive') {
            const recordedDurationMs = Math.max(1000, Date.now() - (userRecordStartTime || (Date.now() - userRecordSeconds * 1000)));
            userMediaRecorder.addEventListener('stop', function() {
                if (shouldSend && userAudioChunks.length > 0) {
                    const rawBlob = new Blob(userAudioChunks, { type: 'audio/webm' });
                    if (window.fixWebmDuration) {
                        window.fixWebmDuration(rawBlob, recordedDurationMs, function(fixedBlob) {
                            sendUserAudioPayload(fixedBlob);
                        });
                    } else {
                        sendUserAudioPayload(rawBlob);
                    }
                }
                userAudioChunks = [];
            }, { once: true });

            userMediaRecorder.stop();
        }

        revertUserVoiceUI();
    }

    function revertUserVoiceUI() {
        if (userAudioStream) {
            userAudioStream.getTracks().forEach(track => track.stop());
            userAudioStream = null;
        }
        if (userVoiceRecordingBar) {
            userVoiceRecordingBar.classList.add('d-none');
            userVoiceRecordingBar.classList.remove('d-flex');
        }
        if (sendMessageForm) {
            sendMessageForm.classList.remove('d-none');
        }
    }

    function sendUserAudioPayload(blob) {
        if (!sessionToken) return;

        const formData = new FormData();
        formData.append('audio', blob, 'user_voice.webm');
        formData.append('session_token', sessionToken);
        formData.append('_token', '{{ csrf_token() }}');

        fetch('{{ route("chat.send") }}', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                if (data.user_message) {
                    appendMessage(data.user_message);
                }

                // Render Thank You Bot Auto-Reply exactly 3 SECONDS later with typing indicator
                if (data.bot_message) {
                    pendingDelayedMsgId = data.bot_message.id;
                    if (chatTypingIndicator) chatTypingIndicator.classList.remove('d-none');
                    scrollToBottom();

                    setTimeout(function() {
                        if (chatTypingIndicator) chatTypingIndicator.classList.add('d-none');
                        appendMessage(data.bot_message);
                        playChime();
                        scrollToBottom();
                        pendingDelayedMsgId = null;
                    }, 3000);
                }
            }
        })
        .catch(err => {
            console.error('Error sending audio message:', err);
        });
    }

    function setWidgetChatClosedState(isClosed) {
        let closedBanner = document.getElementById('chatWidgetClosedBanner');
        const activeForm = document.getElementById('sendMessageForm');
        const recBar = document.getElementById('userVoiceRecordingBar');

        if (isClosed) {
            if (!closedBanner && activeForm) {
                closedBanner = document.createElement('div');
                closedBanner.id = 'chatWidgetClosedBanner';
                closedBanner.className = 'text-center p-2 text-muted small bg-light rounded-pill border w-100 mb-2';
                closedBanner.innerHTML = '<i class="fa-solid fa-lock text-danger me-1"></i> This conversation has ended. Click <i class="fa-solid fa-rotate-right text-primary"></i> above to start a new chat.';
                activeForm.parentElement.insertBefore(closedBanner, activeForm);
            }
            if (closedBanner) closedBanner.classList.remove('d-none');
            if (activeForm) activeForm.classList.add('d-none');
            if (recBar) recBar.classList.add('d-none');
        } else {
            if (closedBanner) closedBanner.classList.add('d-none');
            if (activeForm) activeForm.classList.remove('d-none');
        }
    }

    // 4. Polling for incoming Admin replies
    function startPolling() {
        if (pollInterval) clearInterval(pollInterval);
        pollInterval = setInterval(function() {
            if (!sessionToken) return;

            fetch(`{{ url('chat/poll') }}?session_token=${encodeURIComponent(sessionToken)}&last_id=${lastMessageId}`)
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        if (data.conversation_status === 'closed') {
                            setWidgetChatClosedState(true);
                        } else if (data.conversation_status === 'active') {
                            setWidgetChatClosedState(false);
                        }

                        if (data.messages && data.messages.length > 0) {
                            let hasIncoming = false;
                            let hasAdminReply = false;
                            let latestAdminText = '';

                            data.messages.forEach(msg => {
                                // If this message is currently being held for the 3-second delay, don't show it prematurely
                                if (msg.id === pendingDelayedMsgId) {
                                    return;
                                }
                                appendMessage(msg);
                                if (msg.sender_type === 'admin') {
                                    hasAdminReply = true;
                                    latestAdminText = (msg.type === 'audio') ? '🎤 Voice message from support' : msg.message;
                                }
                                if (msg.sender_type === 'admin' || msg.sender_type === 'bot') {
                                    hasIncoming = true;
                                    if (!latestAdminText) {
                                        latestAdminText = msg.message;
                                    }
                                }
                            });

                            if (hasIncoming) {
                                playChime();
                            }

                            // Auto-open chat popup on EVERY new Admin reply!
                            if (hasAdminReply) {
                                if (chatBox.classList.contains('d-none')) {
                                    toggleChat(true);
                                }
                            } else if (hasIncoming) {
                                if (chatBox.classList.contains('d-none')) {
                                    chatUnreadDot.classList.remove('d-none');
                                    showChatTooltip(latestAdminText);
                                }
                            }
                        }
                    }
                })
                .catch(() => {});
        }, 3000);
    }

    // 5. Tooltip Event Listeners & Intervals

    if (closeChatTooltipBtn) {
        closeChatTooltipBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            hideChatTooltip();
        });
    }

    if (liveChatTooltip) {
        liveChatTooltip.addEventListener('click', function(e) {
            if (e.target.closest('#closeChatTooltipBtn')) return;
            hideChatTooltip();
            toggleChat(true);
        });
    }

    // Show initial teaser tooltip after 3 seconds if chat was never opened
    setTimeout(function() {
        if (chatBox && chatBox.classList.contains('d-none')) {
            showChatTooltip();
        }
    }, 3000);

    // Periodically re-display tooltip every 6 seconds if chat is closed
    setInterval(function() {
        if (chatBox && chatBox.classList.contains('d-none')) {
            showChatTooltip();
        } else {
            hideChatTooltip();
        }
    }, 6000);

    // Start background polling if session exists
    if (sessionToken) {
        startPolling();
    }
});
</script>
@endif
