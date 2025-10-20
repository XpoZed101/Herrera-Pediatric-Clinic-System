<x-layouts.app :title="__('New Visit Type')">
    <div class="px-4 py-6 rounded-xl border border-neutral-200 dark:border-neutral-700">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-lg font-semibold">New Visit Type</h1>
            <a href="{{ route('admin.visit-types.index') }}" class="inline-flex items-center justify-center rounded-md p-1.5 hover:bg-neutral-200 dark:hover:bg-neutral-700" title="Back" wire:navigate>
                <flux:icon.chevron-left variant="mini" />
            </a>
        </div>

        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900 p-4">
            <form method="POST" action="{{ route('admin.visit-types.store') }}">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Name</label>
                        <input type="text" name="name" class="w-full rounded-md border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-zinc-900" required />
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Slug (optional)</label>
                        <input type="text" name="slug" class="w-full rounded-md border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-zinc-900" placeholder="e.g., well_visit" />
                        <p class="text-xs text-neutral-500 mt-1">If blank, a slug is auto-generated.</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Amount (PHP)</label>
                        <input type="number" step="0.01" min="0" name="amount" class="w-full rounded-md border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-zinc-900" required />
                    </div>
                    <div class="flex items-center gap-2 mt-6">
                        <input type="checkbox" id="is_active" name="is_active" class="rounded border border-neutral-300 dark:border-neutral-700" checked />
                        <label for="is_active" class="text-sm">Active</label>
                    </div>
                </div>

                <div class="mt-4">
                    <label class="block text-sm font-medium mb-1">Description</label>
                    <textarea name="description" rows="3" class="w-full rounded-md border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-zinc-900"></textarea>
                </div>

                <div class="mt-4 flex items-center gap-2">
                    <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-accent text-white px-4 py-2 hover:opacity-90">
                        <flux:icon.check variant="mini" /> Create Type
                    </button>
                    <a href="{{ route('admin.visit-types.index') }}" class="inline-flex items-center gap-2 rounded-lg bg-neutral-100 dark:bg-neutral-800 text-neutral-800 dark:text-neutral-200 px-4 py-2 hover:bg-neutral-200 dark:hover:bg-neutral-700" wire:navigate>
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
