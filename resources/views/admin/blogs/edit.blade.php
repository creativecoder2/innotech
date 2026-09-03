@extends('admin.layouts.master')

@section('title', 'Edit Article - ' . $blog->title)
@section('header_title', 'Edit Research / Blog Post')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="{{ route('admin.blogs.index') }}" class="btn btn-outline-secondary">
            <i class="fa-solid fa-arrow-left me-1"></i> Back to Articles
        </a>
        <a href="{{ route('blog.detail', $blog->slug) }}" target="_blank" class="btn btn-light text-primary border">
            <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> View Live on Website
        </a>
    </div>

    <form action="{{ route('admin.blogs.update', $blog->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- 1. CORE DETAILS -->
        <div class="admin-card mb-4">
            <div class="admin-card-header bg-light">
                <h5 class="mb-0 text-primary"><i class="fa-solid fa-circle-info me-2"></i> 1. Core Information</h5>
            </div>
            <div class="admin-card-body">
                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label font-weight-bold">Article Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" value="{{ old('title', $blog->title) }}" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label font-weight-bold">Category <span class="text-danger">*</span></label>
                        <input type="text" name="category" class="form-control" value="{{ old('category', $blog->category) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label font-weight-bold">Popular Tags (Comma Separated)</label>
                        <input type="text" name="tags" class="form-control" placeholder="e.g. Covid-19, ICU, Biomedical, Laboratory, Surgery" value="{{ old('tags', $blog->tags) }}">
                        <small class="text-muted">Separate multiple tags with commas. These appear in the sidebar tag cloud.</small>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label font-weight-bold">Author Name <span class="text-danger">*</span></label>
                        <input type="text" name="author" class="form-control" value="{{ old('author', $blog->author) }}" required>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label font-weight-bold">Publication Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select" required>
                            <option value="published" {{ old('status', $blog->status) === 'published' ? 'selected' : '' }}>Published</option>
                            <option value="draft" {{ old('status', $blog->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label font-weight-bold">Published Date</label>
                        <input type="date" name="published_at" class="form-control" value="{{ $blog->published_at ? $blog->published_at->format('Y-m-d') : date('Y-m-d') }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label font-weight-bold">Views Count</label>
                        <input type="number" name="views" class="form-control" value="{{ old('views', $blog->views) }}">
                        <small class="text-muted">Auto-increments every time an article is viewed.</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. ARTICLE CONTENT & QUOTES -->
        <div class="admin-card mb-4">
            <div class="admin-card-header bg-light">
                <h5 class="mb-0 text-primary"><i class="fa-solid fa-newspaper me-2"></i> 2. Content, Summary & Quote</h5>
            </div>
            <div class="admin-card-body">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label font-weight-bold">Short Summary / Abstract <span class="text-danger">*</span></label>
                        <textarea name="summary" class="form-control" rows="3" required>{{ old('summary', $blog->summary) }}</textarea>
                    </div>

                    <div class="col-12">
                        <label class="form-label font-weight-bold">Full Article Content <span class="text-danger">*</span></label>
                        <textarea name="content" class="form-control" rows="10" required>{{ old('content', $blog->content) }}</textarea>
                    </div>

                    <div class="col-md-8">
                        <label class="form-label font-weight-bold">Featured Blockquote (Optional)</label>
                        <textarea name="quote" class="form-control" rows="2" placeholder="Featured highlighted quote...">{{ old('quote', $blog->quote) }}</textarea>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label font-weight-bold">Quote Author / Citation</label>
                        <input type="text" name="quote_author" class="form-control" placeholder="e.g. Dr. Cameron Williamson" value="{{ old('quote_author', $blog->quote_author) }}">
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. MEDIA & POST FORMATS -->
        <div class="admin-card mb-4">
            <div class="admin-card-header bg-light">
                <h5 class="mb-0 text-primary"><i class="fa-solid fa-photo-film me-2"></i> 3. Media & Post Formats</h5>
            </div>
            <div class="admin-card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label font-weight-bold">Featured Main Image</label>
                        @if($blog->image)
                            <div class="mb-2">
                                <img src="{{ asset($blog->image) }}" alt="Preview" class="rounded shadow-sm" style="max-height: 120px; object-fit: cover;">
                            </div>
                        @endif
                        <input type="file" name="image" class="form-control" accept="image/*,.jfif,.png,.jpg,.jpeg,.webp,.svg,.gif,.avif,.bmp">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label font-weight-bold">Video URL (For Video Post Format)</label>
                        <input type="text" name="video_url" class="form-control" placeholder="e.g. https://www.youtube.com/watch?v=OMqWRlxo1oQ" value="{{ old('video_url', $blog->video_url) }}">
                        <small class="text-muted">Adds popup play button overlay on thumbnail.</small>
                    </div>

                    <div class="col-12">
                        <label class="form-label font-weight-bold">Gallery Slider Images (For Gallery Slider Format)</label>
                        @if(!empty($blog->slider_images) && is_array($blog->slider_images))
                            <div class="d-flex flex-wrap gap-2 mb-2">
                                @foreach($blog->slider_images as $sImg)
                                    <img src="{{ asset($sImg) }}" alt="Gallery item" class="rounded shadow-sm" style="width: 80px; height: 60px; object-fit: cover;">
                                @endforeach
                            </div>
                        @endif
                        <input type="file" name="slider_images[]" class="form-control" multiple accept="image/*,.jfif,.png,.jpg,.jpeg,.webp,.svg,.gif,.avif,.bmp">
                        <small class="text-muted">Upload new images to append or replace gallery slider.</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4. EXTENDED DETAILS & APPROACH SECTION -->
        <div class="admin-card mb-4">
            <div class="admin-card-header bg-light">
                <h5 class="mb-0 text-primary"><i class="fa-solid fa-flask-vial me-2"></i> 4. Clinical Approach & Sub-Images (Optional)</h5>
            </div>
            <div class="admin-card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label font-weight-bold">Section Heading</label>
                        <input type="text" name="approach_title" class="form-control" value="{{ old('approach_title', $blog->approach_title) }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label font-weight-bold">Side Image (Meta Image 1)</label>
                        @if($blog->meta_image_1)
                            <div class="mb-2">
                                <img src="{{ asset($blog->meta_image_1) }}" alt="Preview" class="rounded shadow-sm" style="max-height: 90px; object-fit: cover;">
                            </div>
                        @endif
                        <input type="file" name="meta_image_1" class="form-control" accept="image/*,.jfif,.png,.jpg,.jpeg,.webp,.svg,.gif,.avif,.bmp">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label font-weight-bold">Approach Description</label>
                        <textarea name="approach_text" class="form-control" rows="4">{{ old('approach_text', $blog->approach_text) }}</textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label font-weight-bold">Key Checklist Points (1 per line)</label>
                        <textarea name="approach_points" class="form-control" rows="4">{{ old('approach_points', $blog->approach_points) }}</textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label font-weight-bold">Full Width Detail Image (Meta Image 2)</label>
                        @if($blog->meta_image_2)
                            <div class="mb-2">
                                <img src="{{ asset($blog->meta_image_2) }}" alt="Preview" class="rounded shadow-sm" style="max-height: 90px; object-fit: cover;">
                            </div>
                        @endif
                        <input type="file" name="meta_image_2" class="form-control" accept="image/*,.jfif,.png,.jpg,.jpeg,.webp,.svg,.gif,.avif,.bmp">
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-5 text-end">
            <button type="submit" class="btn-theme py-3 px-5 fs-6">
                <i class="fa-solid fa-floppy-disk me-2"></i> Update Article
            </button>
        </div>
    </form>

@endsection
