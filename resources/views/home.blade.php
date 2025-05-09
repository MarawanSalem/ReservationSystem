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
        <form action="{{ route('home') }}" method="GET" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <input type="text"
                       name="search"
                       placeholder="Search services..."
                       value="{{ request('search') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-pink-500 focus:border-pink-500">
            </div>
            <div class="flex gap-2">
                <select name="category"
                        class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-pink-500 focus:border-pink-500">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category }}" {{ request('category') === $category ? 'selected' : '' }}>
                            {{ $category }}
                        </option>
                    @endforeach
                </select>
                <select name="sort"
                        class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-pink-500 focus:border-pink-500">
                    <option value="rating" {{ request('sort') === 'rating' ? 'selected' : '' }}>By Rating</option>
                    <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                    <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                </select>
                <button type="submit"
                        class="px-6 py-2 bg-pink-600 text-white rounded-lg hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-pink-500">
                    Search
                </button>
            </div>
        </form>
    </div>

    <!-- Services Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($services as $service)
            <x-service-card :service="$service" />
        @empty
            <div class="col-span-3 text-center py-12">
                <h3 class="text-lg font-medium text-gray-900">No services found</h3>
                <p class="mt-2 text-sm text-gray-500">Try adjusting your search or filter criteria</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $services->withQueryString()->links() }}
    </div>
</div>
@endsection
