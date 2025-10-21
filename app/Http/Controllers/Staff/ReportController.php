<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function appointmentsPdf(Request $request)
    {
        abort_unless(Auth::check() && (Auth::user()->role ?? null) === 'staff', 403);

        $range = $request->input('range', 'month'); // today | month | all
        $startInput = $request->input('start');
        $endInput = $request->input('end');

        $query = Appointment::with(['user', 'patient'])->orderBy('scheduled_at', 'asc');

        $periodStart = null;
        $periodEnd = null;

        if ($startInput && $endInput) {
            try {
                $isDateOnly = preg_match('/^\d{4}-\d{2}-\d{2}$/', $startInput) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $endInput);
                if ($isDateOnly) {
                    $periodStart = \Carbon\Carbon::parse($startInput)->startOfDay();
                    $periodEnd = \Carbon\Carbon::parse($endInput)->endOfDay();
                } else {
                    $periodStart = \Carbon\Carbon::parse($startInput)->startOfMinute();
                    $periodEnd = \Carbon\Carbon::parse($endInput)->endOfMinute();
                }
                $query->whereBetween('scheduled_at', [$periodStart, $periodEnd]);
                // When explicit period is provided, ignore preset range label
                $range = 'custom';
            } catch (\Throwable $e) {
                // Fall back to range if parsing fails
            }
        }

        if (!$periodStart || !$periodEnd) {
            if ($range === 'today') {
                $query->whereDate('scheduled_at', now()->toDateString());
                $periodStart = now()->startOfDay();
                $periodEnd = now()->endOfDay();
            } elseif ($range === 'month') {
                $query->whereMonth('scheduled_at', now()->month)->whereYear('scheduled_at', now()->year);
                $periodStart = now()->startOfMonth();
                $periodEnd = now()->endOfMonth();
            } else {
                // 'all' default — no filter
                $range = 'all';
            }
        }

        $appointments = $query->get();

        $stats = [
            'total' => $appointments->count(),
            'requested' => $appointments->where('status', 'requested')->count(),
            'scheduled' => $appointments->where('status', 'scheduled')->count(),
            'completed' => $appointments->where('status', 'completed')->count(),
            'cancelled' => $appointments->where('status', 'cancelled')->count(),
        ];

        $pdf = Pdf::loadView('staff.reports.appointments', [
            'appointments' => $appointments,
            'stats' => $stats,
            'generatedAt' => now(),
            'range' => $range,
            'periodStart' => $periodStart,
            'periodEnd' => $periodEnd,
        ])->setPaper('a4');

        return $pdf->stream('appointments-report-' . now()->format('Ymd-His') . '.pdf');
    }

    public function paymentsPdf(Request $request)
    {
        abort_unless(Auth::check() && (Auth::user()->role ?? null) === 'staff', 403);

        $status = $request->input('status'); // optional: pending|paid|cancelled
        $startInput = $request->input('start');
        $endInput = $request->input('end');
        $dateField = $request->input('date_field', 'created_at'); // created_at or paid_at
        if (!in_array($dateField, ['created_at', 'paid_at'])) {
            $dateField = 'created_at';
        }

        $query = Payment::with(['user', 'appointment.patient'])->orderBy($dateField, 'desc');

        if (!empty($status)) {
            $query->where('status', $status);
        }

        $periodStart = null;
        $periodEnd = null;
        if ($startInput && $endInput) {
            try {
                $isDateOnly = preg_match('/^\d{4}-\d{2}-\d{2}$/', $startInput) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $endInput);
                if ($isDateOnly) {
                    $periodStart = \Carbon\Carbon::parse($startInput)->startOfDay();
                    $periodEnd = \Carbon\Carbon::parse($endInput)->endOfDay();
                } else {
                    $periodStart = \Carbon\Carbon::parse($startInput)->startOfMinute();
                    $periodEnd = \Carbon\Carbon::parse($endInput)->endOfMinute();
                }
                $query->whereBetween($dateField, [$periodStart, $periodEnd]);
            } catch (\Throwable $e) {
                // Ignore parsing errors; fall back to unbounded
            }
        }

        $payments = $query->get();

        $stats = [
            'count' => $payments->count(),
            'paid_total' => (int) $payments->where('status', 'paid')->sum('amount'),
            'pending_count' => $payments->where('status', 'pending')->count(),
            'cancelled_count' => $payments->where('status', 'cancelled')->count(),
        ];

        $pdf = Pdf::loadView('staff.reports.payments', [
            'payments' => $payments,
            'stats' => $stats,
            'generatedAt' => now(),
            'status' => $status,
            'periodStart' => $periodStart,
            'periodEnd' => $periodEnd,
            'dateField' => $dateField,
        ])->setPaper('a4');

        return $pdf->stream('payments-report-' . now()->format('Ymd-His') . '.pdf');
    }
}