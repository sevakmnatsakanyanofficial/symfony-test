<?php

namespace App\Service;

use App\Calculator\ProductPriceCalculator;
use App\Form\PurchaseForm;
use App\PaymentProcessor\PaymentService;
use App\PaymentProcessor\PaypalPaymentStrategy;
use App\PaymentProcessor\StripePaymentStrategy;
use App\Repository\CouponRepository;
use App\Repository\ProductRepository;
use App\Response\ProductPurchaseItem;
use App\Response\ProductPurchaseResponse;

class PurchaseService
{
    public function __construct(
        private ProductRepository $productRepository,
        private CouponRepository $couponRepository,
        private PaymentService $paymentService) {
    }

    public function buy(PurchaseForm $form): ProductPurchaseResponse
    {
        try {
            if (!($product = $this->productRepository->find($form->getProductId()))) {
                throw new \Exception('Not Fount Product: '.$form->getProductId());
            }

            $coupon = null;
            if ($form->getCouponCode() !== null
                && !($coupon = $this->couponRepository->findByCode($form->getCouponCode()))) {
                throw new \Exception('Not Fount Coupon Code: '.$form->getCouponCode());
            }

            $price = (new ProductPriceCalculator($product, $coupon, $form->getTaxNumber()))->calculate();

            // TODO: move to const of enum
            if ($form->getPaymentProcessor() === 'paypal') {
                $this->paymentService->setStrategy(new PaypalPaymentStrategy());
            } else {
                $this->paymentService->setStrategy(new StripePaymentStrategy());
            }

            $this->paymentService->pay($price);

            return new ProductPurchaseResponse(new ProductPurchaseItem($price));
        } catch (\Throwable $e) {
            throw new \Exception($e->getMessage(), 0, $e);
        }
    }
}
