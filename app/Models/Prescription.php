<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    use HasFactory;

    protected $fillable = [
        'medical_record_id',
        'prescribed_by',
        'type',
        'name',
        'instructions',
        'start_date',
        'end_date',
        'dosage',
        'frequency',
        'route',
        'treatment_schedule',
        'status',
        'notes',
        // e-prescription fields
        'erx_enabled',
        'erx_status',
        'erx_external_id',
        'erx_submitted_at',
        'erx_pharmacy',
        'erx_error',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'erx_enabled' => 'boolean',
        'erx_submitted_at' => 'datetime',
    ];

    public function medicalRecord()
    {
        return $this->belongsTo(MedicalRecord::class);
    }

    public function prescriber()
    {
        return $this->belongsTo(User::class, 'prescribed_by');
    }
}