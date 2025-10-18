<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\User;

class AppointmentSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure we have some patients to attach
        if (Patient::count() < 3) {
            Patient::create(['child_name' => 'Juan Dela Cruz', 'date_of_birth' => now()->subYears(6)->toDateString(), 'age' => 6, 'sex' => 'male']);
            Patient::create(['child_name' => 'Maria Santos', 'date_of_birth' => now()->subYears(2)->toDateString(), 'age' => 2, 'sex' => 'female']);
            Patient::create(['child_name' => 'Pedro Reyes', 'date_of_birth' => now()->subYears(10)->toDateString(), 'age' => 10, 'sex' => 'male']);
        }

        $admin = User::where('role', 'admin')->first() ?? User::first();

        $statuses = ['requested', 'scheduled', 'completed', 'cancelled'];
        $visitTypes = ['well_visit', 'sick_visit', 'follow_up', 'immunization', 'consultation'];

        // Create one appointment per status x visit type (20 total)
        foreach ($statuses as $status) {
            foreach ($visitTypes as $type) {
                Appointment::factory()->create([
                    'status' => $status,
                    'visit_type' => $type,
                    'user_id' => $admin?->id,
                    'patient_id' => Patient::inRandomOrder()->value('id'),
                ]);
            }
        }
    }
}