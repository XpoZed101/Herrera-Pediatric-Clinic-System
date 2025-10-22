<x-layouts.app :title="__('Record Request #').$request->id">
    <div class="p-6 space-y-6">
        <div class="relative overflow-hidden rounded-3xl ring-1 ring-inset ring-zinc-200 dark:ring-zinc-700 bg-gradient-to-br from-sky-500/15 via-emerald-500/15 to-violet-500/15">
            <div class="p-6 sm:p-8">
                <div class="flex items-center justify-between">
                    <h1 class="text-2xl sm:text-3xl font-semibold tracking-tight">{{ __('Record Request') }} #{{ $request->id }}</h1>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('staff.record-requests.index') }}" class="inline-flex items-center gap-2 rounded-md bg-neutral-100 dark:bg-neutral-800 text-neutral-800 dark:text-neutral-200 px-3 py-1 hover:bg-neutral-200 dark:hover:bg-neutral-700" wire:navigate>
                            <flux:icon.arrow-left variant="mini" /> {{ __('Back') }}
                        </a>
                        <a href="{{ route('staff.record-requests.show', $request) }}" class="inline-flex items-center justify-center rounded-md p-1.5 hover:bg-neutral-200 dark:hover:bg-neutral-700" title="Refresh" wire:navigate>
                            <flux:icon.arrow-path variant="mini" />
                        </a>
                    </div>
                </div>
                @php
                    $status = $request->status ?? 'waiting';
                    $statusClasses = 'bg-neutral-50 text-neutral-800 border-neutral-200 dark:bg-neutral-800 dark:text-neutral-200 dark:border-neutral-700';
                    if ($status === 'waiting') {
                        $statusClasses = 'bg-yellow-50 text-yellow-800 border-yellow-200 dark:bg-yellow-900/20 dark:text-yellow-200 dark:border-yellow-800';
                    } elseif ($status === 'processing') {
                        $statusClasses = 'bg-blue-50 text-blue-800 border-blue-200 dark:bg-blue-900/20 dark:text-blue-200 dark:border-blue-800';
                    } elseif ($status === 'completed') {
                        $statusClasses = 'bg-green-50 text-green-800 border-green-200 dark:bg-green-900/20 dark:text-green-200 dark:border-green-800';
                    } elseif ($status === 'rejected') {
                        $statusClasses = 'bg-red-50 text-red-800 border-red-200 dark:bg-red-900/20 dark:text-red-200 dark:border-red-800';
                    }
                @endphp
                <div class="mt-4 inline-flex items-center rounded-md px-2 py-0.5 text-xs border {{ $statusClasses }} capitalize">{{ $status }}</div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-2 space-y-4">
                <div class="rounded-2xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900 p-5">
                    <div class="font-semibold mb-2">{{ __('Requester') }}</div>
                    <div class="text-sm text-neutral-700 dark:text-neutral-300">
                        @if($request->user)
                            <div>{{ $request->user->name }} <span class="text-neutral-500">— {{ $request->user->email }}</span></div>
                        @elseif($request->patient)
                            <div>{{ $request->patient->child_name }} <span class="text-neutral-500">— {{ $request->patient->guardian_name }}</span></div>
                        @else
                            <div class="text-neutral-500">{{ __('Unknown requester') }}</div>
                        @endif
                    </div>
                </div>

                <div class="rounded-2xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900 p-5">
                    <div class="font-semibold mb-2">{{ __('Request Details') }}</div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                        <div>
                            <div class="text-neutral-500">{{ __('Type') }}</div>
                            <div class="mt-1 inline-flex items-center rounded-md px-2 py-0.5 text-xs border bg-neutral-50 text-neutral-800 border-neutral-200 dark:bg-neutral-800 dark:text-neutral-200 dark:border-neutral-700 capitalize">{{ $request->record_type }}</div>
                        </div>
                        <div>
                            <div class="text-neutral-500">{{ __('Date range') }}</div>
                            <div class="mt-1">{{ optional($request->date_start)->format('Y-m-d') }} → {{ optional($request->date_end)->format('Y-m-d') }}</div>
                        </div>
                        <div>
                            <div class="text-neutral-500">{{ __('Delivery') }}</div>
                            <div class="mt-1">{{ ucfirst($request->delivery_method ?? 'download') }} @if($request->delivery_method === 'email' && $request->delivery_email) <span class="text-neutral-500">— {{ $request->delivery_email }}</span> @endif</div>
                        </div>
                        <div>
                            <div class="text-neutral-500">{{ __('Purpose') }}</div>
                            <div class="mt-1">{{ $request->purpose ?? '—' }}</div>
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900 p-5">
                    <div class="font-semibold mb-2">{{ __('Notes') }}</div>
                    <div class="text-sm text-neutral-700 dark:text-neutral-300 whitespace-pre-wrap">{{ $request->notes ?? '—' }}</div>
                </div>
            </div>

            <div class="space-y-4">
                <div class="rounded-2xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900 p-5">
                    <div class="font-semibold mb-2">{{ __('Actions') }}</div>
                    <form method="POST" action="{{ route('staff.record-requests.update-status', $request) }}" class="space-y-3">
                        @csrf
                        <div>
                            <label class="block text-sm text-neutral-600">{{ __('Status') }}</label>
                            <select name="status" class="mt-1 w-full rounded-md border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-zinc-900 px-2 py-2 text-sm">
                                <option value="waiting" {{ $status === 'waiting' ? 'selected' : '' }}>{{ __('Waiting') }}</option>
                                <option value="processing" {{ $status === 'processing' ? 'selected' : '' }}>{{ __('Processing') }}</option>
                                <option value="completed" {{ $status === 'completed' ? 'selected' : '' }}>{{ __('Completed') }}</option>
                                <option value="rejected" {{ $status === 'rejected' ? 'selected' : '' }}>{{ __('Rejected') }}</option>
                            </select>
                        </div>
                        <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-neutral-100 dark:bg-neutral-800 text-neutral-800 dark:text-neutral-200 px-3 py-2 hover:bg-neutral-200 dark:hover:bg-neutral-700">
                            <flux:icon.cog variant="mini" /> {{ __('Update Status') }}
                        </button>
                    </form>

                    <div class="border-t border-neutral-200 dark:border-neutral-700 my-4"></div>

                    <form method="POST" action="{{ route('staff.record-requests.release', $request) }}" class="space-y-3">
                        @csrf
                        <div>
                            <label class="block text-sm text-neutral-600">{{ __('Release note (optional)') }}</label>
                            <textarea name="note" rows="3" class="mt-1 w-full rounded-md border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-zinc-900 px-2 py-2 text-sm" placeholder="{{ __('Describe what was released…') }}"></textarea>
                        </div>
                        <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 text-white px-3 py-2 hover:bg-emerald-700">
                            <flux:icon.check variant="mini" /> {{ __('Release and Mark Completed') }}
                        </button>
                    </form>
                </div>

                <div class="rounded-2xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900 p-5">
                    <div class="font-semibold mb-2">{{ __('Linked Record') }}</div>
                    @if($request->medicalRecord)
                        <div class="text-sm text-neutral-700 dark:text-neutral-300">
                            <div>#{{ $request->medicalRecord->id }} — {{ optional($request->medicalRecord->created_at)->format('Y-m-d') ?? '—' }}</div>
                            <div class="mt-2 flex items-center gap-2">
                                <a href="{{ route('admin.medical-records.show', $request->medicalRecord) }}" class="inline-flex items-center gap-2 rounded-md bg-neutral-100 dark:bg-neutral-800 text-neutral-800 dark:text-neutral-200 px-3 py-1 hover:bg-neutral-200 dark:hover:bg-neutral-700" target="_blank">
                                    <flux:icon.eye variant="mini" /> {{ __('Open Record') }}
                                </a>
                                <a href="{{ route('admin.medical-records.certificate.pdf', $request->medicalRecord) }}" class="inline-flex items-center gap-2 rounded-md bg-blue-600 text-white px-3 py-1 hover:bg-blue-700" target="_blank">
                                    <flux:icon.document-text variant="mini" /> {{ __('Certificate PDF') }}
                                </a>
                                <a href="{{ route('admin.medical-records.clearance.pdf', $request->medicalRecord) }}" class="inline-flex items-center gap-2 rounded-md bg-violet-600 text-white px-3 py-1 hover:bg-violet-700" target="_blank">
                                    <flux:icon.document-text variant="mini" /> {{ __('Clearance PDF') }}
                                </a>
                            </div>
                        </div>
                    @else
                        @php
                            $candidate = null;
                            $candidateQuery = \App\Models\MedicalRecord::query();
                            if ($request->patient_id) {
                                $candidateQuery->whereHas('appointment', function ($aq) use ($request) {
                                    $aq->where('patient_id', $request->patient_id);
                                });
                            }
                            if ($request->user_id) {
                                $candidateQuery->orWhere('user_id', $request->user_id);
                            }
                            $candidate = $candidateQuery->latest('conducted_at')->first();
                        @endphp
                        <div class="text-sm text-neutral-600 dark:text-neutral-400">
                            {{ __('No specific medical record linked.') }}
                            @if($candidate)
                                <div class="mt-2">
                                    <span class="text-neutral-700 dark:text-neutral-300">{{ __('Latest record found') }}:</span>
                                    <span class="text-neutral-900 dark:text-neutral-100">#{{ $candidate->id }} — {{ optional($candidate->conducted_at)->format('Y-m-d') ?? '—' }}</span>
                                    <div class="mt-2 flex items-center gap-2">
                                        <a href="{{ route('admin.medical-records.show', $candidate) }}" class="inline-flex items-center gap-2 rounded-md bg-neutral-100 dark:bg-neutral-800 text-neutral-800 dark:text-neutral-200 px-3 py-1 hover:bg-neutral-200 dark:hover:bg-neutral-700" target="_blank">
                                            <flux:icon.eye variant="mini" /> {{ __('Open Record') }}
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
