<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Security CAPTCHA Challenge -->
        @php
            $captchaQuestion = (new \App\Services\CaptchaService())->getQuestion();
        @endphp
        <div class="mt-4 p-4 bg-white/60 dark:bg-white/[0.03] border border-white/70 dark:border-white/10 rounded-2xl space-y-2 backdrop-blur-md">
            <div class="flex items-center justify-between">
                <span class="text-xs font-mono font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 flex items-center gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-emerald-500"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="m9 12 2 2 4-4"/></svg>
                    Security Verification
                </span>
                <span class="px-2.5 py-0.5 bg-emerald-600 text-white rounded-lg text-xs font-bold font-mono shadow-sm">
                    {{ $captchaQuestion }} = ?
                </span>
            </div>
            <x-text-input id="captcha_input" class="block w-full text-center font-mono font-bold text-sm tracking-widest"
                          type="text"
                          name="captcha_input"
                          placeholder="Answer"
                          required />
            <x-input-error :messages="$errors->get('captcha_input')" class="mt-1 text-xs text-rose-500" />
        </div>

        <div class="flex items-center justify-between mt-6 pt-4 border-t border-white/60 dark:border-white/10">
            <a class="text-xs font-mono font-bold uppercase tracking-wider text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-200 transition-colors" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button>
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
