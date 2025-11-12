<x-layouts.app :title="__('Create Prescription')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900 p-4" x-data="{ type: '{{ old('type', 'medication') }}' }">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold">Create Prescription</h2>
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.prescriptions.index') }}" class="inline-flex items-center gap-2 rounded-lg bg-neutral-100 dark:bg-neutral-800 text-neutral-800 dark:text-neutral-200 px-3 py-1 hover:bg-neutral-200 dark:hover:bg-neutral-700" wire:navigate>
                        <flux:icon.arrow-left variant="mini" /> Back
                    </a>
                </div>
            </div>

            @if(session('status'))
                <flux:callout variant="success" icon="check" class="mb-4" :heading="session('status')" />
            @endif

            <form method="POST" action="{{ route('admin.prescriptions.store') }}" class="space-y-4">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Medical Record</label>
                        <select name="medical_record_id" class="w-full rounded-md border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-zinc-800 px-3 py-2">
                            <option value="">Select a record...</option>
                            @foreach($records as $record)
                                <option value="{{ $record->id }}" @selected(old('medical_record_id') == $record->id)>
                                    #{{ $record->id }} - {{ optional(data_get($record, 'appointment.patient'))->child_name ?? 'Unknown Patient' }}
                                </option>
                            @endforeach
                        </select>
                        @error('medical_record_id')
                            <flux:callout variant="danger" icon="x-circle" class="mt-2" :heading="$message" />
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Type</label>
                        <select name="type" x-model="type" class="w-full rounded-md border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-zinc-800 px-3 py-2">
                            <option value="medication">Medication</option>
                            <option value="treatment">Treatment</option>
                        </select>
                        @error('type')
                            <flux:callout variant="danger" icon="x-circle" class="mt-2" :heading="$message" />
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="w-full rounded-md border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-zinc-800 px-3 py-2" />
                        @error('name')
                            <flux:callout variant="danger" icon="x-circle" class="mt-2" :heading="$message" />
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Status</label>
                        <select name="status" class="w-full rounded-md border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-zinc-800 px-3 py-2">
                            <option value="active" @selected(old('status', 'active') === 'active')>Active</option>
                            <option value="completed" @selected(old('status') === 'completed')>Completed</option>
                            <option value="discontinued" @selected(old('status') === 'discontinued')>Discontinued</option>
                        </select>
                        @error('status')
                            <flux:callout variant="danger" icon="x-circle" class="mt-2" :heading="$message" />
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">Start Date</label>
                            <input type="date" name="start_date" value="{{ old('start_date') }}" class="w-full rounded-md border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-zinc-800 px-3 py-2" />
                            @error('start_date')
                                <flux:callout variant="danger" icon="x-circle" class="mt-2" :heading="$message" />
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">End Date</label>
                            <input type="date" name="end_date" value="{{ old('end_date') }}" class="w-full rounded-md border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-zinc-800 px-3 py-2" />
                            @error('end_date')
                                <flux:callout variant="danger" icon="x-circle" class="mt-2" :heading="$message" />
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Medication fields -->
                <div x-show="type === 'medication'" x-cloak class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Dosage</label>
                        <input type="text" name="dosage" value="{{ old('dosage') }}" class="w-full rounded-md border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-zinc-800 px-3 py-2" />
                        @error('dosage')
                            <flux:callout variant="danger" icon="x-circle" class="mt-2" :heading="$message" />
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Frequency</label>
                        <input type="text" name="frequency" value="{{ old('frequency') }}" class="w-full rounded-md border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-zinc-800 px-3 py-2" />
                        @error('frequency')
                            <flux:callout variant="danger" icon="x-circle" class="mt-2" :heading="$message" />
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Route</label>
                        <input type="text" name="route" value="{{ old('route') }}" class="w-full rounded-md border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-zinc-800 px-3 py-2" />
                        @error('route')
                            <flux:callout variant="danger" icon="x-circle" class="mt-2" :heading="$message" />
                        @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium mb-1">Instructions</label>
                        <textarea name="instructions" rows="3" class="w-full rounded-md border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-zinc-800 px-3 py-2">{{ old('instructions') }}</textarea>
                        @error('instructions')
                            <flux:callout variant="danger" icon="x-circle" class="mt-2" :heading="$message" />
                        @enderror
                    </div>

                    <!-- e‑Prescription options -->
                    <div class="md:col-span-2 border-t border-neutral-200 dark:border-neutral-700 pt-4">
                        <div class="flex items-center gap-2">
                            <input id="erx_submit" type="checkbox" name="erx_submit" value="1" @checked(old('erx_submit')) class="rounded border-neutral-300 dark:border-neutral-700" />
                            <label for="erx_submit" class="text-sm">Send as e‑prescription</label>
                        </div>
                        <div class="mt-3">
                            <label class="block text-sm font-medium mb-1">Preferred Pharmacy (optional)</label>
                            <input type="text" name="erx_pharmacy" value="{{ old('erx_pharmacy') }}" placeholder="e.g., CVS #1234" class="w-full rounded-md border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-zinc-800 px-3 py-2" />
                            @error('erx_pharmacy')
                                <flux:callout variant="danger" icon="x-circle" class="mt-2" :heading="$message" />
                            @enderror
                            <p class="mt-1 text-xs text-neutral-600 dark:text-neutral-400">When enabled, the prescription will be submitted electronically.</p>
                        </div>
                    </div>
                </div>

                <!-- Treatment fields -->
                <div x-show="type === 'treatment'" x-cloak class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Treatment Schedule</label>
                        <input type="text" name="treatment_schedule" value="{{ old('treatment_schedule') }}" class="w-full rounded-md border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-zinc-800 px-3 py-2" />
                        @error('treatment_schedule')
                            <flux:callout variant="danger" icon="x-circle" class="mt-2" :heading="$message" />
                        @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium mb-1">Instructions</label>
                        <textarea name="instructions" rows="3" class="w-full rounded-md border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-zinc-800 px-3 py-2">{{ old('instructions') }}</textarea>
                        @error('instructions')
                            <flux:callout variant="danger" icon="x-circle" class="mt-2" :heading="$message" />
                        @enderror
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2 pt-2">
                    <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 text-white px-4 py-2 hover:bg-blue-700">
                        <flux:icon.clipboard-document-list variant="mini" /> Create Prescription
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>