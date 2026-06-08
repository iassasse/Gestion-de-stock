@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Spaces (Espaces)</h4>
        <p class="text-muted mb-0">Manage physical divisions, warehouses, and poles</p>
    </div>
    <a href="{{ route('espaces.create') }}" class="btn gradient-btn d-flex align-items-center">
        <i class="bi bi-plus-lg me-1"></i> Add Space
    </a>
</div>

<!-- Search & Content Card -->
<div class="card premium-card p-4">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-3">
        <!-- Search bar -->
        <form method="GET" action="{{ route('espaces.index') }}" class="d-flex w-100 max-w-400" style="max-width: 400px;">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Search spaces..." value="{{ $search }}">
                @if($search)
                    <a href="{{ route('espaces.index') }}" class="btn btn-outline-secondary border-start-0" type="button">Clear</a>
                @endif
                <button type="submit" class="btn btn-dark">Search</button>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead>
                <tr>
                    <th style="width: 80px;">ID</th>
                    <th>Space Title</th>
                    <th>Created At</th>
                    <th style="width: 150px;" class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($espaces as $espace)
                    <tr>
                        <td><span class="text-muted fw-bold">#{{ $espace->id }}</span></td>
                        <td>
                            <div class="fw-semibold text-dark d-flex align-items-center">
                                <i class="bi bi-geo-alt-fill text-muted me-2"></i>{{ $espace->title }}
                            </div>
                        </td>
                        <td>{{ $espace->created_at->format('M d, Y H:i') }}</td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ route('espaces.edit', $espace->id) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-danger" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#deleteModal" 
                                        data-action="{{ route('espaces.destroy', $espace->id) }}"
                                        title="Delete">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-5">
                            <i class="bi bi-geo-alt fs-1 d-block mb-3"></i>
                            No spaces found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination links -->
    <div class="mt-4 d-flex justify-content-end">
        {{ $espaces->links('pagination::bootstrap-5') }}
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold text-dark" id="deleteModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-3 text-secondary">
                Are you sure you want to delete this space? <strong>All associated physical articles belonging to this space will also be deleted</strong>. This action is irreversible.
            </div>
            <div class="modal-footer border-top-0 pt-0">
                <button type="button" class="btn btn-light fw-semibold text-dark" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger fw-semibold">Delete Space</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteModal = document.getElementById('deleteModal');
        if (deleteModal) {
            deleteModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const action = button.getAttribute('data-action');
                const form = deleteModal.querySelector('#deleteForm');
                form.setAttribute('action', action);
            });
        }
    });
</script>
@endsection
