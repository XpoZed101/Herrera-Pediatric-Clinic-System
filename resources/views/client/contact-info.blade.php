<x-layouts.app :title="__('Contact Information')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <!-- Header -->
        <div class="rounded-2xl bg-gradient-to-br from-sky-50 to-white dark:from-zinc-800 dark:to-zinc-900 border border-neutral-200 dark:border-neutral-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <div class="inline-flex items-center gap-2 rounded-full bg-sky-100 text-sky-700 dark:bg-sky-900/40 dark:text-sky-300 px-3 py-1 text-xs">
                        <flux:icon.phone variant="mini" />
                        {{ __('Update Contact Information') }}
                    </div>
                    <h1 class="mt-2 text-2xl font-semibold tracking-tight">{{ __('Keep your contact details current') }}</h1>
                    <p class="text-neutral-600 dark:text-neutral-300 text-sm">{{ __('Update guardian and emergency contacts so we can reach you when needed.') }}</p>
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
            <form method="POST" action="{{ route('client.contact-info.update') }}" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @csrf
                @method('PUT')

                <div class="md:col-span-2">
                    <h2 class="text-lg font-semibold">{{ __('Guardian Contact') }}</h2>
                    <p class="text-neutral-600 dark:text-neutral-300 text-sm">{{ __('Primary contact for the child’s care.') }}</p>
                    <flux:separator class="my-4" />
                </div>

                <div>
                    <flux:input name="guardian_name" :label="__('Name')" type="text" value="{{ old('guardian_name', $guardian->name ?? '') }}" />
                    @error('guardian_name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <flux:input name="guardian_phone" :label="__('Phone')" type="tel" inputmode="numeric" value="{{ old('guardian_phone', $guardian->phone ?? '') }}" />
                    @error('guardian_phone')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="md:col-span-2">
                    <flux:input name="guardian_email" :label="__('Email')" type="email" value="{{ old('guardian_email', $guardian->email ?? $user->email ?? '') }}" />
                    @error('guardian_email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    <p class="mt-1 text-xs text-neutral-600 dark:text-neutral-400">{{ __('Changing this will also update your account email and require re‑verification.') }}</p>
                </div>

                <div class="md:col-span-2 mt-4">
                    <h2 class="text-lg font-semibold">{{ __('Emergency Contact') }}</h2>
                    <p class="text-neutral-600 dark:text-neutral-300 text-sm">{{ __('A secondary contact we can reach in urgent situations.') }}</p>
                    <flux:separator class="my-4" />
                </div>

                <div>
                    <flux:input name="emergency_name" :label="__('Name')" type="text" value="{{ old('emergency_name', $emergency->name ?? '') }}" />
                    @error('emergency_name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <flux:input name="emergency_phone" :label="__('Phone')" type="tel" inputmode="numeric" value="{{ old('emergency_phone', $emergency->phone ?? '') }}" />
                    @error('emergency_phone')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="md:col-span-2 flex items-center gap-3 pt-2">
                    <flux:button variant="primary" type="submit" class="inline-flex items-center gap-2">
                        <flux:icon.check variant="mini" /> {{ __('Save Changes') }}
                    </flux:button>
                    <flux:button as="a" href="{{ route('client.home') }}" variant="ghost">
                        {{ __('Cancel') }}
                    </flux:button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>