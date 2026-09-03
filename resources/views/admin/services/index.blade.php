@extends('admin.layouts.master')

@section('title', 'Medical Services & Products')
@section('header_title', 'Manage Medical Products & Services')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <p class="text-muted mb-0">Manage all medical devices, diagnostic categories, and hospital services displayed on the website.</p>
        <a href="{{ route('admin.services.create') }}" class="btn-theme">
            <i class="fa-solid fa-plus"></i> Add New Service
        </a>
    </div>

    <div class="admin-card">
        <div class="table-responsive">
            <table class="table table-custom">
                <thead>
                    <tr>
                        <th width="60">#</th>
                        <th>Icon / Image</th>
                        <th>Title & Slug</th>
                        <th>Category</th>
                        <th>Featured</th>
                        <th>Order</th>
                        <th width="140" class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($services as $service)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                @if($service->image)
                                    <img src="{{ asset($service->image) }}" alt="{{ $service->title }}" class="rounded" style="width: 48px; height: 48px; object-fit: cover;">
                                @else
                                    <div class="rounded d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: #EBF2FE; color: #0E63FF; font-size: 20px;">
                                        <i class="{{ $service->icon ?: 'flaticon-stethoscope' }}"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <strong style="color: #002244;">{{ $service->title }}</strong><br>
                                <small class="text-muted">Slug: <code>{{ $service->slug }}</code></small>
                            </td>
                            <td><span class="badge bg-light text-dark">{{ $service->category }}</span></td>
                            <td>
                                @if($service->is_featured)
                                    <span class="badge-status active">Featured</span>
                                @else
                                    <span class="badge-status read">Standard</span>
                                @endif
                            </td>
                            <td>{{ $service->order }}</td>
                            <td class="text-end">
                                <a href="{{ url('/services/' . $service->slug) }}" target="_blank" class="btn btn-sm btn-light text-success mr-5" title="View on Website"><i class="fa-solid fa-arrow-up-right-from-square"></i></a>
                                <a href="{{ route('admin.services.edit', $service->id) }}" class="btn btn-sm btn-light text-primary mr-5" title="Edit Service & Details"><i class="fa-solid fa-pencil"></i></a>
                                <form action="{{ route('admin.services.destroy', $service->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this service?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-light text-danger" title="Delete"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">No medical services found. Click "Add New Service" to create one.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($services->hasPages())
            <div class="p-3 border-top">
                {{ $services->links() }}
            </div>
        @endif
    </div>

@endsection
