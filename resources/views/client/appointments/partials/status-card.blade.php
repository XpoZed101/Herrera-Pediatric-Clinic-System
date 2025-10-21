@if($appointment)
    @php
        $status = $appointment->status ?? 'requested';
        $badgeClasses = [
            'requested' => 'bg-yellow-50 text-yellow-800 border-yellow-200 dark:bg-yellow-900/20 dark:text-yellow-200 dark:border-yellow-800',
            'scheduled' => 'bg-blue-50 text-blue-800 border-blue-200 dark:bg-blue-900/20 dark:text-blue-200 dark:border-blue-800',
            'completed' => 'bg-green-50 text-green-800 border-green-200 dark:bg-green-900/20 dark:text-green-200 dark:border-green-800',
            'cancelled' => 'bg-red-50 text-red-800 border-red-200 dark:bg-red-900/20 dark:text-red-200 dark:border-red-800',
        ][$status] ?? 'bg-neutral-50 text-neutral-800 border-neutral-200 dark:bg-neutral-800 dark:text-neutral-200 dark:border-neutral-700';
        $allStatuses = ['requested','scheduled','completed','cancelled'];
        $isPaid = method_exists($appointment, 'payments') ? $appointment->payments()->where('status','paid')->exists() : false;
    @endphp
    <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900 p-4 mb-6">
        <div class="flex items-center justify-between gap-4 mb-3">
            <div class="flex-1">
                <div class="flex items-center justify-between">
                    <div class="font-medium">Your Appointment Status</div>
                    <span class="inline-flex items-center rounded-md px-2 py-0.5 text-xs border {{ $badgeClasses }} capitalize">{{ $status }}</span>
                </div>
                <div class="mt-2 flex flex-wrap items-center gap-1.5">
                    @foreach($allStatuses as $s)
                        <span class="inline-flex items-center rounded-md px-2 py-0.5 text-xs border capitalize {{ $s === $status ? 'bg-accent text-white border-accent' : 'bg-neutral-50 text-neutral-800 border-neutral-200 dark:bg-neutral-800 dark:text-neutral-200 dark:border-neutral-700' }}">
                            {{ $s }}
                        </span>
                    @endforeach
                </div>
            </div>
            <div class="w-16 md:w-20 shrink-0 flex items-center justify-center">
                <img src="{{ asset('images/announcement.png') }}" alt="Announcement" class="h-12 md:h-16 w-auto object-contain" aria-hidden="true" />
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 text-sm">
            <div>
                <div class="text-neutral-500">Scheduled</div>
                <div class="font-medium">{{ optional($appointment->scheduled_at)->format('Y-m-d H:i') ?? 'Pending scheduling' }}</div>
            </div>
            <div>
                <div class="text-neutral-500">Visit Type</div>
                <div class="font-medium">{{ $appointment->visit_type ?? '—' }}</div>
            </div>
            <div>
                <div class="text-neutral-500">Reason</div>
                <div class="font-medium">{{ $appointment->reason ?? '—' }}</div>
            </div>
        </div>

        @if($appointment->notes)
            <div class="mt-4">
                <div class="text-neutral-500 text-sm">Notes</div>
                <div class="text-sm whitespace-pre-wrap">{{ $appointment->notes }}</div>
            </div>
        @endif

        {{-- Actions: Pay / Reschedule / Cancel --}}
        @if(in_array($status, ['requested','scheduled']))
            <div class="mt-4 flex items-center gap-2 flex-wrap">
                @if(!$isPaid)
                    <form method="GET" action="{{ route('client.payments.checkout', $appointment) }}" class="inline">
                        <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 text-white px-3 py-1 hover:bg-emerald-700">
                            <flux:icon.credit-card variant="mini" /> {{ __('Pay Online (GCash/Bank)') }}
                        </button>
                    </form>
                @else
                    <span class="inline-flex items-center gap-2 rounded-lg bg-emerald-100 text-emerald-700 px-3 py-1">
                        <flux:icon.check variant="mini" /> {{ __('Paid') }}
                    </span>
                @endif

                @if(($appointment->reschedule_count ?? 0) < 1)
                    <a href="{{ route('client.appointments.reschedule', $appointment) }}" class="inline-flex items-center gap-2 rounded-lg bg-neutral-100 dark:bg-neutral-800 text-neutral-800 dark:text-neutral-200 px-3 py-1 hover:bg-neutral-200 dark:hover:bg-neutral-700" wire:navigate>
                        <flux:icon.arrow-path variant="mini" /> {{ __('Reschedule') }}
                    </a>
                @endif

                <form method="POST" action="{{ route('client.appointments.cancel', $appointment) }}" class="inline">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-rose-600 text-white px-3 py-1 hover:bg-rose-700">
                        <flux:icon.x-mark variant="mini" /> {{ __('Cancel') }}
                    </button>
                </form>
            </div>
        @endif
    </div>
@endif