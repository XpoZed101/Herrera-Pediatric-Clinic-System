<x-layouts.app :title="__('Add Phone Inquiry')">
    <div class="p-6 space-y-6">
        <h1 class="text-2xl font-semibold">{{ __('Add Phone Inquiry') }}</h1>

        <form method="POST" action="{{ route('staff.phone-inquiries.store') }}" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm text-neutral-600">{{ __('Caller name') }}</label>
                    <input type="text" name="caller_name" class="mt-1 w-full rounded-md border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-zinc-900 px-3 py-2" required />
                </div>
                <div>
                    <label class="block text-sm text-neutral-600">{{ __('Caller phone') }}</label>
                    <input type="tel" name="caller_phone" pattern="[0-9]{11}" maxlength="11" inputmode="numeric" placeholder="09123456789" title="Enter exactly 11 digits" class="mt-1 w-full rounded-md border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-zinc-900 px-3 py-2" />
                </div>
            </div>

            <div>
                <label class="block text-sm text-neutral-600">{{ __('Reason for calling') }}</label>
                <textarea name="reason" rows="4" class="mt-1 w-full rounded-md border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-zinc-900 px-3 py-2" required></textarea>
            </div>

            <div>
                <label class="block text-sm text-neutral-600 mb-1">{{ __('Triage level') }}</label>
                <div class="flex items-center gap-4">
                    <label class="inline-flex items-center gap-2">
                        <input type="radio" name="triage_level" value="emergency" required>
                        <span class="text-red-700">{{ __('Emergency') }}</span>
                    </label>
                    <label class="inline-flex items-center gap-2">
                        <input type="radio" name="triage_level" value="urgent" required>
                        <span class="text-amber-700">{{ __('Urgent') }}</span>
                    </label>
                    <label class="inline-flex items-center gap-2">
                        <input type="radio" name="triage_level" value="routine" required>
                        <span class="text-green-700">{{ __('Routine') }}</span>
                    </label>
                </div>
            </div>

            <div>
                <label class="block text-sm text-neutral-600 mb-1">{{ __('Action') }}</label>
                <select name="action" id="action" class="mt-1 w-full max-w-sm rounded-md border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-zinc-900 px-3 py-2" required>
                    <option value="advice">{{ __('Give advice') }}</option>
                    <option value="callback">{{ __('Callback later') }}</option>
                    <option value="schedule">{{ __('Schedule visit') }}</option>
                    <option value="escalate">{{ __('Escalate to clinician') }}</option>
                </select>
            </div>

            <div id="callbackDateWrap" class="hidden">
                <label class="block text-sm text-neutral-600">{{ __('Callback date') }}</label>
                <input type="date" name="callback_date" class="mt-1 w-full max-w-sm rounded-md border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-zinc-900 px-3 py-2" />
                <p class="mt-1 text-xs text-neutral-500">{{ __('We’ll remind staff on this date.') }}</p>
            </div>

            <div>
                <label class="block text-sm text-neutral-600">{{ __('Notes (optional)') }}</label>
                <textarea name="notes" rows="3" class="mt-1 w-full rounded-md border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-zinc-900 px-3 py-2"></textarea>
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 text-white px-4 py-2 hover:bg-emerald-700">
                    <flux:icon.check-badge variant="mini" /> {{ __('Save Inquiry') }}
                </button>
                <a href="{{ route('staff.phone-inquiries.index') }}" class="inline-flex items-center gap-2 rounded-lg bg-neutral-100 dark:bg-neutral-800 text-neutral-800 dark:text-neutral-200 px-4 py-2 hover:bg-neutral-200 dark:hover:bg-neutral-700" wire:navigate>
                    {{ __('Cancel') }}
                </a>
            </div>
        </form>

        <script>
            const actionSel = document.getElementById('action');
            const callbackWrap = document.getElementById('callbackDateWrap');
            function toggleCallback() { callbackWrap.classList.toggle('hidden', actionSel.value !== 'callback'); }
            actionSel.addEventListener('change', toggleCallback);
            toggleCallback();
        </script>
    </div>
</x-layouts.app>
