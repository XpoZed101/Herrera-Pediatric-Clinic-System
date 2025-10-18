<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdditionalNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'notes',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}