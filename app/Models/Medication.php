<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medication extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'name',
        'details',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}