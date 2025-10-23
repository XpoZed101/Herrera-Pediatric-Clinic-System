<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\InventoryItem;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InventoryController extends Controller
{
    public function index()
    {
        $items = InventoryItem::query()
            ->orderBy('name')
            ->paginate(20);

        return view('staff.inventory.index', compact('items'));
    }

    public function create()
    {
        return view('staff.inventory.create');
    }

    public function show(InventoryItem $item)
    {
        return view('staff.inventory.show', compact('item'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:medicine,vaccine'],
            'strength' => ['nullable', 'string', 'max:255'],
            'form' => ['nullable', 'string', 'max:255'],
            'unit' => ['nullable', 'string', 'max:50'],
            'quantity_on_hand' => ['required', 'integer', 'min:0'],
            'reorder_level' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
            'manufacturer' => ['nullable', 'string', 'max:255'],
            'batch_number' => ['nullable', 'string', 'max:255'],
            'expiry_date' => ['nullable', 'date'],
            'requires_cold_chain' => ['nullable', 'boolean'],
            'storage_location' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ]);

        $data['is_active'] = (bool)($data['is_active'] ?? true);
        $data['requires_cold_chain'] = (bool)($data['requires_cold_chain'] ?? false);

        $item = InventoryItem::create($data);

        return redirect()->route('staff.inventory.index')->with('status', 'Inventory item created.');
    }

    public function edit(InventoryItem $item)
    {
        return view('staff.inventory.edit', compact('item'));
    }

    public function update(Request $request, InventoryItem $item)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:medicine,vaccine'],
            'strength' => ['nullable', 'string', 'max:255'],
            'form' => ['nullable', 'string', 'max:255'],
            'unit' => ['nullable', 'string', 'max:50'],
            'reorder_level' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
            'manufacturer' => ['nullable', 'string', 'max:255'],
            'batch_number' => ['nullable', 'string', 'max:255'],
            'expiry_date' => ['nullable', 'date'],
            'requires_cold_chain' => ['nullable', 'boolean'],
            'storage_location' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ]);

        $data['is_active'] = (bool)($data['is_active'] ?? true);

        $item->update($data);

        return redirect()->route('staff.inventory.edit', $item)->with('status_updated', 'Inventory item updated.');
    }

    public function adjust(Request $request, InventoryItem $item)
    {
        $data = $request->validate([
            'type' => ['required', 'in:receive,dispense,adjust,wastage'],
            'quantity' => ['required', 'integer', 'min:1'],
            'notes' => ['nullable', 'string'],
        ]);

        $delta = 0;
        switch ($data['type']) {
            case 'receive':
                $delta = $data['quantity'];
                break;
            case 'dispense':
            case 'wastage':
                $delta = -$data['quantity'];
                break;
            case 'adjust':
                // Allow negative adjustment via optional 'direction' param
                $direction = $request->input('direction', 'increase');
                $delta = ($direction === 'decrease') ? -$data['quantity'] : $data['quantity'];
                break;
        }

        $newQty = max(0, (int)$item->quantity_on_hand + $delta);
        $item->quantity_on_hand = $newQty;
        $item->save();

        StockMovement::create([
            'inventory_item_id' => $item->id,
            'type' => $data['type'],
            'quantity' => $data['quantity'],
            'notes' => $data['notes'] ?? null,
            'performed_by' => Auth::id(),
        ]);

        return redirect()->route('staff.inventory.edit', $item)->with('status_updated', 'Stock adjusted.');
    }
}
