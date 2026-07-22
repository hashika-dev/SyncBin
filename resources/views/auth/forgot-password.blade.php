<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SyncBin - Forgot Password</title>

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- Dark Mode Detection -->
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>

    <style>
        body {
            font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.8s ease-out forwards;
        }

        .delay-100 { animation-delay: 0.1s; }
        .delay-200 { animation-delay: 0.2s; }
        .delay-300 { animation-delay: 0.3s; }
    </style>
</head>
<body class="antialiased bg-gradient-to-br from-rose-100 via-pink-50 to-orange-50 dark:from-zinc-950 dark:via-zinc-900 dark:to-zinc-950 selection:bg-rose-200 selection:text-rose-900 min-h-screen flex items-center justify-center p-6 transition-colors duration-300">
    
    <!-- Decorative Background Ambient Glows -->
    <div class="fixed top-0 right-0 w-96 h-96 bg-rose-200/30 dark:bg-rose-900/10 blur-[100px] rounded-full -mr-48 -mt-48 pointer-events-none"></div>
    <div class="fixed bottom-0 left-0 w-96 h-96 bg-orange-200/30 dark:bg-orange-900/10 blur-[100px] rounded-full -ml-48 -mb-48 pointer-events-none"></div>

    <div class="w-full max-w-md opacity-0 animate-fade-in-up relative z-10">
        
        <!-- Brand Header with Logo (Strict AGENT_RULES.md Compliance) -->
        <div class="flex flex-col items-center mb-8">
            <div class="w-16 h-16 bg-white dark:bg-zinc-900 rounded-3xl flex items-center justify-center shadow-lg shadow-rose-100/50 dark:shadow-none border border-rose-100 dark:border-zinc-800 mb-3 transition-transform hover:scale-105 duration-300">
                <img src="{{ asset('favicon.svg') }}" alt="System Logo" class="w-10 h-10 object-contain">
            </div>
            <h1 class="text-3xl font-black text-rose-950 dark:text-zinc-50 tracking-tight">SyncBin</h1>
        </div>

        <!-- Form Container -->
        <div class="bg-white/80 dark:bg-zinc-900/80 backdrop-blur-2xl rounded-[2.5rem] p-8 sm:p-10 shadow-2xl border border-white/60 dark:border-zinc-800">
            <div class="mb-8 text-center sm:text-left">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-zinc-50 mb-2">Reset Password</h2>
                <p class="text-gray-600 dark:text-zinc-400 text-sm leading-relaxed">
                    Forgot your password? No problem. Enter your account email address and we'll send you a password reset link.
                </p>
            </div>

            <!-- Session Status Alert -->
            <x-auth-session-status class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 rounded-2xl border border-emerald-100 dark:border-emerald-900/50 text-sm font-medium" :status="session('status')" />

            <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                @csrf

                <!-- Email Input -->
                <div class="opacity-0 animate-fade-in-up delay-100">
                    <label for="email" class="block text-sm font-semibold text-gray-700 dark:text-zinc-300 mb-1.5 ml-1">Email Address</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 dark:text-zinc-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" required autofocus
                               class="w-full pl-11 pr-4 py-3.5 border border-gray-200 dark:border-zinc-700 rounded-2xl focus:ring-4 focus:ring-rose-100 dark:focus:ring-rose-950 focus:border-rose-400 outline-none transition-all placeholder:text-gray-300 dark:placeholder:text-zinc-600 bg-white/60 dark:bg-zinc-800/60 text-gray-900 dark:text-zinc-100">
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-2 ml-1" />
                </div>

                <!-- CAPTCHA -->
                @if(config('services.turnstile.key') && config('services.turnstile.key') !== '1x00000000000000000000AA')
                <div class="opacity-0 animate-fade-in-up delay-100 flex justify-center">
                    <div class="cf-turnstile" data-sitekey="{{ config('services.turnstile.key') }}" data-theme="auto"></div>
                </div>
                <x-input-error :messages="$errors->get('cf-turnstile-response')" class="mt-1 ml-1" />
                @endif

                <!-- Submit Button -->
                <div class="opacity-0 animate-fade-in-up delay-200">
                    <button type="submit" class="w-full bg-rose-500 hover:bg-rose-600 text-white font-bold py-4 rounded-2xl transition-all shadow-lg shadow-rose-200 dark:shadow-none hover:shadow-rose-300 active:scale-[0.98] hover:-translate-y-0.5">
                        Email Password Reset Link
                    </button>
                </div>

                <!-- Back to Login Link -->
                <div class="pt-2 text-center opacity-0 animate-fade-in-up delay-200">
                    <a href="{{ route('login') }}" class="text-sm font-bold text-rose-500 hover:text-rose-600 dark:text-rose-400 dark:hover:text-rose-300 transition-colors inline-flex items-center gap-2 group">
                        <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        Back to Login
                    </a>
                </div>
            </form>
        </div>

        <!-- Security Footer -->
        <p class="text-center text-[11px] text-gray-400 dark:text-zinc-600 uppercase tracking-widest mt-8 font-bold">
            Secure SyncBin Gateway
        </p>
    </div>
</body>
</html>
