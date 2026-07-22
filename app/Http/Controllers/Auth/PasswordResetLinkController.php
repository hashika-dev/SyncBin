<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $siteKey = config('services.turnstile.key');
        $secretKey = config('services.turnstile.secret');
        $isDummyKey = !$siteKey || $siteKey === '1x00000000000000000000AA' || !$secretKey || $secretKey === '1x0000000000000000000000000000000AA';

        $request->validate([
            'email' => ['required', 'email'],
            'cf-turnstile-response' => (app()->environment('local') || app()->runningUnitTests() || $isDummyKey) ? ['nullable'] : ['required', function ($attribute, $value, $fail) use ($secretKey) {
                try {
                    $response = \Illuminate\Support\Facades\Http::timeout(3)->asForm()->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
                        'secret' => $secretKey,
                        'response' => $value,
                        'remoteip' => request()->ip(),
                    ]);

                    if (! $response->json('success')) {
                        $fail('The CAPTCHA verification failed. Please try again.');
                    }
                } catch (\Throwable $e) {
                    \Illuminate\Support\Facades\Log::warning('Turnstile reset link verification skipped due to error: ' . $e->getMessage());
                }
            }],
        ]);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status == Password::RESET_LINK_SENT
                    ? back()->with('status', __($status))
                    : back()->withInput($request->only('email'))
                        ->withErrors(['email' => __($status)]);
    }
}
