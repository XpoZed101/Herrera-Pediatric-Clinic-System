<?php

use App\Models\Guardian;
use App\Models\Patient;
use App\Models\EmergencyContact;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component {
    public ?string $guardian_name = null;
    public ?string $guardian_phone = null;
    public ?string $guardian_email = null;
    public ?string $emergency_name = null;
    public ?string $emergency_phone = null;

    public function mount(): void
    {
        // Only show for non-admin (patients). Admins get a warning below.
        $user = Auth::user();

        $guardian = null;
        $patient = null;

        if ($user) {
            $guardian = Guardian::where('email', $user->email)->first();
            $patient = $guardian?->patient;
        }

        $patient = $patient ?? Patient::query()->first();
        $emergency = $patient?->emergencyContact;

        $this->guardian_name = $guardian->name ?? null;
        $this->guardian_phone = $guardian->phone ?? null;
        $this->guardian_email = $guardian->email ?? ($user->email ?? null);
        $this->emergency_name = $emergency->name ?? null;
        $this->emergency_phone = $emergency->phone ?? null;
    }
}; ?>

<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Contact Info')" :subheading=" __('Manage guardian and emergency contacts for the patient')">
        @if ((auth()->user()->role ?? null) === 'admin')
            <flux:callout variant="warning" icon="exclamation-triangle" heading="{{ __('This page is only for patient accounts.') }}" />
        @else
            @if (session('status'))
                <div class="rounded-xl border border-emerald-200 dark:border-emerald-700 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 p-3 mb-4">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('client.contact-info.update') }}" class="my-2 w-full space-y-6">
                @csrf
                @method('PUT')
                <input type="hidden" name="redirect" value="settings" />

                <flux:heading size="lg">{{ __('Guardian Contact') }}</flux:heading>
                <flux:text variant="subtle">{{ __('Primary contact for the child’s care.') }}</flux:text>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <flux:input name="guardian_name" :label="__('Name')" type="text" value="{{ old('guardian_name', $guardian_name) }}" />
                    <flux:input name="guardian_phone" :label="__('Phone')" type="tel" inputmode="numeric" value="{{ old('guardian_phone', $guardian_phone) }}" />
                    @error('guardian_phone')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    <div class="md:col-span-2">
                        <flux:input name="guardian_email" :label="__('Email')" type="email" value="{{ old('guardian_email', $guardian_email) }}" />
                        <flux:text class="mt-1 text-xs" variant="subtle">{{ __('Changing this updates your account email and requires re‑verification.') }}</flux:text>
                    </div>
                </div>

                <flux:separator class="my-4" />

                <flux:heading size="lg">{{ __('Emergency Contact') }}</flux:heading>
                <flux:text variant="subtle">{{ __('Secondary contact for urgent situations.') }}</flux:text>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <flux:input name="emergency_name" :label="__('Name')" type="text" value="{{ old('emergency_name', $emergency_name) }}" />
                    <flux:input name="emergency_phone" :label="__('Phone')" type="tel" inputmode="numeric" value="{{ old('emergency_phone', $emergency_phone) }}" />
                    @error('emergency_phone')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="flex items-center gap-4">
                    <div class="flex items-center justify-end">
                        <flux:button variant="primary" type="submit" class="w-full">
                            {{ __('Save') }}
                        </flux:button>
                    </div>
                </div>
            </form>
        @endif
    </x-settings.layout>
 </section>