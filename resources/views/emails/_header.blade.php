<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body style="margin:0;padding:0;font-family: Arial, 'Segoe UI', sans-serif;background:#f3f4f6;">

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
            <h1 style="margin:0;font-size:20px;font-weight:700;letter-spacing:0.5px;">
                {{ config('app.name') }}
            </h1>

            <p style="margin:6px 0 0 0;font-size:12px;opacity:0.9;">
                {{ $title ?? '' }}
            </p>
        </td>
    </tr>

