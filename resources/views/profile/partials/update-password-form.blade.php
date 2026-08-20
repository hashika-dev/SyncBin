<section>
    <header>
        <h2 class="text-lg font-bold text-slate-900 dark:text-white">
            {{ __('Update Password') }}
        </h2>

        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    @if(str_ends_with(strtolower(auth()->user()->email), '@wastesync.com'))
        <div class="mt-4 p-4 bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-900/50 rounded-xl text-amber-800 dark:text-amber-300 text-xs font-semibold leading-relaxed">
            <div class="flex items-center gap-2 mb-1 text-amber-900 dark:text-amber-200 font-bold">
                <svg class="w-4 h-4 text-amber-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <span>Email Update Required</span>
            </div>
            You are currently using a demo email address (<strong>{{ auth()->user()->email }}</strong>). You must update your email above to a valid personal or company email before changing your password to avoid permanent account lockout.
        </div>
    @endif

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div>
            <x-input-label for="update_password_current_password" :value="__('Current Password')" />
            <x-text-input id="update_password_current_password" name="current_password" type="password" class="mt-1 block w-full" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div x-data="{
            pass: '',
            get hasLength() { return this.pass.length >= 12 },
            get hasMixed() { return /[a-z]/.test(this.pass) && /[A-Z]/.test(this.pass) },
            get hasNumber() { return /[0-9]/.test(this.pass) },
            get hasSymbol() { return /[^a-zA-Z0-9]/.test(this.pass) }
        }">
            <x-input-label for="update_password_password" :value="__('New Password')" />
            <x-text-input id="update_password_password" name="password" type="password" x-model="pass" class="mt-1 block w-full" autocomplete="new-password" placeholder="••••••••••••" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
            
            <div class="mt-3 p-4 bg-slate-50 dark:bg-slate-950 rounded-xl border border-slate-200 dark:border-slate-800 text-xs font-semibold text-slate-600 dark:text-slate-400">
                <span class="block font-mono font-bold uppercase tracking-wider text-[11px] text-slate-800 dark:text-slate-200 mb-1.5 flex items-center gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-emerald-500"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>
                    Password Requirements:
                </span>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-1.5 text-[11px] font-mono">
                    <div class="flex items-center gap-1.5 transition-colors" :class="hasLength ? 'text-emerald-600 dark:text-emerald-400 font-bold' : 'text-slate-400 dark:text-slate-500'">
                        <span class="font-mono text-xs" x-text="hasLength ? '✓' : '•'"></span> At least 12 characters
                    </div>
                    <div class="flex items-center gap-1.5 transition-colors" :class="hasMixed ? 'text-emerald-600 dark:text-emerald-400 font-bold' : 'text-slate-400 dark:text-slate-500'">
                        <span class="font-mono text-xs" x-text="hasMixed ? '✓' : '•'"></span> Upper & lowercase letters
                    </div>
                    <div class="flex items-center gap-1.5 transition-colors" :class="hasNumber ? 'text-emerald-600 dark:text-emerald-400 font-bold' : 'text-slate-400 dark:text-slate-500'">
                        <span class="font-mono text-xs" x-text="hasNumber ? '✓' : '•'"></span> At least one number
                    </div>
                    <div class="flex items-center gap-1.5 transition-colors" :class="hasSymbol ? 'text-emerald-600 dark:text-emerald-400 font-bold' : 'text-slate-400 dark:text-slate-500'">
                        <span class="font-mono text-xs" x-text="hasSymbol ? '✓' : '•'"></span> At least one symbol (!@#$)
                    </div>
                </div>
            </div>
        </div>

        <div>
            <x-input-label for="update_password_password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-zinc-400"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
