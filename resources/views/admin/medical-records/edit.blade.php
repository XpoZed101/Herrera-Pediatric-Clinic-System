<x-layouts.app :title="__('Edit Medical Record')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900 p-4">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold">Edit Medical Record for Appointment #{{ $appointment->id }}</h2>
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.appointments.show', $appointment) }}" class="inline-flex items-center gap-2 rounded-lg bg-neutral-100 dark:bg-neutral-800 text-neutral-800 dark:text-neutral-200 px-3 py-1 hover:bg-neutral-200 dark:hover:bg-neutral-700" wire:navigate>
                        <flux:icon.arrow-left variant="mini" /> Back
                    </a>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.medical-records.update', $medicalRecord) }}" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-medium mb-1" for="conducted_at">Conducted At</label>
                    @php($value = optional($medicalRecord->conducted_at)->format('Y-m-d\TH:i'))
                    <input type="datetime-local" id="conducted_at" name="conducted_at" value="{{ old('conducted_at', $value) }}" class="w-full rounded-lg border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-zinc-800 px-3 py-2" />
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1" for="chief_complaint">Chief Complaint</label>
                    <textarea id="chief_complaint" name="chief_complaint" rows="2" class="w-full rounded-lg border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-zinc-800 px-3 py-2">{{ old('chief_complaint', $medicalRecord->chief_complaint) }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1" for="examination">Examination</label>
                    <textarea id="examination" name="examination" rows="3" class="w-full rounded-lg border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-zinc-800 px-3 py-2">{{ old('examination', $medicalRecord->examination) }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1" for="plan">Plan</label>
                    <textarea id="plan" name="plan" rows="3" class="w-full rounded-lg border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-zinc-800 px-3 py-2">{{ old('plan', $medicalRecord->plan) }}</textarea>
                </div>

                

                <div>
                    <label class="block text-sm font-medium mb-1" for="notes">Notes</label>
                    <textarea id="notes" name="notes" rows="3" class="w-full rounded-lg border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-zinc-800 px-3 py-2">{{ old('notes', $medicalRecord->notes) }}</textarea>
                </div>

                @php($existingDiagnosis = $medicalRecord->diagnoses->first())
                <div class="border-t border-neutral-200 dark:border-neutral-700 pt-4">
                    <h3 class="text-md font-semibold mb-2">Diagnosis (optional)</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-1" for="diagnosis_title">Title</label>
                            <input type="text" id="diagnosis_title" name="diagnosis_title" value="{{ old('diagnosis_title', optional($existingDiagnosis)->title) }}" class="w-full rounded-lg border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-zinc-800 px-3 py-2" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1" for="diagnosis_severity">Severity</label>
                            <input type="text" id="diagnosis_severity" name="diagnosis_severity" value="{{ old('diagnosis_severity', optional($existingDiagnosis)->severity) }}" class="w-full rounded-lg border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-zinc-800 px-3 py-2" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1" for="diagnosis_icd_code">ICD Code</label>
                            <input type="text" id="diagnosis_icd_code" name="diagnosis_icd_code" value="{{ old('diagnosis_icd_code', optional($existingDiagnosis)->icd_code) }}" class="w-full rounded-lg border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-zinc-800 px-3 py-2" />
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium mb-1" for="diagnosis_description">Description</label>
                            <textarea id="diagnosis_description" name="diagnosis_description" rows="3" class="w-full rounded-lg border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-zinc-800 px-3 py-2">{{ old('diagnosis_description', optional($existingDiagnosis)->description) }}</textarea>
                        </div>
                    </div>
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