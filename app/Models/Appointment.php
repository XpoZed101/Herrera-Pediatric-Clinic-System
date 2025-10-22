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
        // check-in/out
        'checked_in_at',
        'checked_out_at',
        'checked_in_by',
        'checked_out_by',
        // symptom flags (match DB schema)
        'fever',
        'cough',
        'vomiting',
        'diarrhea',
        'rash',
        'ear_pain',
        'stomach_pain',
        'headaches',
        'trouble_breathing',
        'symptom_other',
        'reschedule_count',
        // queue management
        'queue_position',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'checked_in_at' => 'datetime',
        'checked_out_at' => 'datetime',
        'checked_in_by' => 'integer',
        'checked_out_by' => 'integer',
        'fever' => 'boolean',
        'cough' => 'boolean',
        'vomiting' => 'boolean',
        'diarrhea' => 'boolean',
        'rash' => 'boolean',
        'ear_pain' => 'boolean',
        'stomach_pain' => 'boolean',
        'headaches' => 'boolean',
        'trouble_breathing' => 'boolean',
        'reschedule_count' => 'integer',
        // queue management
        'queue_position' => 'integer',
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

    public function payments()
    {
        return $this->hasMany(\App\Models\Payment::class);
    }
}
