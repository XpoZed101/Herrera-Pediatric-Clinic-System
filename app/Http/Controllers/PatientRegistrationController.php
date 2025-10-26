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
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\RegistrationOtpMail;

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

        // Generate and send OTP to guardian email (only if email is unique)
        try {
            $otp = (string) random_int(100000, 999999);
            Session::put('registration.otp', [
                'code' => $otp,
                'email' => $payload['guardian_email'],
                'expires_at' => now()->addMinutes(10)->toIso8601String(),
            ]);
            Session::put('registration.otp_pending', true);
            Session::forget('registration.otp_verified');

            Mail::to($payload['guardian_email'])->send(new RegistrationOtpMail($otp));
            Session::flash('status', __('A verification code was sent to your email.'));
        } catch (\Throwable $e) {
            Session::flash('error', __('Failed to send verification code. Please try again.'));
        }

        // Stay on step 1 to complete OTP verification before proceeding
        return redirect()->route('register.step.show', ['step' => 1]);
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

                // Fire registration event to send verification email for newly created accounts
                if ($user->wasRecentlyCreated) {
                    event(new Registered($user));
                }

                // Log in user
                Auth::login($user);
                Session::regenerate();
                Session::flash('status', 'verification-link-sent');
            }
        } catch (\Throwable $e) {
            // Swallow errors to avoid blocking registration; could be logged
        }

        Session::forget('registration.data');
        // Redirect to client home instead of email verification page
        return redirect()->route('client.home')->with('success', __('Registration complete. Welcome!'));
    }

    /**
     * Verify the OTP code sent to guardian email.
     */
    public function verifyOtp(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'otp_code' => ['required', 'digits:6'],
        ]);

        $otp = Session::get('registration.otp');
        if (!$otp || empty($otp['code']) || empty($otp['expires_at'])) {
            return redirect()->route('register.step.show', ['step' => 1])
                ->with('error', __('No verification code found. Please resend.'));
        }

        $expired = now()->gt(\Illuminate\Support\Carbon::parse($otp['expires_at']));
        if ($expired) {
            return redirect()->route('register.step.show', ['step' => 1])
                ->withErrors(['otp_code' => __('Code has expired. Please resend a new code.')]);
        }

        if ($validated['otp_code'] !== $otp['code']) {
            return redirect()->route('register.step.show', ['step' => 1])
                ->withErrors(['otp_code' => __('Invalid code. Please try again.')]);
        }

        // Mark OTP as verified and allow proceeding
        Session::put('registration.otp_verified', true);
        Session::forget('registration.otp_pending');

        return redirect()->route('register.step.show', ['step' => 2])
            ->with('success', __('Email verified. Continue registration.'));
    }

    /**
     * Resend a new OTP code.
     */
    public function resendOtp(Request $request): RedirectResponse
    {
        $data = Session::get('registration.data', []);
        $email = $data['auth']['email'] ?? $data['guardian']['email'] ?? null;
        if (!$email) {
            return redirect()->route('register.step.show', ['step' => 1])
                ->with('error', __('No email to send code to. Please re-enter details.'));
        }

        try {
            $otp = (string) random_int(100000, 999999);
            Session::put('registration.otp', [
                'code' => $otp,
                'email' => $email,
                'expires_at' => now()->addMinutes(10)->toIso8601String(),
            ]);
            Session::put('registration.otp_pending', true);
            Session::forget('registration.otp_verified');

            Mail::to($email)->send(new RegistrationOtpMail($otp));
            return redirect()->route('register.step.show', ['step' => 1])
                ->with('status', __('A new verification code was sent.'));
        } catch (\Throwable $e) {
            return redirect()->route('register.step.show', ['step' => 1])
                ->with('error', __('Failed to resend code. Please try again.'));
        }
    }
}
