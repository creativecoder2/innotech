@extends('admin.layouts.master')

@section('title', 'Medical Products Catalog')
@section('header_title', 'Products Management')

@section('content')

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h4 class="mb-1 text-dark fw-bold">Medical Products Catalog</h4>
            <p class="text-muted mb-0">Manage hospital ICU systems, surgical devices, and clinical equipment displayed across catalog and company filters.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('products') }}" target="_blank" class="btn btn-outline-secondary">
               <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> View Live Products
            </a>
            <a href="{{ route('admin.products.create') }}" class="btn-theme">
                <i class="fa-solid fa-plus me-1"></i> Add New Product
            </a>
        </div>
    </div>

    <!-- FILTER BAR -->
    <div class="admin-card mb-4">
        <div class="admin-card-body p-3">
            <form action="{{ route('admin.products.index') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-magnifying-glass"></i></span>
                        <input type="text" name="search" class="form-control border-start-0" placeholder="Search by title, SKU, model or description..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <select name="company_id" class="form-select">
                        <option value="">All Manufacturers / Brands</option>
                        @foreach($companies as $comp)
                            <option value="{{ $comp->id }}" {{ request('company_id') == $comp->id ? 'selected' : '' }}>
                                {{ $comp->name }} {{ $comp->country ? '('.$comp->country.')' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-grow-1"><i class="fa-solid fa-filter me-1"></i> Filter</button>
                    @if(request()->hasAny(['search', 'company_id']))
                        <a href="{{ route('admin.products.index') }}" class="btn btn-light" title="Clear Filters"><i class="fa-solid fa-rotate-left"></i></a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- PRODUCTS TABLE -->
    <div class="admin-card">
        <div class="table-responsive">
            <table class="table table-custom align-middle mb-0">
                <thead>
                    <tr>
                        <th width="40">#</th>
                        <th width="80">Image</th>
                        <th>Product Title & Model</th>
                        <th>Manufacturer</th>
                        <th width="120">Features</th>
                        <th width="100">Featured</th>
                        <th width="70">Order</th>
                        <th width="110">Status</th>
                        <th width="130" class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td class="text-muted fw-bold">{{ $loop->iteration }}</td>
                            <td>
                                <div class="p-1 bg-white rounded border d-flex align-items-center justify-content-center" style="width: 58px; height: 50px;">
                                    <img src="{{ asset($product->image ?: 'assets/img/shop/shop-01.jpg') }}" alt="{{ $product->title }}" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                                </div>
                            </td>
                            <td>
                                <div class="fw-bold text-dark mb-0">{{ $product->title }}</div>
                                <div class="small text-muted">
                                    @if($product->sku)
                                        <span class="badge bg-light text-dark border me-1">SKU: {{ $product->sku }}</span>
                                    @endif
                                    <span class="text-secondary">slug: {{ $product->slug }}</span>
                                </div>
                            </td>
                            <td>
                                @if($product->company)
                                    <span class="badge bg-light text-primary border px-2 py-1 fw-semibold">
                                        <i class="fa-solid fa-industry me-1"></i> {{ $product->company->name }}
                                        @if($product->company->country)
                                            <span class="text-muted fw-normal">({{ $product->company->country }})</span>
                                        @endif
                                    </span>
                                @else
                                    <span class="text-muted small">Unassigned</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-info-subtle text-info border border-info-subtle px-2 py-1">
                                    {{ count($product->features_list) }} bullets
                                </span>
                            </td>
                            <td>
                                @if($product->is_featured)
                                    <span class="badge px-2.5 py-1.5 fw-bold rounded-pill" style="background-color: #fef3c7; color: #92400e; border: 1px solid #fcd34d; font-size: 11px;">
                                        <i class="fa-solid fa-star me-1" style="color: #d97706;"></i> Featured
                                    </span>
                                @else
                                    <span class="badge bg-light text-secondary border px-2 py-1 fw-normal" style="font-size: 11px;">
                                        Standard
                                    </span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-light text-secondary border">{{ $product->order }}</span>
                            </td>
                            <td>
                                <div class="form-check form-switch">
                                    <input class="form-check-input toggle-product-status" type="checkbox" role="switch"
                                           data-id="{{ $product->id }}"
                                           data-url="{{ route('admin.products.toggle', $product->id) }}"
                                           {{ $product->is_active ? 'checked' : '' }}>
                                    <label class="form-check-label small ms-1 {{ $product->is_active ? 'text-success fw-bold' : 'text-muted' }}">
                                        {{ $product->is_active ? 'Active' : 'Inactive' }}
                                    </label>
                                </div>
                            </td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-1">
                                    <a href="{{ route('product.detail', $product->slug) }}" target="_blank" class="btn btn-sm btn-light" title="View Detail Page">
                                        <i class="fa-solid fa-eye text-primary"></i>
                                    </a>
                                    <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-light" title="Edit Product">
                                        <i class="fa-solid fa-pen text-secondary"></i>
                                    </a>
                                    <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this product?');" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-light" title="Delete">
                                            <i class="fa-solid fa-trash text-danger"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-box-open fa-2x mb-2 opacity-50"></i>
                                <p class="mb-0">No products found matching criteria.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($products->hasPages())
            <div class="p-3 border-top d-flex justify-content-end">
                {{ $products->links() }}
            </div>
        @endif
    </div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggleInputs = document.querySelectorAll('.toggle-product-status');
        toggleInputs.forEach(input => {
            input.addEventListener('change', function () {
                const url = this.dataset.url;
                const label = this.nextElementSibling;
                fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        label.textContent = data.is_active ? 'Active' : 'Inactive';
                        label.className = 'form-check-label small ms-1 ' + (data.is_active ? 'text-success fw-bold' : 'text-muted');
                    }
                })
                .catch(() => {
                    alert('Error updating product status.');
                    this.checked = !this.checked;
                });
            });
        });
    });
</script>
@endpush
@endsection
