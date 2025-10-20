<?php

namespace App\Services\Payments;

interface PaymentProviderInterface
{
    /**
     * Create a checkout session and return provider-specific identifiers.
     *
     * @param int $amount Amount in centavos
     * @param string $currency ISO currency (e.g., 'PHP')
     * @param array $options Additional options like success_url, cancel_url, description, reference_id, payment_method_types
     * @return array { session_id: string, checkout_url: string, payment_method_types: array }
     */
    public function createCheckout(int $amount, string $currency, array $options = []): array;
}
