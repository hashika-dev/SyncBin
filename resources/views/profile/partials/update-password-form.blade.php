<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-zinc-100">
            {{ __('Update Password') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-zinc-400">
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

        <div>
            <x-input-label for="update_password_password" :value="__('New Password')" />
            <x-text-input id="update_password_password" name="password" type="password" class="mt-1 block w-full" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
            
            <div class="mt-2.5 p-3 bg-gray-50 dark:bg-zinc-800/50 rounded-xl border border-gray-200/60 dark:border-zinc-700/60 text-xs text-gray-600 dark:text-zinc-400">
                <p class="font-bold text-gray-700 dark:text-zinc-300 mb-1">Strong Password Requirements:</p>
                <ul class="list-disc list-inside space-y-0.5 text-[11px] text-gray-500 dark:text-zinc-400 font-medium">
                    <li>Minimum <strong>12 characters</strong> long</li>
                    <li>Includes <strong>uppercase & lowercase</strong> letters (A-Z, a-z)</li>
                    <li>Includes at least <strong>1 number</strong> (0-9)</li>
                    <li>Includes at least <strong>1 special symbol</strong> (@, $, !, %, *, #, ?, &)</li>
                </ul>
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
