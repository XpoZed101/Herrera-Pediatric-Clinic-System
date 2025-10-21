<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

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
}