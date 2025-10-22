<x-layouts.app :title="__('Medical Records Request')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <!-- Header / Hero -->
        <div class="rounded-2xl bg-gradient-to-br from-sky-50 to-white dark:from-zinc-800 dark:to-zinc-900 border border-neutral-200 dark:border-neutral-700 p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <div class="inline-flex items-center gap-2 rounded-full bg-sky-100 text-sky-700 dark:bg-sky-900/40 dark:text-sky-300 px-3 py-1 text-xs">
                        <flux:icon.document-text variant="mini" />
                        {{ __('Request Your Child’s Medical Records') }}
                    </div>
                    <h1 class="mt-2 text-2xl font-semibold tracking-tight">{{ __('Simple, secure access to records') }}</h1>
                    <p class="text-neutral-600 dark:text-neutral-300 text-sm">{{ __('Tell us what you need. We’ll prepare the records and send them to you securely.') }}</p>
                </div>
                <a href="{{ route('client.home') }}" class="inline-flex items-center gap-2 rounded-lg border border-neutral-200 dark:border-neutral-700 px-3 py-1.5 hover:bg-neutral-100 dark:hover:bg-neutral-800" wire:navigate>
                    <flux:icon.arrow-left variant="mini" /> {{ __('Back') }}
                </a>
            </div>
        </div>

        <!-- Status messages -->
        @if (session('status'))
            <div class="rounded-xl border border-emerald-200 dark:border-emerald-700 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 p-3">
                {{ session('status') }}
            </div>
        @endif

        <!-- Form Card -->
        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900 p-6">
            <form method="POST" action="{{ route('client.records-request.submit') }}" class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                @csrf

                <!-- Left: form inputs -->
                <div class="lg:col-span-8">
                    <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900 p-4">
                        <h2 class="text-lg font-semibold mb-1">{{ __('1. What do you need?') }}</h2>
                        <p class="text-neutral-600 dark:text-neutral-300 text-sm mb-3">{{ __('Choose one record type.') }}</p>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                            @php($types = [
                                'history' => __('Complete Medical History'),
                                'vaccinations' => __('Vaccination Records'),
                                'prescriptions' => __('Prescriptions'),
                                'diagnoses' => __('Diagnoses Summary'),
                                'visit_summaries' => __('Visit Summaries'),
                                'lab_results' => __('Lab Results'),
                            ])
                            @foreach($types as $key => $label)
                                <label class="inline-flex items-center gap-2 text-sm">
                                    <input type="radio" name="record_type" value="{{ $key }}" class="rounded border-neutral-300 dark:border-neutral-700" @checked(old('record_type') === $key) @if($loop->first) required @endif>
                                    {{ $label }}
                                </label>
                            @endforeach
                        </div>
                        @error('record_type')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>


                    <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900 p-4 mt-4">
                        <h2 class="text-lg font-semibold mb-1">{{ __('Delivery Method') }}</h2>
                        <p class="text-neutral-600 dark:text-neutral-300 text-sm mb-3">{{ __('How should we send your records?') }}</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium mb-1" for="delivery">{{ __('Choose one') }}</label>
                                <select id="delivery" name="delivery" class="w-full rounded-lg border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-zinc-800 px-3 py-2" required>
                                    <option value="" disabled selected>{{ __('Select delivery method') }}</option>
                                    <option value="download" @selected(old('delivery')==='download')>{{ __('Secure PDF download') }}</option>
                                    <option value="email" @selected(old('delivery')==='email')>{{ __('Email to me') }}</option>
                                    <option value="pickup" @selected(old('delivery')==='pickup')>{{ __('Pick up at clinic') }}</option>
                                </select>
                                @error('delivery')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <flux:input name="email" :label="__('Email')" type="email" value="{{ old('email', $guardian->email ?? $user->email ?? '') }}" />
                                @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                                <p class="mt-1 text-xs text-neutral-600 dark:text-neutral-400">{{ __('Required if delivery method is Email.') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900 p-4 mt-4">
                        <h2 class="text-lg font-semibold mb-1">{{ __('Purpose') }}</h2>
                        <p class="text-neutral-600 dark:text-neutral-300 text-sm mb-3">{{ __('This helps us prepare the right documents.') }}</p>
                        <select name="purpose" class="w-full rounded-lg border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-zinc-800 px-3 py-2">
                            <option value="" disabled selected>{{ __('Select purpose') }}</option>
                            <option value="school" @selected(old('purpose')==='school')>{{ __('School requirement') }}</option>
                            <option value="insurance" @selected(old('purpose')==='insurance')>{{ __('Insurance claim') }}</option>
                            <option value="referral" @selected(old('purpose')==='referral')>{{ __('Referral to another provider') }}</option>
                            <option value="personal" @selected(old('purpose')==='personal')>{{ __('Personal record') }}</option>
                        </select>
                        @error('purpose')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900 p-4 mt-4">
                        <h2 class="text-lg font-semibold mb-2">{{ __('Notes (optional)') }}</h2>
                        <flux:textarea name="notes" :label="__('Details or special instructions')" rows="4">{{ old('notes') }}</flux:textarea>
                        @error('notes')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>

                <!-- Right: summary & submit -->
                <aside class="lg:col-span-4 space-y-4">
                    <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900 p-4">
                        <h3 class="font-medium mb-2">{{ __('Request Summary') }}</h3>
                        <p class="text-sm text-neutral-600 dark:text-neutral-300">{{ __('We’ll confirm availability and next steps by email or phone.') }}</p>
                        <flux:separator class="my-3" />
                        <div class="text-xs text-neutral-600 dark:text-neutral-400">
                            <div>{{ __('Child') }}: <strong>{{ $patient?->child_name ?? '—' }}</strong></div>
                            <div>{{ __('Guardian') }}: <strong>{{ $guardian?->name ?? $user?->name ?? '—' }}</strong></div>
                            <div>{{ __('Contact') }}: <strong>{{ $guardian?->email ?? $user?->email ?? '—' }}</strong></div>
                        </div>
                    </div>
                    <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900 p-4">
                        <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-lg bg-accent text-white px-4 py-2 hover:opacity-90">
                            <flux:icon.document-text variant="mini" /> {{ __('Submit Request') }}
                        </button>
                        <p class="mt-2 text-xs text-neutral-600 dark:text-neutral-400">{{ __('By submitting, you agree to our secure records handling policy.') }}</p>
                    </div>
                </aside>
            </form>
        </div>
    </div>
</x-layouts.app>
