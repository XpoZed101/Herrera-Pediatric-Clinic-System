<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $appName ?? 'Pediatric Clinic' }} — Medical Clearance #{{ $record->id }}</title>
    <style>
        @page { margin: 48px 48px; }
        :root {
            --primary: #6366f1; /* indigo-500 */
            --accent: #0ea5e9; /* sky-500 */
            --text: #0f172a; /* slate-900 */
            --muted: #64748b; /* slate-500 */
            --border: #cbd5e1; /* slate-300 */
        }
        body { font-family: DejaVu Sans, -apple-system, Segoe UI, Arial, sans-serif; color: var(--text); }
        .header { display:flex; align-items:center; justify-content:space-between; background: linear-gradient(90deg, var(--primary), var(--accent)); color:#fff; padding: 16px 18px; border-radius: 12px; }
        .brand { display:flex; align-items:center; gap:12px; }
        .brand-title { font-size: 26px; font-weight: 800; letter-spacing: .4px; }
        .brand-sub { font-size: 12px; opacity: .9; }
        .clinic-meta { text-align:right; font-size: 11px; opacity: .9; }
        .watermark { position: fixed; top: 40%; right: 12%; font-size: 68px; color: rgba(99,102,241,0.07); transform: rotate(16deg); font-weight: 800; letter-spacing: 2px; }
        .title { margin: 18px 0 6px; font-weight: 800; font-size: 22px; letter-spacing: .5px; color:#0f172a; }
        .chips { display:flex; gap:8px; flex-wrap:wrap; margin-top: 8px; }
        .chip { display:inline-flex; align-items:center; gap:6px; font-size: 11px; color:#1e3a8a; background:#eef2ff; border:1px solid #c7d2fe; border-radius: 999px; padding: 6px 10px; }
        .chip .dot { width:8px; height:8px; border-radius:999px; background:#c7d2fe; }
        .section { margin-top: 14px; border: 1px solid var(--border); border-radius: 12px; padding: 16px; background: #ffffff; }
        .section h4 { margin: 0 0 8px; font-size: 13px; color: var(--primary); text-transform: uppercase; letter-spacing: .5px; }
        .muted { color: var(--muted); }
        .grid-two { display:grid; grid-template-columns: 1fr 1fr; gap: 12px; }
        .signature-block { margin-top: 24px; display:flex; justify-content:flex-end; }
        .sig-line { width: 240px; border-top: 1px solid #94a3b8; text-align:center; padding-top: 6px; font-size: 12px; color:#334155; }
        .verify { margin-top: 10px; font-size: 11px; color:#475569; }
        .footer { position: fixed; bottom: 24px; left: 48px; right: 48px; font-size: 11px; color: #64748b; }
        .wave { height: 4px; background: linear-gradient(90deg, var(--primary), var(--accent), #6b7280); border-radius: 2px; margin-top: 8px; }
    </style>
</head>
<body>
    <div class="watermark">{{ $appName ?? 'Pediatric Clinic' }}</div>
    <div class="header">
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
    @php
        $issuedDateCode = optional($record->conducted_at)->format('Ymd') ?? 'N/A';
        $docId = strtoupper(substr(md5((($record->id ?? 0) . '|' . $issuedDateCode)), 0, 10));
        $patientName = optional($record->appointment->patient)->child_name ?? optional($record->appointment->user)->name ?? '—';
    @endphp
    <div class="chips">
        <div class="chip"><span class="dot"></span> Issued: <strong>{{ now()->format('Y-m-d') }}</strong></div>
        <div class="chip"><span class="dot"></span> Document ID: <strong>{{ $docId }}</strong></div>
        <div class="chip"><span class="dot" style="background:#34d399"></span> Patient: <strong>{{ $patientName }}</strong></div>
    </div>

    <div class="section">
        <h4>Fitness Declaration</h4>
        <p style="margin:0 0 10px; line-height:1.65;">
            After clinical evaluation, <strong>{{ $patientName }}</strong> is deemed
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
        <div class="verify">For verification, present this document with valid ID. Code: <strong>{{ $docId }}</strong></div>
    </div>

    <div class="signature-block">
        <div class="sig-line">Physician Signature — {{ optional($issuer)->name ?? '—' }}</div>
    </div>

    <div class="footer">
        <div>{{ $appName ?? 'Pediatric Clinic' }} • Call/WhatsApp: 000-000-0000 • www.example.com</div>
        <div>Near City Center, Address Line, State</div>
        <div class="wave"></div>
    </div>
</body>
</html>