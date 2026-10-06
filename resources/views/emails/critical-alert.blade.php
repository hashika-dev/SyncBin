<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoSync - Critical Capacity Alert</title>
</head>
<body style="margin: 0; padding: 40px 16px; background-color: #f1f5f9; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; -webkit-font-smoothing: antialiased;">
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 560px; margin: 0 auto; background-color: #ffffff; border-radius: 20px; border: 1px solid #e2e8f0; box-shadow: 0 16px 36px -4px rgba(15, 23, 42, 0.08), 0 4px 12px rgba(0, 0, 0, 0.03); overflow: hidden;">
        <!-- Header Banner with Brand Accent -->
        <tr>
            <td style="padding: 32px 32px 24px 32px; background: linear-gradient(135deg, #064e3b 0%, #065f46 100%); text-align: center; color: #ffffff;">
                <div style="display: inline-block; width: 48px; height: 48px; background-color: rgba(255, 255, 255, 0.15); border: 1px solid rgba(255, 255, 255, 0.25); border-radius: 14px; padding: 10px; margin-bottom: 12px;">
                    <img src="{{ asset('favicon.svg') }}" alt="System Logo" style="width: 100%; height: 100%; object-fit: contain; display: block; filter: brightness(0) invert(1);">
                </div>
                <h1 style="margin: 0; font-size: 22px; font-weight: 800; letter-spacing: -0.5px; color: #ffffff;">EcoSync Telemetry Alert</h1>
                <p style="margin: 6px 0 0 0; font-size: 13px; color: #a7f3d0; font-weight: 500;">Waste Infrastructure Capacity Monitor</p>
            </td>
        </tr>

        <!-- Alert Status Ribbon -->
        <tr>
            <td style="background-color: #fef2f2; border-bottom: 1px solid #fee2e2; padding: 12px 24px; text-align: center;">
                <span style="display: inline-block; font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 1.2px; color: #dc2626;">
                    ⚠️ Attention Required &bull; Evacuation Threshold Crossed
                </span>
            </td>
        </tr>

        <!-- Main Content Body -->
        <tr>
            <td style="padding: 32px; color: #334155; font-size: 14px; line-height: 1.6;">
                <p style="margin: 0 0 14px 0; font-size: 15px; color: #0f172a;">Hello <strong>Administrator</strong>,</p>
                <p style="margin: 0 0 20px 0; color: #475569;">
                    The real-time sensor array has reported that a containment stream has crossed critical operating capacity and requires immediate clearance:
                </p>

                <!-- Diagnostic Metric Card -->
                <div style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 16px; padding: 20px; margin: 20px 0;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <tr>
                            <td style="padding: 8px 0; color: #64748b; font-size: 13px; font-weight: 600; width: 130px;">Containment Stream:</td>
                            <td style="padding: 8px 0; color: #0f172a; font-size: 15px; font-weight: 700;">{{ $bin->name }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 8px 0; color: #64748b; font-size: 13px; font-weight: 600;">Fill Level:</td>
                            <td style="padding: 8px 0;">
                                <span style="font-size: 18px; font-weight: 800; color: #dc2626;">{{ $bin->level }}%</span>
                                <span style="font-size: 12px; color: #64748b; font-weight: 500;">(Capacity Limit Reached)</span>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 8px 0; color: #64748b; font-size: 13px; font-weight: 600;">System Status:</td>
                            <td style="padding: 8px 0;">
                                <span style="display: inline-block; background-color: #dc2626; color: #ffffff; padding: 3px 10px; border-radius: 6px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">
                                    {{ $bin->status }}
                                </span>
                            </td>
                        </tr>
                    </table>

                    <!-- Capacity Progress Bar -->
                    <div style="margin-top: 16px; padding-top: 16px; border-top: 1px solid #e2e8f0;">
                        <div style="display: flex; justify-content: space-between; font-size: 11px; color: #64748b; margin-bottom: 6px; font-weight: 600;">
                            <span>CONTAINMENT FILL</span>
                            <span style="color: #dc2626; font-weight: 700;">{{ $bin->level }}%</span>
                        </div>
                        <div style="width: 100%; height: 10px; background-color: #e2e8f0; border-radius: 999px; overflow: hidden;">
                            <div style="width: {{ min(100, (int)$bin->level) }}%; height: 100%; background: linear-gradient(90deg, #ea580c 0%, #dc2626 100%); border-radius: 999px;"></div>
                        </div>
                    </div>
                </div>

                <p style="font-size: 13px; color: #64748b; line-height: 1.5; margin: 18px 0 26px 0;">
                    To reset this warning, please physically clear the bin and click <strong>"Empty Bin"</strong> on the EcoSync web control console.
                </p>

                <!-- Call to Action Button -->
                <div style="text-align: center; margin: 28px 0 8px 0;">
                    <a href="{{ url('/dashboard') }}" style="display: inline-block; background-color: #059669; color: #ffffff; text-decoration: none; padding: 14px 32px; font-weight: 700; font-size: 14px; border-radius: 12px; box-shadow: 0 4px 14px rgba(5, 150, 105, 0.35); letter-spacing: 0.3px;">
                        Open Control Panel &rarr;
                    </a>
                </div>
            </td>
        </tr>

        <!-- Footer -->
        <tr>
            <td style="background-color: #f8fafc; padding: 20px 32px; text-align: center; font-size: 11px; color: #94a3b8; border-top: 1px solid #e2e8f0; line-height: 1.5;">
                EcoSync Automated Telemetry &bull; Station Waste Management Node<br>
                <span style="font-size: 10px; color: #cbd5e1;">This is a system generated notification. Please do not reply directly to this email.</span>
            </td>
        </tr>
    </table>
</body>
</html>
