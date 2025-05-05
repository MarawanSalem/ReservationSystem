@props(['service'])

<div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow duration-300">
    <div class="relative">
        <img src="{{ $service->image ?? 'https://via.placeholder.com/400x300' }}" alt="{{ $service->name }}" class="w-full h-48 object-cover">
        <div class="absolute top-2 right-2">
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-pink-100 text-pink-800">
                {{ $service->duration }} min
            </span>
        </div>
    </div>
    <div class="p-4">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900">{{ $service->name }}</h3>
            <span class="text-lg font-bold text-pink-600">${{ number_format($service->price, 2) }}</span>
        </div>
        <p class="mt-2 text-sm text-gray-500">{{ Str::limit($service->description, 100) }}</p>
        <div class="mt-4 flex items-center justify-between">
            <div class="flex items-center">
                <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
                <span class="ml-1 text-sm text-gray-600">{{ number_format($service->rating, 1) }}</span>
            </div>
            <a href="{{ route('services.show', $service) }}" class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-pink-600 hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500">
                Book Now
            </a>
        </div>
    </div>
</div>
