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
<body class="antialiased min-h-screen bg-white dark:bg-[#0B0F17] text-slate-900 dark:text-slate-100 flex transition-colors duration-300"
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

    <div class="flex-1 flex flex-col lg:flex-row min-h-screen w-full relative">
        
        <!-- Top Right Theme Toggle -->
        <div class="absolute top-5 right-5 z-50">
            <button @click="toggleTheme()" type="button" class="p-2 rounded-xl bg-slate-100 dark:bg-slate-900/90 border border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white shadow-sm transition-all" title="Toggle Theme">
                <svg x-show="!isDark" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-amber-500"><circle cx="12" cy="12" r="4"/><path d="M12 2v2"/><path d="M12 20v2"/><path d="m4.93 4.93 1.41 1.41"/><path d="m17.66 17.66 1.41 1.41"/><path d="M2 12h2"/><path d="M20 12h2"/><path d="m6.34 17.66-1.41 1.41"/><path d="m19.07 4.93-1.41 1.41"/></svg>
                <svg x-show="isDark" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-slate-300"><path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"/></svg>
            </button>
        </div>

        <!-- Left Column: Branding Showcase (Figma Dark Forest Green) -->
        <div class="hidden lg:flex lg:w-5/12 xl:w-2/5 bg-[#0B3D20] flex-col items-center justify-center p-12 relative overflow-hidden text-center select-none">
            <!-- Background Ambient Glow -->
            <div class="absolute w-96 h-96 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col items-center max-w-sm">
                <!-- Concentric Radar/Beacon Rings -->
                <div class="relative w-64 h-64 flex items-center justify-center mb-8">
                    <div class="absolute inset-0 rounded-full border border-emerald-500/20 animate-pulse"></div>
                    <div class="absolute inset-6 rounded-full border border-emerald-500/30"></div>
                    <div class="absolute inset-14 rounded-full border border-emerald-400/40"></div>
                    
                    <!-- Center Logo Orb -->
                    <div class="relative w-16 h-16 rounded-full bg-emerald-900/90 border border-emerald-400/60 flex items-center justify-center shadow-2xl">
                        <img src="{{ asset('favicon.svg') }}" alt="System Logo" class="w-8 h-8 object-contain">
                    </div>
                </div>

                <h1 class="text-3xl font-extrabold text-white tracking-tight">EcoSync</h1>
                <span class="text-[10px] font-mono font-bold uppercase tracking-widest text-emerald-400 mt-2 block">
                    WASTE INTELLIGENCE SYSTEM
                </span>
                <p class="text-emerald-100/70 text-xs sm:text-sm mt-6 leading-relaxed">
                    Operational portal for facility managers and waste collection staff.
                </p>
            </div>
        </div>

        <!-- Right Column: Login Form -->
        <div class="flex-1 flex items-center justify-center p-6 sm:p-10 lg:p-16 bg-white dark:bg-[#0B0F17]">
            <div class="w-full max-w-md mx-auto">

                <!-- Mobile Logo (Shown only on small screens) -->
                <div class="lg:hidden flex items-center gap-3 mb-8">
                    <img src="{{ asset('favicon.svg') }}" alt="System Logo" class="w-8 h-8 object-contain">
                    <span class="text-xl font-bold text-slate-900 dark:text-white">EcoSync</span>
                </div>

                <!-- Header Title -->
                <div class="mb-6">
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">Welcome back</h2>
                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">Sign in to access the EcoSync portal.</p>
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

                <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
                    @csrf
                    
                    <!-- Email Address -->
                    <div>
                        <label for="email" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Email address</label>
                        <input type="email" id="email" name="email" placeholder="name@company.com" value="{{ old('email') }}" required autofocus autocomplete="username"
                               class="w-full px-3.5 py-2.5 border border-slate-300 dark:border-slate-800 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all placeholder:text-slate-400 dark:placeholder:text-slate-600 bg-white dark:bg-[#111622] text-slate-900 dark:text-white text-xs">
                        <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs text-red-500" />
                    </div>

                    <!-- Password Field -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="password" class="block text-xs font-semibold text-slate-700 dark:text-slate-300">Password</label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-xs font-semibold text-[#10A34E] dark:text-emerald-400 hover:underline">Forgot password?</a>
                            @endif
                        </div>
                        <div class="relative">
                            <input type="password" id="password" name="password" placeholder="••••••••" required autocomplete="current-password"
                                   class="w-full px-3.5 py-2.5 pr-10 border border-slate-300 dark:border-slate-800 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all placeholder:text-slate-400 dark:placeholder:text-slate-600 bg-white dark:bg-[#111622] text-slate-900 dark:text-white text-xs">
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
                        <div class="p-3 bg-slate-50 dark:bg-[#111622] border border-slate-200 dark:border-slate-800 rounded-xl flex items-center justify-between gap-3">
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
                            <span class="text-xs text-slate-600 dark:text-slate-400">Remember me for 30 days</span>
                        </label>
                    </div>

                    <!-- Sign In Submit Button (Bright Green from Figma) -->
                    <div class="pt-2">
                        <button type="submit" class="w-full bg-[#10A34E] hover:bg-[#0E8E43] text-white font-bold py-3 rounded-xl transition-all shadow-sm flex items-center justify-center gap-2 text-xs uppercase tracking-wider">
                            <span>Sign in</span>
                        </button>
                    </div>
                </form>

                <!-- Footer Copyright (From Figma) -->
                <p class="text-center text-[11px] text-slate-400 dark:text-slate-500 mt-8 font-mono">
                    &copy; 2026 EcoSync Systems · v4.2.1
                </p>
            </div>
        </div>
    </div>

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
