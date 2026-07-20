<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
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

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('status', 'password-updated');
    }
}
