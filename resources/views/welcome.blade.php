<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>EcoSync - Sign In</title>

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

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @if(config('services.recaptcha.enabled') && config('services.recaptcha.key'))
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @endif
</head>
<body class="antialiased min-h-screen bg-slate-50 dark:bg-[#070B12] text-slate-900 dark:text-slate-100 flex flex-col lg:flex-row selection:bg-emerald-500 selection:text-white transition-colors duration-200"
      x-data="{
          submitting: false,
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

    <!-- Left Column: HD Low-Opacity Photographic Showcase (Visible on lg screens and up) -->
    <aside class="hidden lg:flex lg:w-1/2 xl:w-5/12 bg-slate-950 border-r border-slate-800/80 flex-col justify-between p-10 xl:p-14 text-white relative overflow-hidden select-none">
        
        <!-- Project Hardware Smart Station Image with Low Opacity & Gradient Overlay -->
        <img src="{{ asset('images/ecosync_hardware_bin.jpg') }}" alt="EcoSync Automated Waste Station Hardware" class="absolute inset-0 w-full h-full object-cover object-center opacity-55 filter brightness-95 contrast-105 pointer-events-none">
        <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/45 to-slate-950/70 pointer-events-none"></div>

        <!-- Top Header / Brand -->
        <div class="relative z-10">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-white/10 backdrop-blur-md border border-white/20 flex items-center justify-center p-2 shadow-sm">
                    <img src="{{ asset('favicon.svg') }}" alt="System Logo" class="w-full h-full object-contain">
                </div>
                <div>
                    <span class="text-base font-bold tracking-tight text-white block">EcoSync</span>
                    <span class="text-[10px] font-mono tracking-wider uppercase text-emerald-400 font-semibold block">Waste Intelligence System</span>
                </div>
            </div>
        </div>

        <!-- Middle: 4-Stream Smart Waste Headline & Stream Tags -->
        <div class="relative z-10 my-auto max-w-md">
            <h1 class="text-3xl xl:text-4xl font-extrabold tracking-tight text-white leading-tight mb-6">
                Automated sorting across all four waste streams.
            </h1>

            <!-- The 4 System Streams Matching Database Categories -->
            <div class="flex flex-wrap items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-emerald-950/70 border border-emerald-500/40 text-emerald-300 text-xs font-mono font-medium backdrop-blur-sm">
                    <span class="w-2 h-2 rounded-full bg-emerald-400"></span> Bio
                </span>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-sky-950/70 border border-sky-500/40 text-sky-300 text-xs font-mono font-medium backdrop-blur-sm">
                    <span class="w-2 h-2 rounded-full bg-sky-400"></span> Recyclable
                </span>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-amber-950/70 border border-amber-500/40 text-amber-300 text-xs font-mono font-medium backdrop-blur-sm">
                    <span class="w-2 h-2 rounded-full bg-amber-400"></span> Non-Bio
                </span>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-rose-950/70 border border-rose-500/40 text-rose-300 text-xs font-mono font-medium backdrop-blur-sm">
                    <span class="w-2 h-2 rounded-full bg-rose-400"></span> Hazard
                </span>
            </div>
        </div>

        <!-- Bottom: Status & Version -->
        <div class="relative z-10 pt-6 border-t border-white/10 flex items-center justify-between text-xs text-slate-400 font-mono">
            <span>EcoSync Operational Station</span>
            <span class="text-slate-500">v4.2.1</span>
        </div>
    </aside>

    <!-- Right Column: Clean Sign-In Form -->
    <main class="flex-1 flex flex-col justify-between min-h-screen relative p-6 sm:p-10 lg:p-14">
        
        <!-- Top Bar: Mobile Logo & Theme Toggle -->
        <div class="w-full flex items-center justify-between mb-6">
            <!-- Mobile Brand (only on screens < lg) -->
            <div class="lg:hidden flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-emerald-500/10 dark:bg-emerald-500/20 border border-emerald-500/30 flex items-center justify-center p-1.5">
                    <img src="{{ asset('favicon.svg') }}" alt="System Logo" class="w-full h-full object-contain">
                </div>
                <span class="text-base font-semibold tracking-tight text-slate-900 dark:text-white">EcoSync</span>
            </div>
            <div class="hidden lg:block"></div>

            <!-- Theme Toggle Button -->
            <button @click="toggleTheme()" type="button" class="inline-flex items-center justify-center w-9 h-9 rounded-lg border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:border-slate-300 dark:hover:border-slate-700 transition-colors shadow-sm" title="Toggle Theme" aria-label="Toggle theme">
                <svg x-show="!isDark" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-amber-500"><circle cx="12" cy="12" r="4"/><path d="M12 2v2"/><path d="M12 20v2"/><path d="m4.93 4.93 1.41 1.41"/><path d="m17.66 17.66 1.41 1.41"/><path d="M2 12h2"/><path d="M20 12h2"/><path d="m6.34 17.66-1.41 1.41"/><path d="m19.07 4.93-1.41 1.41"/></svg>
                <svg x-show="isDark" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-slate-300"><path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"/></svg>
            </button>
        </div>

        <!-- Center Form Card -->
        <div class="w-full max-w-sm sm:max-w-md mx-auto my-auto py-6">
            
            <!-- Form Header -->
            <div class="mb-7">
                <span class="inline-block text-[11px] font-mono font-semibold tracking-wider uppercase text-emerald-700 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/60 px-2.5 py-1 rounded-md border border-emerald-200 dark:border-emerald-800/50 mb-3">
                    Portal Authentication
                </span>
                <h2 class="text-2xl sm:text-3xl font-bold tracking-tight text-slate-900 dark:text-white">
                    Sign in to EcoSync
                </h2>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1 leading-relaxed">
                    Access facility management, telemetry, and reporting.
                </p>
            </div>

            <!-- Session Status Alert -->
            <x-auth-session-status class="mb-5 p-3.5 bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 rounded-xl border border-emerald-200 dark:border-emerald-800 text-xs font-medium" :status="session('status')" />

            <!-- Error Banner -->
            @if ($errors->any())
                <div class="mb-5 p-3.5 rounded-xl bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-800/60 text-red-700 dark:text-red-300 text-xs">
                    <ul class="list-disc list-inside space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST" @submit="submitting = true" class="space-y-4">
                @csrf
                
                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Email address</label>
                    <input type="email" id="email" name="email" placeholder="operator@ecosync.local" value="{{ old('email') }}" required autofocus autocomplete="username"
                           class="w-full px-3.5 py-2.5 border border-slate-300 dark:border-slate-800 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 outline-none transition-all placeholder:text-slate-400 dark:placeholder:text-slate-600 bg-white dark:bg-[#0D121B] text-slate-900 dark:text-white text-sm">
                    <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs text-red-500" />
                </div>

                <!-- Password Field -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password" class="block text-xs font-semibold text-slate-700 dark:text-slate-300">Password</label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-xs font-medium text-emerald-600 dark:text-emerald-400 hover:underline">Forgot password?</a>
                        @endif
                    </div>
                    <div class="relative">
                        <input type="password" id="password" name="password" placeholder="••••••••" required autocomplete="current-password"
                               class="w-full px-3.5 py-2.5 pr-10 border border-slate-300 dark:border-slate-800 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-600 outline-none transition-all placeholder:text-slate-400 dark:placeholder:text-slate-600 bg-white dark:bg-[#0D121B] text-slate-900 dark:text-white text-sm">
                        <button type="button" id="togglePassword" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200" aria-label="Toggle password visibility">
                            <svg id="eyeIconOpen" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                            <svg id="eyeIconClosed" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="hidden"><path d="M9.88a3 3 0 1 0 4.24 4.24"/><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/><path d="M6.61 6.61A13.52 13.52 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/><line x1="2" x2="22" y1="2" y2="22"/></svg>
                        </button>
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs text-red-500" />
                </div>

                <!-- Security Verification Check (CAPTCHA / Robot check) -->
                @if(config('services.recaptcha.enabled') && config('services.recaptcha.key'))
                    <div class="flex flex-col items-center justify-center pt-2">
                        <div class="g-recaptcha scale-90 sm:scale-100 origin-center" data-sitekey="{{ config('services.recaptcha.key') }}"></div>
                        <x-input-error :messages="$errors->get('g-recaptcha-response')" class="mt-1 text-center text-xs text-red-500" />
                    </div>
                @else
                    @php
                        $captchaQuestion = (new \App\Services\CaptchaService())->getQuestion();
                    @endphp
                    <div class="p-3 bg-slate-100 dark:bg-[#0D121B] border border-slate-200 dark:border-slate-800 rounded-xl flex items-center justify-between gap-3">
                        <div class="flex items-center gap-2.5">
                            <span class="w-4 h-4 rounded border border-slate-400 dark:border-slate-600 bg-white dark:bg-slate-900 flex items-center justify-center">
                                <span class="w-2 h-2 rounded-sm bg-emerald-500"></span>
                            </span>
                            <span class="text-xs text-slate-700 dark:text-slate-300 font-medium">Verify: <strong class="font-mono text-emerald-600 dark:text-emerald-400">{{ $captchaQuestion }}</strong> =</span>
                        </div>
                        <input type="text" id="captcha_input" name="captcha_input" placeholder="Answer" required
                               class="w-24 text-center font-mono font-bold text-xs py-1.5 border border-slate-300 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-900 text-slate-900 dark:text-white outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>
                    <x-input-error :messages="$errors->get('captcha_input')" class="mt-1 text-xs text-red-500" />
                @endif

                <!-- Remember Me Option -->
                <div class="flex items-center pt-1">
                    <label class="flex items-center gap-2 cursor-pointer select-none">
                        <input type="checkbox" id="remember" name="remember" class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-slate-300 dark:border-slate-700 rounded dark:bg-slate-950 cursor-pointer">
                        <span class="text-xs text-slate-600 dark:text-slate-400">Remember this device</span>
                    </label>
                </div>

                <!-- Sign In Submit Button -->
                <div class="pt-2">
                    <button type="submit" :disabled="submitting"
                            class="w-full bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white font-semibold py-2.5 px-4 rounded-xl transition-colors shadow-sm flex items-center justify-center gap-2 text-sm tracking-normal"
                            :class="submitting ? 'opacity-75 cursor-not-allowed' : ''">
                        <svg x-show="submitting" class="animate-spin h-4 w-4 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                        <span x-text="submitting ? 'Authenticating...' : 'Sign in'"></span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Footer Copyright -->
        <div class="w-full text-center text-xs text-slate-400 dark:text-slate-500 font-mono py-2">
            &copy; 2026 EcoSync Systems · v4.2.1
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const togglePassword = document.querySelector('#togglePassword');
            const password = document.querySelector('#password');
            const eyeOpen = document.querySelector('#eyeIconOpen');
            const eyeClosed = document.querySelector('#eyeIconClosed');

            if (togglePassword && password) {
                togglePassword.addEventListener('click', function () {
                    const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                    password.setAttribute('type', type);
                    eyeOpen.classList.toggle('hidden');
                    eyeClosed.classList.toggle('hidden');
                });
            }
        });
    </script>
</body>
</html>
