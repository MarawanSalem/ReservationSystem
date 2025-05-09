@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-12">
    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h2 class="text-2xl font-semibold text-gray-800">Reservation Details</h2>
                <span class="px-3 py-1 rounded-full text-sm font-medium
                    @if($reservation->status === 'confirmed') bg-green-100 text-green-800
                    @elseif($reservation->status === 'pending') bg-yellow-100 text-yellow-800
                    @else bg-red-100 text-red-800
                    @endif">
                    {{ ucfirst($reservation->status) }}
                </span>
            </div>
        </div>

        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Service Details -->
            <div class="space-y-4">
                <div>
                    <h3 class="text-lg font-medium text-gray-900">Service Information</h3>
                    <div class="mt-2">
                        <p class="text-sm text-gray-600">Service: <span class="font-medium text-gray-900">{{ $reservation->service->name }}</span></p>
                        <p class="text-sm text-gray-600">Provider: <span class="font-medium text-gray-900">{{ $reservation->service->service_provider }}</span></p>
                        <p class="text-sm text-gray-600">Location: <span class="font-medium text-gray-900">{{ $reservation->service->location }}</span></p>
                        <p class="text-sm text-gray-600">Duration: <span class="font-medium text-gray-900">{{ $reservation->service->duration }} minutes</span></p>
                        <p class="text-sm text-gray-600">Price: <span class="font-medium text-gray-900">${{ number_format($reservation->service->price, 2) }}</span></p>
                    </div>
                </div>
            </div>

            <!-- Reservation Details -->
            <div class="space-y-4">
                <div>
                    <h3 class="text-lg font-medium text-gray-900">Booking Information</h3>
                    <div class="mt-2">
                        <p class="text-sm text-gray-600">Date: <span class="font-medium text-gray-900">{{ date('F j, Y', strtotime($reservation->date)) }}</span></p>
                        <p class="text-sm text-gray-600">Time: <span class="font-medium text-gray-900">{{ date('g:i A', strtotime($reservation->session_from)) }} - {{ date('g:i A', strtotime($reservation->session_to)) }}</span></p>
                        @if($reservation->notes)
                            <p class="text-sm text-gray-600 mt-4">Special Requests:</p>
                            <p class="text-sm text-gray-900 mt-1">{{ $reservation->notes }}</p>
                        @endif
                    </div>
                </div>

                @if($reservation->status === 'pending')
                    <div class="flex space-x-4 mt-6">
                        <form action="{{ route('reservations.destroy', $reservation) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    onclick="return confirm('Are you sure you want to cancel this reservation?')"
                                    class="bg-red-100 text-red-700 hover:bg-red-200 px-4 py-2 rounded-md text-sm font-medium">
                                Cancel Reservation
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
