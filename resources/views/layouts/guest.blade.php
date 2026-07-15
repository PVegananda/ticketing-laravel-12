<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Login</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    
    <!-- Tailwind & DaisyUI -->
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased text-gray-900 bg-gray-50">
    <!-- Background Design -->
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 relative overflow-hidden bg-gradient-to-br from-blue-50 via-white to-indigo-50">
        
        <!-- Decorative Background Shapes -->
        <div class="absolute top-0 right-0 w-96 h-96 bg-blue-100 rounded-full blur-3xl transform translate-x-1/3 -translate-y-1/3 opacity-60 pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-96 h-96 bg-indigo-100 rounded-full blur-3xl transform -translate-x-1/3 translate-y-1/3 opacity-60 pointer-events-none"></div>

        <!-- Logo -->
        <div class="mb-8 z-10 relative text-center">
            <a href="/" class="flex flex-col items-center group">
                <div class="w-16 h-16 bg-blue-600 rounded-2xl shadow-lg flex items-center justify-center transform group-hover:scale-105 transition-transform duration-300">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                    </svg>
                </div>
                <h1 class="mt-4 text-3xl font-extrabold text-gray-800 tracking-tight">
                    eTicketing Admin
                </h1>
            </a>
        </div>

        <!-- Auth Card -->
        <div class="w-full sm:max-w-md px-8 py-10 bg-white/90 backdrop-blur-xl shadow-2xl overflow-hidden sm:rounded-3xl border border-white/50 z-10 relative">
            {{ $slot }}
        </div>
        
        <!-- Footer Info -->
        <div class="mt-8 text-sm text-gray-500 z-10">
            &copy; {{ date('Y') }} eTicketing Admin. All rights reserved.
        </div>
        
    </div>
</body>

</html>