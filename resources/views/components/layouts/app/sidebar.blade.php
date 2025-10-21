<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:sidebar sticky stashable class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

            <a href="{{ (auth()->check() && (auth()->user()->role ?? null) === 'admin') ? route('admin.dashboard') : ((auth()->check() && (auth()->user()->role ?? null) === 'staff') ? route('staff.welcome') : route('client.home')) }}" class="block w-full text-center" wire:navigate>
                <x-app-logo />
            </a>

            <flux:navlist variant="outline">
                @if(auth()->check() && (auth()->user()->role ?? null) === 'patient')
                <flux:navlist.group :heading="__('Platform')" class="grid">
                        <flux:navlist.item icon="home" :href="route('client.home')" :current="request()->routeIs('client.home')" class="[&_*[data-slot=icon]]:text-sky-600">{{ __('Home') }}</flux:navlist.item>
                        <flux:navlist.item icon="calendar-days" :href="route('client.appointments.create')" :current="request()->routeIs('client.appointments.create')" wire:navigate class="[&_*[data-slot=icon]]:text-emerald-600">{{ __('Appointments') }}</flux:navlist.item>
                        <flux:navlist.item icon="credit-card" :href="route('client.billing.history')" :current="request()->routeIs('client.billing.history')" wire:navigate class="[&_*[data-slot=icon]]:text-violet-600">{{ __('Billing') }}</flux:navlist.item>
                </flux:navlist.group>
                @endif

                @if(auth()->check() && (auth()->user()->role ?? null) === 'staff')
                <flux:navlist.group :heading="__('Platform')" class="grid">
                        <flux:navlist.item icon="layout-grid" :href="route('staff.welcome')" :current="request()->routeIs('staff.welcome')" wire:navigate>{{ __('Dashboard') }}</flux:navlist.item>
                        <flux:navlist.item icon="calendar-days" :href="route('staff.appointments.index')" :current="request()->routeIs('staff.appointments.*')" wire:navigate>{{ __('Appointments') }}</flux:navlist.item>
                        <flux:navlist.item icon="users" :href="route('staff.patients.index')" :current="request()->routeIs('staff.patients.*')" wire:navigate>{{ __('Patients') }}</flux:navlist.item>
                </flux:navlist.group>
                @endif
                @if(auth()->check() && (auth()->user()->role ?? null) === 'admin')
                <flux:navlist.group :heading="__('Platform')" class="grid">
                        <flux:navlist.item icon="layout-grid" :href="route('admin.dashboard')" :current="request()->routeIs('admin.dashboard')" wire:navigate>{{ __('Dashboard') }}</flux:navlist.item>
                        <flux:navlist.item icon="calendar-days" :href="route('admin.appointments.index')" :current="request()->routeIs('admin.appointments.*')" wire:navigate>{{ __('Appointments') }}</flux:navlist.item>
                        <flux:navlist.item icon="users" :href="route('admin.patients.index')" :current="request()->routeIs('admin.patients.index')" wire:navigate>{{ __('Patients') }}</flux:navlist.item>
                        <flux:navlist.item icon="user-group" :href="route('admin.staff.index')" :current="request()->routeIs('admin.staff.*')" wire:navigate>{{ __('Manage Staff') }}</flux:navlist.item>
                        <flux:navlist.item icon="rectangle-stack" :href="route('admin.visit-types.index')" :current="request()->routeIs('admin.visit-types.*')" wire:navigate>{{ __('Visit Types') }}</flux:navlist.item>
                </flux:navlist.group>
                @endif

                @if(auth()->check() && (auth()->user()->role ?? null) === 'patient')
                <flux:navlist.group :heading="__('Child Health')" class="grid mt-2">
                    <flux:navlist.item icon="document-text" :href="route('client.medical-history')" :current="request()->routeIs('client.medical-history')" wire:navigate class="[&_*[data-slot=icon]]:text-rose-600">{{ __('Medical History') }}</flux:navlist.item>
                    <flux:navlist.item icon="phone" :href="route('client.contact-info')" :current="request()->routeIs('client.contact-info')" wire:navigate class="[&_*[data-slot=icon]]:text-indigo-600">{{ __('Contact Info') }}</flux:navlist.item>
                </flux:navlist.group>
                @endif

                @if(auth()->check() && (auth()->user()->role ?? null) === 'admin')
                <flux:navlist.group :heading="__('Records')" class="grid mt-2">
                    <flux:navlist.item icon="document-text" :href="route('admin.medical-records.index')" :current="request()->routeIs('admin.medical-records.*')" wire:navigate>{{ __('Medical Records') }}</flux:navlist.item>
                    <flux:navlist.item icon="clipboard-document-list" :href="route('admin.prescriptions.index')" :current="request()->routeIs('admin.prescriptions.*')" wire:navigate>{{ __('Prescriptions') }}</flux:navlist.item>
                </flux:navlist.group>
                @endif
            </flux:navlist>

            <flux:spacer />

            <flux:navlist variant="outline">
            </flux:navlist>

            <!-- Desktop User Menu -->
            <flux:dropdown class="hidden lg:block" position="bottom" align="start">
                <flux:profile
                    :name="auth()->user()->name"
                    :initials="auth()->user()->initials()"
                    icon:trailing="chevrons-up-down"
                    data-test="sidebar-menu-button"
                />

                <flux:menu class="w-[220px]">
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full" data-test="logout-button">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:sidebar>

        <!-- Mobile User Menu -->
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            <flux:dropdown position="top" align="end">
                <flux:profile
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevron-down"
                />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full" data-test="logout-button">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        {{ $slot }}

        @fluxScripts
    </body>
</html>
