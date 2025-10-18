<x-layouts.app :title="__('Medical Records')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900 p-4">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold">Medical Records</h2>
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.medical-records.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-accent text-white px-3 py-2 hover:opacity-90" wire:navigate>
                        <flux:icon.document-duplicate variant="mini" /> Create Record
                    </a>
                    <a href="{{ route('admin.medical-records.index') }}" class="inline-flex items-center justify-center rounded-md p-1.5 hover:bg-neutral-200 dark:hover:bg-neutral-700" title="Refresh" wire:navigate>
                        <flux:icon.arrow-path variant="mini" />
                    </a>
                </div>
            </div>

            <div class="max-h-[70vh] overflow-auto">
                <table class="min-w-full text-sm">
                    <thead class="sticky top-0 bg-neutral-50 dark:bg-zinc-900/60 backdrop-blur supports-[backdrop-filter]:bg-neutral-50/80 dark:supports-[backdrop-filter]:bg-zinc-900/40">
                        <tr>
                            <th class="px-3 py-2 text-left">Conducted</th>
                            <th class="px-3 py-2 text-left">Prescriptions</th>
                            <th class="px-3 py-2 text-left">User</th>
                            <th class="px-3 py-2 text-left">Appointment</th>
                            <th class="px-3 py-2 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($records as $record)
                            <tr class="border-t border-neutral-200 dark:border-neutral-700">
                                <td class="px-3 py-2">{{ optional($record->conducted_at)->format('Y-m-d H:i') ?? '—' }}</td>
                                <td class="px-3 py-2">
                                    <a href="{{ route('admin.prescriptions.index') }}" class="inline-flex items-center gap-1 rounded-md bg-blue-100 text-blue-700 px-2 py-0.5 text-xs hover:bg-blue-200 dark:bg-blue-900/30 dark:text-blue-200">
                                        <flux:icon.clipboard-document-list variant="mini" /> {{ $record->prescriptions_count ?? 0 }}
                                    </a>
                                </td>
                                <td class="px-3 py-2">
                                    @if($record->user)
                                        <div class="text-neutral-900 dark:text-neutral-100">
                                            {{ $record->user->name ?? '—' }}
                                        </div>
                                        <div class="text-xs text-neutral-600 dark:text-neutral-300">
                                            ID: {{ $record->user->id ?? '—' }} • Email: {{ $record->user->email ?? '—' }} • Phone: {{ $record->user->phone ?? '—' }}
                                        </div>
                                    @else
                                        <span class="text-neutral-500">Unknown</span>
                                    @endif
                                </td>
                                <td class="px-3 py-2">
                                    @if($record->appointment)
                                        <div class="text-neutral-900 dark:text-neutral-100">#{{ $record->appointment->id }}</div>
                                    @else
                                        <span class="text-neutral-500">—</span>
                                    @endif
                                </td>
                                <td class="px-3 py-2">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.medical-records.show', $record) }}" class="inline-flex items-center gap-2 rounded-lg bg-neutral-100 dark:bg-neutral-800 text-neutral-800 dark:text-neutral-200 px-3 py-1 hover:bg-neutral-200 dark:hover:bg-neutral-700" title="View Record" wire:navigate>
                                            <flux:icon.document-text variant="mini" /> View
                                        </a>
                                        <a href="{{ route('admin.medical-records.edit', $record) }}" class="inline-flex items-center gap-2 rounded-lg bg-green-600 text-white px-3 py-1 hover:bg-green-700" wire:navigate>
                                            <flux:icon.document-text variant="mini" /> Edit
                                        </a>
                                        <a href="{{ route('admin.prescriptions.create', ['medical_record_id' => $record->id]) }}" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 text-white px-3 py-1 hover:bg-blue-700" title="Create Prescription" wire:navigate>
                                            <flux:icon.document-plus variant="mini" /> Add Prescription
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-3 py-4 text-center text-neutral-600 dark:text-neutral-300">No medical records found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $records->links() }}
            </div>
        </div>
    </div>
</x-layouts.app>