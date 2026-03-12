<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', config('app.name', 'SILAPOR')) - {{ config('app.name', 'SILAPOR') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-100"
          x-data="{
              sidebarOpen: window.innerWidth >= 1024,
              isMobile: window.innerWidth < 1024,
              init() {
                  window.addEventListener('resize', () => {
                      const wasMobile = this.isMobile;
                      this.isMobile = window.innerWidth < 1024;
                      if (!this.isMobile && wasMobile) {
                          this.sidebarOpen = true;
                      }
                  });
              }
          }">
        <!-- Mobile Overlay -->
        <div x-show="sidebarOpen && isMobile"
             @click="sidebarOpen = false"
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-30 bg-gray-900 bg-opacity-50"
             style="display: none;"></div>

        <!-- Sidebar -->
        <x-sidebar />

        <!-- Main Content Area -->
        <div class="min-h-screen transition-all duration-300"
             :class="{ 'lg:ml-64': sidebarOpen && !isMobile, 'ml-0': !sidebarOpen || isMobile }"
             id="main-content">
            <!-- Topbar -->
            <x-topbar :title="view()->yieldContent('title', 'Dashboard')" />

            <!-- Page Content -->
            <main class="p-6">
                @yield('content')
            </main>
        </div>

        <!-- Scripts Stack -->
        @stack('scripts')
    </body>
</html>
