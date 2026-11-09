@php
    // Email-safe version of each bin's dashboard color (solid hex, no gradients).
    $binColors = [
        'hazardous'     => '#dc2626',
        'recyclable'    => '#0284c7',
        'biodegradable' => '#059669',
        'non-bio'       => '#ea580c',
    ];
    $accent    = $binColors[$bin->slug] ?? '#475569';
    $level     = max(0, min(100, (int) $bin->level));
    $threshold = (int) config('bins.critical_threshold', 80);
    $readAt    = now()->format('M j, Y \a\t g:i A');
    $headline  = "{$bin->name} bin is {$level}% full";
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="x-apple-disable-message-reformatting">
    <title>EcoSync alert: {{ $headline }}</title>
</head>
<body style="margin:0; padding:0; background-color:#f1f5f9;">
    {{-- Preview text shown next to the subject in the inbox list --}}
    <div style="display:none; max-height:0; overflow:hidden; mso-hide:all;">
        Empty it soon, then press Empty on the dashboard.
    </div>

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#f1f5f9;">
        <tr>
            <td align="center" style="padding:32px 16px;">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width:520px; background-color:#ffffff; border:1px solid #e2e8f0; border-radius:8px; font-family:Arial, Helvetica, sans-serif;">

                    {{-- Header: wordmark (text, so it never breaks) --}}
                    <tr>
                        <td style="padding:20px 28px; border-bottom:1px solid #e2e8f0;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td style="font-size:16px; font-weight:bold; color:#0f172a;">EcoSync</td>
                                    <td align="right" style="font-size:12px; color:#64748b;">Bin alert</td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- Bin color strip --}}
                    <tr>
                        <td height="4" style="height:4px; line-height:4px; font-size:0; background-color:{{ $accent }};">&nbsp;</td>
                    </tr>

                    {{-- Main message --}}
                    <tr>
                        <td style="padding:28px 28px 8px 28px;">
                            <p style="margin:0 0 8px 0; font-size:12px; font-weight:bold; letter-spacing:1px; text-transform:uppercase; color:{{ $accent }};">
                                {{ $bin->name }} bin
                            </p>
                            <h1 style="margin:0; font-size:24px; line-height:1.3; font-weight:bold; color:#0f172a;">
                                {{ $headline }}
                            </h1>
                            <p style="margin:12px 0 0 0; font-size:15px; line-height:1.6; color:#475569;">
                                It passed the {{ $threshold }}% alert level. Please empty it soon so the sorter has room for the next items.
                            </p>
                        </td>
                    </tr>

                    {{-- Fill bar (table cells, works in every email app) --}}
                    <tr>
                        <td style="padding:20px 28px 4px 28px;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td style="font-size:12px; color:#64748b; padding-bottom:6px;">Fill level</td>
                                    <td align="right" style="font-size:12px; font-weight:bold; color:#0f172a; padding-bottom:6px;">{{ $level }}%</td>
                                </tr>
                            </table>
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#e2e8f0;">
                                <tr>
                                    @if ($level > 0)
                                        <td width="{{ $level }}%" height="10" style="height:10px; line-height:10px; font-size:0; background-color:{{ $accent }};">&nbsp;</td>
                                    @endif
                                    @if ($level < 100)
                                        <td height="10" style="height:10px; line-height:10px; font-size:0;">&nbsp;</td>
                                    @endif
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- Details --}}
                    <tr>
                        <td style="padding:20px 28px 0 28px;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="font-size:14px; color:#0f172a;">
                                <tr>
                                    <td style="padding:10px 0; border-top:1px solid #e2e8f0; color:#64748b;">Reading</td>
                                    <td align="right" style="padding:10px 0; border-top:1px solid #e2e8f0;">{{ $level }}% full</td>
                                </tr>
                                <tr>
                                    <td style="padding:10px 0; border-top:1px solid #e2e8f0; border-bottom:1px solid #e2e8f0; color:#64748b;">Sent</td>
                                    <td align="right" style="padding:10px 0; border-top:1px solid #e2e8f0; border-bottom:1px solid #e2e8f0;">{{ $readAt }}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- What to do --}}
                    <tr>
                        <td style="padding:20px 28px 0 28px; font-size:14px; line-height:1.6; color:#475569;">
                            After emptying it, press <strong style="color:#0f172a;">Empty</strong> on the dashboard so the level resets to 0%. Until then, a reminder is sent every 30 minutes.
                        </td>
                    </tr>

                    {{-- Button --}}
                    <tr>
                        <td style="padding:24px 28px 28px 28px;">
                            <table role="presentation" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td bgcolor="#0f172a" style="border-radius:6px; background-color:#0f172a;">
                                        <a href="{{ url('/dashboard') }}" style="display:inline-block; padding:12px 24px; font-size:14px; font-weight:bold; color:#ffffff; text-decoration:none; border-radius:6px;">
                                            Open dashboard
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="padding:16px 28px; border-top:1px solid #e2e8f0; font-size:12px; line-height:1.5; color:#64748b;">
                            Sent automatically by EcoSync when a bin passes {{ $threshold }}% full. Replies to this email are not read.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
