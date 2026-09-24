<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>EcoSync - Waste Intelligence Portal</title>

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Dark Mode Initialization -->
    <script>
        if (localStorage.theme === 'light') {
            document.documentElement.classList.remove('dark');
        } else {
            document.documentElement.classList.add('dark');
        }
    </script>

    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif; }
    </style>

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @if(config('services.recaptcha.enabled') && config('services.recaptcha.key'))
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @endif
</head>
<body class="antialiased min-h-screen bg-slate-950 text-slate-900 dark:text-slate-100 flex flex-col lg:flex-row selection:bg-emerald-500 selection:text-white transition-colors duration-200"
      x-data="{
          submitting: false,
          showPassword: false,
          isDark: document.documentElement.classList.contains('dark'),
          toggleTheme() {
              this.isDark = !this.isDark;
              if (this.isDark) {
                  document.documentElement.classList.add('dark');
                  localStorage.theme = 'dark';
              } else {
                  document.documentElement.classList.remove('dark');
                  localStorage.theme = 'light';
              }
          }
      }">

    <!-- Left Column: Hardware Showcase Panel -->
    <aside class="hidden lg:flex lg:w-1/2 xl:w-5/12 bg-slate-950 border-r border-slate-800/80 flex-col justify-between p-10 xl:p-14 text-white relative overflow-hidden select-none">
        
        <!-- Hardware Station Background Image -->
        <img src="{{ asset('images/ecosync_hardware_bin.jpg') }}" 
             alt="EcoSync Automated Waste Station Hardware" 
             class="absolute inset-0 w-full h-full object-cover object-center opacity-60 filter brightness-95 contrast-105 pointer-events-none scale-105 transition-transform duration-1000 ease-out">
        
        <!-- Vignette & Surface Gradients -->
        <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/60 to-slate-950/40 pointer-events-none"></div>
        <div class="absolute inset-0 bg-radial-gradient from-transparent via-slate-950/20 to-slate-950/80 pointer-events-none"></div>

        <!-- Top Brand Lockup -->
        <div class="relative z-10">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-white/10 backdrop-blur-md border border-white/20 flex items-center justify-center p-2 shadow-sm">
                    <img src="{{ asset('favicon.svg') }}" alt="System Logo" class="w-full h-full object-contain">
                </div>
                <div>
                    <span class="text-lg font-bold tracking-tight text-white block">EcoSync</span>
                    <span class="text-[11px] font-mono tracking-wider uppercase text-emerald-400 font-semibold block">Waste Intelligence System</span>
                </div>
            </div>
        </div>

        <!-- Middle: Mission & 4-Stream Indicator Cards -->
        <div class="relative z-10 my-auto max-w-md">
            <!-- Hardware Telemetry Beacon -->
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-slate-900/80 border border-emerald-500/30 text-emerald-400 text-xs font-mono mb-6 backdrop-blur-md">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                </span>
                Station Online &bull; Optical AI Active
            </div>

            <h1 class="text-3xl xl:text-4xl font-extrabold tracking-tight text-white leading-tight mb-4">
                Automated sorting across all four waste streams.
            </h1>
            <p class="text-sm text-slate-300 leading-relaxed mb-6 font-normal">
                Continuous multispectral vision, sensor telemetry, and automated routing for municipal and campus facilities.
            </p>

            <!-- 4 System Stream Badges with Dedicated SVGs -->
            <div class="grid grid-cols-2 gap-2.5">
                <div class="flex items-center gap-2.5 px-3 py-2 rounded-xl bg-slate-900/70 border border-emerald-500/30 text-slate-200 text-xs font-medium backdrop-blur-md">
                    <div class="w-6 h-6 rounded-lg bg-emerald-500/20 text-emerald-400 flex items-center justify-center">
                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/>
                            <path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/>
                        </svg>
                    </div>
                    <div>
                        <div class="font-semibold text-white">Bio-Waste</div>
                        <div class="text-[10px] text-emerald-400 font-mono">Organic stream</div>
                    </div>
                </div>

                <div class="flex items-center gap-2.5 px-3 py-2 rounded-xl bg-slate-900/70 border border-sky-500/30 text-slate-200 text-xs font-medium backdrop-blur-md">
                    <div class="w-6 h-6 rounded-lg bg-sky-500/20 text-sky-400 flex items-center justify-center">
                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M7 19H4.815a1.83 1.83 0 0 1-1.57-.881 1.785 1.785 0 0 1-.004-1.784L7.196 9.5"/>
                            <path d="M11 19h8.2a1.8 1.8 0 0 0 1.564-.91 1.776 1.776 0 0 0-.007-1.779L16.8 9.5"/>
                            <path d="m14 13-3-4-3 4"/>
                        </svg>
                    </div>
                    <div>
                        <div class="font-semibold text-white">Recyclables</div>
                        <div class="text-[10px] text-sky-400 font-mono">Plastics &amp; metals</div>
                    </div>
                </div>

                <div class="flex items-center gap-2.5 px-3 py-2 rounded-xl bg-slate-900/70 border border-amber-500/30 text-slate-200 text-xs font-medium backdrop-blur-md">
                    <div class="w-6 h-6 rounded-lg bg-amber-500/20 text-amber-400 flex items-center justify-center">
                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 6h18"/>
                            <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/>
                            <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/>
                        </svg>
                    </div>
                    <div>
                        <div class="font-semibold text-white">Non-Bio</div>
                        <div class="text-[10px] text-amber-400 font-mono">Residual landfill</div>
                    </div>
                </div>

                <div class="flex items-center gap-2.5 px-3 py-2 rounded-xl bg-slate-900/70 border border-rose-500/30 text-slate-200 text-xs font-medium backdrop-blur-md">
                    <div class="w-6 h-6 rounded-lg bg-rose-500/20 text-rose-400 flex items-center justify-center">
                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/>
                            <line x1="12" y1="9" x2="12" y2="13"/>
                            <line x1="12" y1="17" x2="12.01" y2="17"/>
                        </svg>
                    </div>
                    <div>
                        <div class="font-semibold text-white">Hazardous</div>
                        <div class="text-[10px] text-rose-400 font-mono">E-waste &amp; toxins</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom: Hardware Specs & System Version -->
        <div class="relative z-10 pt-6 border-t border-white/10 flex items-center justify-between text-xs text-slate-400 font-mono">
            <span>EcoSync AI Station Node</span>
            <span class="text-slate-500">v4.2.1</span>
        </div>
    </aside>

    <!-- Right Column: Sign-In Authentication with Frosted Glassmorphism -->
    <main class="flex-1 flex flex-col justify-between min-h-screen relative p-6 sm:p-10 lg:p-12 bg-gradient-to-br from-emerald-100/60 via-slate-100 to-teal-100/40 dark:from-[#030906] dark:via-[#06120D] dark:to-[#040C09] selection:bg-emerald-500 selection:text-white transition-colors duration-300 isolate overflow-hidden">
        
        <!-- VIBRANT BACKLIGHT AURORA FOR REAL GLASS REFRACTION -->
        <div class="pointer-events-none absolute inset-0 overflow-hidden select-none z-0">
            <!-- Center Emerald Glow -->
            <div class="absolute -top-32 right-1/4 w-[650px] h-[500px] bg-emerald-400/35 dark:bg-emerald-500/20 blur-[130px] rounded-full"></div>
            <!-- Teal Orb -->
            <div class="absolute bottom-1/4 -right-24 w-[550px] h-[550px] bg-teal-400/30 dark:bg-teal-600/15 blur-[140px] rounded-full"></div>
            <!-- Fine Dot Grid Matrix -->
            <div class="absolute inset-0 bg-[radial-gradient(#059669_1.2px,transparent_1.2px)] dark:bg-[radial-gradient(#10b981_1px,transparent_1px)] [background-size:26px_26px] opacity-25 dark:opacity-[0.08]"></div>
        </div>

        <!-- Top Bar: Mobile Logo & Theme Toggle -->
        <div class="w-full flex items-center justify-between relative z-10">
            <!-- Mobile Brand -->
            <div class="lg:hidden flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-white/70 dark:bg-emerald-500/15 border border-white/80 dark:border-emerald-400/30 flex items-center justify-center p-1.5 shadow-sm">
                    <img src="{{ asset('favicon.svg') }}" alt="System Logo" class="w-full h-full object-contain">
                </div>
                <div>
                    <span class="text-base font-bold tracking-tight text-slate-900 dark:text-white block">EcoSync</span>
                    <span class="text-[10px] font-mono uppercase tracking-wider text-emerald-600 dark:text-emerald-400 font-semibold block">Waste Intelligence</span>
                </div>
            </div>
            <div class="hidden lg:block"></div>

            <!-- Theme Toggle Button -->
            <button @click="toggleTheme()" 
                    type="button" 
                    class="inline-flex items-center justify-center w-10 h-10 rounded-xl border border-white/80 dark:border-white/10 bg-white/70 dark:bg-white/[0.04] text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white backdrop-blur-md transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20" 
                    title="Toggle Color Theme" 
                    aria-label="Toggle theme">
                <svg x-show="!isDark" xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-amber-500">
                    <circle cx="12" cy="12" r="4"/>
                    <path d="M12 2v2"/><path d="M12 20v2"/>
                    <path d="m4.93 4.93 1.41 1.41"/><path d="m17.66 17.66 1.41 1.41"/>
                    <path d="M2 12h2"/><path d="M20 12h2"/>
                    <path d="m6.34 17.66-1.41 1.41"/><path d="m19.07 4.93-1.41 1.41"/>
                </svg>
                <svg x-show="isDark" xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-slate-300">
                    <path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"/>
                </svg>
            </button>
        </div>

        <!-- Center Form Card (Frosted Acrylic Container) -->
        <div class="w-full max-w-sm sm:max-w-md mx-auto my-auto py-6 relative z-10">
            <div class="relative rounded-3xl p-7 sm:p-9 bg-white/55 dark:bg-[#0B1713]/60 border border-white/80 dark:border-emerald-500/25 backdrop-blur-2xl shadow-[0_10px_30px_-5px_rgba(16,185,129,0.1)] dark:shadow-[0_16px_40px_-10px_rgba(0,0,0,0.6)] overflow-hidden">
                <!-- Top Specular Glass Reflection Line -->
                <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-white dark:via-emerald-400/40 to-transparent pointer-events-none"></div>

                <!-- Form Header -->
                <div class="mb-7">
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-mono font-bold bg-white/70 dark:bg-emerald-500/15 border border-white/80 dark:border-emerald-400/30 text-emerald-800 dark:text-emerald-300 shadow-sm backdrop-blur-md mb-3">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        Secure Access Portal
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white drop-shadow-sm">
                        Sign in to EcoSync
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mt-1 leading-relaxed">
                        Access facility monitoring, sensor telemetry, and operational reports.
                    </p>
                </div>

                <!-- Session Status Alert -->
                <x-auth-session-status class="mb-5 p-3.5 bg-emerald-500/15 dark:bg-emerald-500/20 text-emerald-900 dark:text-emerald-200 rounded-2xl border border-emerald-400/40 text-xs font-semibold backdrop-blur-md" :status="session('status')" />

                <!-- Error Banner -->
                @if ($errors->any())
                    <div class="mb-6 p-4 rounded-2xl bg-rose-500/10 dark:bg-rose-500/15 border border-rose-400/30 text-rose-900 dark:text-rose-200 text-xs leading-relaxed backdrop-blur-md">
                        <div class="flex items-start gap-2.5">
                            <svg class="w-4 h-4 text-rose-600 dark:text-rose-400 mt-0.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                            </svg>
                            <ul class="list-disc list-inside space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <form action="{{ route('login.post') }}" method="POST" @submit="submitting = true" class="space-y-4">
                    @csrf
                    
                    <!-- Email Address -->
                    <div>
                        <label for="email" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wider font-mono">
                            Email address
                        </label>
                        <div class="relative">
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   placeholder="operator@ecosync.local" 
                                   value="{{ old('email') }}" 
                                   required 
                                   autofocus 
                                   autocomplete="username"
                                   class="w-full px-3.5 py-2.5 border border-white/80 dark:border-white/10 rounded-xl focus:ring-4 focus:ring-emerald-500/15 focus:border-emerald-500 outline-none transition-all placeholder:text-slate-400 dark:placeholder:text-slate-500 bg-white/70 dark:bg-white/[0.04] text-slate-900 dark:text-white text-sm shadow-sm backdrop-blur-md">
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-1.5 text-xs text-rose-500" />
                    </div>

                    <!-- Password Field -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="password" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider font-mono">
                                Password
                            </label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-xs font-medium text-emerald-700 hover:text-emerald-800 dark:text-emerald-400 dark:hover:text-emerald-300 transition-colors">
                                    Forgot password?
                                </a>
                            @endif
                        </div>
                        <div class="relative">
                            <input :type="showPassword ? 'text' : 'password'" 
                                   id="password" 
                                   name="password" 
                                   placeholder="••••••••" 
                                   required 
                                   autocomplete="current-password"
                                   class="w-full px-3.5 py-2.5 pr-11 border border-white/80 dark:border-white/10 rounded-xl focus:ring-4 focus:ring-emerald-500/15 focus:border-emerald-500 outline-none transition-all placeholder:text-slate-400 dark:placeholder:text-slate-500 bg-white/70 dark:bg-white/[0.04] text-slate-900 dark:text-white text-sm shadow-sm backdrop-blur-md">
                            <button type="button" 
                                    @click="showPassword = !showPassword" 
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors p-1" 
                                    aria-label="Toggle password visibility">
                                <svg x-show="!showPassword" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                                <svg x-show="showPassword" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" x-cloak>
                                    <path d="M9.88a3 3 0 1 0 4.24 4.24"/>
                                    <path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/>
                                    <path d="M6.61 6.61A13.52 13.52 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/>
                                    <line x1="2" x2="22" y1="2" y2="22"/>
                                </svg>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-1.5 text-xs text-rose-500" />
                    </div>

                    <!-- Security Verification (reCAPTCHA or Math Challenge) -->
                    @if(config('services.recaptcha.enabled') && config('services.recaptcha.key'))
                        <div class="flex flex-col items-center justify-center pt-2">
                            <div class="g-recaptcha scale-90 sm:scale-100 origin-center" data-sitekey="{{ config('services.recaptcha.key') }}"></div>
                            <x-input-error :messages="$errors->get('g-recaptcha-response')" class="mt-1 text-center text-xs text-rose-500" />
                        </div>
                    @else
                        @php
                            $captchaQuestion = (new \App\Services\CaptchaService())->getQuestion();
                        @endphp
                        <div class="p-3.5 bg-white/60 dark:bg-white/[0.03] border border-white/70 dark:border-white/10 rounded-2xl flex items-center justify-between gap-3 shadow-sm backdrop-blur-md">
                            <div class="flex items-center gap-2.5">
                                <span class="w-6 h-6 rounded-lg bg-emerald-500/15 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-400 flex items-center justify-center">
                                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                                    </svg>
                                </span>
                                <span class="text-xs text-slate-700 dark:text-slate-300 font-medium">
                                    Challenge: <span class="font-mono font-bold text-slate-900 dark:text-white">{{ $captchaQuestion }}</span> =
                                </span>
                            </div>
                            <input type="text" 
                                   id="captcha_input" 
                                   name="captcha_input" 
                                   placeholder="Answer" 
                                   required
                                   class="w-24 text-center font-mono font-bold text-xs py-1.5 px-2 border border-white/80 dark:border-white/10 rounded-lg bg-white/80 dark:bg-white/[0.04] text-slate-900 dark:text-white outline-none focus:ring-2 focus:ring-emerald-500 shadow-inner">
                        </div>
                        <x-input-error :messages="$errors->get('captcha_input')" class="mt-1 text-xs text-rose-500" />
                    @endif

                    <!-- Remember Device -->
                    <div class="flex items-center pt-1">
                        <label class="flex items-center gap-2 cursor-pointer select-none">
                            <input type="checkbox" 
                                   id="remember" 
                                   name="remember" 
                                   class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-slate-300 dark:border-white/20 rounded dark:bg-white/5 cursor-pointer">
                            <span class="text-xs text-slate-600 dark:text-slate-400 font-medium">Remember this device</span>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-2">
                        <button type="submit" 
                                :disabled="submitting"
                                class="w-full bg-emerald-600 hover:bg-emerald-500 active:bg-emerald-700 text-white font-semibold py-3 px-4 rounded-xl transition-all duration-150 active:scale-[0.99] shadow-[0_4px_20px_rgba(16,185,129,0.25)] hover:shadow-[0_6px_24px_rgba(16,185,129,0.35)] flex items-center justify-center gap-2 text-sm tracking-normal"
                                :class="submitting ? 'opacity-75 cursor-not-allowed' : ''">
                            <svg x-show="submitting" class="animate-spin h-4 w-4 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" x-cloak>
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                            </svg>
                            <span x-text="submitting ? 'Authenticating...' : 'Sign in'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Footer Copyright & System ID -->
        <div class="w-full text-center text-xs text-slate-500 dark:text-slate-400 font-mono py-2 relative z-10">
            &copy; 2026 EcoSync Systems &bull; High-Assurance Waste Infrastructure
        </div>
    </main>

</body>
</html>
