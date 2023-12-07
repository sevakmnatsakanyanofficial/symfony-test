<?php

namespace App\Response;

class ProductPurchaseResponse implements Linkable
{
    /**
     * @param ProductPurchaseItem $data
     */
    public function __construct(private ProductPurchaseItem $data)
    {
    }

    /**
     * @return ProductPurchaseItem
     */
    public function getData(): ProductPurchaseItem
    {
        return $this->data;
    }

    public function getLinks(): array
    {
        // TODO: change to dynamic url generator
        return [
            'self' => 'http://symfonyapi.loc/api/purchase',
        ];
    }
}
