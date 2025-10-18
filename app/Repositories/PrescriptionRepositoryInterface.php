<?php

namespace App\Repositories;

use App\Models\Prescription;

interface PrescriptionRepositoryInterface
{
    public function create(array $data): Prescription;
    public function update(Prescription $prescription, array $data): Prescription;
    public function findById(int $id): ?Prescription;
    public function forMedicalRecord(int $medicalRecordId): \Illuminate\Contracts\Pagination\LengthAwarePaginator;
    public function delete(Prescription $prescription): void;
}