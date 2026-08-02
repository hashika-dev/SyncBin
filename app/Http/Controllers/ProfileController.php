<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Mail\EmailChangeVerificationMail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $validated = $request->validated();
        $newEmail = strtolower($validated['email']);

        // Check if email is being changed
        if ($newEmail !== strtolower($user->email)) {
            $code = (string) mt_rand(100000, 999999);

            $request->session()->put('pending_email_change', [
                'name' => $validated['name'],
                'email' => $newEmail,
                'code' => $code,
                'expires_at' => now()->addMinutes(15)->getTimestamp(),
            ]);

            // Send 6-digit OTP code directly to email via Brevo HTTPS API (for any email) or standard Mailer
            if (!empty(env('BREVO_API_KEY'))) {
                \App\Services\BrevoMailService::sendOtp($newEmail, $validated['name'], $code);
            } else {
                try {
                    Mail::to($newEmail)->send(new EmailChangeVerificationMail($code, $newEmail, $validated['name']));
                } catch (\Throwable $e) {
                    logger()->error('Failed to send verification email via mailer: ' . $e->getMessage());
                }
            }

            // Update name immediately if changed
            $user->name = $validated['name'];
            $user->save();

            return Redirect::route('profile.verify-email-change')
                ->with('status', 'A 6-digit OTP verification code has been generated for ' . $newEmail . '. Please check your inbox.');
        }

        $user->fill($validated);
        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Display the 6-digit email change verification form.
     */
    public function showVerifyEmailChange(Request $request): View|RedirectResponse
    {
        $pending = $request->session()->get('pending_email_change');

        if (!$pending) {
            return Redirect::route('profile.edit');
        }

        return view('profile.verify-email-change', [
            'pendingEmail' => $pending['email'] ?? '',
        ]);
    }

    /**
     * Confirm the 6-digit verification code for email change.
     */
    public function confirmEmailChange(Request $request): RedirectResponse
    {
        $pending = $request->session()->get('pending_email_change');

        if (!$pending) {
            return Redirect::route('profile.edit')->withErrors(['email' => 'No pending email change request found.']);
        }

        if (now()->getTimestamp() > $pending['expires_at']) {
            $request->session()->forget('pending_email_change');
            return Redirect::route('profile.edit')->withErrors(['email' => 'The verification code has expired. Please try updating your email again.']);
        }

        $inputCode = preg_replace('/[^0-9]/', '', (string) $request->input('code', ''));

        if ($inputCode !== $pending['code']) {
            return back()->withErrors(['code' => 'Invalid verification code. Please check your email and try again.']);
        }

        $user = $request->user();
        $user->email = $pending['email'];
        $user->name = $pending['name'];
        $user->email_verified_at = now();
        $user->save();

        $request->session()->forget('pending_email_change');

        return Redirect::route('profile.edit')->with('status', 'email-updated-successfully');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
