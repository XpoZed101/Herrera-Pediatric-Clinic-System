<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $appName ?? 'Pediatric Clinic' }} — Medical Certificate #{{ $record->id }}</title>
    <style>
        @page { margin: 48px 48px; }
        body { font-family: DejaVu Sans, -apple-system, Segoe UI, Arial, sans-serif; color: #0f172a; }
        .letterhead { display:flex; align-items:center; justify-content:space-between; border-bottom: 4px solid #0ea5e9; padding-bottom: 14px; }
        .brand { display:flex; align-items:center; gap:12px; }
        .brand-title { font-size: 26px; font-weight: 800; color: #0ea5e9; }
        .brand-sub { font-size: 12px; color:#475569; }
        .clinic-meta { text-align:right; font-size: 11px; color:#334155; }
        .watermark { position: fixed; top: 35%; left: 12%; font-size: 72px; color: rgba(14,165,233,0.08); transform: rotate(-20deg); font-weight: 800; letter-spacing: 2px; }
        .title { margin: 18px 0 6px; font-weight: 700; font-size: 18px; letter-spacing: .5px; color:#0f172a; }
        .meta-row { display:flex; gap:16px; font-size: 12px; margin-top: 8px; }
        .meta-row > div { flex:1; }
        .card { margin-top: 14px; border: 1px solid #cbd5e1; border-radius: 10px; padding: 16px; background: #ffffff; }
        .card h4 { margin: 0 0 8px; font-size: 13px; color:#0ea5e9; text-transform: uppercase; letter-spacing: .5px; }
        .muted { color:#64748b; }
        .grid-two { display:grid; grid-template-columns: 1fr 1fr; gap: 12px; }
        .signature { margin-top: 36px; display:flex; justify-content:space-between; align-items:center; }
        .sig-line { width: 240px; border-top: 1px solid #94a3b8; text-align:center; padding-top: 6px; font-size: 12px; color:#334155; }
        .verify { margin-top: 10px; font-size: 11px; color:#475569; }
        .footer { position: fixed; bottom: 24px; left: 48px; right: 48px; font-size: 11px; color: #64748b; }
        .wave { height: 4px; background: linear-gradient(90deg, #0ea5e9, #6366f1, #6b7280); border-radius: 2px; margin-top: 8px; }
    </style>
    </head>
<body>
    <div class="watermark">{{ $appName ?? 'Pediatric Clinic' }}</div>
    <div class="letterhead">
        <div class="brand">
            <div class="brand-title">{{ $appName ?? 'Pediatric Clinic' }}</div>
            <div class="brand-sub">Creating care for a lifetime</div>
        </div>
        <div class="clinic-meta">
            <div>Near City Center, Address Line, State</div>
            <div>Tel: 000-000-0000 • www.example.com</div>
        </div>
    </div>

    <div class="title">Medical Certificate</div>

    <div class="meta-row">
        <div>Patient: <strong>{{ optional($record->appointment->user)->name ?? '—' }}</strong></div>
        <div>Issued: <strong>{{ now()->format('Y-m-d') }}</strong></div>
        <div>Document ID: <strong>{{ strtoupper(substr(md5(($record->id ?? 0).'|'.(optional($record->conducted_at)->format('Ymd') ?? 'N/A')), 0, 10)) }}</strong></div>
    </div>

    <div class="card">
        <h4>Summary</h4>
        <p class="muted" style="margin:0 0 10px;">This certifies that <strong>{{ optional($record->appointment->user)->name ?? '—' }}</strong> was examined at our clinic on <strong>{{ optional($record->conducted_at)->format('Y-m-d') ?? optional($record->appointment->scheduled_at)->format('Y-m-d') ?? now()->format('Y-m-d') }}</strong>.</p>
        @if($record->chief_complaint)
            <p style="margin:0 0 8px;">Chief Complaint: {{ $record->chief_complaint }}</p>
        @endif
        @if(!empty($record->diagnoses) && $record->diagnoses->first())
            <p style="margin:0 0 8px;">Clinical Impression: <strong>{{ $record->diagnoses->first()->title }}</strong>@if($record->diagnoses->first()->severity) ({{ $record->diagnoses->first()->severity }}) @endif</p>
        @endif
        <div class="grid-two">
            <div>
                <h4>Examination</h4>
                <div class="muted" style="line-height:1.65;">{{ $record->examination ?? '—' }}</div>
            </div>
            <div>
                <h4>Plan & Advice</h4>
                <div style="line-height:1.65;">{{ $record->plan ?? '—' }}</div>
            </div>
        </div>
        <div style="margin-top:12px;" class="muted">This certificate is issued upon request for official purposes and reflects the patient’s condition on the stated date.</div>
    </div>

    <div class="card">
        <h4>Physician</h4>
        <div class="grid-two">
            <div>
                <div><strong>{{ auth()->user()->name ?? 'Attending Physician' }}</strong></div>
                <div class="muted">License No: {{ auth()->user()->id ?? '—' }}</div>
            </div>
            <div class="signature">
                <div class="sig-line">Signature</div>
            </div>
        </div>
        <div class="verify">Verify: Present this document with ID and contact the clinic for confirmation. Code: <strong>{{ strtoupper(substr(md5(($record->id ?? 0).'|'.(optional($record->conducted_at)->format('Ymd') ?? 'N/A')), 0, 10)) }}</strong></div>
    </div>

    <div class="footer">
        <div>{{ $appName ?? 'Pediatric Clinic' }} • Call/WhatsApp: 000-000-0000 • www.example.com</div>
        <div>Near City Center, Address Line, State</div>
        <div class="wave"></div>
    </div>
</body>
</html>