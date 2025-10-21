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

    public function updateStatus(Request $request, Appointment $appointment)
    {
        abort_unless(Auth::check() && (Auth::user()->role ?? null) === 'staff', 403);

        $validated = $request->validate([
            'status' => 'required|in:requested,scheduled,completed,cancelled',
        ]);

        $appointment->status = $validated['status'];
        $appointment->save();

        // Send email immediately on status change
        $recipient = optional($appointment->user)->email;
        $emailSent = false;
        if ($recipient) {
            try {
                Mail::to($recipient)->send(new AppointmentStatusMail($appointment));
                $emailSent = true;
            } catch (\Throwable $e) {
                // swallow exception; we'll still respond OK to UI
            }
        }

        if ($request->wantsJson()) {
            return response()->json([
                'ok' => true,
                'status' => $validated['status'],
                'email_sent' => $emailSent,
            ]);
        }

        return redirect()
            ->route('staff.appointments.index')
            ->with('status_updated', "Appointment #{$appointment->id} status updated to {$validated['status']}")
            ->with('email_sent', $emailSent ? "Email sent to {$recipient}" : null);
    }

    public function email(Request $request, Appointment $appointment): RedirectResponse
    {
        abort_unless(Auth::check() && (Auth::user()->role ?? null) === 'staff', 403);

        $recipient = optional($appointment->user)->email;
        if (!$recipient) {
            return redirect()
                ->route('staff.appointments.index')
                ->with('status_updated', "No email found for appointment #{$appointment->id}.");
        }

        try {
            Mail::to($recipient)->send(new AppointmentStatusMail($appointment));
            return redirect()
                ->route('staff.appointments.index')
                ->with('email_sent', "Email sent to {$recipient} for appointment #{$appointment->id}.");
        } catch (\Throwable $e) {
            return redirect()
                ->route('staff.appointments.index')
                ->with('status_updated', "Failed to send email: " . $e->getMessage());
        }
    }
}