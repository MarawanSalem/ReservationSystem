<!-- User Profile Dropdown -->
<div class="relative ml-3" x-data="{ open: false }">
    <div>
        <button @click="open = !open"
                class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition duration-150"
                id="user-menu-button">
            <img class="h-8 w-8 rounded-full object-cover"
                 src="{{ auth()->user()->avatar_url }}"
                 alt="{{ auth()->user()->name }}">
        </button>
    </div>

    <div x-show="open"
         @click.away="open = false"
         class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5"
         role="menu"
         aria-orientation="vertical"
         aria-labelledby="user-menu-button"
         tabindex="-1">

        <div class="px-4 py-2 text-xs text-gray-400">
            {{ auth()->user()->name }}
        </div>

        <a href="{{ route('profile.edit') }}"
           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
           role="menuitem">
            Profile Settings
        </a>

        <a href="{{ route('reservations.index') }}"
           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
           role="menuitem">
            My Reservations
        </a>

        @role('admin')
        <a href="{{ route('admin.dashboard') }}"
           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
           role="menuitem">
            Admin Dashboard
        </a>
        @endrole

        <div class="border-t border-gray-100"></div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                    role="menuitem">
                Sign out
            </button>
        </form>
    </div>
</div>
