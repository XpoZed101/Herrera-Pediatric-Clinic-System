<x-layouts.app :title="__('Add Staff Account')">
    <div class="flex h-full w-full flex-1 flex-col gap-6">
        <!-- Header -->
        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900 shadow-sm">
            <div class="flex items-center justify-between px-4 py-3">
                <div>
                    <h2 class="text-xl font-semibold tracking-tight">{{ __('Add Staff Account') }}</h2>
                    <p class="mt-1 text-sm text-neutral-600 dark:text-neutral-300">{{ __('Create a new staff user with access to the clinic platform.') }}</p>
                </div>
                <a href="{{ route('admin.staff.index') }}" class="inline-flex items-center gap-2 rounded-lg border border-neutral-200 dark:border-neutral-700 bg-neutral-50 dark:bg-zinc-800 text-neutral-800 dark:text-neutral-200 px-3 py-1.5 hover:bg-neutral-100 dark:hover:bg-zinc-700" wire:navigate>
                    <flux:icon.arrow-left variant="mini" /> {{ __('Back') }}
                </a>
            </div>
        </div>

        <!-- Form -->
        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900 shadow-sm">
            <div class="p-4">
                <form method="POST" action="{{ route('admin.staff.store') }}" class="grid gap-4">
                    @csrf

                    <div class="grid gap-1">
                        <label class="text-sm font-medium">{{ __('Name') }}</label>
                        <input type="text" name="name" class="rounded-lg border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-zinc-800 px-3 py-2" required />
                    </div>

                    <div class="grid gap-1">
                        <label class="text-sm font-medium">{{ __('Email') }}</label>
                        <input type="email" name="email" class="rounded-lg border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-zinc-800 px-3 py-2" required />
                    </div>

                    <div class="grid gap-1">
                        <label class="text-sm font-medium">{{ __('Password') }}</label>
                        <input type="password" name="password" class="rounded-lg border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-zinc-800 px-3 py-2" placeholder="{{ __('Leave blank to auto-generate') }}" />
                        <p class="text-xs text-neutral-500 mt-1">{{ __('If omitted, a secure password will be auto-generated.') }}</p>
                    </div>

                    <div class="flex items-center justify-end gap-3">
                        <a href="{{ route('admin.staff.index') }}" class="inline-flex items-center gap-2 rounded-lg border border-neutral-200 dark:border-neutral-700 bg-neutral-50 dark:bg-zinc-800 text-neutral-800 dark:text-neutral-200 px-3 py-1.5 hover:bg-neutral-100 dark:hover:bg-zinc-700" wire:navigate>
                            {{ __('Cancel') }}
                        </a>
                        <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 text-white px-3 py-1.5 hover:bg-blue-700">
                            <flux:icon.check-circle variant="mini" /> {{ __('Create') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>