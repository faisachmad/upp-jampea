<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Silapor UPP Jampea') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Outfit', sans-serif;
            margin: 0;
            padding: 0;
        }
        .auth-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            background-color: #0c1427;
            overflow: hidden;
        }
        .auth-background {
            position: absolute;
            inset: 0;
            background-image: url('{{ asset('images/auth-bg.png') }}');
            background-size: cover;
            background-position: center;
            filter: brightness(0.6) saturate(1.2);
            z-index: 1;
        }
        .auth-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(12, 20, 39, 0.9) 0%, rgba(12, 20, 39, 0.4) 100%);
            z-index: 2;
        }
        .auth-card {
            width: 100%;
            max-width: 450px;
            padding: 2.5rem;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 24px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            position: relative;
            z-index: 10;
            color: white;
            animation: slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .brand-logo {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 2rem;
        }
        .brand-name {
            font-size: 1.5rem;
            font-weight: 700;
            letter-spacing: -0.02em;
            margin-top: 1rem;
            text-transform: uppercase;
            color: #ffffff;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }
        .brand-subtitle {
            font-size: 0.75rem;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.6);
            letter-spacing: 0.1em;
            text-transform: uppercase;
        }
        /* Override Laravel Breeze defaults */
        .auth-card input {
            background: rgba(255, 255, 255, 0.05) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            color: white !important;
            border-radius: 12px !important;
            padding: 0.75rem 1rem !important;
        }
        .auth-card input:focus {
            border-color: #3b82f6 !important;
            ring-color: #3b82f6 !important;
            background: rgba(255, 255, 255, 0.1) !important;
        }
        .auth-card label {
            color: rgba(255, 255, 255, 0.8) !important;
            margin-bottom: 0.5rem !important;
            font-weight: 500 !important;
        }
        .auth-card button {
            background: linear-gradient(90deg, #2563eb 0%, #3b82f6 100%) !important;
            border: none !important;
            border-radius: 12px !important;
            padding: 0.75rem 1.5rem !important;
            font-weight: 600 !important;
            letter-spacing: 0.02em !important;
            transition: all 0.3s ease !important;
            box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.4) !important;
        }
        .auth-card button:hover {
            transform: translateY(-2px) !important;
            box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.5) !important;
        }
        .auth-card a {
            color: rgba(255, 255, 255, 0.6) !important;
            transition: color 0.3s ease !important;
        }
        .auth-card a:hover {
            color: white !important;
        }
    </style>
</head>
<body class="antialiased">
    <div class="auth-wrapper">
        <div class="auth-background"></div>
        <div class="auth-overlay"></div>
        
        <div class="auth-card">
            <div class="brand-logo">
                <a href="/">
                    <x-application-logo class="w-16 h-16 fill-current text-blue-500" />
                </a>
                <span class="brand-name">Silapor</span>
                <span class="brand-subtitle">UPP Kelas III Jampea</span>
            </div>

            {{ $slot }}
        </div>
    </div>
</body>
</html>
