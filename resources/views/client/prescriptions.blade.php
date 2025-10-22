<x-layouts.app :title="__('Prescriptions')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="rounded-2xl bg-gradient-to-br from-sky-50 to-white dark:from-zinc-800 dark:to-zinc-900 border border-neutral-200 dark:border-neutral-700 p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-xl font-semibold tracking-tight">{{ __('Prescriptions') }}</h2>
                    <p class="text-neutral-600 dark:text-neutral-300 text-sm">{{ __('Medications and treatment plans prescribed for your child.') }}</p>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('client.prescriptions.pdf') }}" target="_blank" class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 text-white px-3 py-2 hover:bg-indigo-700" title="Download Prescriptions PDF">
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

                    <div class="px-4 py-4">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-sm font-semibold tracking-tight">💊 {{ __('Active and Past Prescriptions') }}</h3>
                            <span class="text-xs text-neutral-500 dark:text-neutral-400">{{ __('Most recent first') }}</span>
                        </div>
                        @if(isset($prescriptions) && $prescriptions->count())
                            <div class="max-h-[60vh] overflow-auto rounded-xl border border-neutral-200 dark:border-neutral-700 shadow-sm">
                                <table class="min-w-full text-sm">
                                    <thead class="sticky top-0 bg-gradient-to-r from-sky-50 to-indigo-50 dark:from-zinc-800 dark:to-zinc-700 backdrop-blur supports-[backdrop-filter]:bg-sky-50/70 dark:supports-[backdrop-filter]:bg-zinc-800/50">
                                        <tr class="text-xs uppercase tracking-wide text-neutral-700 dark:text-neutral-300">
                                            <th class="px-3 py-2 text-left">{{ __('Start') }}</th>
                                            <th class="px-3 py-2 text-left">{{ __('End') }}</th>
                                            <th class="px-3 py-2 text-left">{{ __('Name') }}</th>
                                            <th class="px-3 py-2 text-left">{{ __('Type') }}</th>
                                            <th class="px-3 py-2 text-left">{{ __('Dosage') }}</th>
                                            <th class="px-3 py-2 text-left">{{ __('Frequency') }}</th>
                                            <th class="px-3 py-2 text-left">{{ __('Route') }}</th>
                                            <th class="px-3 py-2 text-left">{{ __('Instructions') }}</th>
                                            <th class="px-3 py-2 text-left">{{ __('Status') }}</th>
                                            <th class="px-3 py-2 text-left">{{ __('Prescriber') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                                        @foreach($prescriptions as $rx)
                                            <tr class="odd:bg-white even:bg-neutral-50 dark:odd:bg-zinc-900 dark:even:bg-zinc-800/50 hover:bg-neutral-100/60 dark:hover:bg-zinc-700/60 transition-colors">
                                                <td class="px-3 py-2 text-neutral-800 dark:text-neutral-200 whitespace-nowrap text-xs">
                                                    🗓️ {{ optional($rx->start_date)->format('M d, Y') ?? '—' }}
                                                </td>
                                                <td class="px-3 py-2 text-neutral-800 dark:text-neutral-200 whitespace-nowrap text-xs">
                                                    🗓️ {{ optional($rx->end_date)->format('M d, Y') ?? '—' }}
                                                </td>
                                                <td class="px-3 py-2 text-neutral-800 dark:text-neutral-200 text-xs">{{ $rx->name ?? '—' }}</td>
                                                <td class="px-3 py-2 text-neutral-800 dark:text-neutral-200 text-xs">{{ ucfirst($rx->type ?? '') }}</td>
                                                <td class="px-3 py-2 text-neutral-800 dark:text-neutral-200 text-xs">{{ $rx->dosage ?? '—' }}</td>
                                                <td class="px-3 py-2 text-neutral-800 dark:text-neutral-200 text-xs">{{ $rx->frequency ?? '—' }}</td>
                                                <td class="px-3 py-2 text-neutral-800 dark:text-neutral-200 text-xs">{{ $rx->route ?? '—' }}</td>
                                                <td class="px-3 py-2 text-neutral-800 dark:text-neutral-200 text-xs">
                                                    <span title="{{ $rx->instructions }}">{!! $wordWrap($rx->instructions ?? '', 20) ?: '—' !!}</span>
                                                </td>
                                                <td class="px-3 py-2 text-neutral-800 dark:text-neutral-200 text-xs">
                                                    @php($s = $rx->status)
                                                    @php($badgeColor = match($s){
                                                        'active' => 'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-300',
                                                        'completed' => 'bg-sky-100 text-sky-700 dark:bg-sky-900/40 dark:text-sky-300',
                                                        'cancelled' => 'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300',
                                                        default => 'bg-neutral-100 text-neutral-700 dark:bg-neutral-800 dark:text-neutral-200',
                                                    })
                                                    <span class="px-2 py-1 rounded-full text-xs font-medium {{ $badgeColor }}">{{ $s ? ucfirst($s) : '—' }}</span>
                                                </td>
                                                <td class="px-3 py-2 text-neutral-800 dark:text-neutral-200 text-xs">{{ optional($rx->prescriber)->name ?? '—' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-sm text-neutral-600 dark:text-neutral-300">{{ __('No prescriptions found for this account.') }}</div>
                        @endif
                    </div>
                </div>
            @else
                <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900 overflow-hidden">
                    <div class="px-4 py-3 border-b border-neutral-200 dark:border-neutral-700">
                        <div class="flex items-center justify-between">
                            <h3 class="text-sm font-semibold tracking-tight">{{ __('Prescriptions') }}</h3>
                        </div>
                    </div>
                    <div class="px-4 py-3">
                        <div class="text-sm text-neutral-600 dark:text-neutral-300">{{ __('No child record linked to this account yet.') }}</div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>