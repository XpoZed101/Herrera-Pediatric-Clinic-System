<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Appointment;

class StaffController extends Controller
{
    public function welcome()
    {
        abort_unless(Auth::check() && (Auth::user()->role ?? null) === 'staff', 403);

        $upcoming = Appointment::with('user')
            ->where('status', 'scheduled')
            ->where('scheduled_at', '>=', now())
            ->orderBy('scheduled_at')
            ->limit(5)
            ->get();

        return view('staff.welcome', compact('upcoming'));
    }
}