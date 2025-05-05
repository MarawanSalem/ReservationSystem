@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Users Management</h1>
        <div>
            <form class="d-flex" action="{{ route('admin.users.index') }}" method="GET">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Search users..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search fa-sm"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="usersTable">
                    <thead class="thead-light">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="{{ $user->image ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) }}"
                                         alt="{{ $user->name }}"
                                         class="rounded-circle me-2"
                                         style="width: 40px; height: 40px; object-fit: cover;">
                                    <div>
                                        <span class="fw-bold">{{ $user->name }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @foreach($user->roles as $role)
                                    <span class="badge bg-primary rounded-pill">{{ $role->name }}</span>
                                @endforeach
                            </td>
                            <td class="text-center">
                                <div class="action-buttons">
                                    <button type="button" class="btn btn-info btn-sm view-user"
                                            data-bs-toggle="modal"
                                            data-bs-target="#viewUserModal"
                                            data-user-id="{{ $user->id }}">
                                        <i class="fas fa-eye me-1"></i> View
                                    </button>
                                    @if(!$user->hasRole('admin'))
                                        <button type="button" class="btn btn-danger btn-sm delete-user ms-2"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteUserModal"
                                                data-user-id="{{ $user->id }}"
                                                data-user-name="{{ $user->name }}">
                                            <i class="fas fa-trash me-1"></i> Delete
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-4">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</div>

<!-- View User Modal -->
<div class="modal fade" id="viewUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">User Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4 text-center border-end">
                        <img id="userImage" src="" alt="" class="img-fluid rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                        <h4 id="userName" class="mb-1"></h4>
                        <p class="text-muted mb-3" id="userEmail"></p>
                        <div id="userRole" class="mb-3"></div>
                    </div>
                    <div class="col-md-8">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <tbody>
                                    <tr>
                                        <th style="width: 30%;">Username</th>
                                        <td id="userUsername"></td>
                                    </tr>
                                    <tr>
                                        <th>Phone</th>
                                        <td id="userPhone"></td>
                                    </tr>
                                    <tr>
                                        <th>Member Since</th>
                                        <td id="userCreatedAt"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete User Modal -->
<div class="modal fade" id="deleteUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Confirm Deletion</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
                <i class="fas fa-exclamation-triangle text-warning display-3 mb-4"></i>
                <p class="mb-1">Are you sure you want to delete this user?</p>
                <p class="h5 mb-3" id="deleteUserName"></p>
                <p class="text-danger mb-0"><small>This action cannot be undone.</small></p>
            </div>
            <div class="modal-footer justify-content-center">
                <form id="deleteUserForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete User</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .modal {
        visibility: hidden;
        opacity: 0;
        position: fixed;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        z-index: 1050;
        background-color: rgba(0, 0, 0, 0.4);
        transition: all 0.2s ease;
    }

    .modal.show {
        visibility: visible;
        opacity: 1;
    }

    .modal-dialog {
        transform: translateY(-100%);
        transition: transform 0.3s ease-out;
    }

    .modal.show .modal-dialog {
        transform: translateY(0);
    }

    .table-hover tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.05);
    }

    .action-buttons {
        white-space: nowrap;
    }
</style>
@endpush
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Bootstrap modals
        const viewUserModal = new bootstrap.Modal(document.getElementById('viewUserModal'));
        const deleteUserModal = new bootstrap.Modal(document.getElementById('deleteUserModal'));

        // View User Modal Handler
        document.querySelectorAll('.view-user').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const userId = this.dataset.userId;

                // Show loading state
                document.getElementById('userImage').src = '';
                document.getElementById('userName').textContent = 'Loading...';
                document.getElementById('userEmail').textContent = '';
                document.getElementById('userRole').innerHTML = '';

                fetch(`/admin/users/${userId}`)
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('userImage').src = data.image || `https://ui-avatars.com/api/?name=${encodeURIComponent(data.name)}`;
                        document.getElementById('userName').textContent = data.name;
                        document.getElementById('userEmail').textContent = data.email;
                        document.getElementById('userUsername').textContent = data.username;
                        document.getElementById('userPhone').textContent = data.phone || 'Not provided';
                        document.getElementById('userRole').innerHTML = data.roles.map(role =>
                            `<span class="badge bg-primary rounded-pill">${role.name}</span>`
                        ).join(' ');
                        document.getElementById('userCreatedAt').textContent = new Date(data.created_at).toLocaleDateString('en-US', {
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric'
                        });
                        viewUserModal.show();
                    });
            });
        });

        // Delete User Modal Handler
        document.querySelectorAll('.delete-user').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const userId = this.dataset.userId;
                const userName = this.dataset.userName;
                document.getElementById('deleteUserName').textContent = userName;
                document.getElementById('deleteUserForm').action = `/admin/users/${userId}`;
                deleteUserModal.show();
            });
        });
    });
</script>
@endpush
@endsection
