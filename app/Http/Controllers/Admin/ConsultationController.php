<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Consultation;
use App\Models\Prescription;
use App\Models\VisitType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ConsultationController extends Controller
{
    public function create(Patient $patient): View
    {
        abort_unless(Auth::check() && (Auth::user()->role ?? null) === 'admin', 403);
        // Load recent prescriptions linked to this patient.
        // Support both schemas: by appointment.patient_id OR medical_record.user (guardian/patient account).
        $guardianEmail = optional($patient->guardian)->email;
        $prescriptions = Prescription::with(['medicalRecord.appointment.patient', 'medicalRecord.user', 'prescriber'])
            ->where(function ($q) use ($patient, $guardianEmail) {
                $q->whereHas('medicalRecord.appointment', function ($aq) use ($patient) {
                    $aq->where('patient_id', $patient->id);
                });

                if (!empty($guardianEmail)) {
                    $q->orWhereHas('medicalRecord.user', function ($uq) use ($guardianEmail) {
                        $uq->where('email', $guardianEmail);
                    });
                }
            })
            ->latest('created_at')
            ->limit(50)
            ->get();

        $visitTypes = VisitType::active()->orderBy('name')->get();

        return view('admin.consultations.create', [
            'patient' => $patient,
            'prescriptions' => $prescriptions,
            'visitTypes' => $visitTypes,
        ]);
    }

    public function store(Request $request, Patient $patient): RedirectResponse
    {
        abort_unless(Auth::check() && (Auth::user()->role ?? null) === 'admin', 403);

        $data = $request->validate([
            'conducted_at' => ['nullable', 'date'],
            'visit_type' => ['nullable', 'string', 'exists:visit_types,slug'],
            'chief_complaint' => ['nullable', 'string'],
            'examination' => ['nullable', 'string'],
            'diagnosis' => ['nullable', 'string'],
            'plan' => ['nullable', 'string'],
            'prescriptions' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
        ]);

        $data['patient_id'] = $patient->id;
        Consultation::create($data);

        return redirect()
            ->route('admin.patients.show', $patient)
            ->with('status', 'Consultation recorded successfully.');
    }
}