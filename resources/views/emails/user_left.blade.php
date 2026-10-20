@include('emails._header', ['title' => 'Account Marked As Left'])

<tr>
    <td style="padding:22px 18px;">
        <h2 style="margin:0 0 12px 0;color:#111827;font-size:18px;">👋 You Left {{ config('app.name') }}</h2>

        <p style="font-size:14px;line-height:1.65;color:#374151;">Dear {{ $user->name }},</p>

        <p style="font-size:14px;line-height:1.65;color:#374151;">You have been marked as a left member of {{ config('app.name') }} on <strong>{{ $user->left_at ? $user->left_at->format('d M Y') : '-' }}</strong>. Your access to the panel has been removed.</p>

        @if($user->left_reason)
            <p style="font-size:14px;line-height:1.65;color:#374151;">Reason: <strong>{{ $user->left_reason }}</strong></p>
        @endif

        <p style="font-size:14px;line-height:1.65;color:#374151;">If you believe this is a mistake, please contact your administrator.</p>

        <div style="margin:26px 0;height:1px;background:#e5e7eb;"></div>

        <p style="margin-top:18px;font-size:13px;color:#6b7280;">Regards,</p>
        <p style="margin:4px 0 0 0;font-size:14px;font-weight:600;color:#111827;">Support Team<br>{{ config('app.name') }}</p>
    </td>
</tr>

@include('emails._footer')