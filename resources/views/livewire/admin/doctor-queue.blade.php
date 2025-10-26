<div class="min-h-[calc(100vh-8rem)] bg-gradient-to-br from-white to-neutral-50 dark:from-zinc-900 dark:to-zinc-950 px-4 py-6" wire:poll.10s>
    <div class="max-w-7xl mx-auto">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl md:text-3xl font-semibold tracking-tight">Doctor Queue</h1>
                <p class="text-neutral-600 dark:text-neutral-300">Today's appointments ordered by queue, updated live.</p>
            </div>
            <div class="flex items-center gap-2">
                <input type="text" wire:model.debounce.300ms="search" placeholder="Search patient or reason" class="w-64 rounded-lg border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-zinc-900 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
                <select wire:model="status" class="rounded-lg border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-zinc-900 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All statuses</option>
                    <option value="requested">Requested</option>
                    <option value="scheduled">Scheduled</option>
                    <option value="completed">Completed</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($appointments as $appt)
                <div class="group rounded-xl border border-neutral-200 dark:border-neutral-800 bg-white dark:bg-zinc-900 p-4 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-2">
                            <div class="font-medium text-lg">{{ $appt->patient->child_name ?? 'Unknown Patient' }}</div>
                            <span class="inline-flex items-center rounded-full px-2 py-1 text-xs font-semibold
                                {{ match($appt->status) {
                                    'requested' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/40 dark:text-yellow-200',
                                    'scheduled' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-200',
                                    'completed' => 'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-200',
                                    default => 'bg-neutral-100 text-neutral-700 dark:bg-neutral-800 dark:text-neutral-200'
                                } }}">
                                {{ ucfirst($appt->status) }}
                            </span>
                        </div>
                        <div class="text-sm text-neutral-600 dark:text-neutral-300">
                            {{ optional($appt->scheduled_at)->format('H:i') ?? '—' }}
                        </div>
                    </div>

                    <div class="text-sm text-neutral-700 dark:text-neutral-300 mb-2">Reason: {{ $appt->reason ?: '—' }}</div>

                    <div class="flex flex-wrap items-center gap-2 mb-3">
                        <span class="inline-flex items-center rounded-lg bg-neutral-100 dark:bg-neutral-800 px-2 py-1 text-xs text-neutral-700 dark:text-neutral-300">Queue #{{ $appt->queue_position ?? '—' }}</span>
                        <span class="inline-flex items-center rounded-lg px-2 py-1 text-xs
                            {{ $appt->checked_in_at ? 'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-200' : 'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-200' }}">
                            {{ $appt->checked_in_at ? 'Checked-in' : 'Not checked-in' }}
                        </span>
                    </div>

                    <div class="flex items-center gap-2">
                        @if($appt->patient)
                            <a href="{{ route('admin.patients.show', $appt->patient) }}" class="inline-flex items-center gap-2 rounded-lg border border-neutral-300 dark:border-neutral-700 px-3 py-2 text-sm hover:bg-neutral-100 dark:hover:bg-neutral-800">Patient</a>
                            <a href="{{ route('admin.patients.consultations.create', $appt->patient) }}" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 text-white px-3 py-2 text-sm hover:bg-blue-700">Start Consultation</a>
                        @else
                            <span class="inline-flex items-center gap-2 rounded-lg border border-neutral-300 dark:border-neutral-700 px-3 py-2 text-sm text-neutral-500">Patient</span>
                        @endif
                        <a href="{{ route('admin.appointments.index') }}" class="inline-flex items-center gap-2 rounded-lg border border-neutral-300 dark:border-neutral-700 px-3 py-2 text-sm hover:bg-neutral-100 dark:hover:bg-neutral-800">Appointments</a>
                    </div>
                </div>

             @empty
                <div class="text-sm text-neutral-600 dark:text-neutral-300">No appointments in today’s queue.</div>
             @endforelse
        </div>

        <div class="mt-6">
            {{ $appointments->links() }}
        </div>
    </div>
</div>
