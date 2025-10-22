<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WaitlistEntry extends Model
{
    protected $fillable = [
        'patient_id',
        'appointment_id',
        'triage_level',
        'priority',
        'desired_date_start',
        'desired_date_end',
        'status',
        'notes',
    ];

    protected $casts = [
        'priority' => 'integer',
        'desired_date_start' => 'date',
        'desired_date_end' => 'date',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['waiting', 'invited']);
    }

    public function getTriageBadgeColorAttribute(): string
    {
        return match ($this->triage_level) {
            'emergency' => 'bg-red-100 text-red-700',
            'urgent' => 'bg-amber-100 text-amber-700',
            default => 'bg-green-100 text-green-700',
        };
    }
}