<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>EcoSync - Setup MFA</title>

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

        .delay-100 { animation-delay: 0.1s; }
        .delay-200 { animation-delay: 0.2s; }
    </style>
</head>
<body class="antialiased bg-slate-100 dark:bg-slate-950 text-slate-900 dark:text-slate-100 selection:bg-emerald-500 selection:text-white min-h-screen flex items-center justify-center p-6 transition-colors duration-300">
    <div class="min-h-screen w-full flex items-center justify-center p-4 sm:p-6">
        <div class="w-full max-w-2xl opacity-0 animate-fade-in-up">
            <!-- Brand Logo -->
            <div class="flex flex-col items-center mb-8">
                <div class="w-16 h-16 bg-white dark:bg-slate-900 rounded-2xl flex items-center justify-center shadow-sm border border-slate-200 dark:border-slate-800 mb-3 transition-transform hover:scale-105 duration-300">
                    <img src="{{ asset('favicon.svg') }}" alt="EcoSync Logo" class="w-10 h-10 object-contain">
                </div>
                <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">EcoSync</h1>
                <p class="text-xs font-mono font-bold text-emerald-600 dark:text-emerald-400 mt-1 uppercase tracking-widest">Multi-Factor Authentication</p>
            </div>

            <!-- Setup Card -->
            <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 sm:p-10 shadow-xl border border-slate-200 dark:border-slate-800">
                <div class="mb-8 text-center">
                    <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-1.5">Two-Factor Authentication Setup</h2>
                    <p class="text-slate-500 dark:text-slate-400 text-xs sm:text-sm leading-relaxed max-w-md mx-auto">
                        Protect your EcoSync account with Google Authenticator or any TOTP authenticator app.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8 items-center">
                    <!-- Step 1: QR Code -->
                    <div class="flex flex-col items-center gap-3 bg-slate-50 dark:bg-slate-950 p-5 rounded-2xl border border-slate-200 dark:border-slate-800">
                        <div class="p-3 bg-white rounded-xl shadow-sm border border-slate-200 w-44 h-44 flex items-center justify-center [&>svg]:w-full [&>svg]:h-full overflow-hidden">
                            {!! $qrCode !!}
                        </div>
                        <div class="text-center space-y-0.5">
                            <span class="text-[10px] font-mono font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-widest block">Step 1</span>
                            <p class="text-xs font-semibold text-slate-700 dark:text-slate-300">Scan with Authenticator App</p>
                        </div>
                    </div>

                    <!-- Step 2: Verification Form -->
                    <div class="space-y-5">
                        <div class="text-center md:text-left">
                            <span class="text-[10px] font-mono font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-widest block mb-1">Step 2</span>
                            <p class="text-xs font-semibold text-slate-700 dark:text-slate-300">Enter the 6-digit verification code:</p>
                        </div>

                        <form method="POST" action="{{ route('2fa.enable') }}" class="space-y-4">
                            @csrf
                            
                            <div>
                                <input type="text" name="code" placeholder="123456" maxlength="6" required autofocus
                                       class="w-full text-center text-2xl font-mono tracking-[0.3em] font-bold px-4 py-3 border border-slate-200 dark:border-slate-800 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all placeholder:text-slate-400 bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100">
                                <x-input-error :messages="$errors->get('code')" class="mt-1.5 text-center" />
                            </div>

                            <div>
                                <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-500 active:bg-emerald-700 text-white font-mono font-bold text-xs uppercase tracking-wider py-3.5 rounded-xl transition-all shadow-sm">
                                    Verify & Enable MFA
                                </button>
                            </div>
                        </form>

                        <div class="pt-3 text-center md:text-left border-t border-slate-200 dark:border-slate-800 space-y-1.5">
                            <p class="text-[11px] text-slate-500 dark:text-slate-400 font-medium">
                                Manual setup key: <span class="font-mono text-slate-900 dark:text-slate-200 font-bold select-all">{{ $secret }}</span>
                            </p>
                            <form method="POST" action="{{ route('2fa.reset') }}">
                                @csrf
                                <button type="submit" class="text-[10px] font-mono font-bold text-emerald-600 hover:text-emerald-500 dark:text-emerald-400 dark:hover:text-emerald-300 uppercase tracking-wider">
                                    🔄 Generate Fresh QR Code
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cancel -->
            <div class="text-center mt-6">
                <a href="{{ route('dashboard') }}" class="text-xs font-mono font-bold text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200 transition-colors uppercase tracking-wider">
                    ← Back to Dashboard
                </a>
            </div>
        </div>
    </div>
</body>
</html>
