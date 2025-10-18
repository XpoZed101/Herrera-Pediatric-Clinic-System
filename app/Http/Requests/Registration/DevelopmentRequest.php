<?php

namespace App\Http\Requests\Registration;

use Illuminate\Foundation\Http\FormRequest;

class DevelopmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'concerns' => ['array'],
            'concerns.*' => ['in:speech_language,walking_movement,learning,behavior,social_skills,no_concerns'],
            'notes' => ['nullable', 'string'],
        ];
    }
}