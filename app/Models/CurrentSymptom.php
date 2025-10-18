<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurrentSymptom extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'symptom_type',
        'other_name',
        'details',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}