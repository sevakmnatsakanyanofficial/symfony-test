<?php

namespace App\Service;

use App\Form\CalculableFormInterface;
use App\Helper\TaxHelper;
use App\Repository\CouponRepository;
use App\Repository\ProductRepository;

class CalculatorService
{
    private ProductRepository $productRepository;
    private CouponRepository $couponRepository;


    public function __construct(ProductRepository $productRepository, CouponRepository $couponRepository)
    {
        $this->productRepository = $productRepository;
        $this->couponRepository = $couponRepository;
    }

    public function calculatePrice(CalculableFormInterface $form): float
    {
        if (!($product = $this->productRepository->find($form->getProductId()))) {
            throw new \Exception('Not Fount Product: '.$form->getProductId());
        }

        $coupon = null;
        if ($form->getCouponCode() !== null
            && !($coupon = $this->couponRepository->findByCode($form->getCouponCode()))) {
            throw new \Exception('Not Fount Coupon Code: '.$form->getCouponCode());
        }

        $price = $product->getPrice();
        if ($coupon) {
            $price = ($coupon->isAmountType() ? $price - $coupon->getValue() : $price - ($price*$coupon->getValue()/100));
        }

        $taxValue = TaxHelper::getPercentValueByNumber($form->getTaxNumber());
        $price = $price - ($price*$taxValue/100);

        return $price;
    }
}
