<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', config('app.name', 'SAPOJAM')) - {{ config('app.name', 'SAPOJAM') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles Stack -->
        @stack('styles')

        <!-- Favicon -->
        <link rel="icon" type="image/png" href="{{ asset('icon.png') }}">

        <!-- SweetAlert2 -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        
        <style>
            .swal2-popup {
                font-family: 'Figtree', sans-serif !important;
                border-radius: 1rem !important;
            }
            .swal2-title {
                font-weight: 700 !important;
            }
            /* Row Stacking Fix for Dropdown Menus */
            .dataTables_wrapper table tbody tr {
                position: relative;
                z-index: 1;
            }
            .dataTables_wrapper table tbody tr:hover,
            .dataTables_wrapper table tbody tr:focus-within {
                z-index: 100 !important;
                position: relative !important;
            }
        </style>
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

        <!-- Loading Screen -->
        <x-loading-screen />

        <!-- Notification Handler -->
        <x-sweet-alert />

        <script>
            // Global Confirmation Handler
            window.confirmDelete = function(form, message = 'Yakin ingin menghapus data ini?') {
                Swal.fire({
                    title: 'Konfirmasi Hapus',
                    text: message,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            }
        </script>

        <!-- Scripts Stack -->
        @stack('scripts')
    </body>
</html>
