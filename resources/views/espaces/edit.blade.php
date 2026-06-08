@extends('layouts.app')

@section('content')
<div class="mb-4">
    <a href="{{ route('espaces.index') }}" class="btn btn-sm btn-link text-muted p-0 text-decoration-none">
        <i class="bi bi-arrow-left me-1"></i> Back to Spaces
    </a>
    <h4 class="fw-bold mt-2 mb-1">Edit Space</h4>
    <p class="text-muted mb-0">Modify details of space #{{ $espace->id }}</p>
</div>

<div class="row">
    <div class="col-12 col-md-8 col-lg-6">
        <div class="card premium-card p-4">
            <form method="POST" action="{{ route('espaces.update', $espace->id) }}">
                @csrf
                @method('PUT')

                <!-- Space Title -->
                <div class="mb-4">
                    <label for="title" class="form-label text-dark fw-semibold">Space Title</label>
                    <input type="text" name="title" id="title" 
                           class="form-control @error('title') is-invalid @enderror" 
                           value="{{ old('title', $espace->title) }}" 
                           placeholder="e.g. Pole DIA, Pole GC, Magasin..." 
                           required autocomplete="off">
                    @error('title')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('espaces.index') }}" class="btn btn-light fw-semibold text-dark">Cancel</a>
                    <button type="submit" class="btn gradient-btn">Update Space</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
