<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inquiry;
use App\Models\BlogComment;
use App\Models\ChatMessage;
use App\Models\ChatConversation;
use Carbon\Carbon;
use Illuminate\Support\Str;

class NotificationController extends Controller
{
    /**
     * Check for new inquiries, newsletter subscriptions, comments, and chat messages.
     */
    public function check(Request $request)
    {
        $lastCheckTimestamp = $request->input('last_check');
        $lastCheck = $lastCheckTimestamp ? Carbon::createFromTimestamp($lastCheckTimestamp) : now()->subSeconds(10);

        $events = [];

        // 1. Check for newly arrived Inquiries & Newsletters
        $newInquiries = Inquiry::where('created_at', '>', $lastCheck)
            ->orderBy('created_at', 'asc')
            ->get();

        foreach ($newInquiries as $inq) {
            $isNewsletter = ($inq->name === 'Newsletter Subscriber' || str_contains(strtolower($inq->message), 'newsletter'));

            if ($isNewsletter) {
                $events[] = [
                    'id' => 'newsletter_' . $inq->id,
                    'type' => 'newsletter',
                    'title' => '📬 New Newsletter Subscriber!',
                    'body' => 'New email subscription received: ' . $inq->email,
                    'url' => route('admin.inquiries.index'),
                    'sound' => 'chime',
                    'timestamp' => $inq->created_at->timestamp,
                ];
            } else {
                $events[] = [
                    'id' => 'inquiry_' . $inq->id,
                    'type' => 'inquiry',
                    'title' => '🏥 New Inquiry: ' . $inq->name,
                    'body' => Str::limit($inq->message ?: 'Equipment inquiry / consultation request', 90),
                    'url' => route('admin.inquiries.show', $inq->id),
                    'sound' => 'chime',
                    'timestamp' => $inq->created_at->timestamp,
                ];
            }
        }

        // 2. Check for newly arrived Blog Comments
        if (class_exists(BlogComment::class)) {
            $newComments = BlogComment::where('status', 'pending')
                ->where('created_at', '>', $lastCheck)
                ->orderBy('created_at', 'asc')
                ->get();

            foreach ($newComments as $c) {
                $events[] = [
                    'id' => 'comment_' . $c->id,
                    'type' => 'comment',
                    'title' => '💬 New Blog Comment (Pending Approval)',
                    'body' => $c->name . ': "' . Str::limit($c->comment, 80) . '"',
                    'url' => route('admin.blog_comments.index'),
                    'sound' => 'chime',
                    'timestamp' => $c->created_at->timestamp,
                ];
            }
        }

        // 3. Check for newly arrived Live Chat Messages from users
        if (class_exists(ChatMessage::class)) {
            $newChats = ChatMessage::where('sender_type', 'user')
                ->where('created_at', '>', $lastCheck)
                ->with('conversation')
                ->orderBy('created_at', 'asc')
                ->get();

            foreach ($newChats as $chat) {
                $convName = $chat->conversation ? ($chat->conversation->name ?: 'Website Visitor') : 'Website Visitor';
                $events[] = [
                    'id' => 'chat_' . $chat->id,
                    'type' => 'chat',
                    'title' => '⚡ Live Support Chat: ' . $convName,
                    'body' => Str::limit($chat->message ?: 'Sent an attachment / message', 85),
                    'url' => route('admin.live_chat.index'),
                    'sound' => 'chime',
                    'timestamp' => $chat->created_at->timestamp,
                ];
            }
        }

        // Badge counts
        $unreadInquiriesCount = Inquiry::where('status', 'unread')->count();
        $pendingCommentsCount = class_exists(BlogComment::class) ? BlogComment::where('status', 'pending')->count() : 0;
        $unreadChatCount = class_exists(ChatConversation::class) ? (int) ChatConversation::sum('unread_admin') : 0;

        return response()->json([
            'success' => true,
            'server_time' => now()->timestamp,
            'events' => $events,
            'badges' => [
                'inquiries' => $unreadInquiriesCount,
                'comments' => $pendingCommentsCount,
                'chats' => $unreadChatCount,
            ]
        ]);
    }
}
