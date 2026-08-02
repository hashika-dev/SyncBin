<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Google reCAPTCHA v2 / Math CAPTCHA -->
        @if(config('services.recaptcha.enabled'))
            <script src="https://www.google.com/recaptcha/api.js" async defer></script>
            <div class="mt-4 flex flex-col items-center justify-center">
                <div class="g-recaptcha scale-90 sm:scale-100 origin-center" data-sitekey="{{ config('services.recaptcha.key') }}"></div>
                <x-input-error :messages="$errors->get('g-recaptcha-response')" class="mt-2 text-xs" />
            </div>
        @else
            @php
                $captchaQuestion = (new \App\Services\CaptchaService())->getQuestion();
            @endphp
            <div class="mt-4 p-4 bg-rose-50/60 dark:bg-zinc-800/60 border border-rose-100 dark:border-zinc-700 rounded-2xl space-y-2">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-black uppercase tracking-wider text-rose-950 dark:text-zinc-100 flex items-center gap-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="text-rose-500"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="m9 12 2 2 4-4"/></svg>
                        Security Verification (CAPTCHA)
                    </span>
                    <span class="px-2.5 py-0.5 bg-rose-500 text-white rounded-lg text-xs font-black font-mono shadow-sm">
                        {{ $captchaQuestion }} = ?
                    </span>
                </div>
                <x-text-input id="captcha_input" class="block w-full text-center font-mono font-bold text-sm tracking-widest"
                              type="text"
                              name="captcha_input"
                              placeholder="Type your answer here..."
                              required />
                <x-input-error :messages="$errors->get('captcha_input')" class="mt-1 text-xs" />
            </div>
        @endif

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600 dark:text-zinc-400">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
