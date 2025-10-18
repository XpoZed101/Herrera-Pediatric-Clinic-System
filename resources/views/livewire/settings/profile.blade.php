<?php

use App\Models\User;
use App\Models\ClinicPolicy;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;

new class extends Component {
    public string $name = '';
    public string $email = '';
    // Clinic policies (admin-only)
    public ?string $cancellation_policy = null;
    public ?string $privacy_rules = null;
    public ?string $staff_workflows = null;

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;

        // Load clinic policies if admin
        if ((Auth::user()->role ?? null) === 'admin') {
            $policy = ClinicPolicy::query()->first();
            if ($policy) {
                $this->cancellation_policy = $policy->cancellation_policy;
                $this->privacy_rules = $policy->privacy_rules;
                $this->staff_workflows = $policy->staff_workflows;
            }
        }
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],

            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($user->id)
            ],
        ]);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->dispatch('profile-updated', name: $user->name);
    }

    /**
     * Update clinic policies (admin-only)
     */
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

    /**
     * Send an email verification notification to the current user.
     */
    public function resendVerificationNotification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }
}; ?>

<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Profile')" :subheading="__('Update your name and email address')">
        <form wire:submit="updateProfileInformation" class="my-6 w-full space-y-6">
            <flux:input wire:model="name" :label="__('Name')" type="text" required autofocus autocomplete="name" />

            <div>
                <flux:input wire:model="email" :label="__('Email')" type="email" required autocomplete="email" />

                @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail &&! auth()->user()->hasVerifiedEmail())
                    <div>
                        <flux:text class="mt-4">
                            {{ __('Your email address is unverified.') }}

                            <flux:link class="text-sm cursor-pointer" wire:click.prevent="resendVerificationNotification">
                                {{ __('Click here to re-send the verification email.') }}
                            </flux:link>
                        </flux:text>

                        @if (session('status') === 'verification-link-sent')
                            <flux:text class="mt-2 font-medium !dark:text-green-400 !text-green-600">
                                {{ __('A new verification link has been sent to your email address.') }}
                            </flux:text>
                        @endif
                    </div>
                @endif
            </div>

            <div class="flex items-center gap-4">
                <div class="flex items-center justify-end">
                    <flux:button variant="primary" type="submit" class="w-full" data-test="update-profile-button">
                        {{ __('Save') }}
                    </flux:button>
                </div>

                <x-action-message class="me-3" on="profile-updated">
                    {{ __('Saved.') }}
                </x-action-message>
            </div>
        </form>

        @if ((auth()->user()->role ?? null) === 'admin')
            <flux:separator class="my-8" />

            <x-settings.layout :heading="__('Clinic Policies')" :subheading="__('Establish or update clinic operating rules')">
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
                                {{ __('Save Policies') }}
                            </flux:button>
                        </div>

                        <x-action-message class="me-3" on="clinic-policies-updated">
                            {{ __('Policies saved.') }}
                        </x-action-message>
                    </div>
                </form>
            </x-settings.layout>
        @endif

        <livewire:settings.delete-user-form />
    </x-settings.layout>
</section>
