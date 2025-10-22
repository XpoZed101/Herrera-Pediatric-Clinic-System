<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\WaitlistEntry;

class QueueWaitlistTestSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $date = Carbon::create(2025, 10, 23);

            // Ensure a staff user exists (for linking if needed)
            $staff = User::firstOrCreate(
                ['email' => 'staff.test@example.com'],
                [
                    'name' => 'Staff Test',
                    'password' => Hash::make('password'),
                    // role column exists per migration; set to staff
                    'role' => 'staff',
                ]
            );

            // Create patients
            $p1 = Patient::firstOrCreate([
                'child_name' => 'Juan Dela Cruz Jr.',
                'date_of_birth' => Carbon::create(2018, 5, 10)->toDateString(),
                'sex' => 'male',
            ]);

            $p2 = Patient::firstOrCreate([
                'child_name' => 'Maria Santos',
                'date_of_birth' => Carbon::create(2017, 9, 2)->toDateString(),
                'sex' => 'female',
            ]);

            $p3 = Patient::firstOrCreate([
                'child_name' => 'Kim Lee',
                'date_of_birth' => Carbon::create(2019, 12, 25)->toDateString(),
                'sex' => 'male',
            ]);

            $p4 = Patient::firstOrCreate([
                'child_name' => 'Ava Reyes',
                'date_of_birth' => Carbon::create(2020, 3, 14)->toDateString(),
                'sex' => 'female',
            ]);

            $p5 = Patient::firstOrCreate([
                'child_name' => 'Noah Garcia',
                'date_of_birth' => Carbon::create(2016, 7, 8)->toDateString(),
                'sex' => 'male',
            ]);

            // Appointments on 23/10/2025
            $a1 = Appointment::create([
                'user_id' => $staff->id,
                'patient_id' => $p1->id,
                'scheduled_at' => $date->copy()->setTime(8, 30),
                'visit_type' => 'consultation',
                'reason' => 'Cough and colds',
                'status' => 'scheduled',
                'notes' => 'Bring vaccination card.',
                'fever' => true,
                'cough' => true,
                'queue_position' => 1,
            ]);

            $a2 = Appointment::create([
                'user_id' => $staff->id,
                'patient_id' => $p2->id,
                'scheduled_at' => $date->copy()->setTime(9, 0),
                'visit_type' => 'immunization',
                'reason' => 'MMR vaccine',
                'status' => 'scheduled',
                'notes' => null,
                'queue_position' => 2,
            ]);

            $a3 = Appointment::create([
                'user_id' => $staff->id,
                'patient_id' => $p3->id,
                'scheduled_at' => $date->copy()->setTime(9, 30),
                'visit_type' => 'follow_up',
                'reason' => 'Follow-up for previous visit',
                'status' => 'scheduled',
                'notes' => 'Check recovery status.',
                'queue_position' => 3,
            ]);

            // Waitlist entries targeted for 23/10/2025
            WaitlistEntry::create([
                'patient_id' => $p4->id,
                'appointment_id' => null,
                'triage_level' => 'urgent',
                'priority' => 2,
                'desired_date_start' => $date->toDateString(),
                'desired_date_end' => $date->toDateString(),
                'status' => 'waiting',
                'notes' => 'High fever since last night.',
            ]);

            WaitlistEntry::create([
                'patient_id' => $p5->id,
                'appointment_id' => null,
                'triage_level' => 'routine',
                'priority' => 3,
                'desired_date_start' => $date->toDateString(),
                'desired_date_end' => $date->toDateString(),
                'status' => 'waiting',
                'notes' => 'Routine consultation; flexible within the day.',
            ]);
        });
    }
}