@extends('layouts.site')

@section('content')
    <!-- Colorful Hero -->
    <section class="relative isolate overflow-hidden">
        <div class="absolute inset-0 -z-10">
            <div class="absolute -top-28 -left-40 h-80 w-80 rounded-full bg-gradient-to-br from-fuchsia-500 via-sky-500 to-emerald-400 blur-3xl opacity-30"></div>
            <div class="absolute -bottom-24 -right-32 h-96 w-96 rounded-full bg-gradient-to-tr from-orange-500 via-pink-500 to-violet-500 blur-3xl opacity-30"></div>
        </div>

        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-14">
            <div class="grid items-center gap-10 lg:grid-cols-[7fr_3fr]">
                <div>
                    <span class="inline-flex items-center gap-2 rounded-full bg-white/70 px-3 py-1 text-sm text-zinc-700 shadow-sm ring-1 ring-white/60 dark:bg-zinc-800/60 dark:text-zinc-200 dark:ring-zinc-700">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5M21 12A9 9 0 1 1 3 12a9 9 0 0 1 18 0Z" />
                        </svg>
                        {{ __('Explore our care sections') }}
                    </span>

                    <h1 class="mt-4 text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-zinc-900 dark:text-white">{{ __('Sections') }}</h1>
                    <p class="mt-6 text-lg text-zinc-700 dark:text-zinc-300">{{ __('Explore key areas of our pediatric care and clinic information. Learn how new families get started, what wellness looks like at every age, and how we handle same‑day sick visits with compassion and efficiency. We also cover development, behavior, and resources for navigating insurance and billing — all in one place.') }}</p>
                    <p class="mt-3 text-sm text-zinc-600 dark:text-zinc-400 max-w-2xl">{{ __('From first appointments to long‑term care plans, our sections guide you through expectations, preparation tips, and practical steps to make every visit smooth. Discover preventative guidance, vaccination schedules, growth tracking, and telehealth options designed to support your child and your family’s routine.') }}</p>
                </div>

                <div class="relative">
                    <div class="aspect-[4/3] w-full">
                        <img src="{{ asset('images/doctor1.png') }}" alt="Doctor" class="w-full max-h-[300px] object-contain mx-auto" loading="lazy" />
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section Grid -->
    <section class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-16">
        <div class="grid gap-6 lg:grid-cols-3">
            <div class="group relative overflow-hidden rounded-2xl bg-white/80 dark:bg-white/5 backdrop-blur-sm shadow-sm hover:shadow-lg transition-all p-6">
                <div class="absolute -top-20 -right-20 w-40 h-40 rounded-full bg-gradient-to-tr from-sky-200/40 to-transparent dark:from-sky-400/10 blur-2xl"></div>
                <div class="flex items-center gap-3 mb-2">
                    <span class="inline-flex size-10 items-center justify-center rounded-xl bg-sky-50 text-sky-600 dark:bg-sky-950/50 dark:text-sky-300 ring-1 ring-sky-200/60 dark:ring-sky-800/30">
                        <flux:icon.users variant="mini" />
                    </span>
                    <h3 class="text-xl font-semibold">{{ __('New Patients') }}</h3>
                </div>
                <p class="text-sm text-zinc-600 dark:text-zinc-300">{{ __('How to register, first visit expectations, and what to bring.') }}</p>
            </div>

            <div class="group relative overflow-hidden rounded-2xl bg-white/80 dark:bg-white/5 backdrop-blur-sm shadow-sm hover:shadow-lg transition-all p-6">
                <div class="absolute -top-20 -right-20 w-40 h-40 rounded-full bg-gradient-to-tr from-emerald-200/40 to-transparent dark:from-emerald-400/10 blur-2xl"></div>
                <div class="flex items-center gap-3 mb-2">
                    <span class="inline-flex size-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 dark:bg-emerald-950/50 dark:text-emerald-300 ring-1 ring-emerald-200/60 dark:ring-emerald-800/30">
                        <flux:icon.shield-check variant="mini" />
                    </span>
                    <h3 class="text-xl font-semibold">{{ __('Wellness & Vaccinations') }}</h3>
                </div>
                <p class="text-sm text-zinc-600 dark:text-zinc-300">{{ __('Routine checkups, growth tracking, and immunization schedules.') }}</p>
            </div>

            <div class="group relative overflow-hidden rounded-2xl bg-white/80 dark:bg-white/5 backdrop-blur-sm shadow-sm hover:shadow-lg transition-all p-6">
                <div class="absolute -top-20 -right-20 w-40 h-40 rounded-full bg-gradient-to-tr from-rose-200/40 to-transparent dark:from-rose-400/10 blur-2xl"></div>
                <div class="flex items-center gap-3 mb-2">
                    <span class="inline-flex size-10 items-center justify-center rounded-xl bg-rose-50 text-rose-600 dark:bg-rose-950/50 dark:text-rose-300 ring-1 ring-rose-200/60 dark:ring-rose-800/30">
                        <flux:icon.bolt variant="mini" />
                    </span>
                    <h3 class="text-xl font-semibold">{{ __('Sick Visits') }}</h3>
                </div>
                <p class="text-sm text-zinc-600 dark:text-zinc-300">{{ __('Same-day appointments and telehealth options when possible.') }}</p>
            </div>

            <div class="group relative overflow-hidden rounded-2xl bg-white/80 dark:bg-white/5 backdrop-blur-sm shadow-sm hover:shadow-lg transition-all p-6">
                <div class="absolute -top-20 -right-20 w-40 h-40 rounded-full bg-gradient-to-tr from-violet-200/40 to-transparent dark:from-violet-400/10 blur-2xl"></div>
                <div class="flex items-center gap-3 mb-2">
                    <span class="inline-flex size-10 items-center justify-center rounded-xl bg-violet-50 text-violet-600 dark:bg-violet-950/50 dark:text-violet-300 ring-1 ring-violet-200/60 dark:ring-violet-800/30">
                        <flux:icon.clipboard-document-list variant="mini" />
                    </span>
                    <h3 class="text-xl font-semibold">{{ __('Development & Behavior') }}</h3>
                </div>
                <p class="text-sm text-zinc-600 dark:text-zinc-300">{{ __('Screenings, support, and referrals for families.') }}</p>
            </div>

            <div class="group relative overflow-hidden rounded-2xl bg-white/80 dark:bg-white/5 backdrop-blur-sm shadow-sm hover:shadow-lg transition-all p-6">
                <div class="absolute -top-20 -right-20 w-40 h-40 rounded-full bg-gradient-to-tr from-amber-200/40 to-transparent dark:from-amber-400/10 blur-2xl"></div>
                <div class="flex items-center gap-3 mb-2">
                    <span class="inline-flex size-10 items-center justify-center rounded-xl bg-amber-50 text-amber-600 dark:bg-amber-950/50 dark:text-amber-300 ring-1 ring-amber-200/60 dark:ring-amber-800/30">
                        <flux:icon.credit-card variant="mini" />
                    </span>
                    <h3 class="text-xl font-semibold">{{ __('Billing & Insurance') }}</h3>
                </div>
                <p class="text-sm text-zinc-600 dark:text-zinc-300">{{ __('Accepted plans, payment options, and financial assistance.') }}</p>
            </div>
        </div>
    </section>
@endsection