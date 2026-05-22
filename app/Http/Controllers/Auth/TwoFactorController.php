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

        if ($user->twoFactorAuth()->exists()) {
            return redirect()->route('dashboard')->with('status', 'Two-Factor Authentication is already enabled.');
        }

        // Generate the 2FA secret and QR Code
        $twoFactor = $user->twoFactorAuth()->create();

        return view('auth.two-factor-setup', [
            'qrCode' => $twoFactor->toQrCode(),
            'secret' => $twoFactor->shared_secret,
        ]);
    }

    /**
     * Enable 2FA for the user.
     */
    public function enable(Request $request)
    {
        $request->validate([
            'code' => 'required|string|min:6|max:6',
        ]);

        $user = Auth::user();
        $twoFactor = $user->twoFactorAuth()->first();

        if ($twoFactor->validate($request->code)) {
            $twoFactor->enabled_at = now();
            $twoFactor->save();

            return redirect()->route('dashboard')->with('status', 'Two-Factor Authentication has been enabled!');
        }

        return back()->withErrors(['code' => 'The provided code is invalid.']);
    }

    /**
     * Disable 2FA for the user.
     */
    public function disable()
    {
        Auth::user()->twoFactorAuth()->delete();

        return redirect()->route('dashboard')->with('status', 'Two-Factor Authentication has been disabled.');
    }
}
