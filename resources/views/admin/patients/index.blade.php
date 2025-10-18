<x-layouts.app :title="__('Patients')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900 p-4">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold">Patients</h2>
                <div class="flex items-center gap-2">
                    <span class="text-sm text-neutral-500">Total: {{ $patients->total() }}</span>
                    <a href="{{ route('admin.patients.index') }}" class="inline-flex items-center justify-center rounded-md p-1.5 hover:bg-neutral-200 dark:hover:bg-neutral-700" title="Refresh" wire:navigate>
                        <flux:icon.arrow-path variant="mini" />
                    </a>
                </div>
            </div>

            <div class="max-h-[70vh] overflow-auto">
                <table class="min-w-full text-sm">
                    <thead class="sticky top-0 bg-neutral-50 dark:bg-zinc-900/60 backdrop-blur supports-[backdrop-filter]:bg-neutral-50/80 dark:supports-[backdrop-filter]:bg-zinc-900/40">
                        <tr>
                            <th class="px-3 py-2 text-left">Patient</th>
                            <th class="px-3 py-2 text-left">DOB</th>
                            <th class="px-3 py-2 text-left">Sex</th>
                            <th class="px-3 py-2 text-left">Guardian</th>
                            <th class="px-3 py-2 text-left">Emergency</th>
                            <th class="px-3 py-2 text-left">Symptoms</th>
                            <th class="px-3 py-2 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($patients as $p)
                            <tr class="border-t border-neutral-200 dark:border-neutral-700 hover:bg-neutral-50 dark:hover:bg-zinc-900/40">
                                <td class="px-3 py-2">
                                    <div class="font-medium">{{ $p->child_name }}</div>
                                </td>
                                <td class="px-3 py-2">{{ \Illuminate\Support\Carbon::parse($p->date_of_birth)->format('M d, Y') }}</td>
                                <td class="px-3 py-2 capitalize">{{ $p->sex }}</td>
                                <td class="px-3 py-2">
                                    @if($p->guardian)
                                        <div>{{ $p->guardian->name }}</div>
                                        <div class="text-neutral-500">{{ $p->guardian->email }}{{ $p->guardian->phone ? ' • '.$p->guardian->phone : '' }}</div>
                                    @else
                                        <span class="text-neutral-400">—</span>
                                    @endif
                                </td>
                                <td class="px-3 py-2">
                                    @if($p->emergencyContact)
                                        <div>{{ $p->emergencyContact->name }}</div>
                                        <div class="text-neutral-500">{{ $p->emergencyContact->phone }}</div>
                                    @else
                                        <span class="text-neutral-400">—</span>
                                    @endif
                                </td>
                                <td class="px-3 py-2">
                                    @php $symps = $p->currentSymptoms->map(fn($s) => str_replace('_',' ', $s->symptom_type))->implode(', '); @endphp
                                    <div class="text-neutral-500">{{ $symps ?: '—' }}</div>
                                </td>
                                <td class="px-3 py-2">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.patients.show', $p) }}" class="inline-flex items-center justify-center rounded-md p-1.5 hover:bg-neutral-200 dark:hover:bg-neutral-700" title="View" wire:navigate>
                                            <flux:icon.eye variant="mini" />
                                        </a>
                                        <form method="POST" action="{{ route('admin.patients.destroy', $p) }}" onsubmit="return confirm('Delete patient and all related records?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center justify-center rounded-md p-1.5 hover:bg-red-100 dark:hover:bg-red-900/40" title="Delete">
                                                <flux:icon.trash variant="mini" class="text-red-600" />
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="px-3 py-6 text-center text-neutral-500" colspan="6">No patients found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="px-3 py-3">
                    {{ $patients->links() }}
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>