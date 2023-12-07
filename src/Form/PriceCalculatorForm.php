<?php

namespace App\Form;

use App\Validator\CorrectTaxNumber;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\NotBlank;

class PriceCalculatorForm implements CalculableFormInterface
{
    #[NotBlank]
    private int $productId;

    #[NotBlank]
    #[CorrectTaxNumber]
    private string $taxNumber;

    private ?string $couponCode;


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

    public function load(Request $request): void
    {
        $data = $request->getPayload();

        $this->setProductId($data->get('productId'));
        $this->setTaxNumber($data->get('taxNumber'));
        $this->setCouponCode($data->get('couponCode'));
    }
}
