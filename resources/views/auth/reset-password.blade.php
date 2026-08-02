<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SyncBin - Reset Password</title>

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
<body class="antialiased bg-gradient-to-br from-rose-100 via-pink-50 to-orange-50 dark:from-zinc-950 dark:via-zinc-900 dark:to-zinc-950 selection:bg-rose-200 selection:text-rose-900 min-h-screen flex items-center justify-center p-6 transition-colors duration-300">
    
    <!-- Decorative Background Ambient Glows -->
    <div class="fixed top-0 right-0 w-96 h-96 bg-rose-200/30 dark:bg-rose-900/10 blur-[100px] rounded-full -mr-48 -mt-48 pointer-events-none"></div>
    <div class="fixed bottom-0 left-0 w-96 h-96 bg-orange-200/30 dark:bg-orange-900/10 blur-[100px] rounded-full -ml-48 -mb-48 pointer-events-none"></div>

    <div class="w-full max-w-md opacity-0 animate-fade-in-up relative z-10">
        
        <!-- Brand Header with Logo -->
        <div class="flex flex-col items-center mb-8">
            <div class="w-16 h-16 bg-white dark:bg-zinc-900 rounded-3xl flex items-center justify-center shadow-lg shadow-rose-100/50 dark:shadow-none border border-rose-100 dark:border-zinc-800 mb-3 transition-transform hover:scale-105 duration-300">
                <img src="{{ asset('favicon.svg') }}" alt="System Logo" class="w-10 h-10 object-contain">
            </div>
            <h1 class="text-3xl font-black text-rose-950 dark:text-zinc-50 tracking-tight">SyncBin</h1>
        </div>

        <!-- Form Container -->
        <div class="bg-white/80 dark:bg-zinc-900/80 backdrop-blur-2xl rounded-[2.5rem] p-8 sm:p-10 shadow-2xl border border-white/60 dark:border-zinc-800">
            <div class="mb-8 text-center sm:text-left">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-zinc-50 mb-2">Set New Password</h2>
                <p class="text-gray-600 dark:text-zinc-400 text-sm leading-relaxed">
                    Please choose a strong new password for your SyncBin account.
                </p>
            </div>

            <!-- Session Status Alert -->
            <x-auth-session-status class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 rounded-2xl border border-emerald-100 dark:border-emerald-900/50 text-sm font-medium" :status="session('status')" />

            <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
                @csrf

                <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- Email Input -->
                <div class="opacity-0 animate-fade-in-up delay-100">
                    <label for="email" class="block text-sm font-semibold text-gray-700 dark:text-zinc-300 mb-1.5 ml-1">Email Address</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 dark:text-zinc-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <input type="email" id="email" name="email" value="{{ old('email', $request->email) }}" placeholder="you@example.com" required autofocus readonly
                               class="w-full pl-11 pr-4 py-3.5 border border-gray-200 dark:border-zinc-700 rounded-2xl focus:ring-4 focus:ring-rose-100 dark:focus:ring-rose-950 focus:border-rose-400 outline-none transition-all placeholder:text-gray-300 dark:placeholder:text-zinc-600 bg-gray-50/80 dark:bg-zinc-800/80 text-gray-900 dark:text-zinc-100">
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-2 ml-1" />
                </div>

                <!-- New Password with Live Requirements Tracker -->
                <div class="opacity-0 animate-fade-in-up delay-100" x-data="{
                    pass: '',
                    get hasLength() { return this.pass.length >= 12 },
                    get hasMixed() { return /[a-z]/.test(this.pass) && /[A-Z]/.test(this.pass) },
                    get hasNumber() { return /[0-9]/.test(this.pass) },
                    get hasSymbol() { return /[^a-zA-Z0-9]/.test(this.pass) }
                }">
                    <label for="password" class="block text-sm font-semibold text-gray-700 dark:text-zinc-300 mb-1.5 ml-1">New Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 dark:text-zinc-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <input type="password" id="password" name="password" x-model="pass" placeholder="••••••••••••" required autocomplete="new-password"
                               class="w-full pl-11 pr-4 py-3.5 border border-gray-200 dark:border-zinc-700 rounded-2xl focus:ring-4 focus:ring-rose-100 dark:focus:ring-rose-950 focus:border-rose-400 outline-none transition-all placeholder:text-gray-300 dark:placeholder:text-zinc-600 bg-white/60 dark:bg-zinc-800/60 text-gray-900 dark:text-zinc-100">
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-2 ml-1" />

                    <!-- Visible Interactive Requirements Checklist Box -->
                    <div class="mt-3 p-4 bg-rose-50/70 dark:bg-zinc-800/70 border border-rose-100 dark:border-zinc-700/80 rounded-2xl space-y-2 text-xs font-semibold text-gray-600 dark:text-zinc-400">
                        <span class="block text-[11px] font-black uppercase tracking-wider text-rose-950 dark:text-zinc-200 mb-1 flex items-center gap-1.5">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="text-rose-500"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>
                            Password Requirements:
                        </span>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-1.5 text-[11px]">
                            <div class="flex items-center gap-1.5 transition-colors" :class="hasLength ? 'text-emerald-600 dark:text-emerald-400 font-bold' : 'text-gray-500 dark:text-zinc-400'">
                                <span class="font-mono text-xs" x-text="hasLength ? '✓' : '•'"></span> At least 12 characters
                            </div>
                            <div class="flex items-center gap-1.5 transition-colors" :class="hasMixed ? 'text-emerald-600 dark:text-emerald-400 font-bold' : 'text-gray-500 dark:text-zinc-400'">
                                <span class="font-mono text-xs" x-text="hasMixed ? '✓' : '•'"></span> Upper & lowercase letters
                            </div>
                            <div class="flex items-center gap-1.5 transition-colors" :class="hasNumber ? 'text-emerald-600 dark:text-emerald-400 font-bold' : 'text-gray-500 dark:text-zinc-400'">
                                <span class="font-mono text-xs" x-text="hasNumber ? '✓' : '•'"></span> At least one number
                            </div>
                            <div class="flex items-center gap-1.5 transition-colors" :class="hasSymbol ? 'text-emerald-600 dark:text-emerald-400 font-bold' : 'text-gray-500 dark:text-zinc-400'">
                                <span class="font-mono text-xs" x-text="hasSymbol ? '✓' : '•'"></span> At least one symbol (!@#$)
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Confirm Password -->
                <div class="opacity-0 animate-fade-in-up delay-200">
                    <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 dark:text-zinc-300 mb-1.5 ml-1">Confirm New Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 dark:text-zinc-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <input type="password" id="password_confirmation" name="password_confirmation" placeholder="••••••••" required autocomplete="new-password"
                               class="w-full pl-11 pr-4 py-3.5 border border-gray-200 dark:border-zinc-700 rounded-2xl focus:ring-4 focus:ring-rose-100 dark:focus:ring-rose-950 focus:border-rose-400 outline-none transition-all placeholder:text-gray-300 dark:placeholder:text-zinc-600 bg-white/60 dark:bg-zinc-800/60 text-gray-900 dark:text-zinc-100">
                    </div>
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 ml-1" />
                </div>

                <!-- Submit Button -->
                <div class="opacity-0 animate-fade-in-up delay-300 pt-2">
                    <button type="submit" class="w-full bg-rose-500 hover:bg-rose-600 text-white font-bold py-4 rounded-2xl transition-all shadow-lg shadow-rose-200 dark:shadow-none hover:shadow-rose-300 active:scale-[0.98] hover:-translate-y-0.5">
                        Reset Password & Sign In
                    </button>
                </div>

                <!-- Back to Login Link -->
                <div class="pt-2 text-center opacity-0 animate-fade-in-up delay-300">
                    <a href="{{ route('login') }}" class="text-sm font-bold text-rose-500 hover:text-rose-600 dark:text-rose-400 dark:hover:text-rose-300 transition-colors inline-flex items-center gap-2 group">
                        <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        Cancel and Return to Login
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
