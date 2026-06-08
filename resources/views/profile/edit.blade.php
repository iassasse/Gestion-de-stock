@extends('layouts.app')

@section('content')
<div class="mb-4">
    <h4 class="fw-bold mb-1">My Profile</h4>
    <p class="text-muted mb-0">Update your account settings, profile picture, and login credentials</p>
</div>

<div class="row g-4">
    <!-- Profile Info Card -->
    <div class="col-12 col-lg-6">
        <div class="card premium-card p-4 h-100">
            <h5 class="fw-bold mb-4"><i class="bi bi-person-lines-fill text-primary me-2"></i>Profile Details</h5>

            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                @csrf

                <!-- Current Avatar Preview & Upload -->
                <div class="d-flex align-items-center gap-3 mb-4">
                    @if($user->profile_picture)
                        <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="Avatar" class="rounded-circle border border-2 shadow-sm" style="width: 80px; height: 80px; object-fit: cover;">
                    @else
                        <div class="bg-light text-secondary rounded-circle d-flex align-items-center justify-content-center border border-2 shadow-sm" style="width: 80px; height: 80px;">
                            <i class="bi bi-person-fill fs-1"></i>
                        </div>
                    @endif
                    <div>
                        <label for="profile_picture" class="form-label fw-semibold text-dark mb-1">Profile Picture (Optional)</label>
                        <input class="form-control form-control-sm @error('profile_picture') is-invalid @enderror" type="file" id="profile_picture" name="profile_picture">
                        <small class="text-muted">Accepts JPEG, PNG, JPG, GIF. Max: 2MB.</small>
                        @error('profile_picture')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Full Name -->
                <div class="mb-3">
                    <label for="name" class="form-label text-dark fw-semibold">Full Name</label>
                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Email Address -->
                <div class="mb-4">
                    <label for="email" class="form-label text-dark fw-semibold">Email Address</label>
                    <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn gradient-btn w-100">
                    <i class="bi bi-check2-circle me-1"></i> Save Profile Details
                </button>
            </form>
        </div>
    </div>

    <!-- Security Credentials Card -->
    <div class="col-12 col-lg-6">
        <div class="card premium-card p-4 h-100">
            <h5 class="fw-bold mb-4"><i class="bi bi-shield-lock-fill text-danger me-2"></i>Update Password</h5>

            <form method="POST" action="{{ route('profile.password') }}">
                @csrf

                <!-- Current Password -->
                <div class="mb-3">
                    <label for="current_password" class="form-label text-dark fw-semibold">Current Password</label>
                    <input type="password" name="current_password" id="current_password" class="form-control @error('current_password') is-invalid @enderror" required>
                    @error('current_password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- New Password -->
                <div class="mb-3">
                    <label for="password" class="form-label text-dark fw-semibold">New Password</label>
                    <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" required>
                    <small class="text-muted">Password must be at least 8 characters.</small>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="mb-4">
                    <label for="password_confirmation" class="form-label text-dark fw-semibold">Confirm New Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-dark w-100">
                    <i class="bi bi-key-fill me-1"></i> Update Password
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
