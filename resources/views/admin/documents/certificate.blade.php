<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $appName ?? 'Pediatric Clinic' }} — Medical Certificate #{{ $record->id }}</title>
    <style>
        @page { margin: 48px 48px; }
        :root {
            --primary: #0ea5e9; /* sky-500 */
            --secondary: #6366f1; /* indigo-500 */
            --text: #0f172a; /* slate-900 */
            --muted: #64748b; /* slate-500 */
            --border: #cbd5e1; /* slate-300 */
        }
        body { font-family: DejaVu Sans, -apple-system, Segoe UI, Arial, sans-serif; color: var(--text); }
        .header { display:flex; align-items:center; justify-content:space-between; background: linear-gradient(90deg, var(--primary), var(--secondary)); color:#fff; padding: 16px 18px; border-radius: 12px; }
        .brand { display:flex; align-items:center; gap:12px; }
        .brand-title { font-size: 26px; font-weight: 800; letter-spacing: .4px; }
        .brand-sub { font-size: 12px; opacity: .9; }
        .clinic-meta { text-align:right; font-size: 11px; opacity: .9; }
        .watermark { position: fixed; top: 38%; left: 14%; font-size: 72px; color: rgba(14,165,233,0.07); transform: rotate(-16deg); font-weight: 800; letter-spacing: 2px; }
        .title { margin: 18px 0 6px; font-weight: 800; font-size: 22px; letter-spacing: .5px; color:#0f172a; }
        .chips { display:flex; gap:8px; flex-wrap:wrap; margin-top: 8px; }
        .chip { display:inline-flex; align-items:center; gap:6px; font-size: 11px; color:#1e3a8a; background:#eff6ff; border:1px solid #93c5fd; border-radius: 999px; padding: 6px 10px; }
        .chip .dot { width:8px; height:8px; border-radius:999px; background:#93c5fd; }
        .meta-row { display:flex; gap:16px; font-size: 12px; margin-top: 8px; }
        .meta-row > div { flex:1; }
        .section { margin-top: 14px; border: 1px solid var(--border); border-radius: 12px; padding: 16px; background: #ffffff; }
        .section h4 { margin: 0 0 8px; font-size: 13px; color: var(--primary); text-transform: uppercase; letter-spacing: .5px; }
        .section .content { line-height:1.65; }
        .muted { color: var(--muted); }
        .grid-two { display:grid; grid-template-columns: 1fr 1fr; gap: 12px; }
        .signature { margin-top: 18px; display:flex; justify-content:space-between; align-items:center; }
        .sig-line { width: 240px; border-top: 1px solid #94a3b8; text-align:center; padding-top: 6px; font-size: 12px; color:#334155; }
        .verify { margin-top: 10px; font-size: 11px; color:#475569; }
        .footer { position: fixed; bottom: 24px; left: 48px; right: 48px; font-size: 11px; color: #64748b; }
        .wave { height: 4px; background: linear-gradient(90deg, var(--primary), var(--secondary), #6b7280); border-radius: 2px; margin-top: 8px; }
    </style>
</head>
<body>
    <div class="watermark">{{ $appName ?? 'Pediatric Clinic' }}</div>

    <div class="header">
        <div class="brand">
            <div class="brand-title">{{ $appName ?? 'Pediatric Clinic' }}</div>
            <div class="brand-sub">Child‑centered care, modern and compassionate</div>
        </div>
        <div class="clinic-meta">
            <div>Near City Center, Address Line, State</div>
            <div>Tel: 000-000-0000 • www.example.com</div>
        </div>
    </div>

    <div class="title">Medical Certificate</div>
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
        <h4>Summary</h4>
        <p class="muted" style="margin:0 0 10px;">This certifies that <strong>{{ $patientName }}</strong> was examined at our clinic on <strong>{{ optional($record->conducted_at)->format('Y-m-d') ?? optional($record->appointment->scheduled_at)->format('Y-m-d') ?? now()->format('Y-m-d') }}</strong>.</p>
        @if($record->chief_complaint)
            <p style="margin:0 0 8px;">Chief Complaint: {{ $record->chief_complaint }}</p>
        @endif
        @if(!empty($record->diagnoses) && $record->diagnoses->first())
            <p style="margin:0 0 8px;">Clinical Impression: <strong>{{ $record->diagnoses->first()->title }}</strong>@if($record->diagnoses->first()->severity) ({{ $record->diagnoses->first()->severity }}) @endif</p>
        @endif
        <div class="grid-two">
            <div>
                <h4>Examination</h4>
                <div class="content muted">{{ $record->examination ?? '—' }}</div>
            </div>
            <div>
                <h4>Plan & Advice</h4>
                <div class="content">{{ $record->plan ?? '—' }}</div>
            </div>
        </div>
        <div style="margin-top:12px;" class="muted">This certificate is issued upon request for official purposes and reflects the patient’s condition on the stated date.</div>
    </div>

    <div class="section">
        <h4>Physician</h4>
        <div class="grid-two">
            <div>
                <div><strong>{{ optional($issuer)->name ?? 'Attending Physician' }}</strong></div>
                <div class="muted">License No: {{ optional($issuer)->license_no ?? optional($issuer)->id ?? '—' }}</div>
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
