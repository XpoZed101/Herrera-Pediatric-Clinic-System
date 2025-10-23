<x-layouts.app>
    <x-slot:title>
        Inventory Item
    </x-slot:title>

    <div class="max-w-5xl mx-auto px-4 py-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold tracking-tight text-zinc-900 dark:text-zinc-100">{{ $item->name }}</h1>
                <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">Details for this {{ $item->type ?? 'item' }}.</p>
                @if ($item->type)
                    <span class="mt-2 inline-flex items-center rounded-md px-2 py-0.5 text-xs font-medium {{ $item->type === 'vaccine' ? 'bg-sky-100 text-sky-700 dark:bg-sky-900/40 dark:text-sky-200' : 'bg-violet-100 text-violet-700 dark:bg-violet-900/40 dark:text-violet-200' }}">
                        {{ ucfirst($item->type) }}
                    </span>
                @endif
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('staff.inventory.index') }}" class="inline-flex items-center rounded-md border border-zinc-300 px-3 py-1.5 text-xs font-medium text-zinc-700 hover:bg-zinc-50 dark:border-zinc-600 dark:text-zinc-200 dark:hover:bg-zinc-800">Back to Inventory</a>
                <a href="{{ route('staff.inventory.edit', $item) }}" class="inline-flex items-center rounded-md bg-violet-600 px-3 py-1.5 text-xs font-medium text-white shadow-sm hover:bg-violet-700">Edit Item</a>
            </div>
        </div>

        <div class="mt-6 rounded-xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
            <dl class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                    <dt class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Strength</dt>
                    <dd class="mt-1 text-sm text-zinc-900 dark:text-zinc-100">{{ $item->strength ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Form</dt>
                    <dd class="mt-1 text-sm text-zinc-900 dark:text-zinc-100">{{ $item->form ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Unit</dt>
                    <dd class="mt-1 text-sm text-zinc-900 dark:text-zinc-100">{{ $item->unit ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Quantity On Hand</dt>
                    <dd class="mt-1 text-sm text-zinc-900 dark:text-zinc-100">{{ $item->quantity_on_hand }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Reorder Level</dt>
                    <dd class="mt-1 text-sm text-zinc-900 dark:text-zinc-100">{{ $item->reorder_level ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Status</dt>
                    <dd class="mt-1">
                        <span class="inline-flex items-center rounded-md px-2 py-0.5 text-xs font-medium {{ $item->is_active ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-200' : 'bg-zinc-100 text-zinc-700 dark:bg-zinc-800/40 dark:text-zinc-300' }}">
                            {{ $item->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Manufacturer</dt>
                    <dd class="mt-1 text-sm text-zinc-900 dark:text-zinc-100">{{ $item->manufacturer ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Batch Number</dt>
                    <dd class="mt-1 text-sm text-zinc-900 dark:text-zinc-100">{{ $item->batch_number ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Expiry Date</dt>
                    <dd class="mt-1 text-sm text-zinc-900 dark:text-zinc-100">{{ optional($item->expiry_date)->format('M j, Y') ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Requires Cold Chain</dt>
                    <dd class="mt-1 text-sm text-zinc-900 dark:text-zinc-100">{{ $item->requires_cold_chain ? 'Yes' : 'No' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Storage Location</dt>
                    <dd class="mt-1 text-sm text-zinc-900 dark:text-zinc-100">{{ $item->storage_location ?? '—' }}</dd>
                </div>
                <div class="sm:col-span-2">
                    <dt class="text-sm font-medium text-zinc-500 dark:text-zinc-400">Notes</dt>
                    <dd class="mt-1 text-sm text-zinc-900 dark:text-zinc-100">{{ $item->notes ?? '—' }}</dd>
                </div>
            </dl>
        </div>
    </div>
</x-layouts.app>