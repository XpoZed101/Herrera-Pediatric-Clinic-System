<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Diagnosis;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DiagnosisController extends Controller
{
    public function index(): View
    {
        abort_unless(Auth::check() && (Auth::user()->role ?? null) === 'admin', 403);

        $diagnoses = Diagnosis::with(['medicalRecord.user', 'medicalRecord.appointment'])
            ->latest('id')
            ->paginate(15);

        return view('admin.diagnoses.index', compact('diagnoses'));
    }
}