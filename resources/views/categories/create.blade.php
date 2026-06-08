@extends('layouts.app')

@section('content')
<div class="mb-4">
    <a href="{{ route('categories.index') }}" class="btn btn-sm btn-link text-muted p-0 text-decoration-none">
        <i class="bi bi-arrow-left me-1"></i> Back to Categories
    </a>
    <h4 class="fw-bold mt-2 mb-1">Add Category</h4>
    <p class="text-muted mb-0">Create a new item group category classification</p>
</div>

<div class="row">
    <div class="col-12 col-md-8 col-lg-6">
        <div class="card premium-card p-4">
            <form method="POST" action="{{ route('categories.store') }}">
                @csrf

                <!-- Category Title -->
                <div class="mb-4">
                    <label for="title" class="form-label text-dark fw-semibold">Category Title</label>
                    <input type="text" name="title" id="title" 
                           class="form-control @error('title') is-invalid @enderror" 
                           value="{{ old('title') }}" 
                           placeholder="e.g. Electrical Tools" 
                           required autocomplete="off">
                    @error('title')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('categories.index') }}" class="btn btn-light fw-semibold text-dark">Cancel</a>
                    <button type="submit" class="btn gradient-btn">Create Category</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
