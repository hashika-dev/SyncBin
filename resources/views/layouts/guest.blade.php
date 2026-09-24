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
            body { font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif; }
        </style>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased text-slate-800 dark:text-slate-100 bg-gradient-to-br from-emerald-100/60 via-slate-100 to-teal-100/40 dark:from-[#030906] dark:via-[#06120D] dark:to-[#040C09] min-h-screen selection:bg-emerald-500 selection:text-white transition-colors duration-300 relative isolate overflow-x-hidden flex flex-col justify-between"
          x-data="{
              isDark: document.documentElement.classList.contains('dark'),
              toggleTheme() {
                  this.isDark = !this.isDark;
                  if (this.isDark) {
                      document.documentElement.classList.add('dark');
                      localStorage.theme = 'dark';
                  } else {
                      document.documentElement.classList.remove('dark');
                      localStorage.theme = 'light';
                  }
              }
          }">
        
        <!-- VIBRANT BACKLIGHT AURORA FOR REAL GLASS REFRACTION -->
        <div class="pointer-events-none absolute inset-0 overflow-hidden select-none z-0">
            <!-- Center Emerald Glow -->
            <div class="absolute -top-32 left-1/2 -translate-x-1/2 w-[750px] h-[500px] bg-emerald-400/35 dark:bg-emerald-500/20 blur-[130px] rounded-full"></div>
            <!-- Left Cyan/Teal Orb -->
            <div class="absolute top-1/3 -left-32 w-[550px] h-[550px] bg-teal-400/30 dark:bg-teal-600/15 blur-[140px] rounded-full"></div>
            <!-- Right Emerald/Sage Orb -->
            <div class="absolute -bottom-24 right-10 w-[600px] h-[500px] bg-emerald-500/30 dark:bg-emerald-600/15 blur-[140px] rounded-full"></div>
            <!-- Fine Dot Grid Matrix -->
            <div class="absolute inset-0 bg-[radial-gradient(#059669_1.2px,transparent_1.2px)] dark:bg-[radial-gradient(#10b981_1px,transparent_1px)] [background-size:26px_26px] opacity-25 dark:opacity-[0.08]"></div>
        </div>

        <!-- Top Navigation / Theme Toggle Bar -->
        <header class="w-full max-w-5xl mx-auto px-6 pt-6 flex items-center justify-between relative z-10">
            <a href="/" class="flex items-center gap-2.5 group">
                <div class="w-9 h-9 rounded-xl bg-white/70 dark:bg-emerald-500/15 border border-white/80 dark:border-emerald-400/30 flex items-center justify-center p-1.5 shadow-sm group-hover:scale-105 transition-transform">
                    <img src="{{ asset('favicon.svg') }}" alt="System Logo" class="w-full h-full object-contain">
                </div>
                <div class="leading-tight">
                    <span class="text-base font-bold tracking-tight text-slate-900 dark:text-white block">EcoSync</span>
                    <span class="text-[10px] font-mono uppercase tracking-wider text-emerald-600 dark:text-emerald-400 font-semibold block">Waste Intelligence</span>
                </div>
            </a>

            <!-- Theme Toggle Button -->
            <button @click="toggleTheme()" 
                    type="button" 
                    class="p-2 rounded-xl bg-white/70 dark:bg-white/[0.04] border border-white/80 dark:border-white/10 text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white backdrop-blur-md transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20"
                    title="Toggle Theme" 
                    aria-label="Toggle Color Theme">
                <svg x-show="!isDark" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-amber-500"><circle cx="12" cy="12" r="4"/><path d="M12 2v2"/><path d="M12 20v2"/><path d="m4.93 4.93 1.41 1.41"/><path d="m17.66 17.66 1.41 1.41"/><path d="M2 12h2"/><path d="M20 12h2"/><path d="m6.34 17.66-1.41 1.41"/><path d="m19.07 4.93-1.41 1.41"/></svg>
                <svg x-show="isDark" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-slate-300"><path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"/></svg>
            </button>
        </header>

        <!-- Main Form Slot Container -->
        <main class="w-full max-w-md mx-auto my-auto p-4 sm:p-6 relative z-10">
            <!-- Frosted Acrylic Glass Card Container -->
            <div class="relative rounded-3xl p-6 sm:p-8 bg-white/55 dark:bg-[#0B1713]/60 border border-white/80 dark:border-emerald-500/25 backdrop-blur-2xl shadow-[0_10px_30px_-5px_rgba(16,185,129,0.1)] dark:shadow-[0_16px_40px_-10px_rgba(0,0,0,0.6)] overflow-hidden">
                <!-- Top Specular Glass Reflection Line -->
                <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-white dark:via-emerald-400/40 to-transparent pointer-events-none"></div>

                {{ $slot }}
            </div>
        </main>

        <!-- Footer -->
        <footer class="w-full text-center text-xs text-slate-500 dark:text-slate-400 font-mono py-6 relative z-10">
            &copy; {{ date('Y') }} EcoSync Systems &bull; High-Assurance Waste Infrastructure
        </footer>
    </body>
</html>
