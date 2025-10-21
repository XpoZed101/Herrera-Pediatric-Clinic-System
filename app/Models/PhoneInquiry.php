<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PhoneInquiry extends Model
{
    protected $fillable = [
        'patient_id',
        'caller_name',
        'caller_phone',
        'reason',
        'triage_level',
        'action',
        'callback_date',
        'status',
        'appointment_id',
        'assigned_to_id',
        'notes',
    ];

    protected $casts = [
        'callback_date' => 'date',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to_id');
    }

    public function getTriageBadgeColorAttribute(): string
    {
        return match ($this->triage_level) {
            'emergency' => 'bg-red-100 text-red-700',
            'urgent' => 'bg-amber-100 text-amber-700',
            default => 'bg-green-100 text-green-700',
        };
    }

    public function scopeDueToday($query)
    {
        return $query->whereDate('callback_date', today())
            ->whereIn('status', ['open', 'awaiting_callback']);
    }
}
