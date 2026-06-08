@extends('layouts.app')

@section('content')
<div class="mb-4">
    <a href="{{ route('articles.index') }}" class="btn btn-sm btn-link text-muted p-0 text-decoration-none">
        <i class="bi bi-arrow-left me-1"></i> Back to Articles
    </a>
    <h4 class="fw-bold mt-2 mb-1">Edit Article</h4>
    <p class="text-muted mb-0">Modify details of article #{{ $article->id }}</p>
</div>

<div class="row">
    <div class="col-12 col-md-8 col-lg-6">
        <div class="card premium-card p-4">
            <form method="POST" action="{{ route('articles.update', $article->id) }}">
                @csrf
                @method('PUT')

                <!-- LI Reference Code -->
                <div class="mb-3">
                    <label for="li_ref" class="form-label text-dark fw-semibold">LI Reference Code (Unique)</label>
                    <input type="text" name="li_ref" id="li_ref" 
                           class="form-control @error('li_ref') is-invalid @enderror" 
                           value="{{ old('li_ref', $article->li_ref) }}" 
                           placeholder="e.g. ART-CAB-001" 
                           required autocomplete="off">
                    @error('li_ref')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Material Selector -->
                <div class="mb-3">
                    <label for="material_id" class="form-label text-dark fw-semibold">Material Specification</label>
                    <select name="material_id" id="material_id" class="form-select @error('material_id') is-invalid @enderror" required>
                        <option value="">Select a Material</option>
                        @foreach($materials as $material)
                            <option value="{{ $material->id }}" {{ old('material_id', $article->material_id) == $material->id ? 'selected' : '' }}>
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
                <div class="mb-4">
                    <label for="espace_id" class="form-label text-dark fw-semibold">Storage Space (Espace)</label>
                    <select name="espace_id" id="espace_id" class="form-select @error('espace_id') is-invalid @enderror" required>
                        <option value="">Select a Space</option>
                        @foreach($espaces as $espace)
                            <option value="{{ $espace->id }}" {{ old('espace_id', $article->espace_id) == $espace->id ? 'selected' : '' }}>
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

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('articles.index') }}" class="btn btn-light fw-semibold text-dark">Cancel</a>
                    <button type="submit" class="btn gradient-btn">Update Article</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
