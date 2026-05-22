<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SyncBin - MFA Verification</title>

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

        <div class="w-full max-w-md opacity-0 animate-fade-in-up">
            <!-- Brand Logo -->
            <div class="flex flex-col items-center mb-8">
                <div class="w-16 h-16 bg-white rounded-3xl flex items-center justify-center shadow-sm border border-rose-100 mb-4 transition-transform hover:scale-105 duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-leaf text-rose-500"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C10.9 14.36 12 15 12 15"/></svg>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 tracking-tight">SyncBin</h1>
            </div>

            <!-- Verification Card -->
            <div class="bg-white/70 backdrop-blur-2xl rounded-[2.5rem] p-8 sm:p-10 shadow-2xl border border-white/60">
                <div class="mb-10 text-center">
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Secure Verification</h2>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        Please enter the 6-digit code from your Authenticator app to continue to your dashboard.
                    </p>
                </div>

                <form method="POST" class="space-y-8">
                    @csrf
                    
                    <div class="opacity-0 animate-fade-in-up delay-100">
                        <input type="text" name="{{ $input }}" id="{{ $input }}" placeholder="000 000" required autofocus
                               class="w-full text-center text-3xl tracking-[0.4em] font-bold px-5 py-6 border border-gray-200 rounded-3xl focus:ring-4 focus:ring-rose-100 focus:border-rose-400 outline-none transition-all placeholder:text-gray-200 bg-white/50">
                        @if($errors->isNotEmpty())
                            <div class="mt-4 text-center">
                                @foreach ($errors->all() as $error)
                                    <p class="text-sm font-semibold text-rose-500">{{ $error }}</p>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div class="opacity-0 animate-fade-in-up delay-200">
                        <button type="submit" class="w-full bg-rose-500 hover:bg-rose-600 text-white font-bold py-4 rounded-2xl transition-all shadow-lg shadow-rose-200 hover:shadow-rose-300 active:scale-[0.98]">
                            Confirm Identity
                        </button>
                    </div>

                    <div class="pt-2 text-center opacity-0 animate-fade-in-up delay-200">
                        <p class="text-xs text-gray-400">
                            Lost your device? Contact your system administrator.
                        </p>
                    </div>
                </form>
            </div>

            <!-- Security Footer -->
            <p class="text-center text-[11px] text-gray-400 uppercase tracking-widest mt-8 font-bold">
                Multi-Factor Shield Active
            </p>
        </div>
    </div>
</body>
</html>
