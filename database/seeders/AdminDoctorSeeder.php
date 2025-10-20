<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminDoctorSeeder extends Seeder
{
    public function run(): void
    {
        $accounts = [
            [
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => 'password',
            ],
            [
                'name' => 'Doctor User',
                'email' => 'doctor@example.com',
                'password' => 'password',
            ],
        ];

        foreach ($accounts as $data) {
            User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make($data['password']),
                    'role' => 'admin', // Admin and doctor share the same role
                    'email_verified_at' => now(),
                ]
            );
        }
    }
}
