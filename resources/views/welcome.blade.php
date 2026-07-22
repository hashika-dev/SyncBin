<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SyncBin - Login</title>

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

        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-10px) rotate(2deg); }
        }

        @keyframes float-slow {
            0%, 100% { transform: translate(0, 0); }
            33% { transform: translate(10px, -15px); }
            66% { transform: translate(-15px, 10px); }
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

        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-10px) rotate(2deg); }
        }

        .animate-float {
            animation: float 3s ease-in-out infinite;
        }

        .animate-float-slow {
            animation: float-slow 8s ease-in-out infinite;
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.8s ease-out forwards;
        }

        .delay-100 { animation-delay: 0.1s; }
        .delay-200 { animation-delay: 0.2s; }
        .delay-300 { animation-delay: 0.3s; }
    </style>
</head>
<body class="antialiased bg-white dark:bg-zinc-950 selection:bg-rose-200 selection:text-rose-900 transition-colors duration-300">
    <div class="flex flex-col lg:flex-row min-h-screen w-full">
        <!-- Left Panel -->
        <div class="hidden lg:flex lg:w-1/3 bg-gradient-to-br from-rose-100 via-pink-50 to-orange-50 dark:from-zinc-900 dark:via-zinc-800 dark:to-zinc-900 text-rose-950 dark:text-zinc-50 p-12 flex-col justify-between relative border-r border-rose-100 dark:border-zinc-800 transition-colors duration-300">
            <!-- Decorative Floating Elements -->
            <div class="absolute top-20 left-10 w-32 h-32 bg-rose-300/20 blur-2xl rounded-full animate-float-slow"></div>
            <div class="absolute bottom-40 right-10 w-40 h-40 bg-orange-300/20 blur-3xl rounded-full animate-float-slow delay-500"></div>
            
            <!-- Top left: Logo -->
            <div class="flex items-center gap-3 relative z-10">
                <img src="{{ asset('favicon.svg') }}" alt="System Logo" class="w-8 h-8 object-contain animate-float">
                <span class="text-2xl font-bold tracking-tight text-rose-900 dark:text-zinc-50">SyncBin</span>
            </div>

            <!-- Middle: Description & Animation -->
            <div class="max-w-xl relative z-10 flex flex-col gap-12">
                <p class="text-xl leading-relaxed font-medium opacity-90 text-rose-900/80 dark:text-zinc-300">
                    Simplify your segregation process. Automated bin tracking, real-time alerts, and comprehensive insights in a minimalist workspace. Keep your environment clean and organized.
                </p>
                
                <!-- SyncBin in Action Graphic -->
                <div class="flex flex-col py-4">
                    <x-sorting-graphic />
                </div>
            </div>

            <!-- Bottom: Carousel Indicator -->
            <div class="flex gap-3 relative z-10">
                <div class="w-8 h-2 rounded-full bg-rose-400 transition-all duration-300"></div>
                <div class="w-2 h-2 rounded-full bg-rose-200 transition-all duration-300"></div>
                <div class="w-2 h-2 rounded-full bg-rose-200 transition-all duration-300"></div>
            </div>
        </div>

        <!-- Right Panel (Form) -->
        <div class="w-full lg:w-2/3 bg-white dark:bg-zinc-900 flex flex-col justify-center items-center p-6 sm:p-12 lg:p-16 transition-colors duration-300 min-h-screen">
            <div class="w-full max-w-md opacity-0 animate-fade-in-up">
                <!-- Mobile Branding Header (Visible < lg) -->
                <div class="flex lg:hidden items-center gap-3 mb-8">
                    <img src="{{ asset('favicon.svg') }}" alt="System Logo" class="w-8 h-8 object-contain animate-float">
                    <span class="text-2xl font-black tracking-tight text-rose-950 dark:text-zinc-50">SyncBin</span>
                </div>

                <!-- Form Header -->
                <div class="mb-8 sm:mb-10">
                    <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 dark:text-zinc-50">Welcome back</h2>
                    <p class="text-rose-600 dark:text-rose-400 font-semibold mt-2 text-sm sm:text-base">Sign in to your SyncBin account to continue.</p>
                </div>

                <!-- Login Form -->
                <form action="{{ route('login.post') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <!-- Email Field -->
                    <div class="opacity-0 animate-fade-in-up delay-100">
                        <label for="email" class="block text-sm font-semibold text-gray-700 dark:text-zinc-300 mb-1.5 ml-1">Email Address</label>
                        <input type="email" id="email" name="email" placeholder="you@example.com" value="{{ old('email') }}" required
                               class="w-full px-4 py-3.5 border border-gray-200 dark:border-zinc-700 rounded-xl focus:ring-4 focus:ring-rose-100 focus:border-rose-400 outline-none transition-all placeholder:text-gray-300 dark:placeholder:text-zinc-650 bg-gray-50/50 dark:bg-zinc-800/50 hover:bg-white dark:hover:bg-zinc-800 focus:bg-white dark:focus:bg-zinc-800 text-gray-900 dark:text-zinc-100">
                        <x-input-error :messages="$errors->get('email')" class="mt-2 ml-1" />
                    </div>

                    <!-- Password Field -->
                    <div class="opacity-0 animate-fade-in-up delay-200">
                        <label for="password" class="block text-sm font-semibold text-gray-700 dark:text-zinc-300 mb-1.5 ml-1">Password</label>
                        <div class="relative group">
                            <input type="password" id="password" name="password" placeholder="........" required
                                   class="w-full px-4 py-3.5 border border-gray-200 dark:border-zinc-700 rounded-xl focus:ring-4 focus:ring-rose-100 focus:border-rose-400 outline-none transition-all placeholder:text-gray-300 dark:placeholder:text-zinc-650 bg-gray-50/50 dark:bg-zinc-800/50 hover:bg-white dark:hover:bg-zinc-800 focus:bg-white dark:focus:bg-zinc-800 text-gray-900 dark:text-zinc-100">
                            <button type="button" id="togglePassword" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-rose-500 focus:outline-none transition-colors">
                                <svg id="eyeIconOpen" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                <svg id="eyeIconClosed" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye-off hidden"><path d="M9.88a3 3 0 1 0 4.24 4.24"/><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/><path d="M6.61 6.61A13.52 13.52 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/><line x1="2" x2="22" y1="2" y2="22"/></svg>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2 ml-1" />
                    </div>

                    <!-- Form Actions -->
                    <div class="flex items-center justify-between opacity-0 animate-fade-in-up delay-300">
                        <div class="flex items-center">
                            <input type="checkbox" id="remember" name="remember" class="h-4.5 w-4.5 text-rose-500 focus:ring-rose-400 border-gray-300 rounded-md cursor-pointer transition-colors">
                            <label for="remember" class="ml-2 block text-sm text-gray-600 dark:text-zinc-400 font-medium cursor-pointer">Remember me</label>
                        </div>
                        <a href="{{ route('password.request') }}" class="text-sm font-bold text-rose-500 hover:text-rose-600 transition-colors">Forgot password?</a>
                    </div>

                    <!-- CAPTCHA -->
                    <div class="opacity-0 animate-fade-in-up delay-300">
                        <div class="cf-turnstile" data-sitekey="{{ config('services.turnstile.key') ?: '1x00000000000000000000AA' }}" data-theme="light"></div>
                        <x-input-error :messages="$errors->get('cf-turnstile-response')" class="mt-2 ml-1" />
                    </div>

                    <!-- Submit Button -->
                    <div class="opacity-0 animate-fade-in-up delay-300">
                        <button type="submit" class="w-full bg-rose-500 hover:bg-rose-600 text-white font-bold py-4 rounded-xl transition-all shadow-lg shadow-rose-200 hover:shadow-rose-300 active:scale-[0.98] mt-2">
                            Sign In
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const togglePassword = document.querySelector('#togglePassword');
            const password = document.querySelector('#password');
            const eyeOpen = document.querySelector('#eyeIconOpen');
            const eyeClosed = document.querySelector('#eyeIconClosed');

            togglePassword.addEventListener('click', function (e) {
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);
                eyeOpen.classList.toggle('hidden');
                eyeClosed.classList.toggle('hidden');
            });
        });
    </script>
</body>
</html>
