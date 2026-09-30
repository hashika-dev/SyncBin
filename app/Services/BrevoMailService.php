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
            $htmlContent = view('emails.email-change-code', [
                'code' => $code,
                'newEmail' => $toEmail,
                'userName' => $toName,
            ])->render();

            $response = Http::withHeaders([
                'api-key' => $apiKey,
                'content-type' => 'application/json',
                'accept' => 'application/json',
            ])->post('https://api.brevo.com/v3/smtp/email', [
                'sender' => [
                    'name' => env('MAIL_FROM_NAME', 'EcoSync Security'),
                    'email' => env('MAIL_FROM_ADDRESS', 'kurtumali06@gmail.com'),
                ],
                'to' => [
                    [
                        'email' => $toEmail,
                        'name' => $toName ?: 'User',
                    ]
                ],
                'subject' => 'EcoSync - Email Change Verification Code: ' . $code,
                'htmlContent' => $htmlContent,
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
     * Send password change OTP verification email via Brevo HTTPS API (Port 443).
     */
    public static function sendPasswordChangeOtp(string $toEmail, string $toName, string $code): array
    {
        $apiKey = trim((string) env('BREVO_API_KEY'));

        if (empty($apiKey)) {
            return ['success' => false, 'error' => 'BREVO_API_KEY environment variable is empty.'];
        }

        try {
            $htmlContent = view('emails.password-change-code', [
                'code' => $code,
                'userEmail' => $toEmail,
                'userName' => $toName,
            ])->render();

            $response = Http::withHeaders([
                'api-key' => $apiKey,
                'content-type' => 'application/json',
                'accept' => 'application/json',
            ])->post('https://api.brevo.com/v3/smtp/email', [
                'sender' => [
                    'name' => env('MAIL_FROM_NAME', 'EcoSync Security'),
                    'email' => env('MAIL_FROM_ADDRESS', 'kurtumali06@gmail.com'),
                ],
                'to' => [
                    [
                        'email' => $toEmail,
                        'name' => $toName ?: 'User',
                    ]
                ],
                'subject' => 'EcoSync - Password Change Verification Code: ' . $code,
                'htmlContent' => $htmlContent,
            ]);

            if ($response->successful()) {
                logger()->info("Brevo Password Change OTP email successfully delivered to {$toEmail}");
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
            $htmlContent = view('emails.password-reset', [
                'toName' => $toName,
                'resetUrl' => $resetUrl,
            ])->render();

            $response = Http::withHeaders([
                'api-key' => $apiKey,
                'content-type' => 'application/json',
                'accept' => 'application/json',
            ])->post('https://api.brevo.com/v3/smtp/email', [
                'sender' => [
                    'name' => env('MAIL_FROM_NAME', 'EcoSync Security'),
                    'email' => env('MAIL_FROM_ADDRESS', 'kurtumali06@gmail.com'),
                ],
                'to' => [
                    [
                        'email' => $toEmail,
                        'name' => $toName ?: 'User',
                    ]
                ],
                'subject' => 'EcoSync - Reset Your Password',
                'htmlContent' => $htmlContent,
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
