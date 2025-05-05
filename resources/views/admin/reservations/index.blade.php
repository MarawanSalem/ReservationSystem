@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Reservations Management</h1>
        <div>
            <form class="d-flex" action="{{ route('admin.reservations.index') }}" method="GET">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Search reservations..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search fa-sm"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Filters -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filters</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.reservations.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label for="start_date" class="form-label">Start Date</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request('start_date') }}">
                </div>
                <div class="col-md-3">
                    <label for="end_date" class="form-label">End Date</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request('end_date') }}">
                </div>
                <div class="col-md-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter me-1"></i> Apply Filters
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="reservationsTable">
                    <thead class="thead-light">
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Service</th>
                            <th>Date & Time</th>
                            <th>Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reservations as $reservation)
                        <tr>
                            <td>{{ $reservation->id }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="{{ $reservation->user->image ?? 'https://ui-avatars.com/api/?name=' . urlencode($reservation->user->name) }}"
                                         alt="{{ $reservation->user->name }}"
                                         class="rounded-circle me-2"
                                         style="width: 40px; height: 40px; object-fit: cover;">
                                    <div>
                                        <span class="fw-bold">{{ $reservation->user->name }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $reservation->service->name }}</td>
                            <td>
                                <div>{{ $reservation->date->format('M d, Y') }}</div>
                                <small class="text-muted">{{ $reservation->session_from->format('H:i') }} - {{ $reservation->session_to->format('H:i') }}</small>
                            </td>
                            <td>
                                @php
                                    $statusClass = [
                                        'pending' => 'bg-warning',
                                        'confirmed' => 'bg-primary',
                                        'cancelled' => 'bg-danger',
                                        'completed' => 'bg-success'
                                    ][$reservation->status];
                                @endphp
                                <span class="badge {{ $statusClass }}">{{ ucfirst($reservation->status) }}</span>
                            </td>
                            <td class="text-center">
                                <div class="action-buttons">
                                    <button type="button" class="btn btn-info btn-sm view-reservation"
                                            data-bs-toggle="modal"
                                            data-bs-target="#viewReservationModal"
                                            data-reservation-id="{{ $reservation->id }}">
                                        <i class="fas fa-eye me-1"></i> View
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm delete-reservation ms-2"
                                            data-bs-toggle="modal"
                                            data-bs-target="#deleteReservationModal"
                                            data-reservation-id="{{ $reservation->id }}"
                                            data-reservation-details="{{ $reservation->service->name }} - {{ $reservation->date->format('M d, Y') }}">
                                        <i class="fas fa-trash me-1"></i> Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-4">
                {{ $reservations->links() }}
            </div>
        </div>
    </div>
</div>

<!-- View Reservation Modal -->
<div class="modal fade" id="viewReservationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">Reservation Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4 text-center border-end">
                        <img id="userImage" src="" alt="" class="img-fluid rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                        <h4 id="userName" class="mb-1"></h4>
                        <p class="text-muted mb-3" id="userEmail"></p>
                    </div>
                    <div class="col-md-8">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <tbody>
                                    <tr>
                                        <th style="width: 30%;">Service</th>
                                        <td id="serviceName"></td>
                                    </tr>
                                    <tr>
                                        <th>Date</th>
                                        <td id="reservationDate"></td>
                                    </tr>
                                    <tr>
                                        <th>Time</th>
                                        <td id="reservationTime"></td>
                                    </tr>
                                    <tr>
                                        <th>Status</th>
                                        <td>
                                            <form id="updateStatusForm" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <select name="status" class="form-select" id="reservationStatus" onchange="this.form.submit()">
                                                    <option value="pending">Pending</option>
                                                    <option value="confirmed">Confirmed</option>
                                                    <option value="cancelled">Cancelled</option>
                                                    <option value="completed">Completed</option>
                                                </select>
                                            </form>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Notes</th>
                                        <td id="reservationNotes"></td>
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

<!-- Delete Reservation Modal -->
<div class="modal fade" id="deleteReservationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Confirm Deletion</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
                <i class="fas fa-exclamation-triangle text-warning display-3 mb-4"></i>
                <p class="mb-1">Are you sure you want to delete this reservation?</p>
                <p class="h5 mb-3" id="deleteReservationDetails"></p>
                <p class="text-danger mb-0"><small>This action cannot be undone.</small></p>
            </div>
            <div class="modal-footer justify-content-center">
                <form id="deleteReservationForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete Reservation</button>
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
        const viewReservationModal = new bootstrap.Modal(document.getElementById('viewReservationModal'));
        const deleteReservationModal = new bootstrap.Modal(document.getElementById('deleteReservationModal'));

        // View Reservation Modal Handler
        document.querySelectorAll('.view-reservation').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const reservationId = this.dataset.reservationId;

                // Show loading state
                document.getElementById('userImage').src = '';
                document.getElementById('userName').textContent = 'Loading...';
                document.getElementById('userEmail').textContent = '';
                document.getElementById('serviceName').textContent = 'Loading...';

                fetch(`/admin/reservations/${reservationId}`)
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('userImage').src = data.user.image || `https://ui-avatars.com/api/?name=${encodeURIComponent(data.user.name)}`;
                        document.getElementById('userName').textContent = data.user.name;
                        document.getElementById('userEmail').textContent = data.user.email;
                        document.getElementById('serviceName').textContent = data.service.name;
                        document.getElementById('reservationDate').textContent = new Date(data.date).toLocaleDateString('en-US', {
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric'
                        });
                        document.getElementById('reservationTime').textContent = `${data.session_from} - ${data.session_to}`;
                        document.getElementById('reservationStatus').value = data.status;
                        document.getElementById('reservationNotes').textContent = data.notes || 'No notes provided';
                        document.getElementById('updateStatusForm').action = `/admin/reservations/${reservationId}/status`;
                        viewReservationModal.show();
                    });
            });
        });

        // Delete Reservation Modal Handler
        document.querySelectorAll('.delete-reservation').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const reservationId = this.dataset.reservationId;
                const reservationDetails = this.dataset.reservationDetails;
                document.getElementById('deleteReservationDetails').textContent = reservationDetails;
                document.getElementById('deleteReservationForm').action = `/admin/reservations/${reservationId}`;
                deleteReservationModal.show();
            });
        });
    });
</script>
@endpush
@endsection
