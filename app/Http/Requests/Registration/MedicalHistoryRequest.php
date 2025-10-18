<?php

namespace App\Http\Requests\Registration;

use Illuminate\Foundation\Http\FormRequest;

class MedicalHistoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'medications' => ['nullable', 'string'],
            'allergies' => ['nullable', 'string'],
            'past_conditions' => ['array'],
            'past_conditions.*' => ['in:asthma,ear_infections,eczema,seizures,heart_problems,adhd,autism,diabetes,developmental_delays,other'],
            'immunizations_status' => ['required', 'in:yes,no,not_sure'],
        ];
    }
}