<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\PhoneInquiry;
use Illuminate\Http\Request;

class PhoneInquiryController extends Controller
{
    public function index()
    {
        $inquiries = PhoneInquiry::latest()->paginate(12);
        $counts = [
            'open' => PhoneInquiry::where('status', 'open')->count(),
            'urgent' => PhoneInquiry::where('triage_level', 'urgent')->where('status', 'open')->count(),
            'dueToday' => PhoneInquiry::dueToday()->count(),
        ];
        return view('staff.phone.index', compact('inquiries', 'counts'));
    }

    public function create()
    {
        return view('staff.phone.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'caller_name' => ['required', 'string', 'max:120'],
            'caller_phone' => ['nullable', 'regex:/^\d{11}$/'],
            'reason' => ['required', 'string'],
            'triage_level' => ['required', 'in:emergency,urgent,routine'],
            'action' => ['required', 'in:advice,callback,schedule,escalate'],
            'callback_date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
            'patient_id' => ['nullable', 'integer'],
        ]);

        $data['status'] = match ($data['action']) {
            'callback' => 'awaiting_callback',
            'schedule' => 'scheduled',
            'escalate' => 'escalated',
            default => 'open',
        };

        $inquiry = PhoneInquiry::create($data);

        return redirect()->route('staff.phone-inquiries.show', $inquiry)->with('status', 'Inquiry recorded.');
    }

    public function show(PhoneInquiry $phoneInquiry)
    {
        return view('staff.phone.show', ['inquiry' => $phoneInquiry]);
    }

    public function updateStatus(Request $request, PhoneInquiry $phoneInquiry)
    {
        $data = $request->validate([
            'status' => ['required', 'in:open,awaiting_callback,scheduled,escalated,closed'],
            'notes' => ['nullable', 'string'],
        ]);
        $phoneInquiry->update($data);
        return back()->with('status', 'Status updated.');
    }

    public function convertToAppointment(PhoneInquiry $phoneInquiry)
    {
        // Placeholder: link or logic to create appointment from inquiry
        return back()->with('status', 'Scheduling flow not yet wired.');
    }
}