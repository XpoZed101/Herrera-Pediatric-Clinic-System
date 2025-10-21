<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Appointment;
use App\Models\Payment;
use App\Models\Patient;

class StaffController extends Controller
{
    public function welcome()
    {
        abort_unless(Auth::check() && (Auth::user()->role ?? null) === 'staff', 403);

        // Show all appointments with pagination instead of a limited upcoming list
        $appointments = Appointment::with(['user', 'patient'])
            ->orderBy('scheduled_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        // Real metrics for dashboard (local date)
        $todayDate = now()->toDateString();

        // Count both requested (pending confirmation) and scheduled appointments for today
        $todayAppointmentsCount = Appointment::whereDate('scheduled_at', $todayDate)
            ->whereIn('status', ['requested', 'scheduled'])
            ->count();

        $requestedCount = Appointment::where('status', 'requested')->count();
        $completedToday = Appointment::where('status', 'completed')
            ->whereDate('scheduled_at', $todayDate)
            ->count();

        $paidTotal = Payment::where('status', 'paid')->sum('amount');
        $pendingPayments = Payment::where('status', 'pending')->count();
        $lastPaidAt = Payment::whereNotNull('paid_at')->latest('paid_at')->value('paid_at');

        $newPatientsWeek = Patient::where('created_at', '>=', now()->subDays(7))->count();

        $stats = [
            'today_appointments' => $todayAppointmentsCount,
            'requested_count' => $requestedCount,
            'completed_today' => $completedToday,
            'paid_total' => $paidTotal,
            'pending_payments' => $pendingPayments,
            'last_paid_at' => $lastPaidAt,
            'new_patients_week' => $newPatientsWeek,
        ];

        return view('staff.welcome', compact('appointments', 'stats'));
    }
}
