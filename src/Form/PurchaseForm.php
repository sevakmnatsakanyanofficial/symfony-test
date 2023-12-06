<?php

namespace App\Form;

use App\Validator\CorrectTaxNumber;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\NotBlank;

class PurchaseForm implements CalculableFormInterface
{
    #[NotBlank]
    private int $productId;

    #[NotBlank]
    #[CorrectTaxNumber]
    private string $taxNumber;

    private ?string $couponCode;

    #[NotBlank]
    #[Choice(
        choices: ['paypal', 'stripe'],
        message: 'The value you selected is not a valid payment processor.'
    )]
    private string $paymentProcessor;


    public function getProductId(): int
    {
        return $this->productId;
    }

    public function setProductId(int $productId): void
    {
        $this->productId = $productId;
    }

    public function getTaxNumber(): ?string
    {
        return $this->taxNumber;
    }

    public function setTaxNumber(string $taxNumber): void
    {
        $this->taxNumber = $taxNumber;
    }

    public function getCouponCode(): ?string
    {
        return $this->couponCode;
    }

    public function setCouponCode(?string $couponCode): void
    {
        $this->couponCode = $couponCode;
    }

    public function getPaymentProcessor(): string
    {
        return $this->paymentProcessor;
    }

    public function setPaymentProcessor(string $paymentProcessor): void
    {
        $this->paymentProcessor = $paymentProcessor;
    }

    public function load(Request $request): void
    {
        $this->setProductId($request->get('productId'));
        $this->setTaxNumber($request->get('taxNumber'));
        $this->setCouponCode($request->get('couponCode'));
        $this->setPaymentProcessor($request->get('paymentProcessor'));
    }
}
