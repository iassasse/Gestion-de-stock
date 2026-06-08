@extends('layouts.app')

@section('content')
<div class="mb-4">
    <a href="{{ route('users.index') }}" class="btn btn-sm btn-link text-muted p-0 text-decoration-none">
        <i class="bi bi-arrow-left me-1"></i> Back to Users
    </a>
    <h4 class="fw-bold mt-2 mb-1">Add User</h4>
    <p class="text-muted mb-0">Create a new user account profile for staff</p>
</div>

<div class="row">
    <div class="col-12 col-md-8 col-lg-6">
        <div class="card premium-card p-4">
            <form method="POST" action="{{ route('users.store') }}">
                @csrf

                <!-- Name -->
                <div class="mb-3">
                    <label for="name" class="form-label text-dark fw-semibold">Full Name</label>
                    <input type="text" name="name" id="name" 
                           class="form-control @error('name') is-invalid @enderror" 
                           value="{{ old('name') }}" 
                           placeholder="e.g. Jean Dupont" required autocomplete="off">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <label for="email" class="form-label text-dark fw-semibold">Email Address</label>
                    <input type="email" name="email" id="email" 
                           class="form-control @error('email') is-invalid @enderror" 
                           value="{{ old('email') }}" 
                           placeholder="e.g. jean@example.com" required autocomplete="off">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <label for="password" class="form-label text-dark fw-semibold">Password</label>
                    <input type="password" name="password" id="password" 
                           class="form-control @error('password') is-invalid @enderror" required>
                    <small class="text-muted">Password must be at least 8 characters.</small>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Role Selector -->
                <div class="mb-4">
                    <label for="role" class="form-label text-dark fw-semibold">Role</label>
                    <select name="role" id="role" class="form-select @error('role') is-invalid @enderror" required>
                        <option value="">Select a Role</option>
                        <option value="Magasinier" {{ old('role') === 'Magasinier' ? 'selected' : '' }}>Magasinier</option>
                        <option value="Chef Magasinier" {{ old('role') === 'Chef Magasinier' ? 'selected' : '' }}>Chef Magasinier</option>
                    </select>
                    @error('role')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('users.index') }}" class="btn btn-light fw-semibold text-dark">Cancel</a>
                    <button type="submit" class="btn gradient-btn">Create User</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
