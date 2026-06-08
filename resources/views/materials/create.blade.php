@extends('layouts.app')

@section('content')
<div class="mb-4">
    <a href="{{ route('materials.index') }}" class="btn btn-sm btn-link text-muted p-0 text-decoration-none">
        <i class="bi bi-arrow-left me-1"></i> Back to Materials
    </a>
    <h4 class="fw-bold mt-2 mb-1">Add Material</h4>
    <p class="text-muted mb-0">Create a new material specification record</p>
</div>

<div class="row">
    <div class="col-12 col-md-8 col-lg-6">
        <div class="card premium-card p-4">
            <form method="POST" action="{{ route('materials.store') }}">
                @csrf

                <!-- Name -->
                <div class="mb-3">
                    <label for="name" class="form-label text-dark fw-semibold">Material Name</label>
                    <input type="text" name="name" id="name" 
                           class="form-control @error('name') is-invalid @enderror" 
                           value="{{ old('name') }}" 
                           placeholder="e.g. Copper Wire 2.5mm" 
                           required autocomplete="off">
                    @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Reference Code -->
                <div class="mb-3">
                    <label for="ref" class="form-label text-dark fw-semibold">Reference Code (Unique)</label>
                    <input type="text" name="ref" id="ref" 
                           class="form-control @error('ref') is-invalid @enderror" 
                           value="{{ old('ref') }}" 
                           placeholder="e.g. MAT-ELEC-001" 
                           required autocomplete="off">
                    @error('ref')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Category Selector -->
                <div class="mb-4">
                    <label for="category_id" class="form-label text-dark fw-semibold">Category</label>
                    <select name="category_id" id="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                        <option value="">Select a Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ (old('category_id', $selectedCategoryId ?? '') == $category->id) ? 'selected' : '' }}>
                                {{ $category->title }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('materials.index') }}" class="btn btn-light fw-semibold text-dark">Cancel</a>
                    <button type="submit" class="btn gradient-btn">Create Material</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
