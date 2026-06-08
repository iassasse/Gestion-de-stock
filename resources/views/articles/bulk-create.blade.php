@extends('layouts.app')

@section('content')
<div class="mb-4">
    <a href="{{ route('articles.index') }}" class="btn btn-sm btn-link text-muted p-0 text-decoration-none">
        <i class="bi bi-arrow-left me-1"></i> Back to Articles
    </a>
    <h4 class="fw-bold mt-2 mb-1">Bulk Create Articles</h4>
    <p class="text-muted mb-0">Generate multiple physical article instances in a reference code range</p>
</div>

<div class="row">
    <div class="col-12 col-md-8 col-lg-6">
        <div class="card premium-card p-4">
            <form method="POST" action="{{ route('articles.bulk-store') }}">
                @csrf

                <!-- Material Selector -->
                <div class="mb-3">
                    <label for="material_id" class="form-label text-dark fw-semibold">Material Specification</label>
                    <select name="material_id" id="material_id" class="form-select @error('material_id') is-invalid @enderror" required>
                        <option value="">Select a Material</option>
                        @foreach($materials as $material)
                            <option value="{{ $material->id }}" {{ old('material_id') == $material->id ? 'selected' : '' }}>
                                {{ $material->name }} ({{ $material->ref }})
                            </option>
                        @endforeach
                    </select>
                    @error('material_id')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Espace/Space Selector -->
                <div class="mb-3">
                    <label for="espace_id" class="form-label text-dark fw-semibold">Storage Space (Espace)</label>
                    <select name="espace_id" id="espace_id" class="form-select @error('espace_id') is-invalid @enderror" required>
                        <option value="">Select a Space</option>
                        @foreach($espaces as $espace)
                            <option value="{{ $espace->id }}" {{ old('espace_id') == $espace->id ? 'selected' : '' }}>
                                {{ $espace->title }}
                            </option>
                        @endforeach
                    </select>
                    @error('espace_id')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Reference Range -->
                <div class="row mb-4">
                    <!-- Start Reference -->
                    <div class="col-6">
                        <label for="start_ref" class="form-label text-dark fw-semibold">Start Reference</label>
                        <input type="number" name="start_ref" id="start_ref" 
                               class="form-control @error('start_ref') is-invalid @enderror" 
                               value="{{ old('start_ref') }}" 
                               placeholder="e.g. 1234" 
                               min="0" required autocomplete="off">
                        @error('start_ref')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- End Reference -->
                    <div class="col-6">
                        <label for="end_ref" class="form-label text-dark fw-semibold">End Reference</label>
                        <input type="number" name="end_ref" id="end_ref" 
                               class="form-control @error('end_ref') is-invalid @enderror" 
                               value="{{ old('end_ref') }}" 
                               placeholder="e.g. 1264" 
                               min="0" required autocomplete="off">
                        @error('end_ref')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="col-12 mt-2">
                        <small class="text-muted">Note: References will be generated sequentially (inclusive). Max range: 500.</small>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('articles.index') }}" class="btn btn-light fw-semibold text-dark">Cancel</a>
                    <button type="submit" class="btn gradient-btn">
                        <i class="bi bi-stack me-1"></i> Generate Articles
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
