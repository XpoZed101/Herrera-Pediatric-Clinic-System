<x-layouts.app :title="__('Visit Summary')">
    <div class="mx-auto w-full max-w-5xl flex flex-col gap-4">
        <div class="rounded-2xl bg-gradient-to-br from-indigo-50 to-white dark:from-zinc-800 dark:to-zinc-900 border border-neutral-200 dark:border-neutral-700 p-6 shadow-sm">
            @php($child = optional($record->appointment->patient))
            @php($conducted = optional($record->conducted_at)->format('M d, Y h:i A'))
            @php($visitType = $record->appointment?->visit_type ?: __('Visit'))
            @php($providerName = optional($provider)->name ?: __('—'))

            <div class="flex items-start justify-between gap-4 mb-4">
                <div class="flex items-center gap-3">
                    <div class="grid place-items-center h-12 w-12 rounded-full bg-indigo-100 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300 text-sm font-semibold">
                        {{ collect(explode(' ', (string) $child?->child_name))->map(fn($w) => strtoupper(substr($w,0,1)))->join('') ?: '👶' }}
                    </div>
                    <div class="flex flex-col">
                        <h2 class="text-xl font-semibold tracking-tight">{{ __('Visit Summary') }}</h2>
                        <p class="text-neutral-600 dark:text-neutral-300 text-sm">{{ $child?->child_name ?: __('Child') }} • {{ $conducted ?: '—' }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <a href="{{ route('client.medical-history') }}" class="inline-flex items-center gap-2 rounded-lg border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-800 text-neutral-800 dark:text-neutral-200 px-3 py-2 hover:bg-neutral-100 dark:hover:bg-zinc-700" wire:navigate>
                        <flux:icon.arrow-left variant="mini" /> {{ __('Back to Medical History') }}
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-6">
                <div class="rounded-lg border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900 px-4 py-3">
                    <div class="text-xs uppercase tracking-wide text-neutral-500 dark:text-neutral-400">{{ __('Visit Type') }}</div>
                    <div class="text-sm font-medium text-neutral-800 dark:text-neutral-200">{{ ucfirst($visitType) }}</div>
                </div>
                <div class="rounded-lg border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900 px-4 py-3">
                    <div class="text-xs uppercase tracking-wide text-neutral-500 dark:text-neutral-400">{{ __('Conducted') }}</div>
                    <div class="text-sm font-medium text-neutral-800 dark:text-neutral-200">{{ $conducted ?: '—' }}</div>
                </div>
                <div class="rounded-lg border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900 px-4 py-3">
                    <div class="text-xs uppercase tracking-wide text-neutral-500 dark:text-neutral-400">{{ __('Provider') }}</div>
                    <div class="text-sm font-medium text-neutral-800 dark:text-neutral-200">{{ $providerName }}</div>
                </div>
            </div>

            @php($wrap = function ($text, $wordsPerLine = 40) {
                $text = trim((string) $text);
                if ($text === '') { return ''; }
                $words = preg_split('/\s+/', $text, -1, PREG_SPLIT_NO_EMPTY);
                $chunks = array_chunk($words, $wordsPerLine);
                $lines = array_map(fn($chunk) => implode(' ', $chunk), $chunks);
                return implode('<br>', $lines);
            })

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900 p-4 shadow-sm">
                        <h3 class="text-sm font-semibold tracking-tight mb-2">🗣️ {{ __('Chief Complaint') }}</h3>
                        <div class="text-sm text-neutral-800 dark:text-neutral-200">{!! $wrap($record->chief_complaint ?? '') ?: '—' !!}</div>
                    </div>
                    <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900 p-4 shadow-sm">
                        <h3 class="text-sm font-semibold tracking-tight mb-2">🔎 {{ __('Examination') }}</h3>
                        <div class="text-sm text-neutral-800 dark:text-neutral-200">{!! $wrap($record->examination ?? '') ?: '—' !!}</div>
                    </div>
                    <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900 p-4 shadow-sm">
                        <h3 class="text-sm font-semibold tracking-tight mb-2">📝 {{ __('Notes') }}</h3>
                        <div class="text-sm text-neutral-800 dark:text-neutral-200">{!! $wrap($record->notes ?? '') ?: '—' !!}</div>
                    </div>
                </div>
                <div class="space-y-4">
                    <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900 p-4 shadow-sm">
                        <h3 class="text-sm font-semibold tracking-tight mb-2">🧠 {{ __('Diagnoses') }}</h3>
                        @php($dx = $record->diagnoses)
                        @if($dx->isNotEmpty())
                            <ul class="space-y-2">
                                @foreach($dx as $d)
                                    <li class="flex items-start gap-2">
                                        <span class="inline-flex items-center gap-1 rounded-full bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300 px-2 py-1 text-xs">{{ $d->severity ? ucfirst($d->severity) : __('Diagnosis') }}</span>
                                        <div class="text-sm text-neutral-800 dark:text-neutral-200">
                                            <div class="font-medium">{{ $d->title }}</div>
                                            @if($d->icd_code)
                                                <div class="text-xs text-neutral-500 dark:text-neutral-400">ICD: {{ $d->icd_code }}</div>
                                            @endif
                                            @if($d->description)
                                                <div class="text-xs mt-1">{{ $d->description }}</div>
                                            @endif
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <div class="text-sm text-neutral-800 dark:text-neutral-200">{{ __('No diagnoses recorded.') }}</div>
                        @endif
                    </div>
                    <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900 p-4 shadow-sm">
                        <h3 class="text-sm font-semibold tracking-tight mb-2">💊 {{ __('Prescriptions') }}</h3>
                        @php($rx = $record->prescriptions)
                        @if($rx->isNotEmpty())
                            <div class="space-y-3">
                                @foreach($rx as $p)
                                    <div class="rounded-lg border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900 p-3">
                                        <div class="flex items-center justify-between">
                                            <div class="font-medium text-neutral-800 dark:text-neutral-200">{{ $p->name }}</div>
                                            <span class="text-xs text-neutral-500 dark:text-neutral-400">{{ $p->type ?: __('Medication') }}</span>
                                        </div>
                                        <div class="text-xs text-neutral-600 dark:text-neutral-300 mt-1">
                                            @if($p->dosage) <span>{{ __('Dosage') }}: {{ $p->dosage }}</span>@endif
                                            @if($p->frequency) <span class="ml-2">{{ __('Frequency') }}: {{ $p->frequency }}</span>@endif
                                            @if($p->route) <span class="ml-2">{{ __('Route') }}: {{ $p->route }}</span>@endif
                                        </div>
                                        <div class="text-xs text-neutral-600 dark:text-neutral-300 mt-1">
                                            @if($p->start_date) <span>{{ __('Start') }}: {{ optional($p->start_date)->format('M d, Y') }}</span>@endif
                                            @if($p->end_date) <span class="ml-2">{{ __('End') }}: {{ optional($p->end_date)->format('M d, Y') }}</span>@endif
                                            @if($p->status) <span class="ml-2">{{ __('Status') }}: {{ ucfirst($p->status) }}</span>@endif
                                        </div>
                                        @if($p->instructions)
                                            <div class="text-xs mt-2">{{ $p->instructions }}</div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-sm text-neutral-800 dark:text-neutral-200">{{ __('No prescriptions recorded.') }}</div>
                        @endif
                    </div>
                    <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900 p-4 shadow-sm">
                        <h3 class="text-sm font-semibold tracking-tight mb-2">🗂️ {{ __('Plan') }}</h3>
                        <div class="text-sm text-neutral-800 dark:text-neutral-200">{!! $wrap($record->plan ?? '') ?: '—' !!}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
