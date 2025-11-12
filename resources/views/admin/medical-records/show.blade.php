<x-layouts.app :title="__('Medical Record Overview')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl">
        <!-- Header -->
        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900 shadow-sm">
            <div class="flex items-center justify-between px-4 py-3">
                <div>
                    <h2 class="text-xl font-semibold tracking-tight">Medical Record for Appointment #{{ $appointment->id }}</h2>
                    <div class="mt-1 text-sm text-neutral-600 dark:text-neutral-300">Patient: {{ optional($appointment->patient)->child_name ?? 'Unknown Patient' }}</div>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.appointments.show', $appointment) }}" class="inline-flex items-center gap-2 rounded-lg border border-neutral-200 dark:border-neutral-700 bg-neutral-50 dark:bg-zinc-800 text-neutral-800 dark:text-neutral-200 px-3 py-1.5 hover:bg-neutral-100 dark:hover:bg-zinc-700" wire:navigate>
                        <flux:icon.arrow-left variant="mini" /> Back
                    </a>
                    <a href="{{ route('admin.medical-records.edit', $medicalRecord) }}" class="inline-flex items-center gap-2 rounded-lg bg-accent text-white px-3 py-1.5 hover:opacity-90" wire:navigate>
                        <flux:icon.pencil-square variant="mini" /> Edit
                    </a>
                    <a href="{{ route('admin.medical-records.certificate.pdf', $medicalRecord) }}" target="_blank" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 text-white px-3 py-1.5 hover:bg-blue-700" title="Generate Medical Certificate">
                        <flux:icon.document-text variant="mini" /> Certificate PDF
                    </a>
                    <a href="{{ route('admin.medical-records.clearance.pdf', $medicalRecord) }}" target="_blank" class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 text-white px-3 py-1.5 hover:bg-indigo-700" title="Generate Medical Clearance">
                        <flux:icon.document-text variant="mini" /> Clearance PDF
                    </a>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900 p-6 shadow-sm">
            <!-- Overview grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div class="rounded-lg border border-neutral-200 dark:border-neutral-700 p-4">
                        <div class="text-xs font-medium uppercase tracking-wide text-neutral-600 dark:text-neutral-300">Conducted At</div>
                        <div class="mt-1 text-neutral-900 dark:text-neutral-100 text-sm">{{ optional($medicalRecord->conducted_at)->format('Y-m-d H:i') ?? '—' }}</div>
                    </div>
                    <div class="rounded-lg border border-neutral-200 dark:border-neutral-700 p-4">
                        <div class="text-xs font-medium uppercase tracking-wide text-neutral-600 dark:text-neutral-300">Chief Complaint</div>
                        <div class="mt-1 text-neutral-900 dark:text-neutral-100">{{ $medicalRecord->chief_complaint ?? '—' }}</div>
                    </div>
                    <div class="rounded-lg border border-neutral-200 dark:border-neutral-700 p-4">
                        <div class="text-xs font-medium uppercase tracking-wide text-neutral-600 dark:text-neutral-300">Examination</div>
                        <div class="mt-2 text-neutral-900 dark:text-neutral-100 whitespace-pre-line leading-relaxed">{{ $medicalRecord->examination ?? '—' }}</div>
                    </div>
                </div>
                <div class="space-y-4">
                    <div class="rounded-lg border border-neutral-200 dark:border-neutral-700 p-4">
                        <div class="text-xs font-medium uppercase tracking-wide text-neutral-600 dark:text-neutral-300">Plan</div>
                        <div class="mt-2 text-neutral-900 dark:text-neutral-100 whitespace-pre-line leading-relaxed">{{ $medicalRecord->plan ?? '—' }}</div>
                    </div>
                    <div class="rounded-lg border border-neutral-200 dark:border-neutral-700 p-4">
                        <div class="text-xs font-medium uppercase tracking-wide text-neutral-600 dark:text-neutral-300">Notes</div>
                        <div class="mt-2 text-neutral-900 dark:text-neutral-100 whitespace-pre-line leading-relaxed">{{ $medicalRecord->notes ?? '—' }}</div>
                    </div>
                </div>
            </div>

            <!-- Diagnoses -->
            <div class="mt-8">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-sm font-semibold tracking-tight">Diagnoses</h3>
                    <span class="inline-flex items-center rounded-md bg-neutral-100 dark:bg-neutral-800 px-2 py-0.5 text-xs text-neutral-700 dark:text-neutral-200">{{ $medicalRecord->diagnoses->count() }} total</span>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @forelse($medicalRecord->diagnoses as $dx)
                        <div class="rounded-lg border border-neutral-200 dark:border-neutral-700 p-4">
                            <div class="flex items-center gap-2">
                                <span class="font-medium">{{ $dx->title }}</span>
                                @if($dx->severity)
                                    <span class="inline-flex items-center rounded-md bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-200 px-2 py-0.5 text-xs">{{ $dx->severity }}</span>
                                @endif
                                @if($dx->icd_code)
                                    <span class="inline-flex items-center rounded-md bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-200 px-2 py-0.5 text-xs">ICD {{ $dx->icd_code }}</span>
                                @endif
                            </div>
                            <div class="mt-2 text-sm text-neutral-700 dark:text-neutral-300 whitespace-pre-line">{{ $dx->description ?? '—' }}</div>
                        </div>
                    @empty
                        <div class="text-neutral-600 dark:text-neutral-300">No diagnoses recorded.</div>
                    @endforelse
                </div>
            </div>

            <!-- Prescriptions -->
            <div class="mt-8">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-sm font-semibold tracking-tight">Prescriptions</h3>
                    <span class="inline-flex items-center rounded-md bg-neutral-100 dark:bg-neutral-800 px-2 py-0.5 text-xs text-neutral-700 dark:text-neutral-200">{{ $medicalRecord->prescriptions->count() }} total</span>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @forelse($medicalRecord->prescriptions as $p)
                        <div class="rounded-lg border border-neutral-200 dark:border-neutral-700 p-4">
                            <div class="flex items-center gap-2">
                                <span class="inline-flex items-center justify-center rounded-md px-2 py-0.5 text-xs {{ $p->type === 'medication' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-200' : 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-200' }}">{{ ucfirst($p->type) }}</span>
                                <span class="font-medium">{{ $p->name }}</span>
                            </div>
                            <div class="mt-2 text-xs text-neutral-600 dark:text-neutral-300">
                                @if($p->type === 'medication')
                                    {{ $p->dosage }} • {{ $p->frequency }} • {{ $p->route }}
                                @elseif($p->type === 'treatment')
                                    {{ $p->treatment_schedule }}
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-neutral-600 dark:text-neutral-300">No prescriptions recorded.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>