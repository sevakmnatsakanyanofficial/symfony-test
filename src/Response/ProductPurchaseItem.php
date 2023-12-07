<?php

namespace App\Response;

class ProductPurchaseItem
{
    public function __construct(private float $amount)
    {
    }

    public function getAmount(): float
    {
        return $this->amount;
    }
}
