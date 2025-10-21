<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use App\Models\Appointment;
use App\Models\Payment;
use App\Models\VisitType;

return new class extends Migration
{
    public function up(): void
    {
        DB::transaction(function () {
            $appointments = Appointment::whereNotIn('id', Payment::select('appointment_id'))->get();

            foreach ($appointments as $appointment) {
                $type = VisitType::where('slug', $appointment->visit_type)->first();
                $amount = $type ? (int) $type->amount_cents : 1000 * 100;

                Payment::create([
                    'user_id' => $appointment->user_id,
                    'appointment_id' => $appointment->id,
                    'amount' => $amount,
                    'currency' => 'PHP',
                    'status' => 'pending',
                    'provider' => 'manual',
                    'payment_method' => null,
                    'metadata' => [
                        'appointment_visit_type' => $appointment->visit_type,
                        'created_by' => 'system:backfill',
                    ],
                ]);
            }
        });
    }

    public function down(): void
    {
        // Intentionally left blank: do not delete backfilled payments.
    }
};