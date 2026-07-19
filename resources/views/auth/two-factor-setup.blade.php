<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SyncBin - Setup MFA</title>

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
<body class="antialiased selection:bg-rose-200 selection:text-rose-900">
    <div class="min-h-screen w-full flex items-center justify-center bg-gradient-to-br from-rose-100 via-pink-50 to-orange-50 p-6">
        
        <!-- Decorative Background Shapes -->
        <div class="fixed top-0 right-0 w-96 h-96 bg-rose-200/20 blur-[100px] rounded-full -mr-48 -mt-48 pointer-events-none"></div>
        <div class="fixed bottom-0 left-0 w-96 h-96 bg-orange-200/20 blur-[100px] rounded-full -ml-48 -mb-48 pointer-events-none"></div>

        <div class="w-full max-w-3xl opacity-0 animate-fade-in-up">
            <!-- Brand Logo (Strict AGENT_RULES.md Compliance: asset('favicon.svg')) -->
            <div class="flex flex-col items-center mb-8">
                <div class="w-16 h-16 bg-white rounded-3xl flex items-center justify-center shadow-md border border-rose-100 mb-3 transition-transform hover:scale-105 duration-300">
                    <img src="{{ asset('favicon.svg') }}" alt="SyncBin Logo" class="w-10 h-10 object-contain">
                </div>
                <h1 class="text-3xl font-black text-rose-950 tracking-tight">SyncBin</h1>
            </div>

            <!-- Setup Card -->
            <div class="bg-white/80 backdrop-blur-2xl rounded-[2.5rem] p-8 sm:p-12 shadow-2xl border border-white/60">
                <div class="mb-10 text-center">
                    <h2 class="text-3xl font-black text-rose-950 mb-2">Two-Factor Authentication Setup</h2>
                    <p class="text-rose-600/80 text-sm font-bold leading-relaxed max-w-md mx-auto">
                        Protect your SyncBin account with Google Authenticator or any TOTP app.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-12 items-center">
                    <!-- Step 1: QR Code -->
                    <div class="flex flex-col items-center gap-4 bg-rose-50/50 p-6 rounded-3xl border border-rose-100">
                        <div class="p-3 bg-white rounded-2xl shadow-md border border-rose-100 w-48 h-48 flex items-center justify-center [&>svg]:w-full [&>svg]:h-full overflow-hidden">
                            {!! $qrCode !!}
                        </div>
                        <div class="text-center space-y-1">
                            <span class="text-[10px] font-black text-rose-600 uppercase tracking-widest block">Step 1</span>
                            <p class="text-xs font-bold text-gray-700">Scan this QR code with Google Authenticator.</p>
                        </div>
                    </div>

                    <!-- Step 2: Verification Form -->
                    <div class="space-y-6">
                        <div class="text-center md:text-left">
                            <span class="text-[10px] font-black text-rose-600 uppercase tracking-widest block mb-1">Step 2</span>
                            <p class="text-sm font-bold text-gray-800">Enter the 6-digit verification code:</p>
                        </div>

                        <form method="POST" action="{{ route('2fa.enable') }}" class="space-y-5">
                            @csrf
                            
                            <div>
                                <input type="text" name="code" placeholder="123456" maxlength="6" required autofocus
                                       class="w-full text-center text-3xl font-mono tracking-[0.4em] font-black px-4 py-4 border border-rose-200 rounded-2xl focus:ring-4 focus:ring-rose-100 focus:border-rose-500 outline-none transition-all placeholder:text-gray-300 bg-white/70">
                                <x-input-error :messages="$errors->get('code')" class="mt-2 text-center" />
                            </div>

                            <div>
                                <button type="submit" class="w-full bg-rose-950 hover:bg-rose-900 text-white font-black py-4 rounded-2xl transition-all shadow-lg hover:-translate-y-0.5 active:translate-y-0">
                                    Verify & Enable MFA
                                </button>
                            </div>
                        </form>

                        <div class="pt-2 text-center md:text-left border-t border-rose-100 space-y-2">
                            <p class="text-[11px] text-gray-500 leading-relaxed font-semibold">
                                Can't scan? Manual setup secret key:<br>
                                <span class="font-mono text-rose-950 font-black text-xs uppercase tracking-wider select-all font-bold">{{ $secret }}</span>
                            </p>
                            <form method="POST" action="{{ route('2fa.reset') }}">
                                @csrf
                                <button type="submit" class="text-[10px] font-bold text-rose-500 hover:text-rose-700 underline uppercase tracking-wider">
                                    🔄 Generate Fresh QR Code
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cancel -->
            <div class="text-center mt-6">
                <a href="{{ route('dashboard') }}" class="text-xs font-bold text-rose-400 hover:text-rose-600 transition-colors uppercase tracking-widest">
                    ← Back to Dashboard
                </a>
            </div>
        </div>
    </div>
</body>
</html>
