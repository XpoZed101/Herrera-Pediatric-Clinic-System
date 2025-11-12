@extends('layouts.site')

@section('content')
    <!-- Colorful Hero -->
    <section class="relative isolate overflow-hidden">
        <div class="absolute inset-0 -z-10">
            <div class="absolute -top-32 -left-40 h-80 w-80 rounded-full bg-gradient-to-br from-fuchsia-500 via-sky-500 to-emerald-400 blur-3xl opacity-30"></div>
            <div class="absolute -bottom-24 -right-32 h-96 w-96 rounded-full bg-gradient-to-tr from-orange-500 via-pink-500 to-violet-500 blur-3xl opacity-30"></div>
        </div>

        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-24">
            <div class="grid items-center gap-12 lg:grid-cols-2">
                <div>
                    <span class="inline-flex items-center gap-2 rounded-full bg-white/70 px-3 py-1 text-sm text-zinc-700 shadow-sm ring-1 ring-white/60 dark:bg-zinc-800/60 dark:text-zinc-200 dark:ring-zinc-700">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5M21 12A9 9 0 1 1 3 12a9 9 0 0 1 18 0Z" />
                        </svg>
                        Pediatric care made simple
                    </span>

                    <h1 class="mt-4 text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-zinc-900 dark:text-white">
                        Modern, Colorful, Kid‑Friendly Care
                    </h1>
                    <p class="mt-6 text-lg text-zinc-700 dark:text-zinc-300">
                        Compassionate, evidence‑based pediatrics for newborns through teens. Easy scheduling, a calm environment, and tools that help families feel prepared and supported.
                    </p>
                    <div class="mt-8 flex flex-wrap gap-4">
                        <a href="{{ route('features') }}" class="rounded-xl bg-gradient-to-r from-sky-600 to-blue-600 px-6 py-3 text-white shadow-md hover:from-sky-700 hover:to-blue-700">Explore Features</a>
                        <a href="{{ route('about') }}" class="rounded-xl bg-white/80 px-6 py-3 text-zinc-900 shadow-sm ring-1 ring-zinc-200 hover:bg-white dark:bg-zinc-800/70 dark:text-white dark:ring-zinc-700">Learn More</a>
                    </div>
                </div>

                <div class="relative">
                    <div class="aspect-[4/3] w-full overflow-hidden rounded-3xl">
                        <img src="{{ asset('images/doctor2.png') }}" alt="Smiling child at clinic" class="h-full w-full object-cover" loading="lazy" />
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Highlights -->
    <section class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-16">
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-2xl bg-gradient-to-br from-sky-50 to-indigo-50 p-6 shadow-sm dark:from-zinc-800 dark:to-zinc-900">
                <h3 class="text-lg font-semibold text-sky-900 dark:text-white">Online Scheduling</h3>
                <p class="mt-2 text-sm text-sky-900/70 dark:text-zinc-300">Book visits anytime with reminders and follow‑ups.</p>
            </div>
            <div class="rounded-2xl bg-gradient-to-br from-pink-50 to-fuchsia-50 p-6 shadow-sm dark:from-zinc-800 dark:to-zinc-900">
                <h3 class="text-lg font-semibold text-fuchsia-900 dark:text-white">Kid‑Friendly Spaces</h3>
                <p class="mt-2 text-sm text-fuchsia-900/70 dark:text-zinc-300">Playful decor and sensory‑friendly rooms.</p>
            </div>
            <div class="rounded-2xl bg-gradient-to-br from-emerald-50 to-teal-50 p-6 shadow-sm dark:from-zinc-800 dark:to-zinc-900">
                <h3 class="text-lg font-semibold text-emerald-900 dark:text-white">Vaccines & Wellness</h3>
                <p class="mt-2 text-sm text-emerald-900/70 dark:text-zinc-300">Routine checkups, growth tracking, and guidance.</p>
            </div>
            <div class="rounded-2xl bg-gradient-to-br from-orange-50 to-rose-50 p-6 shadow-sm dark:from-zinc-800 dark:to-zinc-900">
                <h3 class="text-lg font-semibold text-rose-900 dark:text-white">Same‑Day Sick Visits</h3>
                <p class="mt-2 text-sm text-rose-900/70 dark:text-zinc-300">Quick access with telehealth when appropriate.</p>
            </div>
        </div>
    </section>

    <!-- Meet Our Doctors -->
    <section class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-16">


        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Doctor 1 -->
            <div class="group relative overflow-hidden rounded-2xl bg-white/80 dark:bg-white/5 backdrop-blur-sm shadow-sm hover:shadow-lg transition-all">
                <div class="absolute -top-24 -left-24 w-48 h-48 rounded-full bg-gradient-to-tr from-fuchsia-200/40 to-transparent dark:from-fuchsia-400/10 blur-2xl"></div>
                <div class="aspect-[4/5] w-full overflow-hidden">
                    <img src="{{ asset('images/doctor.png') }}" alt="Pediatrician" class="h-full w-full object-cover transform transition-transform duration-300 group-hover:scale-105" loading="lazy" />
                </div>
                <div class="absolute inset-x-0 bottom-0 p-4">
                    <div class="mx-auto w-fit rounded-full bg-white/90 dark:bg-zinc-900/70 px-4 py-1 text-xs text-zinc-700 dark:text-zinc-200 shadow-sm ring-1 ring-white/70 dark:ring-zinc-700"></div>
                </div>
            </div>

            <!-- Doctor 2 -->
            <div class="group relative overflow-hidden rounded-2xl bg-white/80 dark:bg-white/5 backdrop-blur-sm shadow-sm hover:shadow-lg transition-all">
                <div class="absolute -top-24 -right-24 w-48 h-48 rounded-full bg-gradient-to-tr from-sky-200/40 to-transparent dark:from-sky-400/10 blur-2xl"></div>
                <div class="aspect-[4/5] w-full overflow-hidden">
                    <img src="{{ asset('images/doctor1.png') }}" alt="Pediatrician" class="h-full w-full object-cover transform transition-transform duration-300 group-hover:scale-105" loading="lazy" />
                </div>
                <div class="absolute inset-x-0 bottom-0 p-4">
                    <div class="mx-auto w-fit rounded-full bg-white/90 dark:bg-zinc-900/70 px-4 py-1 text-xs text-zinc-700 dark:text-zinc-200 shadow-sm ring-1 ring-white/70 dark:ring-zinc-700"></div>
                </div>
            </div>

            <!-- Doctor 3 -->
            <div class="group relative overflow-hidden rounded-2xl bg-white/80 dark:bg-white/5 backdrop-blur-sm shadow-sm hover:shadow-lg transition-all">
                <div class="absolute -bottom-24 -left-24 w-48 h-48 rounded-full bg-gradient-to-tr from-emerald-200/40 to-transparent dark:from-emerald-400/10 blur-2xl"></div>
                <div class="aspect-[4/5] w-full overflow-hidden">
                    <img src="{{ asset('images/doctor2.png') }}" alt="Pediatrician" class="h-full w-full object-cover transform transition-transform duration-300 group-hover:scale-105" loading="lazy" />
                </div>
                <div class="absolute inset-x-0 bottom-0 p-4">
                    <div class="mx-auto w-fit rounded-full bg-white/90 dark:bg-zinc-900/70 px-4 py-1 text-xs text-zinc-700 dark:text-zinc-200 shadow-sm ring-1 ring-white/70 dark:ring-zinc-700"></div>
                </div>
            </div>

            <!-- Doctor 4 -->
            <div class="group relative overflow-hidden rounded-2xl bg-white/80 dark:bg-white/5 backdrop-blur-sm shadow-sm hover:shadow-lg transition-all">
                <div class="absolute -bottom-24 -right-24 w-48 h-48 rounded-full bg-gradient-to-tr from-violet-200/40 to-transparent dark:from-violet-400/10 blur-2xl"></div>
                <div class="aspect-[4/5] w-full overflow-hidden">
                    <img src="{{ asset('images/doctor3.png') }}" alt="Pediatrician" class="h-full w-full object-cover transform transition-transform duration-300 group-hover:scale-105" loading="lazy" />
                </div>
                <div class="absolute inset-x-0 bottom-0 p-4">
                    <div class="mx-auto w-fit rounded-full bg-white/90 dark:bg-zinc-900/70 px-4 py-1 text-xs text-zinc-700 dark:text-zinc-200 shadow-sm ring-1 ring-white/70 dark:ring-zinc-700"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Clinic Rules & Policies -->
   <!-- @php($policy = \App\Models\ClinicPolicy::query()->first()) -->
    <section class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-16">
        <div class="mb-10 text-center">
            <div class="inline-flex items-center gap-2 rounded-full bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 px-3 py-1 text-xs">
                {{ __('Clinic Rules') }}
            </div>
            <h2 class="mt-3 text-3xl md:text-4xl font-bold tracking-tight bg-gradient-to-r from-zinc-900 via-zinc-700 to-zinc-900 text-transparent bg-clip-text dark:from-white dark:via-neutral-200 dark:to-white">{{ __('Clinic Rules & Care Policies') }}</h2>
            <p class="mt-3 text-sm text-zinc-600 dark:text-zinc-400 max-w-2xl mx-auto">{{ __('Clear, modern, and patient‑first — feel prepared every step of the way.') }}</p>
        </div>

        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <div class="group relative overflow-hidden rounded-2xl bg-white/80 dark:bg-white/5 backdrop-blur-sm shadow-sm hover:shadow-lg transition-all text-center p-6">
                <div class="absolute -top-20 -right-20 w-40 h-40 rounded-full bg-gradient-to-tr from-blue-200/40 to-transparent dark:from-blue-400/10 blur-2xl"></div>
                <div class="flex items-center gap-3 justify-center">
                    <span class="inline-flex size-10 items-center justify-center rounded-xl bg-blue-50 text-blue-600 dark:bg-blue-950/50 dark:text-blue-300">
                        <flux:icon.calendar-days variant="mini" />
                    </span>
                    <h3 class="text-lg font-semibold">{{ __('Appointments & Cancellations') }}</h3>
                </div>
                <p class="mt-3 text-sm text-zinc-600 dark:text-zinc-300 whitespace-pre-line leading-relaxed">
                    {{ $policy?->cancellation_policy ?? __('Please cancel or reschedule at least 24 hours in advance so we can offer your slot to another family.') }}
                </p>
            </div>
            <div class="group relative overflow-hidden rounded-2xl bg-white/80 dark:bg-white/5 backdrop-blur-sm shadow-sm hover:shadow-lg transition-all text-center p-6">
                <div class="absolute -top-20 -right-20 w-40 h-40 rounded-full bg-gradient-to-tr from-emerald-200/40 to-transparent dark:from-emerald-400/10 blur-2xl"></div>
                <div class="flex items-center gap-3 justify-center">
                    <span class="inline-flex size-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 dark:bg-emerald-950/50 dark:text-emerald-300">
                        <flux:icon.shield-check variant="mini" />
                    </span>
                    <h3 class="text-lg font-semibold">{{ __('Privacy & Data') }}</h3>
                </div>
                <p class="mt-3 text-sm text-zinc-600 dark:text-zinc-300 whitespace-pre-line leading-relaxed">
                    {{ $policy?->privacy_rules ?? __('We protect your child’s information with strict access controls and never share without consent.') }}
                </p>
            </div>
            <div class="group relative overflow-hidden rounded-2xl bg-white/80 dark:bg-white/5 backdrop-blur-sm shadow-sm hover:shadow-lg transition-all text-center p-6">
                <div class="absolute -top-20 -right-20 w-40 h-40 rounded-full bg-gradient-to-tr from-violet-200/40 to-transparent dark:from-violet-400/10 blur-2xl"></div>
                <div class="flex items-center gap-3 justify-center">
                    <span class="inline-flex size-10 items-center justify-center rounded-xl bg-violet-50 text-violet-600 dark:bg-violet-950/50 dark:text-violet-300">
                        <flux:icon.clipboard-document-list variant="mini" />
                    </span>
                    <h3 class="text-lg font-semibold">{{ __('Staff Workflows') }}</h3>
                </div>
                <p class="mt-3 text-sm text-zinc-600 dark:text-zinc-300 whitespace-pre-line leading-relaxed">
                    {{ $policy?->staff_workflows ?? __('Our team follows clear procedures to provide safe, timely, and compassionate care.') }}
                </p>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 pb-20">
        <div class="rounded-2xl bg-blue-600 p-10 text-white">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-2xl font-semibold">Ready to schedule?</h2>
                    <p class="text-blue-100">Book an appointment in minutes. We’re here for you.</p>
                </div>
                <a href="{{ route('dashboard') }}" class="rounded-lg bg-white/10 px-5 py-3 hover:bg-white/20">Book Now</a>
            </div>
        </div>
    </section>
@endsection
