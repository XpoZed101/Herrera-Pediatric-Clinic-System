<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class QueueController extends Controller
{
    public function index(): View
    {
        abort_unless(Auth::check() && (Auth::user()->role ?? null) === 'staff', 403);

        $appointments = Appointment::with(['patient', 'user'])
            ->whereDate('scheduled_at', today())
            // Exclude cancelled appointments from today's queue
            ->where('status', '!=', 'cancelled')
            ->where(function ($q) {
                $q->whereNull('checked_out_at');
            })
            ->orderByRaw('CASE WHEN queue_position IS NULL THEN 1 ELSE 0 END, queue_position ASC')
            ->orderByRaw('CASE WHEN checked_in_at IS NULL THEN 1 ELSE 0 END, checked_in_at ASC')
            ->orderBy('scheduled_at', 'asc')
            ->paginate(20);

        return view('staff.queue.index', compact('appointments'));
    }

    public function reorder(Request $request)
    {
        abort_unless(Auth::check() && (Auth::user()->role ?? null) === 'staff', 403);

        $validated = $request->validate([
            'appointment_id' => ['required', 'integer', 'exists:appointments,id'],
            'direction' => ['required', 'in:up,down'],
        ]);

        $appointment = Appointment::findOrFail($validated['appointment_id']);

        // Ensure only today's active queue is affected
        if (!$appointment->scheduled_at || $appointment->scheduled_at->isSameDay(today()) === false) {
            return response()->json(['ok' => false, 'message' => 'Only today\'s appointments can be reordered.'], 422);
        }
        if ($appointment->checked_out_at) {
            return response()->json(['ok' => false, 'message' => 'Checked out appointments cannot be reordered.'], 422);
        }

        // Initialize position if missing
        if ($appointment->queue_position === null) {
            $maxPosition = Appointment::whereDate('scheduled_at', today())
                ->whereNull('checked_out_at')
                ->max('queue_position');
            $appointment->queue_position = is_numeric($maxPosition) ? ((int) $maxPosition + 1) : 1;
            $appointment->save();
        }

        if ($validated['direction'] === 'up') {
            $swap = Appointment::whereDate('scheduled_at', today())
                ->whereNull('checked_out_at')
                ->where('queue_position', '<', $appointment->queue_position)
                ->orderBy('queue_position', 'desc')
                ->first();
        } else {
            $swap = Appointment::whereDate('scheduled_at', today())
                ->whereNull('checked_out_at')
                ->where('queue_position', '>', $appointment->queue_position)
                ->orderBy('queue_position', 'asc')
                ->first();
        }

        if ($swap) {
            $currentPos = $appointment->queue_position;
            $appointment->queue_position = $swap->queue_position;
            $swap->queue_position = $currentPos;
            $appointment->save();
            $swap->save();
        }

        return response()->json(['ok' => true, 'appointment_id' => $appointment->id, 'queue_position' => $appointment->queue_position]);
    }
}
