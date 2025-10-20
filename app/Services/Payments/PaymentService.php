<?php

namespace App\Services\Payments;

class PaymentService
{
    public function __construct(private readonly PaymentProviderInterface $provider)
    {
    }

    public function createCheckout(int $amount, string $currency, array $options = []): array
    {
        return $this->provider->createCheckout($amount, $currency, $options);
    }
}