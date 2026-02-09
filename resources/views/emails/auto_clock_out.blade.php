<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Auto Clock-Out Notice</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body style="
    margin:0;
    padding:0;
    font-family: Arial, 'Segoe UI', sans-serif;
    background:#f3f4f6;
">

<table width="100%" cellpadding="0" cellspacing="0" style="padding:20px 10px;">
    <tr>
        <td align="center">

            <!-- Card -->
            <table width="100%" cellpadding="0" cellspacing="0" style="
                max-width:600px;
                background:#ffffff;
                border-radius:14px;
                overflow:hidden;
                box-shadow:0 12px 30px rgba(0,0,0,0.25);
                border:1px solid #ff2ba6;
            ">

                <!-- Header -->
                <tr>
                    <td style="
                        background:linear-gradient(135deg,#ff2ba6,#ff5fcf);
                        padding:26px 18px;
                        text-align:center;
                        color:#ffffff;
                    ">
                        <h1 style="
                            margin:0;
                            font-size:20px;
                            font-weight:700;
                            letter-spacing:0.5px;
                        ">
                            Sigma Engineering Services
                        </h1>

                        <p style="
                            margin:6px 0 0 0;
                            font-size:12px;
                            opacity:0.9;
                        ">
                            Attendance Notification
                        </p>
                    </td>
                </tr>

                <!-- Body -->
                <tr>
                    <td style="padding:22px 18px;">

                        <h2 style="
                            margin:0 0 12px 0;
                            color:#111827;
                            font-size:18px;
                        ">
                            ⏰ Auto Clock-Out Notice
                        </h2>

                        <p style="
                            font-size:14px;
                            line-height:1.65;
                            color:#374151;
                        ">
                            Hello <strong>{{ $attendance->user->name }}</strong>,
                        </p>

                        <p style="
                            font-size:14px;
                            line-height:1.65;
                            color:#374151;
                        ">
                            You were <strong>automatically clocked out</strong> at
                            <strong>8:00 PM</strong> because no manual clock-out
                            was recorded for today.
                        </p>

                        <!-- Info Box -->
                        <table width="100%" cellpadding="0" cellspacing="0" style="
                            margin-top:16px;
                            background:#fdf2f8;
                            border:1px solid #fbcfe8;
                            border-radius:10px;
                        ">
                            <tr>
                                <td style="padding:14px;font-size:13px;color:#374151;">
                                    <strong>Date:</strong> {{ $attendance->date }} <br>
                                    <strong>Clock In:</strong> {{ $attendance->clock_in }} <br>
                                    <strong>Clock Out:</strong> 8:00 PM (Auto)
                                </td>
                            </tr>
                        </table>

                        <p style="
                            margin-top:16px;
                            font-size:14px;
                            line-height:1.65;
                            color:#374151;
                        ">
                            If this auto clock-out is incorrect, please contact
                            the HR department for correction.
                        </p>

                        <!-- Divider -->
                        <div style="margin:26px 0;height:1px;background:#e5e7eb;"></div>

                        <!-- Signature -->
                        <p style="margin-top:18px;font-size:13px;color:#6b7280;">
                            Regards,
                        </p>

                        <p style="margin:4px 0 0 0;font-size:14px;font-weight:600;color:#111827;">
                            HR Team<br>
                            Sigma Engineering Services
                        </p>

                    </td>
                </tr>

            </table>

            <!-- Footer -->
            <p style="
                margin-top:14px;
                font-size:11px;
                color:#9ca3af;
                text-align:center;
            ">
                © {{ date('Y') }} {{ config('app.name') }} · All rights reserved
            </p>

        </td>
    </tr>
</table>

</body>
</html>
