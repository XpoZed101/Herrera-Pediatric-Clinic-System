<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Prescription;
use App\Models\MedicalRecord;
use App\Repositories\PrescriptionRepositoryInterface;
use App\Services\EPrescriptionService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class PrescriptionController extends Controller
{
    public function index(): \Illuminate\View\View
    {
        abort_unless(Auth::check() && (Auth::user()->role ?? null) === 'admin', 403);

        $prescriptions = Prescription::with(['medicalRecord.appointment.user', 'medicalRecord.appointment.patient', 'prescriber'])
            ->latest('created_at')
            ->paginate(15);

        return view('admin.prescriptions.index', compact('prescriptions'));
    }

    public function create(): \Illuminate\View\View
    {
        abort_unless(Auth::check() && (Auth::user()->role ?? null) === 'admin', 403);
    $records = MedicalRecord::with(['appointment.user', 'appointment.patient', 'user'])->latest('created_at')->limit(50)->get();

        return view('admin.prescriptions.create', compact('records'));
    }

    public function edit(Prescription $prescription): \Illuminate\View\View
    {
        abort_unless(Auth::check() && (Auth::user()->role ?? null) === 'admin', 403);
    $records = MedicalRecord::with(['appointment.user', 'appointment.patient', 'user'])->latest('created_at')->limit(50)->get();
        return view('admin.prescriptions.edit', compact('prescription', 'records'));
    }

    public function store(Request $request, PrescriptionRepositoryInterface $repository, EPrescriptionService $erx): RedirectResponse
    {
        abort_unless(Auth::check() && (Auth::user()->role ?? null) === 'admin', 403);

        $validated = $request->validate([
            'medical_record_id' => ['required', 'integer', 'exists:medical_records,id'],
            'type' => ['required', 'in:medication,treatment'],
            'name' => ['required', 'string', 'max:255'],
            'instructions' => ['nullable', 'string'],
            'status' => ['nullable', 'in:active,completed,discontinued'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            // Medication-specific (optional)
            'dosage' => ['nullable', 'string', 'max:255'],
            'frequency' => ['nullable', 'string', 'max:255'],
            'route' => ['nullable', 'string', 'max:255'],
            // Treatment-specific
            'treatment_schedule' => ['nullable', 'string', 'max:255'],
            // e‑prescription
            'erx_pharmacy' => ['nullable', 'string', 'max:255'],
            'erx_submit' => ['nullable'],
        ]);

        $data = [
            'medical_record_id' => $validated['medical_record_id'],
            'prescribed_by' => Auth::id(),
            'type' => $validated['type'],
            'name' => $validated['name'],
            'instructions' => $validated['instructions'] ?? null,
            'status' => $validated['status'] ?? 'active',
            'start_date' => $validated['start_date'] ?? null,
            'end_date' => $validated['end_date'] ?? null,
        ];

        if ($validated['type'] === 'medication') {
            $data = array_merge($data, [
                'dosage' => $validated['dosage'] ?? null,
                'frequency' => $validated['frequency'] ?? null,
                'route' => $validated['route'] ?? null,
            ]);
        } else {
            $data = array_merge($data, [
                'treatment_schedule' => $validated['treatment_schedule'] ?? null,
            ]);
        }

        // Create prescription
        $prescription = $repository->create(array_merge($data, [
            'erx_enabled' => $request->boolean('erx_submit'),
            'erx_pharmacy' => $validated['erx_pharmacy'] ?? null,
        ]));

        // Optionally submit e‑prescription for medications
        if ($prescription->type === 'medication' && $prescription->erx_enabled) {
            try {
                $result = $erx->submitMedication($prescription, [
                    'pharmacy' => $prescription->erx_pharmacy,
                ]);
                $repository->update($prescription, [
                    'erx_status' => $result['status'] ?? 'submitted',
                    'erx_external_id' => $result['external_id'] ?? null,
                    'erx_submitted_at' => $result['submitted_at'] ?? now(),
                ]);
            } catch (\Throwable $e) {
                $repository->update($prescription, [
                    'erx_status' => 'failed',
                    'erx_error' => $e->getMessage(),
                ]);
            }
        }

        return redirect()->route('admin.prescriptions.index')
            ->with('status', __('Prescription created successfully.'));
    }

    public function update(Request $request, Prescription $prescription, PrescriptionRepositoryInterface $repository, EPrescriptionService $erx): RedirectResponse
    {
        abort_unless(Auth::check() && (Auth::user()->role ?? null) === 'admin', 403);

        $validated = $request->validate([
            'medical_record_id' => ['required', 'integer', 'exists:medical_records,id'],
            'type' => ['required', 'in:medication,treatment'],
            'name' => ['required', 'string', 'max:255'],
            'instructions' => ['nullable', 'string'],
            'status' => ['nullable', 'in:active,completed,discontinued'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            // Medication-specific (optional)
            'dosage' => ['nullable', 'string', 'max:255'],
            'frequency' => ['nullable', 'string', 'max:255'],
            'route' => ['nullable', 'string', 'max:255'],
            // Treatment-specific
            'treatment_schedule' => ['nullable', 'string', 'max:255'],
            // e‑prescription
            'erx_pharmacy' => ['nullable', 'string', 'max:255'],
            'erx_submit' => ['nullable'],
        ]);

        $data = [
            'medical_record_id' => $validated['medical_record_id'],
            'type' => $validated['type'],
            'name' => $validated['name'],
            'instructions' => $validated['instructions'] ?? null,
            'status' => $validated['status'] ?? 'active',
            'start_date' => $validated['start_date'] ?? null,
            'end_date' => $validated['end_date'] ?? null,
        ];

        if ($validated['type'] === 'medication') {
            $data = array_merge($data, [
                'dosage' => $validated['dosage'] ?? null,
                'frequency' => $validated['frequency'] ?? null,
                'route' => $validated['route'] ?? null,
            ]);
        } else {
            $data = array_merge($data, [
                'treatment_schedule' => $validated['treatment_schedule'] ?? null,
            ]);
        }

        $repository->update($prescription, array_merge($data, [
            'erx_enabled' => $request->boolean('erx_submit'),
            'erx_pharmacy' => $validated['erx_pharmacy'] ?? null,
        ]));

        // Optionally submit e‑prescription
        $prescription->refresh();
        if ($prescription->type === 'medication' && $prescription->erx_enabled && empty($prescription->erx_external_id)) {
            try {
                $result = $erx->submitMedication($prescription, [
                    'pharmacy' => $prescription->erx_pharmacy,
                ]);
                $repository->update($prescription, [
                    'erx_status' => $result['status'] ?? 'submitted',
                    'erx_external_id' => $result['external_id'] ?? null,
                    'erx_submitted_at' => $result['submitted_at'] ?? now(),
                ]);
            } catch (\Throwable $e) {
                $repository->update($prescription, [
                    'erx_status' => 'failed',
                    'erx_error' => $e->getMessage(),
                ]);
            }
        }

        return redirect()->route('admin.prescriptions.index')
            ->with('status', __('Prescription updated successfully.'));
    }

    public function destroy(Prescription $prescription, PrescriptionRepositoryInterface $repository): RedirectResponse
    {
        abort_unless(Auth::check() && (Auth::user()->role ?? null) === 'admin', 403);
        $repository->delete($prescription);
        return redirect()->route('admin.prescriptions.index')
            ->with('status', __('Prescription deleted successfully.'));
    }

    public function pdf(Request $request, Prescription $prescription)
    {
        abort_unless(Auth::check() && (Auth::user()->role ?? null) === 'admin', 403);

        $pdf = Pdf::loadView('admin.prescriptions.pdf', [
            'prescription' => $prescription->load(['medicalRecord.appointment.user', 'medicalRecord.appointment.patient', 'prescriber']),
            'appName' => config('app.name'),
        ])->setPaper('a4');

        // Inline preview to avoid repeated downloads (opens in a new tab)
        return $pdf->stream('prescription-' . $prescription->id . '.pdf');
    }
}