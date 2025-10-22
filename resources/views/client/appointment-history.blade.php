<x-layouts.app :title="__('Appointment History')">
    <div class="px-4 py-6">
        <div class="rounded-2xl bg-gradient-to-br from-sky-50 to-white dark:from-zinc-800 dark:to-zinc-900 border border-neutral-200 dark:border-neutral-700 p-6 mb-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-center">
                <div>
                    <div class="inline-flex items-center gap-2 rounded-full bg-sky-100 text-sky-700 dark:bg-sky-900/40 dark:text-sky-300 px-3 py-1 text-xs mb-2">
                        <flux:icon.calendar-days variant="mini" />
                        {{ __('Your Appointments') }}
                    </div>
                    <h1 class="text-2xl font-semibold tracking-tight mb-1">{{ __('All current and past visits') }}</h1>
                    <p class="text-neutral-600 dark:text-neutral-300">{{ __('Review upcoming schedules, past visits, and manage changes with ease.') }}</p>
                </div>
                <div class="flex gap-2 justify-start lg:justify-end">
                    <a href="{{ route('client.appointments.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-accent text-white px-4 py-2 hover:opacity-90">
                        <flux:icon.calendar-days variant="mini" /> {{ __('Book or Manage') }}
                    </a>
                    <a href="{{ route('client.home') }}" class="inline-flex items-center gap-2 rounded-lg border border-neutral-200 dark:border-neutral-700 px-4 py-2 hover:bg-neutral-100 dark:hover:bg-neutral-800">
                        <flux:icon.home variant="mini" /> {{ __('Home') }}
                    </a>
                </div>
            </div>
        </div>

        {{-- Upcoming / Current Appointments --}}
        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900 p-5 mb-6">
            <div class="flex items-center gap-2 mb-3">
                <span class="inline-flex size-8 items-center justify-center rounded-lg bg-blue-50 text-blue-600 dark:bg-blue-950/50 dark:text-blue-300 ring-1 ring-blue-200/60 dark:ring-blue-800/30">
                    <flux:icon.clock variant="mini" />
                </span>
                <h2 class="font-semibold">{{ __('Upcoming') }}</h2>
            </div>
            @if(($currentAppointments ?? collect())->isEmpty())
                <div class="flex items-start gap-3 rounded-lg border border-neutral-200 dark:border-neutral-700 p-4">
                    <flux:icon.sparkles variant="mini" class="mt-1" />
                    <div class="text-sm text-neutral-600 dark:text-neutral-300">
                        {{ __('No upcoming appointments yet. Book a visit to get started.') }}
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($currentAppointments as $appt)
                        @php($dt = \Carbon\Carbon::parse($appt->scheduled_at))
                        <div class="group relative overflow-hidden rounded-xl bg-white dark:bg-white/5 border border-zinc-200/70 dark:border-white/10 backdrop-blur-sm shadow-sm hover:shadow-md transition-all p-4 flex flex-col gap-3">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <span class="inline-flex size-10 items-center justify-center rounded-xl bg-blue-50 text-blue-600 dark:bg-blue-950/40 dark:text-blue-300 ring-1 ring-blue-200/60 dark:ring-blue-800/30">
                                        <flux:icon.calendar-days variant="mini" />
                                    </span>
                                    <div>
                                        <div class="font-medium">{{ $dt->format('M d, Y') }} · {{ $dt->format('g:i A') }}</div>
                                        <div class="text-xs text-neutral-600 dark:text-neutral-300">{{ ucfirst($appt->visit_type ?? 'General') }}</div>
                                    </div>
                                </div>
                                <span class="inline-flex items-center gap-1 rounded-full px-2 py-1 text-xs
                                    @class([
                                        'bg-amber-50 text-amber-700 ring-1 ring-amber-200' => ($appt->status ?? 'requested') === 'requested',
                                        'bg-blue-50 text-blue-700 ring-1 ring-blue-200' => ($appt->status ?? 'scheduled') === 'scheduled',
                                    ])">
                                    <flux:icon.bolt variant="mini" /> {{ __($appt->status ?? 'requested') }}
                                </span>
                            </div>
                            <div class="text-sm text-neutral-600 dark:text-neutral-300">
                                {{ $appt->reason ?? __('No reason provided') }}
                            </div>
                            <x-client.vitals-summary :appointment="$appt" />
                            <div class="flex gap-2 mt-auto">
                                <a href="{{ route('client.appointments.create') }}" class="inline-flex items-center gap-2 rounded-lg border border-neutral-200 dark:border-neutral-700 px-3 py-1.5 text-xs hover:bg-neutral-100 dark:hover:bg-neutral-800">
                                    <flux:icon.pencil-square variant="mini" /> {{ __('Reschedule') }}
                                </a>
                                <form method="POST" action="{{ route('client.appointments.cancel', $appt) }}" onsubmit="return confirm('{{ __('Cancel this appointment?') }}')">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center gap-2 rounded-lg border border-red-200 text-red-700 bg-red-50 px-3 py-1.5 text-xs hover:bg-red-100 dark:border-red-800 dark:bg-red-900/20 dark:text-red-300">
                                        <flux:icon.x-mark variant="mini" /> {{ __('Cancel') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Past Appointments --}}
        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900 p-5">
            <div class="flex items-center gap-2 mb-3">
                <span class="inline-flex size-8 items-center justify-center rounded-lg bg-violet-50 text-violet-600 dark:bg-violet-950/50 dark:text-violet-300 ring-1 ring-violet-200/60 dark:ring-violet-800/30">
                    <flux:icon.archive-box variant="mini" />
                </span>
                <h2 class="font-semibold">{{ __('Past') }}</h2>
            </div>
            @if(($pastAppointments ?? collect())->isEmpty())
                <div class="flex items-start gap-3 rounded-lg border border-neutral-200 dark:border-neutral-700 p-4">
                    <flux:icon.information-circle variant="mini" class="mt-1" />
                    <div class="text-sm text-neutral-600 dark:text-neutral-300">
                        {{ __('No past appointments found.') }}
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($pastAppointments as $appt)
                        @php($dt = \Carbon\Carbon::parse($appt->scheduled_at))
                        <div class="group relative overflow-hidden rounded-xl bg-white dark:bg-white/5 border border-zinc-200/70 dark:border-white/10 backdrop-blur-sm shadow-sm hover:shadow-md transition-all p-4 flex flex-col gap-3">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <span class="inline-flex size-10 items-center justify-center rounded-xl bg-violet-50 text-violet-600 dark:bg-violet-950/40 dark:text-violet-300 ring-1 ring-violet-200/60 dark:ring-violet-800/30">
                                        <flux:icon.calendar-days variant="mini" />
                                    </span>
                                    <div>
                                        <div class="font-medium">{{ $dt->format('M d, Y') }} · {{ $dt->format('g:i A') }}</div>
                                        <div class="text-xs text-neutral-600 dark:text-neutral-300">{{ ucfirst($appt->visit_type ?? 'General') }}</div>
                                    </div>
                                </div>
                                <span class="inline-flex items-center gap-1 rounded-full px-2 py-1 text-xs
                                    @class([
                                        'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200' => ($appt->status ?? '') === 'completed',
                                        'bg-red-50 text-red-700 ring-1 ring-red-200' => ($appt->status ?? '') === 'cancelled',
                                        'bg-neutral-100 text-neutral-700 ring-1 ring-neutral-200' => !in_array(($appt->status ?? ''), ['completed','cancelled']),
                                    ])">
                                    <flux:icon.check-badge variant="mini" /> {{ __($appt->status ?? 'past') }}
                                </span>
                            </div>
                            <div class="text-sm text-neutral-600 dark:text-neutral-300">
                                {{ $appt->reason ?? __('No reason provided') }}
                            </div>
                            <x-client.vitals-summary :appointment="$appt" />
                            <div class="flex gap-2 mt-auto">
                                <a href="{{ route('client.appointments.create') }}" class="inline-flex items-center gap-2 rounded-lg border border-neutral-200 dark:border-neutral-700 px-3 py-1.5 text-xs hover:bg-neutral-100 dark:hover:bg-neutral-800">
                                    <flux:icon.eye variant="mini" /> {{ __('Book again') }}
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
