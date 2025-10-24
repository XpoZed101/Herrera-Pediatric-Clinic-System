<x-layouts.app>
    <div class="max-w-7xl mx-auto px-6 py-8 space-y-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-xl font-semibold tracking-tight">Patient Details</h1>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.patients.index') }}" class="inline-flex items-center justify-center rounded-lg p-2 bg-white dark:bg-zinc-900 border border-neutral-200 dark:border-neutral-700 hover:bg-neutral-100 dark:hover:bg-zinc-800 transition-colors" title="Back" wire:navigate>
                    <flux:icon.chevron-left variant="mini" />
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900 shadow-sm p-5">
                <h2 class="text-base font-medium mb-3">Medical Records</h2>
                @if(isset($medicalRecords) && $medicalRecords->count())
                    <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 overflow-hidden">
                        <table class="min-w-full text-sm leading-6">
                            <thead class="bg-neutral-50 dark:bg-zinc-800">
                                <tr>
                                    <th class="px-3 py-2 text-left">Conducted</th>
                                    <th class="px-3 py-2 text-left">Provider</th>
                                    <th class="px-3 py-2 text-left">Diagnoses</th>
                                    <th class="px-3 py-2 text-left">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                                @foreach($medicalRecords as $record)
                                    <tr class="hover:bg-neutral-50 dark:hover:bg-zinc-800/60">
                                        <td class="px-3 py-2">{{ optional($record->conducted_at)->format('Y-m-d H:i') ?? '—' }}</td>
                                        <td class="px-3 py-2">{{ optional($record->appointment?->user)->name ?? optional($record->user)->name ?? '—' }}</td>
                                        <td class="px-3 py-2">
                                            @php($dx = $record->diagnoses->pluck('title')->filter())
                                            @if($dx->isNotEmpty())
                                                <div class="flex flex-wrap gap-2">
                                                    @foreach($dx as $d)
                                                        <span class="inline-flex items-center gap-1 rounded-full bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300 px-2 py-1 text-xs" title="{{ $d }}">{{ $d }}</span>
                                                    @endforeach
                                                </div>
                                            @else
                                                —
                                            @endif
                                        </td>
                                        <td class="px-3 py-2 whitespace-nowrap">
                                            <a href="{{ route('admin.medical-records.show', $record) }}" class="inline-flex items-center gap-1 rounded-lg bg-neutral-50 dark:bg-zinc-800 ring-1 ring-neutral-200 dark:ring-neutral-700 text-neutral-800 dark:text-neutral-200 px-2.5 py-1.5 hover:bg-neutral-100 dark:hover:bg-zinc-700 transition-colors" wire:navigate>
                                                <flux:icon.clipboard-document variant="mini" /> View
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-sm text-neutral-600 dark:text-neutral-300">No medical records found for this patient.</div>
                @endif
            </div>
            <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900 shadow-sm p-5">
                <h2 class="text-base font-medium mb-3">Appointments</h2>
                @if(isset($appointments) && $appointments->count())
                    <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 overflow-hidden">
                        <table class="min-w-full text-sm leading-6">
                            <thead class="bg-neutral-50 dark:bg-zinc-800">
                                <tr>
                                    <th class="px-3 py-2 text-left">Scheduled</th>
                                    <th class="px-3 py-2 text-left">Status</th>
                                    <th class="px-3 py-2 text-left">Provider</th>
                                    <th class="px-3 py-2 text-left">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                                @foreach($appointments as $ap)
                                    <tr class="hover:bg-neutral-50 dark:hover:bg-zinc-800/60">
                                        <td class="px-3 py-2">{{ optional($ap->scheduled_at)->format('Y-m-d H:i') ?? '—' }}</td>
                                        <td class="px-3 py-2">{{ ucfirst($ap->status ?? '—') }}</td>
                                        <td class="px-3 py-2">{{ optional($ap->user)->name ?? '—' }}</td>
                                        <td class="px-3 py-2 whitespace-nowrap">
                                            <a href="{{ route('admin.appointments.show', $ap) }}" class="inline-flex items-center gap-1 rounded-lg bg-neutral-50 dark:bg-zinc-800 ring-1 ring-neutral-200 dark:ring-neutral-700 text-neutral-800 dark:text-neutral-200 px-2.5 py-1.5 hover:bg-neutral-100 dark:hover:bg-zinc-700 transition-colors" wire:navigate>
                                                <flux:icon.eye variant="mini" /> View
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-sm text-neutral-600 dark:text-neutral-300">No appointments found for this patient.</div>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900 shadow-sm p-5">
                <h2 class="text-base font-medium mb-3">Child</h2>
                <div class="text-sm">
                    <div><span class="text-neutral-500">Name:</span> {{ $patient->child_name }}</div>
                    <div><span class="text-neutral-500">DOB:</span> {{ $patient->date_of_birth }}</div>
                    <div><span class="text-neutral-500">Age:</span> {{ $patient->age }}</div>
                    <div><span class="text-neutral-500">Sex:</span> {{ ucfirst($patient->sex) }}</div>
                </div>
            </div>

            <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900 shadow-sm p-5">
                <h2 class="text-base font-medium mb-3">Guardian</h2>
                <div class="text-sm">
                    @if($patient->guardian)
                    <div><span class="text-neutral-500">Name:</span> {{ $patient->guardian->name }}</div>
                    <div><span class="text-neutral-500">Phone:</span> {{ $patient->guardian->phone }}</div>
                    <div><span class="text-neutral-500">Email:</span> {{ $patient->guardian->email }}</div>
                    @endif
                </div>
            </div>

            <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900 shadow-sm p-5">
                <h2 class="text-base font-medium mb-3">Emergency Contact</h2>
                <div class="text-sm">
                    @if($patient->emergencyContact)
                    <div><span class="text-neutral-500">Name:</span> {{ $patient->emergencyContact->name }}</div>
                    <div><span class="text-neutral-500">Phone:</span> {{ $patient->emergencyContact->phone }}</div>
                    @endif
                </div>
            </div>

            <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900 shadow-sm p-5">
                <h2 class="text-base font-medium mb-3">Medications</h2>
                <div class="text-sm space-y-1">
                    @forelse($patient->medications as $m)
                    <div>• {{ $m->name }}@if(!empty($m->details)) — {{ $m->details }}@endif</div>
                    @empty
                    @endforelse
                </div>
            </div>

            <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900 shadow-sm p-5">
                <h2 class="text-base font-medium mb-3">Allergies</h2>
                <div class="text-sm space-y-1">
                    @forelse($patient->allergies as $a)
                    <div>• {{ $a->name }}</div>
                    @empty
                    @endforelse
                </div>
            </div>

            <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900 shadow-sm p-5">
                <h2 class="text-base font-medium mb-3">Past Medical Conditions</h2>
                <div class="text-sm space-y-1">
                    @forelse($patient->pastMedicalConditions as $c)
                    <div>• {{ $c->condition_type }} @if($c->other_name) ({{ $c->other_name }}) @endif @if(!empty($c->notes)) — {{ $c->notes }}@endif</div>
                    @empty
                    @endforelse
                </div>
            </div>

            <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900 shadow-sm p-5">
                <h2 class="text-base font-medium mb-3">Immunization</h2>
                <div class="text-sm">
                    {{ optional($patient->immunization)->status ?? '' }}
                </div>
            </div>

            <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900 shadow-sm p-5">
                <h2 class="text-base font-medium mb-3">Development Concerns</h2>
                <div class="text-sm space-y-1">
                    @forelse($patient->developmentConcerns as $d)
                    <div>• {{ $d->area }}</div>
                    @empty
                    @endforelse
                </div>
            </div>

            <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900 shadow-sm p-5">
                <h2 class="text-base font-medium mb-3">Current Symptoms</h2>
                <div class="text-sm space-y-1">
                    @forelse($patient->currentSymptoms as $s)
                    <div>• {{ $s->symptom_type }} @if($s->other_name) ({{ $s->other_name }}) @endif @if(!empty($s->details)) — {{ $s->details }}@endif</div>
                    @empty
                    @endforelse
                </div>
            </div>

            <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900 shadow-sm p-5 lg:col-span-2">
                <h2 class="text-base font-medium mb-3">Additional Notes</h2>
                <div class="text-sm">
                    {{ optional($patient->additionalNote)->notes ?? '' }}
                </div>
            </div>
        </div>

        <div class="mt-6 rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900 shadow-sm p-5">
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-base font-medium">Consultations</h2>
                <a href="{{ route('admin.patients.consultations.create', $patient) }}" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 text-white px-3 py-2 hover:bg-blue-700 transition-colors" wire:navigate>
                    <flux:icon.clipboard-document-list variant="mini" /> Conduct Consultation
                </a>
            </div>
            @if($patient->consultations && $patient->consultations->count())
            <div class="divide-y divide-neutral-200 dark:divide-neutral-700">
                @foreach($patient->consultations->sortByDesc('conducted_at') as $consultation)
                <div class="py-3">
                    <div class="text-sm text-neutral-600 dark:text-neutral-300">
                        <span class="font-medium">{{ $consultation->conducted_at?->format('Y-m-d H:i') ?? '—' }}</span>
                        <span class="ml-2">{{ $consultation->visit_type }}</span>
                    </div>
                    @if($consultation->chief_complaint)
                    <div class="text-sm mt-1"><span class="text-neutral-500">Chief Complaint:</span> {{ $consultation->chief_complaint }}</div>
                    @endif
                    @if($consultation->examination)
                    <div class="text-sm mt-1"><span class="text-neutral-500">Examination:</span> {{ $consultation->examination }}</div>
                    @endif
                    @if($consultation->diagnosis)
                    <div class="text-sm mt-1"><span class="text-neutral-500">Diagnosis:</span> {{ $consultation->diagnosis }}</div>
                    @endif
                    @if($consultation->plan)
                    <div class="text-sm mt-1"><span class="text-neutral-500">Plan:</span> {{ $consultation->plan }}</div>
                    @endif
                    @if($consultation->prescriptions)
                    <div class="text-sm mt-1"><span class="text-neutral-500">Prescriptions:</span> {{ $consultation->prescriptions }}</div>
                    @endif
                    @if($consultation->notes)
                    <div class="text-sm mt-1"><span class="text-neutral-500">Notes:</span> {{ $consultation->notes }}</div>
                    @endif
                </div>
                @endforeach
            </div>
            @else
            <p class="text-sm text-neutral-600 dark:text-neutral-300">No consultations recorded yet.</p>
            @endif
        </div>
    </div>
</x-layouts.app>
