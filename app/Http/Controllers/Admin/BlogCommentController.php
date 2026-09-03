<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogComment;
use Illuminate\Http\Request;

class BlogCommentController extends Controller
{
    /**
     * Display listing of blog comments with filters and pagination.
     */
    public function index(Request $request)
    {
        $status = $request->input('status', 'all');
        $blogId = $request->input('blog_id');
        $search = trim($request->input('search', ''));

        $query = BlogComment::with('blog')->orderBy('created_at', 'desc');

        if ($status === 'pending') {
            $query->where('status', 'pending');
        } elseif ($status === 'approved') {
            $query->where(function ($q) {
                $q->where('status', 'approved')->orWhere('is_approved', true);
            });
        } elseif ($status === 'rejected') {
            $query->where('status', 'rejected');
        }

        if (!empty($blogId)) {
            $query->where('blog_id', $blogId);
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('comment', 'LIKE', "%{$search}%");
            });
        }

        $comments = $query->paginate(15)->withQueryString();

        $counts = [
            'all' => BlogComment::count(),
            'pending' => BlogComment::where('status', 'pending')->count(),
            'approved' => BlogComment::where('status', 'approved')->orWhere('is_approved', true)->count(),
            'rejected' => BlogComment::where('status', 'rejected')->count(),
        ];

        $blogs = Blog::orderBy('title', 'asc')->get(['id', 'title']);

        return view('admin.blog_comments.index', compact('comments', 'counts', 'status', 'blogs', 'blogId', 'search'));
    }

    /**
     * Approve a comment and publish it to the website.
     */
    public function approve(Request $request, BlogComment $comment)
    {
        $comment->update([
            'status' => 'approved',
            'is_approved' => true,
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Comment approved and published to website.',
                'status' => 'approved',
            ]);
        }

        return redirect()->back()->with('success', 'Comment approved successfully! It is now visible on the website.');
    }

    /**
     * Reject a comment.
     */
    public function reject(Request $request, BlogComment $comment)
    {
        $comment->update([
            'status' => 'rejected',
            'is_approved' => false,
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Comment marked as rejected.',
                'status' => 'rejected',
            ]);
        }

        return redirect()->back()->with('success', 'Comment marked as rejected.');
    }

    /**
     * Permanently delete a comment.
     */
    public function destroy(BlogComment $comment)
    {
        $comment->delete();

        return redirect()->back()->with('success', 'Comment permanently deleted.');
    }
}
