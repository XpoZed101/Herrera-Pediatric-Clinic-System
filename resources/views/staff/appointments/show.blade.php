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
                    <form method="POST" action="{{ route('staff.appointments.check-in', $appointment) }}" class="inline-flex items-center gap-2 js-confirm" data-confirm-title="Check in patient?" data-confirm-text="Mark this appointment as checked in." data-confirm-submit-text="Check in">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-1 rounded-lg bg-emerald-600 text-white px-3 py-1 hover:bg-emerald-700">
                            <flux:icon.check variant="mini" /> Check In
                        </button>
                    </form>
                @elseif(!$appointment->checked_out_at)
                    <form method="POST" action="{{ route('staff.appointments.check-out', $appointment) }}" class="inline-flex items-center gap-2 js-confirm" data-confirm-title="Check out patient?" data-confirm-text="Mark this appointment as checked out." data-confirm-submit-text="Check out">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-1 rounded-lg bg-indigo-600 text-white px-3 py-1 hover:bg-indigo-700">
                            <flux:icon.arrow-right-start-on-rectangle variant="mini" /> Check Out
                        </button>
                    </form>
                @endif
            </div>

            @if($appointment->medicalRecord)
                @php
                    $meds = optional($appointment->medicalRecord->prescriptions)->where('type', 'medication');
                @endphp
                <div class="mt-6">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-sm font-semibold tracking-tight">Medications To Dispense</h3>
                        <span class="inline-flex items-center rounded-md bg-neutral-100 dark:bg-neutral-800 px-2 py-0.5 text-xs text-neutral-700 dark:text-neutral-200">{{ $meds->count() }} total</span>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @forelse($meds as $p)
                            @php $erxSubmitted = $p->erx_enabled && ($p->erx_status === 'submitted'); @endphp
                            <div class="rounded-lg border border-neutral-200 dark:border-neutral-700 p-4">
                                <div class="flex items-center gap-2">
                                    <span class="font-medium">{{ $p->name }}</span>
                                    @if($p->status)
                                        <span class="inline-flex items-center rounded-md bg-neutral-100 dark:bg-neutral-800 text-neutral-700 dark:text-neutral-200 px-2 py-0.5 text-xs">{{ ucfirst($p->status) }}</span>
                                    @endif
                                    @if($p->erx_enabled)
                                        <span class="inline-flex items-center rounded-md px-2 py-0.5 text-xs {{ $erxSubmitted ? 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-200' : 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-200' }}">
                                            eRx: {{ $p->erx_status ? ucfirst($p->erx_status) : 'Enabled' }}
                                        </span>
                                    @endif
                                </div>
                                <div class="mt-2 text-xs text-neutral-600 dark:text-neutral-300">
                                    {{ $p->dosage }} • {{ $p->frequency }} • {{ $p->route }}
                                </div>
                                @if($p->instructions)
                                    <div class="mt-2 text-sm text-neutral-700 dark:text-neutral-300 whitespace-pre-line">{{ $p->instructions }}</div>
                                @endif
                                @if($p->start_date || $p->end_date)
                                    <div class="mt-2 text-xs text-neutral-600 dark:text-neutral-300">
                                        Start–End: {{ optional($p->start_date)->format('Y-m-d') ?? '—' }} to {{ optional($p->end_date)->format('Y-m-d') ?? '—' }}
                                    </div>
                                @endif
                                <div class="mt-2 text-xs text-neutral-700 dark:text-neutral-300">
                                    @if($erxSubmitted)
                                        Dispense at pharmacy: {{ $p->erx_pharmacy ?? '—' }}. Do not handover in‑clinic.
                                    @elseif(in_array(($p->status ?? ''), ['held','cancelled']))
                                        Do not dispense ({{ ucfirst($p->status) }}).
                                    @else
                                        Dispense in‑clinic as prescribed.
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-neutral-600 dark:text-neutral-300">No medications to dispense.</div>
                        @endforelse
                    </div>
                </div>
            @else
                <div class="mt-6 text-neutral-600 dark:text-neutral-300">No medical record linked; prescriptions unavailable.</div>
            @endif

        </div>
    </div>
</x-layouts.app>
