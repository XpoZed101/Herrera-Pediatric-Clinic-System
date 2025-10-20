<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Payment;
use App\Models\VisitType;
use App\Services\Payments\PaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

class PaymentController extends Controller
{
    public function checkout(Appointment $appointment, PaymentService $payments): RedirectResponse
    {
        abort_unless(Auth::id() === $appointment->user_id, 403);

        // Use dynamic pricing from VisitType by slug
        $type = VisitType::where('slug', $appointment->visit_type)->first();
        $amount = $type ? (int) $type->amount_cents : 1000 * 100; // centavos

        $payment = Payment::create([
            'user_id' => $appointment->user_id,
            'appointment_id' => $appointment->id,
            'amount' => $amount,
            'currency' => 'PHP',
            'status' => 'pending',
            'provider' => 'paymongo',
            'metadata' => [
                'appointment_visit_type' => $appointment->visit_type,
            ],
        ]);

        $successUrl = URL::route('client.payments.success', $payment);
        $cancelUrl = URL::route('client.payments.cancel', $payment);

        $name = $type ? ($type->name . ' Fee') : 'Appointment Fee';

        $checkout = $payments->createCheckout($amount, 'PHP', [
            'success_url' => $successUrl,
            'cancel_url' => $cancelUrl,
            'description' => $type ? ('Clinic ' . $type->name . ' #' . $appointment->id) : ('Clinic Appointment #' . $appointment->id),
            'reference_id' => 'apt-' . $appointment->id . '-pay-' . $payment->id,
            'payment_method_types' => config('services.paymongo.allowed_methods'),
            'name' => $name,
        ]);

        $payment->update([
            'provider_session_id' => $checkout['session_id'] ?? null,
            'checkout_url' => $checkout['checkout_url'] ?? null,
        ]);

        return Redirect::to($checkout['checkout_url']);
    }

    public function success(Payment $payment): RedirectResponse
    {
        abort_unless(Auth::id() === $payment->user_id, 403);
        $payment->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        return redirect()->route('client.appointments.create')
            ->with('status', 'Payment successful. Thank you!');
    }

    public function cancel(Payment $payment): RedirectResponse
    {
        abort_unless(Auth::id() === $payment->user_id, 403);
        $payment->update(['status' => 'cancelled']);

        return redirect()->route('client.appointments.create')
            ->with('status', 'Payment cancelled.');
    }
}