<x-layouts.app :title="__('Reschedule Appointment')">
    <div id="appointments-page" class="px-4 py-6">
        <div class="rounded-2xl bg-gradient-to-br from-sky-50 to-white dark:from-zinc-800 dark:to-zinc-900 border border-neutral-200 dark:border-neutral-700 p-6 mb-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-center">
                <div>
                    <h1 class="text-2xl font-semibold tracking-tight mb-1">{{ __('Reschedule your appointment') }}</h1>
                    <p class="text-neutral-600 dark:text-neutral-300">{{ __('Pick a new date and time that works for you.') }}</p>
                </div>
                <div class="relative">
                    <img src="{{ asset('images/calendar.png') }}" alt="Calendar" class="w-full max-h-40 object-contain drop-shadow-sm" />
                </div>
            </div>
        </div>

        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900 p-4 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3 text-sm">
                <div>
                    <div class="text-neutral-500">{{ __('Current Time') }}</div>
                    <div class="font-medium">{{ optional($appointment->scheduled_at)->format('Y-m-d H:i') ?? '—' }}</div>
                </div>
                <div>
                    <div class="text-neutral-500">{{ __('Visit Type') }}</div>
                    <div class="font-medium">{{ $appointment->visit_type ?? '—' }}</div>
                </div>
                <div>
                    <div class="text-neutral-500">{{ __('Status') }}</div>
                    <div class="font-medium capitalize">{{ $appointment->status ?? 'requested' }}</div>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('client.appointments.reschedule.update', $appointment) }}" class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            @csrf
            @method('PUT')

            <div class="lg:col-span-8 rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900 p-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1" for="scheduled_date">{{ __('New Date') }}</label>
                        <input type="date" id="scheduled_date" name="scheduled_date" class="w-full rounded-lg border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-zinc-800 px-3 py-2" required value="{{ optional($appointment->scheduled_at)->toDateString() }}" />
                        @error('scheduled_date')
                            <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1" for="scheduled_time">{{ __('New Time (9am–3pm, every 30 minutes)') }}</label>
                        @php($allowedTimes = ['09:00','09:30','10:00','10:30','11:00','11:30','12:00','12:30','13:00','13:30','14:00','14:30','15:00'])
                        @php($currentTime = optional($appointment->scheduled_at)->format('H:i'))
                        <select id="scheduled_time" name="scheduled_time" class="w-full rounded-lg border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-zinc-800 px-3 py-2" required>
                            <option value="" disabled {{ empty($currentTime) ? 'selected' : '' }}>{{ __('Select a time') }}</option>
                            @foreach($allowedTimes as $t)
                                <option value="{{ $t }}" @selected($currentTime === $t)>{{ \Carbon\Carbon::createFromFormat('H:i', $t)->format('g:i A') }}</option>
                            @endforeach
                        </select>
                        @error('scheduled_time')
                            <div class="text-xs text-red-600 mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <aside class="lg:col-span-4 space-y-4">
                <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900 p-4">
                    <h3 class="font-medium mb-2">{{ __('Confirm Changes') }}</h3>
                    <p class="text-sm text-neutral-600 dark:text-neutral-300">{{ __('We’ll review and confirm your new time.') }}</p>
                </div>
                <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900 p-4 flex items-center gap-2">
                    <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-lg bg-accent text-white px-4 py-2 hover:opacity-90">
                        <flux:icon.check variant="mini" /> {{ __('Update Appointment') }}
                    </button>
                    <a href="{{ route('client.appointments.create') }}" class="w-full inline-flex items-center justify-center gap-2 rounded-lg bg-neutral-100 dark:bg-neutral-800 text-neutral-800 dark:text-neutral-200 px-4 py-2 hover:bg-neutral-200 dark:hover:bg-neutral-700" wire:navigate>
                        <flux:icon.arrow-left variant="mini" /> {{ __('Back') }}
                    </a>
                </div>
            </aside>
        </form>
    </div>
    @vite('resources/js/appointments.js')
</x-layouts.app>