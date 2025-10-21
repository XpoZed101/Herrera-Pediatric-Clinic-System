<x-layouts.app :title="__('Welcome Staff')">
    <div class="p-6 space-y-6">
        <div class="relative overflow-hidden rounded-3xl ring-1 ring-inset ring-zinc-200 dark:ring-zinc-700 bg-gradient-to-br from-sky-500/15 via-emerald-500/15 to-violet-500/15">
            <div class="p-8 sm:p-10">
                <h1 class="text-3xl sm:text-4xl font-semibold tracking-tight">{{ __('Welcome, Staff') }}</h1>
                <p class="mt-2 text-neutral-700 dark:text-neutral-300">{{ __('Your workspace is ready. Use the quick links below.') }}</p>
                <div class="mt-6 flex flex-wrap gap-3">
                    <flux:button icon="calendar-days" :href="route('staff.appointments.index')" wire:navigate>{{ __('View Appointments') }}</flux:button>

                    <flux:button variant="outline" icon="cog" :href="route('profile.edit')" wire:navigate>{{ __('Account Settings') }}</flux:button>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <div class="rounded-2xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900 p-5">
                <div class="flex items-center gap-3">
                    <flux:icon.calendar-days class="text-emerald-600" />
                    <div>
                        <div class="text-sm text-neutral-500 dark:text-neutral-400">{{ __('Today’s Appointments') }}</div>
                        <div class="text-2xl font-semibold">{{ number_format($stats['today_appointments'] ?? 0) }}</div>
                    </div>
                </div>
            </div>
            <div class="rounded-2xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900 p-5">
                <div class="flex items-center gap-3">
                    <flux:icon.clock class="text-amber-600" />
                    <div>
                        <div class="text-sm text-neutral-500 dark:text-neutral-400">{{ __('Pending Payments') }}</div>
                        <div class="text-2xl font-semibold">{{ number_format($stats['pending_payments'] ?? 0) }}</div>
                    </div>
                </div>
            </div>
            <div class="rounded-2xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900 p-5">
                <div class="flex items-center gap-3">
                    <flux:icon.banknotes class="text-violet-600" />
                    <div>
                        <div class="text-sm text-neutral-500 dark:text-neutral-400">{{ __('Total Paid') }}</div>
                        <div class="text-2xl font-semibold">₱{{ number_format(($stats['paid_total'] ?? 0) / 100, 2) }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900 p-5">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-lg font-semibold">{{ __('Download Reports') }}</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900 p-4">
                    <div class="flex items-center gap-2 mb-3">
                        <flux:icon.document-text class="text-blue-600" />
                        <div class="font-medium">{{ __('Appointments PDF') }}</div>
                    </div>
                    <form method="GET" action="{{ route('staff.reports.appointments.pdf') }}" target="_blank" class="space-y-3">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm text-neutral-600">{{ __('Start date') }}</label>
                                <input type="date" name="start" class="mt-1 w-full rounded-md border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-zinc-900 px-2 py-2 text-sm" />
                            </div>
                            <div>
                                <label class="block text-sm text-neutral-600">{{ __('End date') }}</label>
                                <input type="date" name="end" class="mt-1 w-full rounded-md border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-zinc-900 px-2 py-2 text-sm" />
                            </div>
                        </div>
                        <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 text-white px-3 py-2 hover:bg-blue-700">
                            <flux:icon.document-text variant="mini" /> {{ __('Generate PDF') }}
                        </button>
                    </form>
                </div>

                <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900 p-4">
                    <div class="flex items-center gap-2 mb-3">
                        <flux:icon.wallet class="text-violet-600" />
                        <div class="font-medium">{{ __('Payments PDF') }}</div>
                    </div>
                    <form method="GET" action="{{ route('staff.reports.payments.pdf') }}" target="_blank" class="space-y-3">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm text-neutral-600">{{ __('Start date') }}</label>
                                <input type="date" name="start" class="mt-1 w-full rounded-md border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-zinc-900 px-2 py-2 text-sm" />
                            </div>
                            <div>
                                <label class="block text-sm text-neutral-600">{{ __('End date') }}</label>
                                <input type="date" name="end" class="mt-1 w-full rounded-md border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-zinc-900 px-2 py-2 text-sm" />
                            </div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm text-neutral-600">{{ __('Status') }}</label>
                                <select name="status" class="mt-1 w-full rounded-md border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-zinc-900 px-2 py-2 text-sm">
                                    <option value="">{{ __('All') }}</option>
                                    <option value="pending">{{ __('Pending') }}</option>
                                    <option value="paid">{{ __('Paid') }}</option>
                                    <option value="cancelled">{{ __('Cancelled') }}</option>
                                </select>
                            </div>

                        </div>
                        <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-violet-600 text-white px-3 py-2 hover:bg-violet-700">
                            <flux:icon.document-text variant="mini" /> {{ __('Generate PDF') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900 p-5">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-lg font-semibold">{{ __('Appointments') }}</h3>
            </div>
            <div class="divide-y divide-neutral-200 dark:divide-neutral-700">
                @forelse(($appointments ?? collect()) as $appt)
                    <div class="flex items-center justify-between py-3">
                        <div>
                            <div class="font-medium">#{{ $appt->id }} — {{ optional($appt->patient)->child_name ?? (optional($appt->user)->name ?? '—') }}</div>
                            <div class="text-sm text-neutral-600 dark:text-neutral-400">{{ optional($appt->scheduled_at)->format('Y-m-d H:i') ?? '—' }} — {{ $appt->visit_type ?? '—' }}</div>
                        </div>
                        <a href="{{ route('staff.appointments.show', $appt) }}" class="inline-flex items-center gap-2 rounded-md bg-neutral-100 dark:bg-neutral-800 text-neutral-800 dark:text-neutral-200 px-2 py-1 hover:bg-neutral-200 dark:hover:bg-neutral-700" wire:navigate>
                            <flux:icon.eye variant="mini" /> {{ __('View') }}
                        </a>
                    </div>
                @empty
                    <div class="py-4 text-neutral-600 dark:text-neutral-300">{{ __('No appointments found.') }}</div>
                @endforelse
            </div>

            @if(method_exists(($appointments ?? null), 'links'))
                <div class="mt-4">
                    {{ $appointments->links() }}
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
