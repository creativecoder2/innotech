@extends('admin.layouts.master')

@section('title', 'Edit Product: ' . $product->title)
@section('header_title', 'Edit Product')

@section('content')

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="mb-1 text-dark fw-bold">Edit Medical Equipment</h4>
            <p class="text-muted mb-0">Update technical information, key features list, and product imagery.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('product.detail', $product->slug) }}" target="_blank" class="btn btn-outline-secondary">
                <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> View Live
            </a>
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
                <i class="fa-solid fa-arrow-left me-1"></i> Back to Products
            </a>
        </div>
    </div>

    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row g-4">
            
            <!-- LEFT MAIN COLUMN -->
            <div class="col-lg-8">
                <div class="admin-card p-4 mb-4">
                    <h5 class="fw-bold text-dark mb-3">General Information</h5>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Product Title *</label>
                        <input type="text" name="title" class="form-control rounded-3" value="{{ old('title', $product->title) }}" required>
                        @error('title') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">URL Slug *</label>
                        <input type="text" name="slug" class="form-control rounded-3" value="{{ old('slug', $product->slug) }}" required>
                        <small class="text-muted">Unique web identifier for this equipment.</small>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Manufacturer / Company</label>
                            <select name="company_id" class="form-select rounded-3">
                                <option value="">-- Select Manufacturer --</option>
                                @foreach($companies as $comp)
                                    <option value="{{ $comp->id }}" {{ old('company_id', $product->company_id) == $comp->id ? 'selected' : '' }}>
                                        {{ $comp->name }} {{ $comp->country ? '('.$comp->country.')' : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Model / SKU Code</label>
                            <input type="text" name="sku" class="form-control rounded-3" value="{{ old('sku', $product->sku) }}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Short Overview Description</label>
                        <textarea name="short_description" rows="3" class="form-control rounded-3">{{ old('short_description', $product->short_description) }}</textarea>
                    </div>

                    <!-- KEY FEATURES (BULLET POINTS) -->
                    @php
                        $featuresText = old('key_features', implode("\n", $product->features_list));
                    @endphp
                    <div class="mb-3 p-3 bg-light rounded-3 border">
                        <label class="form-label fw-bold text-dark mb-1">
                            <i class="fa-solid fa-list-check text-primary me-1"></i> Key Features (Bulleted List)
                        </label>
                        <p class="small text-muted mb-2">
                            Enter one key feature per line. Each line renders as a bullet item matching the design mockup.
                        </p>
                        <textarea name="key_features" rows="7" class="form-control rounded-3 font-monospace" style="font-size: 13.5px;">{{ $featuresText }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Full Technical & Clinical Details</label>
                        <textarea name="description" rows="6" class="form-control rounded-3">{{ old('description', $product->description) }}</textarea>
                    </div>

                </div>
            </div>

            <!-- RIGHT SIDEBAR COLUMN -->
            <div class="col-lg-4">
                
                <!-- PUBLISHING & STATUS -->
                <div class="admin-card p-4 mb-4">
                    <h5 class="fw-bold text-dark mb-3">Status & Visibility</h5>

                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="is_active" id="isActive" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold" for="isActive">Published on Website</label>
                    </div>

                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="is_featured" id="isFeatured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold" for="isFeatured">Featured in Related & Home</label>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Display Order</label>
                        <input type="number" name="order" class="form-control rounded-3" value="{{ old('order', $product->order) }}">
                    </div>

                    <hr class="my-3">

                    <button type="submit" class="btn btn-primary w-100 py-2.5 fw-bold rounded-3">
                        <i class="fa-solid fa-save me-1"></i> Update Product
                    </button>
                </div>

                <!-- PRODUCT IMAGE UPLOAD -->
                <div class="admin-card p-4">
                    <h5 class="fw-bold text-dark mb-3">Product Image</h5>
                    
                    <div class="image-preview-wrapper mb-3 border rounded-3 bg-white p-3 text-center" style="min-height: 220px; display: flex; align-items: center; justify-content: center;">
                        <img id="imagePreview" src="{{ asset($product->image ?: 'assets/img/shop/shop-01.jpg') }}" alt="Preview" 
                             style="max-height: 200px; max-width: 100%; object-fit: contain;">
                    </div>

                    <div class="mb-2">
                        <label class="form-label small fw-bold">Update Equipment Photo</label>
                        <input type="file" name="image" id="imageInput" class="form-control rounded-3" accept="image/*">
                        <small class="text-muted">Leave empty to keep existing image.</small>
                    </div>
                </div>

            </div>

        </div>
    </form>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const imageInput = document.getElementById('imageInput');
        const imagePreview = document.getElementById('imagePreview');

        if (imageInput && imagePreview) {
            imageInput.addEventListener('change', function () {
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        imagePreview.src = e.target.result;
                    }
                    reader.readAsDataURL(this.files[0]);
                }
            });
        }
    });
</script>
@endpush
@endsection
