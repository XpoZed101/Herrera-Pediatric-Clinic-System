<x-layouts.app :title="__('Medical History Preview')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl">
        <!-- Hero / Header -->
        <div class="rounded-2xl bg-gradient-to-br from-sky-100 via-white to-pink-100 dark:from-zinc-800 dark:via-zinc-900 dark:to-zinc-800 border border-neutral-200 dark:border-neutral-700 p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-semibold tracking-tight">{{ __('Medical History Preview') }}</h2>
                    <p class="text-neutral-600 dark:text-neutral-300 text-sm">{{ __('Review the printable version before downloading as PDF.') }}</p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('client.medical-history') }}" class="inline-flex items-center gap-2 rounded-lg border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-800 text-neutral-800 dark:text-neutral-200 px-3 py-2 hover:bg-neutral-100 dark:hover:bg-zinc-700" wire:navigate>
                        <flux:icon.arrow-left variant="mini" /> {{ __('Back') }}
                    </a>
                    <a href="{{ route('client.medical-history.pdf') }}" target="_blank" class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 text-white px-3 py-2 hover:bg-indigo-700">
                        <flux:icon.document-text variant="mini" /> {{ __('Download PDF') }}
                    </a>
                </div>
            </div>
        </div>

        <!-- Patient card -->
        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900 overflow-hidden">
            @if($patient)
                @php($status = optional($patient->immunization)->status)
                @php($labels = ['yes' => __('Up to date'), 'no' => __('Not up to date'), 'not_sure' => __('Not sure')])
                <div class="px-4 py-4 border-b border-neutral-200 dark:border-neutral-700 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        @php($initials = collect(explode(' ', (string) $patient->child_name))->map(fn($w) => strtoupper(substr($w,0,1)))->join(''))
                        <div class="grid place-items-center h-12 w-12 rounded-full bg-sky-100 text-sky-700 dark:bg-sky-900/40 dark:text-sky-300 text-sm font-semibold">
                            {{ $initials ?: '👶' }}
                        </div>
                        <div>
                            <div class="text-sm font-semibold">{{ $patient->child_name }}</div>
                            <div class="text-xs text-neutral-600 dark:text-neutral-300">{{ __('DOB') }} {{ $patient->date_of_birth }} • {{ __('Age') }} {{ $patient->age }} • {{ __('Sex') }} {{ ucfirst($patient->sex) }}</div>
                        </div>
                    </div>
                    <span class="px-2 py-1 rounded-full text-xs font-medium @switch($status)
                        @case('yes') bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-300 @break
                        @case('no') bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300 @break
                        @case('not_sure') bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300 @break
                        @default bg-neutral-100 text-neutral-700 dark:bg-neutral-800 dark:text-neutral-200
                    @endswitch">
                        {{ $status ? ($labels[$status] ?? ucfirst($status)) : __('—') }}
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4">
                    <div class="rounded-lg bg-gradient-to-br from-blue-50 to-white dark:from-zinc-800 dark:to-zinc-900 border border-neutral-200 dark:border-neutral-700 p-4">
                        <h3 class="text-sm font-semibold mb-2">{{ __('Medications') }}</h3>
                        @php($meds = $patient->medications)
                        @if($meds->isNotEmpty())
                            <div class="flex flex-wrap gap-2">
                                @foreach($meds as $m)
                                    <span class="inline-flex items-center gap-1 rounded-full bg-neutral-100 dark:bg-neutral-800 text-neutral-700 dark:text-neutral-200 px-2 py-1 text-xs">{{ $m->name }}</span>
                                @endforeach
                            </div>
                        @else
                            <div class="text-neutral-600 dark:text-neutral-300 text-sm">{{ __('No medications recorded.') }}</div>
                        @endif
                    </div>
                    <div class="rounded-lg bg-gradient-to-br from-pink-50 to-white dark:from-zinc-800 dark:to-zinc-900 border border-neutral-200 dark:border-neutral-700 p-4">
                        <h3 class="text-sm font-semibold mb-2">{{ __('Allergies') }}</h3>
                        @php($allergies = $patient->allergies)
                        @if($allergies->isNotEmpty())
                            <div class="flex flex-wrap gap-2">
                                @foreach($allergies as $a)
                                    <span class="inline-flex items-center gap-1 rounded-full bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-300 px-2 py-1 text-xs">{{ $a->name }}</span>
                                @endforeach
                            </div>
                        @else
                            <div class="text-neutral-600 dark:text-neutral-300 text-sm">{{ __('No allergies recorded.') }}</div>
                        @endif
                    </div>
                    
                    <div class="rounded-lg bg-gradient-to-br from-amber-50 to-white dark:from-zinc-800 dark:to-zinc-900 border border-neutral-200 dark:border-neutral-700 p-4">
                        <h3 class="text-sm font-semibold mb-2">{{ __('Development & Current Symptoms') }}</h3>
                        @php($dc = $patient->developmentConcerns->pluck('area')->filter()->values())
                        @php($sym = $patient->currentSymptoms->map(function($s){ return ucfirst($s->symptom_type).($s->details ? ' — '.$s->details : ''); }))
                        <div class="text-sm text-neutral-800 dark:text-neutral-200">
                            <strong>{{ __('Development Concerns') }}:</strong> {{ $dc->isNotEmpty() ? $dc->map(fn($t) => ucfirst($t))->join(', ') : __('None recorded') }}
                        </div>
                        <div class="text-sm text-neutral-800 dark:text-neutral-200 mt-2">
                            <strong>{{ __('Current Symptoms') }}:</strong> {{ $sym->isNotEmpty() ? $sym->join(', ') : __('None recorded') }}
                        </div>
                        <div class="text-sm text-neutral-800 dark:text-neutral-200 mt-2">
                            <strong>{{ __('Notes') }}:</strong> {{ optional($patient->additionalNote)->notes ?? '—' }}
                        </div>
                    </div>
                </div>

                <!-- Records table -->
                <div class="p-4">
                    <h3 class="text-sm font-semibold mb-3">🩺 {{ __('Medical Records') }}</h3>
                    @if(($medicalRecords ?? collect())->isNotEmpty())
                        <div class="rounded-lg overflow-hidden border border-neutral-200 dark:border-neutral-700">
                            <table class="min-w-full text-sm">
                                <thead class="bg-neutral-50 dark:bg-zinc-800">
                                    <tr>
                                        <th class="px-3 py-2 text-left">{{ __('Date') }}</th>
                                        <th class="px-3 py-2 text-left">{{ __('Chief Complaint') }}</th>
                                        <th class="px-3 py-2 text-left">{{ __('Examination') }}</th>
                                        <th class="px-3 py-2 text-left">{{ __('Plan') }}</th>
                                        <th class="px-3 py-2 text-left">{{ __('Diagnosis') }}</th>
                                        <th class="px-3 py-2 text-left">{{ __('Notes') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                                    @foreach($medicalRecords as $record)
                                        @php($dx = $record->diagnoses->pluck('title')->filter())
                                        <tr class="odd:bg-white even:bg-neutral-50 dark:odd:bg-zinc-900 dark:even:bg-zinc-800/50">
                                            <td class="px-3 py-2 text-neutral-800 dark:text-neutral-200 whitespace-nowrap text-xs">{{ optional($record->conducted_at)->format('M d, Y h:i A') ?? '—' }}</td>
                                            <td class="px-3 py-2 text-neutral-800 dark:text-neutral-200 text-xs">{{ $record->chief_complaint ?? '—' }}</td>
                                            <td class="px-3 py-2 text-neutral-800 dark:text-neutral-200 text-xs">{{ $record->examination ?? '—' }}</td>
                                            <td class="px-3 py-2 text-neutral-800 dark:text-neutral-200 text-xs">{{ $record->plan ?? '—' }}</td>
                                            <td class="px-3 py-2 text-neutral-800 dark:text-neutral-200 text-xs">{{ $dx->isNotEmpty() ? $dx->join(', ') : '—' }}</td>
                                            <td class="px-3 py-2 text-neutral-800 dark:text-neutral-200 text-xs">{{ $record->notes ?? '—' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-sm text-neutral-600 dark:text-neutral-300">{{ __('No medical records found for this account.') }}</div>
                    @endif
                </div>
            @else
                <div class="p-4 text-sm text-neutral-600 dark:text-neutral-300">{{ __('No patient record found.') }}</div>
            @endif
        </div>
    </div>
</x-layouts.app>