@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <a href="{{ route('admin.reservations.index') }}" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left me-1"></i> Back to Reservations
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Reservation Details</h6>
            <div>
                <form action="{{ route('admin.reservations.destroy', $reservation) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this reservation?')">
                        <i class="fas fa-trash me-1"></i> Delete Reservation
                    </button>
                </form>
            </div>
        </div>

        <div class="card-body">
            <!-- User Information -->
            <div class="card mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">User Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 text-center">
                            <img src="{{ $reservation->user->image ? Storage::url($reservation->user->image) : '' }}"
                                 alt="{{ $reservation->user->name }}"
                                 data-name="{{ $reservation->user->name }}"
                                 class="user-avatar-auto rounded-circle mb-3"
                                 style="width: 150px; height: 150px;">
                            <h4 class="mb-1">{{ $reservation->user->name }}</h4>
                            <p class="text-muted">{{ $reservation->user->email }}</p>
                        </div>
                        <div class="col-md-8">
                            <div class="table-responsive">
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <th style="width: 30%;">Phone</th>
                                            <td>{{ $reservation->user->phone ?? 'N/A' }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Service Information -->
            <div class="card mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Service Information</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <th style="width: 30%;">Service Name</th>
                                    <td>{{ $reservation->service->name }}</td>
                                </tr>
                                <tr>
                                    <th>Provider</th>
                                    <td>{{ $reservation->service->service_provider }}</td>
                                </tr>
                                <tr>
                                    <th>Location</th>
                                    <td>{{ $reservation->service->location }}</td>
                                </tr>
                                <tr>
                                    <th>Price</th>
                                    <td>${{ number_format($reservation->service->price, 2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Reservation Details -->
            <div class="card mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Reservation Details</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <th style="width: 30%;">Date</th>
                                    <td>{{ $reservation->date->format('F j, Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Time</th>
                                    <td>{{ $reservation->session_from->format('H:i') }} - {{ $reservation->session_to->format('H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        <form action="{{ route('admin.reservations.update-status', $reservation) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <select name="status" onchange="this.form.submit()" class="form-select">
                                                <option value="pending" {{ $reservation->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="confirmed" {{ $reservation->status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                                <option value="cancelled" {{ $reservation->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                                <option value="completed" {{ $reservation->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                            </select>
                                        </form>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Created At</th>
                                    <td>{{ $reservation->created_at->format('F j, Y H:i:s') }}</td>
                                </tr>
                                @if($reservation->notes)
                                <tr>
                                    <th>Notes</th>
                                    <td>{{ $reservation->notes }}</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
