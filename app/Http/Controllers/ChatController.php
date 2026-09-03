<?php

namespace App\Http\Controllers;

use App\Models\ChatConversation;
use App\Models\ChatMessage;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ChatController extends Controller
{
    /**
     * Start a new chat conversation with pre-chat inquiry details.
     */
    public function start(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'phone' => 'required|string|max:40',
            'email' => 'nullable|email|max:100',
            'message' => 'required|string|max:2000',
        ]);

        $sessionToken = Str::random(36);

        $conversation = ChatConversation::create([
            'session_token' => $sessionToken,
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'email' => $validated['email'] ?? null,
            'status' => 'active',
            'unread_admin' => 1,
            'unread_user' => 0,
            'user_message_count' => 1,
            'last_message_at' => now(),
        ]);

        // 1. User's initial inquiry message
        $userMsg = ChatMessage::create([
            'conversation_id' => $conversation->id,
            'sender_type' => 'user',
            'message' => $validated['message'],
            'is_read' => false,
        ]);

        // 2. Automatic Welcome Message
        $welcomeText = Setting::get(
            'chat_welcome_message',
            'Hello! Welcome to INNOTECH MEDICAL PVT LTD. How can our biomedical engineering team assist you today?'
        );

        $botMsg = ChatMessage::create([
            'conversation_id' => $conversation->id,
            'sender_type' => 'bot',
            'message' => $welcomeText,
            'is_read' => true,
        ]);

        return response()->json([
            'status' => 'success',
            'session_token' => $sessionToken,
            'conversation' => $conversation,
            'user_message' => $userMsg,
            'bot_message' => $botMsg,
            'messages' => [$userMsg, $botMsg],
        ]);
    }

    /**
     * Send a subsequent message from customer (text or voice note).
     */
    public function send(Request $request)
    {
        $request->validate([
            'session_token' => 'required|string',
            'message' => 'nullable|string|max:2000',
            'audio' => 'nullable|file|max:20480',
        ]);

        $conversation = ChatConversation::where('session_token', $request->session_token)->first();

        if (!$conversation) {
            return response()->json(['status' => 'error', 'message' => 'Conversation not found'], 404);
        }

        if ($conversation->status === 'closed') {
            return response()->json([
                'status' => 'error',
                'message' => 'This chat conversation has been closed by support. Please reset or start a new inquiry.'
            ], 403);
        }

        $isAudio = $request->hasFile('audio');
        if (!$isAudio && empty(trim($request->message ?? ''))) {
            return response()->json(['status' => 'error', 'message' => 'Message or audio is required'], 422);
        }

        $attachmentPath = null;
        if ($isAudio) {
            $audioFile = $request->file('audio');
            $ext = $audioFile->getClientOriginalExtension() ?: 'webm';
            $fileName = 'voice_' . time() . '_' . Str::random(8) . '.' . $ext;

            $baseDir = base_path('uploads/chat_audio');
            $publicDir = public_path('uploads/chat_audio');
            if (!file_exists($baseDir)) @mkdir($baseDir, 0777, true);
            if (!file_exists($publicDir)) @mkdir($publicDir, 0777, true);

            $audioFile->move($baseDir, $fileName);
            @copy($baseDir . '/' . $fileName, $publicDir . '/' . $fileName);

            $attachmentPath = 'uploads/chat_audio/' . $fileName;
            $messageText = $request->message ?: 'Voice message';
            $msgType = 'audio';
        } else {
            $messageText = $request->message;
            $msgType = 'text';
        }

        $conversation->increment('user_message_count');
        $conversation->increment('unread_admin');
        $conversation->update(['last_message_at' => now()]);

        $newMessages = [];

        // Save customer message
        $userMsg = ChatMessage::create([
            'conversation_id' => $conversation->id,
            'sender_type' => 'user',
            'type' => $msgType,
            'message' => $messageText,
            'attachment' => $attachmentPath,
            'is_read' => false,
        ]);
        $newMessages[] = $userMsg;

        $botMsg = null;
        // Auto-reply on message #2 (Thank You acknowledgement)
        if ($conversation->user_message_count === 2) {
            $thankYouText = Setting::get(
                'chat_second_message',
                'Thank you for providing the details! An Innotech specialist has been notified and will assist you shortly.'
            );

            $botMsg = ChatMessage::create([
                'conversation_id' => $conversation->id,
                'sender_type' => 'bot',
                'type' => 'text',
                'message' => $thankYouText,
                'is_read' => true,
            ]);
            $newMessages[] = $botMsg;
        }

        return response()->json([
            'status' => 'success',
            'user_message' => $userMsg,
            'bot_message' => $botMsg,
            'messages' => $newMessages,
        ]);
    }

    /**
     * Poll new messages for customer widget.
     */
    public function poll(Request $request)
    {
        $sessionToken = $request->query('session_token');
        $lastId = (int) $request->query('last_id', 0);

        if (!$sessionToken) {
            return response()->json(['status' => 'error', 'message' => 'No session'], 400);
        }

        $conversation = ChatConversation::where('session_token', $sessionToken)->first();

        if (!$conversation) {
            return response()->json(['status' => 'error', 'message' => 'Session expired'], 404);
        }

        $messages = ChatMessage::where('conversation_id', $conversation->id)
            ->where('id', '>', $lastId)
            ->orderBy('id', 'asc')
            ->get();

        // Mark incoming admin messages as read
        ChatMessage::where('conversation_id', $conversation->id)
            ->whereIn('sender_type', ['admin', 'bot'])
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $conversation->update(['unread_user' => 0]);

        return response()->json([
            'status' => 'success',
            'conversation_status' => $conversation->status,
            'messages' => $messages,
        ]);
    }

    /**
     * Restore conversation on page load if session exists.
     */
    public function restore(Request $request)
    {
        $sessionToken = $request->query('session_token');

        if (!$sessionToken) {
            return response()->json(['status' => 'none']);
        }

        $conversation = ChatConversation::where('session_token', $sessionToken)->first();

        if (!$conversation) {
            return response()->json(['status' => 'none']);
        }

        $messages = ChatMessage::where('conversation_id', $conversation->id)
            ->orderBy('id', 'asc')
            ->get();

        return response()->json([
            'status' => 'success',
            'conversation' => $conversation,
            'messages' => $messages,
        ]);
    }
}
