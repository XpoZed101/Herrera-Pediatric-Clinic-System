<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        // Determine role-aware default destination
        $role = (optional($request->user())->role ?? 'patient');
        $default = $role === 'admin'
            ? route('admin.dashboard', absolute: false)
            : ($role === 'staff'
                ? route('staff.welcome', absolute: false)
                : route('client.home', absolute: false));

        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended($default.'?verified=1');
        }

        $request->fulfill();

        return redirect()->intended($default.'?verified=1');
    }
}
