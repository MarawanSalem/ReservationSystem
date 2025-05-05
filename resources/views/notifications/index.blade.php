@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Notifications
                </h3>
                @if($notifications->count() > 0)
                    <form action="{{ route('notifications.markAllAsRead') }}" method="POST" class="inline">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm font-medium rounded-md text-pink-700 bg-pink-100 hover:bg-pink-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500">
                            Mark all as read
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <div class="divide-y divide-gray-200">
            @forelse($notifications as $notification)
                <div class="p-4 hover:bg-gray-50 {{ $notification->read_at ? 'bg-white' : 'bg-pink-50' }}">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 {{ $notification->read_at ? 'text-gray-400' : 'text-pink-600' }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                        </div>
                        <div class="ml-3 w-0 flex-1">
                            <p class="text-sm font-medium text-gray-900">
                                {{ $notification->data['title'] ?? 'Notification' }}
                            </p>
                            <p class="mt-1 text-sm text-gray-500">
                                {{ $notification->data['message'] ?? '' }}
                            </p>
                            <div class="mt-2 flex items-center text-xs text-gray-500">
                                <time datetime="{{ $notification->created_at }}">
                                    {{ $notification->created_at->diffForHumans() }}
                                </time>
                                @if(!$notification->read_at)
                                    <form action="{{ route('notifications.markAsRead', $notification) }}" method="POST" class="ml-4">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="text-pink-600 hover:text-pink-900">
                                            Mark as read
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-4 text-center text-gray-500">
                    No notifications found.
                </div>
            @endforelse
        </div>

        <div class="px-4 py-4 sm:px-6 border-t border-gray-200">
            {{ $notifications->links() }}
        </div>
    </div>
</div>
@endsection
