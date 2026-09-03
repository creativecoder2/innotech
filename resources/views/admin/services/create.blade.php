@extends('admin.layouts.master')

@section('title', 'Add New Medical Service')
@section('header_title', 'Add New Medical Service & Detail Page')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <a href="{{ route('admin.home_sections.index') }}" class="btn btn-outline-secondary">
                <i class="fa-solid fa-arrow-left mr-5"></i> Back to Home Page Manager
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <h6 class="alert-heading font-weight-bold mb-1"><i class="fa-solid fa-triangle-exclamation me-2"></i> Please fix the following errors:</h6>
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form action="{{ route('admin.services.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- SECTION 1: BASIC SERVICE INFO -->
        <div class="admin-card mb-4">
            <div class="admin-card-header d-flex align-items-center justify-content-between">
                <h5 class="mb-0 text-primary"><i class="fa-solid fa-stethoscope me-2"></i> 1. Basic Service Information</h5>
                <span class="badge bg-secondary-subtle text-secondary px-3 py-1">New Service</span>
            </div>
            <div class="admin-card-body">
                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label font-weight-bold">Service / Equipment Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control form-control-lg" placeholder="e.g. Critical Care ICU Ventilators" value="{{ old('title') }}" required>
                        <small class="text-muted">Displays across the website cards, listings, menus and main headings.</small>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label font-weight-bold">Category <span class="text-danger">*</span></label>
                        <input type="text" name="category" class="form-control form-control-lg" placeholder="e.g. ICU & Critical Care, Diagnostics, Surgical" value="{{ old('category', 'Medical') }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label font-weight-bold">Medical Icon (Flaticon / FontAwesome)</label>
                        <input type="text" name="icon" list="flaticon_list" class="form-control" placeholder="Select or type icon..." value="{{ old('icon', 'flaticon-hemoglobin-test-meter') }}">
                        <datalist id="flaticon_list">
                            <option value="flaticon-hemoglobin-test-meter">Hemoglobin Meter / ICU</option>
                            <option value="flaticon-blood-test">Blood Test / Diagnostics</option>
                            <option value="flaticon-biochemistry">Biochemistry / Flask</option>
                            <option value="flaticon-ct-scan">CT Scan / Radiology</option>
                            <option value="flaticon-dna">DNA Genetics / Lab</option>
                            <option value="flaticon-microscope">Microscope / Research</option>
                            <option value="flaticon-experiment">Experiment / Chemistry</option>
                            <option value="flaticon-bacteria">Bacteria / Pathology</option>
                            <option value="flaticon-heart">Heart / Cardiology</option>
                            <option value="flaticon-research">Research / Clinical</option>
                            <option value="fa-solid fa-stethoscope">FontAwesome Stethoscope</option>
                            <option value="fa-solid fa-hospital">FontAwesome Hospital</option>
                            <option value="fa-solid fa-truck-medical">FontAwesome Ambulance / Delivery</option>
                        </datalist>
                        <small class="text-muted">Choose from suggested medical icons or enter any FontAwesome class.</small>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label font-weight-bold">Display Order</label>
                        <input type="number" name="order" class="form-control" value="{{ old('order', 0) }}">
                    </div>

                    <div class="col-md-2 d-flex align-items-center pt-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="is_featured" {{ old('is_featured', '1') ? 'checked' : '' }}>
                            <label class="form-check-label font-weight-bold" for="is_featured">
                                Show on Home
                            </label>
                        </div>
                    </div>

                    <div class="col-md-2 d-flex align-items-center pt-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" {{ old('is_active', '1') ? 'checked' : '' }}>
                            <label class="form-check-label font-weight-bold text-success" for="is_active">
                                Active Status
                            </label>
                        </div>
                    </div>

                    <div class="col-12">
                        <label class="form-label font-weight-bold">Short Excerpt / Card Summary <span class="text-danger">*</span></label>
                        <textarea name="short_description" class="form-control" rows="3" placeholder="Brief 1-2 sentence overview for cards and summary header..." required>{{ old('short_description') }}</textarea>
                        <small class="text-muted">Displayed on home service carousels and top summary paragraph of the detail page.</small>
                    </div>

                    <div class="col-12">
                        <label class="form-label font-weight-bold">Full Detailed Description</label>
                        <textarea name="description" class="form-control" rows="5" placeholder="Comprehensive specifications, clinical use cases, biomedical certifications...">{{ old('description', 'At Innotech Medical, we are dedicated to bridging the gap between world-class medical innovation and Pakistan’s healthcare sector. As a leading provider of advanced biomedical technologies, diagnostic systems, and specialized clinical equipment, we empower healthcare institutions to deliver accurate diagnoses and superior patient care.') }}</textarea>
                        <small class="text-muted">Detailed technical narrative, capabilities, and clinical applications.</small>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label font-weight-bold">Primary Equipment Image (Thumbnail 1)</label>
                        <input type="file" name="image" class="form-control" accept="image/*,.jfif,.png,.jpg,.jpeg,.webp,.svg,.gif,.avif,.bmp">
                        <small class="text-muted">Featured card image and left-side image on detail page.</small>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label font-weight-bold">Secondary Showcase Image (Thumbnail 2)</label>
                        <input type="file" name="image_2" class="form-control" accept="image/*,.jfif,.png,.jpg,.jpeg,.webp,.svg,.gif,.avif,.bmp">
                        <small class="text-muted">Right-side secondary image shown alongside primary image on the detail page.</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- SECTION 2: DETAIL PAGE OVERVIEW & CHECKLIST -->
        <div class="admin-card mb-4">
            <div class="admin-card-header">
                <h5 class="mb-0 text-primary"><i class="fa-solid fa-list-check me-2"></i> 2. Detail Page Overview & Specifications Checklist</h5>
            </div>
            <div class="admin-card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label font-weight-bold">Overview Section Title</label>
                        <input type="text" name="process_title" class="form-control" value="{{ old('process_title') }}" placeholder="e.g. Clinical Overview & Specifications">
                        <small class="text-muted">Heading displayed right below the two top images.</small>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label font-weight-bold">Breadcrumb Subtitle</label>
                        <input type="text" name="banner_subtitle" class="form-control" value="{{ old('banner_subtitle') }}" placeholder="e.g. Advanced Biomedical Diagnostic Systems">
                        <small class="text-muted">Optional header subtitle displayed on the top banner.</small>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label font-weight-bold">Key Specifications & Features Checklist</label>
                        <textarea name="features" class="form-control" rows="5" placeholder="Enter each key bullet point on a new line...">{{ old('features', "Certified OEM medical equipment with official manufacturer warranty\nOn-site calibration, turnkey clinical integration, and staff operation training\n24/7 biomedical emergency hotline and guaranteed genuine spare parts\nFull compliance with international CE, FDA, and ISO healthcare safety benchmarks") }}</textarea>
                        <small class="text-muted"><i class="fa-solid fa-info-circle text-primary"></i> <strong>Tip:</strong> Put each feature on a <strong>new line</strong>. Each line will automatically become a bullet item with a green medical checkmark icon.</small>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label font-weight-bold">Custom Header Banner Background Image</label>
                        <input type="file" name="banner_image" class="form-control" accept="image/*,.jfif,.png,.jpg,.jpeg,.webp,.svg,.gif,.avif,.bmp">
                        <small class="text-muted">Optional: Upload custom top breadcrumb banner image (Leave empty for default).</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- SECTION 3: 4-STEP PROCESS / WORKFLOW -->
        <div class="admin-card mb-4">
            <div class="admin-card-header">
                <h5 class="mb-0 text-primary"><i class="fa-solid fa-shoe-prints me-2"></i> 3. Step-by-Step Implementation & Workflow (4 Steps)</h5>
            </div>
            <div class="admin-card-body">
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label font-weight-bold">Steps Section Heading</label>
                        <input type="text" name="steps_title" class="form-control" value="{{ old('steps_title', '4 Simple Steps to Implementation') }}">
                    </div>
                    <div class="col-md-12">
                        <label class="form-label font-weight-bold">Steps Section Description</label>
                        <textarea name="steps_description" class="form-control" rows="3">{{ old('steps_description', 'Streamlined procurement, rapid delivery, and certified biomedical installation, delivering complete healthcare projects with minimal turnaround time and total precision.') }}</textarea>
                    </div>
                </div>

                <div class="row g-3">
                    <!-- Step 1 -->
                    <div class="col-lg-3 col-md-6">
                        <div class="p-3 border rounded bg-light h-100">
                            <label class="form-label font-weight-bold text-primary"><i class="fa-solid fa-1 me-1"></i> Step 1 Title</label>
                            <input type="text" name="step_1_title" class="form-control mb-2" value="{{ old('step_1_title', 'Step 01') }}">
                            <label class="form-label small font-weight-bold">Bullet Points (1 per line)</label>
                            <textarea name="step_1_points" class="form-control" rows="4">{{ old('step_1_points', "Consultation and Needs Assessment\nClinical Setup Planning") }}</textarea>
                        </div>
                    </div>

                    <!-- Step 2 -->
                    <div class="col-lg-3 col-md-6">
                        <div class="p-3 border rounded bg-light h-100">
                            <label class="form-label font-weight-bold text-primary"><i class="fa-solid fa-2 me-1"></i> Step 2 Title</label>
                            <input type="text" name="step_2_title" class="form-control mb-2" value="{{ old('step_2_title', 'Step 02') }}">
                            <label class="form-label small font-weight-bold">Bullet Points (1 per line)</label>
                            <textarea name="step_2_points" class="form-control" rows="4">{{ old('step_2_points', "Turnkey Delivery and Placement\nBiomedical Precision Calibration") }}</textarea>
                        </div>
                    </div>

                    <!-- Step 3 -->
                    <div class="col-lg-3 col-md-6">
                        <div class="p-3 border rounded bg-light h-100">
                            <label class="form-label font-weight-bold text-primary"><i class="fa-solid fa-3 me-1"></i> Step 3 Title</label>
                            <input type="text" name="step_3_title" class="form-control mb-2" value="{{ old('step_3_title', 'Step 03') }}">
                            <label class="form-label small font-weight-bold">Bullet Points (1 per line)</label>
                            <textarea name="step_3_points" class="form-control" rows="4">{{ old('step_3_points', "Staff Clinical Operations Training\nPreventative Maintenance Contracts") }}</textarea>
                        </div>
                    </div>

                    <!-- Step 4 -->
                    <div class="col-lg-3 col-md-6">
                        <div class="p-3 border rounded bg-light h-100">
                            <label class="form-label font-weight-bold text-primary"><i class="fa-solid fa-4 me-1"></i> Step 4 Title</label>
                            <input type="text" name="step_4_title" class="form-control mb-2" value="{{ old('step_4_title', 'Step 04') }}">
                            <label class="form-label small font-weight-bold">Bullet Points (1 per line)</label>
                            <textarea name="step_4_points" class="form-control" rows="4">{{ old('step_4_points', "24/7 Technical Support\nEmergency Spare Parts Dispatch") }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SECTION 4: RESEARCH & PROJECT SHOWCASE -->
        <div class="admin-card mb-4">
            <div class="admin-card-header">
                <h5 class="mb-0 text-primary"><i class="fa-solid fa-flask-vial me-2"></i> 4. Research, Clinical Verification & Bottom Action</h5>
            </div>
            <div class="admin-card-body">
                <div class="row g-3">
                    <div class="col-md-12">
                        <label class="form-label font-weight-bold">Research / Verification Heading</label>
                        <input type="text" name="research_title" class="form-control" value="{{ old('research_title', 'Our Research and Clinical Verification') }}">
                    </div>

                    <div class="col-12">
                        <label class="form-label font-weight-bold">Research Description</label>
                        <textarea name="research_description" class="form-control" rows="3">{{ old('research_description', 'Every piece of medical equipment and clinical service provided by Innotech undergoes rigorous testing and biomedical calibration to guarantee peak diagnostic accuracy and patient safety.') }}</textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label font-weight-bold">Showcase Wide Banner Image</label>
                        <input type="file" name="research_image" class="form-control" accept="image/*,.jfif,.png,.jpg,.jpeg,.webp,.svg,.gif,.avif,.bmp">
                        <small class="text-muted">Wide landscape image displayed across the bottom of the service page.</small>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label font-weight-bold">Bottom CTA Button Text</label>
                        <input type="text" name="bottom_link_text" class="form-control" value="{{ old('bottom_link_text', 'Inquire About This Service') }}">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label font-weight-bold">Bottom CTA Link URL</label>
                        <input type="text" name="bottom_link_url" class="form-control" value="{{ old('bottom_link_url', '/contact') }}">
                    </div>
                </div>
            </div>
        </div>

        <!-- SAVE BUTTON BAR -->
        <div class="d-flex justify-content-between align-items-center p-3 bg-white border rounded shadow-sm sticky-bottom mb-5">
            <div>
                <a href="{{ route('admin.home_sections.index') }}" class="btn btn-outline-secondary px-4 py-2">Cancel</a>
            </div>
            <div>
                <button type="submit" class="btn btn-theme px-5 py-2 fs-6 shadow">
                    <i class="fa-solid fa-floppy-disk mr-5"></i> Save & Publish Medical Service
                </button>
            </div>
        </div>
    </form>

@endsection
