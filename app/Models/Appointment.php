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
        // symptom flags
        'has_fever',
        'has_cough',
        'has_vomiting',
        'has_diarrhea',
        'has_rash',
        'has_diff_breathing',
        'has_dehydration',
        'has_pain',
        'reschedule_count',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'checked_in_at' => 'datetime',
        'checked_out_at' => 'datetime',
        'checked_in_by' => 'integer',
        'checked_out_by' => 'integer',
        'has_fever' => 'boolean',
        'has_cough' => 'boolean',
        'has_vomiting' => 'boolean',
        'has_diarrhea' => 'boolean',
        'has_rash' => 'boolean',
        'has_diff_breathing' => 'boolean',
        'has_dehydration' => 'boolean',
        'has_pain' => 'boolean',
        'reschedule_count' => 'integer',
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
