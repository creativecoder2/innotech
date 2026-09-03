@extends('admin.layouts.master')

@section('title', 'Research & Blog Articles')
@section('header_title', 'Research & Blog Posts')

@section('content')

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h4 class="mb-1 text-dark fw-bold">Clinical Articles & Research Publications</h4>
            <p class="text-muted mb-0">Publish medical insights, equipment guides, and laboratory news with full dynamic controls.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('blog') }}" target="_blank" class="btn btn-outline-secondary">
               <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> View Live Blog
            </a>
            <a href="{{ route('admin.blogs.create') }}" class="btn-theme">
                <i class="fa-solid fa-plus me-1"></i> Post New Article
            </a>
        </div>
    </div>

    <!-- FILTER BAR -->
    <div class="admin-card mb-4">
        <div class="admin-card-body p-3">
            <form action="{{ route('admin.blogs.index') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-magnifying-glass"></i></span>
                        <input type="text" name="search" class="form-control border-start-0" placeholder="Search by title, author, category, tags..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="category" class="form-select">
                        <option value="">All Categories</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">All Statuses</option>
                        <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-grow-1"><i class="fa-solid fa-filter me-1"></i> Filter</button>
                    @if(request()->hasAny(['search', 'category', 'status']))
                        <a href="{{ route('admin.blogs.index') }}" class="btn btn-light" title="Clear Filters"><i class="fa-solid fa-rotate-left"></i></a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- TABLE LIST -->
    <div class="admin-card">
        <div class="table-responsive">
            <table class="table table-custom align-middle">
                <thead>
                    <tr>
                        <th width="50">#</th>
                        <th width="80">Thumbnail</th>
                        <th>Title & Category</th>
                        <th>Author</th>
                        <th width="110">Views</th>
                        <th width="120">Status</th>
                        <th width="140">Date</th>
                        <th width="160" class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($blogs as $blog)
                        <tr>
                            <td>{{ $loop->iteration + ($blogs->currentPage() - 1) * $blogs->perPage() }}</td>
                            <td>
                                <img src="{{ asset($blog->image ?: 'assets/img/blog/blog-thumb-01.jpg') }}" alt="Thumbnail" class="rounded shadow-sm" style="width: 70px; height: 50px; object-fit: cover;">
                            </td>
                            <td>
                                <strong style="color: #002244; font-size: 0.95rem;">{{ $blog->title }}</strong>
                                <div class="mt-1 d-flex flex-wrap gap-1 align-items-center">
                                    <span class="badge bg-light text-primary border">{{ $blog->category }}</span>
                                    @if(!empty($blog->video_url))
                                        <span class="badge bg-danger text-white"><i class="fa-brands fa-youtube me-1"></i> Video</span>
                                    @endif
                                    @if(!empty($blog->slider_images))
                                        <span class="badge bg-warning text-dark"><i class="fa-solid fa-images me-1"></i> Gallery</span>
                                    @endif
                                    @if(!empty($blog->tags))
                                        <span class="text-muted small ms-1"><i class="fa-solid fa-tag me-1"></i>{{ Str::limit($blog->tags, 30) }}</span>
                                    @endif
                                </div>
                            </td>
                            <td>{{ $blog->author }}</td>
                            <td>
                                <span class="badge bg-light text-dark border">
                                    <i class="fa-solid fa-eye text-primary me-1"></i> {{ number_format($blog->views) }}
                                </span>
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-toggle-status {{ $blog->status === 'published' ? 'btn-success' : 'btn-secondary' }}" data-id="{{ $blog->id }}" title="Click to toggle publish status">
                                    <i class="fa-solid {{ $blog->status === 'published' ? 'fa-check' : 'fa-pause' }} me-1"></i>
                                    <span>{{ ucfirst($blog->status) }}</span>
                                </button>
                            </td>
                            <td><small class="text-muted">{{ $blog->published_at ? $blog->published_at->format('M d, Y') : '-' }}</small></td>
                            <td class="text-end">
                                <a href="{{ route('blog.detail', $blog->slug) }}" target="_blank" class="btn btn-sm btn-light text-success me-1" title="View live on website">
                                    <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                </a>
                                <a href="{{ route('admin.blogs.edit', $blog->id) }}" class="btn btn-sm btn-light text-primary me-1" title="Edit">
                                    <i class="fa-solid fa-pencil"></i>
                                </a>
                                <form action="{{ route('admin.blogs.destroy', $blog->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this article?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-light text-danger" title="Delete">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="fa-light fa-newspaper fa-3x mb-3 d-block text-secondary opacity-50"></i>
                                No blog articles found matching your criteria.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($blogs->hasPages())
            <div class="p-3 border-top">
                {{ $blogs->links() }}
            </div>
        @endif
    </div>

@endsection

@push('scripts')
<script>
document.querySelectorAll('.btn-toggle-status').forEach(btn => {
    btn.addEventListener('click', function() {
        const blogId = this.getAttribute('data-id');
        const button = this;

        fetch(`{{ url('/admin/blogs') }}/${blogId}/toggle`, {
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
                if (data.status === 'published') {
                    button.className = 'btn btn-sm btn-toggle-status btn-success';
                    button.innerHTML = '<i class="fa-solid fa-check me-1"></i> <span>Published</span>';
                } else {
                    button.className = 'btn btn-sm btn-toggle-status btn-secondary';
                    button.innerHTML = '<i class="fa-solid fa-pause me-1"></i> <span>Draft</span>';
                }
            }
        })
        .catch(err => alert('Failed to update status.'));
    });
});
</script>
@endpush
