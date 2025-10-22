<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\WaitlistEntry;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class WaitlistController extends Controller
{
    public function index(): View
    {
        abort_unless(Auth::check() && (Auth::user()->role ?? null) === 'staff', 403);

        $entries = WaitlistEntry::with(['patient'])
            ->orderBy('priority', 'desc')
            ->orderBy('desired_date_start', 'asc')
            ->latest('created_at')
            ->paginate(15);

        $counts = [
            'waiting' => WaitlistEntry::where('status', 'waiting')->count(),
            'invited' => WaitlistEntry::where('status', 'invited')->count(),
            'scheduled' => WaitlistEntry::where('status', 'scheduled')->count(),
        ];

        $patients = Patient::orderBy('child_name')->get(['id', 'child_name']);

        return view('staff.waitlist.index', compact('entries', 'counts', 'patients'));
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless(Auth::check() && (Auth::user()->role ?? null) === 'staff', 403);

        $data = $request->validate([
            'patient_id' => ['required', 'integer', 'exists:patients,id'],
            'triage_level' => ['required', 'in:emergency,urgent,routine'],
            'desired_date_start' => ['nullable', 'date'],
            'desired_date_end' => ['nullable', 'date', 'after_or_equal:desired_date_start'],
            'notes' => ['nullable', 'string'],
        ]);

        $priorityMap = ['emergency' => 3, 'urgent' => 2, 'routine' => 1];
        $data['priority'] = $priorityMap[$data['triage_level']] ?? 1;
        $data['status'] = 'waiting';

        WaitlistEntry::create($data);

        return redirect()->route('staff.waitlist.index')->with('status', 'Added to waitlist.');
    }

    public function updateStatus(Request $request, WaitlistEntry $entry): RedirectResponse
    {
        abort_unless(Auth::check() && (Auth::user()->role ?? null) === 'staff', 403);

        $data = $request->validate([
            'status' => ['required', 'in:waiting,invited,scheduled,expired,cancelled'],
        ]);

        $entry->update(['status' => $data['status']]);

        return redirect()->route('staff.waitlist.index')->with('status', 'Waitlist status updated.');
    }

    public function destroy(WaitlistEntry $entry): RedirectResponse
    {
        abort_unless(Auth::check() && (Auth::user()->role ?? null) === 'staff', 403);

        $entry->delete();
        return redirect()->route('staff.waitlist.index')->with('status', 'Waitlist entry removed.');
    }
}