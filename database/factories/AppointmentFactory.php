<?php

namespace Database\Factories;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Appointment>
 */
class AppointmentFactory extends Factory
{
    protected $model = Appointment::class;

    public function definition(): array
    {
        $statuses = ['requested', 'scheduled', 'completed', 'cancelled'];
        $visitTypes = ['well_visit', 'sick_visit', 'follow_up', 'immunization', 'consultation'];

        $patient = Patient::inRandomOrder()->first();
        $user = User::where('role', 'admin')->inRandomOrder()->first() ?? User::inRandomOrder()->first();

        return [
            'user_id' => $user?->id,
            'patient_id' => $patient?->id,
            'scheduled_at' => now()->addDays($this->faker->numberBetween(0, 14))->addHours($this->faker->numberBetween(8, 17)),
            'visit_type' => $this->faker->randomElement($visitTypes),
            'reason' => $this->faker->sentence(),
            'status' => $this->faker->randomElement($statuses),
            'notes' => $this->faker->optional()->paragraph(),
            'fever' => $this->faker->boolean(25),
            'cough' => $this->faker->boolean(25),
            'rash' => $this->faker->boolean(15),
            'ear_pain' => $this->faker->boolean(15),
            'stomach_pain' => $this->faker->boolean(15),
            'diarrhea' => $this->faker->boolean(10),
            'vomiting' => $this->faker->boolean(10),
            'headaches' => $this->faker->boolean(20),
            'trouble_breathing' => $this->faker->boolean(5),
            'symptom_other' => $this->faker->optional()->word(),
        ];
    }
}