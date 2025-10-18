<x-layouts.app :title="__('Child Health Summary')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="rounded-2xl bg-gradient-to-br from-sky-50 to-white dark:from-zinc-800 dark:to-zinc-900 border border-neutral-200 dark:border-neutral-700 p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-xl font-semibold tracking-tight">{{ __('Child Health Summary') }}</h2>
                    <p class="text-neutral-600 dark:text-neutral-300 text-sm">{{ __('A clean, modern overview of your child’s health records.') }}</p>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('client.medical-history.preview') }}" class="inline-flex items-center gap-2 rounded-lg border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-800 text-neutral-800 dark:text-neutral-200 px-3 py-2 hover:bg-neutral-100 dark:hover:bg-zinc-700" title="Preview Printable Page" wire:navigate>
                            <flux:icon.eye variant="mini" /> {{ __('Preview') }}
                        </a>
                        <a href="{{ route('client.medical-history.pdf') }}" target="_blank" class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 text-white px-3 py-2 hover:bg-indigo-700" title="Download Medical History PDF">
                            <flux:icon.document-text variant="mini" /> {{ __('Download PDF') }}
                        </a>
                    </div>
                </div>
            </div>

            @if($patient)
                <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900 overflow-hidden">
                    <div class="px-4 py-4 border-b border-neutral-200 dark:border-neutral-700">
                        @php($initials = collect(explode(' ', (string) $patient->child_name))->map(fn($w) => strtoupper(substr($w,0,1)))->join(''))
                        @php($status = optional($patient->immunization)->status)
                        @php($labels = ['yes' => __('Up to date'), 'no' => __('Not up to date'), 'not_sure' => __('Not sure')])
                        @php($colors = [
                            'yes' => 'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-300',
                            'no' => 'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300',
                            'not_sure' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300',
                        ])
                        <div class="flex items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <div class="grid place-items-center h-10 w-10 rounded-full bg-sky-100 text-sky-700 dark:bg-sky-900/40 dark:text-sky-300 text-sm font-semibold">
                                    {{ $initials ?: '👶' }}
                                </div>
                                <div class="flex flex-col">
                                    <div class="text-sm font-semibold">{{ $patient->child_name }}</div>
                                    <div class="text-xs text-neutral-600 dark:text-neutral-300">{{ __('DOB') }} {{ $patient->date_of_birth }} • {{ __('Age') }} {{ $patient->age }} • {{ __('Sex') }} {{ ucfirst($patient->sex) }}</div>
                                </div>
                            </div>
                            <span class="px-2 py-1 rounded-full text-xs font-medium {{ $status ? ($colors[$status] ?? 'bg-neutral-100 text-neutral-700 dark:bg-neutral-800 dark:text-neutral-200') : 'bg-neutral-100 text-neutral-700 dark:bg-neutral-800 dark:text-neutral-200' }}">
                                {{ $status ? ($labels[$status] ?? $status) : __('No immunization status') }}
                            </span>
                        </div>
                    </div>

                    <table class="min-w-full text-sm">
                        <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                            <tr class="hover:bg-neutral-50 dark:hover:bg-zinc-800/60">
                                <td class="w-1/3 px-4 py-3 font-medium text-neutral-700 dark:text-neutral-300">{{ __('Immunization Status') }}</td>
                                <td class="px-4 py-3 text-neutral-800 dark:text-neutral-200">
                                    @php($status = optional($patient->immunization)->status)
                                    @php($labels = ['yes' => __('Up to date'), 'no' => __('Not up to date'), 'not_sure' => __('Not sure')])
                                    {{ $status ? ($labels[$status] ?? $status) : __('No immunization status recorded.') }}
                                </td>
                            </tr>
                            <tr class="hover:bg-neutral-50 dark:hover:bg-zinc-800/60">
                                <td class="w-1/3 px-4 py-3 font-medium text-neutral-700 dark:text-neutral-300">{{ __('Medications') }}</td>
                                <td class="px-4 py-3 text-neutral-800 dark:text-neutral-200">
                                    @php($meds = $patient->medications)
                                    @if($meds->isNotEmpty())
                                        <div class="flex flex-wrap gap-2">
                                            @foreach($meds as $m)
                                                <span class="inline-flex items-center gap-1 rounded-full bg-neutral-100 dark:bg-neutral-800 text-neutral-700 dark:text-neutral-200 px-2 py-1 text-xs">
                                                    {{ $m->name }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @else
                                        {{ __('No medications recorded.') }}
                                    @endif
                                </td>
                            </tr>
                            <tr class="hover:bg-neutral-50 dark:hover:bg-zinc-800/60">
                                <td class="w-1/3 px-4 py-3 font-medium text-neutral-700 dark:text-neutral-300">{{ __('Allergies') }}</td>
                                <td class="px-4 py-3 text-neutral-800 dark:text-neutral-200">
                                    @php($allergies = $patient->allergies)
                                    @if($allergies->isNotEmpty())
                                        <div class="flex flex-wrap gap-2">
                                            @foreach($allergies as $a)
                                                <span class="inline-flex items-center gap-1 rounded-full bg-pink-100 dark:bg-pink-900/40 text-pink-700 dark:text-pink-300 px-2 py-1 text-xs">
                                                    {{ $a->name }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @else
                                        {{ __('No allergies recorded.') }}
                                    @endif
                                </td>
                            </tr>
                            <tr class="hover:bg-neutral-50 dark:hover:bg-zinc-800/60">
                                <td class="w-1/3 px-4 py-3 font-medium text-neutral-700 dark:text-neutral-300">{{ __('Past Medical Conditions') }}</td>
                                <td class="px-4 py-3 text-neutral-800 dark:text-neutral-200">
                                    @php($pmc = $patient->pastMedicalConditions)
                                    @if($pmc->isNotEmpty())
                                        <div class="flex flex-wrap gap-2">
                                            @foreach($pmc as $c)
                                                <span class="inline-flex items-center gap-1 rounded-full bg-purple-100 dark:bg-purple-900/40 text-purple-700 dark:text-purple-300 px-2 py-1 text-xs">
                                                    {{ $c->condition_type === 'other' ? ($c->other_name ?: __('Other')) : ucfirst($c->condition_type) }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @else
                                        {{ __('No past medical conditions recorded.') }}
                                    @endif
                                </td>
                            </tr>
                            <tr class="hover:bg-neutral-50 dark:hover:bg-zinc-800/60">
                                <td class="w-1/3 px-4 py-3 font-medium text-neutral-700 dark:text-neutral-300">{{ __('Development Concerns') }}</td>
                                <td class="px-4 py-3 text-neutral-800 dark:text-neutral-200">
                                    @php($dc = $patient->developmentConcerns->pluck('area')->filter()->values())
                                    {{ $dc->isNotEmpty() ? $dc->map(fn($t) => ucfirst($t))->join(', ') : __('No development concerns recorded.') }}
                                </td>
                            </tr>
                            <tr class="hover:bg-neutral-50 dark:hover:bg-zinc-800/60">
                                <td class="w-1/3 px-4 py-3 font-medium text-neutral-700 dark:text-neutral-300">{{ __('Current Symptoms') }}</td>
                                <td class="px-4 py-3 text-neutral-800 dark:text-neutral-200">
                                    @php($sym = $patient->currentSymptoms->map(function($s){ return ucfirst($s->symptom_type).($s->details ? ' — '.$s->details : ''); }))
                                    {{ $sym->isNotEmpty() ? $sym->join(', ') : __('No current symptoms recorded.') }}
                                </td>
                            </tr>
                            <tr class="hover:bg-neutral-50 dark:hover:bg-zinc-800/60">
                                <td class="w-1/3 px-4 py-3 font-medium text-neutral-700 dark:text-neutral-300">{{ __('Additional Notes') }}</td>
                                <td class="px-4 py-3 text-neutral-800 dark:text-neutral-200">{{ optional($patient->additionalNote)->notes ?? __('No additional notes recorded.') }}</td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="px-4 py-4 border-t border-neutral-200 dark:border-neutral-700">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-sm font-semibold tracking-tight">🩺 {{ __('Medical Records') }}</h3>
                            <span class="text-xs text-neutral-500 dark:text-neutral-400">{{ __('Latest first') }}</span>
                        </div>
                        @php(
                            $wordWrap = function ($text, $wordsPerLine = 30) {
                                $text = trim((string) $text);
                                if ($text === '') { return ''; }
                                $words = preg_split('/\s+/', $text, -1, PREG_SPLIT_NO_EMPTY);
                                $chunks = array_chunk($words, $wordsPerLine);
                                $lines = array_map(fn($chunk) => implode(' ', $chunk), $chunks);
                                return implode('<br>', $lines);
                            }
                        )
                        @if(isset($medicalRecords) && $medicalRecords->count())
                            <div class="max-h-[60vh] overflow-auto rounded-xl border border-neutral-200 dark:border-neutral-700 shadow-sm">
                                <table class="min-w-full text-sm">
                                    <thead class="sticky top-0 bg-gradient-to-r from-sky-50 to-indigo-50 dark:from-zinc-800 dark:to-zinc-700 backdrop-blur supports-[backdrop-filter]:bg-sky-50/70 dark:supports-[backdrop-filter]:bg-zinc-800/50">
                                        <tr class="text-xs uppercase tracking-wide text-neutral-700 dark:text-neutral-300">
                                            <th class="px-3 py-2 text-left">{{ __('Conducted') }}</th>
                                            <th class="px-3 py-2 text-left">{{ __('Chief Complaint') }}</th>
                                            <th class="px-3 py-2 text-left">{{ __('Examination') }}</th>
                                            <th class="px-3 py-2 text-left">{{ __('Plan') }}</th>
                                            <th class="px-3 py-2 text-left">{{ __('Diagnosis') }}</th>
                                            <th class="px-3 py-2 text-left">{{ __('Notes') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                                        @foreach($medicalRecords as $record)
                                            <tr class="odd:bg-white even:bg-neutral-50 dark:odd:bg-zinc-900 dark:even:bg-zinc-800/50 hover:bg-neutral-100/60 dark:hover:bg-zinc-700/60 transition-colors">
                                                <td class="px-3 py-2 text-neutral-800 dark:text-neutral-200 whitespace-nowrap text-xs">
                                                    🗓️ {{ optional($record->conducted_at)->format('M d, Y h:i A') ?? '—' }}
                                                </td>
                                                <td class="px-3 py-2 text-neutral-800 dark:text-neutral-200 text-xs">
                                                    <span class="inline-flex items-center gap-1">🗣️ <span title="{{ $record->chief_complaint }}">{!! $wordWrap($record->chief_complaint ?? '', 30) ?: '—' !!}</span></span>
                                                </td>
                                                <td class="px-3 py-2 text-neutral-800 dark:text-neutral-200 text-xs">
                                                    <span title="{{ $record->examination }}">{!! $wordWrap($record->examination ?? '', 30) ?: '—' !!}</span>
                                                </td>
                                                <td class="px-3 py-2 text-neutral-800 dark:text-neutral-200 text-xs">
                                                    <span title="{{ $record->plan }}">{!! $wordWrap($record->plan ?? '', 30) ?: '—' !!}</span>
                                                </td>
                                                <td class="px-3 py-2 text-neutral-800 dark:text-neutral-200 text-xs">
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
                                                <td class="px-3 py-2 text-neutral-800 dark:text-neutral-200 text-xs">
                                                    <span title="{{ $record->notes }}">{!! $wordWrap($record->notes ?? '', 30) ?: '—' !!}</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-sm text-neutral-600 dark:text-neutral-300">{{ __('No medical records found for this account.') }}</div>
                        @endif
                    </div>
                </div>
            @else
                <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900 overflow-hidden">
                    <div class="px-4 py-3 border-b border-neutral-200 dark:border-neutral-700">
                        <div class="flex items-center justify-between">
                            <h3 class="text-sm font-semibold tracking-tight">{{ __('Immunization Records') }}</h3>
                        </div>
                    </div>
                    <div class="px-4 py-3 border-b border-neutral-200 dark:border-neutral-700">
                        <h4 class="font-medium mb-2">{{ __('Child') }}</h4>
                        <div class="flex flex-wrap items-center gap-4 text-sm">
                            <div><span class="text-neutral-500">{{ __('Name') }}:</span> demo</div>
                            <div><span class="text-neutral-500">{{ __('DOB') }}:</span> 2025-10-27</div>
                            <div><span class="text-neutral-500">{{ __('Age') }}:</span> </div>
                            <div><span class="text-neutral-500">{{ __('Sex') }}:</span> {{ __('Male') }}</div>
                        </div>
                    </div>
                    <table class="min-w-full text-sm">
                        <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                            <tr class="hover:bg-neutral-50 dark:hover:bg-zinc-800/60">
                                <td class="w-1/3 px-4 py-3 font-medium text-neutral-700 dark:text-neutral-300">{{ __('Immunization Status') }}</td>
                                <td class="px-4 py-3 text-neutral-800 dark:text-neutral-200">{{ __('Not sure') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
