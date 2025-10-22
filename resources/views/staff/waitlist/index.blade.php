<x-layouts.app :title="__('Waitlist Management')">
    <div class="p-6 space-y-8">
        <div class="rounded-2xl bg-gradient-to-br from-sky-50 to-white dark:from-zinc-800 dark:to-zinc-900 border border-neutral-200 dark:border-neutral-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-semibold tracking-tight">{{ __('Waitlist & Queue') }}</h1>
                    <p class="text-neutral-600 dark:text-neutral-300">{{ __('Manage patients waiting for a slot and invitations.') }}</p>
                </div>
                <div class="hidden md:flex items-center gap-2">
                    <span class="inline-flex items-center gap-2 rounded-lg bg-neutral-900 text-white px-4 py-2">
                        <flux:icon.clock variant="mini" /> {{ __('Waitlist') }}
                    </span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-5 border border-gray-100 dark:border-gray-700">
                <h3 class="text-gray-900 dark:text-white font-semibold mb-4">{{ __('Add to Waitlist') }}</h3>
                <form method="POST" action="{{ route('staff.waitlist.store') }}" class="space-y-3">
                    @csrf
                    <label class="block text-sm text-neutral-600">{{ __('Patient') }}</label>
                    <select name="patient_id" class="w-full rounded-md border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-zinc-900 px-3 py-2" required>
                        <option value="">{{ __('Select patient') }}</option>
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}">{{ $patient->child_name }}</option>
                        @endforeach
                    </select>

                    <label class="block text-sm text-neutral-600">{{ __('Triage level') }}</label>
                    <select name="triage_level" class="w-full rounded-md border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-zinc-900 px-3 py-2" required>
                        <option value="urgent">{{ __('Urgent') }}</option>
                        <option value="routine">{{ __('Routine') }}</option>
                        <option value="emergency">{{ __('Emergency') }}</option>
                    </select>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm text-neutral-600">{{ __('Desired start date') }}</label>
                            <input type="date" name="desired_date_start" class="w-full rounded-md border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-zinc-900 px-3 py-2" />
                        </div>
                        <div>
                            <label class="block text-sm text-neutral-600">{{ __('Desired end date') }}</label>
                            <input type="date" name="desired_date_end" class="w-full rounded-md border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-zinc-900 px-3 py-2" />
                        </div>
                    </div>

                    <label class="block text-sm text-neutral-600">{{ __('Notes (optional)') }}</label>
                    <textarea name="notes" rows="3" class="w-full rounded-md border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-zinc-900 px-3 py-2"></textarea>

                    <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 text-white px-3 py-2 hover:bg-blue-700">
                        <flux:icon.plus variant="mini" /> {{ __('Add to waitlist') }}
                    </button>
                </form>
            </div>

            <div class="xl:col-span-2 bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-5 border border-gray-100 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <h3 class="text-gray-900 dark:text-white font-semibold">{{ __('Waitlist') }}</h3>
                    <div class="flex items-center gap-3 text-sm">
                        <span class="rounded px-2 py-1 bg-amber-100 text-amber-700">{{ __('Waiting') }}: {{ $counts['waiting'] ?? 0 }}</span>
                        <span class="rounded px-2 py-1 bg-sky-100 text-sky-700">{{ __('Invited') }}: {{ $counts['invited'] ?? 0 }}</span>
                        <span class="rounded px-2 py-1 bg-emerald-100 text-emerald-700">{{ __('Scheduled') }}: {{ $counts['scheduled'] ?? 0 }}</span>
                    </div>
                </div>
                <div class="mt-4 overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="text-left text-neutral-600 dark:text-neutral-300">
                                <th class="px-3 py-2">#</th>
                                <th class="px-3 py-2">{{ __('Patient') }}</th>
                                <th class="px-3 py-2">{{ __('Triage') }}</th>
                                <th class="px-3 py-2">{{ __('Desired Range') }}</th>
                                <th class="px-3 py-2">{{ __('Status') }}</th>
                                <th class="px-3 py-2">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($entries as $entry)
                            <tr class="border-t border-neutral-200 dark:border-neutral-700">
                                <td class="px-3 py-2">{{ $entry->id }}</td>
                                <td class="px-3 py-2">{{ $entry->patient->child_name ?? '—' }}</td>
                                <td class="px-3 py-2">
                                    <span class="inline-flex items-center rounded px-2 py-1 {{ $entry->triage_badge_color }}">{{ ucfirst($entry->triage_level) }}</span>
                                </td>
                                <td class="px-3 py-2">{{ $entry->desired_date_start?->format('Y-m-d') ?? '—' }} — {{ $entry->desired_date_end?->format('Y-m-d') ?? '—' }}</td>
                                <td class="px-3 py-2">
                                    @php $statusColor = match($entry->status){
                                        'waiting' => 'bg-amber-100 text-amber-700',
                                        'invited' => 'bg-sky-100 text-sky-700',
                                        'scheduled' => 'bg-emerald-100 text-emerald-700',
                                        'expired' => 'bg-gray-100 text-gray-700',
                                        'cancelled' => 'bg-red-100 text-red-700',
                                        default => 'bg-neutral-100 text-neutral-700',
                                    }; @endphp
                                    <span class="inline-flex items-center rounded px-2 py-1 {{ $statusColor }}">{{ ucfirst($entry->status) }}</span>
                                </td>
                                <td class="px-3 py-2">
                                    <form method="POST" action="{{ route('staff.waitlist.update-status', $entry) }}" class="inline-flex items-center gap-2 js-confirm" data-confirm-title="Invite patient?" data-confirm-text="Send an invitation and mark the entry as invited." data-confirm-submit-text="Invite">
                                        @csrf
                                        <input type="hidden" name="status" value="invited" />
                                        <button type="submit" class="rounded bg-sky-600 text-white px-3 py-1 hover:bg-sky-700">{{ __('Invite') }}</button>
                                    </form>
                                    <form method="POST" action="{{ route('staff.waitlist.destroy', $entry) }}" class="inline-flex items-center gap-2 js-confirm" data-confirm-title="Remove entry?" data-confirm-text="This will remove the waitlist entry." data-confirm-submit-text="Remove">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded bg-red-600 text-white px-3 py-1 hover:bg-red-700">{{ __('Remove') }}</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-3 py-4 text-center text-neutral-600 dark:text-neutral-300">{{ __('No waitlist entries') }}</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">{{ $entries->links() }}</div>
            </div>
        </div>
    </div>
</x-layouts.app>