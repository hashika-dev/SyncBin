<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoSync - Reset Your Password</title>
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
                        Password Reset Request
                    </div>
                </div>

                <!-- Message Body -->
                <div style="color: #334155; font-size: 14px; line-height: 1.6;">
                    <p style="margin: 0 0 16px 0;">Hello <strong>{{ $toName ?: 'User' }}</strong>,</p>
                    <p style="margin: 0 0 16px 0;">We received a request to reset the password for your EcoSync account. Click the button below to choose a new password:</p>

                    <!-- Reset Button -->
                    <div style="text-align: center; margin: 28px 0;">
                        <a href="{{ $resetUrl }}" style="display: inline-block; background-color: #059669; color: #ffffff; text-decoration: none; font-size: 14px; font-weight: 700; padding: 14px 28px; border-radius: 12px; box-shadow: 0 4px 14px rgba(5, 150, 105, 0.35); letter-spacing: 0.2px;">Reset Password</a>
                    </div>

                    <p style="font-size: 12px; color: #64748b; line-height: 1.5; text-align: center; margin: 0 0 8px 0;">This password reset link will expire in <strong>60 minutes</strong>.</p>
                    <p style="font-size: 12px; color: #94a3b8; line-height: 1.5; text-align: center; margin: 0;">If you did not request a password reset, no further action is required and your account remains safe.</p>
                </div>

                <hr style="border: none; border-top: 1px solid #e2e8f0; margin: 28px 0 20px 0;">

                <!-- Footer -->
                <div style="text-align: center; font-size: 11px; color: #94a3b8; line-height: 1.5;">
                    EcoSync Automated Telemetry &bull; High-Assurance Waste Infrastructure<br>
                    <span style="font-size: 10px; color: #cbd5e1;">This is an automated notification. Please do not reply directly to this email.</span>
                </div>
            </td>
        </tr>
    </table>
</body>
</html>
