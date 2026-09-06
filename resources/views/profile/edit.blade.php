<x-app-layout>
    <div class="w-full bg-[#0B0F17] text-slate-100 min-h-[calc(100vh-3.5rem)] py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl mx-auto space-y-6">

            <!-- 1. Header -->
            <header class="pb-2">
                <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">Security & Settings</h1>
                <p class="text-xs text-slate-400 mt-1">Manage your profile, password, and security preferences.</p>
            </header>

            <!-- Status Alerts -->
            @if (session('status'))
                <div class="p-3.5 bg-emerald-950/60 border border-emerald-800/80 rounded-xl flex items-center gap-3 text-emerald-400 text-xs font-semibold">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            <!-- 2. CARD 1: PROFILE -->
            <div class="bg-[#111622] border border-[#1E2638] rounded-2xl p-6 sm:p-7 shadow-sm">
                <span class="text-[10px] font-mono font-bold uppercase tracking-widest text-slate-400 block mb-4">PROFILE</span>

                <form method="post" action="{{ route('profile.update') }}" class="space-y-4">
                    @csrf
                    @method('patch')

                    <!-- Display Name -->
                    <div>
                        <label for="name" class="block text-xs font-semibold text-slate-400 mb-1.5">Display Name</label>
                        <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required
                               class="w-full px-3.5 py-2.5 bg-[#161D2B] border border-[#243046] rounded-xl focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 outline-none text-xs text-white">
                        <x-input-error class="mt-1 text-xs text-red-400" :messages="$errors->get('name')" />
                    </div>

                    <!-- Email Address -->
                    <div>
                        <label for="email" class="block text-xs font-semibold text-slate-400 mb-1.5">Email Address</label>
                        <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required
                               class="w-full px-3.5 py-2.5 bg-[#161D2B] border border-[#243046] rounded-xl focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 outline-none text-xs text-white">
                        <x-input-error class="mt-1 text-xs text-red-400" :messages="$errors->get('email')" />
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="px-5 py-2.5 bg-[#10B981] hover:bg-[#0E8E43] text-white font-bold rounded-xl text-xs transition-colors shadow-sm">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>

            <!-- 3. CARD 2: CHANGE PASSWORD -->
            <div class="bg-[#111622] border border-[#1E2638] rounded-2xl p-6 sm:p-7 shadow-sm">
                <span class="text-[10px] font-mono font-bold uppercase tracking-widest text-slate-400 block mb-4">CHANGE PASSWORD</span>

                <form method="post" action="{{ route('password.update') }}" class="space-y-4">
                    @csrf
                    @method('put')

                    <!-- Current Password -->
                    <div>
                        <label for="current_password" class="block text-xs font-semibold text-slate-400 mb-1.5">Current Password</label>
                        <input id="current_password" name="current_password" type="password" placeholder="••••••••"
                               class="w-full px-3.5 py-2.5 bg-[#161D2B] border border-[#243046] rounded-xl focus:border-emerald-500 outline-none text-xs text-white">
                        <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-1 text-xs text-red-400" />
                    </div>

                    <!-- New Password -->
                    <div>
                        <label for="password" class="block text-xs font-semibold text-slate-400 mb-1.5">New Password</label>
                        <input id="password" name="password" type="password" placeholder="••••••••"
                               class="w-full px-3.5 py-2.5 bg-[#161D2B] border border-[#243046] rounded-xl focus:border-emerald-500 outline-none text-xs text-white">
                        <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-1 text-xs text-red-400" />
                    </div>

                    <!-- Confirm New Password -->
                    <div>
                        <label for="password_confirmation" class="block text-xs font-semibold text-slate-400 mb-1.5">Confirm New Password</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" placeholder="••••••••"
                               class="w-full px-3.5 py-2.5 bg-[#161D2B] border border-[#243046] rounded-xl focus:border-emerald-500 outline-none text-xs text-white">
                        <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-1 text-xs text-red-400" />
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="px-5 py-2.5 bg-[#10B981] hover:bg-[#0E8E43] text-white font-bold rounded-xl text-xs transition-colors shadow-sm">
                            Update Password
                        </button>
                    </div>
                </form>
            </div>

            <!-- 4. CARD 3: SECURITY (Toggles) -->
            <div class="bg-[#111622] border border-[#1E2638] rounded-2xl p-6 sm:p-7 shadow-sm space-y-4"
                 x-data="{
                     emailAlerts: true,
                     twoFactorEnabled: {{ Auth::user()->hasTwoFactorEnabled() ? 'true' : 'false' }}
                 }">
                <span class="text-[10px] font-mono font-bold uppercase tracking-widest text-slate-400 block mb-4">SECURITY</span>

                <div class="space-y-4 pt-1">
                    <!-- 2FA Toggle Row -->
                    <div class="flex items-center justify-between py-2 border-b border-[#1E2638]">
                        <div>
                            <span class="text-xs font-semibold text-slate-200 block">Two-Factor Authentication (2FA)</span>
                            <span class="text-[11px] text-slate-400">Add an extra layer of biometric or authenticator app protection</span>
                        </div>
                        <a href="{{ route('2fa.setup') }}" 
                           class="w-11 h-6 rounded-full p-1 transition-colors duration-200 ease-in-out relative shrink-0 block"
                           :class="twoFactorEnabled ? 'bg-emerald-500' : 'bg-slate-700'">
                            <div class="w-4 h-4 bg-white rounded-full transition-transform duration-200 ease-in-out shadow"
                                 :class="twoFactorEnabled ? 'translate-x-5' : 'translate-x-0'"></div>
                        </a>
                    </div>

                    <!-- Email Alerts Toggle Row -->
                    <div class="flex items-center justify-between py-2">
                        <div>
                            <span class="text-xs font-semibold text-slate-200 block">Email alerts for critical events</span>
                            <span class="text-[11px] text-slate-400">Receive instant notifications when bin thresholds exceed 80%</span>
                        </div>
                        <button type="button" @click="emailAlerts = !emailAlerts" 
                                class="w-11 h-6 rounded-full p-1 transition-colors duration-200 ease-in-out relative shrink-0"
                                :class="emailAlerts ? 'bg-emerald-500' : 'bg-slate-700'">
                            <div class="w-4 h-4 bg-white rounded-full transition-transform duration-200 ease-in-out shadow"
                                 :class="emailAlerts ? 'translate-x-5' : 'translate-x-0'"></div>
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
