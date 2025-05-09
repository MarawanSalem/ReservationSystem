@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-12">
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <!-- Profile Header -->
        <div class="px-4 py-5 sm:px-6 flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="relative h-20 w-20 rounded-full overflow-hidden bg-gray-100">
                    @if($user->image && !str_contains($user->image, 'ui-avatars'))
                        <img src="{{ Storage::url($user->image) }}"
                             alt="{{ $user->name }}"
                             class="h-full w-full object-cover">
                    @else
                        <div class="h-full w-full flex items-center justify-center bg-pink-100 text-pink-600 text-2xl font-bold">
                            {{ substr($user->name, 0, 2) }}
                        </div>
                    @endif
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h2>
                    <p class="text-sm text-gray-500">Joined {{ $user->created_at->format('F Y') }}</p>
                </div>
            </div>
            <a href="{{ route('profile.edit') }}"
               class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-pink-600 hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500">
                <svg class="mr-2 -ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                </svg>
                Edit Profile
            </a>
        </div>

        <!-- Profile Information -->
        <div class="border-t border-gray-200">
            <dl>
                <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 bg-gray-50">
                    <dt class="text-sm font-medium text-gray-500">Username</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $user->username }}</dd>
                </div>
                <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Email address</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $user->email }}</dd>
                </div>
                <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 bg-gray-50">
                    <dt class="text-sm font-medium text-gray-500">Phone number</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $user->phone }}</dd>
                </div>
                @if($user->bio)
                <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Bio</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $user->bio }}</dd>
                </div>
                @endif
            </dl>
        </div>

        <!-- Activity Section -->
        <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
            <h3 class="text-lg font-medium text-gray-900">Recent Activity</h3>
            <div class="mt-4 space-y-4">
                <!-- Upcoming Reservations -->
                <div>
                    <h4 class="text-sm font-medium text-gray-500">Upcoming Reservations</h4>
                    @php
                        $upcomingReservations = $user->reservations()
                            ->where('date', '>=', now())
                            ->where('status', '!=', 'cancelled')
                            ->with('service')
                            ->latest('date')
                            ->take(3)
                            ->get();
                    @endphp

                    @if($upcomingReservations->isEmpty())
                        <p class="mt-2 text-sm text-gray-500">No upcoming reservations</p>
                    @else
                        <div class="mt-2 divide-y divide-gray-200">
                            @foreach($upcomingReservations as $reservation)
                                <div class="py-3">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ $reservation->service->name }}</p>
                                            <p class="text-sm text-gray-500">
                                                {{ date('F j, Y', strtotime($reservation->date)) }} •
                                                {{ date('g:i A', strtotime($reservation->session_from)) }} -
                                                {{ date('g:i A', strtotime($reservation->session_to)) }}
                                            </p>
                                        </div>
                                        <span @class([
                                            'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                                            'bg-yellow-100 text-yellow-800' => $reservation->status === 'pending',
                                            'bg-green-100 text-green-800' => $reservation->status === 'confirmed',
                                        ])>
                                            {{ ucfirst($reservation->status) }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach

                            <div class="pt-4">
                                <a href="{{ route('reservations.index') }}" class="text-sm font-medium text-pink-600 hover:text-pink-500">
                                    View all reservations →
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
