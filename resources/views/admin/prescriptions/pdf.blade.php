<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Pediatric Clinic — Prescription #{{ $prescription->id }}</title>
    <style>
        @page { margin: 40px 40px; }
        body { font-family: DejaVu Sans, Arial, sans-serif; color: #1f2937; }
        .header { display: flex; align-items: center; justify-content: space-between; padding-bottom: 12px; border-bottom: 3px solid #0ea5e9; }
        .brand { font-size: 24px; font-weight: 700; color: #0ea5e9; }
        .subbrand { font-size: 12px; color: #334155; }
        .prescribers { text-align: right; font-size: 12px; color: #0ea5e9; }
        .section-title { font-weight: 600; font-size: 12px; margin: 12px 0 6px; color: #334155; }
        .row { display: flex; gap: 16px; font-size: 12px; }
        .row > div { flex: 1; }
        .rx { margin-top: 16px; border: 1px solid #cbd5e1; border-radius: 6px; padding: 12px; }
        .rx h3 { margin: 0 0 8px; font-size: 14px; }
        .footer { position: fixed; bottom: 24px; left: 40px; right: 40px; font-size: 11px; color: #64748b; }
        .wave { height: 4px; background: linear-gradient(90deg, #0ea5e9, #6366f1, #6b7280); border-radius: 2px; margin-top: 8px; }
        .muted { color: #64748b; }
        .badge { display:inline-block; font-size: 10px; background:#dbeafe; color:#1e40af; padding:2px 6px; border-radius:4px; }
    </style>
    </head>
<body>
    <div class="header">
        <div>
            <div class="brand">Pediatric Clinic</div>
            <div class="subbrand">Creating care for a lifetime</div>
        </div>
        <div class="prescribers">
            <div><strong>{{ optional($prescription->prescriber)->name }}</strong></div>
            <div class="muted">Reg. No — {{ optional($prescription->prescriber)->id }}</div>
        </div>
    </div>

    <div class="row" style="margin-top: 12px;">
        <div>Patient Name: <strong>{{ optional(optional($prescription->medicalRecord->appointment)->user)->name ?? '—' }}</strong></div>
        <div>Date: <strong>{{ now()->format('Y-m-d') }}</strong></div>
    </div>

    <div class="rx">
        <h3>Prescription <span class="badge">{{ ucfirst($prescription->type) }}</span></h3>
        <div><strong>Name:</strong> {{ $prescription->name }}</div>
        @if($prescription->type === 'medication')
            <div><strong>Dosage:</strong> {{ $prescription->dosage }}</div>
            <div><strong>Frequency:</strong> {{ $prescription->frequency }}</div>
            <div><strong>Route:</strong> {{ $prescription->route }}</div>
        @else
            <div><strong>Treatment Schedule:</strong> {{ $prescription->treatment_schedule }}</div>
        @endif
        @if(!empty($prescription->instructions))
            <div><strong>Instructions:</strong> {{ $prescription->instructions }}</div>
        @endif
        <div class="row" style="margin-top:8px;">
            <div>Start: {{ optional($prescription->start_date)->format('Y-m-d') ?? '—' }}</div>
            <div>End: {{ optional($prescription->end_date)->format('Y-m-d') ?? '—' }}</div>
            <div>Status: {{ ucfirst($prescription->status ?? 'active') }}</div>
        </div>
        @if($prescription->erx_enabled)
            <div class="row" style="margin-top:8px;">
                <div>eRx Status: {{ ucfirst($prescription->erx_status ?? 'submitted') }}</div>
                <div>eRx ID: {{ $prescription->erx_external_id ?? '—' }}</div>
                <div>Pharmacy: {{ $prescription->erx_pharmacy ?? '—' }}</div>
            </div>
        @endif
    </div>

    <div class="footer">
        <div>Pediatric Clinic • Call/WhatsApp: 000-000-0000 • www.example.com</div>
        <div>Near City Center, Address Line, State</div>
        <div class="wave"></div>
    </div>
</body>
</html>