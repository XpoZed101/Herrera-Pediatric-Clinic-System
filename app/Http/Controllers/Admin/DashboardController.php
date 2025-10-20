<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\MedicalRecord;
use App\Models\Prescription;
use App\Models\Diagnosis;
use App\Models\VisitType;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        // Restrict to admin users
        abort_unless(Auth::check() && (Auth::user()->role ?? null) === 'admin', 403);

        // High-level counts
        $stats = [
            'patients' => Patient::count(),
            'appointments' => Appointment::count(),
            'medical_records' => MedicalRecord::count(),
            'prescriptions' => Prescription::count(),
            'diagnoses' => Diagnosis::count(),
        ];

        // Appointment status distribution for circle chart
        $statusLabels = ['requested', 'scheduled', 'completed', 'cancelled'];
        $statusCounts = collect($statusLabels)->map(fn ($s) => Appointment::where('status', $s)->count())->all();

        // Visit type distribution (dynamic from VisitType)
        $types = VisitType::active()->orderBy('name')->get(['slug','name']);
        $visitTypeLabels = $types->pluck('name')->all();
        $visitTypeCounts = $types->map(fn ($t) => Appointment::where('visit_type', $t->slug)->count())->all();

        return view('admin.dashboard', [
            'stats' => $stats,
            'statusLabels' => $statusLabels,
            'statusCounts' => $statusCounts,
            'visitTypeLabels' => $visitTypeLabels,
            'visitTypeCounts' => $visitTypeCounts,
        ]);
    }

    public function stats()
    {
        // Appointment status distribution for charts
        $statusLabels = ['requested', 'scheduled', 'completed', 'cancelled'];
        $statusCounts = collect($statusLabels)->map(fn ($s) => Appointment::where('status', $s)->count())->all();

        // Visit type distribution for charts (dynamic from VisitType)
        $types = VisitType::active()->orderBy('name')->get(['slug','name']);
        $visitTypeLabels = $types->pluck('name')->all();
        $visitTypeCounts = $types->map(fn ($t) => Appointment::where('visit_type', $t->slug)->count())->all();

        return response()->json([
            'statusLabels' => $statusLabels,
            'statusCounts' => $statusCounts,
            'visitTypeLabels' => $visitTypeLabels,
            'visitTypeCounts' => $visitTypeCounts,
        ]);
    }
}