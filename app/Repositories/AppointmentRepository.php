<?php

namespace App\Repositories;

use App\Models\Appointment;
use Illuminate\Support\Facades\DB;

class AppointmentRepository
{
    public function create(array $data): Appointment
    {
        return DB::transaction(function () use ($data) {
            return Appointment::create([
                'user_id' => $data['user_id'] ?? null,
                'patient_id' => $data['patient_id'] ?? null,
                'scheduled_at' => $data['scheduled_at'],
                'visit_type' => $data['visit_type'],
                'reason' => $data['reason'] ?? null,
                'status' => $data['status'] ?? 'requested',
                'notes' => $data['notes'] ?? null,
                // symptoms
                'fever' => (bool)($data['fever'] ?? false),
                'cough' => (bool)($data['cough'] ?? false),
                'rash' => (bool)($data['rash'] ?? false),
                'ear_pain' => (bool)($data['ear_pain'] ?? false),
                'stomach_pain' => (bool)($data['stomach_pain'] ?? false),
                'diarrhea' => (bool)($data['diarrhea'] ?? false),
                'vomiting' => (bool)($data['vomiting'] ?? false),
                'headaches' => (bool)($data['headaches'] ?? false),
                'trouble_breathing' => (bool)($data['trouble_breathing'] ?? false),
                'symptom_other' => $data['symptom_other'] ?? null,
            ]);
        });
    }

    public function hasPendingForUser(int $userId): bool
    {
        return Appointment::where('user_id', $userId)
            ->where('status', 'requested')
            ->exists();
    }

    public function hasNotCompletedForUser(int $userId): bool
    {
        return Appointment::where('user_id', $userId)
            ->where('status', '!=', 'completed')
            ->exists();
    }
}