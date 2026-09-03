@extends('admin.layouts.master')

@section('title', 'Hero Banners & Sliders')
@section('header_title', 'Hero Banners & Sliders')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <p class="text-muted mb-0">Control the top hero banners, headline copy, and CTA buttons on the homepage.</p>
        <a href="{{ route('admin.sliders.create') }}" class="btn-theme">
            <i class="fa-solid fa-plus"></i> Add New Banner
        </a>
    </div>

    <div class="admin-card">
        <div class="table-responsive">
            <table class="table table-custom">
                <thead>
                    <tr>
                        <th width="60">#</th>
                        <th>Preview</th>
                        <th>Badge & Title</th>
                        <th>Primary Button</th>
                        <th>Secondary Button</th>
                        <th>Status</th>
                        <th>Order</th>
                        <th width="140" class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sliders as $slider)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                @if($slider->image)
                                    <img src="{{ asset($slider->image) }}" alt="Slider" class="rounded" style="width: 80px; height: 50px; object-fit: cover;">
                                @else
                                    <span class="badge bg-light text-muted">No Image</span>
                                @endif
                            </td>
                            <td>
                                <small class="text-primary font-weight-bold d-block">{{ $slider->badge }}</small>
                                <strong style="color: #002244;">{{ $slider->title }}</strong>
                            </td>
                            <td>
                                <span class="badge bg-primary">{{ $slider->btn_text }}</span><br>
                                <small class="text-muted">{{ $slider->btn_link }}</small>
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ $slider->btn_secondary_text }}</span><br>
                                <small class="text-muted">{{ $slider->btn_secondary_link }}</small>
                            </td>
                            <td>
                                @if($slider->is_active)
                                    <span class="badge-status active">Active</span>
                                @else
                                    <span class="badge-status unread">Inactive</span>
                                @endif
                            </td>
                            <td>{{ $slider->order }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.sliders.edit', $slider->id) }}" class="btn btn-sm btn-light text-primary mr-5" title="Edit"><i class="fa-solid fa-pencil"></i></a>
                                <form action="{{ route('admin.sliders.destroy', $slider->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this banner?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-light text-danger" title="Delete"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">No hero banners created yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection
