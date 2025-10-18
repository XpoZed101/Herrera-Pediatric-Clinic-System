<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'patient_id',
        'scheduled_at',
        'visit_type',
        'reason',
        'status',
        'notes',
        // symptoms
        'fever',
        'cough',
        'rash',
        'ear_pain',
        'stomach_pain',
        'diarrhea',
        'vomiting',
        'headaches',
        'trouble_breathing',
        'symptom_other',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'fever' => 'boolean',
        'cough' => 'boolean',
        'rash' => 'boolean',
        'ear_pain' => 'boolean',
        'stomach_pain' => 'boolean',
        'diarrhea' => 'boolean',
        'vomiting' => 'boolean',
        'headaches' => 'boolean',
        'trouble_breathing' => 'boolean',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function medicalRecord()
    {
        return $this->hasOne(MedicalRecord::class);
    }
}