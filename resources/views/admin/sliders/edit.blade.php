@extends('admin.layouts.master')

@section('title', 'Edit Hero Banner')
@section('header_title', 'Edit Hero Banner')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="{{ route('admin.home_sections.index') }}" class="btn btn-outline-secondary">
            <i class="fa-solid fa-arrow-left mr-5"></i> Back to Home Page Manager
        </a>
    </div>

    <div class="admin-card">
        <div class="admin-card-header">
            <h5>Edit Banner: {{ $slider->title }}</h5>
        </div>
        <div class="admin-card-body">
            <form action="{{ route('admin.sliders.update', $slider->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label font-weight-bold">Small Top Badge Text</label>
                        <input type="text" name="badge" class="form-control" value="{{ old('badge', $slider->badge) }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label font-weight-bold">Display Order</label>
                        <input type="number" name="order" class="form-control" value="{{ old('order', $slider->order) }}">
                    </div>

                    <div class="col-12 mb-3">
                        <label class="form-label font-weight-bold">Main Headline Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" value="{{ old('title', $slider->title) }}" required>
                    </div>

                    <div class="col-12 mb-3">
                        <label class="form-label font-weight-bold">Subtitle / Description</label>
                        <textarea name="subtitle" class="form-control" rows="3">{{ old('subtitle', $slider->subtitle) }}</textarea>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label font-weight-bold">Primary Button Text</label>
                        <input type="text" name="btn_text" class="form-control" value="{{ old('btn_text', $slider->btn_text) }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label font-weight-bold">Primary Button Link</label>
                        <input type="text" name="btn_link" class="form-control" value="{{ old('btn_link', $slider->btn_link) }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label font-weight-bold">Secondary Button Text</label>
                        <input type="text" name="btn_secondary_text" class="form-control" value="{{ old('btn_secondary_text', $slider->btn_secondary_text) }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label font-weight-bold">Secondary Button Link</label>
                        <input type="text" name="btn_secondary_link" class="form-control" value="{{ old('btn_secondary_link', $slider->btn_secondary_link) }}">
                    </div>

                    <div class="col-md-6 mb-4">
                        <label class="form-label font-weight-bold">Banner Image</label>
                        @if($slider->image)
                            <div class="mb-2">
                                <img src="{{ asset($slider->image) }}" alt="Preview" class="rounded shadow-sm" style="max-height: 100px;">
                            </div>
                        @endif
                        <input type="file" name="image" class="form-control" accept="image/*">
                    </div>

                    <div class="col-md-6 mb-4 d-flex align-items-center pt-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" {{ $slider->is_active ? 'checked' : '' }}>
                            <label class="form-check-label font-weight-bold" for="is_active">
                                Active (Visible on Homepage)
                            </label>
                        </div>
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn-theme py-2 px-4">
                            <i class="fa-solid fa-floppy-disk mr-5"></i> Update Banner
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection
