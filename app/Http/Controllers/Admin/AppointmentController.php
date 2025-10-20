<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\VisitType;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use App\Mail\AppointmentStatusMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AppointmentController extends Controller
{
    public function index(): View
    {
        abort_unless(Auth::check() && (Auth::user()->role ?? null) === 'admin', 403);

        $appointments = Appointment::with(['patient', 'user', 'medicalRecord'])
            ->latest('scheduled_at')
            ->paginate(15);

        return view('admin.appointments.index', compact('appointments'));
    }

    public function show(Appointment $appointment): View
    {
        abort_unless(Auth::check() && (Auth::user()->role ?? null) === 'admin', 403);

        $appointment->load(['patient', 'user', 'medicalRecord']);
        return view('admin.appointments.show', compact('appointment'));
    }

    public function edit(Appointment $appointment): View
    {
        abort_unless(Auth::check() && (Auth::user()->role ?? null) === 'admin', 403);

        $appointment->load(['patient', 'user']);
        $visitTypes = VisitType::active()->orderBy('name')->get();
        return view('admin.appointments.edit', compact('appointment', 'visitTypes'));
    }

    public function update(Request $request, Appointment $appointment): RedirectResponse
    {
        abort_unless(Auth::check() && (Auth::user()->role ?? null) === 'admin', 403);

        $validated = $request->validate([
            'scheduled_at' => ['nullable', 'date'],
            'visit_type' => ['nullable', 'string', 'exists:visit_types,slug'],
            'reason' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
            'status' => ['nullable', 'in:requested,scheduled,completed,cancelled'],
        ]);

        $appointment->fill($validated);
        $appointment->save();

        return redirect()
            ->route('admin.appointments.show', $appointment)
            ->with('status', "Appointment #{$appointment->id} updated successfully.");
    }

    public function updateStatus(Request $request, Appointment $appointment): RedirectResponse
    {
        abort_unless(Auth::check() && (Auth::user()->role ?? null) === 'admin', 403);

        $validated = $request->validate([
            'status' => 'required|in:requested,scheduled,completed,cancelled',
        ]);

        $appointment->status = $validated['status'];
        $appointment->save();

        return redirect()
            ->route('admin.appointments.index')
            ->with('status_updated', "Appointment #{$appointment->id} status updated to {$validated['status']}");
    }

    public function email(Request $request, Appointment $appointment): RedirectResponse
    {
        abort_unless(Auth::check() && (Auth::user()->role ?? null) === 'admin', 403);

        $recipient = optional($appointment->user)->email;
        if (!$recipient) {
            return redirect()
                ->route('admin.appointments.index')
                ->with('status_updated', "No email found for appointment #{$appointment->id}.");
        }

        try {
            Mail::to($recipient)->send(new AppointmentStatusMail($appointment));
            return redirect()
                ->route('admin.appointments.index')
                ->with('email_sent', "Email sent to {$recipient} for appointment #{$appointment->id}.");
        } catch (\Throwable $e) {
            return redirect()
                ->route('admin.appointments.index')
                ->with('status_updated', "Failed to send email: " . $e->getMessage());
        }
    }
}