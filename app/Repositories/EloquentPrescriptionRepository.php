<?php

namespace App\Repositories;

use App\Models\Prescription;
use Illuminate\Support\Facades\DB;

class EloquentPrescriptionRepository implements PrescriptionRepositoryInterface
{
    public function create(array $data): Prescription
    {
        return DB::transaction(function () use ($data) {
            return Prescription::create($data);
        });
    }

    public function update(Prescription $prescription, array $data): Prescription
    {
        return DB::transaction(function () use ($prescription, $data) {
            $prescription->fill($data)->save();
            return $prescription;
        });
    }

    public function findById(int $id): ?Prescription
    {
        return Prescription::query()->find($id);
    }

    public function forMedicalRecord(int $medicalRecordId): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return Prescription::query()
            ->where('medical_record_id', $medicalRecordId)
            ->latest('created_at')
            ->paginate(15);
    }

    public function delete(Prescription $prescription): void
    {
        DB::transaction(function () use ($prescription) {
            $prescription->delete();
        });
    }
}