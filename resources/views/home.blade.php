@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-pink-500 to-purple-600 rounded-lg shadow-lg overflow-hidden">
        <div class="px-4 py-16 sm:px-6 sm:py-24 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl font-extrabold tracking-tight text-white sm:text-5xl lg:text-6xl">
                    Book Your Perfect Service
                </h1>
                <p class="mt-6 max-w-lg mx-auto text-xl text-pink-100">
                    Discover and book the best services in your area. From beauty treatments to wellness services, we've got you covered.
                </p>
            </div>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="bg-white rounded-lg shadow-sm p-4">
        <div class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <input type="text" placeholder="Search services..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-pink-500 focus:border-pink-500">
            </div>
            <div class="flex gap-2">
                <select class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-pink-500 focus:border-pink-500">
                    <option>All Categories</option>
                    <option>Beauty</option>
                    <option>Wellness</option>
                    <option>Fitness</option>
                </select>
                <select class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-pink-500 focus:border-pink-500">
                    <option>Sort by</option>
                    <option>Price: Low to High</option>
                    <option>Price: High to Low</option>
                    <option>Rating</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Services Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($services as $service)
            <x-service-card :service="$service" />
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $services->links() }}
    </div>
</div>
@endsection
