<x-layouts.app>
    <x-slot:title>
        Inventory
    </x-slot:title>

    <div class="max-w-7xl mx-auto px-4 py-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold tracking-tight text-zinc-900 dark:text-zinc-100">Inventory</h1>
                <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">Manage medicines and vaccines stock levels and details.</p>
            </div>
            <a href="{{ route('staff.inventory.create') }}" class="inline-flex items-center rounded-md bg-violet-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-violet-700 focus:outline-none focus:ring-2 focus:ring-violet-500">Add Item</a>
        </div>

        <div class="mt-6 overflow-x-auto rounded-xl border border-zinc-200 bg-white shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
            <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-700">
                <thead class="bg-zinc-50 dark:bg-zinc-800">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-zinc-600 dark:text-zinc-300">Item</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-zinc-600 dark:text-zinc-300">Strength / Form</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-zinc-600 dark:text-zinc-300">Unit</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-zinc-600 dark:text-zinc-300">On Hand</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-zinc-600 dark:text-zinc-300">Reorder</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-zinc-600 dark:text-zinc-300">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-zinc-600 dark:text-zinc-300">Expiry</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-zinc-600 dark:text-zinc-300">Storage</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-zinc-600 dark:text-zinc-300">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                    @forelse ($items as $item)
                        @php
                            $low = isset($item->reorder_level) && $item->quantity_on_hand <= $item->reorder_level;
                        @endphp
                        <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/50">
                            <td class="px-6 py-3">
                                <div class="flex items-center gap-2">
                                    <span class="text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ $item->name }}</span>
                                    @if ($item->type)
                                        <span class="inline-flex items-center rounded-md px-2 py-0.5 text-xs font-medium {{ $item->type === 'vaccine' ? 'bg-sky-100 text-sky-700 dark:bg-sky-900/40 dark:text-sky-200' : 'bg-violet-100 text-violet-700 dark:bg-violet-900/40 dark:text-violet-200' }}">
                                            {{ ucfirst($item->type) }}
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-3 text-sm text-zinc-700 dark:text-zinc-200">{{ $item->strength }} {{ $item->form }}</td>
                            <td class="px-6 py-3 text-sm text-zinc-700 dark:text-zinc-200">{{ $item->unit }}</td>
                            <td class="px-6 py-3">
                                <div class="flex items-center gap-2">
                                    <span class="text-sm font-medium text-zinc-900 dark:text-zinc-100">{{ $item->quantity_on_hand }}</span>
                                    @if ($low)
                                        <span class="inline-flex items-center rounded-md bg-rose-100 px-2 py-0.5 text-xs font-medium text-rose-700 dark:bg-rose-900/40 dark:text-rose-200">Low</span>
                                    @else
                                        <span class="inline-flex items-center rounded-md bg-emerald-100 px-2 py-0.5 text-xs font-medium text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-200">OK</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-3 text-sm text-zinc-700 dark:text-zinc-200">{{ $item->reorder_level ?? '—' }}</td>
                            <td class="px-6 py-3">
                                <span class="inline-flex items-center rounded-md px-2 py-0.5 text-xs font-medium {{ $item->is_active ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-200' : 'bg-zinc-100 text-zinc-700 dark:bg-zinc-800/40 dark:text-zinc-300' }}">
                                    {{ $item->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-6 py-3 text-sm text-zinc-700 dark:text-zinc-200">{{ optional($item->expiry_date)->format('M j, Y') ?? '—' }}</td>
                            <td class="px-6 py-3 text-sm text-zinc-700 dark:text-zinc-200">{{ $item->storage_location ?? '—' }}</td>
                            <td class="px-6 py-3 text-right">
                                <div class="inline-flex items-center gap-2">
                                    <a href="{{ route('staff.inventory.edit', $item) }}" class="inline-flex items-center rounded-md border border-zinc-300 px-3 py-1.5 text-xs font-medium text-zinc-700 hover:bg-zinc-50 dark:border-zinc-600 dark:text-zinc-200 dark:hover:bg-zinc-800">Edit</a>
                                    <a href="{{ route('staff.inventory.show', $item) }}" class="inline-flex items-center rounded-md bg-sky-600 px-3 py-1.5 text-xs font-medium text-white shadow-sm hover:bg-sky-700">View</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center text-sm text-zinc-500">
                                No inventory items found.
                                <a href="{{ route('staff.inventory.create') }}" class="ml-2 font-medium text-violet-600 hover:text-violet-700">Add your first item</a>.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.app>
