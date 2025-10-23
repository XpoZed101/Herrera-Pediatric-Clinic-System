<x-layouts.app :title="__('Patients')">
    <div id="staff-patients-page" class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900 p-4">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold">{{ __('Patients') }}</h2>
                <div class="flex items-center gap-2">
                    <a href="{{ route('staff.patients.index') }}" class="inline-flex items-center justify-center rounded-md p-1.5 hover:bg-neutral-200 dark:hover:bg-neutral-700" title="Refresh" wire:navigate>
                        <flux:icon.arrow-path variant="mini" />
                    </a>

                </div>
            </div>

            <div class="max-h-[70vh] overflow-auto">
                <table class="min-w-full text-sm">
                    <thead class="sticky top-0 bg-neutral-50 dark:bg-zinc-900/60 backdrop-blur supports-[backdrop-filter]:bg-neutral-50/80 dark:supports-[backdrop-filter]:bg-zinc-900/40">
                        <tr>
                            <th class="px-3 py-2 text-left">{{ __('Child Name') }}</th>
                            <th class="px-3 py-2 text-left">{{ __('Date of Birth') }}</th>
                            <th class="px-3 py-2 text-left">{{ __('Age') }}</th>
                            <th class="px-3 py-2 text-left">{{ __('Sex') }}</th>
                            <th class="px-3 py-2 text-left">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($patients as $patient)
                            <tr class="border-t border-neutral-200 dark:border-neutral-700">
                                <td class="px-3 py-2">{{ $patient->child_name }}</td>
                                <td class="px-3 py-2">{{ \Carbon\Carbon::parse($patient->date_of_birth)->format('Y-m-d') }}</td>
                                <td class="px-3 py-2">{{ $patient->age ?? \Carbon\Carbon::parse($patient->date_of_birth)->age }}</td>
                                <td class="px-3 py-2 capitalize">{{ $patient->sex }}</td>
                                <td class="px-3 py-2">
                                    <div class="inline-flex items-center gap-2">
                                        <a href="{{ route('staff.patients.show', $patient) }}" class="inline-flex items-center gap-2 rounded-lg bg-neutral-900 text-white px-3 py-1 hover:bg-neutral-700" wire:navigate>
                                            <flux:icon.eye variant="mini" /> {{ __('View') }}
                                        </a>
                                        <a href="{{ route('staff.patients.edit', $patient) }}" class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 text-white px-3 py-1 hover:bg-emerald-700" wire:navigate>
                                            <flux:icon.pencil-square variant="mini" /> {{ __('Edit') }}
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-3 py-4 text-center text-neutral-600 dark:text-neutral-300">{{ __('No patients found.') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $patients->links() }}
            </div>
        </div>
    </div>
</x-layouts.app>
