<x-layouts.app :title="__('Billing & Payments')">
    <div id="staff-billing-page" class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900 p-4">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold">{{ __('Billing & Payments') }}</h2>
                <div class="flex items-center gap-2">
                    <a href="{{ route('staff.billing.index', request()->query()) }}" class="inline-flex items-center justify-center rounded-md p-1.5 hover:bg-neutral-200 dark:hover:bg-neutral-700" title="Refresh" wire:navigate>
                        <flux:icon.arrow-path variant="mini" />
                    </a>
                    <a href="{{ route('staff.appointments.index') }}" class="inline-flex items-center gap-2 rounded-lg bg-neutral-900 text-white px-3 py-1 hover:bg-neutral-700" wire:navigate>
                        <flux:icon.calendar-days variant="mini" /> {{ __('View Appointments') }}
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="rounded-lg border border-neutral-200 dark:border-neutral-700 p-4 bg-gradient-to-br from-emerald-50 to-white dark:from-emerald-900/20 dark:to-zinc-900">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-neutral-600 dark:text-neutral-300">{{ __('Total Paid') }}</span>
                        <flux:icon.banknotes variant="mini" class="text-emerald-600" />
                    </div>
                    <div class="mt-2 text-2xl font-semibold">₱{{ number_format(($stats['paid_total'] ?? 0) / 100, 2) }}</div>
                </div>
                <div class="rounded-lg border border-neutral-200 dark:border-neutral-700 p-4 bg-gradient-to-br from-amber-50 to-white dark:from-amber-900/20 dark:to-zinc-900">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-neutral-600 dark:text-neutral-300">{{ __('Pending Payments') }}</span>
                        <flux:icon.clock variant="mini" class="text-amber-600" />
                    </div>
                    <div class="mt-2 text-2xl font-semibold">{{ $stats['pending_count'] ?? 0 }}</div>
                </div>
                <div class="rounded-lg border border-neutral-200 dark:border-neutral-700 p-4 bg-gradient-to-br from-sky-50 to-white dark:from-sky-900/20 dark:to-zinc-900">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-neutral-600 dark:text-neutral-300">{{ __('Last Paid At') }}</span>
                        <flux:icon.check-badge variant="mini" class="text-sky-600" />
                    </div>
                    <div class="mt-2 text-sm font-medium">{{ optional($stats['last_paid_at'] ?? null)->format('Y-m-d H:i') ?? '—' }}</div>
                </div>
            </div>

            <div class="flex items-center justify-between mb-2">
                <div class="flex items-center gap-2">
                    <a href="{{ route('staff.billing.index') }}" class="inline-flex items-center gap-2 rounded-md px-3 py-1.5 bg-neutral-100 hover:bg-neutral-200 dark:bg-neutral-800 dark:hover:bg-neutral-700" wire:navigate>
                        {{ __('All') }}
                    </a>
                    <a href="{{ route('staff.billing.index', ['status' => 'pending']) }}" class="inline-flex items-center gap-2 rounded-md px-3 py-1.5 bg-amber-100 hover:bg-amber-200 dark:bg-amber-900/30 dark:hover:bg-amber-900/50" wire:navigate>
                        {{ __('Pending') }}
                    </a>
                    <a href="{{ route('staff.billing.index', ['status' => 'paid']) }}" class="inline-flex items-center gap-2 rounded-md px-3 py-1.5 bg-emerald-100 hover:bg-emerald-200 dark:bg-emerald-900/30 dark:hover:bg-emerald-900/50" wire:navigate>
                        {{ __('Paid') }}
                    </a>
                    <a href="{{ route('staff.billing.index', ['status' => 'cancelled']) }}" class="inline-flex items-center gap-2 rounded-md px-3 py-1.5 bg-rose-100 hover:bg-rose-200 dark:bg-rose-900/30 dark:hover:bg-rose-900/50" wire:navigate>
                        {{ __('Cancelled') }}
                    </a>
                </div>
            </div>

            <div class="max-h-[70vh] overflow-auto">
                <table class="min-w-full text-sm">
                    <thead class="sticky top-0 bg-neutral-50 dark:bg-zinc-900/60 backdrop-blur supports-[backdrop-filter]:bg-neutral-50/80 dark:supports-[backdrop-filter]:bg-zinc-900/40">
                        <tr>
                            <th class="px-3 py-2 text-left">{{ __('#') }}</th>
                            <th class="px-3 py-2 text-left">{{ __('Appointment') }}</th>
                            <th class="px-3 py-2 text-left">{{ __('Client') }}</th>
                            <th class="px-3 py-2 text-left">{{ __('Amount') }}</th>
                            <th class="px-3 py-2 text-left">{{ __('Status') }}</th>
                            <th class="px-3 py-2 text-left">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                            @php
                                $statusClass = 'bg-neutral-100 text-neutral-800';
                                if ($payment->status === 'pending') $statusClass = 'bg-amber-100 text-amber-800';
                                elseif ($payment->status === 'paid') $statusClass = 'bg-emerald-100 text-emerald-800';
                                elseif ($payment->status === 'cancelled') $statusClass = 'bg-rose-100 text-rose-800';
                            @endphp
                            <tr class="border-t border-neutral-200 dark:border-neutral-700">
                                <td class="px-3 py-2">{{ $payment->id }}</td>
                                <td class="px-3 py-2">#{{ $payment->appointment_id }}</td>
                                <td class="px-3 py-2">{{ $payment->user->name ?? '—' }}</td>
                                <td class="px-3 py-2">₱{{ number_format(($payment->amount ?? 0) / 100, 2) }}</td>
                                <td class="px-3 py-2">
                                    <span class="inline-flex items-center rounded-md px-2 py-0.5 text-xs font-semibold {{ $statusClass }}">{{ ucfirst($payment->status) }}</span>
                                </td>
                                <td class="px-3 py-2">
                                    <a href="{{ route('staff.billing.payments.show', $payment) }}" class="inline-flex items-center gap-2 rounded-lg bg-neutral-900 text-white px-3 py-1 hover:bg-neutral-700" wire:navigate>
                                        <flux:icon.credit-card variant="mini" /> {{ __('Process') }}
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-3 py-4 text-center text-neutral-600 dark:text-neutral-300">{{ __('No payments found.') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $payments->links() }}
            </div>
        </div>
    </div>
</x-layouts.app>