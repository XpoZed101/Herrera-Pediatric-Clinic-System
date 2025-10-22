<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Prescriptions</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: DejaVu Sans, Arial, Helvetica, sans-serif; color: #111827; margin: 24px; }
        h1 { font-size: 18px; margin: 0 0 6px; }
        h2 { font-size: 14px; margin: 18px 0 8px; }
        .muted { color: #6b7280; }
        .header { display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 10px; }
        .card { border: 1px solid #e5e7eb; border-radius: 8px; padding: 12px; margin-bottom: 16px; }
        .table { width: 100%; border-collapse: collapse; font-size: 12px; }
        .table th { text-align: left; padding: 6px 8px; background: #f3f4f6; border-bottom: 1px solid #e5e7eb; font-weight: 600; }
        .table td { padding: 6px 8px; border-bottom: 1px solid #e5e7eb; vertical-align: top; }
        .badge { display: inline-block; border-radius: 9999px; padding: 2px 6px; font-size: 11px; }
        .badge-active { background: #dcfce7; color: #166534; }
        .badge-completed { background: #dbeafe; color: #1e40af; }
        .badge-cancelled { background: #fee2e2; color: #991b1b; }
        .badge-default { background: #f3f4f6; color: #374151; }
        .small { font-size: 11px; }
        .nowrap { white-space: nowrap; }
    </style>
</head>
<body>
    <div class="header">
        <div>
            <h1>Prescriptions</h1>
            <div class="small muted">Medications and treatment plans prescribed.</div>
        </div>
        <div class="small muted">Generated: {{ \Carbon\Carbon::now()->format('M d, Y h:i A') }}</div>
    </div>

    @if($patient)
    <div class="card">
        <div style="display:flex;justify-content:space-between;align-items:center;gap:8px;">
            <div>
                <div style="font-weight:600;">{{ $patient->child_name }}</div>
                <div class="small muted">DOB {{ $patient->date_of_birth }} • Age {{ $patient->age }} • Sex {{ ucfirst($patient->sex) }}</div>
            </div>
            <div class="small muted">Immunization: {{ optional($patient->immunization)->status ?? 'Unknown' }}</div>
        </div>
    </div>

    @if(isset($prescriptions) && $prescriptions->count())
    <table class="table">
        <thead>
            <tr>
                <th class="nowrap">Start</th>
                <th class="nowrap">End</th>
                <th>Name</th>
                <th>Type</th>
                <th class="nowrap">Dosage</th>
                <th>Frequency</th>
                <th class="nowrap">Route</th>
                <th>Instructions</th>
                <th class="nowrap">Status</th>
                <th>Prescriber</th>
            </tr>
        </thead>
        <tbody>
            @foreach($prescriptions as $rx)
            <tr>
                <td class="small nowrap">{{ $rx->start_date ? \Carbon\Carbon::parse($rx->start_date)->format('M d, Y') : '—' }}</td>
                <td class="small nowrap">{{ $rx->end_date ? \Carbon\Carbon::parse($rx->end_date)->format('M d, Y') : '—' }}</td>
                <td class="small">{{ $rx->name ?? '—' }}</td>
                <td class="small">{{ $rx->type ? ucfirst($rx->type) : '—' }}</td>
                <td class="small">{{ $rx->dosage ?? '—' }}</td>
                <td class="small">{{ $rx->frequency ?? '—' }}</td>
                <td class="small">{{ $rx->route ?? '—' }}</td>
                <td class="small">{{ $rx->instructions ?? '—' }}</td>
                @php($s = $rx->status)
                @php($cls = $s === 'active' ? 'badge-active' : ($s === 'completed' ? 'badge-completed' : ($s === 'cancelled' ? 'badge-cancelled' : 'badge-default')))
                <td><span class="badge {{ $cls }}">{{ $s ? ucfirst($s) : '—' }}</span></td>
                <td class="small">{{ optional($rx->prescriber)->name ?? '—' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="card small muted">No prescriptions found for this account.</div>
    @endif

    @else
    <div class="card small muted">No child record linked to this account yet.</div>
    @endif
</body>
</html>