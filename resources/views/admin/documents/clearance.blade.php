<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $appName ?? 'Pediatric Clinic' }} — Medical Clearance #{{ $record->id }}</title>
    <style>
        @page { margin: 48px 48px; }
        body { font-family: DejaVu Sans, -apple-system, Segoe UI, Arial, sans-serif; color: #0f172a; }
        .letterhead { display:flex; align-items:center; justify-content:space-between; border-bottom: 4px solid #6366f1; padding-bottom: 14px; }
        .brand { display:flex; align-items:center; gap:12px; }
        .brand-title { font-size: 26px; font-weight: 800; color: #6366f1; }
        .brand-sub { font-size: 12px; color:#475569; }
        .clinic-meta { text-align:right; font-size: 11px; color:#334155; }
        .watermark { position: fixed; top: 38%; right: 10%; font-size: 68px; color: rgba(99,102,241,0.08); transform: rotate(18deg); font-weight: 800; letter-spacing: 2px; }
        .title { margin: 18px 0 6px; font-weight: 700; font-size: 18px; letter-spacing: .5px; color:#0f172a; }
        .meta-row { display:flex; gap:16px; font-size: 12px; margin-top: 8px; }
        .meta-row > div { flex:1; }
        .card { margin-top: 14px; border: 1px solid #cbd5e1; border-radius: 10px; padding: 16px; background: #ffffff; }
        .card h4 { margin: 0 0 8px; font-size: 13px; color:#6366f1; text-transform: uppercase; letter-spacing: .5px; }
        .muted { color:#64748b; }
        .grid-two { display:grid; grid-template-columns: 1fr 1fr; gap: 12px; }
        .signature-block { margin-top: 24px; display:flex; justify-content:flex-end; }
        .sig-line { width: 240px; border-top: 1px solid #94a3b8; text-align:center; padding-top: 6px; font-size: 12px; color:#334155; }
        .verify { margin-top: 10px; font-size: 11px; color:#475569; }
        .footer { position: fixed; bottom: 24px; left: 48px; right: 48px; font-size: 11px; color: #64748b; }
        .wave { height: 4px; background: linear-gradient(90deg, #6366f1, #0ea5e9, #6b7280); border-radius: 2px; margin-top: 8px; }
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

    <div class="title">Medical Clearance</div>

    <div class="meta-row">
        <div>Patient: <strong>{{ optional($record->appointment->user)->name ?? '—' }}</strong></div>
        <div>Issued: <strong>{{ now()->format('Y-m-d') }}</strong></div>
        <div>Document ID: <strong>{{ strtoupper(substr(md5(($record->id ?? 0).'|'.(optional($record->conducted_at)->format('Ymd') ?? 'N/A')), 0, 10)) }}</strong></div>
    </div>

    <div class="card">
        <h4>Fitness Declaration</h4>
        <p style="margin:0 0 10px; line-height:1.65;">
            After clinical evaluation, <strong>{{ optional($record->appointment->user)->name ?? '—' }}</strong> is deemed
            <strong>medically fit</strong> for participation in school activities, sports, travel,
            or employment as of <strong>{{ now()->format('Y-m-d') }}</strong>.
        </p>
        @if($record->examination)
            <p style="margin:0 0 8px;">Summary of Examination: {{ $record->examination }}</p>
        @endif
        @if($record->notes)
            <p style="margin:0 0 8px;">Additional Notes: {{ $record->notes }}</p>
        @endif
        <div class="grid-two">
            <div>
                <h4>Restrictions</h4>
                <div class="muted">None unless otherwise specified by supervising physician.</div>
            </div>
            <div>
                <h4>Validity</h4>
                <div class="muted">Valid for 6 months from issue date, unless health status changes.</div>
            </div>
        </div>
        <div class="verify">For verification, present this document with valid ID. Code: <strong>{{ strtoupper(substr(md5(($record->id ?? 0).'|'.(optional($record->conducted_at)->format('Ymd') ?? 'N/A')), 0, 10)) }}</strong></div>
    </div>

    <div class="signature-block">
        <div class="sig-line">Physician Signature — {{ auth()->user()->name ?? '—' }}</div>
    </div>

    <div class="footer">
        <div>{{ $appName ?? 'Pediatric Clinic' }} • Call/WhatsApp: 000-000-0000 • www.example.com</div>
        <div>Near City Center, Address Line, State</div>
        <div class="wave"></div>
    </div>
</body>
</html>