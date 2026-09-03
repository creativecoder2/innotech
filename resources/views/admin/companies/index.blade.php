@extends('admin.layouts.master')

@section('title', 'Companies & Manufacturers')
@section('header_title', 'Companies & Brands')

@section('content')

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h4 class="mb-1 text-dark fw-bold">Equipment Manufacturers & Brand Partners</h4>
            <p class="text-muted mb-0">Manage global medical equipment manufacturers shown in the header navigation and products catalog.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('products') }}" target="_blank" class="btn btn-outline-secondary">
               <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> View Live Products
            </a>
            <button type="button" class="btn-theme" data-bs-toggle="modal" data-bs-target="#createCompanyModal">
                <i class="fa-solid fa-plus me-1"></i> Add Company
            </button>
        </div>
    </div>

    <!-- COMPANIES TABLE -->
    <div class="admin-card">
        <div class="table-responsive">
            <table class="table table-custom align-middle mb-0">
                <thead>
                    <tr>
                        <th width="50">#</th>
                        <th>Company Name</th>
                        <th>Country</th>
                        <th width="120">Products</th>
                        <th width="80">Order</th>
                        <th width="120">Status</th>
                        <th width="140" class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($companies as $company)
                        <tr>
                            <td class="text-muted fw-bold">{{ $loop->iteration }}</td>
                            <td>
                                <div class="fw-bold text-dark">{{ $company->name }}</div>
                                <small class="text-muted">Slug: {{ $company->slug }}</small>
                                @if($company->website)
                                    <div><a href="{{ $company->website }}" target="_blank" class="small text-primary text-decoration-none"><i class="fa-solid fa-link me-1"></i>{{ Str::limit($company->website, 30) }}</a></div>
                                @endif
                            </td>
                            <td>
                                @if($company->country)
                                    <span class="badge bg-light text-dark border px-2 py-1"><i class="fa-solid fa-location-dot me-1 text-danger"></i> {{ $company->country }}</span>
                                @else
                                    <span class="text-muted small">Global</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.products.index', ['company_id' => $company->id]) }}" class="badge bg-primary-subtle text-primary border border-primary-subtle px-2.5 py-1.5 text-decoration-none">
                                    <i class="fa-solid fa-boxes-stacked me-1"></i> {{ $company->products_count }} items
                                </a>
                            </td>
                            <td>
                                <span class="badge bg-light text-secondary border">{{ $company->order }}</span>
                            </td>
                            <td>
                                <div class="form-check form-switch">
                                    <input class="form-check-input toggle-company-status" type="checkbox" role="switch"
                                           data-id="{{ $company->id }}"
                                           data-url="{{ route('admin.companies.toggle', $company->id) }}"
                                           {{ $company->is_active ? 'checked' : '' }}>
                                    <label class="form-check-label small ms-1 {{ $company->is_active ? 'text-success fw-bold' : 'text-muted' }}">
                                        {{ $company->is_active ? 'Active' : 'Inactive' }}
                                    </label>
                                </div>
                            </td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-1">
                                    <a href="{{ route('products', ['company' => $company->slug]) }}" target="_blank" class="btn btn-sm btn-light" title="View on Site">
                                        <i class="fa-solid fa-eye text-primary"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-light edit-company-btn" 
                                            data-id="{{ $company->id }}"
                                            data-name="{{ $company->name }}"
                                            data-country="{{ $company->country }}"
                                            data-website="{{ $company->website }}"
                                            data-description="{{ $company->description }}"
                                            data-order="{{ $company->order }}"
                                            data-active="{{ $company->is_active ? '1' : '0' }}"
                                            data-action="{{ route('admin.companies.update', $company->id) }}"
                                            title="Edit Company">
                                        <i class="fa-solid fa-pen text-secondary"></i>
                                    </button>
                                    <form action="{{ route('admin.companies.destroy', $company->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this company? Associated products will have their company unassigned.');" class="d-inline">
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
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-industry fa-2x mb-2 opacity-50"></i>
                                <p class="mb-0">No companies or manufacturers found.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($companies->hasPages())
            <div class="p-3 border-top d-flex justify-content-end">
                {{ $companies->links() }}
            </div>
        @endif
    </div>

    <!-- CREATE COMPANY MODAL -->
    <div class="modal fade" id="createCompanyModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="modal-header border-0 bg-light px-4 py-3">
                    <h5 class="modal-title fw-bold text-dark"><i class="fa-solid fa-plus-circle text-primary me-2"></i> Add Manufacturer / Brand</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.companies.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Company / Brand Name *</label>
                            <input type="text" name="name" class="form-control rounded-3" placeholder="e.g. Elektro-mag, BNG Medical" required>
                        </div>
                        <div class="row g-2 mb-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Country / Origin</label>
                                <input type="text" name="country" class="form-control rounded-3" placeholder="e.g. Turkey, Germany">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Display Order</label>
                                <input type="number" name="order" class="form-control rounded-3" value="0">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Official Website URL</label>
                            <input type="url" name="website" class="form-control rounded-3" placeholder="https://www.example.com">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Description / Overview</label>
                            <textarea name="description" rows="3" class="form-control rounded-3" placeholder="Brief background of manufacturer..."></textarea>
                        </div>
                        <div class="form-check form-switch mt-2">
                            <input class="form-check-input" type="checkbox" name="is_active" id="createIsActive" value="1" checked>
                            <label class="form-check-label fw-semibold" for="createIsActive">Active (Visible in Header Dropdown & Catalog)</label>
                        </div>
                    </div>
                    <div class="modal-footer border-0 px-4 pb-4 pt-0">
                        <button type="button" class="btn btn-light rounded-3 px-3" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary rounded-3 px-4 fw-bold">Save Company</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- EDIT COMPANY MODAL -->
    <div class="modal fade" id="editCompanyModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="modal-header border-0 bg-light px-4 py-3">
                    <h5 class="modal-title fw-bold text-dark"><i class="fa-solid fa-pen-to-square text-primary me-2"></i> Edit Manufacturer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editCompanyForm" action="" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Company / Brand Name *</label>
                            <input type="text" name="name" id="editName" class="form-control rounded-3" required>
                        </div>
                        <div class="row g-2 mb-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Country / Origin</label>
                                <input type="text" name="country" id="editCountry" class="form-control rounded-3">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Display Order</label>
                                <input type="number" name="order" id="editOrder" class="form-control rounded-3">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Official Website URL</label>
                            <input type="url" name="website" id="editWebsite" class="form-control rounded-3">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Description / Overview</label>
                            <textarea name="description" id="editDescription" rows="3" class="form-control rounded-3"></textarea>
                        </div>
                        <div class="form-check form-switch mt-2">
                            <input class="form-check-input" type="checkbox" name="is_active" id="editIsActive" value="1">
                            <label class="form-check-label fw-semibold" for="editIsActive">Active (Visible in Header Dropdown & Catalog)</label>
                        </div>
                    </div>
                    <div class="modal-footer border-0 px-4 pb-4 pt-0">
                        <button type="button" class="btn btn-light rounded-3 px-3" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary rounded-3 px-4 fw-bold">Update Company</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Edit button click -> populate modal
        const editButtons = document.querySelectorAll('.edit-company-btn');
        const editModal = new bootstrap.Modal(document.getElementById('editCompanyModal'));
        const editForm = document.getElementById('editCompanyForm');
        const editName = document.getElementById('editName');
        const editCountry = document.getElementById('editCountry');
        const editWebsite = document.getElementById('editWebsite');
        const editDescription = document.getElementById('editDescription');
        const editOrder = document.getElementById('editOrder');
        const editIsActive = document.getElementById('editIsActive');

        editButtons.forEach(btn => {
            btn.addEventListener('click', function () {
                editForm.action = this.dataset.action;
                editName.value = this.dataset.name || '';
                editCountry.value = this.dataset.country || '';
                editWebsite.value = this.dataset.website || '';
                editDescription.value = this.dataset.description || '';
                editOrder.value = this.dataset.order || 0;
                editIsActive.checked = (this.dataset.active === '1');
                editModal.show();
            });
        });

        // AJAX Status Toggle
        const toggleInputs = document.querySelectorAll('.toggle-company-status');
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
                    alert('Error updating company status.');
                    this.checked = !this.checked;
                });
            });
        });
    });
</script>
@endpush
@endsection
