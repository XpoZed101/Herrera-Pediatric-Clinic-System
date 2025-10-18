<x-layouts.app>
    <div class="px-4 py-6">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-lg font-semibold">Patient Details</h1>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.patients.index') }}" class="inline-flex items-center justify-center rounded-md p-1.5 hover:bg-neutral-200 dark:hover:bg-neutral-700" title="Back" wire:navigate>
                    <flux:icon.chevron-left variant="mini" />
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            <div class="rounded-lg border border-neutral-200 dark:border-neutral-700 p-4">
                <h2 class="font-medium mb-2">Child</h2>
                <div class="text-sm">
                    <div><span class="text-neutral-500">Name:</span> {{ $patient->child_name }}</div>
                    <div><span class="text-neutral-500">DOB:</span> {{ $patient->date_of_birth }}</div>
                    <div><span class="text-neutral-500">Age:</span> {{ $patient->age }}</div>
                    <div><span class="text-neutral-500">Sex:</span> {{ ucfirst($patient->sex) }}</div>
                </div>
            </div>

            <div class="rounded-lg border border-neutral-200 dark:border-neutral-700 p-4">
                <h2 class="font-medium mb-2">Guardian</h2>
                <div class="text-sm">
                    @if($patient->guardian)
                    <div><span class="text-neutral-500">Name:</span> {{ $patient->guardian->name }}</div>
                    <div><span class="text-neutral-500">Phone:</span> {{ $patient->guardian->phone }}</div>
                    <div><span class="text-neutral-500">Email:</span> {{ $patient->guardian->email }}</div>
                    @endif
                </div>
            </div>

            <div class="rounded-lg border border-neutral-200 dark:border-neutral-700 p-4">
                <h2 class="font-medium mb-2">Emergency Contact</h2>
                <div class="text-sm">
                    @if($patient->emergencyContact)
                    <div><span class="text-neutral-500">Name:</span> {{ $patient->emergencyContact->name }}</div>
                    <div><span class="text-neutral-500">Phone:</span> {{ $patient->emergencyContact->phone }}</div>
                    @endif
                </div>
            </div>

            <div class="rounded-lg border border-neutral-200 dark:border-neutral-700 p-4">
                <h2 class="font-medium mb-2">Medications</h2>
                <div class="text-sm space-y-1">
                    @forelse($patient->medications as $m)
                    <div>• {{ $m->name }}@if(!empty($m->details)) — {{ $m->details }}@endif</div>
                    @empty
                    @endforelse
                </div>
            </div>

            <div class="rounded-lg border border-neutral-200 dark:border-neutral-700 p-4">
                <h2 class="font-medium mb-2">Allergies</h2>
                <div class="text-sm space-y-1">
                    @forelse($patient->allergies as $a)
                    <div>• {{ $a->name }}</div>
                    @empty
                    @endforelse
                </div>
            </div>

            <div class="rounded-lg border border-neutral-200 dark:border-neutral-700 p-4">
                <h2 class="font-medium mb-2">Past Medical Conditions</h2>
                <div class="text-sm space-y-1">
                    @forelse($patient->pastMedicalConditions as $c)
                    <div>• {{ $c->condition_type }} @if($c->other_name) ({{ $c->other_name }}) @endif @if(!empty($c->notes)) — {{ $c->notes }}@endif</div>
                    @empty
                    @endforelse
                </div>
            </div>

            <div class="rounded-lg border border-neutral-200 dark:border-neutral-700 p-4">
                <h2 class="font-medium mb-2">Immunization</h2>
                <div class="text-sm">
                    {{ optional($patient->immunization)->status ?? '' }}
                </div>
            </div>

            <div class="rounded-lg border border-neutral-200 dark:border-neutral-700 p-4">
                <h2 class="font-medium mb-2">Development Concerns</h2>
                <div class="text-sm space-y-1">
                    @forelse($patient->developmentConcerns as $d)
                    <div>• {{ $d->area }}</div>
                    @empty
                    @endforelse
                </div>
            </div>

            <div class="rounded-lg border border-neutral-200 dark:border-neutral-700 p-4">
                <h2 class="font-medium mb-2">Current Symptoms</h2>
                <div class="text-sm space-y-1">
                    @forelse($patient->currentSymptoms as $s)
                    <div>• {{ $s->symptom_type }} @if($s->other_name) ({{ $s->other_name }}) @endif @if(!empty($s->details)) — {{ $s->details }}@endif</div>
                    @empty
                    @endforelse
                </div>
            </div>

            <div class="rounded-lg border border-neutral-200 dark:border-neutral-700 p-4 lg:col-span-2">
                <h2 class="font-medium mb-2">Additional Notes</h2>
                <div class="text-sm">
                    {{ optional($patient->additionalNote)->notes ?? '' }}
                </div>
            </div>
        </div>

        <div class="mt-6 rounded-lg border border-neutral-200 dark:border-neutral-700 p-4">
            <div class="flex items-center justify-between mb-2">
                <h2 class="font-medium">Consultations</h2>
                <a href="{{ route('admin.patients.consultations.create', $patient) }}" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 text-white px-3 py-2 hover:bg-blue-700" wire:navigate>
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