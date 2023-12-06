<?php

namespace App\Service;

use App\Form\PurchaseForm;
use App\PaymentProcessor\PaymentService;
use App\PaymentProcessor\PaypalPaymentStrategy;
use App\PaymentProcessor\StripePaymentStrategy;

class PurchaseService
{
    public function __construct(private CalculatorService $calculatorService, private PaymentService $paymentService)
    {
    }

    public function buy(PurchaseForm $form)
    {
        try {
            $price = $this->calculatorService->calculatePrice($form);

            if ($form->getPaymentProcessor() === 'paypal') {
                $this->paymentService->setStrategy(new PaypalPaymentStrategy());
            } else {
                $this->paymentService->setStrategy(new StripePaymentStrategy());
            }

            $this->paymentService->pay($price);
        } catch (\Throwable $e) {
            throw new \Exception('Purchase error', 0, $e);
        }
    }
}
