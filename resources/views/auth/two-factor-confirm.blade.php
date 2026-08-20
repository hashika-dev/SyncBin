<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>EcoSync - Confirm MFA</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

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
    </style>
</head>
<body class="antialiased bg-slate-100 dark:bg-slate-950 text-slate-900 dark:text-slate-100 selection:bg-emerald-500 selection:text-white min-h-screen flex items-center justify-center p-6 transition-colors duration-300">
    <div class="min-h-screen w-full flex items-center justify-center p-4 sm:p-6">
        <div class="w-full max-w-md opacity-0 animate-fade-in-up">
            <!-- Brand Logo -->
            <div class="flex flex-col items-center mb-8">
                <div class="w-16 h-16 bg-white dark:bg-slate-900 rounded-2xl flex items-center justify-center shadow-sm border border-slate-200 dark:border-slate-800 mb-3 transition-transform hover:scale-105 duration-300">
                    <img src="{{ asset('favicon.svg') }}" alt="System Logo" class="w-10 h-10 object-contain">
                </div>
                <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">EcoSync</h1>
                <p class="text-xs font-mono font-bold text-emerald-600 dark:text-emerald-400 mt-1 uppercase tracking-widest">Two-Factor Verification</p>
            </div>

            <!-- Confirmation Card -->
            <div class="bg-white dark:bg-slate-900 rounded-3xl p-8 sm:p-10 shadow-xl border border-slate-200 dark:border-slate-800">
                <div class="mb-8 text-center">
                    <div class="w-12 h-12 bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 rounded-2xl flex items-center justify-center mx-auto mb-3 border border-emerald-200 dark:border-emerald-800">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-1.5">Security Verification</h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                        Please enter the 6-digit verification code from your authenticator app to access your account.
                    </p>
                </div>

                <form method="POST" action="{{ route('2fa.confirm') }}" class="space-y-5">
                    @csrf

                    <div>
                        <input type="text" name="code" placeholder="123456" maxlength="6" required autofocus
                               class="w-full text-center text-2xl font-mono tracking-[0.3em] font-bold px-4 py-3 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all placeholder:text-slate-400 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100">
                        <x-input-error :messages="$errors->get('code')" class="mt-1.5 text-center" />
                    </div>

                    <div>
                        <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-500 active:bg-emerald-700 text-white font-mono font-bold text-xs uppercase tracking-wider py-3.5 rounded-xl transition-all shadow-sm">
                            Verify Code & Continue
                        </button>
                    </div>
                </form>

                <div class="mt-8 pt-6 border-t border-slate-200 dark:border-slate-800 text-center">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-xs font-mono font-bold text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200 transition-colors uppercase tracking-wider">
                            Logout / Switch Account
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
