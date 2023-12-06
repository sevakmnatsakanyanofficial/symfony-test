<?php

namespace App\PaymentProcessor;

interface PaymentStrategy
{
    public function pay(float $price): void;
}
