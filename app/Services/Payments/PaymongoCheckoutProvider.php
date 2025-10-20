<?php

namespace App\Services\Payments;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class PaymongoCheckoutProvider implements PaymentProviderInterface
{
    public function createCheckout(int $amount, string $currency, array $options = []): array
    {
        $secret = config('services.paymongo.secret_key');
        $baseUrl = rtrim(config('services.paymongo.checkout_base_url'), '/');
        $methods = $options['payment_method_types'] ?? config('services.paymongo.allowed_methods');
        $successUrl = $options['success_url'] ?? url('/payments/success');
        $cancelUrl = $options['cancel_url'] ?? url('/payments/cancel');
        $description = $options['description'] ?? 'Clinic Appointment Payment';
        $referenceId = $options['reference_id'] ?? Str::uuid()->toString();
        $name = $options['name'] ?? 'Clinic Appointment Fee';

        // PayMongo requires Basic auth with secret key + ':'
        $auth = base64_encode($secret . ':');

        $payload = [
            'data' => [
                'attributes' => [
                    'line_items' => [[
                        'amount' => $amount, // centavos
                        'currency' => $currency,
                        'description' => $description,
                        'name' => $name,
                        'quantity' => 1,
                    ]],
                    'payment_method_types' => array_values($methods),
                    'success_url' => $successUrl,
                    'cancel_url' => $cancelUrl,
                    'reference_id' => $referenceId,
                ],
            ],
        ];

        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . $auth,
            'Content-Type' => 'application/json',
        ])->post($baseUrl, $payload);

        if (!$response->successful()) {
            throw new \RuntimeException('PayMongo checkout creation failed: ' . $response->body());
        }

        $data = $response->json('data.attributes');

        return [
            'session_id' => $response->json('data.id'),
            'checkout_url' => $data['checkout_url'] ?? $data['checkout_url_with_gcash'] ?? null,
            'payment_method_types' => $data['payment_method_types'] ?? [],
        ];
    }
}