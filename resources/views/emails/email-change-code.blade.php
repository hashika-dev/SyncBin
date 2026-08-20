<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>EcoSync - Email Change Verification</title>
</head>
<body style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #fcf4f6; margin: 0; padding: 40px 20px;">
    <div style="max-width: 500px; margin: 0 auto; background: #ffffff; border-radius: 24px; padding: 32px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); border: 1px solid #fce7f3;">
        
        <!-- Header -->
        <div style="text-align: center; margin-bottom: 24px;">
            <div style="display: inline-block; width: 48px; height: 48px; background: #ffe4e6; border-radius: 16px; padding: 8px; margin-bottom: 8px;">
                <img src="{{ asset('favicon.svg') }}" alt="EcoSync Logo" style="width: 100%; height: 100%; object-fit: contain;">
            </div>
            <h2 style="color: #4c0519; margin: 4px 0 0 0; font-size: 24px; font-weight: 800;">EcoSync Security</h2>
        </div>

        <div style="color: #374151; font-size: 14px; line-height: 1.6;">
            <p>Hello <strong>{{ $userName }}</strong>,</p>
            <p>You requested to change your account email address to <strong>{{ $newEmail }}</strong>.</p>
            <p>Please use the following 6-digit verification code to confirm this email change:</p>

            <!-- Verification Code Box -->
            <div style="background: #fff1f2; border: 2px dashed #f43f5e; border-radius: 16px; padding: 20px; text-align: center; margin: 24px 0;">
                <span style="font-family: monospace; font-size: 32px; font-weight: 900; letter-spacing: 8px; color: #881337;">{{ $code }}</span>
            </div>

            <p style="font-size: 12px; color: #6b7280; text-align: center;">This verification code will expire in <strong>15 minutes</strong>. If you did not request this change, please ignore this email or contact support immediately.</p>
        </div>

        <hr style="border: none; border-top: 1px solid #fecdd3; margin: 24px 0;">

        <div style="text-align: center; font-size: 11px; color: #9ca3af; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">
            Secure EcoSync Account Services
        </div>
    </div>
</body>
</html>
