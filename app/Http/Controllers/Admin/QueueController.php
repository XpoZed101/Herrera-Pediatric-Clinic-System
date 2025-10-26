<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class QueueController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless(Auth::check() && (Auth::user()->role ?? null) === 'admin', 403);

        $search = (string) $request->query('search', '');
        $status = $request->query('status');

        $appointments = Appointment::with(['patient', 'user'])
            ->whereDate('scheduled_at', today())
            ->where('status', '!=', 'cancelled')
            ->whereNull('checked_out_at')
            ->when($status, function ($q) use ($status) {
                $q->where('status', $status);
            })
            ->when($search, function ($q) use ($search) {
                $q->where(function ($inner) use ($search) {
                    $inner->whereHas('patient', function ($pq) use ($search) {
                        $pq->where('child_name', 'like', '%' . $search . '%');
                    })->orWhere('reason', 'like', '%' . $search . '%');
                });
            })
            ->orderByRaw('CASE WHEN queue_position IS NULL THEN 1 ELSE 0 END')
            ->orderBy('queue_position', 'asc')
            ->orderByRaw('CASE WHEN checked_in_at IS NULL THEN 1 ELSE 0 END')
            ->orderBy('checked_in_at', 'asc')
            ->orderBy('scheduled_at', 'asc')
            ->paginate(20)
            ->appends($request->query());

        return view('admin.doctor-queue', [
            'appointments' => $appointments,
            'search' => $search,
            'status' => $status,
        ]);
    }
}