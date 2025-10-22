@props(['appointment'])

@php
    $vParts = [];
    $vAlerts = [];
    if ($appointment) {
        $temp = $appointment->temperature;
        $bp = trim((string)($appointment->blood_pressure ?? ''));
        $hr = $appointment->heart_rate;
        $rr = $appointment->respiratory_rate;
        $spo2 = $appointment->oxygen_saturation;

        if ($temp !== null) { $vParts[] = 'Temp ' . number_format((float)$temp, 1) . '°C'; if ((float)$temp >= 38.0) $vAlerts[] = 'Fever'; }
        if ($bp !== '') {
            $vParts[] = 'BP ' . $bp;
            $sys = null; $dia = null;
            if (preg_match('/(\d{2,3})\D+(\d{2,3})/', $bp, $m)) { $sys = (int)$m[1]; $dia = (int)$m[2]; }
            if (($sys !== null && $sys >= 130) || ($dia !== null && $dia >= 80)) { $vAlerts[] = 'High BP'; }
        }
        if ($hr !== null) { $vParts[] = 'HR ' . (int)$hr . ' bpm'; if ((int)$hr >= 100) $vAlerts[] = 'High HR'; }
        if ($rr !== null) { $vParts[] = 'RR ' . (int)$rr . ' /min'; if ((int)$rr >= 22) $vAlerts[] = 'High RR'; }
        if ($spo2 !== null) { $vParts[] = 'SpO₂ ' . (int)$spo2 . '%'; if ((int)$spo2 < 95) $vAlerts[] = 'Low SpO₂'; }
    }
    $statusColor = empty($vAlerts) ? 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200' : 'bg-red-50 text-red-700 ring-1 ring-red-200';
    $statusLabel = empty($vAlerts) ? __('Normal') : __('Alert');
@endphp

@if(!empty($vParts))
    <div class="flex items-center justify-between rounded-lg border border-neutral-200 dark:border-neutral-700 p-2 text-xs">
        <div class="text-neutral-700 dark:text-neutral-300 flex flex-wrap gap-2">
            @foreach($vParts as $p)
                <span class="inline-flex items-center gap-1 rounded-full bg-neutral-100 dark:bg-neutral-800 px-2 py-1">{{ $p }}</span>
            @endforeach
        </div>
        <span class="inline-flex items-center gap-1 rounded-full px-2 py-1 {{ $statusColor }}">
            <flux:icon.shield-check variant="mini" /> {{ $statusLabel }}
        </span>
    </div>
@endif
