<x-layouts.app :title="__('Appointment #'.$appointment->id)">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900 p-4">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold">Appointment #{{ $appointment->id }}</h2>
                <div class="flex items-center gap-2">
                    <a href="{{ route('staff.appointments.index') }}" class="inline-flex items-center gap-2 rounded-lg bg-neutral-100 dark:bg-neutral-800 text-neutral-800 dark:text-neutral-200 px-3 py-1 hover:bg-neutral-200 dark:hover:bg-neutral-700" wire:navigate>
                        <flux:icon.arrow-left variant="mini" /> Back
                    </a>
                </div>
            </div>

            @php
                $status = $appointment->status ?? 'requested';
                $classes = 'bg-neutral-50 text-neutral-800 border-neutral-200 dark:bg-neutral-800 dark:text-neutral-200 dark:border-neutral-700';
                if ($status === 'requested') {
                    $classes = 'bg-yellow-50 text-yellow-800 border-yellow-200 dark:bg-yellow-900/20 dark:text-yellow-200 dark:border-yellow-800';
                } elseif ($status === 'scheduled') {
                    $classes = 'bg-blue-50 text-blue-800 border-blue-200 dark:bg-blue-900/20 dark:text-blue-200 dark:border-blue-800';
                } elseif ($status === 'completed') {
                    $classes = 'bg-green-50 text-green-800 border-green-200 dark:bg-green-900/20 dark:text-green-200 dark:border-green-800';
                } elseif ($status === 'cancelled') {
                    $classes = 'bg-red-50 text-red-800 border-red-200 dark:bg-red-900/20 dark:text-red-200 dark:border-red-800';
                }
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-2">
                    <div><span class="text-neutral-500 text-sm">Scheduled</span><div class="font-medium">{{ optional($appointment->scheduled_at)->format('Y-m-d H:i') ?? '—' }}</div></div>
                    <div><span class="text-neutral-500 text-sm">Visit Type</span><div class="font-medium">{{ $appointment->visit_type ?? '—' }}</div></div>
                    <div><span class="text-neutral-500 text-sm">Reason</span><div class="font-medium">{{ $appointment->reason ?? '—' }}</div></div>
                    <div><span class="text-neutral-500 text-sm">Notes</span><div class="font-medium whitespace-pre-wrap">{{ $appointment->notes ?? '—' }}</div></div>
                </div>
                <div class="space-y-2">
                    <div><span class="text-neutral-500 text-sm">Requester</span>
                        <div class="font-medium">{{ optional($appointment->user)->name ?? '—' }}
                            @if(optional($appointment->user)->email)
                                <span class="text-neutral-500 text-xs"> — {{ $appointment->user->email }}</span>
                            @endif
                        </div>
                    </div>
                    <div>
                        <span class="text-neutral-500 text-sm">Status</span>
                        <div>
                            <span class="inline-flex items-center rounded-md px-2 py-0.5 text-xs border {{ $classes }} capitalize">{{ $status }}</span>
                        </div>
                    </div>
                    <div class="space-y-1">
                        <div><span class="text-neutral-500 text-sm">Checked In</span><div class="font-medium">{{ optional($appointment->checked_in_at)->format('Y-m-d H:i') ?? '—' }}</div></div>
                        <div><span class="text-neutral-500 text-sm">Checked Out</span><div class="font-medium">{{ optional($appointment->checked_out_at)->format('Y-m-d H:i') ?? '—' }}</div></div>
                    </div>
                </div>
            </div>

            <div class="mt-4 flex items-center gap-3">
                @if(!$appointment->checked_in_at)
                    <form method="POST" action="{{ route('staff.appointments.check-in', $appointment) }}" class="inline-flex items-center gap-2">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-1 rounded-lg bg-emerald-600 text-white px-3 py-1 hover:bg-emerald-700">
                            <flux:icon.check variant="mini" /> Check In
                        </button>
                    </form>
                @endif
                @if(!$appointment->checked_out_at)
                    <form method="POST" action="{{ route('staff.appointments.check-out', $appointment) }}" class="inline-flex items-center gap-2">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-1 rounded-lg bg-indigo-600 text-white px-3 py-1 hover:bg-indigo-700">
                            <flux:icon.arrow-right-start-on-rectangle variant="mini" /> Check Out
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</x-layouts.app>