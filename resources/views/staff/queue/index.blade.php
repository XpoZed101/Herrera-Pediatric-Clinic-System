<x-layouts.app :title="__('Today’s Queue')">
    <div class="p-6 space-y-8">
        <div class="rounded-2xl bg-gradient-to-br from-sky-50 to-white dark:from-zinc-800 dark:to-zinc-900 border border-neutral-200 dark:border-neutral-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-semibold tracking-tight">{{ __('Today’s Queue') }}</h1>
                    <p class="text-neutral-600 dark:text-neutral-300">{{ __('Manage the in-clinic queue for today’s appointments.') }}</p>
                </div>
                <div class="hidden md:flex items-center gap-2">
                    <span class="inline-flex items-center gap-2 rounded-lg bg-neutral-900 text-white px-4 py-2">
                        <flux:icon.queue-list variant="mini" /> {{ __('Queue') }}
                    </span>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-5 border border-gray-100 dark:border-gray-700">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="text-left text-neutral-600 dark:text-neutral-300">
                            <th class="px-3 py-2">#</th>
                            <th class="px-3 py-2">{{ __('Patient') }}</th>
                            <th class="px-3 py-2">{{ __('Scheduled') }}</th>
                            <th class="px-3 py-2">{{ __('Status') }}</th>
                            <th class="px-3 py-2">{{ __('Check-in/out') }}</th>
                            <th class="px-3 py-2">{{ __('Position') }}</th>
                            <th class="px-3 py-2">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($appointments as $appointment)
                        @php
                            $statusColor = match($appointment->status){
                                'scheduled' => 'bg-sky-100 text-sky-700',
                                'completed' => 'bg-emerald-100 text-emerald-700',
                                'cancelled' => 'bg-red-100 text-red-700',
                                'requested' => 'bg-amber-100 text-amber-700',
                                default => 'bg-neutral-100 text-neutral-700',
                            };
                        @endphp
                        <tr class="border-t border-neutral-200 dark:border-neutral-700" data-appointment-id="{{ $appointment->id }}">
                            <td class="px-3 py-2">{{ $appointment->id }}</td>
                            <td class="px-3 py-2">{{ $appointment->patient->child_name ?? optional($appointment->user)->name ?? '—' }}</td>
                            <td class="px-3 py-2">{{ $appointment->scheduled_at?->format('Y-m-d H:i') }}</td>
                            <td class="px-3 py-2">
                                <span class="inline-flex items-center rounded px-2 py-1 {{ $statusColor }}">{{ ucfirst($appointment->status) }}</span>
                                @if($appointment->checked_in_at)
                                    <span class="ml-2 inline-flex items-center rounded px-2 py-1 bg-emerald-100 text-emerald-700">{{ __('Checked in') }}</span>
                                @endif
                            </td>
                            <td class="px-3 py-2">
                                @if(!$appointment->checked_in_at)
                                    <form method="POST" action="{{ route('staff.appointments.check-in', $appointment) }}" class="inline js-confirm" data-confirm-title="Check in patient?" data-confirm-text="Mark as checked in." data-confirm-submit-text="Check in">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 text-white px-3 py-1 hover:bg-emerald-700">
                                            <flux:icon.check variant="mini" /> {{ __('Check In') }}
                                        </button>
                                    </form>
                                @elseif(!$appointment->checked_out_at)
                                    <form method="POST" action="{{ route('staff.appointments.check-out', $appointment) }}" class="inline js-confirm" data-confirm-title="Check out patient?" data-confirm-text="Mark as checked out." data-confirm-submit-text="Check out">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 text-white px-3 py-1 hover:bg-indigo-700">
                                            <flux:icon.arrow-right-start-on-rectangle variant="mini" /> {{ __('Check Out') }}
                                        </button>
                                    </form>
                                @else
                                    <span class="text-neutral-600 dark:text-neutral-300">{{ __('Completed') }}</span>
                                @endif
                            </td>
                            <td class="px-3 py-2">
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex items-center rounded px-2 py-1 bg-neutral-100 text-neutral-700"><span class="js-position">{{ $appointment->queue_position ?? '—' }}</span></span>
                                    <button type="button" class="rounded bg-neutral-200 px-2 py-1 js-reorder" data-direction="up">▲</button>
                                    <button type="button" class="rounded bg-neutral-200 px-2 py-1 js-reorder" data-direction="down">▼</button>
                                </div>
                            </td>
                            <td class="px-3 py-2">
                                <span class="text-xs text-neutral-600 dark:text-neutral-300">{{ __('Use arrows to adjust queue order') }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-3 py-4 text-center text-neutral-600 dark:text-neutral-300">{{ __('No appointments in today’s queue') }}</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $appointments->links() }}</div>
        </div>
    </div>

    <div id="queue-config" data-reorder-url="{{ route('staff.queue.reorder') }}"></div>
    @vite(['resources/js/queue.js'])
</x-layouts.app>