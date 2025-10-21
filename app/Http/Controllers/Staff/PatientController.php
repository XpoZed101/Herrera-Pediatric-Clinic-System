<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PatientController extends Controller
{
    public function create(): View
    {
        abort_unless(Auth::check() && (Auth::user()->role ?? null) === 'staff', 403);
        return view('staff.patients.create');
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless(Auth::check() && (Auth::user()->role ?? null) === 'staff', 403);

        $validated = $request->validate([
            'child_name' => ['required', 'string', 'max:255'],
            'date_of_birth' => ['required', 'date'],
            'age' => ['nullable', 'integer', 'min:0', 'max:150'],
            'sex' => ['required', 'in:male,female'],
        ]);

        $patient = Patient::create($validated);

        return redirect()->route('staff.appointments.index')
            ->with('status', "Patient '{$patient->child_name}' registered successfully.");
    }
}