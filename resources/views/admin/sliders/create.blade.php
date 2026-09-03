@extends('admin.layouts.master')

@section('title', 'Add New Hero Banner')
@section('header_title', 'Add New Hero Banner')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="{{ route('admin.home_sections.index') }}" class="btn btn-outline-secondary">
            <i class="fa-solid fa-arrow-left mr-5"></i> Back to Home Page Manager
        </a>
    </div>

    <div class="admin-card">
        <div class="admin-card-header">
            <h5>Banner Content & Settings</h5>
        </div>
        <div class="admin-card-body">
            <form action="{{ route('admin.sliders.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label font-weight-bold">Small Top Badge Text</label>
                        <input type="text" name="badge" class="form-control" placeholder="e.g. WELCOME TO INNOTECH MEDICAL" value="{{ old('badge') }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label font-weight-bold">Display Order</label>
                        <input type="number" name="order" class="form-control" value="{{ old('order', 1) }}">
                    </div>

                    <div class="col-12 mb-3">
                        <label class="form-label font-weight-bold">Main Headline Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" placeholder="e.g. Advanced Medical & Diagnostic Technologies" value="{{ old('title') }}" required>
                    </div>

                    <div class="col-12 mb-3">
                        <label class="form-label font-weight-bold">Subtitle / Description</label>
                        <textarea name="subtitle" class="form-control" rows="3" placeholder="Description under headline...">{{ old('subtitle') }}</textarea>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label font-weight-bold">Primary Button Text</label>
                        <input type="text" name="btn_text" class="form-control" value="{{ old('btn_text', 'Explore Our Services') }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label font-weight-bold">Primary Button Link</label>
                        <input type="text" name="btn_link" class="form-control" value="{{ old('btn_link', '#services-section') }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label font-weight-bold">Secondary Button Text</label>
                        <input type="text" name="btn_secondary_text" class="form-control" value="{{ old('btn_secondary_text', 'Contact Us') }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label font-weight-bold">Secondary Button Link</label>
                        <input type="text" name="btn_secondary_link" class="form-control" value="{{ old('btn_secondary_link', '#contact-section') }}">
                    </div>

                    <div class="col-md-6 mb-4">
                        <label class="form-label font-weight-bold">Background / Banner Image</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                    </div>

                    <div class="col-md-6 mb-4 d-flex align-items-center pt-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" checked>
                            <label class="form-check-label font-weight-bold" for="is_active">
                                Active (Visible on Homepage)
                            </label>
                        </div>
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn-theme py-2 px-4">
                            <i class="fa-solid fa-floppy-disk mr-5"></i> Create Banner
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection
