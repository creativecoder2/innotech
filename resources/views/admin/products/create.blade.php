@extends('admin.layouts.master')

@section('title', 'Add New Product')
@section('header_title', 'Add Product')

@section('content')

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="mb-1 text-dark fw-bold">Add Medical Equipment / Product</h4>
            <p class="text-muted mb-0">Fill in the product details, key features, and upload high-resolution equipment imagery.</p>
        </div>
        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
            <i class="fa-solid fa-arrow-left me-1"></i> Back to Products
        </a>
    </div>

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row g-4">
            
            <!-- LEFT MAIN COLUMN -->
            <div class="col-lg-8">
                <div class="admin-card p-4 mb-4">
                    <h5 class="fw-bold text-dark mb-3">General Information</h5>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Product Title *</label>
                        <input type="text" name="title" class="form-control rounded-3" value="{{ old('title') }}" 
                               placeholder="e.g. Elektro-mag M 308 Infant Radiant Warmer Unit (TFT)" required>
                        @error('title') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Manufacturer / Company</label>
                            <select name="company_id" class="form-select rounded-3">
                                <option value="">-- Select Manufacturer --</option>
                                @foreach($companies as $comp)
                                    <option value="{{ $comp->id }}" {{ old('company_id') == $comp->id ? 'selected' : '' }}>
                                        {{ $comp->name }} {{ $comp->country ? '('.$comp->country.')' : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Model / SKU Code</label>
                            <input type="text" name="sku" class="form-control rounded-3" value="{{ old('sku') }}" placeholder="e.g. EM-M308-TFT">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Short Overview Description</label>
                        <textarea name="short_description" rows="3" class="form-control rounded-3" 
                                  placeholder="Brief summary appearing under manufacturer on product detail page (e.g. The Elektro-mag M 308 Infant Radiant Warmer Unit is a state-of-the-art neonatal care solution from Turkey.)">{{ old('short_description') }}</textarea>
                    </div>

                    <!-- KEY FEATURES (BULLET POINTS) -->
                    <div class="mb-3 p-3 bg-light rounded-3 border">
                        <label class="form-label fw-bold text-dark mb-1">
                            <i class="fa-solid fa-list-check text-primary me-1"></i> Key Features (Bulleted List)
                        </label>
                        <p class="small text-muted mb-2">
                            Enter one key feature per line. Each line will render as a bullet item matching the design mockup.
                        </p>
                        <textarea name="key_features" rows="7" class="form-control rounded-3 font-monospace" style="font-size: 13.5px;"
                                  placeholder="7&quot; Colored TFT Touch Screen Display&#10;PID Digital Temperature Control (±0.1ºC accuracy)&#10;Three Modes: Preheat, Manual, Baby Servo Control&#10;Ceramic Heater Technology&#10;Integrated APGAR Timer and examination light&#10;Built-in Digital Scale&#10;Trendelenburg Positioning">{{ old('key_features') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Full Technical & Clinical Details</label>
                        <textarea name="description" rows="6" class="form-control rounded-3" 
                                  placeholder="Detailed engineering descriptions, clinical specifications, safety mechanisms, etc.">{{ old('description') }}</textarea>
                    </div>

                </div>
            </div>

            <!-- RIGHT SIDEBAR COLUMN -->
            <div class="col-lg-4">
                
                <!-- PUBLISHING & STATUS -->
                <div class="admin-card p-4 mb-4">
                    <h5 class="fw-bold text-dark mb-3">Status & Visibility</h5>

                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="is_active" id="isActive" value="1" checked>
                        <label class="form-check-label fw-semibold" for="isActive">Published on Website</label>
                    </div>

                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="is_featured" id="isFeatured" value="1">
                        <label class="form-check-label fw-semibold" for="isFeatured">Featured in Related & Home</label>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Display Order</label>
                        <input type="number" name="order" class="form-control rounded-3" value="{{ old('order', 0) }}">
                        <small class="text-muted">Lower values appear first in grids.</small>
                    </div>

                    <hr class="my-3">

                    <button type="submit" class="btn btn-primary w-100 py-2.5 fw-bold rounded-3">
                        <i class="fa-solid fa-save me-1"></i> Save Product
                    </button>
                </div>

                <!-- PRODUCT IMAGE UPLOAD -->
                <div class="admin-card p-4">
                    <h5 class="fw-bold text-dark mb-3">Product Image</h5>
                    
                    <div class="image-preview-wrapper mb-3 border rounded-3 bg-white p-3 text-center" style="min-height: 220px; display: flex; align-items: center; justify-content: center;">
                        <img id="imagePreview" src="{{ asset('assets/img/shop/shop-01.jpg') }}" alt="Preview" 
                             style="max-height: 200px; max-width: 100%; object-fit: contain;">
                    </div>

                    <div class="mb-2">
                        <label class="form-label small fw-bold">Select Equipment Photo</label>
                        <input type="file" name="image" id="imageInput" class="form-control rounded-3" accept="image/*">
                        <small class="text-muted">JPG, PNG, WebP up to 5MB. White or transparent background recommended.</small>
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
