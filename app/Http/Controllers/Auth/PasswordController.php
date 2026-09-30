<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\PasswordChangeVerificationMail;
use App\Services\BrevoMailService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class PasswordController extends Controller
{
    /**
     * Initiate password update by validating input and issuing a 6-digit OTP.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        // Security Policy: Prevent changing password if user is still using demo/default email (@wastesync.com)
        if (str_ends_with(strtolower($user->email), '@wastesync.com')) {
            return back()->withErrors([
                'current_password' => 'Security Restriction: You must update your email address to a valid personal or company email above before changing your password. Default demo emails (@wastesync.com) cannot receive password recovery links.',
            ], 'updatePassword');
        }

        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $code = (string) mt_rand(100000, 999999);

        $request->session()->put('pending_password_change', [
            'password_hash' => Hash::make($validated['password']),
            'code' => $code,
            'expires_at' => now()->addMinutes(15)->getTimestamp(),
        ]);

        // Send 6-digit OTP code directly to user email via Brevo HTTPS API or standard Mailer
        if (!empty(env('BREVO_API_KEY'))) {
            $result = BrevoMailService::sendPasswordChangeOtp($user->email, $user->name, $code);
            if (!$result['success']) {
                $request->session()->forget('pending_password_change');
                return back()->withErrors([
                    'current_password' => 'OTP Dispatch Alert: ' . $result['error'],
                ], 'updatePassword');
            }
        } else {
            try {
                Mail::to($user->email)->send(new PasswordChangeVerificationMail($code, $user->email, $user->name));
            } catch (\Throwable $e) {
                logger()->error('Failed to send password verification email via mailer: ' . $e->getMessage());
                $request->session()->forget('pending_password_change');
                return back()->withErrors([
                    'current_password' => 'OTP Dispatch Alert: ' . $e->getMessage(),
                ], 'updatePassword');
            }
        }

        return redirect()->route('password.verify-change')
            ->with('status', 'A 6-digit OTP verification code has been dispatched to ' . $user->email . '. Please check your inbox.');
    }

    /**
     * Display the 6-digit password change verification form.
     */
    public function showVerifyPasswordChange(Request $request): View|RedirectResponse
    {
        $pending = $request->session()->get('pending_password_change');

        if (!$pending) {
            return redirect()->route('profile.edit');
        }

        return view('profile.verify-password-change', [
            'userEmail' => $request->user()->email,
        ]);
    }

    /**
     * Confirm the 6-digit verification code and finalize the password update.
     */
    public function confirmPasswordChange(Request $request): RedirectResponse
    {
        $pending = $request->session()->get('pending_password_change');

        if (!$pending) {
            return redirect()->route('profile.edit')->withErrors([
                'current_password' => 'No pending password change request found.',
            ], 'updatePassword');
        }

        if (now()->getTimestamp() > $pending['expires_at']) {
            $request->session()->forget('pending_password_change');
            return redirect()->route('profile.edit')->withErrors([
                'current_password' => 'The verification code has expired. Please try updating your password again.',
            ], 'updatePassword');
        }

        $inputCode = preg_replace('/[^0-9]/', '', (string) $request->input('code', ''));

        if ($inputCode !== $pending['code']) {
            return back()->withErrors(['code' => 'Invalid verification code. Please check your email and try again.']);
        }

        $user = $request->user();
        $user->password = $pending['password_hash'];
        $user->save();

        $request->session()->forget('pending_password_change');

        return redirect()->route('profile.edit')->with('status', 'password-updated');
    }

    /**
     * Resend a fresh 6-digit OTP verification code for password change.
     */
    public function resendOtp(Request $request): RedirectResponse
    {
        $pending = $request->session()->get('pending_password_change');

        if (!$pending) {
            return redirect()->route('profile.edit');
        }

        $user = $request->user();
        $code = (string) mt_rand(100000, 999999);
        $pending['code'] = $code;
        $pending['expires_at'] = now()->addMinutes(15)->getTimestamp();
        $request->session()->put('pending_password_change', $pending);

        if (!empty(env('BREVO_API_KEY'))) {
            $result = BrevoMailService::sendPasswordChangeOtp($user->email, $user->name, $code);
            if (!$result['success']) {
                return back()->withErrors(['code' => 'OTP Dispatch Alert: ' . $result['error']]);
            }
        } else {
            try {
                Mail::to($user->email)->send(new PasswordChangeVerificationMail($code, $user->email, $user->name));
            } catch (\Throwable $e) {
                return back()->withErrors(['code' => 'OTP Dispatch Alert: ' . $e->getMessage()]);
            }
        }

        return back()->with('status', 'A new 6-digit verification code has been dispatched to your email.');
    }
}
