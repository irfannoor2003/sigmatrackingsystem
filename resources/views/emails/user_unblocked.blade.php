@include('emails._header', ['title' => 'Account Unblocked'])

<tr>
    <td style="padding:22px 18px;">
        <h2 style="margin:0 0 12px 0;color:#111827;font-size:18px;">✅ Account Unblocked</h2>

        <p style="font-size:14px;line-height:1.65;color:#374151;">Dear {{ $user->name }},</p>

        <p style="font-size:14px;line-height:1.65;color:#374151;">Your account has been unblocked by the administrator. You can now access the panel and mark attendance as usual.</p>

        <div style="margin:26px 0;height:1px;background:#e5e7eb;"></div>

        <p style="margin-top:18px;font-size:13px;color:#6b7280;">Regards,</p>
        <p style="margin:4px 0 0 0;font-size:14px;font-weight:600;color:#111827;">Support Team<br>{{ config('app.name') }}</p>
    </td>
</tr>

@include('emails._footer')
