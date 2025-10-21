<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Payment;
use App\Models\VisitType;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class BillingController extends Controller
{
    public function index(): View
    {
        abort_unless(Auth::check() && (Auth::user()->role ?? null) === 'staff', 403);

        $status = request('status');
        $query = Payment::with(['appointment', 'user']);
        if (in_array($status, ['paid', 'pending', 'cancelled'])) {
            $query->where('status', $status);
        }
        $payments = $query->latest('created_at')->paginate(10)->withQueryString();

        $paidTotal = Payment::where('status', 'paid')->sum('amount');
        $pendingCount = Payment::where('status', 'pending')->count();
        $lastPaidAt = Payment::whereNotNull('paid_at')->latest('paid_at')->value('paid_at');

        return view('staff.billing.index', [
            'payments' => $payments,
            'stats' => [
                'paid_total' => $paidTotal,
                'pending_count' => $pendingCount,
                'last_paid_at' => $lastPaidAt,
            ],
        ]);
    }

    public function show(Payment $payment): View
    {
        abort_unless(Auth::check() && (Auth::user()->role ?? null) === 'staff', 403);

        $payment->load(['appointment.user']);
        return view('staff.billing.show', compact('payment'));
    }

    public function markPaid(Request $request, Payment $payment): RedirectResponse
    {
        abort_unless(Auth::check() && (Auth::user()->role ?? null) === 'staff', 403);

        $data = $request->validate([
            'amount_php' => ['nullable', 'numeric', 'min:0'],
            'payment_method' => ['nullable', 'string', 'in:cash,card,bank_transfer'],
            'reference' => ['nullable', 'string', 'max:64', 'required_if:payment_method,card,bank_transfer'],
        ]);

        $update = [
            'status' => 'paid',
            'paid_at' => now(),
        ];
        if (isset($data['amount_php'])) {
            $update['amount'] = (int) round($data['amount_php'] * 100);
        }
        if (isset($data['payment_method'])) {
            $update['payment_method'] = $data['payment_method'];
        } else {
            $update['payment_method'] = $payment->payment_method ?? 'cash';
        }
        if (isset($data['reference'])) {
            $update['reference'] = trim($data['reference']);
        }

        $payment->update($update);

        return redirect()->route('staff.billing.payments.show', $payment)
            ->with('status', 'Payment marked as paid.');
    }

    public function createPayment(Appointment $appointment): RedirectResponse
    {
        abort_unless(Auth::check() && (Auth::user()->role ?? null) === 'staff', 403);

        // If there is already a pending payment for this appointment, redirect to it.
        $existing = Payment::where('appointment_id', $appointment->id)
            ->where('status', 'pending')
            ->latest()
            ->first();
        if ($existing) {
            return redirect()->route('staff.billing.payments.show', $existing)
                ->with('status', 'Pending payment already exists for this appointment.');
        }

        $type = VisitType::where('slug', $appointment->visit_type)->first();
        $amount = $type ? (int) $type->amount_cents : 1000 * 100; // centavos

        $payment = Payment::create([
            'user_id' => $appointment->user_id,
            'appointment_id' => $appointment->id,
            'amount' => $amount,
            'currency' => 'PHP',
            'status' => 'pending',
            'provider' => 'manual',
            'payment_method' => null,
            'metadata' => [
                'appointment_visit_type' => $appointment->visit_type,
                'created_by' => 'staff:' . Auth::id(),
            ],
        ]);

        return redirect()->route('staff.billing.payments.show', $payment)
            ->with('status', 'Payment record created. Ready to process.');
    }
}
