<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class BillingController extends Controller
{
    public function history(): View
    {
        $userId = Auth::id();

        $status = request('status');
        $query = Payment::with(['appointment'])
            ->where('user_id', $userId);
        if (in_array($status, ['paid', 'pending', 'cancelled'])) {
            $query->where('status', $status);
        }

        $payments = $query->latest('created_at')->paginate(10)->withQueryString();

        $paidTotal = Payment::where('user_id', $userId)
            ->where('status', 'paid')
            ->sum('amount');
        $pendingCount = Payment::where('user_id', $userId)
            ->where('status', 'pending')
            ->count();
        $lastPaidAt = Payment::where('user_id', $userId)
            ->whereNotNull('paid_at')
            ->latest('paid_at')
            ->value('paid_at');

        return view('client.billing.history', [
            'payments' => $payments,
            'stats' => [
                'paid_total' => $paidTotal,
                'pending_count' => $pendingCount,
                'last_paid_at' => $lastPaidAt,
            ],
        ]);
    }
}