<?php

namespace App\Http\Requests\Appointment;

use Illuminate\Foundation\Http\FormRequest;

class StoreAppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Avoid Intelephense union-type warning on auth() by using FormRequest API
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'scheduled_date' => ['required', 'date', 'after_or_equal:today'],
            'scheduled_time' => ['required', 'date_format:H:i', 'in:09:00,09:30,10:00,10:30,11:00,11:30,12:00,12:30,13:00,13:30,14:00,14:30,15:00'],
            'visit_type' => ['required', 'in:well_visit,sick_visit,follow_up,immunization,consultation'],
            'reason' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],

            // symptom checkboxes
            'fever' => ['sometimes', 'boolean'],
            'cough' => ['sometimes', 'boolean'],
            'rash' => ['sometimes', 'boolean'],
            'ear_pain' => ['sometimes', 'boolean'],
            'stomach_pain' => ['sometimes', 'boolean'],
            'diarrhea' => ['sometimes', 'boolean'],
            'vomiting' => ['sometimes', 'boolean'],
            'headaches' => ['sometimes', 'boolean'],
            'trouble_breathing' => ['sometimes', 'boolean'],
            'symptom_other' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'scheduled_date.required' => __('Please select a date for your appointment.'),
            'scheduled_time.required' => __('Please select a time for your appointment.'),
            'visit_type.required' => __('Please pick an appointment type.'),
        ];
    }
}