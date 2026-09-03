@extends('admin.layouts.master')

@section('title', 'Blog Comments Moderation')
@section('header_title', 'Blog Comments Moderation')

@section('content')

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h4 class="mb-1 text-dark fw-bold">User Comments Moderation Desk</h4>
            <p class="text-muted mb-0">Review, approve, or reject comments submitted by visitors. Only approved comments appear live on the website.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.blogs.index') }}" class="btn btn-outline-secondary">
                <i class="fa-solid fa-newspaper me-1"></i> Manage Articles
            </a>
            <a href="{{ route('blog') }}" target="_blank" class="btn btn-outline-primary">
                <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> Live Blog
            </a>
        </div>
    </div>

    <!-- STATS / STATUS FILTER TABS -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-sm-6">
            <a href="{{ route('admin.blog_comments.index', ['status' => 'all', 'blog_id' => $blogId, 'search' => $search]) }}" class="text-decoration-none">
                <div class="admin-card p-3 h-100 border-start border-4 border-primary {{ $status === 'all' ? 'shadow-sm bg-white ring-active' : 'bg-light' }}">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small fw-semibold text-uppercase">All Comments</span>
                            <h3 class="mb-0 fw-bold text-dark mt-1">{{ $counts['all'] }}</h3>
                        </div>
                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; background: #eef2ff; color: #0E63FF;">
                            <i class="fa-solid fa-comments fa-lg"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-xl-3 col-sm-6">
            <a href="{{ route('admin.blog_comments.index', ['status' => 'pending', 'blog_id' => $blogId, 'search' => $search]) }}" class="text-decoration-none">
                <div class="admin-card p-3 h-100 border-start border-4 border-warning {{ $status === 'pending' ? 'shadow-sm bg-white ring-active' : 'bg-light' }}">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small fw-semibold text-uppercase">Pending Approval</span>
                            <h3 class="mb-0 fw-bold text-warning mt-1">{{ $counts['pending'] }}</h3>
                        </div>
                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; background: #fef3c7; color: #d97706;">
                            <i class="fa-solid fa-hourglass-half fa-lg"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-xl-3 col-sm-6">
            <a href="{{ route('admin.blog_comments.index', ['status' => 'approved', 'blog_id' => $blogId, 'search' => $search]) }}" class="text-decoration-none">
                <div class="admin-card p-3 h-100 border-start border-4 border-success {{ $status === 'approved' ? 'shadow-sm bg-white ring-active' : 'bg-light' }}">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small fw-semibold text-uppercase">Approved (Live)</span>
                            <h3 class="mb-0 fw-bold text-success mt-1">{{ $counts['approved'] }}</h3>
                        </div>
                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; background: #dcfce7; color: #15803D;">
                            <i class="fa-solid fa-circle-check fa-lg"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-xl-3 col-sm-6">
            <a href="{{ route('admin.blog_comments.index', ['status' => 'rejected', 'blog_id' => $blogId, 'search' => $search]) }}" class="text-decoration-none">
                <div class="admin-card p-3 h-100 border-start border-4 border-danger {{ $status === 'rejected' ? 'shadow-sm bg-white ring-active' : 'bg-light' }}">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small fw-semibold text-uppercase">Rejected</span>
                            <h3 class="mb-0 fw-bold text-danger mt-1">{{ $counts['rejected'] }}</h3>
                        </div>
                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; background: #fee2e2; color: #b91c1c;">
                            <i class="fa-solid fa-circle-xmark fa-lg"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- SEARCH & ARTICLE FILTER -->
    <div class="admin-card mb-4">
        <div class="admin-card-body p-3">
            <form action="{{ route('admin.blog_comments.index') }}" method="GET" class="row g-2 align-items-center">
                <input type="hidden" name="status" value="{{ $status }}">
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-magnifying-glass"></i></span>
                        <input type="text" name="search" class="form-control border-start-0" placeholder="Search commenter name, email, comment text..." value="{{ $search }}">
                    </div>
                </div>
                <div class="col-md-5">
                    <select name="blog_id" class="form-select">
                        <option value="">All Blog Articles</option>
                        @foreach($blogs as $b)
                            <option value="{{ $b->id }}" {{ $blogId == $b->id ? 'selected' : '' }}>{{ Str::limit($b->title, 60) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-grow-1"><i class="fa-solid fa-filter me-1"></i> Filter</button>
                    @if($search !== '' || !empty($blogId) || $status !== 'all')
                        <a href="{{ route('admin.blog_comments.index') }}" class="btn btn-light" title="Reset All Filters"><i class="fa-solid fa-rotate-left"></i></a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center mb-4" role="alert">
            <i class="fa-solid fa-circle-check fs-4 me-2"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- COMMENTS TABLE -->
    <div class="admin-card">
        <div class="table-responsive">
            <table class="table table-custom align-middle">
                <thead>
                    <tr>
                        <th width="40">#</th>
                        <th width="200">Commenter</th>
                        <th>Target Article</th>
                        <th>Comment Text</th>
                        <th width="130">Status</th>
                        <th width="130">Submitted</th>
                        <th width="160" class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($comments as $comment)
                        <tr id="comment-row-{{ $comment->id }}">
                            <td>{{ $loop->iteration + ($comments->currentPage() - 1) * $comments->perPage() }}</td>
                            <td>
                                <strong class="text-dark d-block" style="font-size: 0.95rem;">{{ $comment->name }}</strong>
                                <a href="mailto:{{ $comment->email }}" class="text-muted small text-decoration-none d-block">
                                    <i class="fa-regular fa-envelope me-1"></i> {{ $comment->email }}
                                </a>
                                @if($comment->phone)
                                    <span class="text-muted small d-block"><i class="fa-regular fa-phone me-1"></i> {{ $comment->phone }}</span>
                                @endif
                                @if($comment->website)
                                    <a href="{{ $comment->website }}" target="_blank" class="text-primary small d-block"><i class="fa-regular fa-globe me-1"></i> {{ Str::limit($comment->website, 25) }}</a>
                                @endif
                            </td>
                            <td>
                                @if($comment->blog)
                                    <a href="{{ route('blog.detail', $comment->blog->slug) }}" target="_blank" class="fw-semibold text-primary text-decoration-none">
                                        {{ Str::limit($comment->blog->title, 40) }}
                                        <i class="fa-solid fa-arrow-up-right-from-square small ms-1"></i>
                                    </a>
                                    <span class="badge bg-light text-muted border d-block mt-1" style="width: fit-content;">{{ $comment->blog->category }}</span>
                                @else
                                    <span class="text-muted">Article Removed</span>
                                @endif
                            </td>
                            <td>
                                <div class="p-2 rounded bg-light border text-secondary" style="font-size: 0.9rem; line-height: 1.5; max-width: 380px;">
                                    "{{ $comment->comment }}"
                                </div>
                            </td>
                            <td id="status-col-{{ $comment->id }}">
                                @if($comment->status === 'approved' || $comment->is_approved)
                                    <span class="badge bg-success text-white px-2 py-1"><i class="fa-solid fa-circle-check me-1"></i> Live / Approved</span>
                                @elseif($comment->status === 'rejected')
                                    <span class="badge bg-danger text-white px-2 py-1"><i class="fa-solid fa-circle-xmark me-1"></i> Rejected</span>
                                @else
                                    <span class="badge bg-warning text-dark px-2 py-1"><i class="fa-solid fa-hourglass-start me-1"></i> Pending Approval</span>
                                @endif
                            </td>
                            <td>
                                <small class="text-muted d-block">{{ $comment->created_at ? $comment->created_at->format('M d, Y') : '-' }}</small>
                                <small class="text-secondary opacity-75">{{ $comment->created_at ? $comment->created_at->diffForHumans() : '' }}</small>
                            </td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <button type="button" class="btn btn-outline-success btn-approve-comment" data-id="{{ $comment->id }}" title="Approve & Show Live on Website">
                                        <i class="fa-solid fa-check"></i> Approve
                                    </button>
                                    <button type="button" class="btn btn-outline-warning btn-reject-comment text-dark" data-id="{{ $comment->id }}" title="Reject Comment">
                                        <i class="fa-solid fa-ban"></i>
                                    </button>
                                </div>
                                <form action="{{ route('admin.blog_comments.destroy', $comment->id) }}" method="POST" class="d-inline ms-1" onsubmit="return confirm('Permanently delete this comment?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-light text-danger" title="Delete Comment">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="fa-light fa-comments fa-3x mb-3 d-block text-secondary opacity-50"></i>
                                No comments found in this view.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($comments->hasPages())
            <div class="p-3 border-top">
                {{ $comments->links() }}
            </div>
        @endif
    </div>

@endsection

@push('styles')
<style>
.ring-active {
    border-color: #0E63FF !important;
    background: #ffffff !important;
    box-shadow: 0 4px 12px rgba(14, 99, 255, 0.12) !important;
}
</style>
@endpush

@push('scripts')
<script>
document.querySelectorAll('.btn-approve-comment').forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.getAttribute('data-id');
        const row = document.getElementById(`comment-row-${id}`);
        const statusCol = document.getElementById(`status-col-${id}`);

        fetch(`{{ url('/admin/blog-comments') }}/${id}/approve`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                statusCol.innerHTML = '<span class="badge bg-success text-white px-2 py-1"><i class="fa-solid fa-circle-check me-1"></i> Live / Approved</span>';
            }
        })
        .catch(err => alert('Failed to approve comment.'));
    });
});

document.querySelectorAll('.btn-reject-comment').forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.getAttribute('data-id');
        const statusCol = document.getElementById(`status-col-${id}`);

        fetch(`{{ url('/admin/blog-comments') }}/${id}/reject`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                statusCol.innerHTML = '<span class="badge bg-danger text-white px-2 py-1"><i class="fa-solid fa-circle-xmark me-1"></i> Rejected</span>';
            }
        })
        .catch(err => alert('Failed to reject comment.'));
    });
});
</script>
@endpush
