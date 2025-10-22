<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\RecordRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecordRequestController extends Controller
{
    /**
     * Display a listing of the record requests for staff.
     */
    public function index(Request $request)
    {
        $status = $request->string('status')->toString();
        $q = $request->string('q')->toString();

        $requests = RecordRequest::with(['user', 'patient', 'medicalRecord'])
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->when($q, function ($query) use ($q) {
                $query->where(function ($inner) use ($q) {
                    $inner->where('record_type', 'like', "%{$q}%")
                        ->orWhere('delivery_method', 'like', "%{$q}%")
                        ->orWhere('purpose', 'like', "%{$q}%")
                        ->orWhereHas('user', function ($u) use ($q) {
                            $u->where('name', 'like', "%{$q}%")
                                ->orWhere('email', 'like', "%{$q}%");
                        })
                        ->orWhereHas('patient', function ($p) use ($q) {
                            $p->where('child_name', 'like', "%{$q}%")
                                ->orWhere('guardian_name', 'like', "%{$q}%");
                        });
                });
            })
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        return view('staff.record-requests.index', [
            'requests' => $requests,
            'selectedStatus' => $status,
            'q' => $q,
        ]);
    }

    /**
     * Display a specific record request.
     */
    public function show(RecordRequest $recordRequest)
    {
        $recordRequest->load(['user', 'patient', 'medicalRecord']);

        return view('staff.record-requests.show', [
            'request' => $recordRequest,
        ]);
    }

    /**
     * Update the status of a record request.
     */
    public function updateStatus(Request $request, RecordRequest $recordRequest)
    {
        $data = $request->validate([
            'status' => 'required|string|in:waiting,processing,completed,rejected',
        ]);

        $recordRequest->update(['status' => $data['status']]);

        return back()->with('status', __('Status updated.'));
    }

    /**
     * Mark a record request as released (completed).
     */
    public function release(Request $request, RecordRequest $recordRequest)
    {
        $note = $request->string('note')->toString();

        $updates = ['status' => 'completed'];
        if ($note) {
            $updates['notes'] = trim(($recordRequest->notes ? $recordRequest->notes."\n\n" : '') . '[' . now()->format('Y-m-d H:i') . '] ' . (Auth::user()->name ?? 'Staff') . ': ' . $note);
        }

        $recordRequest->update($updates);

        return back()->with('status', __('Record released and marked completed.'));
    }
}