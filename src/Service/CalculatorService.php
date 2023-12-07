<?php

namespace App\Service;

use App\Calculator\ProductPriceCalculator;
use App\Form\CalculableFormInterface;
use App\Repository\CouponRepository;
use App\Repository\ProductRepository;
use App\Response\ProductPriceItem;
use App\Response\ProductPriceResponse;
use Doctrine\ORM\EntityNotFoundException;

class CalculatorService
{
    public function __construct(
        private ProductRepository $productRepository,
        private CouponRepository $couponRepository) {
    }

    public function calculatePrice(CalculableFormInterface $form): ProductPriceResponse
    {
        if (!($product = $this->productRepository->find($form->getProductId()))) {
            throw new EntityNotFoundException('Not Fount Product: '.$form->getProductId());
        }

        $coupon = null;
        if ($form->getCouponCode() !== null
            && !($coupon = $this->couponRepository->findByCode($form->getCouponCode()))) {
            throw new EntityNotFoundException('Not Fount Coupon Code: '.$form->getCouponCode());
        }

        $price = (new ProductPriceCalculator($product, $coupon, $form->getTaxNumber()))->calculate();

        return new ProductPriceResponse(new ProductPriceItem($price));
    }
}
