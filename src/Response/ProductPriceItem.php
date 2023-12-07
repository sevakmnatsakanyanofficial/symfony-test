<?php

namespace App\Response;

class ProductPriceItem
{
    public function __construct(private float $price)
    {
    }

    public function getPrice(): float
    {
        return $this->price;
    }
}
