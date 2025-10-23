<x-layouts.app :title="__('Message Clinic')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <!-- Header -->
        <div class="rounded-2xl bg-gradient-to-br from-violet-50 to-white dark:from-zinc-800 dark:to-zinc-900 border border-neutral-200 dark:border-neutral-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <div class="inline-flex items-center gap-2 rounded-full bg-violet-100 text-violet-700 dark:bg-violet-900/40 dark:text-violet-300 px-3 py-1 text-xs">
                        <flux:icon.chat-bubble-left-right variant="mini" />
                        {{ __('Non‑urgent clinic message') }}
                    </div>
                    <h1 class="mt-2 text-2xl font-semibold tracking-tight">{{ __('Message the clinic') }}</h1>
                    <p class="text-neutral-600 dark:text-neutral-300 text-sm">{{ __('Ask quick questions about schedules, prescriptions, or general care — not for emergencies.') }}</p>
                </div>
                <a href="{{ route('client.home') }}" class="inline-flex items-center gap-2 rounded-lg border border-neutral-200 dark:border-neutral-700 px-3 py-1.5 hover:bg-neutral-100 dark:hover:bg-neutral-800" wire:navigate>
                    <flux:icon.arrow-left variant="mini" /> {{ __('Back') }}
                </a>
            </div>
        </div>

        @if (session('status'))
            <div class="rounded-xl border border-emerald-200 dark:border-emerald-700 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 p-3">
                {{ session('status') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <!-- Form -->
            <div class="lg:col-span-2 rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900 p-6 shadow-sm">
                <h2 class="text-lg font-semibold">{{ __('Start a conversation') }}</h2>
                <p class="text-neutral-600 dark:text-neutral-300 text-sm">{{ __('We’ll reply to your email within 1–2 business days.') }}</p>
                <flux:separator class="my-4" />
                <form method="POST" action="{{ route('client.messages.send') }}" class="grid grid-cols-1 gap-6">
                    @csrf

                    <flux:input name="subject" :label="__('Subject (optional)')" type="text" value="{{ old('subject') }}" placeholder="{{ __('e.g., Vaccine schedule question') }}" />
                    @error('subject')<p class="-mt-4 text-sm text-red-600">{{ $message }}</p>@enderror

                    <flux:textarea name="message" :label="__('Your message')" rows="6" placeholder="{{ __('Write your non‑urgent question...') }}">{{ old('message') }}</flux:textarea>
                    @error('message')<p class="-mt-4 text-sm text-red-600">{{ $message }}</p>@enderror

                    <div class="flex items-center gap-3">
                        <flux:button variant="primary" type="submit" class="inline-flex items-center gap-2">
                            <flux:icon.paper-airplane variant="mini" /> {{ __('Send Message') }}
                        </flux:button>
                        <p class="text-xs text-neutral-600 dark:text-neutral-400">{{ __('Not for emergencies — call your local emergency number if urgent.') }}</p>
                    </div>
                </form>
            </div>

            <!-- Info Panel -->
            <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900 p-6 shadow-sm">
                <div class="space-y-4">
                    <div class="flex items-center gap-2">
                        <span class="inline-flex size-10 items-center justify-center rounded-xl bg-violet-50 text-violet-600 dark:bg-violet-950/50 dark:text-violet-300 ring-1 ring-violet-200/60 dark:ring-violet-800/30">
                            <flux:icon.bolt variant="mini" />
                        </span>
                        <div>
                            <p class="font-medium">{{ __('Response Time') }}</p>
                            <p class="text-sm text-neutral-600 dark:text-neutral-300">{{ __('1–2 business days via your account email.') }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="inline-flex size-10 items-center justify-center rounded-xl bg-rose-50 text-rose-600 dark:bg-rose-950/50 dark:text-rose-300 ring-1 ring-rose-200/60 dark:ring-rose-800/30">
                            <flux:icon.shield-exclamation variant="mini" />
                        </span>
                        <div>
                            <p class="font-medium">{{ __('Emergency Note') }}</p>
                            <p class="text-sm text-neutral-600 dark:text-neutral-300">{{ __('If urgent, call your local emergency number or visit the nearest ER.') }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="inline-flex size-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 dark:bg-emerald-950/50 dark:text-emerald-300 ring-1 ring-emerald-200/60 dark:ring-emerald-800/30">
                            <flux:icon.phone variant="mini" />
                        </span>
                        <div>
                            <p class="font-medium">{{ __('Alternate Contact') }}</p>
                            <p class="text-sm text-neutral-600 dark:text-neutral-300">{{ __('Reach our front desk during clinic hours for quick assistance.') }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="inline-flex size-10 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 dark:bg-indigo-950/50 dark:text-indigo-300 ring-1 ring-indigo-200/60 dark:ring-indigo-800/30">
                            <flux:icon.lock-closed variant="mini" />
                        </span>
                        <div>
                            <p class="font-medium">{{ __('Your Privacy') }}</p>
                            <p class="text-sm text-neutral-600 dark:text-neutral-300">{{ __('We store only what’s needed to handle your query responsibly.') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>