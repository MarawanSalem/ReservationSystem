@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Users Management</h1>
        <div class="d-flex gap-2">
            <form class="d-flex" action="{{ route('admin.users.index') }}" method="GET">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Search users..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search fa-sm"></i>
                    </button>
                </div>
            </form>
            <button type="button" id="sendNotificationBtn" class="btn btn-info" disabled>
                <i class="fas fa-bell me-1"></i> Send Notification
            </button>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="usersTable">
                    <thead class="thead-light">
                        <tr>
                            <th>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="selectAllUsers">
                                </div>
                            </th>
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
                            <td>
                                <div class="form-check">
                                    <input class="form-check-input user-checkbox" type="checkbox" value="{{ $user->id }}" data-user-name="{{ $user->name }}">
                                </div>
                            </td>
                            <td>{{ $user->id }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="{{ $user->image ? Storage::url($user->image) : '' }}"
                                         alt="{{ $user->name }}"
                                         data-name="{{ $user->name }}"
                                         class="user-avatar-auto rounded-circle me-2"
                                         style="width: 40px; height: 40px;">
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

<!-- Notification Modal -->
<div class="modal fade" id="notificationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">Send Notification</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="notificationForm" action="{{ route('admin.users.notify') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div id="selectedUsersInfo" class="alert alert-info mb-3">
                        Selected users: <span id="selectedUsersCount">0</span>
                    </div>
                    <div class="mb-3">
                        <label for="notificationTitle" class="form-label">Title</label>
                        <input type="text" class="form-control" id="notificationTitle" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="notificationBody" class="form-label">Message</label>
                        <textarea class="form-control" id="notificationBody" name="body" rows="3" required></textarea>
                    </div>
                    <input type="hidden" name="user_ids" id="selectedUserIds">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-info">Send Notification</button>
                </div>
            </form>
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

    #userImage[data-initials] {
        position: relative;
        background-color: #4e73df;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        font-weight: bold;
    }

    .initials-overlay {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: white;
        font-size: 3rem;
        font-weight: bold;
        pointer-events: none;
    }
</style>
@endpush
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Bootstrap modals
        const viewUserModal = new bootstrap.Modal(document.getElementById('viewUserModal'));
        const deleteUserModal = new bootstrap.Modal(document.getElementById('deleteUserModal'));

        // Helper function to get initials from name
        function getInitials(name) {
            return name
                .split(' ')
                .map(word => word[0])
                .join('')
                .toUpperCase();
        }

        // View User Modal Handler
        document.querySelectorAll('.view-user').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const userId = this.dataset.userId;

                // Show loading state
                const userImageEl = document.getElementById('userImage');
                userImageEl.src = '';
                document.getElementById('userName').textContent = 'Loading...';
                document.getElementById('userEmail').textContent = '';
                document.getElementById('userRole').innerHTML = '';

                fetch(`/admin/users/${userId}`)
                    .then(response => response.json())
                    .then(data => {
                        const userImage = data.image ? `/storage/${data.image}` : null;

                        if (userImage) {
                            userImageEl.src = userImage;
                            userImageEl.style.backgroundColor = '';
                            userImageEl.style.display = 'block';
                            userImageEl.style.color = '';
                            userImageEl.style.lineHeight = '';
                            userImageEl.style.fontSize = '';
                            userImageEl.style.fontWeight = '';
                            userImageEl.style.textAlign = '';
                            userImageEl.removeAttribute('data-initials');
                        } else {
                            // Create initials display
                            const initials = getInitials(data.name);
                            userImageEl.style.backgroundColor = '#4e73df';
                            userImageEl.style.display = 'flex';
                            userImageEl.style.alignItems = 'center';
                            userImageEl.style.justifyContent = 'center';
                            userImageEl.style.color = '#ffffff';
                            userImageEl.style.fontSize = '3rem';
                            userImageEl.style.fontWeight = 'bold';
                            userImageEl.setAttribute('data-initials', initials);
                            userImageEl.src = 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7'; // Transparent image
                        }

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

                        // Add initials after image loads or errors
                        userImageEl.onload = function() {
                            if (this.hasAttribute('data-initials')) {
                                this.insertAdjacentHTML('afterend',
                                    `<div class="initials-overlay">${this.getAttribute('data-initials')}</div>`);
                            }
                        };

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

        // Select All Users Checkbox Handler
        document.getElementById('selectAllUsers').addEventListener('change', function() {
            const isChecked = this.checked;
            document.querySelectorAll('.user-checkbox').forEach(checkbox => {
                checkbox.checked = isChecked;
            });
            updateSendNotificationButton();
        });

        // Individual User Checkbox Handler
        document.querySelectorAll('.user-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                updateSendNotificationButton();
                // Update select all checkbox state
                const allCheckboxes = document.querySelectorAll('.user-checkbox');
                const allChecked = Array.from(allCheckboxes).every(cb => cb.checked);
                document.getElementById('selectAllUsers').checked = allChecked;
            });
        });

        function updateSendNotificationButton() {
            const anyChecked = document.querySelectorAll('.user-checkbox:checked').length > 0;
            document.getElementById('sendNotificationBtn').disabled = !anyChecked;
        }

        // Notification Modal Handler
        const notificationModal = new bootstrap.Modal(document.getElementById('notificationModal'));

        document.getElementById('sendNotificationBtn').addEventListener('click', function() {
            const checkedUsers = document.querySelectorAll('.user-checkbox:checked');
            const selectedIds = Array.from(checkedUsers).map(checkbox => checkbox.value).join(',');
            document.getElementById('selectedUserIds').value = selectedIds;
            document.getElementById('selectedUsersCount').textContent = checkedUsers.length;
            notificationModal.show();
        });

        // Reset notification form when modal is hidden
        document.getElementById('notificationModal').addEventListener('hidden.bs.modal', function() {
            document.getElementById('notificationForm').reset();
        });

        // Handle notification form submission
        document.getElementById('notificationForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const form = this;
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn.innerHTML;

            // Disable submit button and show loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Sending...';

            const formData = new FormData(this);

            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                console.log('Server response:', data);

                if (data.success) {
                    // Create and show success toast
                    const toastContainer = document.createElement('div');
                    toastContainer.className = 'position-fixed top-0 end-0 p-3';
                    toastContainer.style.zIndex = '1050';
                    toastContainer.innerHTML = `
                        <div class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
                            <div class="d-flex">
                                <div class="toast-body">
                                    ${data.message}
                                </div>
                                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                            </div>
                        </div>
                    `;
                    document.body.appendChild(toastContainer);
                    const toast = new bootstrap.Toast(toastContainer.querySelector('.toast'));
                    toast.show();

                    // Close modal
                    bootstrap.Modal.getInstance(document.getElementById('notificationModal')).hide();

                    // Reset form
                    form.reset();

                    // Uncheck all checkboxes
                    document.querySelectorAll('.user-checkbox').forEach(checkbox => {
                        checkbox.checked = false;
                    });
                    document.getElementById('selectAllUsers').checked = false;
                    updateSendNotificationButton();
                } else {
                    // Create and show error toast
                    const toastContainer = document.createElement('div');
                    toastContainer.className = 'position-fixed top-0 end-0 p-3';
                    toastContainer.style.zIndex = '1050';
                    toastContainer.innerHTML = `
                        <div class="toast align-items-center text-white bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">
                            <div class="d-flex">
                                <div class="toast-body">
                                    ${data.message}
                                </div>
                                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                            </div>
                        </div>
                    `;
                    document.body.appendChild(toastContainer);
                    const toast = new bootstrap.Toast(toastContainer.querySelector('.toast'));
                    toast.show();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // Create and show error toast
                const toastContainer = document.createElement('div');
                toastContainer.className = 'position-fixed top-0 end-0 p-3';
                toastContainer.style.zIndex = '1050';
                toastContainer.innerHTML = `
                    <div class="toast align-items-center text-white bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">
                        <div class="d-flex">
                            <div class="toast-body">
                                An error occurred while sending notifications. Please try again.
                            </div>
                            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                        </div>
                    </div>
                `;
                document.body.appendChild(toastContainer);
                const toast = new bootstrap.Toast(toastContainer.querySelector('.toast'));
                toast.show();
            })
            .finally(() => {
                // Re-enable submit button and restore original text
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
            });
        });
    });
</script>
@endpush
@endsection
