@extends('admin.layouts.master')

@section('title', 'Super Admins & Users')
@section('header_title', 'Administrators & Access')

@section('content')

    <!-- TOP BAR & ACTIONS -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h4 class="mb-1 text-dark fw-bold">Super Admins & Portal Users</h4>
            <p class="text-muted mb-0">Manage system administrators, roles, phone numbers, and login permissions.</p>
        </div>
        <div class="d-flex gap-2">
            <button type="button" class="btn-theme d-inline-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#createUserModal">
                <i class="fa-solid fa-user-plus"></i> Add Administrator
            </button>
        </div>
    </div>

    <!-- ALERTS -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 p-3 mb-4 shadow-sm" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-3 p-3 mb-4 shadow-sm" role="alert">
            <i class="fa-solid fa-triangle-exclamation me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show rounded-3 p-3 mb-4 shadow-sm" role="alert">
            <i class="fa-solid fa-circle-exclamation me-2"></i> <strong>Please resolve the following errors:</strong>
            <ul class="mb-0 mt-1 ps-3">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- FILTER & SEARCH BAR -->
    <div class="admin-card mb-4 p-3">
        <form method="GET" action="{{ route('admin.users.index') }}" class="row g-2 align-items-center">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                    <input type="text" name="search" class="form-control border-start-0" placeholder="Search by name, email, phone, or role..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <select name="role" class="form-select" onchange="this.form.submit()">
                    <option value="">All Roles</option>
                    <option value="Super Admin" {{ request('role') == 'Super Admin' ? 'selected' : '' }}>Super Admin</option>
                    <option value="Admin" {{ request('role') == 'Admin' ? 'selected' : '' }}>Admin</option>
                    <option value="Manager" {{ request('role') == 'Manager' ? 'selected' : '' }}>Manager</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="">All Statuses</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="disabled" {{ request('status') == 'disabled' ? 'selected' : '' }}>Disabled</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100"><i class="fa-solid fa-filter me-1"></i> Filter</button>
                @if(request()->hasAny(['search', 'role', 'status']))
                    <a href="{{ route('admin.users.index') }}" class="btn btn-light" title="Reset Filters"><i class="fa-solid fa-rotate-left"></i></a>
                @endif
            </div>
        </form>
    </div>

    <!-- ADMINS DATA GRID -->
    <div class="admin-card">
        <div class="table-responsive">
            <table class="table table-custom align-middle mb-0">
                <thead>
                    <tr>
                        <th width="60">#</th>
                        <th>Administrator</th>
                        <th>Role</th>
                        <th>Email Address</th>
                        <th>Phone Number</th>
                        <th width="120">Status</th>
                        <th width="140">Created Date</th>
                        <th width="160" class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr class="{{ !$user->is_active ? 'opacity-75 bg-light' : '' }}">
                            <td class="text-muted fw-bold">{{ $loop->iteration }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2.5">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold shadow-sm flex-shrink-0" 
                                         style="width: 38px; height: 38px; font-size: 15px; background: {{ $user->role === 'Super Admin' ? 'linear-gradient(135deg, #002244, #0E63FF)' : 'linear-gradient(135deg, #048C5B, #10b981)' }};">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark d-flex align-items-center gap-1.5">
                                            {{ $user->name }}
                                            @if(Auth::id() === $user->id)
                                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-0.5" style="font-size: 11px;">
                                                    <i class="fa-solid fa-circle-user me-1"></i> You
                                                </span>
                                            @endif
                                        </div>
                                        <small class="text-muted">ID: #{{ $user->id }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($user->role === 'Super Admin')
                                    <span class="badge bg-dark text-white border px-2.5 py-1.5 font-weight-bold">
                                        <i class="fa-solid fa-crown me-1 text-warning"></i> Super Admin
                                    </span>
                                @elseif($user->role === 'Admin')
                                    <span class="badge bg-primary text-white px-2.5 py-1.5">
                                        <i class="fa-solid fa-shield-halved me-1"></i> Admin
                                    </span>
                                @else
                                    <span class="badge bg-secondary text-white px-2.5 py-1.5">
                                        <i class="fa-solid fa-user-gear me-1"></i> {{ $user->role ?: 'Staff' }}
                                    </span>
                                @endif
                            </td>
                            <td>
                                <a href="mailto:{{ $user->email }}" class="text-decoration-none text-dark fw-medium small">
                                    <i class="fa-solid fa-envelope text-muted me-1.5"></i>{{ $user->email }}
                                </a>
                            </td>
                            <td>
                                @if($user->phone)
                                    <a href="tel:{{ $user->phone }}" class="text-decoration-none text-dark fw-medium small">
                                        <i class="fa-solid fa-phone text-success me-1.5"></i>{{ $user->phone }}
                                    </a>
                                @else
                                    <span class="text-muted small fst-italic">Not provided</span>
                                @endif
                            </td>
                            <td>
                                @if($user->is_active)
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1 font-weight-semibold">
                                        <i class="fa-solid fa-circle-check me-1"></i> Active
                                    </span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2.5 py-1 font-weight-semibold">
                                        <i class="fa-solid fa-circle-xmark me-1"></i> Disabled
                                    </span>
                                @endif
                            </td>
                            <td class="text-muted small">
                                {{ $user->created_at ? $user->created_at->format('M d, Y') : 'N/A' }}
                            </td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-1">
                                    <!-- EDIT MODAL TRIGGER -->
                                    <button type="button" 
                                            class="btn btn-sm btn-light border px-2 py-1 text-primary shadow-sm" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editUserModal{{ $user->id }}" 
                                            title="Edit Administrator">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>

                                    <!-- TOGGLE STATUS (DISABLE / ENABLE) -->
                                    @if(Auth::id() === $user->id)
                                        <button type="button" 
                                                class="btn btn-sm btn-light border px-2 py-1 text-muted opacity-50 shadow-sm" 
                                                disabled 
                                                title="You cannot disable your own active account">
                                            <i class="fa-solid fa-ban"></i>
                                        </button>
                                    @else
                                        <form action="{{ route('admin.users.toggle_status', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to {{ $user->is_active ? 'disable' : 'enable' }} {{ $user->name }}?');">
                                            @csrf
                                            <button type="submit" 
                                                    class="btn btn-sm btn-light border px-2 py-1 shadow-sm {{ $user->is_active ? 'text-warning' : 'text-success' }}" 
                                                    title="{{ $user->is_active ? 'Disable Account' : 'Enable Account' }}">
                                                <i class="fa-solid {{ $user->is_active ? 'fa-ban' : 'fa-circle-check' }}"></i>
                                            </button>
                                        </form>
                                    @endif

                                    <!-- DELETE USER -->
                                    @if(Auth::id() === $user->id)
                                        <button type="button" 
                                                class="btn btn-sm btn-light border px-2 py-1 text-muted opacity-50 shadow-sm" 
                                                disabled 
                                                title="You cannot delete your own account">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    @else
                                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to permanently delete administrator {{ $user->name }}? This action cannot be undone.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-light border px-2 py-1 text-danger shadow-sm" title="Delete Administrator">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>

                        <!-- EDIT USER MODAL -->
                        <div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                                    <div class="modal-header border-0 bg-light px-4 py-3">
                                        <h5 class="modal-title fw-bold text-dark">
                                            <i class="fa-solid fa-user-pen text-primary me-2"></i> Edit Administrator: {{ $user->name }}
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body px-4 py-3">
                                            
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                                                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Role <span class="text-danger">*</span></label>
                                                <select name="role" class="form-select" required>
                                                    <option value="Super Admin" {{ old('role', $user->role) === 'Super Admin' ? 'selected' : '' }}>Super Admin (Full Access)</option>
                                                    <option value="Admin" {{ old('role', $user->role) === 'Admin' ? 'selected' : '' }}>Admin (Operational Access)</option>
                                                    <option value="Manager" {{ old('role', $user->role) === 'Manager' ? 'selected' : '' }}>Manager (Catalog & Inquiries)</option>
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
                                                <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                                                <small class="text-muted">Used for login and notifications.</small>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Phone Number <span class="text-muted fw-normal">(Optional)</span></label>
                                                <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}" placeholder="e.g. +92 331 6699992 or 03316699992">
                                                <small class="text-muted">Admins can log in with either this phone number or their email.</small>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">New Password <span class="text-muted fw-normal">(Leave blank to keep current)</span></label>
                                                <input type="password" name="password" class="form-control" placeholder="Enter new password (min. 6 chars)" minlength="6">
                                            </div>

                                            <div class="p-3 bg-light rounded-3 border">
                                                <div class="form-check form-switch mb-0">
                                                    @if(Auth::id() === $user->id)
                                                        <input class="form-check-input" type="checkbox" name="is_active" id="editIsActive{{ $user->id }}" checked disabled>
                                                        <input type="hidden" name="is_active" value="1">
                                                        <label class="form-check-label fw-semibold text-muted" for="editIsActive{{ $user->id }}">
                                                            Account Active <span class="badge bg-primary-subtle text-primary ms-1">Cannot disable your own active session</span>
                                                        </label>
                                                    @else
                                                        <input class="form-check-input" type="checkbox" name="is_active" id="editIsActive{{ $user->id }}" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                                                        <label class="form-check-label fw-semibold text-dark" for="editIsActive{{ $user->id }}">
                                                            Account Active (Allow Login)
                                                        </label>
                                                    @endif
                                                </div>
                                            </div>

                                        </div>
                                        <div class="modal-footer border-0 bg-light px-4 py-3">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary px-4 fw-bold">
                                                <i class="fa-solid fa-floppy-disk me-1"></i> Save Changes
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-users-slash fs-1 text-muted opacity-50 mb-2"></i>
                                <p class="mb-0 fw-semibold">No administrator accounts found.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="p-3 border-top">
                {{ $users->links() }}
            </div>
        @endif
    </div>

    <!-- CREATE USER MODAL -->
    <div class="modal fade" id="createUserModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="modal-header border-0 bg-light px-4 py-3">
                    <h5 class="modal-title fw-bold text-dark">
                        <i class="fa-solid fa-user-plus text-primary me-2"></i> Add New Administrator
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf
                    <div class="modal-body px-4 py-3">
                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="e.g. Dr. Muhammad Ahmed" value="{{ old('name') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Role <span class="text-danger">*</span></label>
                            <select name="role" class="form-select" required>
                                <option value="Super Admin" {{ old('role') === 'Super Admin' ? 'selected' : '' }}>Super Admin (Full Access)</option>
                                <option value="Admin" {{ old('role') === 'Admin' ? 'selected' : '' }}>Admin (Operational Access)</option>
                                <option value="Manager" {{ old('role') === 'Manager' ? 'selected' : '' }}>Manager (Catalog & Inquiries)</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" placeholder="admin@innotechmed.com" value="{{ old('email') }}" required>
                            <small class="text-muted">Must be unique across the portal.</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Phone Number <span class="text-muted fw-normal">(Optional)</span></label>
                            <input type="text" name="phone" class="form-control" placeholder="e.g. +92 331 6699992 or 03316699992" value="{{ old('phone') }}">
                            <small class="text-muted">Optional. Admin can use either this phone or email to log in.</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control" placeholder="At least 6 characters" minlength="6" required>
                        </div>

                        <div class="p-3 bg-light rounded-3 border">
                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input" type="checkbox" name="is_active" id="newIsActive" value="1" checked>
                                <label class="form-check-label fw-semibold text-dark" for="newIsActive">
                                    Active Account (Allow immediate login)
                                </label>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer border-0 bg-light px-4 py-3">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary px-4 fw-bold">
                            <i class="fa-solid fa-user-check me-1"></i> Create Administrator
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
