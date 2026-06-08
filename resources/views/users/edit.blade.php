@extends('layouts.app')

@section('content')
<div class="mb-4">
    <a href="{{ route('users.index') }}" class="btn btn-sm btn-link text-muted p-0 text-decoration-none">
        <i class="bi bi-arrow-left me-1"></i> Back to Users
    </a>
    <h4 class="fw-bold mt-2 mb-1">Edit User</h4>
    <p class="text-muted mb-0">Modify the settings and profile of user #{{ $user->id }}</p>
</div>

<div class="row">
    <div class="col-12 col-md-8 col-lg-6">
        <div class="card premium-card p-4">
            <form method="POST" action="{{ route('users.update', $user->id) }}">
                @csrf
                @method('PUT')

                <!-- Name -->
                <div class="mb-3">
                    <label for="name" class="form-label text-dark fw-semibold">Full Name</label>
                    <input type="text" name="name" id="name" 
                           class="form-control @error('name') is-invalid @enderror" 
                           value="{{ old('name', $user->name) }}" required autocomplete="off">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <label for="email" class="form-label text-dark fw-semibold">Email Address</label>
                    <input type="email" name="email" id="email" 
                           class="form-control @error('email') is-invalid @enderror" 
                           value="{{ old('email', $user->email) }}" required autocomplete="off">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password (Optional) -->
                <div class="mb-3">
                    <label for="password" class="form-label text-dark fw-semibold">Password <small class="text-muted fw-normal">(Leave blank to keep current password)</small></label>
                    <input type="password" name="password" id="password" 
                           class="form-control @error('password') is-invalid @enderror">
                    <small class="text-muted">Password must be at least 8 characters if provided.</small>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Role Selector -->
                <div class="mb-3">
                    <label for="role" class="form-label text-dark fw-semibold">Role</label>
                    @if($user->is_super_chef_magasinier)
                        <select id="role" class="form-select" disabled>
                            <option selected>Chef Magasinier</option>
                        </select>
                        <input type="hidden" name="role" value="Chef Magasinier">
                        <small class="text-muted d-block mt-1">The role of the protected Chef Magasinier account cannot be changed.</small>
                    @else
                        <select name="role" id="role" class="form-select @error('role') is-invalid @enderror" required>
                            <option value="Magasinier" {{ old('role', $user->role) === 'Magasinier' ? 'selected' : '' }}>Magasinier</option>
                            <option value="Chef Magasinier" {{ old('role', $user->role) === 'Chef Magasinier' ? 'selected' : '' }}>Chef Magasinier</option>
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    @endif
                </div>

                <!-- Status Checkbox -->
                <div class="mb-4">
                    <label for="is_active" class="form-label text-dark fw-semibold">Account Status</label>
                    @if($user->is_super_chef_magasinier)
                        <select id="is_active" class="form-select" disabled>
                            <option selected>Active</option>
                        </select>
                        <input type="hidden" name="is_active" value="1">
                        <small class="text-muted d-block mt-1">The protected Chef Magasinier account cannot be deactivated.</small>
                    @else
                        <select name="is_active" id="is_active" class="form-select @error('is_active') is-invalid @enderror" required {{ $user->id === Auth::id() ? 'disabled' : '' }}>
                            <option value="1" {{ old('is_active', $user->is_active) ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ !old('is_active', $user->is_active) ? 'selected' : '' }}>Deactivated</option>
                        </select>
                        @if($user->id === Auth::id())
                            <input type="hidden" name="is_active" value="1">
                            <small class="text-muted d-block mt-1">You cannot deactivate your own logged-in account.</small>
                        @endif
                        @error('is_active')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    @endif
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('users.index') }}" class="btn btn-light fw-semibold text-dark">Cancel</a>
                    <button type="submit" class="btn gradient-btn">Update User</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
