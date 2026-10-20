@include('emails._header', ['title' => 'Welcome to the Team'])

<tr>
    <td style="padding:22px 18px;">
        <h2 style="margin:0 0 12px 0;color:#111827;font-size:18px;">👋 Welcome to {{ config('app.name') }}, {{ $user->name }}!</h2>

        <p style="font-size:14px;line-height:1.65;color:#374151;">Dear {{ $user->name }},</p>

        <p style="font-size:14px;line-height:1.65;color:#374151;">Your account has been created and you are now a member of {{ config('app.name') }}.</p>

        <div style="margin:18px 0;padding:16px;background:#f9fafb;border:1px solid #e5e7eb;border-radius:10px;">
            <p style="margin:0 0 8px 0;font-size:13px;color:#6b7280;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;">Your Details</p>
            <table style="width:100%;font-size:14px;color:#111827;border-collapse:collapse;">
                <tr>
                    <td style="padding:4px 0;color:#6b7280;">Name</td>
                    <td style="padding:4px 0;text-align:right;font-weight:600;">{{ $user->name }}</td>
                </tr>
                <tr>
                    <td style="padding:4px 0;color:#6b7280;">Email</td>
                    <td style="padding:4px 0;text-align:right;font-weight:600;">{{ $user->email }}</td>
                </tr>
                @if($user->phone)
                <tr>
                    <td style="padding:4px 0;color:#6b7280;">Phone</td>
                    <td style="padding:4px 0;text-align:right;font-weight:600;">{{ $user->phone }}</td>
                </tr>
                @endif
                <tr>
                    <td style="padding:4px 0;color:#6b7280;">Role</td>
                    <td style="padding:4px 0;text-align:right;font-weight:600;">{{ ucfirst($user->role) }}</td>
                </tr>
                <tr>
                    <td style="padding:4px 0;color:#6b7280;">Joining Date</td>
                    <td style="padding:4px 0;text-align:right;font-weight:600;color:#ff2ba6;">{{ $user->joined_at ? $user->joined_at->format('d M Y') : '-' }}</td>
                </tr>
            </table>
        </div>

        @if($plainPassword)
        <div style="margin:18px 0;padding:16px;background:#fdf2f8;border:1px solid #fbcfe8;border-radius:10px;">
            <p style="margin:0 0 8px 0;font-size:13px;color:#9d174d;font-weight:600;">🔑 Login Credentials</p>
            <p style="margin:4px 0;font-size:14px;color:#374151;">Email: <strong>{{ $user->email }}</strong></p>
            <p style="margin:4px 0;font-size:14px;color:#374151;">Password: <strong>{{ $plainPassword }}</strong></p>
            <p style="margin:8px 0 0 0;font-size:13px;color:#6b7280;">You can change your password after your first login.</p>
        </div>
        @endif

        <p style="font-size:14px;line-height:1.65;color:#374151;">We look forward to working with you. If you have any questions, please contact your administrator.</p>

        <div style="margin:26px 0;height:1px;background:#e5e7eb;"></div>

        <p style="margin-top:18px;font-size:13px;color:#6b7280;">Regards,</p>
        <p style="margin:4px 0 0 0;font-size:14px;font-weight:600;color:#111827;">Support Team<br>{{ config('app.name') }}<br>{{ config('support.email') }}</p>
    </td>
</tr>

@include('emails._footer')
