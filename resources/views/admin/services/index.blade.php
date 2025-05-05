@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Services Management</h1>
        <div class="d-flex gap-2">
            <form class="d-flex" action="{{ route('admin.services.index') }}" method="GET">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Search services..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search fa-sm"></i>
                    </button>
                </div>
            </form>
            <a href="{{ route('admin.services.create') }}" class="btn btn-primary">
                <i class="fas fa-plus fa-sm"></i> Add Service
            </a>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Provider</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Duration</th>
                            <th>Rating</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($services as $service)
                        <tr>
                            <td>{{ $service->id }}</td>
                            <td>
                                <img src="{{ $service->image ? Storage::url($service->image) : 'https://via.placeholder.com/50' }}"
                                     alt="{{ $service->name }}"
                                     class="rounded"
                                     style="width: 50px; height: 50px; object-fit: cover;">
                            </td>
                            <td>{{ $service->name }}</td>
                            <td>{{ $service->service_provider }}</td>
                            <td>{{ $service->category }}</td>
                            <td>${{ number_format($service->price, 2) }}</td>
                            <td>{{ $service->duration }} mins</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    {{ number_format($service->rating, 1) }}
                                    <i class="fas fa-star text-warning ms-1"></i>
                                </div>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-info btn-sm view-service"
                                        data-bs-toggle="modal"
                                        data-bs-target="#viewServiceModal"
                                        data-service-id="{{ $service->id }}">
                                    <i class="fas fa-eye me-1"></i> View
                                </button>
                                <a href="{{ route('admin.services.edit', $service) }}"
                                   class="btn btn-primary btn-sm ms-1">
                                    <i class="fas fa-edit me-1"></i> Edit
                                </a>
                                <button type="button" class="btn btn-danger btn-sm ms-1 delete-service"
                                        data-bs-toggle="modal"
                                        data-bs-target="#deleteServiceModal"
                                        data-service-id="{{ $service->id }}"
                                        data-service-name="{{ $service->name }}">
                                    <i class="fas fa-trash me-1"></i> Delete
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-4">
                {{ $services->links() }}
            </div>
        </div>
    </div>
</div>

<!-- View Service Modal -->
<div class="modal fade" id="viewServiceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">Service Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4 text-center border-end">
                        <img id="serviceImage" src="" alt="" class="img-fluid rounded mb-3" style="max-height: 200px; width: auto;">
                        <h4 id="serviceName" class="mb-1"></h4>
                        <p class="text-muted mb-3" id="serviceProvider"></p>
                        <div class="mb-3">
                            <span class="badge bg-primary" id="serviceCategory"></span>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="table-responsive">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <th style="width: 30%;">Description</th>
                                        <td id="serviceDescription"></td>
                                    </tr>
                                    <tr>
                                        <th>Location</th>
                                        <td id="serviceLocation"></td>
                                    </tr>
                                    <tr>
                                        <th>Price</th>
                                        <td id="servicePrice"></td>
                                    </tr>
                                    <tr>
                                        <th>Duration</th>
                                        <td id="serviceDuration"></td>
                                    </tr>
                                    <tr>
                                        <th>Rating</th>
                                        <td id="serviceRating"></td>
                                    </tr>
                                    <tr>
                                        <th>Added By</th>
                                        <td id="serviceAddedBy"></td>
                                    </tr>
                                    <tr>
                                        <th>Created At</th>
                                        <td id="serviceCreatedAt"></td>
                                    </tr>
                                    <tr>
                                        <th>Total Reservations</th>
                                        <td id="serviceReservationsCount"></td>
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

<!-- Delete Service Modal -->
<div class="modal fade" id="deleteServiceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Confirm Deletion</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
                <i class="fas fa-exclamation-triangle text-warning display-3 mb-4"></i>
                <p class="mb-1">Are you sure you want to delete this service?</p>
                <p class="h5 mb-3" id="deleteServiceName"></p>
                <p class="text-danger mb-0"><small>This action cannot be undone.</small></p>
            </div>
            <div class="modal-footer justify-content-center">
                <form id="deleteServiceForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete Service</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // View Service Modal
    document.querySelectorAll('.view-service').forEach(button => {
        button.addEventListener('click', function() {
            const serviceId = this.dataset.serviceId;
            fetch(`/admin/services/${serviceId}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('serviceImage').src = data.image || 'https://via.placeholder.com/200';
                    document.getElementById('serviceName').textContent = data.name;
                    document.getElementById('serviceProvider').textContent = data.service_provider;
                    document.getElementById('serviceCategory').textContent = data.category;
                    document.getElementById('serviceDescription').textContent = data.description;
                    document.getElementById('serviceLocation').textContent = data.location;
                    document.getElementById('servicePrice').textContent = `$${parseFloat(data.price).toFixed(2)}`;
                    document.getElementById('serviceDuration').textContent = `${data.duration} minutes`;
                    document.getElementById('serviceRating').innerHTML = `${data.rating} <i class="fas fa-star text-warning"></i>`;
                    document.getElementById('serviceAddedBy').textContent = data.added_by;
                    document.getElementById('serviceCreatedAt').textContent = data.created_at;
                    document.getElementById('serviceReservationsCount').textContent = data.reservations_count;
                });
        });
    });

    // Delete Service Modal
    document.querySelectorAll('.delete-service').forEach(button => {
        button.addEventListener('click', function() {
            const serviceId = this.dataset.serviceId;
            const serviceName = this.dataset.serviceName;
            document.getElementById('deleteServiceName').textContent = serviceName;
            document.getElementById('deleteServiceForm').action = `/admin/services/${serviceId}`;
        });
    });
</script>
@endpush
@endsection
