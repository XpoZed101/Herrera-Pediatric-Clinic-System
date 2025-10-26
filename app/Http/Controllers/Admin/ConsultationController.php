<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use App\Models\Patient;
use App\Models\Prescription;
use App\Models\VisitType;
use App\Models\ConsultationAttachment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ConsultationController extends Controller
{
    public function create(Patient $patient): View
    {
        abort_unless(Auth::check() && (Auth::user()->role ?? null) === 'admin', 403);
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
            'attachments' => ['nullable', 'array'],
            'attachments.*' => ['file', 'mimes:pdf,jpg,jpeg,png,doc,docx', 'max:10240'],
        ]);

        $data['patient_id'] = $patient->id;
        $consultation = Consultation::create($data);

        // Handle uploaded documents
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $upload) {
                if (!$upload || !$upload->isValid()) {
                    continue;
                }

                $storedPath = $upload->store("consultations/{$consultation->id}", 'public');

                ConsultationAttachment::create([
                    'consultation_id' => $consultation->id,
                    'filename' => $upload->getClientOriginalName(),
                    'path' => $storedPath,
                    'mime' => $upload->getClientMimeType(),
                    'size_bytes' => $upload->getSize(),
                    'uploaded_by' => optional(Auth::user())->id,
                ]);
            }
        }

        return redirect()
            ->route('admin.patients.show', $patient)
            ->with('status', 'Consultation recorded successfully.');
    }

    public function index(Request $request): View
    {
        abort_unless(Auth::check() && (Auth::user()->role ?? null) === 'admin', 403);

        $consultations = Consultation::with(['patient'])
            ->orderByDesc('conducted_at')
            ->orderByDesc('created_at')
            ->paginate(15);

        $patients = Patient::select('id', 'child_name')
            ->orderBy('child_name')
            ->limit(200)
            ->get();

        return view('admin.consultations.index', compact('consultations', 'patients'));
    }

    public function start(Request $request): RedirectResponse
    {
        abort_unless(Auth::check() && (Auth::user()->role ?? null) === 'admin', 403);

        $data = $request->validate([
            'patient_id' => ['required', 'exists:patients,id'],
        ]);

        $patient = Patient::findOrFail($data['patient_id']);

        return redirect()->route('admin.patients.consultations.create', $patient);
    }
}