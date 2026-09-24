<x-app-layout>
    <div class="relative isolate w-full min-h-[calc(100vh-3.5rem)] bg-gradient-to-br from-emerald-100/60 via-slate-100 to-teal-100/40 dark:from-[#030906] dark:via-[#06120D] dark:to-[#040C09] text-slate-800 dark:text-slate-100 py-6 sm:py-8 px-4 sm:px-6 lg:px-8 selection:bg-emerald-500 selection:text-white transition-colors duration-300 overflow-hidden">
        
        <!-- VIBRANT BACKLIGHT AURORA FOR REAL GLASS REFRACTION -->
        <div class="pointer-events-none absolute inset-0 overflow-hidden select-none z-0">
            <!-- Center Emerald Glow -->
            <div class="absolute -top-32 left-1/2 -translate-x-1/2 w-[850px] h-[550px] bg-emerald-400/35 dark:bg-emerald-500/20 blur-[130px] rounded-full"></div>
            <!-- Left Cyan/Teal Orb -->
            <div class="absolute top-1/3 -left-32 w-[650px] h-[650px] bg-teal-400/30 dark:bg-teal-600/15 blur-[140px] rounded-full"></div>
            <!-- Right Emerald/Sage Orb -->
            <div class="absolute -bottom-24 right-10 w-[700px] h-[550px] bg-emerald-500/30 dark:bg-emerald-600/15 blur-[140px] rounded-full"></div>
            <!-- Fine Dot Grid Matrix -->
            <div class="absolute inset-0 bg-[radial-gradient(#059669_1.2px,transparent_1.2px)] dark:bg-[radial-gradient(#10b981_1px,transparent_1px)] [background-size:26px_26px] opacity-25 dark:opacity-[0.08]"></div>
        </div>

        <!-- MAIN SETTINGS CONTENT -->
        <div class="max-w-[1720px] mx-auto space-y-6 sm:space-y-7 relative z-10">

            <!-- 1. Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-5 border-b border-white/50 dark:border-white/10">
                <div>
                    <div class="flex flex-wrap items-center gap-3">
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight drop-shadow-sm">
                            Security & Settings
                        </h1>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-white/70 dark:bg-emerald-500/15 border border-white/80 dark:border-emerald-400/30 text-emerald-800 dark:text-emerald-300 shadow-[0_4px_16px_rgba(16,185,129,0.15)] backdrop-blur-xl">
                            <span class="relative flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-500 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                            </span>
                            Account Control
                        </span>
                    </div>
                    <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mt-1">
                        Manage personal profile details, authentication credentials, and security preferences
                    </p>
                </div>

                <div class="flex items-center gap-2 text-xs font-mono text-emerald-800 dark:text-emerald-300 bg-white/70 dark:bg-emerald-950/50 px-3.5 py-1.5 rounded-2xl border border-white/80 dark:border-emerald-500/30 shadow-[0_4px_16px_rgba(0,0,0,0.05)] backdrop-blur-xl">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    <span>Session Active</span>
                </div>
            </div>

            <!-- Status Feedback Alerts -->
            @if (session('status'))
                <div class="p-3.5 bg-emerald-500/15 border border-emerald-400/30 rounded-2xl flex items-center gap-3 text-emerald-800 dark:text-emerald-300 text-xs font-semibold shadow-sm backdrop-blur-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            <!-- 2-Column Responsive Layout -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

                <!-- Left Column: Profile & Password Management (7 cols) -->
                <div class="lg:col-span-7 space-y-6">

                    <!-- CARD 1: PROFILE INFORMATION (Frosted Glass) -->
                    <div class="relative rounded-3xl p-6 sm:p-7 bg-white/55 dark:bg-[#0B1713]/60 border border-white/80 dark:border-emerald-500/25 backdrop-blur-2xl shadow-[0_12px_36px_-6px_rgba(16,185,129,0.1)] dark:shadow-[0_16px_40px_-8px_rgba(0,0,0,0.7)] transition-all overflow-hidden group">
                        <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-white dark:via-emerald-400/40 to-transparent pointer-events-none"></div>

                        <div class="flex items-center justify-between pb-4 mb-5 border-b border-white/50 dark:border-white/10">
                            <div class="flex items-center gap-2.5">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                <h2 class="text-base font-bold text-slate-900 dark:text-white tracking-tight">Profile Information</h2>
                            </div>
                            <span class="text-[11px] font-mono text-emerald-700 dark:text-emerald-400 font-semibold">Basic Info</span>
                        </div>

                        <form method="post" action="{{ route('profile.update') }}" class="space-y-4">
                            @csrf
                            @method('patch')

                            <!-- Display Name -->
                            <div>
                                <label for="name" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Display Name</label>
                                <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required
                                       class="w-full px-3.5 py-2.5 bg-white/70 dark:bg-black/40 border border-white/80 dark:border-white/15 rounded-2xl focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/30 outline-none text-xs text-slate-900 dark:text-white backdrop-blur-md transition-all shadow-sm">
                                <x-input-error class="mt-1 text-xs text-rose-500" :messages="$errors->get('name')" />
                            </div>

                            <!-- Email Address -->
                            <div>
                                <label for="email" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Email Address</label>
                                <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required
                                       class="w-full px-3.5 py-2.5 bg-white/70 dark:bg-black/40 border border-white/80 dark:border-white/15 rounded-2xl focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/30 outline-none text-xs text-slate-900 dark:text-white backdrop-blur-md transition-all shadow-sm">
                                <x-input-error class="mt-1 text-xs text-rose-500" :messages="$errors->get('email')" />
                            </div>

                            <div class="pt-2">
                                <button type="submit" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-500 active:scale-95 text-white font-semibold rounded-2xl text-xs transition-all shadow-[0_4px_16px_rgba(16,185,129,0.3)]">
                                    Save Profile Changes
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- CARD 2: CHANGE PASSWORD (Frosted Glass) -->
                    <div class="relative rounded-3xl p-6 sm:p-7 bg-white/55 dark:bg-[#0B1713]/60 border border-white/80 dark:border-emerald-500/25 backdrop-blur-2xl shadow-[0_12px_36px_-6px_rgba(16,185,129,0.1)] dark:shadow-[0_16px_40px_-8px_rgba(0,0,0,0.7)] transition-all overflow-hidden group">
                        <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-white dark:via-emerald-400/40 to-transparent pointer-events-none"></div>

                        <div class="flex items-center justify-between pb-4 mb-5 border-b border-white/50 dark:border-white/10">
                            <div class="flex items-center gap-2.5">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                <h2 class="text-base font-bold text-slate-900 dark:text-white tracking-tight">Change Password</h2>
                            </div>
                            <span class="text-[11px] font-mono text-emerald-700 dark:text-emerald-400 font-semibold">Security Credentials</span>
                        </div>

                        <form method="post" action="{{ route('password.update') }}" class="space-y-4">
                            @csrf
                            @method('put')

                            <!-- Current Password -->
                            <div>
                                <label for="current_password" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Current Password</label>
                                <input id="current_password" name="current_password" type="password" placeholder="••••••••"
                                       class="w-full px-3.5 py-2.5 bg-white/70 dark:bg-black/40 border border-white/80 dark:border-white/15 rounded-2xl focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/30 outline-none text-xs text-slate-900 dark:text-white backdrop-blur-md transition-all shadow-sm">
                                <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-1 text-xs text-rose-500" />
                            </div>

                            <!-- New Password -->
                            <div>
                                <label for="password" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">New Password</label>
                                <input id="password" name="password" type="password" placeholder="••••••••"
                                       class="w-full px-3.5 py-2.5 bg-white/70 dark:bg-black/40 border border-white/80 dark:border-white/15 rounded-2xl focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/30 outline-none text-xs text-slate-900 dark:text-white backdrop-blur-md transition-all shadow-sm">
                                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-1 text-xs text-rose-500" />
                            </div>

                            <!-- Confirm New Password -->
                            <div>
                                <label for="password_confirmation" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Confirm New Password</label>
                                <input id="password_confirmation" name="password_confirmation" type="password" placeholder="••••••••"
                                       class="w-full px-3.5 py-2.5 bg-white/70 dark:bg-black/40 border border-white/80 dark:border-white/15 rounded-2xl focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/30 outline-none text-xs text-slate-900 dark:text-white backdrop-blur-md transition-all shadow-sm">
                                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-1 text-xs text-rose-500" />
                            </div>

                            <div class="pt-2">
                                <button type="submit" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-500 active:scale-95 text-white font-semibold rounded-2xl text-xs transition-all shadow-[0_4px_16px_rgba(16,185,129,0.3)]">
                                    Update Password
                                </button>
                            </div>
                        </form>
                    </div>

                </div>

                <!-- Right Column: Security Controls & Account Specs (5 cols) -->
                <div class="lg:col-span-5 space-y-6">

                    <!-- CARD 3: SECURITY & PREFERENCES (Frosted Glass) -->
                    <div class="relative rounded-3xl p-6 sm:p-7 bg-white/55 dark:bg-[#0B1713]/60 border border-white/80 dark:border-emerald-500/25 backdrop-blur-2xl shadow-[0_12px_36px_-6px_rgba(16,185,129,0.1)] dark:shadow-[0_16px_40px_-8px_rgba(0,0,0,0.7)] transition-all overflow-hidden group"
                         x-data="{
                             emailAlerts: true,
                             twoFactorEnabled: {{ Auth::user()->hasTwoFactorEnabled() ? 'true' : 'false' }}
                         }">
                        <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-white dark:via-emerald-400/40 to-transparent pointer-events-none"></div>

                        <div class="flex items-center justify-between pb-4 mb-5 border-b border-white/50 dark:border-white/10">
                            <div class="flex items-center gap-2.5">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                <h2 class="text-base font-bold text-slate-900 dark:text-white tracking-tight">Security & Controls</h2>
                            </div>
                            <span class="text-[11px] font-mono text-emerald-700 dark:text-emerald-400 font-semibold">Policies</span>
                        </div>

                        <div class="space-y-4">
                            <!-- 2FA Toggle Row -->
                            <div class="flex items-center justify-between py-3 border-b border-white/50 dark:border-white/10">
                                <div class="pr-3">
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-semibold text-slate-900 dark:text-white block">Two-Factor Authentication</span>
                                        <span class="px-2 py-0.5 rounded text-[10px] font-mono font-bold uppercase tracking-wider {{ Auth::user()->hasTwoFactorEnabled() ? 'bg-emerald-500/15 text-emerald-800 dark:text-emerald-300 border border-emerald-400/40' : 'bg-slate-200 dark:bg-white/10 text-slate-600 dark:text-slate-400 border border-white/20' }}">
                                            {{ Auth::user()->hasTwoFactorEnabled() ? 'Enabled' : 'Disabled' }}
                                        </span>
                                    </div>
                                    <span class="text-xs text-slate-600 dark:text-slate-400 mt-0.5 block">Authenticator app protection for admin sessions</span>
                                </div>
                                <a href="{{ route('2fa.setup') }}" 
                                   class="w-11 h-6 rounded-full p-1 transition-colors duration-200 ease-in-out relative shrink-0 block {{ Auth::user()->hasTwoFactorEnabled() ? 'bg-emerald-500' : 'bg-slate-300 dark:bg-slate-700' }}">
                                    <div class="w-4 h-4 bg-white rounded-full transition-transform duration-200 ease-in-out shadow {{ Auth::user()->hasTwoFactorEnabled() ? 'translate-x-5' : 'translate-x-0' }}"></div>
                                </a>
                            </div>

                            <!-- Email Alerts Toggle Row -->
                            <div class="flex items-center justify-between py-3">
                                <div class="pr-3">
                                    <span class="text-xs font-semibold text-slate-900 dark:text-white block">Critical Capacity Alerts</span>
                                    <span class="text-xs text-slate-600 dark:text-slate-400 mt-0.5 block">Automated notifications when any bin exceeds 80% capacity</span>
                                </div>
                                <button type="button" @click="emailAlerts = !emailAlerts" 
                                        class="w-11 h-6 rounded-full p-1 transition-colors duration-200 ease-in-out relative shrink-0"
                                        :class="emailAlerts ? 'bg-emerald-500' : 'bg-slate-300 dark:bg-slate-700'">
                                    <div class="w-4 h-4 bg-white rounded-full transition-transform duration-200 ease-in-out shadow"
                                         :class="emailAlerts ? 'translate-x-5' : 'translate-x-0'"></div>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- CARD 4: ACCOUNT OVERVIEW & SPECS (Frosted Glass) -->
                    <div class="relative rounded-3xl p-6 sm:p-7 bg-white/55 dark:bg-[#0B1713]/60 border border-white/80 dark:border-emerald-500/25 backdrop-blur-2xl shadow-[0_12px_36px_-6px_rgba(16,185,129,0.1)] dark:shadow-[0_16px_40px_-8px_rgba(0,0,0,0.7)] transition-all overflow-hidden group">
                        <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-white dark:via-emerald-400/40 to-transparent pointer-events-none"></div>

                        <div class="flex items-center justify-between pb-4 mb-5 border-b border-white/50 dark:border-white/10">
                            <div class="flex items-center gap-2.5">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                <h2 class="text-base font-bold text-slate-900 dark:text-white tracking-tight">Account Overview</h2>
                            </div>
                            <span class="text-[11px] font-mono text-emerald-700 dark:text-emerald-400 font-semibold">Credentials</span>
                        </div>

                        <div class="space-y-3.5 text-xs">
                            <div class="flex items-center justify-between py-1.5 border-b border-white/50 dark:border-white/10">
                                <span class="text-slate-600 dark:text-slate-400 font-medium">Assigned Role</span>
                                <span class="px-2.5 py-0.5 rounded text-[10px] font-mono font-bold uppercase tracking-wider {{ Auth::user()->isSuperAdmin() ? 'bg-cyan-50 dark:bg-cyan-950/80 text-cyan-700 dark:text-cyan-400 border border-cyan-200 dark:border-cyan-800/80' : 'bg-emerald-50 dark:bg-emerald-950/80 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800/80' }}">
                                    {{ Auth::user()->isSuperAdmin() ? 'SuperAdmin' : 'Admin' }}
                                </span>
                            </div>

                            <div class="flex items-center justify-between py-1.5 border-b border-white/50 dark:border-white/10">
                                <span class="text-slate-600 dark:text-slate-400 font-medium">Email Verification</span>
                                <span class="inline-flex items-center gap-1 font-mono font-semibold text-emerald-700 dark:text-emerald-400">
                                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                                    Verified
                                </span>
                            </div>

                            <div class="flex items-center justify-between py-1.5">
                                <span class="text-slate-600 dark:text-slate-400 font-medium">Account ID</span>
                                <span class="font-mono text-slate-900 dark:text-slate-200 font-bold">#ESC-{{ str_pad(Auth::id(), 4, '0', STR_PAD_LEFT) }}</span>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>
</x-app-layout>
