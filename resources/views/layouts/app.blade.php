<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'EcoSync') }}</title>

        <!-- Favicon -->
        <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Dark Mode Detection -->
        <script>
            if (localStorage.theme === 'light') {
                document.documentElement.classList.remove('dark');
            } else {
                document.documentElement.classList.add('dark');
            }
        </script>

        <style>
            [x-cloak] { display: none !important; }
            html { scroll-behavior: smooth; }
            body { font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif; }
            
            /* Custom sleek scrollbars */
            ::-webkit-scrollbar {
                width: 6px;
                height: 6px;
            }
            ::-webkit-scrollbar-track {
                background: transparent;
            }
            ::-webkit-scrollbar-thumb {
                background: #243046;
                border-radius: 9999px;
            }
            ::-webkit-scrollbar-thumb:hover {
                background: #334460;
            }
        </style>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased text-slate-900 dark:text-slate-100 bg-slate-100 dark:bg-[#0B0F17] min-h-screen transition-colors duration-300">
        <div class="min-h-screen flex flex-col bg-slate-100 dark:bg-[#0B0F17] text-slate-900 dark:text-slate-100">
            @unless(isset($hideNav))
                @include('layouts.navigation')
            @endunless

            <!-- Main Page Content -->
            <main class="flex-1 w-full">
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
