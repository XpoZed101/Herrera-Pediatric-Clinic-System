<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medical History</title>
    <style>
        @page { margin: 24px; }
        body { font-family: DejaVu Sans, Arial, Helvetica, sans-serif; color: #111827; }
        /* Modern header with brand strip */
        .brand { display:flex; align-items:center; justify-content:space-between; padding:10px 12px; border:1px solid #E5E7EB; border-radius:10px; background:#FFFFFF; }
        .brand-left { display:flex; align-items:center; gap:10px; }
        .logo { width:28px; height:28px; border-radius:6px; background: linear-gradient(135deg,#06b6d4,#3b82f6); }
        .title { font-size:20px; font-weight:700; margin:0; }
        .muted { color:#6B7280; font-size:12px; }
        .strip { height:6px; width:100%; background: linear-gradient(90deg,#06b6d4,#3b82f6,#a855f7,#f43f5e); border-radius:9999px; margin:8px 0 12px; }

        /* Card sections */
        .section { margin-top: 16px; }
        .section h3 { font-size: 14px; margin: 0 0 6px 0; }
        .card { border:1px solid #E5E7EB; border-radius:10px; padding:10px; background:#FFFFFF; }
        .grid { display:grid; grid-template-columns: 1fr 1fr; gap:8px; }

        /* Table styling */
        table { width:100%; border-collapse:collapse; }
        th, td { border:1px solid #E5E7EB; padding:6px; font-size:12px; vertical-align:top; }
        th { background:#F9FAFB; text-align:left; }

        /* Chips */
        .chip { display:inline-block; padding:2px 6px; font-size:11px; border-radius:9999px; background:#F3F4F6; color:#374151; margin:2px; }
        .chip.red { background:#FEE2E2; color:#B91C1C; }
        .chip.purple { background:#EDE9FE; color:#6D28D9; }
        .badge { display:inline-block; padding:2px 6px; font-size:11px; border-radius:9999px; background:#EEF2FF; color:#3730A3; }

        .footer { margin-top:16px; font-size:11px; color:#6B7280; text-align:center; }
    </style>
    @php($status = optional($patient?->immunization)->status)
    @php($labels = ['yes' => 'Up to date', 'no' => 'Not up to date', 'not_sure' => 'Not sure'])
</head>
<body>
    <div class="brand">
        <div class="brand-left">
            <div class="logo"></div>
            <div>
                <div class="title">Child Medical History</div>
                <div class="muted">Generated: {{ $generatedAt->format('Y-m-d H:i') }}</div>
            </div>
        </div>
        <div class="muted">Pediatric Clinic</div>
    </div>
    <div class="strip"></div>

    <div class="section">
        <h3>Child</h3>
        <div class="card grid">
            <div><strong>Name:</strong> {{ $patient->child_name ?? '—' }}</div>
            <div><strong>DOB:</strong> {{ $patient->date_of_birth ?? '—' }}</div>
            <div><strong>Sex:</strong> {{ ucfirst($patient->sex ?? '—') }}</div>
        </div>
        <div style="margin-top:6px;"><strong>Immunization:</strong> <span class="badge">{{ $status ? ($labels[$status] ?? ucfirst($status)) : '—' }}</span></div>
    </div>

    <div class="section">
        <h3>Medications</h3>
        @php($meds = $patient?->medications ?? collect())
        @if($meds->isNotEmpty())
            <div class="card">
                @foreach($meds as $m)
                    <span class="chip">{{ $m->name }}</span>
                @endforeach
            </div>
        @else
            <div class="muted">No medications recorded.</div>
        @endif
    </div>

    <div class="section">
        <h3>Allergies</h3>
        @php($allergies = $patient?->allergies ?? collect())
        @if($allergies->isNotEmpty())
            <div class="card">
                @foreach($allergies as $a)
                    <span class="chip red">{{ $a->name }}</span>
                @endforeach
            </div>
        @else
            <div class="muted">No allergies recorded.</div>
        @endif
    </div>

    

    <div class="section">
        <h3>Development Concerns</h3>
        @php($dc = $patient?->developmentConcerns?->pluck('area')->filter()->values() ?? collect())
        <div>{{ $dc->isNotEmpty() ? $dc->map(fn($t) => ucfirst($t))->join(', ') : 'No development concerns recorded.' }}</div>
    </div>

    <div class="section">
        <h3>Current Symptoms</h3>
        @php($sym = $patient?->currentSymptoms?->map(function($s){ return ucfirst($s->symptom_type).($s->details ? ' — '.$s->details : ''); }) ?? collect())
        <div>{{ $sym->isNotEmpty() ? $sym->join(', ') : 'No current symptoms recorded.' }}</div>
    </div>

    <div class="section">
        <h3>Additional Notes</h3>
        <div>{{ optional($patient?->additionalNote)->notes ?? '—' }}</div>
    </div>

    <div class="section">
        <h3>Medical Records</h3>
        @if(($medicalRecords ?? collect())->isNotEmpty())
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Chief Complaint</th>
                        <th>Examination</th>
                        <th>Plan</th>
                        <th>Diagnosis</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($medicalRecords as $record)
                    @php($dx = $record->diagnoses->pluck('title')->filter())
                    <tr>
                        <td>{{ optional($record->conducted_at)->format('Y-m-d H:i') ?? '—' }}</td>
                        <td>{{ $record->chief_complaint ?? '—' }}</td>
                        <td>{{ $record->examination ?? '—' }}</td>
                        <td>{{ $record->plan ?? '—' }}</td>
                        <td>{{ $dx->isNotEmpty() ? $dx->join(', ') : '—' }}</td>
                        <td>{{ $record->notes ?? '—' }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
            <div class="muted">No medical records found.</div>
        @endif
    </div>

    <div class="footer">This document was generated by Pediatric Clinic.</div>
</body>
</html>