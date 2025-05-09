@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-12">
    <div class="md:grid md:grid-cols-3 md:gap-6">
        <div class="md:col-span-1">
            <div class="px-4 sm:px-0">
                <h3 class="text-lg font-medium text-gray-900">Profile Information</h3>
                <p class="mt-1 text-sm text-gray-600">
                    Update your account's profile information and email address.
                </p>
            </div>
        </div>

        <div class="mt-5 md:mt-0 md:col-span-2">
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="shadow-sm rounded-md overflow-hidden">
                    <div class="px-4 py-5 bg-white sm:p-6">
                        <div class="grid grid-cols-6 gap-6">
                            <!-- Profile Photo -->
                            <div class="col-span-6 sm:col-span-4">
                                <label class="block text-sm font-medium text-gray-700">
                                    Profile Photo
                                </label>
                                <div class="mt-2 flex items-center space-x-4">
                                    <div class="relative h-20 w-20 rounded-full overflow-hidden bg-gray-100">
                                        @if(auth()->user()->image && !str_contains(auth()->user()->image, 'ui-avatars'))
                                            <img src="{{ Storage::url(auth()->user()->image) }}"
                                                 alt="{{ auth()->user()->name }}"
                                                 class="h-full w-full object-cover">
                                        @else
                                            <div class="h-full w-full flex items-center justify-center bg-pink-100 text-pink-600 text-xl font-bold">
                                                {{ substr(auth()->user()->name, 0, 2) }}
                                            </div>
                                        @endif

                                        <label for="image" class="absolute inset-0 w-full h-full bg-black bg-opacity-50 flex items-center justify-center text-white opacity-0 hover:opacity-100 transition-opacity cursor-pointer">
                                            <span class="sr-only">Change</span>
                                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                        </label>
                                    </div>
                                    <input type="file"
                                           id="image"
                                           name="image"
                                           class="hidden"
                                           accept="image/*">
                                    @if(auth()->user()->image && !str_contains(auth()->user()->image, 'ui-avatars'))
                                        <button type="button"
                                                onclick="document.getElementById('remove_image').value = '1'"
                                                class="text-sm text-red-600 hover:text-red-500">
                                            Remove Photo
                                        </button>
                                        <input type="hidden" name="remove_image" id="remove_image" value="0">
                                    @endif
                                </div>
                                @error('image')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Name -->
                            <div class="col-span-6 sm:col-span-4">
                                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                                <input type="text"
                                       name="name"
                                       id="name"
                                       value="{{ old('name', auth()->user()->name) }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Username -->
                            <div class="col-span-6 sm:col-span-4">
                                <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                                <input type="text"
                                       name="username"
                                       id="username"
                                       value="{{ old('username', auth()->user()->username) }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500">
                                @error('username')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="col-span-6 sm:col-span-4">
                                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email"
                                       name="email"
                                       id="email"
                                       value="{{ old('email', auth()->user()->email) }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500">
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Phone -->
                            <div class="col-span-6 sm:col-span-4">
                                <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                                <input type="tel"
                                       name="phone"
                                       id="phone"
                                       value="{{ old('phone', auth()->user()->phone) }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500">
                                @error('phone')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Bio -->
                            <div class="col-span-6 sm:col-span-4">
                                <label for="bio" class="block text-sm font-medium text-gray-700">Bio</label>
                                <textarea name="bio"
                                          id="bio"
                                          rows="3"
                                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500">{{ old('bio', auth()->user()->bio) }}</textarea>
                                @error('bio')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                        <button type="submit"
                                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-pink-600 hover:bg-pink-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500">
                            Save Changes
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="fixed bottom-4 right-4">
            <div class="bg-green-50 text-green-800 rounded-lg p-4 shadow-lg border border-green-200">
                {{ session('success') }}
            </div>
        </div>
    @endif
</div>
@endsection
