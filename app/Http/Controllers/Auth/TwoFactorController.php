<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TwoFactorController extends Controller
{
    /**
     * Show the 2FA setup page.
     */
    public function showSetup()
    {
        $user = Auth::user();

        if ($user->hasTwoFactorEnabled()) {
            return redirect()->route('dashboard')->with('status', 'Two-Factor Authentication is already enabled.');
        }

        // Reuse unconfirmed 2FA secret so the QR code stays constant during verification
        $twoFactor = $user->twoFactorAuth()->first() ?? $user->createTwoFactorAuth();

        return view('auth.two-factor-setup', [
            'qrCode' => $twoFactor->toQr(),
            'secret' => $twoFactor->shared_secret,
        ]);
    }

    /**
     * Enable 2FA for the user.
     */
    public function enable(Request $request)
    {
        // Strip all spaces, dashes, and non-numeric characters from the input code
        $code = preg_replace('/[^0-9]/', '', (string) $request->input('code', ''));

        if (strlen($code) !== 6) {
            return back()->withErrors(['code' => 'Please enter a valid 6-digit code from your authenticator app.']);
        }

        $user = Auth::user();

        if ($user->confirmTwoFactorAuth($code)) {
            return redirect()->route('dashboard')->with('status', 'Two-Factor Authentication has been successfully enabled!');
        }

        return back()->withErrors(['code' => 'The provided 6-digit code is invalid. Please check your authenticator app and try again.']);
    }

    /**
     * Disable 2FA for the user.
     */
    public function disable()
    {
        if (Auth::user()->hasTwoFactorEnabled()) {
            Auth::user()->disableTwoFactorAuth();
        }

        return redirect()->route('dashboard')->with('status', 'Two-Factor Authentication has been disabled.');
    }

    /**
     * Reset and regenerate a fresh 2FA secret for setup.
     */
    public function resetSetup()
    {
        $user = Auth::user();
        if (!$user->hasTwoFactorEnabled()) {
            $user->createTwoFactorAuth();
        }
        return redirect()->route('2fa.setup')->with('status', 'Generated a fresh QR Code!');
    }
}
