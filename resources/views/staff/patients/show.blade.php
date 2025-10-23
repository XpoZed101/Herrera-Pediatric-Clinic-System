<x-layouts.app :title="__('Patient Details')">
    <div id="staff-patient-show-page" class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900 p-4">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold">{{ __('Patient') }} #{{ $patient->id }}</h2>
                <div class="flex items-center gap-2">
                    <a href="{{ route('staff.patients.index') }}" class="inline-flex items-center gap-2 rounded-lg bg-neutral-200 dark:bg-neutral-800 text-neutral-900 dark:text-neutral-100 px-3 py-1 hover:bg-neutral-300 dark:hover:bg-neutral-700" wire:navigate>
                        <flux:icon.arrow-left variant="mini" /> {{ __('Back to Patients') }}
                    </a>
                    <a href="{{ route('staff.patients.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 text-white px-3 py-1 hover:bg-blue-700" wire:navigate>
                        <flux:icon.user-plus variant="mini" /> {{ __('Register Patient') }}
                    </a>
                    <a href="{{ route('staff.patients.edit', $patient) }}" class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 text-white px-3 py-1 hover:bg-emerald-700" wire:navigate>
                        <flux:icon.pencil-square variant="mini" /> {{ __('Edit Demographics') }}
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="rounded-lg border border-neutral-200 dark:border-neutral-700 p-4">
                    <h3 class="text-base font-semibold mb-3">{{ __('Child Information') }}</h3>
                    <dl class="grid grid-cols-1 gap-2 text-sm">
                        <div class="flex items-center justify-between">
                            <dt class="text-neutral-600 dark:text-neutral-300">{{ __('Name') }}</dt>
                            <dd class="font-medium">{{ $patient->child_name }}</dd>
                        </div>
                        <div class="flex items-center justify-between">
                            <dt class="text-neutral-600 dark:text-neutral-300">{{ __('Date of Birth') }}</dt>
                            <dd class="font-medium">{{ \Carbon\Carbon::parse($patient->date_of_birth)->format('Y-m-d') }}</dd>
                        </div>
                        <div class="flex items-center justify-between">
                            <dt class="text-neutral-600 dark:text-neutral-300">{{ __('Age') }}</dt>
                            <dd class="font-medium">{{ $patient->age ?? \Carbon\Carbon::parse($patient->date_of_birth)->age }}</dd>
                        </div>
                        <div class="flex items-center justify-between">
                            <dt class="text-neutral-600 dark:text-neutral-300">{{ __('Sex') }}</dt>
                            <dd class="font-medium capitalize">{{ $patient->sex }}</dd>
                        </div>
                    </dl>
                </div>

                <div class="rounded-lg border border-neutral-200 dark:border-neutral-700 p-4">
                    <h3 class="text-base font-semibold mb-3">{{ __('Meta') }}</h3>
                    <dl class="grid grid-cols-1 gap-2 text-sm">
                        <div class="flex items-center justify-between">
                            <dt class="text-neutral-600 dark:text-neutral-300">{{ __('Created') }}</dt>
                            <dd class="font-medium">{{ $patient->created_at?->format('Y-m-d H:i') }}</dd>
                        </div>
                        <div class="flex items-center justify-between">
                            <dt class="text-neutral-600 dark:text-neutral-300">{{ __('Last Updated') }}</dt>
                            <dd class="font-medium">{{ $patient->updated_at?->format('Y-m-d H:i') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>