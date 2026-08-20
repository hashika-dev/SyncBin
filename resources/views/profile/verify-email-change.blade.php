<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>EcoSync - Confirm Email Change</title>

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
            <h1 class="text-3xl font-black text-rose-950 dark:text-zinc-50 tracking-tight">EcoSync</h1>
            <p class="text-xs font-bold text-rose-600/80 dark:text-zinc-400 mt-1 uppercase tracking-widest">Email Change Verification</p>
        </div>

        <!-- Verification Card -->
        <div class="bg-white/80 dark:bg-zinc-900/80 backdrop-blur-2xl rounded-[2.5rem] p-8 sm:p-10 shadow-2xl border border-white/60 dark:border-zinc-800">
            <div class="mb-8 text-center">
                <div class="w-14 h-14 bg-rose-100 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-sm border border-rose-200/50 dark:border-rose-900/40">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-zinc-50 mb-2">Verify New Email</h2>
                <p class="text-xs font-semibold text-gray-600 dark:text-zinc-400 leading-relaxed">
                    We sent a 6-digit OTP verification code to:<br>
                    <span class="font-mono text-rose-600 dark:text-rose-400 font-bold text-sm inline-block mt-1 bg-rose-50 dark:bg-rose-950/50 px-3 py-1 rounded-xl border border-rose-100 dark:border-rose-900/50">{{ session('pending_email_change.email', $pendingEmail ?? '') }}</span>
                </p>
            </div>

            @if(session('status'))
                <div class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 rounded-2xl text-xs font-semibold text-center border border-emerald-100 dark:border-emerald-900/50">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('profile.confirm-email-change') }}" class="space-y-6">
                @csrf

                <div class="opacity-0 animate-fade-in-up delay-100">
                    <label for="code" class="block text-xs font-bold text-gray-700 dark:text-zinc-300 uppercase tracking-wider mb-2 text-center">
                        Enter 6-Digit Code
                    </label>
                    <input type="text" id="code" name="code" placeholder="123456" maxlength="6" required autofocus
                           class="w-full text-center text-3xl font-mono tracking-[0.4em] font-black px-4 py-4 border border-gray-200 dark:border-zinc-700 rounded-2xl focus:ring-4 focus:ring-rose-100 dark:focus:ring-rose-950 focus:border-rose-400 outline-none transition-all placeholder:text-gray-300 dark:placeholder:text-zinc-700 bg-white/70 dark:bg-zinc-800/70 text-gray-900 dark:text-zinc-100 shadow-inner">
                    <x-input-error :messages="$errors->get('code')" class="mt-2 text-center" />
                </div>

                <div class="opacity-0 animate-fade-in-up delay-200">
                    <button type="submit" class="w-full bg-rose-500 hover:bg-rose-600 text-white font-bold py-4 rounded-2xl transition-all shadow-lg shadow-rose-200 dark:shadow-none hover:shadow-rose-300 active:scale-[0.98] hover:-translate-y-0.5 text-sm flex items-center justify-center gap-2">
                        <span>Confirm & Update Email</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </button>
                </div>
            </form>

            <div class="mt-6 pt-6 border-t border-gray-100 dark:border-zinc-800/80 text-center opacity-0 animate-fade-in-up delay-300">
                <a href="{{ route('profile.edit') }}" class="text-xs font-bold text-gray-500 hover:text-rose-600 dark:text-zinc-400 dark:hover:text-rose-400 transition-colors uppercase tracking-wider inline-flex items-center gap-1.5 group">
                    <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Cancel and Back to Profile
                </a>
            </div>
        </div>

        <!-- Security Footer -->
        <p class="text-center text-[11px] text-gray-400 dark:text-zinc-600 uppercase tracking-widest mt-8 font-bold">
            Protected by EcoSync Security Engine
        </p>
    </div>
</body>
</html>
