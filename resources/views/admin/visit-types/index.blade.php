<x-layouts.app :title="__('Visit Types')">
    <div class="px-4 py-6">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-lg font-semibold">Visit Types</h1>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.visit-types.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-accent text-white px-3 py-1.5 hover:opacity-90" wire:navigate>
                    <flux:icon.plus variant="mini" /> New Type
                </a>
            </div>
        </div>

        @if(session('status'))
            <div class="rounded-xl border border-green-200 bg-green-50 dark:bg-green-900/20 dark:border-green-800 p-3 mb-4 text-sm">
                {{ session('status') }}
            </div>
        @endif

        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900 p-4">
            <table class="min-w-full text-sm">
                <thead>
                    <tr>
                        <th class="px-3 py-2 text-left">Name</th>
                        <th class="px-3 py-2 text-left">Slug</th>
                        <th class="px-3 py-2 text-left">Amount</th>
                        <th class="px-3 py-2 text-left">Active</th>
                        <th class="px-3 py-2 text-left">Description</th>
                        <th class="px-3 py-2 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($types as $type)
                        <tr class="border-t border-neutral-200 dark:border-neutral-800">
                            <td class="px-3 py-2">{{ $type->name }}</td>
                            <td class="px-3 py-2">{{ $type->slug }}</td>
                            <td class="px-3 py-2">₱{{ number_format(($type->amount_cents ?? 0) / 100, 2) }}</td>
                            <td class="px-3 py-2">
                                <span class="inline-flex items-center gap-1 rounded-md px-2 py-0.5 text-xs {{ $type->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-200' : 'bg-neutral-100 text-neutral-700 dark:bg-neutral-800 dark:text-neutral-200' }}">
                                    {{ $type->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-3 py-2 text-neutral-600 dark:text-neutral-300">{{ Str::limit($type->description, 60) }}</td>
                            <td class="px-3 py-2">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.visit-types.edit', $type) }}" class="inline-flex items-center gap-1 rounded-lg bg-neutral-100 dark:bg-neutral-800 text-neutral-800 dark:text-neutral-200 px-3 py-1 hover:bg-neutral-200 dark:hover:bg-neutral-700" wire:navigate>
                                        <flux:icon.pencil-square variant="mini" /> Edit
                                    </a>
                                    <form method="POST" action="{{ route('admin.visit-types.destroy', $type) }}" onsubmit="return confirm('Delete this visit type?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center gap-1 rounded-lg bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-200 px-3 py-1 hover:bg-red-200 dark:hover:bg-red-800">
                                            <flux:icon.trash variant="mini" /> Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-4">
                {{ $types->links() }}
            </div>
        </div>
    </div>
</x-layouts.app>