<x-layouts.app :title="__('Edit Patient Demographics')">
    <div class="p-6 space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold tracking-tight">{{ __('Edit Demographics') }}</h1>
                <p class="text-neutral-600 dark:text-neutral-300 text-sm">{{ __('Update child name, birth date, age, and sex.') }}</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('staff.patients.show', $patient) }}" class="inline-flex items-center gap-2 rounded-lg bg-neutral-200 dark:bg-neutral-800 text-neutral-900 dark:text-neutral-100 px-3 py-1 hover:bg-neutral-300 dark:hover:bg-neutral-700" wire:navigate>
                    <flux:icon.arrow-left variant="mini" /> {{ __('Back to Details') }}
                </a>
            </div>
        </div>

        @if(session('status'))
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 dark:border-emerald-700 dark:bg-emerald-900/20 text-emerald-700 dark:text-emerald-200 p-4">
                {{ session('status') }}
            </div>
        @endif

        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900 p-5">
            <form method="POST" action="{{ route('staff.patients.update', $patient) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">{{ __('Child Name') }}</label>
                        <input type="text" name="child_name" value="{{ old('child_name', $patient->child_name) }}" class="mt-1 w-full rounded-md border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-zinc-900 px-3 py-2" required />
                        @error('child_name')
                            <div class="text-rose-600 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">{{ __('Date of Birth') }}</label>
                        <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $patient->date_of_birth) }}" class="mt-1 w-full rounded-md border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-zinc-900 px-3 py-2" required />
                        @error('date_of_birth')
                            <div class="text-rose-600 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">{{ __('Age') }}</label>
                        <input type="number" name="age" value="{{ old('age', $patient->age) }}" min="0" max="150" class="mt-1 w-full rounded-md border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-zinc-900 px-3 py-2" />
                        @error('age')
                            <div class="text-rose-600 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300">{{ __('Sex') }}</label>
                        @php($currentSex = old('sex', $patient->sex))
                        <select name="sex" class="mt-1 w-full rounded-md border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-zinc-900 px-3 py-2" required>
                            <option value="male" {{ $currentSex === 'male' ? 'selected' : '' }}>{{ __('Male') }}</option>
                            <option value="female" {{ $currentSex === 'female' ? 'selected' : '' }}>{{ __('Female') }}</option>
                        </select>
                        @error('sex')
                            <div class="text-rose-600 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2">
                    <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 text-white px-4 py-2 hover:bg-emerald-700">
                        <flux:icon.check variant="mini" /> {{ __('Save Changes') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>