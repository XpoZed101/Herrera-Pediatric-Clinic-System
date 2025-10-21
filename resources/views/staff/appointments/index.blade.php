<x-layouts.app :title="__('Appointments')">
    <div id="staff-appointments-page" class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900 p-4">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold">Appointments</h2>
                <div class="flex items-center gap-2">
                    <a href="{{ route('staff.appointments.index') }}" class="inline-flex items-center justify-center rounded-md p-1.5 hover:bg-neutral-200 dark:hover:bg-neutral-700" title="Refresh" wire:navigate>
                        <flux:icon.arrow-path variant="mini" />
                    </a>
                    <a href="{{ route('staff.patients.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 text-white px-3 py-1 hover:bg-blue-700" wire:navigate>
                        <flux:icon.user-plus variant="mini" /> Register Patient
                    </a>
                </div>
            </div>

            <div class="max-h-[70vh] overflow-auto">
                <table class="min-w-full text-sm">
                    <thead class="sticky top-0 bg-neutral-50 dark:bg-zinc-900/60 backdrop-blur supports-[backdrop-filter]:bg-neutral-50/80 dark:supports-[backdrop-filter]:bg-zinc-900/40">
                        <tr>
                            <th class="px-3 py-2 text-left">Scheduled</th>
                            <th class="px-3 py-2 text-left">Requester</th>
                            <th class="px-3 py-2 text-left">Visit Type</th>
                            <th class="px-3 py-2 text-left">Status</th>
                            <th class="px-3 py-2 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($appointments as $appointment)
                            <tr class="border-t border-neutral-200 dark:border-neutral-700">
                                <td class="px-3 py-2">{{ optional($appointment->scheduled_at)->format('Y-m-d H:i') ?? '—' }}</td>
                                <td class="px-3 py-2">
                                    @if($appointment->user)
                                        <div class="text-neutral-900 dark:text-neutral-100">
                                            {{ $appointment->user->name }}
                                            @if($appointment->user->email)
                                                <span class="text-neutral-500 text-xs"> — {{ $appointment->user->email }}</span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-neutral-500">Unknown</span>
                                    @endif
                                </td>
                                <td class="px-3 py-2">{{ $appointment->visit_type ?? '—' }}</td>
                                <td class="px-3 py-2">
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
                                    <span class="inline-flex items-center rounded-md px-2 py-0.5 text-xs border {{ $classes }} capitalize">
                                        {{ $status }}
                                    </span>
                                </td>
                                <td class="px-3 py-2">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('staff.appointments.show', $appointment) }}" class="inline-flex items-center gap-2 rounded-lg bg-neutral-100 dark:bg-neutral-800 text-neutral-800 dark:text-neutral-200 px-3 py-1 hover:bg-neutral-200 dark:hover:bg-neutral-700" wire:navigate>
                                            <flux:icon.eye variant="mini" /> View
                                        </a>
                                        <form method="POST" action="{{ route('staff.appointments.update-status', $appointment) }}" class="inline-flex items-center gap-2 js-confirm" data-confirm-title="Update status?" data-confirm-text="This will change the appointment status." data-confirm-submit-text="Yes, update">
                                            @csrf
                                            @php $current = $appointment->status ?? 'requested'; @endphp
                                            <select name="status" class="rounded-md border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-zinc-900 text-sm px-2 py-1">
                                                <option value="requested" {{ $current === 'requested' ? 'selected' : '' }}>Requested</option>
                                                <option value="scheduled" {{ $current === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                                <option value="completed" {{ $current === 'completed' ? 'selected' : '' }}>Completed</option>
                                                <option value="cancelled" {{ $current === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                            </select>
                                            <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-neutral-100 dark:bg-neutral-800 text-neutral-800 dark:text-neutral-200 px-3 py-1 hover:bg-neutral-200 dark:hover:bg-neutral-700">
                                                <flux:icon.arrow-path variant="mini" /> Update
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('staff.appointments.email', $appointment) }}" class="inline js-confirm" data-confirm-title="Send email?" data-confirm-text="Notify patient about this appointment." data-confirm-submit-text="Send">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-purple-600 text-white px-3 py-1 hover:bg-purple-700">
                                                Email
                                            </button>
                                        </form>
                                        @if(!$appointment->checked_in_at)
                                            <form method="POST" action="{{ route('staff.appointments.check-in', $appointment) }}" class="inline js-confirm" data-confirm-title="Check in patient?" data-confirm-text="Mark this appointment as checked in." data-confirm-submit-text="Check in">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 text-white px-3 py-1 hover:bg-emerald-700">
                                                    <flux:icon.check variant="mini" /> Check In
                                                </button>
                                            </form>
                                        @elseif(!$appointment->checked_out_at)
                                            <form method="POST" action="{{ route('staff.appointments.check-out', $appointment) }}" class="inline js-confirm" data-confirm-title="Check out patient?" data-confirm-text="Mark this appointment as checked out." data-confirm-submit-text="Check out">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 text-white px-3 py-1 hover:bg-indigo-700">
                                                    <flux:icon.arrow-right-start-on-rectangle variant="mini" /> Check Out
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-3 py-4 text-center text-neutral-600 dark:text-neutral-300">No appointments found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $appointments->links() }}
            </div>
        </div>
    </div>
</x-layouts.app>
