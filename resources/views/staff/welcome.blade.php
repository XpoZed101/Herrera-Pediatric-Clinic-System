<x-layouts.app :title="__('Welcome Staff')">
    <div class="p-6 space-y-6">
        <div class="relative overflow-hidden rounded-3xl ring-1 ring-inset ring-zinc-200 dark:ring-zinc-700 bg-gradient-to-br from-sky-500/15 via-emerald-500/15 to-violet-500/15">
            <div class="p-8 sm:p-10">
                <h1 class="text-3xl sm:text-4xl font-semibold tracking-tight">{{ __('Welcome, Staff') }}</h1>
                <p class="mt-2 text-neutral-700 dark:text-neutral-300">{{ __('A clean, modern workspace. Your tools will appear here as we enable staff features.') }}</p>
                <div class="mt-6 flex flex-wrap gap-3">
                    <flux:button icon="cog" :href="route('profile.edit')" wire:navigate>{{ __('Account Settings') }}</flux:button>
                    <flux:button variant="outline" icon="book-open-text" href="https://laravel.com/docs/starter-kits#livewire" target="_blank">{{ __('Docs') }}</flux:button>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <div class="rounded-2xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900 p-5">
                <div class="flex items-center gap-3">
                    <flux:icon.layout-grid class="text-emerald-600" />
                    <div>
                        <div class="text-sm text-neutral-500 dark:text-neutral-400">{{ __('Workspace') }}</div>
                        <div class="text-lg font-medium">{{ __('Ready and optimized') }}</div>
                    </div>
                </div>
            </div>
            <div class="rounded-2xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900 p-5">
                <div class="flex items-center gap-3">
                    <flux:icon.book-open-text class="text-sky-600" />
                    <div>
                        <div class="text-sm text-neutral-500 dark:text-neutral-400">{{ __('Documentation') }}</div>
                        <div class="text-lg font-medium">{{ __('Quick tips available') }}</div>
                    </div>
                </div>
            </div>
            <div class="rounded-2xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900 p-5">
                <div class="flex items-center gap-3">
                    <flux:icon.folder-git-2 class="text-violet-600" />
                    <div>
                        <div class="text-sm text-neutral-500 dark:text-neutral-400">{{ __('Repository') }}</div>
                        <div class="text-lg font-medium">{{ __('Up to date') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>