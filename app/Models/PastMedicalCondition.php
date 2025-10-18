<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PastMedicalCondition extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'condition_type',
        'other_name',
        'notes',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}