<x-layouts.app :title="__('Prescriptions')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900 p-4">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold">Prescriptions</h2>
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.prescriptions.index') }}" class="inline-flex items-center justify-center rounded-md p-1.5 hover:bg-neutral-200 dark:hover:bg-neutral-700" title="Refresh" wire:navigate>
                        <flux:icon.arrow-path variant="mini" />
                    </a>
                    <a href="{{ route('admin.medical-records.index') }}" class="inline-flex items-center gap-2 rounded-lg bg-neutral-100 dark:bg-neutral-800 text-neutral-800 dark:text-neutral-200 px-3 py-1 hover:bg-neutral-200 dark:hover:bg-neutral-700" title="View All Medical Records" wire:navigate>
                        <flux:icon.document-text variant="mini" /> View All Medical Records
                    </a>
                    <a href="{{ route('admin.prescriptions.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 text-white px-3 py-1 hover:bg-blue-700" wire:navigate>
                        <flux:icon.clipboard-document-list variant="mini" /> Create
                    </a>
                </div>
            </div>

            <div class="max-h-[70vh] overflow-auto">
                <table class="min-w-full text-sm">
                    <thead class="sticky top-0 bg-neutral-50 dark:bg-zinc-900/60 backdrop-blur supports-[backdrop-filter]:bg-neutral-50/80 dark:supports-[backdrop-filter]:bg-zinc-900/40">
                        <tr>
                            <th class="px-3 py-2 text-left">Name</th>
                            <th class="px-3 py-2 text-left">Type</th>
                            <th class="px-3 py-2 text-left">Prescriber</th>
                            <th class="px-3 py-2 text-left">User</th>
                            <th class="px-3 py-2 text-left">Record</th>
                            <th class="px-3 py-2 text-left">Status</th>
                            <th class="px-3 py-2 text-left">eRx</th>
                            <th class="px-3 py-2 text-left">Dates</th>
                            <th class="px-3 py-2 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($prescriptions as $p)
                            <tr class="border-t border-neutral-200 dark:border-neutral-700">
                                <td class="px-3 py-2">
                                    <div class="flex items-center gap-2">
                                        <span class="inline-flex items-center justify-center rounded-md px-2 py-0.5 text-xs {{ $p->type === 'medication' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-200' : 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-200' }}">
                                            {{ ucfirst($p->type) }}
                                        </span>
                                        <span class="font-medium">{{ $p->name }}</span>
                                    </div>
                                    @if($p->type === 'medication')
                                        <div class="text-xs text-neutral-600 dark:text-neutral-300">{{ $p->dosage }} • {{ $p->frequency }} • {{ $p->route }}</div>
                                    @elseif($p->type === 'treatment')
                                        <div class="text-xs text-neutral-600 dark:text-neutral-300">{{ $p->treatment_schedule }}</div>
                                    @endif
                                </td>
                                <td class="px-3 py-2 capitalize">{{ $p->type }}</td>
                                <td class="px-3 py-2">{{ optional($p->prescriber)->name ?? '—' }}</td>
                                <td class="px-3 py-2">{{ optional(optional($p->medicalRecord->appointment)->user)->name ?? '—' }}</td>
                                <td class="px-3 py-2">#{{ optional($p->medicalRecord)->id }}</td>
                                <td class="px-3 py-2">
                                    @php $status = $p->status ?? 'active'; @endphp
                                    <span class="inline-flex rounded-md px-2 py-0.5 text-xs
                                        {{ $status === 'active' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-200' : '' }}
                                        {{ $status === 'completed' ? 'bg-neutral-100 text-neutral-700 dark:bg-neutral-900/30 dark:text-neutral-200' : '' }}
                                        {{ $status === 'discontinued' ? 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-200' : '' }}">
                                        {{ ucfirst($status) }}
                                    </span>
                                </td>
                                <td class="px-3 py-2 text-xs">
                                    <div>Start: {{ optional($p->start_date)->format('Y-m-d') ?? '—' }}</div>
                                    <div>End: {{ optional($p->end_date)->format('Y-m-d') ?? '—' }}</div>
                                </td>
                                <td class="px-3 py-2">
                                    @if($p->erx_enabled)
                                        @php $erxStatus = $p->erx_status ?? 'pending'; @endphp
                                        <div class="flex flex-col gap-1">
                                            <span class="inline-flex rounded-md px-2 py-0.5 text-xs
                                                {{ $erxStatus === 'submitted' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-200' : '' }}
                                                {{ $erxStatus === 'failed' ? 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-200' : '' }}
                                                {{ $erxStatus === 'pending' ? 'bg-neutral-100 text-neutral-700 dark:bg-neutral-900/30 dark:text-neutral-200' : '' }}">
                                                eRx: {{ ucfirst($erxStatus) }}
                                            </span>
                                            <div class="text-xs text-neutral-600 dark:text-neutral-300">
                                                @if($p->erx_external_id)
                                                    ID: {{ $p->erx_external_id }}
                                                @endif
                                                @if($p->erx_submitted_at)
                                                    <span class="ml-2">{{ $p->erx_submitted_at->format('Y-m-d H:i') }}</span>
                                                @endif
                                            </div>
                                            @if($p->erx_status === 'failed' && $p->erx_error)
                                                <div class="text-xs text-red-600 dark:text-red-300">{{ $p->erx_error }}</div>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-neutral-500 text-sm">—</span>
                                    @endif
                                </td>
                                <td class="px-3 py-2">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.prescriptions.edit', $p) }}" class="inline-flex items-center justify-center rounded-md p-1.5 hover:bg-neutral-200 dark:hover:bg-neutral-700" title="Edit" wire:navigate>
                                            <flux:icon.pencil-square variant="mini" />
                                        </a>

                                        <a href="{{ route('admin.prescriptions.pdf', [$p, 'save' => 1]) }}" class="inline-flex items-center justify-center rounded-md p-1.5 hover:bg-neutral-200 dark:hover:bg-neutral-700" title="Save PDF" target="_blank">
                                            <flux:icon.arrow-down-tray variant="mini" />
                                        </a>
                                        <form action="{{ route('admin.prescriptions.destroy', $p) }}" method="POST" onsubmit="return confirm('Delete this prescription?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center justify-center rounded-md p-1.5 hover:bg-red-100 dark:hover:bg-red-900/30" title="Delete">
                                                <flux:icon.trash variant="mini" />
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-3 py-4 text-center text-neutral-600 dark:text-neutral-300">No prescriptions found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $prescriptions->links() }}
            </div>
        </div>
    </div>
</x-layouts.app>