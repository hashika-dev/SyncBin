<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>EcoSync - Reset Password</title>

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
<body class="antialiased bg-slate-100 dark:bg-slate-950 text-slate-900 dark:text-slate-100 selection:bg-emerald-500 selection:text-white min-h-screen flex items-center justify-center p-6 transition-colors duration-300">
    
    <div class="w-full max-w-md opacity-0 animate-fade-in-up relative z-10">
        
        <!-- Brand Header with Logo -->
        <div class="flex flex-col items-center mb-8">
            <div class="w-16 h-16 bg-white dark:bg-slate-900 rounded-2xl flex items-center justify-center shadow-sm border border-slate-200 dark:border-slate-800 mb-3 transition-transform hover:scale-105 duration-300">
                <img src="{{ asset('favicon.svg') }}" alt="System Logo" class="w-10 h-10 object-contain">
            </div>
            <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">EcoSync</h1>
            <p class="text-xs font-mono font-bold text-emerald-600 dark:text-emerald-400 mt-1 uppercase tracking-widest">Password Reset</p>
        </div>

        <!-- Form Container -->
        <div class="bg-white dark:bg-slate-900 rounded-3xl p-8 sm:p-10 shadow-xl border border-slate-200 dark:border-slate-800">
            <div class="mb-8 text-center sm:text-left">
                <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-1.5">Set New Password</h2>
                <p class="text-slate-500 dark:text-slate-400 text-xs sm:text-sm leading-relaxed">
                    Please choose a strong new password for your EcoSync account.
                </p>
            </div>

            <!-- Session Status Alert -->
            <x-auth-session-status class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 rounded-xl border border-emerald-200 dark:border-emerald-800 text-xs font-semibold" :status="session('status')" />

            <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
                @csrf

                <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- Email Input -->
                <div class="opacity-0 animate-fade-in-up delay-100">
                    <label for="email" class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-1.5">Email Address</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 dark:text-slate-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <input type="email" id="email" name="email" value="{{ old('email', $request->email) }}" placeholder="you@example.com" required autofocus readonly
                               class="w-full pl-10 pr-4 py-3 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all placeholder:text-slate-400 bg-slate-50/50 dark:bg-slate-950 text-xs font-medium text-slate-900 dark:text-slate-100">
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
                </div>

                <!-- New Password with Live Requirements Tracker -->
                <div class="opacity-0 animate-fade-in-up delay-100" x-data="{
                    pass: '',
                    get hasLength() { return this.pass.length >= 12 },
                    get hasMixed() { return /[a-z]/.test(this.pass) && /[A-Z]/.test(this.pass) },
                    get hasNumber() { return /[0-9]/.test(this.pass) },
                    get hasSymbol() { return /[^a-zA-Z0-9]/.test(this.pass) }
                }">
                    <label for="password" class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-1.5">New Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 dark:text-slate-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <input type="password" id="password" name="password" x-model="pass" placeholder="••••••••••••" required autocomplete="new-password"
                               class="w-full pl-10 pr-4 py-3 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all placeholder:text-slate-400 bg-slate-50/50 dark:bg-slate-950 text-xs font-medium text-slate-900 dark:text-slate-100">
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-1.5" />

                    <!-- Visible Interactive Requirements Checklist Box -->
                    <div class="mt-3 p-4 bg-slate-50 dark:bg-slate-950 rounded-xl border border-slate-200 dark:border-slate-800 text-xs font-semibold text-slate-600 dark:text-slate-400">
                        <span class="block font-mono font-bold uppercase tracking-wider text-[11px] text-slate-800 dark:text-slate-200 mb-1.5 flex items-center gap-1.5">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-emerald-500"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>
                            Password Requirements:
                        </span>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-1.5 text-[11px] font-mono">
                            <div class="flex items-center gap-1.5 transition-colors" :class="hasLength ? 'text-emerald-600 dark:text-emerald-400 font-bold' : 'text-slate-400 dark:text-slate-500'">
                                <span class="font-mono text-xs" x-text="hasLength ? '✓' : '•'"></span> At least 12 characters
                            </div>
                            <div class="flex items-center gap-1.5 transition-colors" :class="hasMixed ? 'text-emerald-600 dark:text-emerald-400 font-bold' : 'text-slate-400 dark:text-slate-500'">
                                <span class="font-mono text-xs" x-text="hasMixed ? '✓' : '•'"></span> Upper & lowercase letters
                            </div>
                            <div class="flex items-center gap-1.5 transition-colors" :class="hasNumber ? 'text-emerald-600 dark:text-emerald-400 font-bold' : 'text-slate-400 dark:text-slate-500'">
                                <span class="font-mono text-xs" x-text="hasNumber ? '✓' : '•'"></span> At least one number
                            </div>
                            <div class="flex items-center gap-1.5 transition-colors" :class="hasSymbol ? 'text-emerald-600 dark:text-emerald-400 font-bold' : 'text-slate-400 dark:text-slate-500'">
                                <span class="font-mono text-xs" x-text="hasSymbol ? '✓' : '•'"></span> At least one symbol (!@#$)
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Confirm Password -->
                <div class="opacity-0 animate-fade-in-up delay-200">
                    <label for="password_confirmation" class="block text-xs font-mono font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400 mb-1.5">Confirm New Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 dark:text-slate-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <input type="password" id="password_confirmation" name="password_confirmation" placeholder="••••••••••••" required autocomplete="new-password"
                               class="w-full pl-10 pr-4 py-3 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all placeholder:text-slate-400 bg-slate-50/50 dark:bg-slate-950 text-xs font-medium text-slate-900 dark:text-slate-100">
                    </div>
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1.5" />
                </div>

                <!-- Submit Button -->
                <div class="opacity-0 animate-fade-in-up delay-300 pt-1">
                    <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-500 active:bg-emerald-700 text-white font-mono font-bold text-xs uppercase tracking-wider py-3.5 rounded-xl transition-all shadow-sm flex items-center justify-center gap-2">
                        Reset Password & Sign In
                    </button>
                </div>

                <!-- Back to Login Link -->
                <div class="pt-2 text-center opacity-0 animate-fade-in-up delay-300">
                    <a href="{{ route('login') }}" class="text-xs font-mono font-bold uppercase tracking-wider text-emerald-600 hover:text-emerald-500 dark:text-emerald-400 dark:hover:text-emerald-300 transition-colors inline-flex items-center gap-2 group">
                        <svg class="w-3.5 h-3.5 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        Cancel and Return to Login
                    </a>
                </div>
            </form>
        </div>

        <!-- Security Footer -->
        <p class="text-center text-[10px] font-mono text-slate-400 dark:text-slate-600 uppercase tracking-widest mt-8 font-semibold">
            EcoSync Automated Telemetry &copy; {{ date('Y') }}
        </p>
    </div>
</body>
</html>
