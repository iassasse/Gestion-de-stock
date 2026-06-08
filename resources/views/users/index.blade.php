@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Users Management</h4>
        <p class="text-muted mb-0">List, edit, create, delete, and control activation state of team members</p>
    </div>
    <a href="{{ route('users.create') }}" class="btn gradient-btn d-flex align-items-center">
        <i class="bi bi-person-plus me-1"></i> Add User
    </a>
</div>

<!-- Search & Content Card -->
<div class="card premium-card p-4">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-3">
        <!-- Search bar -->
        <form method="GET" action="{{ route('users.index') }}" class="d-flex w-100 max-w-400" style="max-width: 400px;">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Search by name, email, role..." value="{{ $search }}">
                @if($search)
                    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary border-start-0" type="button">Clear</a>
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
                    <th style="width: 70px;">Avatar</th>
                    <th>Full Name</th>
                    <th>Email Address</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th style="width: 280px;" class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $u)
                    <tr>
                        <td>
                            @if($u->profile_picture)
                                <img src="{{ asset('storage/' . $u->profile_picture) }}" alt="Avatar" class="rounded-circle border" style="width: 36px; height: 36px; object-fit: cover;">
                            @else
                                <div class="bg-light text-secondary rounded-circle d-flex align-items-center justify-content-center border" style="width: 36px; height: 36px;">
                                    <i class="bi bi-person-fill fs-5"></i>
                                </div>
                            @endif
                        </td>
                        <td><div class="fw-semibold text-dark">{{ $u->name }}</div></td>
                        <td>{{ $u->email }}</td>
                        <td>
                            <span class="badge {{ $u->role === 'Chef Magasinier' ? 'bg-primary-subtle text-primary border border-primary-subtle' : 'bg-secondary-subtle text-secondary border border-secondary-subtle' }} px-2 py-1">
                                {{ $u->role }}
                            </span>
                            @if($u->is_super_chef_magasinier)
                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1 ms-1" style="color: #856404 !important; background-color: #fff3cd !important; border-color: #ffeeba !important;">
                                    <i class="bi bi-shield-fill-check me-1"></i>Protected
                                </span>
                            @endif
                        </td>
                        <td>
                            @if($u->is_active)
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1"><i class="bi bi-check-circle-fill me-1"></i>Active</span>
                            @else
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1"><i class="bi bi-x-circle-fill me-1"></i>Deactivated</span>
                            @endif
                        </td>
                        <td>{{ $u->created_at->format('M d, Y') }}</td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">
                                @if(!Auth::user()->canManage($u))
                                    <button class="btn btn-sm btn-outline-secondary disabled" title="No permission"><i class="bi bi-person-dash-fill me-1"></i>Deactivate</button>
                                @elseif($u->is_super_chef_magasinier)
                                    <button class="btn btn-sm btn-outline-secondary disabled" title="Protected account cannot be deactivated"><i class="bi bi-person-dash-fill me-1"></i>Deactivate</button>
                                @elseif($u->id !== Auth::id())
                                    <form method="POST" action="{{ route('users.toggle-status', $u->id) }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm {{ $u->is_active ? 'btn-outline-warning' : 'btn-outline-success' }}" title="{{ $u->is_active ? 'Deactivate Account' : 'Activate Account' }}">
                                            @if($u->is_active)
                                                <i class="bi bi-person-dash-fill me-1"></i>Deactivate
                                            @else
                                                <i class="bi bi-person-check-fill me-1"></i>Activate
                                            @endif
                                        </button>
                                    </form>
                                @else
                                    <button class="btn btn-sm btn-outline-secondary disabled" title="Cannot toggle self"><i class="bi bi-person-dash-fill me-1"></i>Deactivate</button>
                                @endif

                                @if(!Auth::user()->canManage($u))
                                    <button class="btn btn-sm btn-outline-secondary disabled" title="No permission"><i class="bi bi-pencil-fill"></i></button>
                                @else
                                    <a href="{{ route('users.edit', $u->id) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>
                                @endif

                                @if(!Auth::user()->canManage($u))
                                    <button class="btn btn-sm btn-outline-secondary disabled" title="No permission"><i class="bi bi-trash-fill"></i></button>
                                @elseif($u->is_super_chef_magasinier)
                                    <button class="btn btn-sm btn-outline-secondary disabled" title="Protected account cannot be deleted"><i class="bi bi-trash-fill"></i></button>
                                @elseif($u->id !== Auth::id())
                                    <button type="button" class="btn btn-sm btn-outline-danger" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#deleteModal" 
                                            data-action="{{ route('users.destroy', $u->id) }}"
                                            title="Delete">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                @else
                                    <button class="btn btn-sm btn-outline-danger disabled" title="Cannot delete self"><i class="bi bi-trash-fill"></i></button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-5">
                            <i class="bi bi-people fs-1 d-block mb-3"></i>
                            No users found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination links -->
    <div class="mt-4 d-flex justify-content-end">
        {{ $users->links('pagination::bootstrap-5') }}
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
                Are you sure you want to delete this user? All session details associated with this user will be removed. This action is irreversible.
            </div>
            <div class="modal-footer border-top-0 pt-0">
                <button type="button" class="btn btn-light fw-semibold text-dark" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger fw-semibold">Delete User</button>
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
