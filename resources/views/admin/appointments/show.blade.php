<x-layouts.app :title="__('Appointment #'.$appointment->id)">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900 p-4">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold">Appointment #{{ $appointment->id }}</h2>
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.appointments.index') }}" class="inline-flex items-center gap-2 rounded-lg bg-neutral-100 dark:bg-neutral-800 text-neutral-800 dark:text-neutral-200 px-3 py-1 hover:bg-neutral-200 dark:hover:bg-neutral-700" wire:navigate>
                        <flux:icon.arrow-left variant="mini" /> Back
                    </a>
                    <a href="{{ route('admin.appointments.edit', $appointment) }}" class="inline-flex items-center gap-2 rounded-lg bg-neutral-100 dark:bg-neutral-800 text-neutral-800 dark:text-neutral-200 px-3 py-1 hover:bg-neutral-200 dark:hover:bg-neutral-700" wire:navigate>
                        <flux:icon.pencil-square variant="mini" /> Edit
                    </a>
                </div>
            </div>

            @php
                $status = $appointment->status ?? 'requested';
                $classes = [
                    'requested' => 'bg-yellow-50 text-yellow-800 border-yellow-200 dark:bg-yellow-900/20 dark:text-yellow-200 dark:border-yellow-800',
                    'scheduled' => 'bg-blue-50 text-blue-800 border-blue-200 dark:bg-blue-900/20 dark:text-blue-200 dark:border-blue-800',
                    'completed' => 'bg-green-50 text-green-800 border-green-200 dark:bg-green-900/20 dark:text-green-200 dark:border-green-800',
                    'cancelled' => 'bg-red-50 text-red-800 border-red-200 dark:bg-red-900/20 dark:text-red-200 dark:border-red-800',
                ][$status] ?? 'bg-neutral-50 text-neutral-800 border-neutral-200 dark:bg-neutral-800 dark:text-neutral-200 dark:border-neutral-700';
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-2">
                    <div><span class="text-neutral-500 text-sm">Scheduled</span><div class="font-medium">{{ optional($appointment->scheduled_at)->format('Y-m-d H:i') ?? '—' }}</div></div>
                    <div><span class="text-neutral-500 text-sm">Visit Type</span><div class="font-medium">{{ $appointment->visit_type ?? '—' }}</div></div>
                    <div><span class="text-neutral-500 text-sm">Reason</span><div class="font-medium">{{ $appointment->reason ?? '—' }}</div></div>
                    <div><span class="text-neutral-500 text-sm">Notes</span><div class="font-medium whitespace-pre-wrap">{{ $appointment->notes ?? '—' }}</div></div>
                </div>
                <div class="space-y-2">
                    <div><span class="text-neutral-500 text-sm">Requester</span>
                        <div class="font-medium">{{ optional($appointment->user)->name ?? '—' }}
                            @if(optional($appointment->user)->email)
                                <span class="text-neutral-500 text-xs"> — {{ $appointment->user->email }}</span>
                            @endif
                        </div>
                    </div>
                    <div><span class="text-neutral-500 text-sm">Patient</span>
                        <div class="font-medium">{{ optional($appointment->patient)->child_name ?? 'Unknown Patient' }}</div>
                    </div>
                    <div>
                        <span class="text-neutral-500 text-sm">Status</span>
                        <div>
                            <span class="inline-flex items-center rounded-md px-2 py-0.5 text-xs border {{ $classes }} capitalize">{{ $status }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4 flex items-center gap-3">
                @if($appointment->patient)
                    <a href="{{ route('admin.patients.consultations.create', $appointment->patient) }}" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 text-white px-3 py-1 hover:bg-blue-700" wire:navigate>
                        <flux:icon.clipboard-document-list variant="mini" /> Conduct
                    </a>
                @endif

                <form method="POST" action="{{ route('admin.appointments.update-status', $appointment) }}" class="inline-flex items-center gap-2">
                    @csrf
                    <select name="status" class="rounded-md border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-zinc-800 px-2 py-1 text-sm">
                        @foreach(['requested','scheduled','completed','cancelled'] as $opt)
                            <option value="{{ $opt }}" @selected(($appointment->status ?? 'requested') === $opt)>{{ ucfirst($opt) }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="inline-flex items-center gap-1 rounded-lg bg-accent text-white px-3 py-1 hover:opacity-90">
                        <flux:icon.check variant="mini" /> Update Status
                    </button>
                </form>

                @if($appointment->medicalRecord)
                    <a href="{{ route('admin.medical-records.edit', $appointment->medicalRecord) }}" class="inline-flex items-center gap-2 rounded-lg bg-green-600 text-white px-3 py-1 hover:bg-green-700" wire:navigate>
                        <flux:icon.document-text variant="mini" /> Edit Medical Record
                    </a>
                @else
                    <a href="{{ route('admin.appointments.medical-record.create', $appointment) }}" class="inline-flex items-center gap-2 rounded-lg bg-neutral-100 dark:bg-neutral-800 text-neutral-800 dark:text-neutral-200 px-3 py-1 hover:bg-neutral-200 dark:hover:bg-neutral-700" wire:navigate>
                        <flux:icon.document-plus variant="mini" /> Create Medical Record
                    </a>
                @endif
            </div>
        </div>
    </div>
</x-layouts.app>