<?php

namespace App\PaymentProcessor;

use Systemeio\TestForCandidates\PaymentProcessor\StripePaymentProcessor;

class StripePaymentStrategy implements PaymentStrategy
{
    public function pay(float $price): void
    {
        $paymentProcessor = new StripePaymentProcessor();
        if (!$paymentProcessor->processPayment($price)) {
            throw new \Exception('Stripe payment error');
        }
    }
}
