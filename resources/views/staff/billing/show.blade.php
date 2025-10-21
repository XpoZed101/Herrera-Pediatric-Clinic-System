<x-layouts.app :title="__('Payment Processing')">
    <div id="staff-billing-detail" class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900">
            <div class="flex items-center justify-between p-4 border-b border-neutral-200 dark:border-neutral-700">
                <div class="flex items-center gap-2">
                    <a href="{{ route('staff.billing.index') }}" class="inline-flex items-center justify-center rounded-md p-1.5 hover:bg-neutral-200 dark:hover:bg-neutral-700" wire:navigate>
                        <flux:icon.arrow-left variant="mini" />
                    </a>
                    <h2 class="text-lg font-semibold">{{ __('Payment #') . $payment->id }}</h2>
                </div>
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center rounded-md px-2 py-0.5 text-xs font-semibold
                        @if($payment->status === 'pending') bg-amber-100 text-amber-800
                        @elseif($payment->status === 'paid') bg-emerald-100 text-emerald-800
                        @elseif($payment->status === 'cancelled') bg-rose-100 text-rose-800
                        @else bg-neutral-100 text-neutral-800 @endif">
                        {{ ucfirst($payment->status) }}
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 p-4">
                <div class="md:col-span-2 flex flex-col gap-4">
                    <div class="rounded-lg border border-neutral-200 dark:border-neutral-700 p-4">
                        <div class="text-sm text-neutral-600 dark:text-neutral-300">{{ __('Appointment') }}</div>
                        <div class="mt-2 flex items-center gap-2">
                            <flux:icon.calendar-days variant="mini" />
                            <div>
                                <div class="text-sm font-medium">{{ __('Appointment #') . ($payment->appointment_id) }}</div>
                                <div class="text-xs text-neutral-500">{{ $payment->appointment->scheduled_at?->format('Y-m-d H:i') ?? '—' }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-lg border border-neutral-200 dark:border-neutral-700 p-4">
                        <div class="text-sm text-neutral-600 dark:text-neutral-300">{{ __('Client') }}</div>
                        <div class="mt-2 flex items-center gap-2">
                            <flux:icon.user variant="mini" />
                            <div>
                                <div class="text-sm font-medium">{{ $payment->user->name ?? '—' }}</div>
                                <div class="text-xs text-neutral-500">{{ $payment->user->email ?? '' }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-lg border border-neutral-200 dark:border-neutral-700 p-4">
                        <div class="text-sm text-neutral-600 dark:text-neutral-300">{{ __('Amount') }}</div>
                        <div class="mt-2 text-2xl font-semibold">₱{{ number_format(($payment->amount ?? 0) / 100, 2) }}</div>
                        <div class="text-xs text-neutral-500">{{ __('Currency') }}: {{ $payment->currency ?? 'PHP' }}</div>
                    </div>
                </div>

                <div class="flex flex-col gap-4">
                    @if($payment->status !== 'paid')
                        <div class="rounded-lg border border-neutral-200 dark:border-neutral-700 p-4">
                            <div class="text-sm font-medium mb-2">{{ __('Process Payment') }}</div>
                            <form x-data="{ method: '{{ $payment->payment_method ?? 'cash' }}' }" method="POST" action="{{ route('staff.billing.payments.mark-paid', $payment) }}" class="flex flex-col gap-3">
                                @csrf
                                @method('PUT')
                                <div>
                                    <label class="text-xs text-neutral-600 dark:text-neutral-300">{{ __('Payment Method') }}</label>
                                    <select name="payment_method" x-model="method" class="mt-1 w-full rounded-md border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-zinc-800 p-2 text-sm">
                                        <option value="cash">{{ __('Cash') }}</option>
                                        <option value="card">{{ __('Card') }}</option>
                                        <option value="bank_transfer">{{ __('Bank Transfer') }}</option>
                                    </select>
                                </div>
                                <div x-show="['card','bank_transfer'].includes(method)" x-transition>
                                    <label class="text-xs text-neutral-600 dark:text-neutral-300 inline-flex items-center gap-1">{{ __('Reference Number') }}</label>
                                    <input type="text" name="reference" placeholder="e.g., AUTH123456 or BANK-REF-2025-00012" pattern="[A-Za-z0-9\-]{4,64}" class="mt-1 w-full rounded-md border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-zinc-800 p-2 text-sm" />
                                    <p class="text-xs text-neutral-500 mt-1">{{ __('Required for Card/Bank methods for audit trail.') }}</p>
                                </div>
                                <div>
                                    <label class="text-xs text-neutral-600 dark:text-neutral-300">{{ __('Amount (₱)') }}</label>
                                    <input type="number" name="amount_php" step="0.01" min="0" value="{{ number_format(($payment->amount ?? 0) / 100, 2) }}" class="mt-1 w-full rounded-md border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-zinc-800 p-2 text-sm" />
                                </div>
                                <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-lg bg-emerald-600 text-white px-3 py-2 hover:bg-emerald-700">
                                    <flux:icon.check-badge variant="mini" /> {{ __('Mark as Paid') }}
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="rounded-lg border border-neutral-200 dark:border-neutral-700 p-4">
                            <div class="text-sm font-medium mb-2">{{ __('Receipt') }}</div>
                            <div class="text-xs text-neutral-600 dark:text-neutral-300">{{ __('Paid At') }}: {{ $payment->paid_at?->format('Y-m-d H:i') }}</div>
                            <div class="text-xs text-neutral-600 dark:text-neutral-300">{{ __('Method') }}: {{ $payment->payment_method ?? '—' }}</div>
                            <div class="text-xs text-neutral-600 dark:text-neutral-300">{{ __('Reference') }}: {{ $payment->reference ?? '—' }}</div>
                            <div class="text-xs text-neutral-600 dark:text-neutral-300">{{ __('Provider') }}: {{ $payment->provider ?? 'manual' }}</div>
                        </div>
                    @endif

                    <div class="rounded-lg border border-neutral-200 dark:border-neutral-700 p-4">
                        <div class="text-sm font-medium mb-2">{{ __('Metadata') }}</div>
                        <pre class="text-xs bg-neutral-100 dark:bg-zinc-800 rounded-md p-2 overflow-auto">{{ json_encode($payment->metadata ?? [], JSON_PRETTY_PRINT) }}</pre>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>