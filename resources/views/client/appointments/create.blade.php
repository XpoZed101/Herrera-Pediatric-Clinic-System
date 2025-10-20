<x-layouts.app :title="__('Appointments')">
    <div id="appointments-page" class="px-4 py-6" 
         data-status="{{ session('status') }}" 
         data-error="{{ $errors->first('appointment') }}">
        <div class="rounded-2xl bg-gradient-to-br from-sky-50 to-white dark:from-zinc-800 dark:to-zinc-900 border border-neutral-200 dark:border-neutral-700 p-6 mb-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-center">
                <div>
                    <h1 class="text-2xl font-semibold tracking-tight mb-1">{{ __('Book, reschedule, or cancel appointments online') }}</h1>
                    <p class="text-neutral-600 dark:text-neutral-300">{{ __('Tell us what’s going on. We’ll match you with the right care at the right time.') }}</p>
                </div>
                <div class="relative">
                    <img src="{{ asset('images/doctor.png') }}" alt="Doctor" class="w-full max-h-40 object-contain drop-shadow-sm" />
                </div>
            </div>
        </div>

        {{-- Current Appointment Status --}}
        @include('client.appointments.partials.status-card', ['appointment' => $currentAppointment])

        @if(session('status'))
            <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900 p-4 mb-6 min-h-24 flex items-start gap-3">
                <flux:icon.sparkles variant="mini" class="mt-1" />
                <div>
                    <div class="font-medium mb-1">{{ __('Appointment Requested') }}</div>
                    <p class="text-sm text-neutral-600 dark:text-neutral-300">
                        {{ __('We’re reviewing your request right now. Expect a confirmation and suggested times shortly. Keep your phone nearby — we may reach out if we need more details.') }}
                    </p>
                    <ul class="mt-2 text-sm list-disc ms-4 text-neutral-600 dark:text-neutral-300">
                        <li>{{ __('Bring vaccination card and recent medications list.') }}</li>
                        <li>{{ __('Arrive 10 minutes early for check-in.') }}</li>
                        <li>{{ __('Reschedule easily if something changes.') }}</li>
                    </ul>
                </div>
            </div>
        @endif

        @if($errors->first('appointment') || session('suggested_times'))
            <div class="rounded-xl border border-red-200 bg-red-50 dark:bg-red-900/20 dark:border-red-800 p-4 mb-6">
                <div class="flex items-start gap-3">
                    <flux:icon.exclamation-triangle variant="mini" class="mt-1" />
                    <div class="flex-1">
                        <div class="font-medium mb-1">{{ $errors->first('appointment') ?? __('The selected time is not available.') }}</div>
                        @if(session('suggested_times'))
                            <div class="text-sm text-neutral-700 dark:text-neutral-300">
                                <div class="mb-2">{{ __('Suggested available times:') }}</div>
                                <div class="flex flex-wrap gap-2">
                                    @foreach(session('suggested_times') as $s)
                                        @php($formatted = \Carbon\Carbon::createFromFormat('Y-m-d H:i', ($s['date'] ?? date('Y-m-d')).' '.($s['time'] ?? '')))
                                        <button type="button" class="inline-flex items-center rounded-md border border-neutral-300 dark:border-neutral-700 px-3 py-1 text-xs bg-white dark:bg-zinc-800 hover:bg-neutral-50"
                                            data-date="{{ $s['date'] }}" data-time="{{ $s['time'] }}">
                                            {{ $formatted->format('M d, Y g:i A') }}
                                        </button>
                                    @endforeach
                                </div>
                                <p class="mt-2 text-xs">{{ __('Click a suggestion to auto-fill the form.') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    document.querySelectorAll('[data-date][data-time]').forEach(btn => {
                        btn.addEventListener('click', () => {
                            const d = btn.getAttribute('data-date');
                            const t = btn.getAttribute('data-time');
                            const dateInput = document.getElementById('scheduled_date');
                            const timeSelect = document.getElementById('scheduled_time');
                            if (dateInput && d) dateInput.value = d;
                            if (timeSelect && t) timeSelect.value = t;
                        });
                    });
                });
            </script>
        @endif

        @php($blocked = $currentAppointment && in_array(($currentAppointment->status ?? 'requested'), ['requested','scheduled']))
        @if($blocked)
            <div class="rounded-xl border border-yellow-200 bg-yellow-50 dark:bg-yellow-900/20 dark:border-yellow-800 p-4 mb-4">
                <div class="flex items-start gap-3">
                    <flux:icon.exclamation-triangle variant="mini" class="mt-1" />
                    <div>
                        <div class="font-medium">You have an active appointment</div>
                        <p class="text-sm text-neutral-700 dark:text-neutral-300">Please complete or cancel your current appointment before creating a new one.</p>
                    </div>
                </div>
            </div>
        @endif

        <form id="appointment-form" method="POST" action="{{ route('client.appointments.store') }}" class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            @csrf

            <div class="lg:col-span-8 rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900 p-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1" for="scheduled_date">{{ __('Preferred Date') }}</label>
                        <input type="date" id="scheduled_date" name="scheduled_date" class="w-full rounded-lg border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-zinc-800 px-3 py-2" required />
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1" for="scheduled_time">{{ __('Preferred Time (9am–3pm, every 30 minutes)') }}</label>
                        @php($allowedTimes = ['09:00','09:30','10:00','10:30','11:00','11:30','12:00','12:30','13:00','13:30','14:00','14:30','15:00'])
                        <select id="scheduled_time" name="scheduled_time" class="w-full rounded-lg border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-zinc-800 px-3 py-2" required>
                            <option value="" disabled selected>{{ __('Select a time') }}</option>
                            @foreach($allowedTimes as $t)
                                <option value="{{ $t }}">{{ \Carbon\Carbon::createFromFormat('H:i', $t)->format('g:i A') }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1" for="visit_type">{{ __('Visit Type') }}</label>
                        <select id="visit_type" name="visit_type" class="w-full rounded-lg border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-zinc-800 px-3 py-2" required>
                            <option value="" disabled @selected(!old('visit_type'))>{{ __('Select a visit type') }}</option>
                            @foreach($visitTypes as $type)
                                <option value="{{ $type->slug }}" @selected(old('visit_type') === $type->slug)>{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mt-4">
                    <label class="block text-sm font-medium mb-1" for="reason">{{ __('Reason (short)') }}</label>
                    <input type="text" id="reason" name="reason" class="w-full rounded-lg border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-zinc-800 px-3 py-2" placeholder="E.g. fever for 2 days" />
                </div>

                <div class="mt-6">
                    <h2 class="text-sm font-semibold mb-2">{{ __('Current Symptoms') }}</h2>
                    <p class="text-xs text-neutral-500 mb-3">{{ __('Check any symptoms your child has had recently.') }}</p>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                        @php($symptoms = [
                            'fever' => 'Fever',
                            'cough' => 'Cough',
                            'rash' => 'Rash',
                            'ear_pain' => 'Ear pain',
                            'stomach_pain' => 'Stomach pain',
                            'diarrhea' => 'Diarrhea',
                            'vomiting' => 'Vomiting',
                            'headaches' => 'Headaches',
                            'trouble_breathing' => 'Trouble breathing',
                        ])
                        @foreach($symptoms as $key => $label)
                            <label class="inline-flex items-center gap-2 text-sm">
                                <input type="checkbox" name="{{ $key }}" value="1" class="rounded border-neutral-300 dark:border-neutral-700">
                                {{ __($label) }}
                            </label>
                        @endforeach
                    </div>
                    <div class="mt-3">
                        <label class="block text-sm" for="symptom_other">{{ __('Other') }}</label>
                        <input type="text" id="symptom_other" name="symptom_other" class="w-full rounded-lg border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-zinc-800 px-3 py-2" placeholder="Describe other symptoms" />
                    </div>
                </div>

                <div class="mt-6">
                    <label class="block text-sm font-medium mb-1" for="notes">{{ __('Additional Notes') }}</label>
                    <textarea id="notes" name="notes" rows="4" class="w-full rounded-lg border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-zinc-800 px-3 py-2" placeholder="Anything else we should know"></textarea>
                </div>
            </div>

            <aside class="lg:col-span-4 space-y-4">
                <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900 p-4">
                    <h3 class="font-medium mb-2">{{ __('Visit Summary') }}</h3>
                    <p class="text-sm text-neutral-600 dark:text-neutral-300">{{ __('We’ll review your symptoms and confirm the best time.') }}</p>
                </div>
                <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900 p-4">
                    <button type="submit" @if($blocked) disabled @endif class="w-full inline-flex items-center justify-center gap-2 rounded-lg bg-accent text-white px-4 py-2 hover:opacity-90 disabled:opacity-60 disabled:cursor-not-allowed">
                        <flux:icon.calendar-days variant="mini" /> {{ __('Request Appointment') }}
                    </button>
                </div>
            </aside>
        </form>
    </div>
    @vite('resources/js/appointments.js')
</x-layouts.app>