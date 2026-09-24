<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>EcoSync - Forgot Password</title>

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

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @if(config('services.turnstile.key') && config('services.turnstile.key') !== '1x00000000000000000000AA')
        <link rel="preconnect" href="https://challenges.cloudflare.com" crossorigin>
        <link rel="dns-prefetch" href="https://challenges.cloudflare.com">
        <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
    @endif

    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif; }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(16px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        .delay-100 { animation-delay: 0.1s; }
        .delay-200 { animation-delay: 0.2s; }
    </style>
</head>
<body class="antialiased bg-gradient-to-br from-emerald-100/60 via-slate-100 to-teal-100/40 dark:from-[#030906] dark:via-[#06120D] dark:to-[#040C09] text-slate-800 dark:text-slate-100 selection:bg-emerald-500 selection:text-white min-h-screen flex flex-col justify-between p-4 sm:p-6 transition-colors duration-300 relative isolate overflow-x-hidden"
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

    <!-- Top Bar with Theme Toggle -->
    <header class="w-full max-w-md mx-auto flex items-center justify-end relative z-10 pt-2 sm:pt-4">
        <button @click="toggleTheme()" 
                type="button" 
                class="p-2 rounded-xl bg-white/70 dark:bg-white/[0.04] border border-white/80 dark:border-white/10 text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white backdrop-blur-md transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20"
                title="Toggle Theme" 
                aria-label="Toggle Color Theme">
            <svg x-show="!isDark" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-amber-500"><circle cx="12" cy="12" r="4"/><path d="M12 2v2"/><path d="M12 20v2"/><path d="m4.93 4.93 1.41 1.41"/><path d="m17.66 17.66 1.41 1.41"/><path d="M2 12h2"/><path d="M20 12h2"/><path d="m6.34 17.66-1.41 1.41"/><path d="m19.07 4.93-1.41 1.41"/></svg>
            <svg x-show="isDark" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-slate-300"><path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"/></svg>
        </button>
    </header>
    
    <div class="w-full max-w-md mx-auto my-auto opacity-0 animate-fade-in-up relative z-10 py-4">
        
        <!-- Brand Header with Logo -->
        <div class="flex flex-col items-center mb-6 text-center">
            <a href="/" class="flex flex-col items-center group">
                <div class="w-14 h-14 bg-white/70 dark:bg-emerald-500/15 rounded-2xl flex items-center justify-center shadow-sm border border-white/80 dark:border-emerald-400/30 mb-3 group-hover:scale-105 transition-transform backdrop-blur-md">
                    <img src="{{ asset('favicon.svg') }}" alt="System Logo" class="w-9 h-9 object-contain">
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight drop-shadow-sm">EcoSync</h1>
            </a>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-mono font-bold uppercase tracking-wider bg-white/70 dark:bg-emerald-500/15 border border-white/80 dark:border-emerald-400/30 text-emerald-800 dark:text-emerald-300 mt-2 backdrop-blur-md shadow-sm">
                Password Recovery
            </span>
        </div>

        <!-- Frosted Acrylic Glass Card Container -->
        <div class="relative rounded-3xl p-7 sm:p-9 bg-white/55 dark:bg-[#0B1713]/60 border border-white/80 dark:border-emerald-500/25 backdrop-blur-2xl shadow-[0_10px_30px_-5px_rgba(16,185,129,0.1)] dark:shadow-[0_16px_40px_-10px_rgba(0,0,0,0.6)] overflow-hidden">
            <!-- Top Specular Glass Reflection Line -->
            <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-white dark:via-emerald-400/40 to-transparent pointer-events-none"></div>

            <div class="mb-6 text-center sm:text-left">
                <h2 class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-white mb-1.5 drop-shadow-sm">Reset Password</h2>
                <p class="text-slate-600 dark:text-slate-400 text-xs sm:text-sm leading-relaxed">
                    Forgot your password? Enter your email address and we'll dispatch a secure recovery link.
                </p>
            </div>

            <!-- Session Status Alert -->
            <x-auth-session-status class="mb-5 p-3.5 bg-emerald-500/15 dark:bg-emerald-500/20 text-emerald-900 dark:text-emerald-200 rounded-2xl border border-emerald-400/40 text-xs font-semibold backdrop-blur-md" :status="session('status')" />

            <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
                @csrf

                <!-- Email Input -->
                <div class="opacity-0 animate-fade-in-up delay-100">
                    <label for="email" class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1.5">Email Address</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 dark:text-slate-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" required autofocus
                               class="w-full pl-10 pr-4 py-2.5 border border-white/80 dark:border-white/10 rounded-xl focus:ring-4 focus:ring-emerald-500/15 focus:border-emerald-500 outline-none transition-all placeholder:text-slate-400 dark:placeholder:text-slate-500 bg-white/70 dark:bg-white/[0.04] text-xs font-medium text-slate-900 dark:text-slate-100 shadow-sm backdrop-blur-md">
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-1.5 text-xs text-rose-500" />
                </div>

                <!-- CAPTCHA -->
                @if(config('services.turnstile.key') && config('services.turnstile.key') !== '1x00000000000000000000AA')
                <div class="opacity-0 animate-fade-in-up delay-100 flex justify-center pt-1">
                    <div class="cf-turnstile" data-sitekey="{{ config('services.turnstile.key') }}" data-theme="auto"></div>
                </div>
                <x-input-error :messages="$errors->get('cf-turnstile-response')" class="mt-1 ml-1 text-xs text-rose-500" />
                @endif

                <!-- Submit Button -->
                <div class="opacity-0 animate-fade-in-up delay-200 pt-2">
                    <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-500 active:bg-emerald-700 text-white font-mono font-bold text-xs uppercase tracking-wider py-3 rounded-xl transition-all shadow-[0_4px_20px_rgba(16,185,129,0.25)] hover:shadow-[0_6px_24px_rgba(16,185,129,0.35)] flex items-center justify-center gap-2">
                        Email Password Reset Link
                    </button>
                </div>

                <!-- Back to Login Link -->
                <div class="pt-2 text-center opacity-0 animate-fade-in-up delay-200">
                    <a href="{{ route('login') }}" class="text-xs font-mono font-bold uppercase tracking-wider text-emerald-700 hover:text-emerald-800 dark:text-emerald-400 dark:hover:text-emerald-300 transition-colors inline-flex items-center gap-2 group">
                        <svg class="w-3.5 h-3.5 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        Back to Login
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Security Footer -->
    <footer class="w-full text-center text-xs text-slate-500 dark:text-slate-400 font-mono py-4 relative z-10">
        EcoSync Automated Telemetry &bull; High-Assurance Waste Infrastructure
    </footer>
</body>
</html>
