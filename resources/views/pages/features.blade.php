@extends('layouts.site')

@section('content')
    <!-- Colorful Hero -->
    <section class="relative isolate overflow-hidden">
        <div class="absolute inset-0 -z-10">
            <div class="absolute -top-28 -left-40 h-80 w-80 rounded-full bg-gradient-to-br from-fuchsia-500 via-sky-500 to-emerald-400 blur-3xl opacity-30"></div>
            <div class="absolute -bottom-24 -right-32 h-96 w-96 rounded-full bg-gradient-to-tr from-orange-500 via-pink-500 to-violet-500 blur-3xl opacity-30"></div>
        </div>

        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-14">
            <div class="grid items-center gap-10 lg:grid-cols-[3fr_2fr]">
                <div>
                    <span class="inline-flex items-center gap-2 rounded-full bg-white/70 px-3 py-1 text-sm text-zinc-700 shadow-sm ring-1 ring-white/60 dark:bg-zinc-800/60 dark:text-zinc-200 dark:ring-zinc-700">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5M21 12A9 9 0 1 1 3 12a9 9 0 0 1 18 0Z" />
                        </svg>
                        {{ __('Designed for families') }}
                    </span>

                    <h1 class="mt-4 text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-zinc-900 dark:text-white">
                        {{ __('Modern, Kid‑Friendly Clinic Features') }}
                    </h1>
                    <p class="mt-6 text-lg text-zinc-700 dark:text-zinc-300">
                        {{ __('Everything you need for smooth visits — intuitive tools, playful spaces, and trusted care.') }}
                    </p>
                    <div class="mt-8 flex flex-wrap gap-4">
                        <a href="{{ route('dashboard') }}" class="rounded-xl bg-gradient-to-r from-sky-600 to-blue-600 px-6 py-3 text-white shadow-md hover:from-sky-700 hover:to-blue-700">{{ __('Go to Dashboard') }}</a>
                        <a href="{{ route('about') }}" class="rounded-xl bg-white/80 px-6 py-3 text-zinc-900 shadow-sm ring-1 ring-zinc-200 hover:bg-white dark:bg-zinc-800/70 dark:text-white dark:ring-zinc-700">{{ __('Learn More') }}</a>
                    </div>
                </div>

                <div class="relative">
                    <div class="aspect-[4/3] w-full">
                        <img src="{{ asset('images/doctor1.png') }}" alt="Clinic feature preview" class="w-full max-h-[380px] object-contain mx-auto" loading="lazy" />
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Feature Grid -->
    <section class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-16">
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <div class="group relative overflow-hidden rounded-2xl bg-white/80 dark:bg-white/5 backdrop-blur-sm shadow-sm hover:shadow-lg transition-all p-6">
                <div class="absolute -top-20 -right-20 w-40 h-40 rounded-full bg-gradient-to-tr from-sky-200/40 to-transparent dark:from-sky-400/10 blur-2xl"></div>
                <div class="flex items-center gap-3 mb-2">
                    <span class="inline-flex size-10 items-center justify-center rounded-xl bg-sky-50 text-sky-600 dark:bg-sky-950/50 dark:text-sky-300 ring-1 ring-sky-200/60 dark:ring-sky-800/30">
                        <flux:icon.calendar-days variant="mini" />
                    </span>
                    <h3 class="font-semibold">{{ __('Online Scheduling') }}</h3>
                </div>
                <p class="text-sm text-zinc-600 dark:text-zinc-300">{{ __('Book visits anytime with automated reminders and follow‑ups.') }}</p>
            </div>

            <div class="group relative overflow-hidden rounded-2xl bg-white/80 dark:bg-white/5 backdrop-blur-sm shadow-sm hover:shadow-lg transition-all p-6">
                <div class="absolute -top-20 -right-20 w-40 h-40 rounded-full bg-gradient-to-tr from-emerald-200/40 to-transparent dark:from-emerald-400/10 blur-2xl"></div>
                <div class="flex items-center gap-3 mb-2">
                    <span class="inline-flex size-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 dark:bg-emerald-950/50 dark:text-emerald-300 ring-1 ring-emerald-200/60 dark:ring-emerald-800/30">
                        <flux:icon.shield-check variant="mini" />
                    </span>
                    <h3 class="font-semibold">{{ __('Secure Portal') }}</h3>
                </div>
                <p class="text-sm text-zinc-600 dark:text-zinc-300">{{ __('Access records, vaccination logs, and messages from your phone.') }}</p>
            </div>

            <div class="group relative overflow-hidden rounded-2xl bg-white/80 dark:bg-white/5 backdrop-blur-sm shadow-sm hover:shadow-lg transition-all p-6">
                <div class="absolute -top-20 -right-20 w-40 h-40 rounded-full bg-gradient-to-tr from-fuchsia-200/40 to-transparent dark:from-fuchsia-400/10 blur-2xl"></div>
                <div class="flex items-center gap-3 mb-2">
                    <span class="inline-flex size-10 items-center justify-center rounded-xl bg-fuchsia-50 text-fuchsia-600 dark:bg-fuchsia-950/50 dark:text-fuchsia-300 ring-1 ring-fuchsia-200/60 dark:ring-fuchsia-800/30">
                        <flux:icon.sparkles variant="mini" />
                    </span>
                    <h3 class="font-semibold">{{ __('Friendly Environment') }}</h3>
                </div>
                <p class="text-sm text-zinc-600 dark:text-zinc-300">{{ __('Child-sized equipment, playful decor, and sensory‑friendly rooms.') }}</p>
            </div>

            <div class="group relative overflow-hidden rounded-2xl bg-white/80 dark:bg-white/5 backdrop-blur-sm shadow-sm hover:shadow-lg transition-all p-6">
                <div class="absolute -top-20 -right-20 w-40 h-40 rounded-full bg-gradient-to-tr from-violet-200/40 to-transparent dark:from-violet-400/10 blur-2xl"></div>
                <div class="flex items-center gap-3 mb-2">
                    <span class="inline-flex size-10 items-center justify-center rounded-xl bg-violet-50 text-violet-600 dark:bg-violet-950/50 dark:text-violet-300 ring-1 ring-violet-200/60 dark:ring-violet-800/30">
                        <flux:icon.clipboard-document-list variant="mini" />
                    </span>
                    <h3 class="font-semibold">{{ __('Vaccinations & Wellness') }}</h3>
                </div>
                <p class="text-sm text-zinc-600 dark:text-zinc-300">{{ __('Evidence‑based preventive care tailored to each stage of growth.') }}</p>
            </div>

            <div class="group relative overflow-hidden rounded-2xl bg-white/80 dark:bg-white/5 backdrop-blur-sm shadow-sm hover:shadow-lg transition-all p-6">
                <div class="absolute -top-20 -right-20 w-40 h-40 rounded-full bg-gradient-to-tr from-rose-200/40 to-transparent dark:from-rose-400/10 blur-2xl"></div>
                <div class="flex items-center gap-3 mb-2">
                    <span class="inline-flex size-10 items-center justify-center rounded-xl bg-rose-50 text-rose-600 dark:bg-rose-950/50 dark:text-rose-300 ring-1 ring-rose-200/60 dark:ring-rose-800/30">
                        <flux:icon.bolt variant="mini" />
                    </span>
                    <h3 class="font-semibold">{{ __('Same‑Day Sick Visits') }}</h3>
                </div>
                <p class="text-sm text-zinc-600 dark:text-zinc-300">{{ __('Quick access for urgent needs with telehealth when appropriate.') }}</p>
            </div>

            <div class="group relative overflow-hidden rounded-2xl bg-white/80 dark:bg-white/5 backdrop-blur-sm shadow-sm hover:shadow-lg transition-all p-6">
                <div class="absolute -top-20 -right-20 w-40 h-40 rounded-full bg-gradient-to-tr from-amber-200/40 to-transparent dark:from-amber-400/10 blur-2xl"></div>
                <div class="flex items-center gap-3 mb-2">
                    <span class="inline-flex size-10 items-center justify-center rounded-xl bg-amber-50 text-amber-600 dark:bg-amber-950/50 dark:text-amber-300 ring-1 ring-amber-200/60 dark:ring-amber-800/30">
                        <flux:icon.link variant="mini" />
                    </span>
                    <h3 class="font-semibold">{{ __('Community Resources') }}</h3>
                </div>
                <p class="text-sm text-zinc-600 dark:text-zinc-300">{{ __('Referrals for parenting classes, nutrition, and developmental support.') }}</p>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 pb-20">
        <div class="rounded-2xl bg-blue-600 p-10 text-white">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-2xl font-semibold">{{ __('Ready to schedule?') }}</h2>
                    <p class="text-blue-100">{{ __('Book an appointment in minutes. We’re here for you.') }}</p>
                </div>
                <a href="{{ route('dashboard') }}" class="rounded-lg bg-white/10 px-5 py-3 hover:bg-white/20">{{ __('Book Now') }}</a>
            </div>
        </div>
    </section>
@endsection