<?php

namespace App\Services;

use App\Models\Prescription;
use Illuminate\Support\Str;

class EPrescriptionService
{
    /**
     * Submit a medication prescription to an e‑prescription provider.
     * This demo implementation simulates a successful submission.
     *
     * @param Prescription $prescription
     * @param array $payload
     * @return array{status:string, external_id:string, submitted_at:\DateTimeInterface}
     */
    public function submitMedication(Prescription $prescription, array $payload): array
    {
        // In a real integration, call the external API here (e.g., via HTTP client)
        // and map the response.

        $externalId = 'ERX-' . Str::upper(Str::random(10));

        return [
            'status' => 'submitted',
            'external_id' => $externalId,
            'submitted_at' => now(),
        ];
    }
}