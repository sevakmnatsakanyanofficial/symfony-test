<?php

namespace App\Calculator;

use App\Entity\Coupon;
use App\Entity\Product;
use App\Helper\TaxHelper;

class ProductPriceCalculator
{
    public function __construct(private Product $product, private ?Coupon $coupon, private ?string $taxNumber)
    {
    }

    public function calculate(): float
    {
        $price = $this->product->getPrice();
        if ($this->coupon) {
            $price = ($this->coupon->isAmountType() ? $price - $this->coupon->getValue() : $price - ($price*$this->coupon->getValue()/100));
        }

        $taxValue = TaxHelper::getPercentValueByNumber($this->taxNumber);
        return $price - ($price*$taxValue/100);
    }
}
