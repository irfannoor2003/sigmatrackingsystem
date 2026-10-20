<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Clock-In Confirmation</title>
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
                        padding:22px 18px;
                        text-align:center;
                        color:#ffffff;
                    ">
                        <img src="{{ asset('images/logo.webp') }}"
                             alt="Company Logo"
                             style="
                                max-width:180px;
                                width:100%;
                                height:auto;
                                display:block;
                                margin:0 auto 6px auto;
                             ">

                        <p style="margin:0;font-size:13px;font-weight:600;">
                            Sigma Engineering Services
                        </p>

                        <p style="margin:6px 0 0 0;font-size:12px;opacity:0.9;">
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
                            ⏱ Clock-In Confirmation
                        </h2>

                        <p style="
                            font-size:14px;
                            line-height:1.65;
                            color:#374151;
                        ">
                             A <strong>clock-in request</strong> from your account.
                            Please confirm it by clicking the button below.
                        </p>

                        <!-- Button -->
                        <div style="text-align:center;margin:22px 0;">
                            <a href="{{ $link }}"
                               style="
                                   display:inline-block;
                                   padding:14px 28px;
                                   background:#ff2ba6;
                                   color:#ffffff;
                                   text-decoration:none;
                                   border-radius:8px;
                                   font-weight:600;
                                   font-size:15px;
                               ">
                                ✅ Confirm Clock-In
                            </a>
                        </div>

                        <p style="
                            color:#dc2626;
                            font-size:13px;
                            text-align:center;
                        ">
                            ⏳ This link will expire in <strong>1 minute</strong>
                        </p>

                        <!-- Divider -->
                        <div style="margin:26px 0;height:1px;background:#e5e7eb;"></div>

                        <p style="
                            font-size:12px;
                            color:#6b7280;
                            line-height:1.5;
                        ">
                            If you did not request this clock-in, you can safely ignore this email.
                            The link will expire automatically.
                        </p>

                        <!-- Signature -->
                        <p style="margin-top:18px;font-size:13px;color:#6b7280;">
                            Regards,
                        </p>

                        <p style="margin:4px 0 0 0;font-size:14px;font-weight:600;color:#111827;">
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
