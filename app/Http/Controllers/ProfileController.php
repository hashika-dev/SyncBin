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

            // Send email verification code safely (fallback to log mailer if SMTP is unreachable/times out)
            try {
                Mail::to($newEmail)->send(new EmailChangeVerificationMail($code, $newEmail, $validated['name']));
            } catch (\Throwable $e) {
                logger()->error('SMTP email send failed, using log mailer fallback: ' . $e->getMessage());
                try {
                    Mail::mailer('log')->to($newEmail)->send(new EmailChangeVerificationMail($code, $newEmail, $validated['name']));
                } catch (\Throwable $ex) {
                    logger()->error('Fallback log mailer failed: ' . $ex->getMessage());
                }
            }

            // Update name immediately if changed
            $user->name = $validated['name'];
            $user->save();

            return Redirect::route('profile.verify-email-change')
                ->with('status', 'A 6-digit verification code has been sent to ' . $newEmail)
                ->with('dev_code', $code);
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
