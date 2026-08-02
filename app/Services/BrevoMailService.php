<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class BrevoMailService
{
    /**
     * Send an email via Brevo HTTPS API (Port 443) - Works 100% on Railway to ANY recipient.
     */
    public static function sendOtp(string $toEmail, string $toName, string $code): bool
    {
        $apiKey = env('BREVO_API_KEY');

        if (empty($apiKey)) {
            logger()->warning('Brevo API Key is missing in environment.');
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
                    'email' => env('MAIL_FROM_ADDRESS', 'no-reply@syncbin.app'),
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
                return true;
            }

            logger()->error("Brevo API error response: " . $response->body());
            return false;
        } catch (\Throwable $e) {
            logger()->error("Brevo API exception: " . $e->getMessage());
            return false;
        }
    }
}
