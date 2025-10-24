<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\MedicalRecord;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PatientController extends Controller
{
    public function index(): View
    {
        abort_unless(Auth::check() && (Auth::user()->role ?? null) === 'admin', 403);

        $patients = Patient::with([
            'guardian',
            'emergencyContact',
        ])->latest()->paginate(15);

        return view('admin.patients.index', compact('patients'));
    }

    public function destroy(Patient $patient): RedirectResponse
    {
        abort_unless(Auth::check() && (Auth::user()->role ?? null) === 'admin', 403);
        $patient->delete();
        return redirect()->route('admin.patients.index')->with('status', 'Patient and related records deleted.');
    }

    public function show(Patient $patient): View
    {
        abort_unless(Auth::check() && (Auth::user()->role ?? null) === 'admin', 403);
        $patient->load([
            'guardian',
            'emergencyContact',
            'medications',
            'allergies',
            'pastMedicalConditions',
            'immunization',
            'developmentConcerns',
            'currentSymptoms',
            'additionalNote',
        ]);

        // Load medical records tied to this patient via appointments
        $medicalRecords = MedicalRecord::with(['appointment.user', 'diagnoses'])
            ->whereHas('appointment', function ($q) use ($patient) {
                $q->where('patient_id', $patient->id);
            })
            ->latest('conducted_at')
            ->get();

        // Load all appointments for this patient
        $appointments = Appointment::with(['user', 'medicalRecord'])
            ->where('patient_id', $patient->id)
            ->orderBy('scheduled_at', 'desc')
            ->get();

        return view('admin.patients.show', compact('patient', 'medicalRecords', 'appointments'));
    }
}