<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\MedicalRecord;
use App\Models\Prescription;
use App\Models\Diagnosis;
use App\Mail\AppointmentReminderMail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Role-aware redirects: admins to admin dashboard, patients to client home
        $user = $request->user();
        if ($user) {
            if (($user->role ?? 'patient') === 'admin') {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('client.home');
        }

        $counts = [
            'patients' => Patient::count(),
            'appointments' => Appointment::count(),
            'medicalRecords' => MedicalRecord::count(),
            'prescriptions' => Prescription::count(),
            'diagnoses' => Diagnosis::count(),
        ];

        $statusLabels = ['requested', 'scheduled', 'cancelled', 'completed'];
        $statusCountsMap = Appointment::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');
        $statusCounts = array_map(fn($s) => (int)($statusCountsMap[$s] ?? 0), $statusLabels);

        $visitTypeLabels = ['well_visit', 'sick_visit', 'follow_up', 'immunization', 'consultation'];
        $visitTypeCountsMap = Appointment::selectRaw('visit_type, COUNT(*) as count')
            ->groupBy('visit_type')
            ->pluck('count', 'visit_type');
        $visitTypeCounts = array_map(fn($t) => (int)($visitTypeCountsMap[$t] ?? 0), $visitTypeLabels);

        return view('dashboard', [
            'counts' => $counts,
            'statusLabels' => $statusLabels,
            'statusCounts' => $statusCounts,
            'visitTypeLabels' => $visitTypeLabels,
            'visitTypeCounts' => $visitTypeCounts,
        ]);
    }

    public function sendReminders(Request $request)
    {
        // Find appointments scheduled for the next day (full-day window)
        $start = Carbon::now()->addDay()->startOfDay();
        $end = Carbon::now()->addDay()->endOfDay();

        $appointments = Appointment::with(['patient.guardian', 'user'])
            ->where('status', 'scheduled')
            ->whereBetween('scheduled_at', [$start, $end])
            ->get();

        $sent = 0;
        foreach ($appointments as $appointment) {
            $emails = collect([
                optional(optional($appointment->patient)->guardian)->email,
                optional($appointment->user)->email,
            ])->filter()->unique();

            if ($emails->isNotEmpty()) {
                foreach ($emails as $email) {
                    Mail::to($email)->send(new AppointmentReminderMail($appointment));
                }
                $sent++;
            }
        }

        return redirect()->route('dashboard')->with('status', "Reminders sent: {$sent}");
    }
}