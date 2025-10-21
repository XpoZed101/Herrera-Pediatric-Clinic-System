<x-layouts.app :title="__('Inquiry #') . $inquiry->id">
    <div class="p-6 space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold">{{ __('Inquiry #') }}{{ $inquiry->id }}</h1>
            <a href="{{ route('staff.phone-inquiries.index') }}" class="inline-flex items-center gap-2 rounded-md bg-neutral-100 dark:bg-neutral-800 text-neutral-800 dark:text-neutral-200 px-3 py-2 hover:bg-neutral-200 dark:hover:bg-neutral-700" wire:navigate>
                <flux:icon.list-bullet variant="mini" /> {{ __('Back to list') }}
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-2 rounded-2xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900 p-5 space-y-3">
                <div class="flex items-center gap-3">
                    <div class="font-medium">{{ $inquiry->caller_name }}</div>
                    <div class="text-neutral-600 dark:text-neutral-400">{{ $inquiry->caller_phone }}</div>
                </div>
                <div>
                    <div class="text-sm text-neutral-600 dark:text-neutral-400">{{ __('Reason') }}</div>
                    <div>{{ $inquiry->reason }}</div>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-xs px-2 py-1 rounded {{ $inquiry->triage_badge_color }} capitalize">{{ $inquiry->triage_level }}</span>
                    <span class="text-xs px-2 py-1 rounded bg-neutral-100 dark:bg-neutral-800 text-neutral-800 dark:text-neutral-200 capitalize">{{ $inquiry->status }}</span>
                    @if($inquiry->callback_date)
                        <span class="text-xs text-neutral-600 dark:text-neutral-400">{{ __('Callback:') }} {{ $inquiry->callback_date->format('Y-m-d') }}</span>
                    @endif
                </div>

                @if($inquiry->notes)
                    <div>
                        <div class="text-sm text-neutral-600 dark:text-neutral-400">{{ __('Notes') }}</div>
                        <div class="whitespace-pre-line">{{ $inquiry->notes }}</div>
                    </div>
                @endif
            </div>

            <div class="rounded-2xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900 p-5 space-y-4">
                <div class="font-medium">{{ __('Update Status') }}</div>
                <form method="POST" action="{{ route('staff.phone-inquiries.update-status', $inquiry) }}" class="space-y-3">
                    @csrf
                    <select name="status" class="w-full rounded-md border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-zinc-900 px-3 py-2" required>
                        <option value="open" @selected($inquiry->status==='open')>{{ __('Open') }}</option>
                        <option value="awaiting_callback" @selected($inquiry->status==='awaiting_callback')>{{ __('Awaiting Callback') }}</option>
                        <option value="scheduled" @selected($inquiry->status==='scheduled')>{{ __('Scheduled') }}</option>
                        <option value="escalated" @selected($inquiry->status==='escalated')>{{ __('Escalated') }}</option>
                        <option value="closed" @selected($inquiry->status==='closed')>{{ __('Closed') }}</option>
                    </select>
                    <label class="block text-sm text-neutral-600">{{ __('Notes (optional)') }}</label>
                    <textarea name="notes" rows="3" class="w-full rounded-md border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-zinc-900 px-3 py-2">{{ old('notes', $inquiry->notes) }}</textarea>
                    <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 text-white px-3 py-2 hover:bg-blue-700">
                        <flux:icon.check-badge variant="mini" /> {{ __('Save') }}
                    </button>
                </form>


            </div>
        </div>
    </div>
</x-layouts.app>
