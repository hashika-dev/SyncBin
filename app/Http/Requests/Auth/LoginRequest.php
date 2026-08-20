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
        $recaptchaSecret = config('services.recaptcha.secret');
        $recaptchaKey = config('services.recaptcha.key');

        $rules = [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];

        if (config('services.recaptcha.enabled')) {
            $rules['g-recaptcha-response'] = [app()->environment('testing') ? 'nullable' : 'required', function ($attribute, $value, $fail) use ($recaptchaSecret, $recaptchaKey) {
                if (app()->environment('testing')) {
                    return;
                }

                if (!$value) {
                    $fail('Please complete the Google reCAPTCHA verification.');
                    return;
                }

                // Official Google reCAPTCHA universal test key bypass
                if ($recaptchaKey === '6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI') {
                    return;
                }

                try {
                    $response = \Illuminate\Support\Facades\Http::timeout(5)->asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                        'secret' => $recaptchaSecret,
                        'response' => $value,
                        'remoteip' => request()->ip(),
                    ]);

                    if (!$response->successful() || $response->json('success') !== true) {
                        $fail('Google reCAPTCHA verification failed. Please try again.');
                    }
                } catch (\Throwable $e) {
                    \Illuminate\Support\Facades\Log::error('reCAPTCHA error: ' . $e->getMessage());
                }
            }];
        } else {
            $rules['captcha_input'] = [app()->environment('testing') ? 'nullable' : 'required', function ($attribute, $value, $fail) {
                if (app()->environment('testing')) {
                    return;
                }
                $captcha = new \App\Services\CaptchaService();
                if (!$captcha->verify($value)) {
                    $fail('Security CAPTCHA verification failed. Please solve the math challenge correctly.');
                }
            }];
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
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 1000)) {
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
