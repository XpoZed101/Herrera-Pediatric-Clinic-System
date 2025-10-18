<?php

namespace App\Http\Requests\Registration;

use Illuminate\Foundation\Http\FormRequest;

class SymptomsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'symptom_types' => ['array'],
            'symptom_types.*' => ['in:fever,cough,rash,ear_pain,stomach_pain,diarrhea,vomiting,headaches,trouble_breathing,other'],
            'symptom_details' => ['nullable', 'string'],
        ];
    }
}