<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class PatientRepository
{
    public function createRegistration(array $data): void
    {
        DB::transaction(function () use ($data) {
            $patientId = DB::table('patients')->insertGetId([
                'child_name' => $data['child']['child_name'] ?? null,
                'date_of_birth' => $data['child']['date_of_birth'] ?? null,
                'sex' => $data['child']['sex'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            if (!empty($data['guardian']['name'])) {
                DB::table('guardians')->insert([
                    'patient_id' => $patientId,
                    'name' => $data['guardian']['name'] ?? null,
                    'phone' => $data['guardian']['phone'] ?? null,
                    'email' => $data['guardian']['email'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            if (!empty($data['emergency']['name']) && !empty($data['emergency']['phone'])) {
                DB::table('emergency_contacts')->insert([
                    'patient_id' => $patientId,
                    'name' => $data['emergency']['name'] ?? null,
                    'phone' => $data['emergency']['phone'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Medications
            if (!empty($data['medical']['medications'])) {
                $meds = $this->normalizeLines($data['medical']['medications']);
                foreach ($meds as $name) {
                    DB::table('medications')->insert([
                        'patient_id' => $patientId,
                        'name' => $name,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            // Allergies
            if (!empty($data['medical']['allergies'])) {
                $allergies = $this->normalizeLines($data['medical']['allergies']);
                foreach ($allergies as $name) {
                    DB::table('allergies')->insert([
                        'patient_id' => $patientId,
                        'name' => $name,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            // Past medical conditions
            if (!empty($data['medical']['past_conditions'])) {
                foreach ((array)$data['medical']['past_conditions'] as $type) {
                    DB::table('past_medical_conditions')->insert([
                        'patient_id' => $patientId,
                        'condition_type' => $type,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            // Immunizations status
            if (!empty($data['medical']['immunizations_status'])) {
                DB::table('immunizations')->insert([
                    'patient_id' => $patientId,
                    'status' => $data['medical']['immunizations_status'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Development concerns and notes
            if (!empty($data['development']['concerns'])) {
                foreach ((array)$data['development']['concerns'] as $area) {
                    DB::table('development_concerns')->insert([
                        'patient_id' => $patientId,
                        'area' => $area,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
            if (!empty($data['development']['notes'])) {
                DB::table('additional_notes')->insert([
                    'patient_id' => $patientId,
                    'notes' => $data['development']['notes'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Current symptoms
            if (!empty($data['symptoms']['types'])) {
                foreach ((array)$data['symptoms']['types'] as $type) {
                    DB::table('current_symptoms')->insert([
                        'patient_id' => $patientId,
                        'symptom_type' => $type,
                        'details' => $data['symptoms']['details'] ?? null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        });
    }

    private function normalizeLines(string $text): array
    {
        return collect(preg_split('/\r?\n/', $text))
            ->map(fn ($s) => trim($s))
            ->filter()
            ->values()
            ->all();
    }
}