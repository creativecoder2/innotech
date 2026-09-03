<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChatConversation;
use App\Models\ChatMessage;
use App\Models\Setting;
use Illuminate\Http\Request;

class LiveChatController extends Controller
{
    /**
     * Display live chat console and settings.
     */
    public function index()
    {
        $conversations = ChatConversation::with('latestMessage')
            ->orderBy('last_message_at', 'desc')
            ->paginate(30);

        $unreadTotal = ChatConversation::sum('unread_admin');

        $settings = [
            'chat_enabled' => Setting::get('chat_enabled', '1'),
            'chat_welcome_message' => Setting::get(
                'chat_welcome_message',
                'Hello! Welcome to INNOTECH MEDICAL PVT LTD. How can our biomedical engineering team assist you today?'
            ),
            'chat_second_message' => Setting::get(
                'chat_second_message',
                'Thank you for providing the details! An Innotech specialist has been notified and will assist you shortly.'
            ),
            'whatsapp_enabled' => Setting::get('whatsapp_enabled', '1'),
            'whatsapp_phone' => Setting::get('whatsapp_phone', '+923316699992'),
            'whatsapp_default_message' => Setting::get(
                'whatsapp_default_message',
                'Hello Innotech Medical, I would like to inquire about your medical equipment and services.'
            ),
        ];

        return view('admin.live_chat.index', compact('conversations', 'unreadTotal', 'settings'));
    }

    /**
     * Real-time JSON feed of conversations for admin dashboard.
     */
    public function conversationsFeed()
    {
        $conversations = ChatConversation::with('latestMessage')
            ->orderBy('last_message_at', 'desc')
            ->take(50)
            ->get()
            ->map(function ($c) {
                $msg = $c->latestMessage;
                $msgText = '';
                $sender = '';
                if ($msg) {
                    $sender = $msg->sender_type;
                    if ($msg->type === 'audio') {
                        $msgText = '🎤 Voice note';
                    } else {
                        $msgText = $msg->message;
                    }
                }

                return [
                    'id' => $c->id,
                    'name' => $c->name,
                    'phone' => $c->phone,
                    'email' => $c->email,
                    'status' => $c->status,
                    'unread_admin' => (int) $c->unread_admin,
                    'last_message' => $msgText,
                    'last_message_sender' => $sender,
                    'last_message_time' => $c->last_message_at ? $c->last_message_at->diffForHumans(null, true) : $c->created_at->diffForHumans(null, true),
                    'last_message_timestamp' => $c->last_message_at ? $c->last_message_at->timestamp : $c->created_at->timestamp,
                ];
            });

        $unreadTotal = ChatConversation::sum('unread_admin');

        return response()->json([
            'conversations' => $conversations,
            'unread_total' => (int) $unreadTotal,
        ]);
    }

    /**
     * Delete a conversation and all its messages / attachments.
     */
    public function destroy($id)
    {
        $conversation = ChatConversation::findOrFail($id);

        // Clean up any uploaded voice notes
        $audioMessages = ChatMessage::where('conversation_id', $conversation->id)
            ->whereNotNull('attachment')
            ->get();

        foreach ($audioMessages as $msg) {
            $filePath = public_path($msg->attachment);
            if (file_exists($filePath) && is_file($filePath)) {
                @unlink($filePath);
            }
        }

        $conversation->delete();

        $unreadTotal = ChatConversation::sum('unread_admin');

        return response()->json([
            'status' => 'success',
            'unread_total' => (int) $unreadTotal,
            'message' => 'Chat conversation deleted successfully.',
        ]);
    }

    /**
     * Fetch all messages for a specific conversation.
     */
    public function messages($id)
    {
        $conversation = ChatConversation::findOrFail($id);

        // Mark unread for admin as 0
        $conversation->update(['unread_admin' => 0]);

        $messages = ChatMessage::where('conversation_id', $conversation->id)
            ->orderBy('id', 'asc')
            ->get();

        return response()->json([
            'conversation' => $conversation,
            'messages' => $messages,
        ]);
    }

    /**
     * Admin posts a reply to customer (text or audio voice note).
     */
    public function reply(Request $request, $id)
    {
        $request->validate([
            'message' => 'nullable|string|max:3000',
            'audio' => 'nullable|file|max:20480',
        ]);

        $conversation = ChatConversation::findOrFail($id);

        if ($conversation->status === 'closed') {
            return response()->json([
                'status' => 'error',
                'message' => 'This chat conversation is currently closed. Please re-open it to reply.'
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
            $fileName = 'admin_voice_' . time() . '_' . \Illuminate\Support\Str::random(8) . '.' . $ext;

            $baseDir = base_path('uploads/chat_audio');
            $publicDir = public_path('uploads/chat_audio');
            if (!file_exists($baseDir)) @mkdir($baseDir, 0777, true);
            if (!file_exists($publicDir)) @mkdir($publicDir, 0777, true);

            $audioFile->move($baseDir, $fileName);
            @copy($baseDir . '/' . $fileName, $publicDir . '/' . $fileName);

            $attachmentPath = 'uploads/chat_audio/' . $fileName;
            $messageText = $request->message ?: 'Voice message from support';
            $msgType = 'audio';
        } else {
            $messageText = $request->message;
            $msgType = 'text';
        }

        $message = ChatMessage::create([
            'conversation_id' => $conversation->id,
            'sender_type' => 'admin',
            'type' => $msgType,
            'message' => $messageText,
            'attachment' => $attachmentPath,
            'is_read' => false,
        ]);

        $conversation->increment('unread_user');
        $conversation->update(['last_message_at' => now()]);

        return response()->json([
            'status' => 'success',
            'message' => $message,
        ]);
    }

    /**
     * Close or Re-open conversation.
     */
    public function toggleStatus($id)
    {
        $conversation = ChatConversation::findOrFail($id);
        $newStatus = ($conversation->status === 'active') ? 'closed' : 'active';
        $conversation->status = $newStatus;
        $conversation->save();

        if ($newStatus === 'closed') {
            ChatMessage::create([
                'conversation_id' => $conversation->id,
                'sender_type' => 'bot',
                'type' => 'text',
                'message' => 'This conversation has been closed by Innotech Support. Thank you for connecting with us! If you need any further help or information, feel free to start a new inquiry anytime.',
                'is_read' => true,
            ]);
            $conversation->increment('unread_user');
            $conversation->update(['last_message_at' => now()]);
        } else {
            ChatMessage::create([
                'conversation_id' => $conversation->id,
                'sender_type' => 'bot',
                'type' => 'text',
                'message' => 'This conversation has been re-opened by Innotech Support. You may continue your inquiry.',
                'is_read' => true,
            ]);
            $conversation->increment('unread_user');
            $conversation->update(['last_message_at' => now()]);
        }

        return response()->json([
            'status' => 'success',
            'new_status' => $conversation->status,
        ]);
    }

    /**
     * Save Chat & WhatsApp system settings.
     */
    public function saveSettings(Request $request)
    {
        $validated = $request->validate([
            'chat_welcome_message' => 'required|string|max:1000',
            'chat_second_message' => 'required|string|max:1000',
            'whatsapp_phone' => 'required|string|max:50',
            'whatsapp_default_message' => 'required|string|max:1000',
        ]);

        Setting::set('chat_enabled', $request->has('chat_enabled') ? '1' : '0');
        Setting::set('chat_welcome_message', $validated['chat_welcome_message']);
        Setting::set('chat_second_message', $validated['chat_second_message']);

        Setting::set('whatsapp_enabled', $request->has('whatsapp_enabled') ? '1' : '0');
        Setting::set('whatsapp_phone', $validated['whatsapp_phone']);
        Setting::set('whatsapp_default_message', $validated['whatsapp_default_message']);

        return redirect()->route('admin.live_chat.index')->with('success', 'Live Chat & WhatsApp settings updated successfully!');
    }
}
