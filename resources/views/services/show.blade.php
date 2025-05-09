@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-12">
    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <!-- Service Header -->
        <div class="relative h-96">
            <img src="{{ $service->image ? Storage::url($service->image) : 'https://via.placeholder.com/800x400' }}"
                 alt="{{ $service->name }}"
                 class="w-full h-full object-cover">
            <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black to-transparent p-6">
                <h1 class="text-4xl font-bold text-white mb-2">{{ $service->name }}</h1>
                <div class="flex items-center text-white">
                    <span class="flex items-center">
                        <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        <span class="ml-1">{{ number_format($service->rating, 1) }}</span>
                    </span>
                    <span class="mx-2">•</span>
                    <span>{{ $service->duration }} min</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 p-6">
            <!-- Service Details -->
            <div class="lg:col-span-2 space-y-6">
                <div>
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">About this service</h2>
                    <p class="text-gray-600">{{ $service->description }}</p>
                </div>

                <div class="border-t pt-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Service Provider</h3>
                    <div class="flex items-center">
                        <div class="ml-4">
                            <p class="text-gray-900 font-medium">{{ $service->service_provider }}</p>
                            <p class="text-gray-500">{{ $service->location }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Booking Section -->
            <div class="lg:border-l lg:pl-8">
                <div class="bg-white p-6 rounded-lg border">
                    <div class="mb-4">
                        <div class="flex items-center justify-between">
                            <span class="text-2xl font-bold text-gray-900">${{ number_format($service->price, 2) }}</span>
                            <span class="text-sm text-gray-500">{{ $service->duration }} minutes</span>
                        </div>
                    </div>

                    <form action="{{ route('reservations.store', $service) }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label for="date" class="block text-sm font-medium text-gray-700">Select Date</label>
                            <input type="date"
                                   id="date"
                                   name="date"
                                   min="{{ date('Y-m-d') }}"
                                   value="{{ old('date', $date) }}"
                                   required
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500">
                            @error('date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="session_from" class="block text-sm font-medium text-gray-700">Start Time</label>
                                <select id="session_from"
                                        name="session_from"
                                        required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500">
                                    @foreach($availableSlots as $slot)
                                        <option value="{{ $slot['start'] }}" {{ old('session_from') == $slot['start'] ? 'selected' : '' }}>
                                            {{ date('g:i A', strtotime($slot['start'])) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('session_from')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="session_to" class="block text-sm font-medium text-gray-700">End Time</label>
                                <select id="session_to"
                                        name="session_to"
                                        required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500">
                                    @foreach($availableSlots as $slot)
                                        <option value="{{ $slot['end'] }}" {{ old('session_to') == $slot['end'] ? 'selected' : '' }}>
                                            {{ date('g:i A', strtotime($slot['end'])) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('session_to')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700">Special Requests (Optional)</label>
                            <textarea id="notes"
                                      name="notes"
                                      rows="3"
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500"
                                      placeholder="Any special requests or notes for the service provider...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        @if($errors->has('error'))
                            <div class="rounded-md bg-red-50 p-4">
                                <div class="flex">
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-red-800">{{ $errors->first('error') }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <button type="submit"
                                class="w-full bg-pink-600 text-white py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500">
                            Book Now
                        </button>
                    </form>

                    @if($availableSlots === [])
                        <div class="mt-4 text-sm text-red-600">
                            No available slots for this date. Please try another date.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('date').addEventListener('change', function() {
    window.location.href = "{{ route('services.show', $service) }}?date=" + this.value;
});
</script>
@endpush
@endsection
