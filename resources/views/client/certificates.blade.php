<x-layouts.app :title="__('Certificates')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="rounded-2xl bg-gradient-to-br from-sky-50 to-white dark:from-zinc-800 dark:to-zinc-900 border border-neutral-200 dark:border-neutral-700 p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-xl font-semibold tracking-tight">{{ __('Medical Certificates') }}</h2>
                    <p class="text-neutral-600 dark:text-neutral-300 text-sm">{{ __('Download a certificate for each visit or record.') }}</p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('client.home') }}" class="inline-flex items-center gap-2 rounded-lg border border-neutral-200 dark:border-neutral-700 px-3 py-2 hover:bg-neutral-100 dark:hover:bg-neutral-800">
                        <flux:icon.home variant="mini" /> {{ __('Home') }}
                    </a>
                </div>
            </div>

            @if(isset($patient))
                <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900 overflow-hidden">
                    <div class="px-4 py-4 border-b border-neutral-200 dark:border-neutral-700">
                        @php($initials = collect(explode(' ', (string) $patient->child_name))->map(fn($w) => strtoupper(substr($w,0,1)))->join(''))
                        <div class="flex items-center gap-3">
                            <div class="grid place-items-center h-10 w-10 rounded-full bg-sky-100 text-sky-700 dark:bg-sky-900/40 dark:text-sky-300 text-sm font-semibold">
                                {{ $initials ?: '👶' }}
                            </div>
                            <div class="flex flex-col">
                                <div class="text-sm font-semibold">{{ $patient->child_name }}</div>
                                <div class="text-xs text-neutral-600 dark:text-neutral-300">{{ __('DOB') }} {{ $patient->date_of_birth }} • {{ __('Age') }} {{ $patient->age }} • {{ __('Sex') }} {{ ucfirst($patient->sex) }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="px-4 py-4">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-sm font-semibold tracking-tight">📄 {{ __('Certificates by visit') }}</h3>
                            <span class="text-xs text-neutral-500 dark:text-neutral-400">{{ __('Most recent first') }}</span>
                        </div>
                        @if(isset($medicalRecords) && $medicalRecords->count())
                            <div class="max-h-[60vh] overflow-auto rounded-xl border border-neutral-200 dark:border-neutral-700 shadow-sm">
                                <table class="min-w-full text-sm">
                                    <thead class="sticky top-0 bg-gradient-to-r from-sky-50 to-indigo-50 dark:from-zinc-800 dark:to-zinc-700 backdrop-blur supports-[backdrop-filter]:bg-sky-50/70 dark:supports-[backdrop-filter]:bg-zinc-800/50">
                                        <tr class="text-xs uppercase tracking-wide text-neutral-700 dark:text-neutral-300">
                                            <th class="px-3 py-2 text-left">{{ __('Conducted') }}</th>
                                            <th class="px-3 py-2 text-left">{{ __('Record') }}</th>
                                            <th class="px-3 py-2 text-left">{{ __('Physician') }}</th>
                                            <th class="px-3 py-2 text-left">{{ __('Actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                                        @foreach($medicalRecords as $record)
                                            <tr class="odd:bg-white even:bg-neutral-50 dark:odd:bg-zinc-900 dark:even:bg-zinc-800/50 hover:bg-neutral-100/60 dark:hover:bg-zinc-700/60 transition-colors">
                                                <td class="px-3 py-2 text-neutral-800 dark:text-neutral-200 whitespace-nowrap text-xs">
                                                    🗓️ {{ optional($record->conducted_at)->format('M d, Y') ?? optional($record->appointment->scheduled_at)->format('M d, Y') ?? '—' }}
                                                </td>
                                                <td class="px-3 py-2 text-neutral-800 dark:text-neutral-200 text-xs">#{{ $record->id }}</td>
                                                <td class="px-3 py-2 text-neutral-800 dark:text-neutral-200 text-xs">{{ optional($record->appointment->user)->name ?? '—' }}</td>
                                                <td class="px-3 py-2 text-neutral-800 dark:text-neutral-200 text-xs">
                                                    <a href="{{ route('client.medical-records.certificate.pdf', $record) }}" target="_blank" class="inline-flex items-center gap-1 rounded-md bg-indigo-600 text-white px-2 py-1 hover:bg-indigo-700" title="{{ __('Download Certificate PDF') }}">
                                                        <flux:icon.document-text variant="mini" /> {{ __('Certificate PDF') }}
                                                    </a>
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
                            <h3 class="text-sm font-semibold tracking-tight">{{ __('Certificates') }}</h3>
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
