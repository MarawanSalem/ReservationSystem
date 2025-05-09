@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-12">
    <div class="sm:flex sm:items-center sm:justify-between mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">My Reservations</h1>
    </div>

    @if($reservations->isEmpty())
        <div class="bg-white rounded-lg shadow-sm p-6 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No reservations</h3>
            <p class="mt-1 text-sm text-gray-500">Get started by booking a service.</p>
            <div class="mt-6">
                <a href="{{ route('home') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-pink-600 hover:bg-pink-700">
                    <svg class="mr-2 -ml-1 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Browse Services
                </a>
            </div>
        </div>
    @else
        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <ul role="list" class="divide-y divide-gray-200">
                @foreach($reservations as $reservation)
                    <li class="p-4 sm:p-6 hover:bg-gray-50 transition-colors duration-200">
                        <div class="flex items-center justify-between flex-wrap gap-4">
                            <div class="sm:flex sm:items-center sm:gap-4">
                                <div class="relative h-24 w-24 rounded-lg overflow-hidden">
                                    <img src="{{ $reservation->service->image ? Storage::url($reservation->service->image) : 'https://via.placeholder.com/400x300' }}"
                                         alt="{{ $reservation->service->name }}"
                                         class="h-full w-full object-cover">
                                </div>
                                <div class="mt-4 sm:mt-0">
                                    <a href="{{ route('services.show', $reservation->service) }}" class="text-lg font-semibold text-gray-900 hover:text-pink-600">
                                        {{ $reservation->service->name }}
                                    </a>
                                    <div class="mt-1 flex flex-wrap gap-2">
                                        <span class="inline-flex items-center text-sm text-gray-600">
                                            <svg class="mr-1.5 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            {{ date('F j, Y', strtotime($reservation->date)) }}
                                        </span>
                                        <span class="inline-flex items-center text-sm text-gray-600">
                                            <svg class="mr-1.5 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            {{ date('g:i A', strtotime($reservation->session_from)) }} - {{ date('g:i A', strtotime($reservation->session_to)) }}
                                        </span>
                                        <span class="inline-flex items-center text-sm text-gray-600">
                                            <svg class="mr-1.5 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            {{ $reservation->service->location }}
                                        </span>
                                    </div>
                                    @if($reservation->notes)
                                        <p class="mt-2 text-sm text-gray-500">
                                            {{ $reservation->notes }}
                                        </p>
                                    @endif
                                </div>
                            </div>

                            <div class="flex items-center gap-4">
                                <span @class([
                                    'px-3 py-1 rounded-full text-sm font-medium',
                                    'bg-yellow-100 text-yellow-800' => $reservation->status === 'pending',
                                    'bg-green-100 text-green-800' => $reservation->status === 'confirmed',
                                    'bg-red-100 text-red-800' => $reservation->status === 'cancelled',
                                ])>
                                    {{ ucfirst($reservation->status) }}
                                </span>

                                @if($reservation->status === 'pending')
                                    <form action="{{ route('reservations.destroy', $reservation) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                onclick="return confirm('Are you sure you want to cancel this reservation?')"
                                                class="text-sm font-medium text-red-600 hover:text-red-500">
                                            Cancel
                                        </button>
                                    </form>
                                @endif

                                <a href="{{ route('reservations.show', $reservation) }}"
                                   class="text-sm font-medium text-pink-600 hover:text-pink-500">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>

            <div class="px-4 py-4 sm:px-6 border-t border-gray-200">
                {{ $reservations->links() }}
            </div>
        </div>
    @endif

    @if(session('success'))
        <div class="fixed bottom-4 right-4">
            <div class="bg-green-50 text-green-800 rounded-lg p-4 shadow-lg border border-green-200">
                {{ session('success') }}
            </div>
        </div>
    @endif
</div>
@endsection
