@extends('admin.layouts.master')

@section('title', 'Create Research / Blog Post')
@section('header_title', 'Create Research / Blog Post')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="{{ route('admin.blogs.index') }}" class="btn btn-outline-secondary">
            <i class="fa-solid fa-arrow-left me-1"></i> Back to Articles
        </a>
    </div>

    <form action="{{ route('admin.blogs.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- 1. CORE DETAILS -->
        <div class="admin-card mb-4">
            <div class="admin-card-header bg-light">
                <h5 class="mb-0 text-primary"><i class="fa-solid fa-circle-info me-2"></i> 1. Core Information</h5>
            </div>
            <div class="admin-card-body">
                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label font-weight-bold">Article Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" placeholder="e.g. Next-Gen Modular Operating Theatres: Design & Safety Protocols" value="{{ old('title') }}" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label font-weight-bold">Category <span class="text-danger">*</span></label>
                        <input type="text" name="category" class="form-control" placeholder="e.g. Medical Technology, Critical Care" value="{{ old('category', 'Medical Technology') }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label font-weight-bold">Popular Tags (Comma Separated)</label>
                        <input type="text" name="tags" class="form-control" placeholder="e.g. Covid-19, ICU, Biomedical, Laboratory, Surgery" value="{{ old('tags') }}">
                        <small class="text-muted">Separate multiple tags with commas. These appear in the sidebar tag cloud.</small>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label font-weight-bold">Author Name <span class="text-danger">*</span></label>
                        <input type="text" name="author" class="form-control" value="{{ old('author', 'Innotech Medical Team') }}" required>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label font-weight-bold">Publication Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select" required>
                            <option value="published" selected>Published</option>
                            <option value="draft">Draft</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label font-weight-bold">Published Date</label>
                        <input type="date" name="published_at" class="form-control" value="{{ date('Y-m-d') }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label font-weight-bold">Initial Views Count</label>
                        <input type="number" name="views" class="form-control" value="{{ rand(120, 450) }}">
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
                        <textarea name="summary" class="form-control" rows="3" placeholder="Brief 2-3 sentence overview shown in blog listing cards..." required>{{ old('summary') }}</textarea>
                    </div>

                    <div class="col-12">
                        <label class="form-label font-weight-bold">Full Article Content <span class="text-danger">*</span></label>
                        <textarea name="content" class="form-control" rows="10" placeholder="Detailed clinical article or research write-up..." required>{{ old('content') }}</textarea>
                    </div>

                    <div class="col-md-8">
                        <label class="form-label font-weight-bold">Featured Blockquote (Optional)</label>
                        <textarea name="quote" class="form-control" rows="2" placeholder="e.g. 'Precision diagnostics coupled with automated ICU workstations reduce critical error margins significantly.'">{{ old('quote') }}</textarea>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label font-weight-bold">Quote Author / Citation</label>
                        <input type="text" name="quote_author" class="form-control" placeholder="e.g. Dr. Cameron Williamson, Lead Biomedical Engineer" value="{{ old('quote_author') }}">
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
                        <input type="file" name="image" class="form-control" accept="image/*,.jfif,.png,.jpg,.jpeg,.webp,.svg,.gif,.avif,.bmp">
                        <small class="text-muted">Primary thumbnail displayed in listings and article header.</small>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label font-weight-bold">Video URL (For Video Post Format)</label>
                        <input type="text" name="video_url" class="form-control" placeholder="e.g. https://www.youtube.com/watch?v=OMqWRlxo1oQ" value="{{ old('video_url') }}">
                        <small class="text-muted">Providing a video link adds the play button and video format styling.</small>
                    </div>

                    <div class="col-12">
                        <label class="form-label font-weight-bold">Gallery Slider Images (For Gallery Slider Format)</label>
                        <input type="file" name="slider_images[]" class="form-control" multiple accept="image/*,.jfif,.png,.jpg,.jpeg,.webp,.svg,.gif,.avif,.bmp">
                        <small class="text-muted">Upload 2 or more images to transform this post into an interactive swiper slider format.</small>
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
                        <input type="text" name="approach_title" class="form-control" placeholder="e.g. Our Clinical Approach & Research Methodology" value="{{ old('approach_title') }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label font-weight-bold">Side Image (Meta Image 1)</label>
                        <input type="file" name="meta_image_1" class="form-control" accept="image/*,.jfif,.png,.jpg,.jpeg,.webp,.svg,.gif,.avif,.bmp">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label font-weight-bold">Approach Description</label>
                        <textarea name="approach_text" class="form-control" rows="4" placeholder="Description of methodologies, technology stacks, or experimental protocols...">{{ old('approach_text') }}</textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label font-weight-bold">Key Checklist Points (1 per line)</label>
                        <textarea name="approach_points" class="form-control" rows="4" placeholder="Extramural Biomedical Funding&#10;Pathogen Biomarker Screening&#10;ISO 13485 Calibration Compliance">{{ old('approach_points') }}</textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label font-weight-bold">Full Width Detail Image (Meta Image 2)</label>
                        <input type="file" name="meta_image_2" class="form-control" accept="image/*,.jfif,.png,.jpg,.jpeg,.webp,.svg,.gif,.avif,.bmp">
                        <small class="text-muted">Displayed as full-width clinical installation photo.</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-5 text-end">
            <button type="submit" class="btn-theme py-3 px-5 fs-6">
                <i class="fa-solid fa-floppy-disk me-2"></i> Publish Article
            </button>
        </div>
    </form>

@endsection
