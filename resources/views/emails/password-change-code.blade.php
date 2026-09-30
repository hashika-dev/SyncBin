<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoSync - Password Change Verification</title>
</head>
<body style="margin: 0; padding: 40px 16px; background-color: #f0fdf4; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; -webkit-font-smoothing: antialiased;">
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 520px; margin: 0 auto; background-color: #ffffff; border-radius: 20px; border: 1px solid #d1fae5; box-shadow: 0 12px 36px -4px rgba(16, 185, 129, 0.08), 0 4px 12px rgba(0, 0, 0, 0.04); overflow: hidden;">
        <tr>
            <td style="padding: 36px 32px;">
                <!-- Brand Header -->
                <div style="text-align: center; margin-bottom: 28px;">
                    <div style="display: inline-block; width: 44px; height: 44px; background-color: #ecfdf5; border: 1px solid #a7f3d0; border-radius: 14px; padding: 8px; margin-bottom: 12px;">
                        <img src="{{ asset('favicon.svg') }}" alt="System Logo" style="width: 100%; height: 100%; object-fit: contain; display: block;">
                    </div>
                    <h1 style="margin: 0; font-size: 22px; font-weight: 800; color: #064e3b; letter-spacing: -0.5px;">EcoSync</h1>
                    <div style="display: inline-block; margin-top: 6px; padding: 3px 10px; background-color: #ecfdf5; border: 1px solid #a7f3d0; border-radius: 20px; font-size: 10px; font-weight: 700; color: #047857; text-transform: uppercase; letter-spacing: 1px;">
                        Password Change Verification
                    </div>
                </div>

                <!-- Message Body -->
                <div style="color: #334155; font-size: 14px; line-height: 1.6;">
                    <p style="margin: 0 0 14px 0;">Hello <strong>{{ $userName ?: 'User' }}</strong>,</p>
                    <p style="margin: 0 0 14px 0;">We received a request to update the password for your EcoSync account associated with:
                        <br>
                        <span style="display: inline-block; margin-top: 6px; background-color: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; font-weight: 600; padding: 3px 10px; border-radius: 6px; font-size: 13px;">{{ $userEmail }}</span>
                    </p>
                    <p style="margin: 0 0 20px 0;">Please use the 6-digit OTP verification code below to authorize and complete this password change:</p>

                    <!-- OTP Code Box -->
                    <div style="background-color: #f0fdf4; border: 2px dashed #10b981; border-radius: 16px; padding: 22px 16px; text-align: center; margin: 24px 0;">
                        <div style="font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; color: #047857; margin-bottom: 8px;">6-Digit OTP Code</div>
                        <div style="font-family: Consolas, 'Courier New', Courier, monospace; font-size: 34px; font-weight: 800; letter-spacing: 10px; color: #065f46; text-indent: 10px;">{{ $code }}</div>
                        <div style="margin-top: 10px; font-size: 12px; color: #059669; font-weight: 500;">Expires in 15 minutes</div>
                    </div>

                    <p style="font-size: 12px; color: #64748b; line-height: 1.5; text-align: center; margin: 0;">If you did not initiate this password change, someone may be attempting to access your account. Please secure your account or notify your system administrator immediately.</p>
                </div>

                <hr style="border: none; border-top: 1px solid #e2e8f0; margin: 28px 0 20px 0;">

                <!-- Footer -->
                <div style="text-align: center; font-size: 11px; color: #94a3b8; line-height: 1.5;">
                    EcoSync Automated Telemetry &bull; High-Assurance Waste Infrastructure<br>
                    <span style="font-size: 10px; color: #cbd5e1;">This is an automated security notification. Please do not reply directly to this email.</span>
                </div>
            </td>
        </tr>
    </table>
</body>
</html>
