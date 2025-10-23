<x-layouts.app :title="__('Appointments')">
    <div id="admin-appointments-page" data-status-updated="{{ session('status_updated') }}" data-email-sent="{{ session('email_sent') }}" class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900 p-4 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold">Appointments</h2>
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.appointments.index') }}" class="inline-flex items-center justify-center rounded-md p-1.5 hover:bg-neutral-200 dark:hover:bg-neutral-700" title="Refresh" wire:navigate>
                        <flux:icon.arrow-path variant="mini" />
                    </a>
                </div>
            </div>

            <div class="max-h-[70vh] overflow-auto">
                <table class="min-w-full text-sm">
                    <thead class="sticky top-0 bg-neutral-50 dark:bg-zinc-900/60 backdrop-blur supports-[backdrop-filter]:bg-neutral-50/80 dark:supports-[backdrop-filter]:bg-zinc-900/40">
                        <tr>
                            <th class="px-3 py-2 text-left">Scheduled</th>
                            <th class="px-3 py-2 text-left">Requester</th>
                            <th class="px-3 py-2 text-left">Visit Type</th>
                            <th class="px-3 py-2 text-left">Status</th>
                            <th class="px-3 py-2 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($appointments as $appointment)
                            <tr class="border-t border-neutral-200 dark:border-neutral-700">
                                <td class="px-3 py-2">{{ optional($appointment->scheduled_at)->format('Y-m-d H:i') ?? '—' }}</td>
                                <td class="px-3 py-2">
                                    @if($appointment->user)
                                        <div class="text-neutral-900 dark:text-neutral-100">
                                            {{ $appointment->user->name }}
                                            @if($appointment->user->email)
                                                <span class="text-neutral-500 text-xs"> — {{ $appointment->user->email }}</span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-neutral-500">Unknown</span>
                                    @endif
                                </td>
                                <td class="px-3 py-2">{{ $appointment->visit_type ?? '—' }}</td>
                                <td class="px-3 py-2">
                                    @php
                                        $status = $appointment->status ?? 'requested';
                                        $classes = [
                                            'requested' => 'bg-yellow-50 text-yellow-800 border-yellow-200 dark:bg-yellow-900/20 dark:text-yellow-200 dark:border-yellow-800',
                                            'scheduled' => 'bg-blue-50 text-blue-800 border-blue-200 dark:bg-blue-900/20 dark:text-blue-200 dark:border-blue-800',
                                            'completed' => 'bg-green-50 text-green-800 border-green-200 dark:bg-green-900/20 dark:text-green-200 dark:border-green-800',
                                            'cancelled' => 'bg-red-50 text-red-800 border-red-200 dark:bg-red-900/20 dark:text-red-200 dark:border-red-800',
                                        ][$status] ?? 'bg-neutral-50 text-neutral-800 border-neutral-200 dark:bg-neutral-800 dark:text-neutral-200 dark:border-neutral-700';
                                    @endphp
                                    <span class="inline-flex items-center rounded-md px-2 py-0.5 text-xs border {{ $classes }} capitalize">
                                        {{ $status }}
                                    </span>
                                </td>
                                <td class="px-3 py-2">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.appointments.show', $appointment) }}" class="inline-flex items-center gap-2 rounded-lg bg-neutral-100 dark:bg-neutral-800 text-neutral-800 dark:text-neutral-200 px-3 py-1 hover:bg-neutral-200 dark:hover:bg-neutral-700" wire:navigate>
                                            <flux:icon.eye variant="mini" /> View
                                        </a>
                                        <a href="{{ route('admin.appointments.edit', $appointment) }}" class="inline-flex items-center gap-2 rounded-lg bg-neutral-100 dark:bg-neutral-800 text-neutral-800 dark:text-neutral-200 px-3 py-1 hover:bg-neutral-200 dark:hover:bg-neutral-700" wire:navigate>
                                            <flux:icon.pencil-square variant="mini" /> Edit
                                        </a>
                                        @if($appointment->patient)
                                            <a href="{{ route('admin.patients.consultations.create', $appointment->patient) }}" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 text-white px-3 py-1 hover:bg-blue-700" wire:navigate>
                                                <flux:icon.clipboard-document-list variant="mini" /> Conduct
                                            </a>
                                        @endif
                                        @if($appointment->user && $appointment->user->email)
                                            <form method="POST" action="{{ route('admin.appointments.email', $appointment) }}" class="inline">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-neutral-100 dark:bg-neutral-800 text-neutral-800 dark:text-neutral-200 px-3 py-1 hover:bg-neutral-200 dark:hover:bg-neutral-700">
                                                    <flux:icon.envelope variant="mini" /> Email
                                                </button>
                                            </form>
                                        @endif
                                        <form method="POST" action="{{ route('admin.appointments.update-status', $appointment) }}" class="inline-flex items-center gap-2">
                                            @csrf
                                            <select name="status" class="rounded-md border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-zinc-800 px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                                @foreach(['requested','scheduled','completed','cancelled'] as $opt)
                                                    <option value="{{ $opt }}" @selected(($appointment->status ?? 'requested') === $opt)>{{ ucfirst($opt) }}</option>
                                                @endforeach
                                            </select>
                                        </form>

                                        @if($appointment->medicalRecord)
                                            <a href="{{ route('admin.medical-records.edit', $appointment->medicalRecord) }}" class="inline-flex items-center gap-1 rounded-md bg-green-600 text-white px-2 py-0.5 text-xs hover:bg-green-700" wire:navigate>
                                                <flux:icon.document-text variant="mini" /> Edit Record
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-3 py-4 text-center text-neutral-600 dark:text-neutral-300">No appointments found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $appointments->links() }}
            </div>
        </div>


        {{-- Script is imported via app.js; avoid separate Vite entry to prevent manifest errors --}}
        <script>
        document.addEventListener('DOMContentLoaded', () => {
          const page = document.getElementById('admin-appointments-page');
          if (!page) return;
          const forms = page.querySelectorAll('form[action$="/status"]');
          forms.forEach(form => {
            const select = form.querySelector('select[name="status"]');
            if (!select) return;
            let prev = select.value;
            select.addEventListener('focus', () => { prev = select.value; });
            select.addEventListener('change', async () => {
              const newVal = select.value;
              const label = select.options[select.selectedIndex]?.text || newVal;
              try {
                const swal = window.Swal;
                if (swal) {
                  const result = await swal.fire({
                    icon: 'question',
                    title: 'Update status?',
                    text: `Change appointment status to ${label}?`,
                    showCancelButton: true,
                    confirmButtonText: 'Yes, update',
                    cancelButtonText: 'Cancel',
                    confirmButtonColor: '#2563eb',
                  });
                  if (!result.isConfirmed) {
                    select.value = prev;
                    return;
                  }
                }

                const token = form.querySelector('input[name="_token"]')?.value;
                const submitBtn = form.querySelector('button[type="submit"]');
                submitBtn?.setAttribute('disabled', 'disabled');
                select.setAttribute('disabled', 'disabled');

                const res = await fetch(form.action, {
                  method: 'POST',
                  headers: {
                    'X-CSRF-TOKEN': token || '',
                    'Accept': 'application/json',
                    'Content-Type': 'application/x-www-form-urlencoded',
                  },
                  body: new URLSearchParams({ status: newVal }),
                });
                if (!res.ok) throw new Error('Failed to update');
                const data = await res.json().catch(() => ({}));
                 prev = newVal;
 
                 // Update status badge text and colors without refresh
                 const row = form.closest('tr');
                 const statusCell = row?.querySelector('td:nth-child(4)');
                 const badge = statusCell?.querySelector('span');
                 if (badge) {
                   const classMap = {
                     requested: 'bg-yellow-50 text-yellow-800 border-yellow-200 dark:bg-yellow-900/20 dark:text-yellow-200 dark:border-yellow-800',
                     scheduled: 'bg-blue-50 text-blue-800 border-blue-200 dark:bg-blue-900/20 dark:text-blue-200 dark:border-blue-800',
                     completed: 'bg-green-50 text-green-800 border-green-200 dark:bg-green-900/20 dark:text-green-200 dark:border-green-800',
                     cancelled: 'bg-red-50 text-red-800 border-red-200 dark:bg-red-900/20 dark:text-red-200 dark:border-red-800',
                   };
                   const cls = classMap[newVal] || 'bg-neutral-50 text-neutral-800 border-neutral-200 dark:bg-neutral-800 dark:text-neutral-200 dark:border-neutral-700';
                   badge.className = 'inline-flex items-center rounded-md px-2 py-0.5 text-xs border ' + cls + ' capitalize';
                   badge.textContent = newVal;
                 }
 
                 if (swal) {
                   const extra = data.email_sent ? ' — Email notification sent.' : '';
                   await swal.fire({
                     icon: 'success',
                     title: 'Status Updated',
                     text: `Updated to ${label}.${extra}`,
                     timer: 1800,
                     showConfirmButton: false,
                   });
                 }
              } catch (err) {
                if (window.Swal) {
                  await window.Swal.fire({
                    icon: 'error',
                    title: 'Update Failed',
                    text: 'Trying standard submission...',
                    timer: 1500,
                    showConfirmButton: false,
                  });
                }
                form.submit();
              } finally {
                select.removeAttribute('disabled');
                const submitBtn = form.querySelector('button[type="submit"]');
                submitBtn?.removeAttribute('disabled');
              }
            });
          });
        });
        </script>
     </div>
 </x-layouts.app>
