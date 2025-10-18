<?php

use App\Models\ClinicPolicy;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Volt\Component;

new class extends Component {
    public ?string $cancellation_policy = null;
    public ?string $privacy_rules = null;
    public ?string $staff_workflows = null;

    public function mount(): void
    {
        if ((Auth::user()->role ?? null) === 'admin') {
            $policy = ClinicPolicy::query()->first();
            if ($policy) {
                $this->cancellation_policy = $policy->cancellation_policy;
                $this->privacy_rules = $policy->privacy_rules;
                $this->staff_workflows = $policy->staff_workflows;
            }
        }
    }

    public function updateClinicPolicies(): void
    {
        abort_unless((Auth::user()->role ?? null) === 'admin', 403);

        $validated = $this->validate([
            'cancellation_policy' => ['nullable', 'string'],
            'privacy_rules' => ['nullable', 'string'],
            'staff_workflows' => ['nullable', 'string'],
        ]);

        $policy = ClinicPolicy::query()->firstOrCreate([]);
        $policy->fill($validated)->save();

        $this->dispatch('clinic-policies-updated');
    }
}; ?>

<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Clinic Rules')" :subheading="__('Define policies for cancellations, privacy, and staff workflows')">
        @if ((auth()->user()->role ?? null) !== 'admin')
            <flux:callout variant="warning" icon="exclamation-triangle" heading="{{ __('Only administrators can manage clinic rules.') }}" />
        @else
            <form wire:submit="updateClinicPolicies" class="my-6 w-full space-y-6">
                <flux:textarea
                    wire:model.defer="cancellation_policy"
                    :label="__('Appointment cancellation or rescheduling policies')"
                    rows="5"
                    placeholder="Define notice periods, rescheduling rules, and fees if any."
                />

                <flux:textarea
                    wire:model.defer="privacy_rules"
                    :label="__('Patient data privacy rules')"
                    rows="5"
                    placeholder="Outline data handling, access controls, and consent procedures."
                />

                <flux:textarea
                    wire:model.defer="staff_workflows"
                    :label="__('Staff workflows and responsibilities')"
                    rows="5"
                    placeholder="Describe standard operating procedures and role responsibilities."
                />

                <div class="flex items-center gap-4">
                    <div class="flex items-center justify-end">
                        <flux:button variant="primary" type="submit" class="w-full">
                            {{ __('Save Rules') }}
                        </flux:button>
                    </div>

                    <x-action-message class="me-3" on="clinic-policies-updated">
                        {{ __('Rules saved.') }}
                    </x-action-message>
                </div>
            </form>
        @endif
    </x-settings.layout>
</section>