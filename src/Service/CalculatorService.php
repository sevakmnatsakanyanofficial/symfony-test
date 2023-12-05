<?php

namespace App\Service;

use App\Repository\ProductRepository;

class CalculatorService
{
    private ProductRepository $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function calculatePrice(): array
    {
        $products = $this->productRepository->findAll();
        $sum = 0;
        foreach ($products as $product) {
            $sum = $sum + $product->getPrice();
        }

        return [
            'result' => $sum,
        ];
    }
}
