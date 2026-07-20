<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SyncBin - Confirm MFA</title>

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
<body class="antialiased selection:bg-rose-200 selection:text-rose-900">
    <div class="min-h-screen w-full flex items-center justify-center bg-gradient-to-br from-rose-100 via-pink-50 to-orange-50 p-6">
        
        <!-- Background Glow -->
        <div class="fixed top-0 right-0 w-96 h-96 bg-rose-200/20 blur-[100px] rounded-full -mr-48 -mt-48 pointer-events-none"></div>
        <div class="fixed bottom-0 left-0 w-96 h-96 bg-orange-200/20 blur-[100px] rounded-full -ml-48 -mb-48 pointer-events-none"></div>

        <div class="w-full max-w-md opacity-0 animate-fade-in-up">
            <!-- Brand Logo (Strict AGENT_RULES.md Compliance: asset('favicon.svg')) -->
            <div class="flex flex-col items-center mb-8">
                <div class="w-16 h-16 bg-white rounded-3xl flex items-center justify-center shadow-md border border-rose-100 mb-3 transition-transform hover:scale-105 duration-300">
                    <img src="{{ asset('favicon.svg') }}" alt="System Logo" class="w-10 h-10 object-contain">
                </div>
                <h1 class="text-3xl font-black text-rose-950 tracking-tight">SyncBin</h1>
            </div>

            <!-- Confirmation Card -->
            <div class="bg-white/80 backdrop-blur-2xl rounded-[2.5rem] p-8 sm:p-10 shadow-2xl border border-white/60">
                <div class="mb-8 text-center">
                    <div class="w-12 h-12 bg-rose-100 text-rose-600 rounded-2xl flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 002-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-black text-rose-950 mb-2">Two-Factor Security Check</h2>
                    <p class="text-xs font-bold text-rose-600/80 leading-relaxed">
                        Please enter the 6-digit verification code from your authenticator app to access your account.
                    </p>
                </div>

                <form method="POST" action="{{ route('2fa.confirm') }}" class="space-y-6">
                    @csrf

                    <div>
                        <input type="text" name="code" placeholder="123456" maxlength="6" required autofocus
                               class="w-full text-center text-3xl font-mono tracking-[0.4em] font-black px-4 py-4 border border-rose-200 rounded-2xl focus:ring-4 focus:ring-rose-100 focus:border-rose-500 outline-none transition-all placeholder:text-gray-300 bg-white/70">
                        <x-input-error :messages="$errors->get('code')" class="mt-2 text-center" />
                    </div>

                    <div>
                        <button type="submit" class="w-full bg-rose-950 hover:bg-rose-900 text-white font-black py-4 rounded-2xl transition-all shadow-lg hover:-translate-y-0.5 active:translate-y-0 text-sm">
                            Verify Code & Continue
                        </button>
                    </div>
                </form>

                <div class="mt-8 pt-6 border-t border-rose-100 text-center">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-xs font-bold text-gray-500 hover:text-rose-600 transition-colors uppercase tracking-wider">
                            Logout / Switch Account
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
