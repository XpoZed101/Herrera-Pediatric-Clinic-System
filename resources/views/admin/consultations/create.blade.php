<x-layouts.app :title="__('New Consultation')">
    <div class="px-4 py-6">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-lg font-semibold">New Consultation</h1>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.patients.show', $patient) }}" class="inline-flex items-center justify-center rounded-md p-1.5 hover:bg-neutral-200 dark:hover:bg-neutral-700" title="Back" wire:navigate>
                    <flux:icon.chevron-left variant="mini" />
                </a>
            </div>
        </div>

        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900 p-4">
            <form method="POST" action="{{ route('admin.patients.consultations.store', $patient) }}">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Conducted At</label>
                        <input type="datetime-local" name="conducted_at" class="w-full rounded-md border-neutral-300 dark:border-neutral-700 bg-white dark:bg-zinc-900" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Visit Type</label>
                        <select id="visit_type" name="visit_type" class="w-full rounded-md border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-zinc-900 px-3 py-2" required>
                            <option value="" disabled @selected(!old('visit_type'))>Select a visit type</option>
                            @foreach($visitTypes as $type)
                                <option value="{{ $type->slug }}" @selected(old('visit_type') === $type->slug)>{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mt-4">
                    <label class="block text-sm font-medium mb-1">Chief Complaint</label>
                    <textarea name="chief_complaint" rows="3" class="w-full rounded-md border-neutral-300 dark:border-neutral-700 bg-white dark:bg-zinc-900"></textarea>
                </div>

                <div class="mt-4">
                    <label class="block text-sm font-medium mb-1">Examination Findings</label>
                    <textarea name="examination" rows="4" class="w-full rounded-md border-neutral-300 dark:border-neutral-700 bg-white dark:bg-zinc-900"></textarea>
                </div>

                <div class="mt-4">
                    <label class="block text-sm font-medium mb-1">Diagnosis</label>
                    <textarea name="diagnosis" rows="3" class="w-full rounded-md border-neutral-300 dark:border-neutral-700 bg-white dark:bg-zinc-900"></textarea>
                </div>

                <div class="mt-4">
                    <label class="block text-sm font-medium mb-1">Plan</label>
                    <textarea name="plan" rows="3" class="w-full rounded-md border-neutral-300 dark:border-neutral-700 bg-white dark:bg-zinc-900"></textarea>
                </div>

                

                <div class="mt-4">
                    <label class="block text-sm font-medium mb-1">Additional Notes</label>
                    <textarea name="notes" rows="3" class="w-full rounded-md border-neutral-300 dark:border-neutral-700 bg-white dark:bg-zinc-900"></textarea>
                </div>

                <div class="mt-6">
                    <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 text-white px-4 py-2 hover:bg-blue-700">
                        <flux:icon.check-badge variant="mini" /> Save Consultation
                    </button>
                </div>
            </form>
        </div>

        <div class="mt-6 rounded-2xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900 p-4 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-lg font-semibold tracking-tight">Existing Prescriptions</h2>
                    <p class="text-xs text-neutral-500 mt-0.5">Patient: {{ $patient->child_name }}</p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.prescriptions.index') }}" class="inline-flex items-center gap-2 rounded-lg bg-neutral-100 dark:bg-neutral-800 text-neutral-800 dark:text-neutral-200 px-3 py-1 hover:bg-neutral-200 dark:hover:bg-neutral-700" wire:navigate>
                        <flux:icon.arrow-path variant="mini" /> Refresh
                    </a>
                </div>
            </div>

            @if(isset($prescriptions) && $prescriptions->count())
                <div class="overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                    <table class="min-w-full text-sm">
                        <thead class="bg-neutral-50 dark:bg-neutral-800">
                            <tr class="border-b border-neutral-200 dark:border-neutral-700">
                                <th class="px-4 py-2 text-left">#</th>
                                <th class="px-4 py-2 text-left">Type</th>
                                <th class="px-4 py-2 text-left">Name</th>
                                <th class="px-4 py-2 text-left">Status</th>
                                <th class="px-4 py-2 text-left">Prescriber</th>
                                <th class="px-4 py-2 text-left">Start</th>
                                <th class="px-4 py-2 text-left">End</th>
                                <th class="px-4 py-2 text-left">Record</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                            @foreach($prescriptions as $rx)
                                <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-800/60">
                                    <td class="px-4 py-2 font-medium text-neutral-700 dark:text-neutral-300">{{ $rx->id }}</td>
                                    <td class="px-4 py-2">
                                        <span class="inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-xs
                                            @if(($rx->type ?? '') === 'medication') bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300
                                            @elseif(($rx->type ?? '') === 'therapy') bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-300
                                            @else bg-neutral-100 text-neutral-700 dark:bg-neutral-800 dark:text-neutral-300 @endif">
                                            {{ ucfirst($rx->type ?? '—') }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2">{{ $rx->name ?? '—' }}</td>
                                    <td class="px-4 py-2">
                                        <span class="inline-flex items-center gap-1 rounded-md px-2 py-0.5 text-xs
                                            @if(($rx->status ?? '') === 'active') bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300
                                            @elseif(($rx->status ?? '') === 'completed') bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300
                                            @elseif(($rx->status ?? '') === 'paused') bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300
                                            @else bg-neutral-100 text-neutral-700 dark:bg-neutral-800 dark:text-neutral-300 @endif">
                                            {{ ucfirst($rx->status ?? '—') }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2">{{ optional($rx->prescriber)->name ?? '—' }}</td>
                                    <td class="px-4 py-2">{{ optional($rx->start_date)->format('Y-m-d') ?? '—' }}</td>
                                    <td class="px-4 py-2">{{ optional($rx->end_date)->format('Y-m-d') ?? '—' }}</td>
                                    <td class="px-4 py-2">
                                        @if($rx->medicalRecord)
                                            <a href="{{ route('admin.medical-records.show', $rx->medicalRecord) }}" class="inline-flex items-center gap-1 rounded-lg bg-neutral-100 dark:bg-neutral-800 text-neutral-800 dark:text-neutral-200 px-2 py-1 text-xs hover:bg-neutral-200 dark:hover:bg-neutral-700" wire:navigate>
                                                <flux:icon.eye variant="mini" /> View
                                            </a>
                                        @else
                                            <span class="text-neutral-500">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="rounded-xl border border-dashed border-neutral-300 dark:border-neutral-700 p-6 text-center">
                    <div class="mx-auto h-10 w-10 rounded-full bg-neutral-100 dark:bg-neutral-800 flex items-center justify-center mb-2">
                        <flux:icon.document-text variant="mini" />
                    </div>
                    <p class="text-sm text-neutral-700 dark:text-neutral-300">No prescriptions recorded for this patient yet.</p>
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>