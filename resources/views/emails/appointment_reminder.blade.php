<div style="font-family: system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, sans-serif; color:#111;">
    <h2 style="margin:0 0 12px;">Appointment Reminder</h2>
    <p style="margin:0 0 12px;">This is a friendly reminder that your appointment is scheduled in approximately 24 hours.</p>
    <div style="margin:12px 0; padding:12px; border:1px solid #cfe2ff; background:#e9f2ff; border-radius:8px;">
        <p style="margin:0 0 8px;"><strong>Date & Time:</strong> {{ optional($appointment->scheduled_at)->format('M d, Y h:i A') }}</p>
        <p style="margin:0 0 8px;"><strong>Visit Type:</strong> {{ ucfirst(str_replace('_',' ', $appointment->visit_type ?? 'consultation')) }}</p>
        @if($appointment->patient)
            <p style="margin:0 0 8px;"><strong>Patient:</strong> {{ $appointment->patient->child_name ?? '—' }}</p>
        @endif
    </div>
    <p style="margin:12px 0 0;">If you need to reschedule or cancel, please contact the clinic.</p>
</div>