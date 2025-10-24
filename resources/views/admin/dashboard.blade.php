<x-layouts.app :title="__('Admin Dashboard')">
    <div class="p-6 space-y-8">
        <!-- Welcome banner -->
        <div class="rounded-2xl bg-gradient-to-br from-sky-50 to-white dark:from-zinc-800 dark:to-zinc-900 border border-neutral-200 dark:border-neutral-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-semibold tracking-tight">{{ __('Welcome, Admin') }}</h1>
                    <p class="text-neutral-600 dark:text-neutral-300">{{ __('Your clinic overview and insights.') }}</p>
                </div>
                <div class="hidden md:flex items-center gap-2">
                    <span class="inline-flex items-center gap-2 rounded-lg bg-neutral-900 text-white px-4 py-2">
                        <flux:icon.sparkles variant="mini" /> {{ __('Dashboard') }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Reminders -->
        <div class="rounded-xl border border-blue-200 bg-blue-50 dark:bg-blue-900/20 dark:border-blue-800 p-4">
            <div class="flex items-start gap-3">
                <flux:icon.bell variant="mini" class="mt-1" />
                <div>
                    <div class="font-medium">{{ __('Appointment Reminders') }}</div>
                    <p class="text-sm text-neutral-700 dark:text-neutral-300">{{ __('Send a 24‑hour email reminder for tomorrow’s scheduled appointments.') }}</p>
                    <div class="mt-3 flex items-center gap-3">
                        <form method="POST" action="{{ route('dashboard.reminders.send') }}">
                            @csrf
                            <button type="submit" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-md bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <flux:icon.paper-airplane variant="mini" /> {{ __('Send reminders now') }}
                            </button>
                        </form>
                        @if(session('status'))
                            <span class="text-sm text-blue-800 bg-blue-100 px-2 py-1 rounded">{{ session('status') }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Top stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-5 border border-gray-100 dark:border-gray-700">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-xl bg-sky-100 dark:bg-sky-900/40 flex items-center justify-center">
                        <svg class="h-6 w-6 text-sky-600 dark:text-sky-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                    </div>
                    <div>
                        <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">{{ __('Patients') }}</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['patients'] ?? '—' }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-5 border border-gray-100 dark:border-gray-700">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-xl bg-emerald-100 dark:bg-emerald-900/40 flex items-center justify-center">
                        <svg class="h-6 w-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7h18M6 12h12M9 17h6"/></svg>
                    </div>
                    <div>
                        <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">{{ __('Appointments') }}</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['appointments'] ?? '—' }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-5 border border-gray-100 dark:border-gray-700">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-xl bg-violet-100 dark:bg-violet-900/40 flex items-center justify-center">
                        <svg class="h-6 w-6 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </div>
                    <div>
                        <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">{{ __('Medical Records') }}</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['medical_records'] ?? '—' }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-5 border border-gray-100 dark:border-gray-700">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-xl bg-amber-100 dark:bg-amber-900/40 flex items-center justify-center">
                        <svg class="h-6 w-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v8m-4-4h8"/></svg>
                    </div>
                    <div>
                        <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">{{ __('Prescriptions') }}</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['prescriptions'] ?? '—' }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-5 border border-gray-100 dark:border-gray-700">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-xl bg-rose-100 dark:bg-rose-900/40 flex items-center justify-center">
                        <svg class="h-6 w-6 text-rose-600 dark:text-rose-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4"/></svg>
                    </div>
                    <div>
                        <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">{{ __('Diagnoses') }}</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['diagnoses'] ?? '—' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <h3 class="text-gray-900 dark:text-white font-semibold">{{ __('Appointments by Status') }}</h3>
                </div>
                <div class="mt-4">
                    <canvas id="statusDoughnut"
                        data-labels='@json($statusLabels ?? [])'
                        data-counts='@json($statusCounts ?? [])'
                        height="220"></canvas>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <h3 class="text-gray-900 dark:text-white font-semibold">{{ __('Visit Types') }}</h3>
                </div>
                <div class="mt-4">
                    <canvas id="visitTypeDoughnut"
                        data-labels='@json($visitTypeLabels ?? [])'
                        data-counts='@json($visitTypeCounts ?? [])'
                        height="220"></canvas>
                </div>
            </div>
        </div>
    </div>

</x-layouts.app>