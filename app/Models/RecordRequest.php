<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecordRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'user_id',
        'medical_record_id',
        'record_type',
        'date_start',
        'date_end',
        'delivery_method',
        'delivery_email',
        'purpose',
        'notes',
        'status',
    ];

    protected $casts = [
        'date_start' => 'date',
        'date_end' => 'date',
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
        return $this->belongsTo(MedicalRecord::class);
    }
}
