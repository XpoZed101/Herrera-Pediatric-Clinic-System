<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\MedicalRecord;
use App\Models\Diagnosis;
use App\Models\Guardian;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Barryvdh\DomPDF\Facade\Pdf;

class MedicalRecordController extends Controller
{
    public function index(): \Illuminate\View\View
    {
        abort_unless(Auth::check() && (Auth::user()->role ?? null) === 'admin', 403);

        $records = MedicalRecord::with(['user', 'appointment.patient'])
            ->withCount('prescriptions')
            ->latest('conducted_at')
            ->paginate(15);

        return view('admin.medical-records.index', compact('records'));
    }
    public function create(Appointment $appointment): View
    {
        abort_unless(Auth::check() && (Auth::user()->role ?? null) === 'admin', 403);

        $appointment->load(['user', 'patient', 'medicalRecord']);
        return view('admin.medical-records.create-appointment', [
            'appointment' => $appointment,
            'user' => $appointment->user,
        ]);
    }

    public function store(Request $request, Appointment $appointment): RedirectResponse
    {
        abort_unless(Auth::check() && (Auth::user()->role ?? null) === 'admin', 403);

        $data = $request->validate([
            'conducted_at' => ['nullable', 'date'],
            'chief_complaint' => ['nullable', 'string'],
            'examination' => ['nullable', 'string'],
            'plan' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
            // diagnoses fields (optional single entry for simplicity)
            'diagnosis_title' => ['nullable', 'string', 'max:255'],
            'diagnosis_severity' => ['nullable', 'string', 'max:255'],
            'diagnosis_icd_code' => ['nullable', 'string', 'max:255'],
            'diagnosis_description' => ['nullable', 'string'],
        ]);

        $record = MedicalRecord::create([
            'appointment_id' => $appointment->id,
            'user_id' => $appointment->user_id,
            'conducted_at' => $data['conducted_at'] ?? null,
            'chief_complaint' => $data['chief_complaint'] ?? null,
            'examination' => $data['examination'] ?? null,
            'plan' => $data['plan'] ?? null,
            'notes' => $data['notes'] ?? null,
        ]);

        // Optionally create a single diagnosis if provided
        if (!empty($data['diagnosis_title'])) {
            Diagnosis::create([
                'medical_record_id' => $record->id,
                'title' => $data['diagnosis_title'],
                'severity' => $data['diagnosis_severity'] ?? null,
                'icd_code' => $data['diagnosis_icd_code'] ?? null,
                'description' => $data['diagnosis_description'] ?? null,
            ]);
        }

        return redirect()
            ->route('admin.appointments.show', $appointment)
            ->with('status_updated', 'Medical record created successfully.');
    }

    public function edit(MedicalRecord $medicalRecord): View
    {
        abort_unless(Auth::check() && (Auth::user()->role ?? null) === 'admin', 403);

        $medicalRecord->load(['appointment.user', 'appointment.patient', 'diagnoses']);
        return view('admin.medical-records.edit', [
            'medicalRecord' => $medicalRecord,
            'appointment' => $medicalRecord->appointment,
            'user' => $medicalRecord->user,
        ]);
    }

    public function show(MedicalRecord $medicalRecord): View
    {
        abort_unless(Auth::check() && (Auth::user()->role ?? null) === 'admin', 403);

        $medicalRecord->load(['appointment.user', 'appointment.patient', 'diagnoses', 'prescriptions']);

        // If appointment has no patient relation (legacy data), try to resolve via guardian email
        $appointment = $medicalRecord->appointment;
        if ($appointment && empty($appointment->patient)) {
            $guardian = Guardian::where('email', optional($medicalRecord->user)->email)->with('patient')->first();
            if ($guardian && $guardian->patient) {
                $appointment->setRelation('patient', $guardian->patient);
            }
        }

        return view('admin.medical-records.show', [
            'medicalRecord' => $medicalRecord,
            'appointment' => $appointment,
            'user' => $medicalRecord->user,
        ]);
    }

    /**
     * Show selector to start creating a medical record by choosing an appointment.
     */
    public function createSelector(): View
    {
        abort_unless(Auth::check() && (Auth::user()->role ?? null) === 'admin', 403);

        $appointments = Appointment::with(['user', 'patient'])
            ->doesntHave('medicalRecord')
            ->latest('scheduled_at')
            ->paginate(15);

        return view('admin.medical-records.create', compact('appointments'));
    }

    /**
     * Redirect to appointment-scoped create route after selecting an appointment.
     */
    public function startCreate(Request $request): RedirectResponse
    {
        abort_unless(Auth::check() && (Auth::user()->role ?? null) === 'admin', 403);

        $validated = $request->validate([
            'appointment_id' => ['required', 'integer', 'exists:appointments,id'],
        ]);

        $appointment = Appointment::findOrFail($validated['appointment_id']);
        return redirect()->route('admin.appointments.medical-record.create', $appointment);
    }

    public function update(Request $request, MedicalRecord $medicalRecord): RedirectResponse
    {
        abort_unless(Auth::check() && (Auth::user()->role ?? null) === 'admin', 403);

        $data = $request->validate([
            'conducted_at' => ['nullable', 'date'],
            'chief_complaint' => ['nullable', 'string'],
            'examination' => ['nullable', 'string'],
            'plan' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
            // one diagnosis update or addition
            'diagnosis_title' => ['nullable', 'string', 'max:255'],
            'diagnosis_severity' => ['nullable', 'string', 'max:255'],
            'diagnosis_icd_code' => ['nullable', 'string', 'max:255'],
            'diagnosis_description' => ['nullable', 'string'],
        ]);

        $medicalRecord->fill([
            'conducted_at' => $data['conducted_at'] ?? null,
            'chief_complaint' => $data['chief_complaint'] ?? null,
            'examination' => $data['examination'] ?? null,
            'plan' => $data['plan'] ?? null,
            'notes' => $data['notes'] ?? null,
        ])->save();

        // Upsert single diagnosis for simplicity
        if (!empty($data['diagnosis_title'])) {
            $diagnosis = $medicalRecord->diagnoses()->first();
            if ($diagnosis) {
                $diagnosis->fill([
                    'title' => $data['diagnosis_title'],
                    'severity' => $data['diagnosis_severity'] ?? null,
                    'icd_code' => $data['diagnosis_icd_code'] ?? null,
                    'description' => $data['diagnosis_description'] ?? null,
                ])->save();
            } else {
                $medicalRecord->diagnoses()->create([
                    'title' => $data['diagnosis_title'],
                    'severity' => $data['diagnosis_severity'] ?? null,
                    'icd_code' => $data['diagnosis_icd_code'] ?? null,
                    'description' => $data['diagnosis_description'] ?? null,
                ]);
            }
        }

        return redirect()
            ->route('admin.appointments.show', $medicalRecord->appointment)
            ->with('status_updated', 'Medical record updated successfully.');
    }

    /**
     * Generate PDF Medical Certificate for the given medical record.
     */
    public function certificatePdf(MedicalRecord $medicalRecord)
    {
        abort_unless(Auth::check() && (Auth::user()->role ?? null) === 'admin', 403);

        $medicalRecord->load(['appointment.user', 'appointment.patient']);
        // Issuer is the currently authenticated admin/doctor, not the guardian
        $issuer = Auth::user();

        $pdf = Pdf::loadView('admin.documents.certificate', [
            'record' => $medicalRecord,
            'issuer' => $issuer,
            'appName' => 'Pediatric Clinic',
        ])->setPaper('a4');

        return $pdf->stream('medical-certificate-' . $medicalRecord->id . '.pdf');
    }

    /**
     * Generate PDF Medical Clearance for the given medical record.
     */
    public function clearancePdf(MedicalRecord $medicalRecord)
    {
        abort_unless(Auth::check() && (Auth::user()->role ?? null) === 'admin', 403);

        $medicalRecord->load(['appointment.user', 'appointment.patient']);
        // Issuer is the currently authenticated admin/doctor, not the guardian
        $issuer = Auth::user();

        $pdf = Pdf::loadView('admin.documents.clearance', [
            'record' => $medicalRecord,
            'issuer' => $issuer,
            'appName' => 'Pediatric Clinic',
        ])->setPaper('a4');

        return $pdf->stream('medical-clearance-' . $medicalRecord->id . '.pdf');
    }
}