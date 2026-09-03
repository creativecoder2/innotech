@extends('admin.layouts.master')

@section('title', 'Admin Profile')
@section('header_title', 'Admin Account & Security')

@section('content')

    <div class="row">
        <div class="col-lg-6">
            <div class="admin-card">
                <div class="admin-card-header">
                    <h5><i class="fa-solid fa-user-shield text-primary mr-10"></i> Profile Credentials</h5>
                </div>
                <div class="admin-card-body">
                    <form action="{{ route('admin.profile.update') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label font-weight-bold">Administrator Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label font-weight-bold">Email Address <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                        </div>

                        <hr class="my-4">

                        <h6 class="font-weight-bold mb-3" style="color: #002244;">Change Password (Optional)</h6>

                        <div class="mb-3">
                            <label class="form-label font-weight-bold">New Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Leave empty to keep current password">
                            <small class="text-muted">Minimum 6 characters</small>
                        </div>

                        <div class="mb-4">
                            <label class="form-label font-weight-bold">Confirm New Password</label>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="Re-type new password">
                        </div>

                        <button type="submit" class="btn-theme py-2 px-4">
                            <i class="fa-solid fa-floppy-disk mr-5"></i> Update Profile & Password
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="admin-card">
                <div class="admin-card-header">
                    <h5>Security & Session Details</h5>
                </div>
                <div class="admin-card-body">
                    <p class="text-muted">Ensure your account credentials are kept secure. You can update your login email and password anytime from this panel.</p>
                    
                    <div class="p-3 bg-light rounded mb-3">
                        <strong>Role:</strong> Super Administrator<br>
                        <strong>Account Status:</strong> <span class="badge bg-success">Active</span><br>
                        <strong>Last Password Update:</strong> {{ $user->updated_at ? $user->updated_at->format('M d, Y h:i A') : 'Default' }}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
