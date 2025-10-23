<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'strength',
        'form',
        'unit',
        'quantity_on_hand',
        'reorder_level',
        'is_active',
        'manufacturer',
        'batch_number',
        'expiry_date',
        'requires_cold_chain',
        'storage_location',
        'notes',
    ];

    protected $casts = [
        'quantity_on_hand' => 'integer',
        'reorder_level' => 'integer',
        'is_active' => 'boolean',
        'requires_cold_chain' => 'boolean',
        'expiry_date' => 'date',
    ];

    public function movements()
    {
        return $this->hasMany(StockMovement::class);
    }
}
