<x-layouts.app :title="__('Edit Appointment #'.$appointment->id)">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900 p-4">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold">Edit Appointment #{{ $appointment->id }}</h2>
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.appointments.show', $appointment) }}" class="inline-flex items-center gap-2 rounded-lg bg-neutral-100 dark:bg-neutral-800 text-neutral-800 dark:text-neutral-200 px-3 py-1 hover:bg-neutral-200 dark:hover:bg-neutral-700" wire:navigate>
                        <flux:icon.eye variant="mini" /> View
                    </a>
                    <a href="{{ route('admin.appointments.index') }}" class="inline-flex items-center gap-2 rounded-lg bg-neutral-100 dark:bg-neutral-800 text-neutral-800 dark:text-neutral-200 px-3 py-1 hover:bg-neutral-200 dark:hover:bg-neutral-700" wire:navigate>
                        <flux:icon.arrow-left variant="mini" /> Back
                    </a>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.appointments.update', $appointment) }}" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-medium mb-1" for="scheduled_at">Scheduled At</label>
                    @php($value = optional($appointment->scheduled_at)->format('Y-m-d\TH:i'))
                    <input type="datetime-local" id="scheduled_at" name="scheduled_at" value="{{ $value }}" class="w-full rounded-lg border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-zinc-800 px-3 py-2" />
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1" for="visit_type">Visit Type</label>
                    <select id="visit_type" name="visit_type" class="w-full rounded-lg border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-zinc-800 px-3 py-2" required>
                        @php($current = old('visit_type', $appointment->visit_type))
                        <option value="well_visit" @selected($current === 'well_visit')>Well Visit</option>
                        <option value="follow_up" @selected($current === 'follow_up')>Follow Up</option>
                        <option value="immunization" @selected($current === 'immunization')>Immunization</option>
                        <option value="consultation" @selected($current === 'consultation')>Consultation</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1" for="reason">Reason</label>
                    <textarea id="reason" name="reason" rows="3" class="w-full rounded-lg border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-zinc-800 px-3 py-2" placeholder="Brief reason">{{ old('reason', $appointment->reason) }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1" for="notes">Notes</label>
                    <textarea id="notes" name="notes" rows="4" class="w-full rounded-lg border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-zinc-800 px-3 py-2" placeholder="Additional details">{{ old('notes', $appointment->notes) }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1" for="status">Status</label>
                    <select id="status" name="status" class="w-full rounded-lg border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-zinc-800 px-3 py-2">
                        @foreach(['requested','scheduled','completed','cancelled'] as $opt)
                            <option value="{{ $opt }}" @selected(($appointment->status ?? 'requested') === $opt)>{{ ucfirst($opt) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-center gap-2">
                    <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-accent text-white px-4 py-2 hover:opacity-90">
                        <flux:icon.check variant="mini" /> Save Changes
                    </button>
                    <a href="{{ route('admin.appointments.show', $appointment) }}" class="inline-flex items-center gap-2 rounded-lg bg-neutral-100 dark:bg-neutral-800 text-neutral-800 dark:text-neutral-200 px-4 py-2 hover:bg-neutral-200 dark:hover:bg-neutral-700" wire:navigate>
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>