<?php

namespace App\Response;

class ProductPriceResponse implements Linkable
{
    /**
     * @param ProductPriceItem $data
     */
    public function __construct(private ProductPriceItem $data)
    {
    }

    /**
     * @return ProductPriceItem
     */
    public function getData(): ProductPriceItem
    {
        return $this->data;
    }

    public function getLinks(): array
    {
        // TODO: change to dynamic url generator
        return [
            'self' => 'http://symfonyapi.loc/api/calculate-price',
        ];
    }
}
