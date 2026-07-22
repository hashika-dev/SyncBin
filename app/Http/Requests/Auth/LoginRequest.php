<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $siteKey = config('services.turnstile.key');
        $secretKey = config('services.turnstile.secret');
        $hasRealKeys = $siteKey && $siteKey !== '1x00000000000000000000AA' && $secretKey && $secretKey !== '1x0000000000000000000000000000000AA';

        $rules = [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];

        if ($hasRealKeys && !app()->environment('local') && !app()->runningUnitTests()) {
            $rules['cf-turnstile-response'] = ['nullable', function ($attribute, $value, $fail) use ($secretKey) {
                if (!$value) return;
                try {
                    $response = \Illuminate\Support\Facades\Http::timeout(3)->asForm()->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
                        'secret' => $secretKey,
                        'response' => $value,
                        'remoteip' => request()->ip(),
                    ]);

                    if ($response->successful() && $response->json('success') === false) {
                        \Illuminate\Support\Facades\Log::warning('Turnstile rejected token: ' . json_encode($response->json()));
                    }
                } catch (\Throwable $e) {
                    \Illuminate\Support\Facades\Log::warning('Turnstile verification error: ' . $e->getMessage());
                }
            }];
        } else {
            $rules['cf-turnstile-response'] = ['nullable'];
        }

        return $rules;
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }
}
