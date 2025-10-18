<?php

namespace App\Http\Requests\Registration;

use Illuminate\Foundation\Http\FormRequest;

class ChildInfoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'child_name' => ['required', 'string', 'max:255'],
            'date_of_birth' => ['required', 'date'],
            'sex' => ['required', 'in:male,female'],
            'guardian_name' => ['nullable', 'string', 'max:255'],
            // If provided, must be exactly 11 digits
            'guardian_phone' => ['nullable', 'regex:/^\d{11}$/'],
            // Require email to create login account
            'guardian_email' => ['required', 'email', 'max:255'],
            // Require password for login account
            'password' => ['required', 'string', 'min:8'],
            // Role defaults to patient; accept only patient
            'role' => ['nullable', 'in:patient'],
            'emergency_name' => ['nullable', 'string', 'max:255'],
            // Require phone if emergency name is present; enforce 11 digits
            'emergency_phone' => ['nullable', 'required_with:emergency_name', 'regex:/^\d{11}$/'],
        ];
    }
}