<?php

namespace App\Service;

use App\Calculator\ProductPriceCalculator;
use App\Exception\PaypalPaymentException;
use App\Exception\PurchaseException;
use App\Exception\StripePaymentException;
use App\Form\PurchaseForm;
use App\PaymentProcessor\PaymentService;
use App\PaymentProcessor\PaypalPaymentStrategy;
use App\PaymentProcessor\StripePaymentStrategy;
use App\Repository\CouponRepository;
use App\Repository\ProductRepository;
use App\Response\ProductPurchaseItem;
use App\Response\ProductPurchaseResponse;
use Doctrine\ORM\EntityNotFoundException;

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
                throw new EntityNotFoundException('Not Fount Product: '.$form->getProductId());
            }

            $coupon = null;
            if ($form->getCouponCode() !== null
                && !($coupon = $this->couponRepository->findByCode($form->getCouponCode()))) {
                throw new EntityNotFoundException('Not Fount Coupon Code: '.$form->getCouponCode());
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
        } catch (PaypalPaymentException|StripePaymentException $e) {
            throw new PurchaseException($e->getMessage(), 400, $e);
        } catch (\Throwable $e) {
            throw new PurchaseException('Not Planned Purchase Error', 400, $e);
        }
    }
}
