<?php

namespace App\PaymentProcessor;

use App\Exception\PaypalPaymentException;
use Systemeio\TestForCandidates\PaymentProcessor\PaypalPaymentProcessor;

class PaypalPaymentStrategy implements PaymentStrategy
{
    public function pay(float $price): void
    {
        try {
            $paymentProcessor = new PaypalPaymentProcessor();
            $paymentProcessor->pay((int)$price);
        } catch (\Throwable $e) {
            throw new PaypalPaymentException();
        }
    }
}
