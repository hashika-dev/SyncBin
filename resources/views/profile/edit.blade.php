<x-app-layout>
    <div class="relative isolate w-full bg-slate-50 dark:bg-[#0A0E17] text-slate-800 dark:text-slate-200 min-h-[calc(100vh-3.5rem)] py-6 sm:py-8 px-4 sm:px-6 lg:px-8 transition-colors duration-300 overflow-hidden">
        
        <!-- PROFESSIONAL COHESIVE BACKGROUND (Single signature brand atmospheric backlight, no rainbow) -->
        <div class="pointer-events-none absolute inset-0 overflow-hidden select-none z-0">
            <!-- Single Unified Subtle Emerald Brand Glow at top center -->
            <div class="absolute -top-32 left-1/2 -translate-x-1/2 w-[850px] h-[450px] bg-emerald-500/10 dark:bg-emerald-500/8 blur-[130px] rounded-full"></div>

            <!-- Subtle Technical Dot-Grid Matrix Texture (Clean & Professional) -->
            <div class="absolute inset-0 bg-[radial-gradient(#94a3b8_1px,transparent_1px)] dark:bg-[radial-gradient(#1e293b_1px,transparent_1px)] [background-size:24px_24px] [mask-image:radial-gradient(ellipse_70%_50%_at_50%_0%,#000_60%,transparent_100%)] opacity-70"></div>
        </div>

        <!-- MAIN SETTINGS CONTENT (Elevated at z-10) -->
        <div class="max-w-[1720px] mx-auto space-y-7 relative z-10">

            <!-- 1. Header -->
            <header class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-200 dark:border-slate-800/80">
                <div>
                    <div class="flex items-center gap-3">
                        <h1 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">Security & Settings</h1>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800/60 text-emerald-700 dark:text-emerald-300 shadow-sm">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            Account Control
                        </span>
                    </div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Manage personal profile details, authentication credentials, and security preferences</p>
                </div>

                <div class="flex items-center gap-2 text-xs font-mono text-slate-500 dark:text-slate-400 bg-white dark:bg-[#101622] px-3.5 py-2 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    <span>Session Active</span>
                </div>
            </header>

            <!-- Status Alerts -->
            @if (session('status'))
                <div class="p-3.5 bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800/80 rounded-xl flex items-center gap-3 text-emerald-700 dark:text-emerald-300 text-xs font-semibold shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            <!-- 2-Column Responsive Layout -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

                <!-- Left Column: Profile & Password Management (7 cols) -->
                <div class="lg:col-span-7 space-y-6">

                    <!-- CARD 1: PROFILE INFORMATION -->
                    <div class="bg-white dark:bg-[#101622] border border-slate-200/90 dark:border-slate-800/90 rounded-2xl p-6 sm:p-7 shadow-sm transition-all">
                        <div class="flex items-center justify-between pb-4 mb-5 border-b border-slate-100 dark:border-slate-800/60">
                            <div class="flex items-center gap-2.5">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                <h2 class="text-base font-bold text-slate-900 dark:text-white tracking-tight">Profile Information</h2>
                            </div>
                            <span class="text-[11px] font-mono text-slate-400">Basic Info</span>
                        </div>

                        <form method="post" action="{{ route('profile.update') }}" class="space-y-4">
                            @csrf
                            @method('patch')

                            <!-- Display Name -->
                            <div>
                                <label for="name" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Display Name</label>
                                <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required
                                       class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-[#0A0E17] border border-slate-200 dark:border-slate-800 rounded-xl focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20 outline-none text-xs text-slate-900 dark:text-white transition-all">
                                <x-input-error class="mt-1 text-xs text-rose-500" :messages="$errors->get('name')" />
                            </div>

                            <!-- Email Address -->
                            <div>
                                <label for="email" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Email Address</label>
                                <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required
                                       class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-[#0A0E17] border border-slate-200 dark:border-slate-800 rounded-xl focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20 outline-none text-xs text-slate-900 dark:text-white transition-all">
                                <x-input-error class="mt-1 text-xs text-rose-500" :messages="$errors->get('email')" />
                            </div>

                            <div class="pt-2">
                                <button type="submit" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-500 active:scale-95 text-white font-semibold rounded-xl text-xs transition-all shadow-sm">
                                    Save Profile Changes
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- CARD 2: CHANGE PASSWORD -->
                    <div class="bg-white dark:bg-[#101622] border border-slate-200/90 dark:border-slate-800/90 rounded-2xl p-6 sm:p-7 shadow-sm transition-all">
                        <div class="flex items-center justify-between pb-4 mb-5 border-b border-slate-100 dark:border-slate-800/60">
                            <div class="flex items-center gap-2.5">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                <h2 class="text-base font-bold text-slate-900 dark:text-white tracking-tight">Change Password</h2>
                            </div>
                            <span class="text-[11px] font-mono text-slate-400">Credentials</span>
                        </div>

                        <form method="post" action="{{ route('password.update') }}" class="space-y-4">
                            @csrf
                            @method('put')

                            <!-- Current Password -->
                            <div>
                                <label for="current_password" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Current Password</label>
                                <input id="current_password" name="current_password" type="password" placeholder="••••••••"
                                       class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-[#0A0E17] border border-slate-200 dark:border-slate-800 rounded-xl focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20 outline-none text-xs text-slate-900 dark:text-white transition-all">
                                <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-1 text-xs text-rose-500" />
                            </div>

                            <!-- New Password -->
                            <div>
                                <label for="password" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">New Password</label>
                                <input id="password" name="password" type="password" placeholder="••••••••"
                                       class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-[#0A0E17] border border-slate-200 dark:border-slate-800 rounded-xl focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20 outline-none text-xs text-slate-900 dark:text-white transition-all">
                                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-1 text-xs text-rose-500" />
                            </div>

                            <!-- Confirm New Password -->
                            <div>
                                <label for="password_confirmation" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Confirm New Password</label>
                                <input id="password_confirmation" name="password_confirmation" type="password" placeholder="••••••••"
                                       class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-[#0A0E17] border border-slate-200 dark:border-slate-800 rounded-xl focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/20 outline-none text-xs text-slate-900 dark:text-white transition-all">
                                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-1 text-xs text-rose-500" />
                            </div>

                            <div class="pt-2">
                                <button type="submit" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-500 active:scale-95 text-white font-semibold rounded-xl text-xs transition-all shadow-sm">
                                    Update Password
                                </button>
                            </div>
                        </form>
                    </div>

                </div>

                <!-- Right Column: Security Controls & Account Specs (5 cols) -->
                <div class="lg:col-span-5 space-y-6">

                    <!-- CARD 3: SECURITY & PREFERENCES -->
                    <div class="bg-white dark:bg-[#101622] border border-slate-200/90 dark:border-slate-800/90 rounded-2xl p-6 sm:p-7 shadow-sm transition-all"
                         x-data="{
                             emailAlerts: true,
                             twoFactorEnabled: {{ Auth::user()->hasTwoFactorEnabled() ? 'true' : 'false' }}
                         }">
                        <div class="flex items-center justify-between pb-4 mb-5 border-b border-slate-100 dark:border-slate-800/60">
                            <div class="flex items-center gap-2.5">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                <h2 class="text-base font-bold text-slate-900 dark:text-white tracking-tight">Security & Controls</h2>
                            </div>
                            <span class="text-[11px] font-mono text-slate-400">Policies</span>
                        </div>

                        <div class="space-y-4">
                            <!-- 2FA Toggle Row -->
                            <div class="flex items-center justify-between py-3 border-b border-slate-100 dark:border-slate-800/60">
                                <div class="pr-3">
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-semibold text-slate-900 dark:text-white block">Two-Factor Authentication</span>
                                        <span class="px-2 py-0.5 rounded text-[10px] font-mono font-bold uppercase tracking-wider {{ Auth::user()->hasTwoFactorEnabled() ? 'bg-emerald-50 dark:bg-emerald-950/80 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800/80' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-700' }}">
                                            {{ Auth::user()->hasTwoFactorEnabled() ? 'Enabled' : 'Disabled' }}
                                        </span>
                                    </div>
                                    <span class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 block">Authenticator app protection for admin sessions</span>
                                </div>
                                <a href="{{ route('2fa.setup') }}" 
                                   class="w-11 h-6 rounded-full p-1 transition-colors duration-200 ease-in-out relative shrink-0 block {{ Auth::user()->hasTwoFactorEnabled() ? 'bg-emerald-500' : 'bg-slate-200 dark:bg-slate-700' }}">
                                    <div class="w-4 h-4 bg-white rounded-full transition-transform duration-200 ease-in-out shadow {{ Auth::user()->hasTwoFactorEnabled() ? 'translate-x-5' : 'translate-x-0' }}"></div>
                                </a>
                            </div>

                            <!-- Email Alerts Toggle Row -->
                            <div class="flex items-center justify-between py-3">
                                <div class="pr-3">
                                    <span class="text-xs font-semibold text-slate-900 dark:text-white block">Critical Capacity Alerts</span>
                                    <span class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 block">Automated notifications when any bin exceeds 80% capacity</span>
                                </div>
                                <button type="button" @click="emailAlerts = !emailAlerts" 
                                        class="w-11 h-6 rounded-full p-1 transition-colors duration-200 ease-in-out relative shrink-0"
                                        :class="emailAlerts ? 'bg-emerald-500' : 'bg-slate-200 dark:bg-slate-700'">
                                    <div class="w-4 h-4 bg-white rounded-full transition-transform duration-200 ease-in-out shadow"
                                         :class="emailAlerts ? 'translate-x-5' : 'translate-x-0'"></div>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- CARD 4: ACCOUNT OVERVIEW & METRICS -->
                    <div class="bg-white dark:bg-[#101622] border border-slate-200/90 dark:border-slate-800/90 rounded-2xl p-6 sm:p-7 shadow-sm transition-all">
                        <div class="flex items-center justify-between pb-4 mb-5 border-b border-slate-100 dark:border-slate-800/60">
                            <div class="flex items-center gap-2.5">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                <h2 class="text-base font-bold text-slate-900 dark:text-white tracking-tight">Account Overview</h2>
                            </div>
                            <span class="text-[11px] font-mono text-slate-400">Credentials</span>
                        </div>

                        <div class="space-y-3.5 text-xs">
                            <div class="flex items-center justify-between py-1.5 border-b border-slate-100 dark:border-slate-800/50">
                                <span class="text-slate-500 dark:text-slate-400 font-medium">Assigned Role</span>
                                <span class="px-2.5 py-0.5 rounded text-[10px] font-mono font-bold uppercase tracking-wider {{ Auth::user()->isSuperAdmin() ? 'bg-cyan-50 dark:bg-cyan-950/80 text-cyan-700 dark:text-cyan-400 border border-cyan-200 dark:border-cyan-800/80' : 'bg-emerald-50 dark:bg-emerald-950/80 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800/80' }}">
                                    {{ Auth::user()->isSuperAdmin() ? 'SuperAdmin' : 'Admin' }}
                                </span>
                            </div>

                            <div class="flex items-center justify-between py-1.5 border-b border-slate-100 dark:border-slate-800/50">
                                <span class="text-slate-500 dark:text-slate-400 font-medium">Email Verification</span>
                                <span class="inline-flex items-center gap-1 font-mono font-semibold text-emerald-600 dark:text-emerald-400">
                                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                                    Verified
                                </span>
                            </div>

                            <div class="flex items-center justify-between py-1.5">
                                <span class="text-slate-500 dark:text-slate-400 font-medium">Account ID</span>
                                <span class="font-mono text-slate-700 dark:text-slate-300 font-semibold">#ESC-{{ str_pad(Auth::id(), 4, '0', STR_PAD_LEFT) }}</span>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>
</x-app-layout>
