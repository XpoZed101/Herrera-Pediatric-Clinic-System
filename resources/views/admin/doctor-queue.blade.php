<x-layouts.app :title="__('Doctor Queue')">
    <div class="p-6 space-y-8">
        <div class="rounded-2xl bg-gradient-to-br from-sky-50 to-white dark:from-zinc-800 dark:to-zinc-900 border border-neutral-200 dark:border-neutral-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-semibold tracking-tight">{{ __('Doctor Queue') }}</h1>
                    <p class="text-neutral-600 dark:text-neutral-300">{{ __('Today’s appointments ordered by queue.') }}</p>
                </div>
                <div class="hidden md:flex items-center gap-2">
                    <span class="inline-flex items-center gap-2 rounded-lg bg-neutral-900 text-white px-4 py-2">
                        <flux:icon.queue-list variant="mini" /> {{ __('Queue') }}
                    </span>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-5 border border-gray-100 dark:border-gray-700">
            <div class="flex items-center justify-between mb-4">
                <form method="GET" action="{{ route('admin.doctor.queue') }}" class="flex items-center gap-2">
                    <input type="text" name="search" value="{{ $search }}" placeholder="{{ __('Search patient or reason') }}" class="w-64 rounded-lg border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-zinc-900 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    <select name="status" class="rounded-lg border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-zinc-900 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">{{ __('All statuses') }}</option>
                        <option value="requested" @selected($status==='requested')>{{ __('Requested') }}</option>
                        <option value="scheduled" @selected($status==='scheduled')>{{ __('Scheduled') }}</option>
                        <option value="completed" @selected($status==='completed')>{{ __('Completed') }}</option>
                    </select>
                    <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 text-white px-3 py-2 text-sm hover:bg-blue-700">{{ __('Filter') }}</button>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="text-left text-neutral-600 dark:text-neutral-300">
                            <th class="px-3 py-2">#</th>
                            <th class="px-3 py-2">{{ __('Patient') }}</th>
                            <th class="px-3 py-2">{{ __('Scheduled') }}</th>
                            <th class="px-3 py-2">{{ __('Status') }}</th>
                            <th class="px-3 py-2">{{ __('Position') }}</th>
                            <th class="px-3 py-2">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($appointments as $appointment)
                            @php
                                $statusColor = 'bg-neutral-100 text-neutral-700';
                                if ($appointment->status === 'scheduled') {
                                    $statusColor = 'bg-sky-100 text-sky-700';
                                } elseif ($appointment->status === 'completed') {
                                    $statusColor = 'bg-emerald-100 text-emerald-700';
                                } elseif ($appointment->status === 'cancelled') {
                                    $statusColor = 'bg-red-100 text-red-700';
                                } elseif ($appointment->status === 'requested') {
                                    $statusColor = 'bg-amber-100 text-amber-700';
                                }
                            @endphp
                            <tr class="border-t border-neutral-200 dark:border-neutral-700" data-appointment-id="{{ $appointment->id }}">
                                <td class="px-3 py-2">{{ $appointment->id }}</td>
                                <td class="px-3 py-2">{{ $appointment->patient->child_name ?? optional($appointment->user)->name ?? '—' }}</td>
                                <td class="px-3 py-2">{{ optional($appointment->scheduled_at)->format('Y-m-d H:i') ?? '—' }}</td>
                                <td class="px-3 py-2">
                                    <span class="inline-flex items-center rounded px-2 py-1 {{ $statusColor }}">{{ ucfirst($appointment->status) }}</span>
                                    @if($appointment->checked_in_at)
                                        <span class="ml-2 inline-flex items-center rounded px-2 py-1 bg-emerald-100 text-emerald-700">{{ __('Arrived') }}</span>
                                    @endif
                                </td>
                                <td class="px-3 py-2">
                                    <span class="inline-flex items-center rounded px-2 py-1 bg-neutral-100 text-neutral-700">{{ $appointment->queue_position ?? '—' }}</span>
                                </td>
                                <td class="px-3 py-2">
                                    <div class="flex items-center gap-2">
                                        @if($appointment->patient)
                                            <a href="{{ route('admin.patients.show', $appointment->patient) }}" class="inline-flex items-center gap-2 rounded-lg border border-neutral-300 dark:border-neutral-700 px-3 py-1.5 text-sm hover:bg-neutral-100 dark:hover:bg-neutral-800">{{ __('Patient') }}</a>
                                            <a href="{{ route('admin.patients.consultations.create', $appointment->patient) }}" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 text-white px-3 py-1.5 text-sm hover:bg-blue-700">{{ __('Start Consultation') }}</a>
                                        @else
                                            <span class="inline-flex items-center gap-2 rounded-lg border border-neutral-300 dark:border-neutral-700 px-3 py-1.5 text-sm text-neutral-500">{{ __('Patient') }}</span>
                                        @endif

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-3 py-4 text-center text-neutral-600 dark:text-neutral-300">{{ __('No appointments in today’s queue') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">{{ $appointments->links() }}</div>
        </div>
    </div>
</x-layouts.app>