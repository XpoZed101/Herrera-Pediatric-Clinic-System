<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; 
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'child_name',
        'date_of_birth',
        'age',
        'sex',
    ];

    public function guardian()
    {
        return $this->hasOne(Guardian::class);
    }

    public function emergencyContact()
    {
        return $this->hasOne(EmergencyContact::class);
    }

    public function medications()
    {
        return $this->hasMany(Medication::class);
    }

    public function allergies()
    {
        return $this->hasMany(Allergy::class);
    }

    public function pastMedicalConditions()
    {
        return $this->hasMany(PastMedicalCondition::class);
    }

    public function immunization()
    {
        return $this->hasOne(Immunization::class);
    }

    public function developmentConcerns()
    {
        return $this->hasMany(DevelopmentConcern::class);
    }

    public function currentSymptoms()
    {
        return $this->hasMany(CurrentSymptom::class);
    }

    public function additionalNote()
    {
        return $this->hasOne(AdditionalNote::class);
    }

    public function consultations()
    {
        return $this->hasMany(Consultation::class);
    }
}