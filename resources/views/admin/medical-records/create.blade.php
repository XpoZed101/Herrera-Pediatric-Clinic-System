<x-layouts.app :title="__('Create Medical Record')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900 p-4">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold">Start Medical Record</h2>
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.medical-records.index') }}" class="inline-flex items-center justify-center rounded-md p-1.5 hover:bg-neutral-200 dark:hover:bg-neutral-700" title="Back" wire:navigate>
                        <flux:icon.arrow-left variant="mini" />
                    </a>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.medical-records.start') }}" class="space-y-4">
                @csrf
                <div>
                    <label for="appointment_id" class="block text-sm font-medium mb-1">Select Appointment</label>
                    <select id="appointment_id" name="appointment_id" class="w-full rounded-lg border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-zinc-800 px-3 py-2">
                        @foreach($appointments as $appt)
                             <option value="{{ $appt->id }}">
                                #{{ $appt->id }} — {{ optional($appt->user)->name ?? 'Unknown User' }} — {{ optional($appt->scheduled_at)->format('Y-m-d H:i') }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-neutral-500 mt-1">Only appointments without an existing medical record are listed.</p>
                </div>

                <div class="flex items-center gap-2">
                    <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-accent text-white px-4 py-2 hover:opacity-90">
                        <flux:icon.document-duplicate variant="mini" /> Continue
                    </button>
                    <a href="{{ route('admin.medical-records.index') }}" class="inline-flex items-center gap-2 rounded-lg bg-neutral-100 dark:bg-neutral-800 text-neutral-800 dark:text-neutral-200 px-4 py-2 hover:bg-neutral-200 dark:hover:bg-neutral-700" wire:navigate>
                        Cancel
                    </a>
                </div>
            </form>

            <div class="mt-4">
                {{ $appointments->links() }}
            </div>
        </div>
    </div>
</x-layouts.app>
