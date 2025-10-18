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
                        {{ __('Family-centered care') }}
                    </span>

                    <h1 class="mt-4 text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-zinc-900 dark:text-white">{{ __('About Our Clinic') }}</h1>
                    <p class="mt-6 text-lg text-zinc-700 dark:text-zinc-300">{{ __('We’re a family-centered pediatric clinic committed to accessible, modern care. Our mission is to partner with parents, empowering them with guidance and support while we care for children from birth through adolescence.') }}</p>

                    <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-3 text-zinc-700 dark:text-zinc-300">
                        <div class="inline-flex items-center gap-2"><span class="inline-block size-2 rounded-full bg-fuchsia-500"></span>{{ __('Board-certified pediatricians') }}</div>
                        <div class="inline-flex items-center gap-2"><span class="inline-block size-2 rounded-full bg-sky-500"></span>{{ __('Preventive care and wellness visits') }}</div>
                        <div class="inline-flex items-center gap-2"><span class="inline-block size-2 rounded-full bg-emerald-500"></span>{{ __('Vaccinations, screenings, and growth tracking') }}</div>
                        <div class="inline-flex items-center gap-2"><span class="inline-block size-2 rounded-full bg-violet-500"></span>{{ __('Developmental and behavioral support') }}</div>
                    </div>
                </div>

                <div class="relative">
                    <div class="aspect-[4/3] w-full">
                        <img src="{{ asset('images/doctor3.png') }}" alt="Our doctors" class="w-full max-h-[380px] object-contain mx-auto" loading="lazy" />
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection