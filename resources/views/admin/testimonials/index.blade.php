@extends('admin.layouts.master')

@section('title', 'Client Testimonials')
@section('header_title', 'Client & Doctor Testimonials')

@section('content')

    <div class="row">
        <!-- Add Testimonial -->
        <div class="col-lg-4 mb-4">
            <div class="admin-card">
                <div class="admin-card-header">
                    <h5><i class="fa-solid fa-plus text-primary mr-10"></i> Add Testimonial</h5>
                </div>
                <div class="admin-card-body">
                    <form action="{{ route('admin.testimonials.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label font-weight-bold">Doctor / Client Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="e.g. Dr. Farhan Siddiqui" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label font-weight-bold">Designation / Role</label>
                            <input type="text" name="designation" class="form-control" placeholder="e.g. Head of Cardiology">
                        </div>

                        <div class="mb-3">
                            <label class="form-label font-weight-bold">Hospital / Institution</label>
                            <input type="text" name="hospital" class="form-control" placeholder="e.g. National Medical Complex">
                        </div>

                        <div class="mb-3">
                            <label class="form-label font-weight-bold">Avatar / Photo Upload</label>
                            <input type="file" name="avatar" class="form-control" accept="image/*">
                            <small class="text-muted">Leave empty to use default avatar.</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label font-weight-bold">Rating (Stars)</label>
                            <select name="rating" class="form-select">
                                <option value="5" selected>5 Stars (★★★★★)</option>
                                <option value="4">4 Stars (★★★★☆)</option>
                                <option value="3">3 Stars (★★★☆☆)</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label font-weight-bold">Testimonial Content <span class="text-danger">*</span></label>
                            <textarea name="content" class="form-control" rows="4" placeholder="Client review / feedback..." required></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label font-weight-bold">Display Order</label>
                            <input type="number" name="order" class="form-control" value="0">
                        </div>

                        <button type="submit" class="btn-theme w-100 py-2">
                            <i class="fa-solid fa-plus mr-5"></i> Add Testimonial
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- List Testimonials -->
        <div class="col-lg-8">
            <div class="admin-card">
                <div class="admin-card-header">
                    <h5>Existing Feedback & Reviews</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-custom align-middle">
                        <thead>
                            <tr>
                                <th width="60">Avatar</th>
                                <th>Name & Hospital</th>
                                <th>Feedback</th>
                                <th>Rating</th>
                                <th width="100" class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($testimonials as $testi)
                                <tr>
                                    <td>
                                        <img src="{{ asset($testi->avatar ?: 'assets/img/icon/testi-ava-01.jpg') }}" alt="Avatar" class="rounded-circle" style="width: 44px; height: 44px; object-fit: cover; border: 2px solid #ECEEF3;">
                                    </td>
                                    <td>
                                        <strong style="color: #002244;">{{ $testi->name }}</strong><br>
                                        <small class="text-muted">{{ $testi->designation }} - {{ $testi->hospital }}</small>
                                    </td>
                                    <td>
                                        <p class="mb-0 small text-muted">"{{ Str::limit($testi->content, 120) }}"</p>
                                    </td>
                                    <td>
                                        <span class="text-warning">
                                            @for($i=1; $i <= $testi->rating; $i++) ★ @endfor
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <form action="{{ route('admin.testimonials.destroy', $testi->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this testimonial?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-light text-danger"><i class="fa-solid fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">No testimonials added yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection
