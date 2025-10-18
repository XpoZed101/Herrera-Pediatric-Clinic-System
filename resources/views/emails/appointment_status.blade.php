<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Update</title>
</head>
<body style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, 'Noto Sans', sans-serif; color:#111827;">
    <div style="max-width:600px;margin:0 auto;padding:16px;">
        <h2 style="margin:0 0 12px 0;">Appointment Update</h2>

        @php($scheduled = optional($appointment->scheduled_at)->format('Y-m-d H:i'))

        @switch($status)
            @case('requested')
                <p>We’ve received your appointment request and are reviewing it. We’ll follow up shortly with confirmation and available times.</p>
                @break
            @case('scheduled')
                <p>Your appointment has been scheduled.</p>
                @if($scheduled)
                    <p><strong>Scheduled:</strong> {{ $scheduled }}</p>
                @endif
                <p><strong>Visit type:</strong> {{ $appointment->visit_type ?? '—' }}</p>
                @break
            @case('completed')
                <p>Thank you for visiting. Your appointment is marked as completed.</p>
                <p>If you have follow-up questions, just reply to this email.</p>
                @break
            @case('cancelled')
                <p>Your appointment was cancelled. If you’d like to reschedule, please book a new time.</p>
                @break
            @default
                <p>There is an update to your appointment.</p>
        @endswitch

        @if($appointment->reason)
            <p><strong>Reason:</strong> {{ $appointment->reason }}</p>
        @endif
        @if($appointment->notes)
            <p><strong>Notes:</strong> {{ $appointment->notes }}</p>
        @endif

        <p style="margin-top:16px;">
            <a href="{{ url('/client/appointments/create') }}" style="display:inline-block;background:#0ea5e9;color:#fff;padding:10px 14px;border-radius:8px;text-decoration:none;">View Appointment Status</a>
        </p>

        <p style="margin-top:24px;color:#6b7280;">This message was sent by the pediatric clinic system.</p>
    </div>
</body>
</html>