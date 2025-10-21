<x-layouts.app :title="__('Phone Inquiries')">
    <div class="p-6 space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold">{{ __('Phone Inquiries') }}</h1>
                <div class="mt-1 text-sm text-neutral-600 dark:text-neutral-400">
                    {{ __('Open:') }} {{ $counts['open'] ?? 0 }} · {{ __('Urgent:') }} {{ $counts['urgent'] ?? 0 }} · {{ __('Due Today:') }} {{ $counts['dueToday'] ?? 0 }}
                </div>
            </div>
            <a href="{{ route('staff.phone-inquiries.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 text-white px-3 py-2 hover:bg-emerald-700" wire:navigate>
                <flux:icon.phone variant="mini" /> {{ __('Add Inquiry') }}
            </a>
        </div>

        <div class="rounded-2xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900">
            <div class="divide-y divide-neutral-200 dark:divide-neutral-700">
                @forelse($inquiries as $inq)
                    <div class="flex items-center justify-between p-4">
                        <div class="space-y-1">
                            <div class="font-medium">{{ $inq->caller_name }} <span class="text-neutral-500">· {{ $inq->caller_phone }}</span></div>
                            <div class="text-sm text-neutral-600 dark:text-neutral-400">{{ __('Reason:') }} {{ \Illuminate\Support\Str::limit($inq->reason, 120) }}</div>
                            <div class="flex items-center gap-2">
                                <span class="text-xs px-2 py-1 rounded {{ $inq->triage_badge_color }} capitalize">{{ $inq->triage_level }}</span>
                                <span class="text-xs px-2 py-1 rounded bg-neutral-100 dark:bg-neutral-800 text-neutral-800 dark:text-neutral-200 capitalize">{{ $inq->status }}</span>
                                @if($inq->callback_date)
                                    <span class="text-xs text-neutral-600 dark:text-neutral-400">{{ __('Callback:') }} {{ $inq->callback_date->format('Y-m-d') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('staff.phone-inquiries.show', $inq) }}" class="inline-flex items-center gap-2 rounded-md bg-neutral-100 dark:bg-neutral-800 text-neutral-800 dark:text-neutral-200 px-2 py-1 hover:bg-neutral-200 dark:hover:bg-neutral-700" wire:navigate>
                                <flux:icon.eye variant="mini" /> {{ __('View') }}
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="p-4 text-neutral-600 dark:text-neutral-300">{{ __('No inquiries yet.') }}</div>
                @endforelse
            </div>
            <div class="p-4">
                {{ $inquiries->links() }}
            </div>
        </div>
    </div>
</x-layouts.app>