<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use App\Mail\AppointmentStatusMail;
use App\Services\EmailService;

class AppointmentController extends Controller
{
    public function index(): View
    {
        abort_unless(Auth::check() && (Auth::user()->role ?? null) === 'staff', 403);

        $appointments = Appointment::with(['user', 'medicalRecord'])
            ->orderBy('scheduled_at', 'asc')
            ->paginate(15);

        return view('staff.appointments.index', compact('appointments'));
    }

    public function show(Appointment $appointment): View
    {
        abort_unless(Auth::check() && (Auth::user()->role ?? null) === 'staff', 403);

        $appointment->load(['user', 'medicalRecord']);
        return view('staff.appointments.show', compact('appointment'));
    }

    public function checkIn(Request $request, Appointment $appointment): RedirectResponse
    {
        abort_unless(Auth::check() && (Auth::user()->role ?? null) === 'staff', 403);

        if ($appointment->checked_in_at) {
            return redirect()->route('staff.appointments.show', $appointment)
                ->with('status_updated', "Appointment #{$appointment->id} already checked in.");
        }

        $appointment->checked_in_at = now();
        $appointment->checked_in_by = Auth::id();
        $appointment->save();

        return redirect()->route('staff.appointments.show', $appointment)
            ->with('status_updated', "Appointment #{$appointment->id} checked in.");
    }

    public function checkOut(Request $request, Appointment $appointment): RedirectResponse
    {
        abort_unless(Auth::check() && (Auth::user()->role ?? null) === 'staff', 403);

        if ($appointment->checked_out_at) {
            return redirect()->route('staff.appointments.show', $appointment)
                ->with('status_updated', "Appointment #{$appointment->id} already checked out.");
        }

        $appointment->checked_out_at = now();
        $appointment->checked_out_by = Auth::id();
        $appointment->save();

        return redirect()->route('staff.appointments.show', $appointment)
            ->with('status_updated', "Appointment #{$appointment->id} checked out.");
    }

    public function __construct(private EmailService $emailService)
    {
    }

    public function updateStatus(Request $request, Appointment $appointment)
    {
        abort_unless(Auth::check() && (Auth::user()->role ?? null) === 'staff', 403);

        $validated = $request->validate([
            'status' => 'required|in:requested,scheduled,completed,cancelled',
        ]);

        $appointment->status = $validated['status'];
        $appointment->save();

        // Send email using EmailService
        $emailResult = $this->emailService->sendAppointmentStatusEmail($appointment);

        if ($request->wantsJson()) {
            return response()->json([
                'ok' => true,
                'status' => $validated['status'],
                'email_sent' => $emailResult['success'],
                'message' => $emailResult['message']
            ]);
        }

        $response = redirect()->route('staff.appointments.index')
            ->with('status_updated', "Appointment #{$appointment->id} status updated to {$validated['status']}");

        if ($emailResult['success']) {
            $response->with('email_sent', $emailResult['message']);
        }

        return $response;
    }

    public function email(Request $request, Appointment $appointment): RedirectResponse
    {
        abort_unless(Auth::check() && (Auth::user()->role ?? null) === 'staff', 403);

        // Use EmailService for separation of concerns
        $emailResult = $this->emailService->sendAppointmentStatusEmail($appointment);

        if ($emailResult['success']) {
            return redirect()
                ->route('staff.appointments.index')
                ->with('email_sent', $emailResult['message']);
        }

        return redirect()
            ->route('staff.appointments.index')
            ->with('status_updated', 'Failed to send email: ' . ($emailResult['message'] ?? 'Unknown error'));
    }

    public function storeVitals(Request $request, Appointment $appointment): RedirectResponse
    {
        abort_unless(Auth::check() && (Auth::user()->role ?? null) === 'staff', 403);

        if (!$appointment->checked_in_at || $appointment->checked_out_at) {
            return redirect()->route('staff.appointments.show', $appointment)
                ->with('status_updated', "Appointment #{$appointment->id} must be checked in and not checked out.");
        }

        $data = $request->validate([
            'temperature' => ['nullable', 'numeric', 'between:30,45'],
            'blood_pressure' => ['nullable', 'string', 'max:20'],
            'heart_rate' => ['nullable', 'integer', 'between:20,240'],
            'respiratory_rate' => ['nullable', 'integer', 'between:5,100'],
            'oxygen_saturation' => ['nullable', 'integer', 'between:50,100'],
        ]);

        $parts = [];
        if (($data['temperature'] ?? null) !== null) $parts[] = 'Temp ' . number_format((float)$data['temperature'], 1) . '°C';
        if (!empty($data['blood_pressure'])) $parts[] = 'BP ' . $data['blood_pressure'];
        if (($data['heart_rate'] ?? null) !== null) $parts[] = 'HR ' . (int)$data['heart_rate'] . ' bpm';
        if (($data['respiratory_rate'] ?? null) !== null) $parts[] = 'RR ' . (int)$data['respiratory_rate'] . ' /min';
        if (($data['oxygen_saturation'] ?? null) !== null) $parts[] = 'SpO₂ ' . (int)$data['oxygen_saturation'] . '%';

        // Persist structured vitals columns
        $appointment->temperature = $data['temperature'] ?? null;
        $appointment->blood_pressure = $data['blood_pressure'] ?? null;
        $appointment->heart_rate = $data['heart_rate'] ?? null;
        $appointment->respiratory_rate = $data['respiratory_rate'] ?? null;
        $appointment->oxygen_saturation = $data['oxygen_saturation'] ?? null;
        $appointment->vitals_recorded_at = (!empty($parts)) ? now() : $appointment->vitals_recorded_at;

        // Append a human-readable note for quick visibility
        if (!empty($parts)) {
            $vitalsText = 'Vitals: ' . implode(', ', $parts);
            $prefix = '[' . now()->format('Y-m-d H:i') . '] ';
            $existing = trim((string)($appointment->notes ?? ''));
            $separator = $existing ? "\n" : '';
            $appointment->notes = $existing . $separator . $prefix . $vitalsText;
        }

        $appointment->save();

        return redirect()->route('staff.queue.index')->with('status', 'Vitals recorded.');
    }
}
