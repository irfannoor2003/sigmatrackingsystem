@include('emails._header', ['title' => 'Visit Reminder'])

<tr>
    <td style="padding:22px 18px;">
        <h2 style="margin:0 0 12px 0;color:#111827;font-size:18px;">⏰ Visit Still In Progress</h2>

        <p style="font-size:14px;line-height:1.65;color:#374151;">Dear {{ $salesmanName }},</p>

        <p style="font-size:14px;line-height:1.65;color:#374151;">This is a reminder that you have a visit that is still in progress. Please complete it as soon as possible.</p>

        <div style="margin:26px 0;height:1px;background:#e5e7eb;"></div>

        <p style="font-size:14px;line-height:1.65;color:#374151;"><strong>Visit Details:</strong></p>
        <ul style="padding-left:20px;margin:8px 0;font-size:14px;color:#374151;">
            <li>Customer: {{ $visit->customer->name }}</li>
            <li>Started: {{ $visit->started_at->format('M d, Y h:i A') }}</li>
            <li>Purpose: {{ $visit->purpose }}</li>
        </ul>

        <div style="margin:26px 0;height:1px;background:#e5e7eb;"></div>

        <p style="margin-top:18px;font-size:13px;color:#6b7280;">Regards,</p>
        <p style="margin:4px 0 0 0;font-size:14px;font-weight:600;color:#111827;">Support Team<br>{{ config('app.name') }}</p>
    </td>
</tr>

@include('emails._footer')