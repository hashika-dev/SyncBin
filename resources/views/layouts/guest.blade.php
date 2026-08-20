<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Favicon -->
        <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Dark Mode Detection -->
        <script>
            if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        </script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 dark:text-zinc-100 antialiased bg-gray-100 dark:bg-zinc-950 transition-colors duration-300">
        <div class="min-h-screen flex flex-col sm:justify-center items-center p-4 sm:p-6 pt-8 sm:pt-0 bg-gradient-to-br from-rose-50 via-white to-orange-50/50 dark:from-zinc-950 dark:via-zinc-900 dark:to-zinc-950">
            <div class="flex flex-col items-center">
                <a href="/" class="flex items-center gap-3">
                    <img src="{{ asset('favicon.svg') }}" alt="EcoSync Logo" class="w-12 h-12">
                    <span class="text-3xl font-black text-rose-950 dark:text-zinc-50 tracking-tight">EcoSync</span>
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-8 bg-white/80 dark:bg-zinc-900/80 backdrop-blur-xl shadow-xl dark:shadow-none border border-white dark:border-zinc-800 rounded-3xl">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
