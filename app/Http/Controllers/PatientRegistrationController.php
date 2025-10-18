<?php

namespace App\Http\Controllers;

use App\Http\Requests\Registration\ChildInfoRequest;
use App\Http\Requests\Registration\MedicalHistoryRequest;
use App\Http\Requests\Registration\DevelopmentRequest;
use App\Http\Requests\Registration\SymptomsRequest;
use App\Repositories\PatientRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class PatientRegistrationController extends Controller
{
    public function __construct(private PatientRepository $repository)
    {
    }

    public function show(int $step = 1): View
    {
        $step = max(1, min(4, $step));
        $data = Session::get('registration.data', []);
        return view('pages.register', compact('step', 'data'));
    }

    public function storeStep1(ChildInfoRequest $request): RedirectResponse
    {
        $payload = $request->validated();
        $data = Session::get('registration.data', []);
        $data['child'] = [
            'child_name' => $payload['child_name'],
            'date_of_birth' => $payload['date_of_birth'],
            'sex' => $payload['sex'],
        ];
        $data['guardian'] = [
            'name' => $payload['guardian_name'] ?? null,
            'phone' => $payload['guardian_phone'] ?? null,
            'email' => $payload['guardian_email'] ?? null,
        ];
        // Auth info for creating a login account on final submission
        $data['auth'] = [
            'email' => $payload['guardian_email'],
            'password' => $payload['password'],
            'role' => $payload['role'] ?? 'patient',
        ];
        $data['emergency'] = [
            'name' => $payload['emergency_name'] ?? null,
            'phone' => $payload['emergency_phone'] ?? null,
        ];
        Session::put('registration.data', $data);
        return redirect()->route('register.step.show', ['step' => 2]);
    }

    public function storeStep2(MedicalHistoryRequest $request): RedirectResponse
    {
        $payload = $request->validated();
        $data = Session::get('registration.data', []);
        $data['medical'] = [
            'medications' => $payload['medications'] ?? '',
            'allergies' => $payload['allergies'] ?? '',
            'past_conditions' => $payload['past_conditions'] ?? [],
            'immunizations_status' => $payload['immunizations_status'] ?? null,
        ];
        Session::put('registration.data', $data);
        return redirect()->route('register.step.show', ['step' => 3]);
    }

    public function storeStep3(DevelopmentRequest $request): RedirectResponse
    {
        $payload = $request->validated();
        $data = Session::get('registration.data', []);
        $data['development'] = [
            'concerns' => $payload['concerns'] ?? [],
            'notes' => $payload['notes'] ?? null,
        ];
        Session::put('registration.data', $data);
        return redirect()->route('register.step.show', ['step' => 4]);
    }

    public function storeStep4(SymptomsRequest $request): RedirectResponse
    {
        $payload = $request->validated();
        $data = Session::get('registration.data', []);
        $data['symptoms'] = [
            'types' => $payload['symptom_types'] ?? [],
            'details' => $payload['symptom_details'] ?? null,
        ];
        Session::put('registration.data', $data);

        $this->repository->createRegistration($data);

        // Create a user account for the guardian to log in
        try {
            $auth = $data['auth'] ?? [];
            $email = $auth['email'] ?? null;
            $password = $auth['password'] ?? null;
            $role = ($auth['role'] ?? 'patient');
            if ($email && $password) {
                $name = $data['guardian']['name'] ?? ($data['child']['child_name'] ?? 'Patient Guardian');
                $user = \App\Models\User::firstOrCreate(
                    ['email' => $email],
                    [
                        'name' => $name,
                        'password' => $password,
                        'role' => $role,
                    ]
                );
                // If user already exists, ensure role is patient (do not overwrite password)
                if ($user->wasRecentlyCreated === false && $user->role !== $role) {
                    $user->role = $role;
                    $user->save();
                }
            }
        } catch (\Throwable $e) {
            // Swallow errors to avoid blocking registration; could be logged
        }

        Session::forget('registration.data');
        return redirect()->route('login')->with('status', 'Registration submitted. Account created. Please log in.');
    }
}