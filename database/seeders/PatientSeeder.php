<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class PatientSeeder extends Seeder
{
    public function run(): void
    {
        $accounts = [
            [
                'name' => 'Patient User',
                'email' => 'patient@example.com',
                'password' => 'password',
            ],
            [
                'name' => 'Patient Two',
                'email' => 'patient2@example.com',
                'password' => 'password',
            ],
        ];

        foreach ($accounts as $data) {
            User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make($data['password']),
                    'role' => 'patient',
                    'email_verified_at' => now(),
                ]
            );
        }
    }
}