<x-layouts.app :title="__('Record Requests')">
    <div id="staff-record-requests-page" class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900 p-4">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold">{{ __('Record Requests') }}</h2>
                <div class="flex items-center gap-3">
                    <a href="{{ route('staff.record-requests.index') }}" class="inline-flex items-center justify-center rounded-md p-1.5 hover:bg-neutral-200 dark:hover:bg-neutral-700" title="Refresh" wire:navigate>
                        <flux:icon.arrow-path variant="mini" />
                    </a>
                    <form method="GET" action="{{ route('staff.record-requests.index') }}" class="flex items-center gap-2">
                        <input type="text" name="q" value="{{ $q }}" placeholder="{{ __('Search name, email, type...') }}" class="rounded-md border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-zinc-900 px-2 py-1 text-sm" />
                        <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-neutral-100 dark:bg-neutral-800 text-neutral-800 dark:text-neutral-200 px-3 py-1 hover:bg-neutral-200 dark:hover:bg-neutral-700">
                            <flux:icon.magnifying-glass variant="mini" /> {{ __('Search') }}
                        </button>
                    </form>
                </div>
            </div>

            <div class="flex items-center gap-2 mb-3">
                @php $selected = $selectedStatus; @endphp
                <a href="{{ route('staff.record-requests.index') }}" class="inline-flex items-center rounded-md px-3 py-1 text-sm border {{ $selected ? 'bg-neutral-50 text-neutral-700 border-neutral-200 dark:bg-neutral-800 dark:text-neutral-300 dark:border-neutral-700' : 'bg-blue-600 text-white border-blue-600' }}" wire:navigate>{{ __('All') }}</a>
                @foreach(['waiting','processing','completed','rejected'] as $st)
                    <a href="{{ route('staff.record-requests.index', ['status' => $st, 'q' => $q]) }}" class="inline-flex items-center rounded-md px-3 py-1 text-sm border {{ $selected === $st ? 'bg-blue-600 text-white border-blue-600' : 'bg-neutral-50 text-neutral-700 border-neutral-200 dark:bg-neutral-800 dark:text-neutral-300 dark:border-neutral-700' }}" wire:navigate>{{ ucfirst($st) }}</a>
                @endforeach
            </div>

            <div class="max-h-[70vh] overflow-auto">
                <table class="min-w-full text-sm">
                    <thead class="sticky top-0 bg-neutral-50 dark:bg-zinc-900/60 backdrop-blur supports-[backdrop-filter]:bg-neutral-50/80 dark:supports-[backdrop-filter]:bg-zinc-900/40">
                        <tr>
                            <th class="px-3 py-2 text-left">{{ __('Created') }}</th>
                            <th class="px-3 py-2 text-left">{{ __('Requester') }}</th>
                            <th class="px-3 py-2 text-left">{{ __('Type & Range') }}</th>
                            <th class="px-3 py-2 text-left">{{ __('Delivery') }}</th>
                            <th class="px-3 py-2 text-left">{{ __('Status') }}</th>
                            <th class="px-3 py-2 text-left">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($requests as $req)
                            @php
                                $status = $req->status ?? 'waiting';
                                $classes = 'bg-neutral-50 text-neutral-800 border-neutral-200 dark:bg-neutral-800 dark:text-neutral-200 dark:border-neutral-700';
                                if ($status === 'waiting') {
                                    $classes = 'bg-yellow-50 text-yellow-800 border-yellow-200 dark:bg-yellow-900/20 dark:text-yellow-200 dark:border-yellow-800';
                                } elseif ($status === 'processing') {
                                    $classes = 'bg-blue-50 text-blue-800 border-blue-200 dark:bg-blue-900/20 dark:text-blue-200 dark:border-blue-800';
                                } elseif ($status === 'completed') {
                                    $classes = 'bg-green-50 text-green-800 border-green-200 dark:bg-green-900/20 dark:text-green-200 dark:border-green-800';
                                } elseif ($status === 'rejected') {
                                    $classes = 'bg-red-50 text-red-800 border-red-200 dark:bg-red-900/20 dark:text-red-200 dark:border-red-800';
                                }
                            @endphp
                            <tr class="border-t border-neutral-200 dark:border-neutral-700">
                                <td class="px-3 py-2">{{ optional($req->created_at)->format('Y-m-d H:i') ?? '—' }}</td>
                                <td class="px-3 py-2">
                                    @if($req->user)
                                        <div class="text-neutral-900 dark:text-neutral-100">
                                            {{ $req->user->name }}
                                            @if($req->user->email)
                                                <span class="text-neutral-500 text-xs"> — {{ $req->user->email }}</span>
                                            @endif
                                        </div>
                                    @elseif($req->patient)
                                        <div class="text-neutral-900 dark:text-neutral-100">
                                            {{ $req->patient->child_name }}
                                            @if($req->patient->guardian_name)
                                                <span class="text-neutral-500 text-xs"> — {{ $req->patient->guardian_name }}</span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-neutral-500">{{ __('Unknown') }}</span>
                                    @endif
                                </td>
                                <td class="px-3 py-2">
                                    @php
                                        $typeLabels = [
                                            'history' => __('Medical history'),
                                            'vaccinations' => __('Vaccination records'),
                                            'certificate' => __('Medical certificate'),
                                            'clearance' => __('Health clearance'),
                                            'consultation' => __('Consultation notes'),
                                            'prescriptions' => __('Prescriptions'),
                                            'lab_results' => __('Lab results'),
                                            'referral' => __('Referral'),
                                            'other' => __('Other'),
                                        ];
                                        $typeText = $typeLabels[$req->record_type] ?? ucfirst($req->record_type ?? '—');
                                    @endphp
                                    <div class="flex items-center gap-2">
                                        <span class="inline-flex items-center gap-1 rounded-md px-2 py-0.5 text-xs border bg-neutral-50 text-neutral-800 border-neutral-200 dark:bg-neutral-800 dark:text-neutral-200 dark:border-neutral-700">
                                            <flux:icon.document-duplicate variant="mini" />
                                            {{ $typeText }}
                                        </span>
                                        <span class="text-xs text-neutral-600 dark:text-neutral-400">{{ optional($req->date_start)->format('Y-m-d') }} → {{ optional($req->date_end)->format('Y-m-d') }}</span>
                                    </div>
                                </td>
                                <td class="px-3 py-2">
                                    <div class="flex items-center gap-2">
                                        <span class="inline-flex items-center rounded-md px-2 py-0.5 text-xs border bg-neutral-50 text-neutral-800 border-neutral-200 dark:bg-neutral-800 dark:text-neutral-200 dark:border-neutral-700 capitalize">{{ $req->delivery_method ?? 'download' }}</span>
                                        @if($req->delivery_method === 'email' && $req->delivery_email)
                                            <span class="text-xs text-neutral-600 dark:text-neutral-400">{{ $req->delivery_email }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-3 py-2">
                                    <span class="inline-flex items-center rounded-md px-2 py-0.5 text-xs border {{ $classes }} capitalize">{{ $status }}</span>
                                </td>
                                <td class="px-3 py-2">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('staff.record-requests.show', $req) }}" class="inline-flex items-center gap-2 rounded-lg bg-neutral-100 dark:bg-neutral-800 text-neutral-800 dark:text-neutral-200 px-3 py-1 hover:bg-neutral-200 dark:hover:bg-neutral-700" wire:navigate>
                                            <flux:icon.eye variant="mini" /> {{ __('View') }}
                                        </a>
                                        <form method="POST" action="{{ route('staff.record-requests.update-status', $req) }}" class="inline-flex items-center gap-2">
                                            @csrf
                                            <select name="status" class="rounded-md border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-zinc-900 text-sm px-2 py-1" onchange="this.form.submit()">
                                                <option value="waiting" {{ $status === 'waiting' ? 'selected' : '' }}>{{ __('Waiting') }}</option>
                                                <option value="processing" {{ $status === 'processing' ? 'selected' : '' }}>{{ __('Processing') }}</option>
                                                <option value="completed" {{ $status === 'completed' ? 'selected' : '' }}>{{ __('Completed') }}</option>
                                                <option value="rejected" {{ $status === 'rejected' ? 'selected' : '' }}>{{ __('Rejected') }}</option>
                                            </select>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-3 py-4 text-center text-neutral-600 dark:text-neutral-300">{{ __('No record requests found.') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $requests->links() }}
            </div>
        </div>
    </div>
</x-layouts.app>
