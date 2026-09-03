@extends('admin.layouts.master')

@section('title', 'Brand Partners')
@section('header_title', 'Brand Partners & Manufacturers')

@section('content')

    <div class="row">
        <!-- Add Partner -->
        <div class="col-lg-4 mb-4">
            <div class="admin-card">
                <div class="admin-card-header">
                    <h5><i class="fa-solid fa-plus text-primary mr-10"></i> Add Brand Partner</h5>
                </div>
                <div class="admin-card-body">
                    <form action="{{ route('admin.partners.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label font-weight-bold">Brand / Company Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="e.g. Philips Healthcare" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label font-weight-bold">Website URL (Optional)</label>
                            <input type="url" name="url" class="form-control" placeholder="https://...">
                        </div>

                        <div class="mb-3">
                            <label class="form-label font-weight-bold">Display Order</label>
                            <input type="number" name="order" class="form-control" value="0">
                        </div>

                        <div class="mb-4">
                            <label class="form-label font-weight-bold">Brand Logo Image <span class="text-danger">*</span></label>
                            <input type="file" name="logo" class="form-control" accept="image/*,.jfif,.png,.jpg,.jpeg,.webp,.svg,.gif,.avif,.bmp" required>
                            <small class="text-muted">PNG with transparent background recommended</small>
                        </div>

                        <button type="submit" class="btn-theme w-100 py-2">
                            <i class="fa-solid fa-plus mr-5"></i> Upload Brand Logo
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- List Partners -->
        <div class="col-lg-8">
            <div class="admin-card">
                <div class="admin-card-header">
                    <h5>Active Partner Logos</h5>
                </div>
                <div class="admin-card-body">
                    <div class="row g-3">
                        @forelse($partners as $partner)
                            <div class="col-md-4 col-sm-6 text-center">
                                <div class="p-3 border rounded h-100 d-flex flex-column justify-content-between align-items-center bg-light">
                                    <div class="my-auto py-3">
                                        <img src="{{ asset($partner->logo) }}" alt="{{ $partner->name }}" style="max-height: 50px; max-width: 100%; object-fit: contain;">
                                    </div>
                                    <div class="w-100 d-flex justify-content-between align-items-center pt-2 border-top">
                                        <span class="small font-weight-bold text-dark">{{ $partner->name }}</span>
                                        <form action="{{ route('admin.partners.destroy', $partner->id) }}" method="POST" onsubmit="return confirm('Delete this brand partner?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-link text-danger p-0"><i class="fa-solid fa-trash"></i></button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center py-4 text-muted">No partners added yet.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
