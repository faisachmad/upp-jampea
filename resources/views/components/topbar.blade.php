<header class="bg-white shadow">
    <div class="py-4 px-4 sm:px-6 lg:px-8 flex justify-between items-center">
        <!-- Burger Menu & Page Title -->
        <div class="flex items-center space-x-3">
            <!-- Burger Menu Button -->
            <button @click="sidebarOpen = !sidebarOpen"
                    class="p-2 rounded-lg text-gray-600 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-200 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            <!-- Page Title -->
            <h1 class="text-xl sm:text-2xl font-bold text-gray-800">
                {{ $title ?? 'Dashboard' }}
            </h1>
        </div>

        <!-- User Dropdown -->
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open"
                    class="flex items-center space-x-2 text-gray-700 hover:text-gray-900 focus:outline-none">
                <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold">
                    {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                </div>
                <span class="hidden sm:block">{{ Auth::user()->name ?? 'Admin' }}</span>
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                </svg>
            </button>

            <!-- Dropdown Menu -->
            <div x-show="open"
                 @click.away="open = false"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="transform opacity-0 scale-95"
                 x-transition:enter-end="transform opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="transform opacity-100 scale-100"
                 x-transition:leave-end="transform opacity-0 scale-95"
                 class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50"
                 style="display: none;">

                <a href="{{ route('profile.edit') }}"
                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                    Profile
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
