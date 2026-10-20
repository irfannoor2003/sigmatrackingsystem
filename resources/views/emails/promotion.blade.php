<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $subjectText }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body style="
    margin:0;
    padding:0;
    font-family: Arial, 'Segoe UI', sans-serif;
">

<!-- Wrapper -->
<table width="100%" cellpadding="0" cellspacing="0" style="padding:20px 10px; ">
    <tr>
        <td align="center">

            <!-- Card Container -->
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



<p style="
    margin:0;
    font-size:13px;
    font-weight:600;
    letter-spacing:0.4px;
">
    Sigma Engineering Services
</p>


                        <p style="
                            margin:6px 0 0 0;
                            font-size:12px;
                            opacity:0.9;
                        ">
                            Notification Alert
                        </p>
                    </td>
                </tr>

                <!-- Body -->
                <tr>
                    <td style="padding:22px 18px;">

                        <h2 style="
                            margin:0 0 14px 0;
                            color:#111827;
                            font-size:18px;
                        ">
                            Hello 👋
                        </h2>

                        <div style="
                            font-size:14px;
                            line-height:1.65;
                            color:#374151;
                        ">
                            {!! nl2br(e($content)) !!}
                        </div>

                        <!-- Divider -->
                        <div style="
                            margin:26px 0;
                            height:1px;
                            background:#e5e7eb;
                        "></div>

                        <!-- Signature -->
                        <table width="100%">
                            <tr>
                                <td>
                                    <p style="
                                        font-size:13px;
                                        color:#6b7280;
                                        margin:0;
                                    ">
                                        Regards,
                                    </p>

                                    <p style="
                                        margin:5px 0 0 0;
                                        font-size:14px;
                                        font-weight:600;
                                        color:#111827;
                                    ">
                                        Sigma Engineering Services
                                    </p>

                                    <p style="
                                        margin:2px 0 0 0;
                                        font-size:12px;
                                        color:#9ca3af;
                                        text-transform:capitalize;
                                    ">
                                        Call Us: +92-321 4428 202
                                    </p>
                                    <p style="
                                        margin:2px 0 0 0;
                                        font-size:12px;
                                        color:#9ca3af;
                                        text-transform:capitalize;
                                    ">
                                        Location: 2-B, Agro square Flats, Shadman Market, Lahore Punjab
                                    </p>
                                </td>
                            </tr>
                        </table>

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
                © {{ date('Y') }} {{ config('app.name') }} . All rights reserved
            </p>

        </td>
    </tr>
</table>

</body>
</html>
