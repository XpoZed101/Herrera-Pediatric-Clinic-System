<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Waitlist Invitation</title>
    <style>
        body { font-family: -apple-system, Segoe UI, Roboto, Helvetica, Arial, sans-serif; color:#111827; }
        .container { max-width: 640px; margin: 0 auto; padding: 24px; }
        .card { border: 1px solid #e5e7eb; border-radius: 12px; padding: 24px; }
        .h1 { font-size: 18px; font-weight: 600; margin: 0 0 12px; }
        .p  { font-size: 14px; line-height: 1.6; margin: 6px 0; }
        .muted { color:#6b7280; }
        .btn { display:inline-block; background:#2563eb; color:#fff; text-decoration:none; padding:10px 16px; border-radius:8px; margin-top:12px; }
        .foot { font-size: 12px; color:#6b7280; margin-top: 16px; }
    </style>
</head>
<body>
<div class="container">
    <div class="card">
        <div class="h1">Invitation to Schedule an Appointment</div>
        <p class="p">Dear Parent/Guardian,</p>
        <p class="p">We are pleased to inform you that <strong>{{ $patientName ?? 'your child' }}</strong> has reached the top of our waitlist. You are invited to schedule an appointment at Pediatric Clinic.</p>
        @php
            $start = optional($entry->desired_date_start)->format('M d, Y');
            $end   = optional($entry->desired_date_end)->format('M d, Y');
        @endphp
        @if($start || $end)
            <p class="p">Preferred date range: <strong>{{ $start }}{{ $start && $end ? ' — ' : '' }}{{ $end }}</strong>.</p>
        @endif
        <p class="p">To proceed, please reply to this email or call our front desk so we can finalize a suitable time and visit type.</p>
        <p class="p muted">If you’ve already scheduled, please disregard this message.</p>
        <p class="p">Warm regards,<br/>Pediatric Clinic Team</p>
        <div class="foot">This message was sent automatically in response to your waitlist status change to "invited".</div>
    </div>
</div>
</body>
</html>