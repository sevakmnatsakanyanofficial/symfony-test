<?php

namespace App\PaymentProcessor;

use Systemeio\TestForCandidates\PaymentProcessor\PaypalPaymentProcessor;

class PaypalPaymentStrategy implements PaymentStrategy
{
    public function pay(float $price): void
    {
        try {
            $paymentProcessor = new PaypalPaymentProcessor();
            $paymentProcessor->pay((int)$price);
        } catch (\Throwable $e) {
            throw new \Exception('Paypal payment error', 0, $e);
        }
    }
}
