<x-layouts.app :title="__('Manage Staff Accounts')">
    <div class="flex h-full w-full flex-1 flex-col gap-6">
        <!-- Header -->
        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900 shadow-sm">
            <div class="flex items-center justify-between px-4 py-3">
                <div>
                    <h2 class="text-xl font-semibold tracking-tight">{{ __('Manage Staff Accounts') }}</h2>
                    <p class="mt-1 text-sm text-neutral-600 dark:text-neutral-300">{{ __('Create, edit, and remove staff users with access to the clinic platform.') }}</p>
                </div>
                <a href="{{ route('admin.staff.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 text-white px-3 py-1.5 hover:bg-blue-700" wire:navigate>
                    <flux:icon.plus variant="mini" /> {{ __('Add Staff') }}
                </a>
            </div>
        </div>

        <!-- Table -->
        <div class="rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-zinc-900 shadow-sm">
            <div class="p-4 overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="text-left text-neutral-600 dark:text-neutral-300">
                            <th class="py-2">{{ __('Name') }}</th>
                            <th class="py-2">{{ __('Email') }}</th>
                            <th class="py-2">{{ __('Role') }}</th>
                            <th class="py-2 text-right">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                        @forelse($staff as $user)
                            <tr>
                                <td class="py-2">{{ $user->name }}</td>
                                <td class="py-2">{{ $user->email }}</td>
                                <td class="py-2"><span class="inline-flex rounded-lg bg-neutral-100 dark:bg-neutral-800 px-2 py-0.5 text-xs">{{ $user->role }}</span></td>
                                <td class="py-2">
                                    <div class="flex items-center gap-2 justify-end">
                                        <a href="{{ route('admin.staff.edit', $user->id) }}" class="inline-flex items-center gap-2 rounded-lg bg-neutral-100 dark:bg-neutral-800 text-neutral-800 dark:text-neutral-200 px-3 py-1 hover:bg-neutral-200 dark:hover:bg-neutral-700" wire:navigate>
                                            <flux:icon.pencil-square variant="mini" /> {{ __('Edit') }}
                                        </a>
                                        <form method="POST" action="{{ route('admin.staff.destroy', $user->id) }}" onsubmit="return confirm('Delete this staff account?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-red-600 text-white px-3 py-1 hover:bg-red-700">
                                                <flux:icon.trash variant="mini" /> {{ __('Delete') }}
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-6 text-center text-neutral-600 dark:text-neutral-300">{{ __('No staff accounts found.') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">{{ $staff->links() }}</div>
            </div>
        </div>
    </div>
</x-layouts.app>