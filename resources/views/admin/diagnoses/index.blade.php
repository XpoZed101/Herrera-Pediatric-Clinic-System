<x-layouts.app :title="__('Diagnoses')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900 p-4">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold">Diagnoses</h2>
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.diagnoses.index') }}" class="inline-flex items-center justify-center rounded-md p-1.5 hover:bg-neutral-200 dark:hover:bg-neutral-700" title="Refresh" wire:navigate>
                        <flux:icon.arrow-path variant="mini" />
                    </a>
                </div>
            </div>

            <div class="max-h-[70vh] overflow-auto">
                <table class="min-w-full text-sm">
                    <thead class="sticky top-0 bg-neutral-50 dark:bg-zinc-900/60 backdrop-blur supports-[backdrop-filter]:bg-neutral-50/80 dark:supports-[backdrop-filter]:bg-zinc-900/40">
                        <tr>
                            <th class="px-3 py-2 text-left">Title</th>
                            <th class="px-3 py-2 text-left">Severity</th>
                            <th class="px-3 py-2 text-left">ICD Code</th>
                            <th class="px-3 py-2 text-left">User</th>
                            <th class="px-3 py-2 text-left">Record</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($diagnoses as $dx)
                            <tr class="border-t border-neutral-200 dark:border-neutral-700">
                                <td class="px-3 py-2">{{ $dx->title ?? '—' }}</td>
                                <td class="px-3 py-2">{{ $dx->severity ?? '—' }}</td>
                                <td class="px-3 py-2">{{ $dx->icd_code ?? '—' }}</td>
                                <td class="px-3 py-2">
                                    @php($user = optional($dx->medicalRecord)->user)
                                    <div class="text-neutral-900 dark:text-neutral-100">{{ optional($user)->name ?? '—' }}</div>
                                    @if($user)
                                        <div class="text-xs text-neutral-600 dark:text-neutral-300">
                                            ID: {{ $user->id ?? '—' }} • Email: {{ $user->email ?? '—' }} • Phone: {{ $user->phone ?? '—' }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-3 py-2">
                                    @if($dx->medicalRecord)
                                        <a href="{{ route('admin.medical-records.edit', $dx->medicalRecord) }}" class="inline-flex items-center gap-2 rounded-lg bg-neutral-100 dark:bg-neutral-800 text-neutral-800 dark:text-neutral-200 px-3 py-1 hover:bg-neutral-200 dark:hover:bg-neutral-700" wire:navigate>
                                            <flux:icon.document-text variant="mini" /> View Record
                                        </a>
                                    @else
                                        <span class="text-neutral-500">—</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-3 py-4 text-center text-neutral-600 dark:text-neutral-300">No diagnoses found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $diagnoses->links() }}
            </div>
        </div>
    </div>
</x-layouts.app>