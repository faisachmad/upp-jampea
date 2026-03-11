<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', config('app.name', 'SILAPOR'))</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-100">
        <!-- Sidebar -->
        <x-sidebar />

        <!-- Main Content Area -->
        <div class="ml-64 min-h-screen">
            <!-- Topbar -->
            <x-topbar title="@yield('title', config('app.name', 'SILAPOR'))" />

            <!-- Page Content -->
            <main class="p-6">
                @yield('content')
            </main>
        </div>

        <!-- Scripts Stack -->
        @stack('scripts')
    </body>
</html>
