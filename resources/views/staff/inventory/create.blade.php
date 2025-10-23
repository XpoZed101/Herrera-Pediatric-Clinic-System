<x-layouts.app>
    <x-slot:title>
        Add Inventory Item
    </x-slot:title>

    <div class="max-w-6xl mx-auto px-4 py-8">
        <div class="flex items-start justify-between">
            <div>
                <h1 class="text-3xl font-bold tracking-tight text-zinc-900 dark:text-zinc-100">Add Medicine or Vaccine</h1>
                <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">Create an inventory record with details and stock information.</p>
            </div>
            <a href="{{ route('staff.inventory.index') }}" class="inline-flex items-center rounded-md border border-zinc-300 px-4 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-50 dark:border-zinc-600 dark:text-zinc-200 dark:hover:bg-zinc-800">Back to Inventory</a>
        </div>

        <div class="mt-8 rounded-2xl border border-zinc-200 bg-white shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
            <div class="border-b border-zinc-200 px-6 py-4 dark:border-zinc-700">
                <h2 class="text-sm font-semibold text-zinc-900 dark:text-zinc-100">Item Details</h2>
            </div>
            <form method="POST" action="{{ route('staff.inventory.store') }}" class="px-6 py-6">
                @csrf

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" required class="mt-1 block w-full rounded-lg border-zinc-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">Type</label>
                        <select name="type" class="mt-1 block w-full rounded-lg border-zinc-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100" required>
                            <option value="medicine" {{ old('type') === 'medicine' ? 'selected' : '' }}>Medicine</option>
                            <option value="vaccine" {{ old('type') === 'vaccine' ? 'selected' : '' }}>Vaccine</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">Strength</label>
                        <input type="text" name="strength" value="{{ old('strength') }}" class="mt-1 block w-full rounded-lg border-zinc-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">Form</label>
                        <input type="text" name="form" value="{{ old('form') }}" class="mt-1 block w-full rounded-lg border-zinc-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100" placeholder="tablet, syrup, vial">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">Unit</label>
                        <input type="text" name="unit" value="{{ old('unit') }}" class="mt-1 block w-full rounded-lg border-zinc-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100" placeholder="mg, mL">
                    </div>
                </div>

                <div class="mt-8 border-t border-zinc-200 pt-6 dark:border-zinc-700">
                    <h2 class="mb-4 text-sm font-semibold text-zinc-900 dark:text-zinc-100">Vaccine & Storage</h2>
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">Manufacturer</label>
                            <input type="text" name="manufacturer" value="{{ old('manufacturer') }}" class="mt-1 block w-full rounded-lg border-zinc-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">Batch Number</label>
                            <input type="text" name="batch_number" value="{{ old('batch_number') }}" class="mt-1 block w-full rounded-lg border-zinc-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">Expiry Date</label>
                            <input type="date" name="expiry_date" value="{{ old('expiry_date') }}" class="mt-1 block w-full rounded-lg border-zinc-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">Requires Cold Chain</label>
                            <select name="requires_cold_chain" class="mt-1 block w-full rounded-lg border-zinc-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100">
                                <option value="0" {{ old('requires_cold_chain') ? '' : 'selected' }}>No</option>
                                <option value="1" {{ old('requires_cold_chain') ? 'selected' : '' }}>Yes</option>
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">Storage Location</label>
                            <input type="text" name="storage_location" value="{{ old('storage_location') }}" class="mt-1 block w-full rounded-lg border-zinc-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100" placeholder="e.g., Refrigerator A, Shelf B">
                        </div>
                    </div>
                </div>

                <div class="mt-8 border-t border-zinc-200 pt-6 dark:border-zinc-700">
                    <h2 class="mb-4 text-sm font-semibold text-zinc-900 dark:text-zinc-100">Stock & Status</h2>
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                        <div>
                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">Quantity On Hand</label>
                            <input type="number" name="quantity_on_hand" min="0" value="{{ old('quantity_on_hand', 0) }}" class="mt-1 block w-full rounded-lg border-zinc-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">Reorder Level</label>
                            <input type="number" name="reorder_level" min="0" value="{{ old('reorder_level', 0) }}" class="mt-1 block w-full rounded-lg border-zinc-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">Active</label>
                            <select name="is_active" class="mt-1 block w-full rounded-lg border-zinc-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100">
                                <option value="1" {{ old('is_active', 1) ? 'selected' : '' }}>Yes</option>
                                <option value="0" {{ !old('is_active', 1) ? 'selected' : '' }}>No</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="mt-8 border-t border-zinc-200 pt-6 dark:border-zinc-700">
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-200">Notes</label>
                    <textarea name="notes" rows="4" class="mt-1 block w-full rounded-lg border-zinc-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-violet-500 focus:outline-none focus:ring-2 focus:ring-violet-500 dark:border-zinc-600 dark:bg-zinc-800 dark:text-zinc-100">{{ old('notes') }}</textarea>
                </div>

                <div class="mt-8 flex items-center justify-end gap-3">
                    <a href="{{ route('staff.inventory.index') }}" class="inline-flex items-center rounded-md border border-zinc-300 px-4 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-50 dark:border-zinc-600 dark:text-zinc-200 dark:hover:bg-zinc-800">Cancel</a>
                    <button class="inline-flex items-center rounded-md bg-violet-600 px-5 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-violet-700 focus:outline-none focus:ring-2 focus:ring-violet-500">Create Item</button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>