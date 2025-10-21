<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class StaffController extends Controller
{
    public function welcome()
    {
        abort_unless(Auth::check() && (Auth::user()->role ?? null) === 'staff', 403);
        return view('staff.welcome');
    }
}