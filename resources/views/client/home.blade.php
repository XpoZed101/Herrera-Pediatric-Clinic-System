<x-layouts.app :title="__('Home')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="rounded-2xl bg-gradient-to-br from-sky-50 to-white dark:from-zinc-800 dark:to-zinc-900 border border-neutral-200 dark:border-neutral-700 p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-center">
                <div class="space-y-3">
                    <div class="inline-flex items-center gap-2 rounded-full bg-sky-100 text-sky-700 dark:bg-sky-900/40 dark:text-sky-300 px-3 py-1 text-xs">
                        <flux:icon.heart variant="mini" />
                        {{ __('Welcome to your client portal') }}
                    </div>
                    <h1 class="text-2xl font-semibold tracking-tight">{{ __('Care at your fingertips') }}</h1>
                    <p class="text-neutral-600 dark:text-neutral-300">{{ __('Review records, appointments, and messages in a clean, modern interface with clear actions.') }}</p>
                    <div class="flex flex-wrap gap-2 pt-2">
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 rounded-lg bg-accent text-white px-4 py-2 hover:opacity-90">
                            <flux:icon.layout-grid variant="mini" /> {{ __('Dashboard') }}
                        </a>
                        @if(auth()->check() && (auth()->user()->role ?? null) === 'admin')
                        <a href="{{ route('admin.patients.index') }}" class="inline-flex items-center gap-2 rounded-lg border border-neutral-200 dark:border-neutral-700 px-4 py-2 hover:bg-neutral-100 dark:hover:bg-neutral-800">
                            <flux:icon.users variant="mini" /> {{ __('Patients') }}
                        </a>
                        @endif
                    </div>
                </div>
                <div class="relative">
                    <img src="{{ asset('images/doctor.png') }}" alt="Doctor" class="w-full max-h-64 object-contain drop-shadow-sm" />
                </div>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 p-4 bg-white dark:bg-zinc-900">
                <div class="flex items-center gap-2 mb-2">
                    <flux:icon.calendar-days variant="mini" />
                    <span class="font-medium">Upcoming</span>
                </div>
                <p class="text-neutral-600 dark:text-neutral-300 text-sm">View appointments and reminders at a glance.</p>
            </div>
            <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 p-4 bg-white dark:bg-zinc-900">
                <div class="flex items-center gap-2 mb-2">
                    <flux:icon.document-duplicate variant="mini" />
                    <span class="font-medium">Records</span>
                </div>
                <p class="text-neutral-600 dark:text-neutral-300 text-sm">Access immunizations, allergies, and past conditions.</p>
            </div>
            <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 p-4 bg-white dark:bg-zinc-900">
                <div class="flex items-center gap-2 mb-2">
                    <flux:icon.chat-bubble-left-right variant="mini" />
                    <span class="font-medium">Messages</span>
                </div>
                <p class="text-neutral-600 dark:text-neutral-300 text-sm">Send and receive secure messages with providers.</p>
            </div>
        </div>

        @php($policy = \App\Models\ClinicPolicy::query()->first())
        <div class="rounded-2xl bg-gradient-to-br from-white to-zinc-50 dark:from-zinc-800 dark:to-zinc-900 border border-neutral-200/70 dark:border-white/10 p-8 text-center shadow-sm">
            <div class="inline-flex items-center gap-2 rounded-full bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 px-3 py-1 text-xs mb-2">
                {{ __('Clinic Rules') }}
            </div>
            <div class="flex items-center gap-2 mb-2 justify-center">
                <flux:icon.shield-check variant="mini" />
                <h2 class="text-3xl md:text-4xl font-bold tracking-tight bg-gradient-to-r from-zinc-900 via-zinc-700 to-zinc-900 text-transparent bg-clip-text dark:from-white dark:via-neutral-200 dark:to-white">{{ __('Clinic Rules & Care Policies') }}</h2>
            </div>
            <p class="text-neutral-600 dark:text-neutral-300 text-sm mb-6 max-w-2xl mx-auto">
                {{ __('Clear, modern, and patient‑first — feel prepared every step of the way.') }}
            </p>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="group relative overflow-hidden rounded-2xl bg-white/80 dark:bg-white/5 border border-zinc-200/70 dark:border-white/10 backdrop-blur-sm shadow-sm hover:shadow-lg transition-all text-center p-6">
                    <div class="absolute -top-20 -right-20 w-40 h-40 rounded-full bg-gradient-to-tr from-blue-200/40 to-transparent dark:from-blue-400/10 blur-2xl"></div>
                    <div class="flex items-center gap-3 mb-2 justify-center">
                        <span class="inline-flex size-10 items-center justify-center rounded-xl bg-blue-50 text-blue-600 dark:bg-blue-950/50 dark:text-blue-300 ring-1 ring-blue-200/60 dark:ring-blue-800/30">
                            <flux:icon.calendar-days variant="mini" />
                        </span>
                        <span class="font-medium">{{ __('Appointments & Cancellations') }}</span>
                    </div>
                    <p class="text-neutral-600 dark:text-neutral-300 text-sm whitespace-pre-line leading-relaxed">
                        {{ $policy?->cancellation_policy ?? __('Please cancel or reschedule at least 24 hours in advance so we can offer your slot to another family.') }}
                    </p>
                </div>
                <div class="group relative overflow-hidden rounded-2xl bg-white/80 dark:bg-white/5 border border-zinc-200/70 dark:border-white/10 backdrop-blur-sm shadow-sm hover:shadow-lg transition-all text-center p-6">
                    <div class="absolute -top-20 -right-20 w-40 h-40 rounded-full bg-gradient-to-tr from-emerald-200/40 to-transparent dark:from-emerald-400/10 blur-2xl"></div>
                    <div class="flex items-center gap-3 mb-2 justify-center">
                        <span class="inline-flex size-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 dark:bg-emerald-950/50 dark:text-emerald-300 ring-1 ring-emerald-200/60 dark:ring-emerald-800/30">
                            <flux:icon.shield-check variant="mini" />
                        </span>
                        <span class="font-medium">{{ __('Privacy & Data') }}</span>
                    </div>
                    <p class="text-neutral-600 dark:text-neutral-300 text-sm whitespace-pre-line leading-relaxed">
                        {{ $policy?->privacy_rules ?? __('We protect your child’s information with strict access controls and never share without consent.') }}
                    </p>
                </div>
                <div class="group relative overflow-hidden rounded-2xl bg-white/80 dark:bg-white/5 border border-zinc-200/70 dark:border-white/10 backdrop-blur-sm shadow-sm hover:shadow-lg transition-all text-center p-6">
                    <div class="absolute -top-20 -right-20 w-40 h-40 rounded-full bg-gradient-to-tr from-violet-200/40 to-transparent dark:from-violet-400/10 blur-2xl"></div>
                    <div class="flex items-center gap-3 mb-2 justify-center">
                        <span class="inline-flex size-10 items-center justify-center rounded-xl bg-violet-50 text-violet-600 dark:bg-violet-950/50 dark:text-violet-300 ring-1 ring-violet-200/60 dark:ring-violet-800/30">
                            <flux:icon.clipboard-document-list variant="mini" />
                        </span>
                        <span class="font-medium">{{ __('Staff Workflows') }}</span>
                    </div>
                    <p class="text-neutral-600 dark:text-neutral-300 text-sm whitespace-pre-line leading-relaxed">
                        {{ $policy?->staff_workflows ?? __('Our team follows clear procedures to provide safe, timely, and compassionate care.') }}
                    </p>
                </div>
            </div>
        </div>

        <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
            <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
        </div>
    </div>
</x-layouts.app>