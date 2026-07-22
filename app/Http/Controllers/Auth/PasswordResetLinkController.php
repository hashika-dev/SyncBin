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
        $hasRealKeys = $siteKey && $siteKey !== '1x00000000000000000000AA' && $secretKey && $secretKey !== '1x0000000000000000000000000000000AA';

        $rules = [
            'email' => ['required', 'email'],
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
                        \Illuminate\Support\Facades\Log::warning('Turnstile reset link rejected token: ' . json_encode($response->json()));
                    }
                } catch (\Throwable $e) {
                    \Illuminate\Support\Facades\Log::warning('Turnstile reset link error: ' . $e->getMessage());
                }
            }];
        } else {
            $rules['cf-turnstile-response'] = ['nullable'];
        }

        $request->validate($rules);

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
