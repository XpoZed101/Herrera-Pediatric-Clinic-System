<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\MedicalRecord;
use App\Models\Guardian;
use App\Models\Patient;
use App\Models\EmergencyContact;
use App\Models\User;
use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Barryvdh\DomPDF\Facade\Pdf;

class PatientController extends Controller
{
    public function medicalHistory(): View
    {
        $user = Auth::user();
        $patient = null;

        if ($user) {
            $guardian = Guardian::where('email', $user->email)->first();
            $patient = $guardian?->patient;
        }

        $patient = $patient ?? Patient::query()->first();

        if ($patient) {
            $patient->load(['medications', 'allergies', 'pastMedicalConditions', 'developmentConcerns', 'currentSymptoms', 'additionalNote', 'immunization']);
        }

        // Load medical records linked to this account (by user) or by the child's appointments.
        $medicalRecords = MedicalRecord::with(['appointment.patient', 'diagnoses'])
            ->where(function ($q) use ($user, $patient) {
                if ($patient) {
                    $q->whereHas('appointment', function ($aq) use ($patient) {
                        $aq->where('patient_id', $patient->id);
                    });
                }

                if ($user) {
                    $q->orWhere('user_id', $user->id);
                }
            })
            ->latest('conducted_at')
            ->get();

        return view('client.medical-history', compact('patient', 'medicalRecords'));
    }

    public function immunizations(): View
    {
        $user = Auth::user();
        $patient = null;

        if ($user) {
            $guardian = Guardian::where('email', $user->email)->first();
            $patient = $guardian?->patient;
        }

        $patient = $patient ?? Patient::query()->first();

        if ($patient) {
            $patient->load(['immunization']);
        }

        return view('client.immunizations', compact('patient'));
    }

    /**
     * Download Medical History as PDF for the authenticated client.
     */
    public function medicalHistoryPdf()
    {
        $user = Auth::user();
        $patient = null;

        if ($user) {
            $guardian = Guardian::where('email', $user->email)->first();
            $patient = $guardian?->patient;
        }

        $patient = $patient ?? Patient::query()->first();

        if ($patient) {
            $patient->load(['medications', 'allergies', 'pastMedicalConditions', 'developmentConcerns', 'currentSymptoms', 'additionalNote', 'immunization']);
        }

        $medicalRecords = MedicalRecord::with(['appointment.patient', 'diagnoses'])
            ->where(function ($q) use ($user, $patient) {
                if ($patient) {
                    $q->whereHas('appointment', function ($aq) use ($patient) {
                        $aq->where('patient_id', $patient->id);
                    });
                }

                if ($user) {
                    $q->orWhere('user_id', $user->id);
                }
            })
            ->latest('conducted_at')
            ->get();

        $pdf = Pdf::loadView('client.medical-history-pdf', [
            'patient' => $patient,
            'medicalRecords' => $medicalRecords,
            'generatedAt' => now(),
        ])->setPaper('a4');

        $filename = 'medical-history-' . ($patient?->child_name ? str_replace(' ', '-', strtolower($patient->child_name)) : 'record') . '.pdf';
        return $pdf->download($filename);
    }

    /**
     * Render a modern, printable preview of the medical history.
     */
    public function medicalHistoryPreview(): View
    {
        $user = Auth::user();
        $patient = null;

        if ($user) {
            $guardian = Guardian::where('email', $user->email)->first();
            $patient = $guardian?->patient;
        }

        $patient = $patient ?? Patient::query()->first();

        if ($patient) {
            $patient->load(['medications', 'allergies', 'pastMedicalConditions', 'developmentConcerns', 'currentSymptoms', 'additionalNote', 'immunization']);
        }

        $medicalRecords = MedicalRecord::with(['appointment.patient', 'diagnoses'])
            ->where(function ($q) use ($user, $patient) {
                if ($patient) {
                    $q->whereHas('appointment', function ($aq) use ($patient) {
                        $aq->where('patient_id', $patient->id);
                    });
                }

                if ($user) {
                    $q->orWhere('user_id', $user->id);
                }
            })
            ->latest('conducted_at')
            ->get();

        return view('client.medical-history-preview', compact('patient', 'medicalRecords'));
    }

    /**
     * Show contact information page for the authenticated client.
     */
    public function contactInfo(): View
    {
        $user = Auth::user();
        $guardian = null;
        $patient = null;

        if ($user) {
            $guardian = Guardian::where('email', $user->email)->first();
            $patient = $guardian?->patient;
        }

        $patient = $patient ?? Patient::query()->first();

        $emergency = $patient?->emergencyContact;

        return view('client.contact-info', [
            'user' => $user,
            'guardian' => $guardian,
            'patient' => $patient,
            'emergency' => $emergency,
        ]);
    }

    /**
     * Update guardian and emergency contact information.
     */
    public function updateContactInfo(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $validated = $request->validate([
            'guardian_name' => ['nullable', 'string', 'max:255'],
            'guardian_phone' => ['nullable', 'digits:11'],
            'guardian_email' => ['nullable', 'email', 'max:255'],
            'emergency_name' => ['nullable', 'string', 'max:255'],
            'emergency_phone' => ['nullable', 'digits:11'],
        ], [
            'guardian_phone.digits' => __('Phone must be exactly 11 digits.'),
            'emergency_phone.digits' => __('Phone must be exactly 11 digits.'),
        ]);

        $guardian = null;
        $patient = null;

        if ($user) {
            $guardian = Guardian::where('email', $user->email)->first();
            $patient = $guardian?->patient;
        }

        $patient = $patient ?? Patient::query()->first();

        if ($guardian) {
            if (array_key_exists('guardian_name', $validated)) {
                $guardian->name = $validated['guardian_name'] ?? $guardian->name;
            }
            if (array_key_exists('guardian_phone', $validated)) {
                $guardian->phone = $validated['guardian_phone'] ?? $guardian->phone;
            }
            if (!empty($validated['guardian_email'])) {
                $newEmail = $validated['guardian_email'];
                $guardian->email = $newEmail;
            if ($user instanceof User && $user->email !== $newEmail) {
                $user->email = $newEmail;
                $user->email_verified_at = null; // re-verify when email changes
                $user->save();
            }
            }
            $guardian->save();
        }

        if ($patient) {
            $emergency = $patient->emergencyContact ?: new EmergencyContact(['patient_id' => $patient->id]);
            if (array_key_exists('emergency_name', $validated)) {
                $emergency->name = $validated['emergency_name'] ?? $emergency->name;
            }
            if (array_key_exists('emergency_phone', $validated)) {
                $emergency->phone = $validated['emergency_phone'] ?? $emergency->phone;
            }
            // Only save if there is any change or it is new
            if ($emergency->isDirty() || !$emergency->exists) {
                $emergency->patient_id = $patient->id;
                $emergency->save();
            }
        }

        if ($request->input('redirect') === 'settings') {
            return redirect()->route('contact-info.edit')
                ->with('status', __('Contact information updated.'));
        }

        return redirect()->route('client.contact-info')
            ->with('status', __('Contact information updated.'));
    }

    public function appointmentHistory(): View
    {
        $user = Auth::user();
        $guardian = null;
        $patient = null;

        if ($user) {
            $guardian = Guardian::where('email', $user->email)->first();
            $patient = $guardian?->patient;
        }

        $patient = $patient ?? Patient::query()->first();

        $currentAppointments = Appointment::with(['patient'])
            ->where(function ($q) use ($user, $patient) {
                if ($patient) {
                    $q->where('patient_id', $patient->id);
                }
                if ($user) {
                    $q->orWhere('user_id', $user->id);
                }
            })
            ->whereIn('status', ['requested', 'scheduled'])
            ->where('scheduled_at', '>=', now())
            ->orderBy('scheduled_at')
            ->get();

        $pastAppointments = Appointment::with(['patient'])
            ->where(function ($q) use ($user, $patient) {
                if ($patient) {
                    $q->where('patient_id', $patient->id);
                }
                if ($user) {
                    $q->orWhere('user_id', $user->id);
                }
            })
            ->where(function ($q) {
                $q->where('scheduled_at', '<', now())
                  ->orWhereIn('status', ['completed', 'cancelled']);
            })
            ->orderBy('scheduled_at', 'desc')
            ->get();

        return view('client.appointment-history', compact('patient', 'currentAppointments', 'pastAppointments'));
    }
}