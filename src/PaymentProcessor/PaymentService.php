<?php

namespace App\PaymentProcessor;

class PaymentService
{
    private PaymentStrategy $paymentStrategy;


    public function setStrategy(PaymentStrategy $paymentStrategy)
    {
        $this->paymentStrategy = $paymentStrategy;
    }

    public function pay(float $price)
    {
        $this->paymentStrategy->pay($price);
    }
}
