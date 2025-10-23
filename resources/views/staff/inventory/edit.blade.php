<x-layouts.app>
    <x-slot:title>
        Edit Inventory Item
    </x-slot:title>

    <div class="max-w-5xl mx-auto px-4 py-6">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold tracking-tight text-zinc-900 dark:text-zinc-100">Edit Inventory Item</h1>
            <a href="{{ route('staff.inventory.index') }}" class="inline-flex items-center rounded-md border border-zinc-300 px-3 py-1.5 text-sm font-medium text-zinc-700 hover:bg-zinc-50 dark:border-zinc-600 dark:text-zinc-200 dark:hover:bg-zinc-800">Back</a>
        </div>

        @if (session('status_updated'))
            <div class="mt-4 rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-700">{{ session('status_updated') }}</div>
        @endif

        <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-2">
            <div class="rounded-xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                <h2 class="text-sm font-semibold text-zinc-700 dark:text-zinc-200">Details</h2>
                <form method="POST" action="{{ route('staff.inventory.update', $item) }}" class="mt-4 space-y-6">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">Name</label>
                            <input type="text" name="name" value="{{ old('name', $item->name) }}" required class="mt-1 block w-full rounded-md border-zinc-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">Type</label>
                            <select name="type" class="mt-1 block w-full rounded-md border-zinc-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100">
                                <option value="medicine" {{ old('type', $item->type) === 'medicine' ? 'selected' : '' }}>Medicine</option>
                                <option value="vaccine" {{ old('type', $item->type) === 'vaccine' ? 'selected' : '' }}>Vaccine</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">Strength</label>
                            <input type="text" name="strength" value="{{ old('strength', $item->strength) }}" class="mt-1 block w-full rounded-md border-zinc-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">Form</label>
                            <input type="text" name="form" value="{{ old('form', $item->form) }}" class="mt-1 block w-full rounded-md border-zinc-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">Unit</label>
                            <input type="text" name="unit" value="{{ old('unit', $item->unit) }}" class="mt-1 block w-full rounded-md border-zinc-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">Reorder Level</label>
                            <input type="number" name="reorder_level" min="0" value="{{ old('reorder_level', $item->reorder_level) }}" class="mt-1 block w-full rounded-md border-zinc-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">Active</label>
                            <select name="is_active" class="mt-1 block w-full rounded-md border-zinc-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100">
                                <option value="1" {{ old('is_active', $item->is_active) ? 'selected' : '' }}>Yes</option>
                                <option value="0" {{ !old('is_active', $item->is_active) ? 'selected' : '' }}>No</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">Manufacturer</label>
                            <input type="text" name="manufacturer" value="{{ old('manufacturer', $item->manufacturer) }}" class="mt-1 block w-full rounded-md border-zinc-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">Batch Number</label>
                            <input type="text" name="batch_number" value="{{ old('batch_number', $item->batch_number) }}" class="mt-1 block w-full rounded-md border-zinc-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">Expiry Date</label>
                            <input type="date" name="expiry_date" value="{{ old('expiry_date', optional($item->expiry_date)->format('Y-m-d')) }}" class="mt-1 block w-full rounded-md border-zinc-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">Requires Cold Chain</label>
                            <select name="requires_cold_chain" class="mt-1 block w-full rounded-md border-zinc-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100">
                                <option value="0" {{ old('requires_cold_chain', $item->requires_cold_chain) ? '' : 'selected' }}>No</option>
                                <option value="1" {{ old('requires_cold_chain', $item->requires_cold_chain) ? 'selected' : '' }}>Yes</option>
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">Storage Location</label>
                            <input type="text" name="storage_location" value="{{ old('storage_location', $item->storage_location) }}" class="mt-1 block w-full rounded-md border-zinc-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">Notes</label>
                        <textarea name="notes" rows="3" class="mt-1 block w-full rounded-md border-zinc-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100">{{ old('notes', $item->notes) }}</textarea>
                    </div>

                    <div class="flex justify-end">
                        <button class="inline-flex items-center rounded-md bg-violet-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-violet-700 focus:outline-none focus:ring-2 focus:ring-violet-500">Save Changes</button>
                    </div>
                </form>
            </div>

            <div class="rounded-xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
                <h2 class="text-sm font-semibold text-zinc-700 dark:text-zinc-200">Adjust Stock</h2>
                <p class="mt-2 text-sm">Current on hand: <strong class="text-zinc-900 dark:text-zinc-100">{{ $item->quantity_on_hand }}</strong></p>
                <form method="POST" action="{{ route('staff.inventory.adjust', $item) }}" class="mt-4 space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">Type</label>
                            <select name="type" class="mt-1 block w-full rounded-md border-zinc-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100" required>
                                <option value="receive">Receive</option>
                                <option value="dispense">Dispense</option>
                                <option value="wastage">Wastage</option>
                                <option value="adjust">Adjust</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">Quantity</label>
                            <input type="number" name="quantity" min="1" value="1" required class="mt-1 block w-full rounded-md border-zinc-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">Direction (only for Adjust)</label>
                            <select name="direction" class="mt-1 block w-full rounded-md border-zinc-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100">
                                <option value="increase">Increase</option>
                                <option value="decrease">Decrease</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">Notes</label>
                        <textarea name="notes" rows="2" class="mt-1 block w-full rounded-md border-zinc-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100"></textarea>
                    </div>
                    <div class="flex justify-end">
                        <button class="inline-flex items-center rounded-md border border-zinc-300 px-3 py-1.5 text-sm font-medium text-zinc-700 hover:bg-zinc-50 dark:border-zinc-600 dark:text-zinc-200 dark:hover:bg-zinc-800">Apply</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>
