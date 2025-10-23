<x-layouts.app :title="__('Consultations')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900 p-4 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold">Consultations</h2>
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.consultations.index') }}" class="inline-flex items-center justify-center rounded-md p-1.5 hover:bg-neutral-200 dark:hover:bg-neutral-700" title="Refresh" wire:navigate>
                        <flux:icon.arrow-path variant="mini" />
                    </a>
                </div>
            </div>

            <div class="rounded-lg border border-neutral-200 dark:border-neutral-700 p-3 mb-4 shadow-sm">
                <form method="POST" action="{{ route('admin.consultations.start') }}" class="flex items-end gap-2">
                    @csrf
                    <div class="flex-1">
                        <label class="block text-sm font-medium mb-1">Select Patient</label>
                        <select name="patient_id" class="w-full rounded-md border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-zinc-900 px-3 py-2 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                            <option value="" disabled selected>Choose a patient</option>
                            @foreach(($patients ?? []) as $p)
                                <option value="{{ $p->id }}">{{ $p->child_name }} (ID: {{ $p->id }})</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 text-white px-4 py-2 hover:bg-blue-700">
                        <flux:icon.clipboard-document-list variant="mini" /> New Consultation
                    </button>
                </form>
            </div>

            <div class="max-h-[70vh] overflow-auto">
                <table class="min-w-full text-sm">
                    <thead class="sticky top-0 bg-neutral-50 dark:bg-zinc-900/60 backdrop-blur supports-[backdrop-filter]:bg-neutral-50/80 dark:supports-[backdrop-filter]:bg-zinc-900/40">
                        <tr>
                            <th class="px-3 py-2 text-left">Conducted</th>
                            <th class="px-3 py-2 text-left">Patient</th>
                            <th class="px-3 py-2 text-left">Visit Type</th>
                            <th class="px-3 py-2 text-left">Summary</th>
                            <th class="px-3 py-2 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($consultations as $c)
                            <tr class="border-t border-neutral-200 dark:border-neutral-700">
                                <td class="px-3 py-2">{{ optional($c->conducted_at)->format('Y-m-d H:i') ?? '—' }}</td>
                                <td class="px-3 py-2">{{ optional($c->patient)->child_name ?? '—' }}</td>
                                <td class="px-3 py-2">{{ $c->visit_type ?? '—' }}</td>
                                <td class="px-3 py-2">{{ Str::limit($c->diagnosis ?? ($c->chief_complaint ?? ($c->plan ?? ($c->notes ?? '—'))), 120) }}</td>
                                <td class="px-3 py-2">
                                    @if($c->patient)
                                        <a href="{{ route('admin.patients.show', $c->patient) }}" class="inline-flex items-center gap-2 rounded-lg bg-neutral-100 dark:bg-neutral-800 text-neutral-800 dark:text-neutral-200 px-3 py-1 hover:bg-neutral-200 dark:hover:bg-neutral-700" wire:navigate>
                                            <flux:icon.eye variant="mini" /> View Patient
                                        </a>
                                        <a href="{{ route('admin.patients.consultations.create', $c->patient) }}" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 text-white px-3 py-1 hover:bg-blue-700" wire:navigate>
                                            <flux:icon.clipboard-document-list variant="mini" /> Conduct Again
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-3 py-4 text-center text-neutral-600 dark:text-neutral-300">No consultations found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $consultations->links() }}
            </div>
        </div>
    </div>
</x-layouts.app>