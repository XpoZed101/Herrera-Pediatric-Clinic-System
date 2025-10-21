<x-layouts.app :title="__('Register Patient')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900 p-4">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold">Register Patient</h2>
                <div class="flex items-center gap-2">
                    <a href="{{ route('staff.appointments.index') }}" class="inline-flex items-center gap-2 rounded-lg bg-neutral-100 dark:bg-neutral-800 text-neutral-800 dark:text-neutral-200 px-3 py-1 hover:bg-neutral-200 dark:hover:bg-neutral-700" wire:navigate>
                        <flux:icon.arrow-left variant="mini" /> Back to Appointments
                    </a>
                </div>
            </div>

            <form method="POST" action="{{ route('staff.patients.store') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-medium mb-1" for="child_name">Child Name</label>
                    <input type="text" id="child_name" name="child_name" value="{{ old('child_name') }}" class="w-full rounded-lg border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-zinc-800 px-3 py-2" required />
                    @error('child_name')<div class="text-red-600 text-xs mt-1">{{ $message }}</div>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1" for="date_of_birth">Date of Birth</label>
                    <input type="date" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth') }}" class="w-full rounded-lg border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-zinc-800 px-3 py-2" required />
                    @error('date_of_birth')<div class="text-red-600 text-xs mt-1">{{ $message }}</div>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1" for="age">Age (optional)</label>
                    <input type="number" id="age" name="age" value="{{ old('age') }}" min="0" class="w-full rounded-lg border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-zinc-800 px-3 py-2" />
                    @error('age')<div class="text-red-600 text-xs mt-1">{{ $message }}</div>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1" for="sex">Sex</label>
                    @php($current = old('sex'))
                    <select id="sex" name="sex" class="w-full rounded-lg border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-zinc-800 px-3 py-2" required>
                        <option value="" disabled @selected(!$current)>Select</option>
                        <option value="male" @selected($current==='male')>Male</option>
                        <option value="female" @selected($current==='female')>Female</option>
                    </select>
                    @error('sex')<div class="text-red-600 text-xs mt-1">{{ $message }}</div>@enderror
                </div>

                <div class="flex items-center gap-2">
                    <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-accent text-white px-4 py-2 hover:opacity-90">
                        <flux:icon.user-plus variant="mini" /> Save Patient
                    </button>
                    <a href="{{ route('staff.appointments.index') }}" class="inline-flex items-center gap-2 rounded-lg bg-neutral-100 dark:bg-neutral-800 text-neutral-800 dark:text-neutral-200 px-4 py-2 hover:bg-neutral-200 dark:hover:bg-neutral-700" wire:navigate>
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>