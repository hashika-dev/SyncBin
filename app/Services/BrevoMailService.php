<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class BrevoMailService
{
    /**
     * Send an email via Brevo HTTPS API (Port 443) - Works 100% on Railway to ANY recipient.
     */
    public static function sendOtp(string $toEmail, string $toName, string $code): array
    {
        $apiKey = trim((string) env('BREVO_API_KEY'));

        if (empty($apiKey)) {
            return ['success' => false, 'error' => 'BREVO_API_KEY environment variable is empty.'];
        }

        try {
            $response = Http::withHeaders([
                'api-key' => $apiKey,
                'content-type' => 'application/json',
                'accept' => 'application/json',
            ])->post('https://api.brevo.com/v3/smtp/email', [
                'sender' => [
                    'name' => env('MAIL_FROM_NAME', 'SyncBin Security'),
                    'email' => 'kurtumali06@gmail.com',
                ],
                'to' => [
                    [
                        'email' => $toEmail,
                        'name' => $toName ?: 'User',
                    ]
                ],
                'subject' => 'SyncBin - Email Change Verification Code: ' . $code,
                'htmlContent' => '
                    <div style="font-family: Arial, sans-serif; padding: 20px; background-color: #fcf4f6;">
                        <div style="max-width: 480px; margin: 0 auto; background: #ffffff; padding: 30px; border-radius: 20px; border: 1px solid #fce7f3;">
                            <h2 style="color: #881337; text-align: center;">SyncBin Security Verification</h2>
                            <p>Hello <strong>' . htmlspecialchars($toName) . '</strong>,</p>
                            <p>Your 6-digit OTP verification code is:</p>
                            <div style="text-align: center; background: #fff1f2; padding: 15px; border-radius: 12px; font-size: 28px; font-weight: bold; letter-spacing: 6px; color: #9f1239; margin: 20px 0;">
                                ' . htmlspecialchars($code) . '
                            </div>
                            <p style="font-size: 12px; color: #6b7280; text-align: center;">This code expires in 15 minutes.</p>
                        </div>
                    </div>
                ',
            ]);

            if ($response->successful()) {
                logger()->info("Brevo OTP email successfully delivered to {$toEmail}");
                return ['success' => true, 'message' => 'Email delivered'];
            }

            $errorMsg = 'Brevo API (' . $response->status() . '): ' . $response->body();
            logger()->error($errorMsg);
            return ['success' => false, 'error' => $errorMsg];
        } catch (\Throwable $e) {
            $errorMsg = 'Brevo API Exception: ' . $e->getMessage();
            logger()->error($errorMsg);
            return ['success' => false, 'error' => $errorMsg];
        }
    }

    /**
     * Send Password Reset link via Brevo HTTPS API (Port 443).
     */
    public static function sendPasswordReset(string $toEmail, string $toName, string $resetUrl): bool
    {
        $apiKey = trim((string) env('BREVO_API_KEY'));

        if (empty($apiKey)) {
            return false;
        }

        try {
            $response = Http::withHeaders([
                'api-key' => $apiKey,
                'content-type' => 'application/json',
                'accept' => 'application/json',
            ])->post('https://api.brevo.com/v3/smtp/email', [
                'sender' => [
                    'name' => env('MAIL_FROM_NAME', 'SyncBin Security'),
                    'email' => 'kurtumali06@gmail.com',
                ],
                'to' => [
                    [
                        'email' => $toEmail,
                        'name' => $toName ?: 'User',
                    ]
                ],
                'subject' => 'SyncBin - Reset Your Password',
                'htmlContent' => '
                    <div style="font-family: Arial, sans-serif; padding: 20px; background-color: #fcf4f6;">
                        <div style="max-width: 500px; margin: 0 auto; background: #ffffff; padding: 30px; border-radius: 20px; border: 1px solid #fce7f3; box-shadow: 0 10px 30px rgba(0,0,0,0.05);">
                            <h2 style="color: #881337; text-align: center; margin-bottom: 20px;">SyncBin Password Reset</h2>
                            <p>Hello <strong>' . htmlspecialchars($toName) . '</strong>,</p>
                            <p>You are receiving this email because we received a password reset request for your SyncBin account.</p>
                            <div style="text-align: center; margin: 30px 0;">
                                <a href="' . htmlspecialchars($resetUrl) . '" style="background-color: #9f1239; color: #ffffff; padding: 14px 28px; text-decoration: none; font-weight: bold; border-radius: 12px; display: inline-block; font-size: 14px;">Reset Password</a>
                            </div>
                            <p style="font-size: 12px; color: #6b7280; text-align: center;">This password reset link will expire in 60 minutes. If you did not request a password reset, no further action is required.</p>
                        </div>
                    </div>
                ',
            ]);

            if ($response->successful()) {
                logger()->info("Brevo Password Reset link successfully sent to {$toEmail}");
                return true;
            }

            logger()->error("Brevo Password Reset API Error: " . $response->body());
            return false;
        } catch (\Throwable $e) {
            logger()->error("Brevo Password Reset API Exception: " . $e->getMessage());
            return false;
        }
    }
}
