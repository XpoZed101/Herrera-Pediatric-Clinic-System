<x-layouts.app :title="__('Billing')">
    <div id="billing-history-page" class="px-4 py-6">
        <div class="rounded-2xl bg-gradient-to-br from-rose-50 to-white dark:from-zinc-800 dark:to-zinc-900 border border-neutral-200 dark:border-neutral-700 p-6 mb-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-center">
                <div class="lg:col-span-2">
                    <h1 class="text-2xl font-semibold tracking-tight mb-1">{{ __('Billing & Payments') }}</h1>
                    <p class="text-neutral-600 dark:text-neutral-300">{{ __('Review your payment history, status, and manage pending bills.') }}</p>
                </div>
                <div class="relative flex items-center justify-end">
                    <flux:icon.credit-card variant="mini" class="size-12 text-rose-500 opacity-80" />
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">
            <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900 p-4">
                <div class="text-neutral-500 text-sm">{{ __('Total Paid') }}</div>
                <div class="mt-1 text-2xl font-semibold">PHP {{ number_format(($stats['paid_total'] ?? 0) / 100, 2) }}</div>
            </div>
            <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900 p-4">
                <div class="text-neutral-500 text-sm">{{ __('Pending Payments') }}</div>
                <div class="mt-1 text-2xl font-semibold">{{ $stats['pending_count'] ?? 0 }}</div>
            </div>
            <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900 p-4">
                <div class="text-neutral-500 text-sm">{{ __('Last Payment') }}</div>
                <div class="mt-1 text-2xl font-semibold">{{ !empty($stats['last_paid_at']) ? \Carbon\Carbon::parse($stats['last_paid_at'])->format('M d, Y g:i A') : '—' }}</div>
            </div>
        </div>

        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900 p-3 mb-4">
            @php $active = request('status'); @endphp
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('client.billing.history') }}" class="inline-flex items-center rounded-md border px-3 py-1 text-sm {{ $active ? 'bg-neutral-50 text-neutral-800 border-neutral-200 dark:bg-neutral-800 dark:text-neutral-200 dark:border-neutral-700' : 'bg-accent text-white border-accent' }}">
                    {{ __('All') }}
                </a>
                @foreach(['paid' => 'Paid', 'pending' => 'Pending', 'cancelled' => 'Cancelled'] as $key => $label)
                    <a href="{{ route('client.billing.history', ['status' => $key]) }}" class="inline-flex items-center rounded-md border px-3 py-1 text-sm capitalize {{ $active === $key ? 'bg-accent text-white border-accent' : 'bg-neutral-50 text-neutral-800 border-neutral-200 dark:bg-neutral-800 dark:text-neutral-200 dark:border-neutral-700' }}" wire:navigate>
                        {{ __($label) }}
                    </a>
                @endforeach
            </div>
        </div>

        <div class="space-y-4">
            @forelse($payments as $payment)
                @php
                    $status = $payment->status ?? 'pending';
                    $badge = [
                        'paid' => 'bg-green-50 text-green-800 border-green-200 dark:bg-green-900/20 dark:text-green-200 dark:border-green-800',
                        'pending' => 'bg-yellow-50 text-yellow-800 border-yellow-200 dark:bg-yellow-900/20 dark:text-yellow-200 dark:border-yellow-800',
                        'cancelled' => 'bg-red-50 text-red-800 border-red-200 dark:bg-red-900/20 dark:text-red-200 dark:border-red-800',
                    ][$status] ?? 'bg-neutral-50 text-neutral-800 border-neutral-200 dark:bg-neutral-800 dark:text-neutral-200 dark:border-neutral-700';
                @endphp
                <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900 p-4">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div class="rounded-lg bg-rose-100 text-rose-600 dark:bg-rose-900/30 dark:text-rose-200 p-2">
                                <flux:icon.credit-card variant="mini" />
                            </div>
                            <div>
                                <div class="text-sm text-neutral-500">{{ __('Appointment') }} #{{ $payment->appointment_id ?? '—' }}</div>
                                <div class="font-medium">{{ optional($payment->appointment)->visit_type ? ucfirst(str_replace('_',' ', optional($payment->appointment)->visit_type)) : '—' }}</div>
                                <div class="text-xs text-neutral-500 mt-1">{{ __('Provider') }}: {{ strtoupper($payment->provider ?? 'paymongo') }} • {{ __('Method') }}: {{ $payment->payment_method ?? '—' }}</div>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-lg font-semibold">PHP {{ number_format(($payment->amount ?? 0) / 100, 2) }}</div>
                            <div class="mt-1 inline-flex items-center rounded-md px-2 py-0.5 text-xs border {{ $badge }} capitalize">{{ $status }}</div>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center justify-between gap-2">
                        <div class="text-xs text-neutral-500">
                            {{ __('Created') }}: {{ optional($payment->created_at)->format('M d, Y g:i A') ?? '—' }}
                            @if($payment->paid_at)
                                • {{ __('Paid') }}: {{ optional($payment->paid_at)->format('M d, Y g:i A') }}
                            @endif
                        </div>
                        <div class="flex items-center gap-2">
                            @if(($payment->status ?? '') === 'pending' && $payment->appointment)
                                <a href="{{ route('client.payments.checkout', $payment->appointment) }}" class="inline-flex items-center gap-2 rounded-lg bg-accent text-white px-3 py-1 hover:opacity-90" wire:navigate>
                                    <flux:icon.credit-card variant="mini" /> {{ __('Pay Now') }}
                                </a>
                            @elseif(($payment->status ?? '') === 'paid')
                                <span class="inline-flex items-center gap-2 rounded-lg bg-emerald-100 text-emerald-700 px-3 py-1">
                                    <flux:icon.check variant="mini" /> {{ __('Paid') }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900 p-4">
                    <div class="flex items-start gap-3">
                        <flux:icon.sparkles variant="mini" class="mt-0.5" />
                        <div>
                            <div class="font-medium">{{ __('No billing history yet') }}</div>
                            <p class="text-sm text-neutral-600 dark:text-neutral-300">{{ __('When you request an appointment, payments will appear here for easy tracking and management.') }}</p>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

        <div class="mt-4">
            {{ $payments->links() }}
        </div>
    </div>
</x-layouts.app>
