<x-layouts.app :title="__('Welcome Staff')">
    <div class="p-6 space-y-6">
        <div class="relative overflow-hidden rounded-3xl ring-1 ring-inset ring-zinc-200 dark:ring-zinc-700 bg-gradient-to-br from-sky-500/15 via-emerald-500/15 to-violet-500/15">
            <div class="p-8 sm:p-10">
                <h1 class="text-3xl sm:text-4xl font-semibold tracking-tight">{{ __('Welcome, Staff') }}</h1>
                <p class="mt-2 text-neutral-700 dark:text-neutral-300">{{ __('Your workspace is ready. Use the quick links below.') }}</p>
                <div class="mt-6 flex flex-wrap gap-3">
                    <flux:button icon="calendar-days" :href="route('staff.appointments.index')" wire:navigate>{{ __('View Appointments') }}</flux:button>
                    <flux:button icon="user-plus" :href="route('staff.patients.create')" wire:navigate>{{ __('Register Patient') }}</flux:button>
                    <flux:button variant="outline" icon="cog" :href="route('profile.edit')" wire:navigate>{{ __('Account Settings') }}</flux:button>
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

        <div class="rounded-2xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900 p-5">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-lg font-semibold">{{ __('Upcoming Appointments') }}</h3>
                <a href="{{ route('staff.appointments.index') }}" class="inline-flex items-center gap-2 rounded-lg bg-neutral-100 dark:bg-neutral-800 text-neutral-800 dark:text-neutral-200 px-3 py-1 hover:bg-neutral-200 dark:hover:bg-neutral-700" wire:navigate>
                    <flux:icon.calendar-days variant="mini" /> {{ __('View All') }}
                </a>
            </div>
            <div class="divide-y divide-neutral-200 dark:divide-neutral-700">
                @forelse($upcoming ?? [] as $appt)
                    <div class="py-2 flex items-center justify-between">
                        <div>
                            <div class="font-medium">#{{ $appt->id }} — {{ optional($appt->user)->name ?? 'Unknown User' }}</div>
                            <div class="text-sm text-neutral-600 dark:text-neutral-400">{{ optional($appt->scheduled_at)->format('Y-m-d H:i') ?? '—' }} — {{ $appt->visit_type ?? '—' }}</div>
                        </div>
                        <a href="{{ route('staff.appointments.show', $appt) }}" class="inline-flex items-center gap-2 rounded-md bg-neutral-100 dark:bg-neutral-800 text-neutral-800 dark:text-neutral-200 px-2 py-1 hover:bg-neutral-200 dark:hover:bg-neutral-700" wire:navigate>
                            <flux:icon.eye variant="mini" /> {{ __('View') }}
                        </a>
                    </div>
                @empty
                    <div class="py-4 text-neutral-600 dark:text-neutral-300">{{ __('No upcoming appointments.') }}</div>
                @endforelse
            </div>
        </div>
    </div>
</x-layouts.app>