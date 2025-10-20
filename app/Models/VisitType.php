<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisitType extends Model
{
    protected $fillable = [
        'slug',
        'name',
        'amount_cents',
        'is_active',
        'description',
    ];

    protected $casts = [
        'amount_cents' => 'integer',
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
