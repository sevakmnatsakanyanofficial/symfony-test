<?php

namespace App\Service;

use App\Calculator\ProductPriceCalculator;
use App\Entity\Coupon;
use App\Entity\Product;
use App\Entity\Purchase;
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
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;

class PurchaseService
{
    public function __construct(
        private ProductRepository $productRepository,
        private CouponRepository $couponRepository,
        private PaymentService $paymentService,
        private EntityManagerInterface $entityManager) {
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
            $purchase = $this->createPurchase($product, $price, $form->getPaymentProcessor(), $coupon, $form->getTaxNumber());

            $this->commitPurchase($purchase);

            $this->initPaymentService($form->getPaymentProcessor());
            $this->paymentService->pay($price);

            return new ProductPurchaseResponse(new ProductPurchaseItem($price));
        } catch (PaypalPaymentException|StripePaymentException $e) {
            throw new PurchaseException($e->getMessage(), 400, $e);
        } catch (\Throwable $e) {
            throw new PurchaseException($e->getMessage(), 400, $e);
        }
    }


    private function createPurchase(Product $product, float $price, string $paymentType, ?Coupon $coupon, ?string $taxNumber): Purchase
    {
        $purchase = new Purchase();
        $purchase->setProduct($product);
        $purchase->setCoupon($coupon);
        $purchase->setTaxNumber($taxNumber);
        $purchase->setPrice($price);
        $purchase->setPaymentType($paymentType);
        $purchase->setPaymentStatus(Purchase::PAYMENT_STATUS_PENDING);
        $purchase->setStatus(Purchase::STATUS_CREATED);
        $purchase->setCreatedAt(new \DateTimeImmutable());

        return $purchase;
    }

    private function initPaymentService(string $paymentProcessor): void
    {
        // TODO: move to const of enum
        if ($paymentProcessor === 'paypal') {
            $this->paymentService->setStrategy(new PaypalPaymentStrategy());
        } else {
            $this->paymentService->setStrategy(new StripePaymentStrategy());
        }
    }

    private function commitPurchase(Purchase $purchase)
    {
        $this->entityManager->getConnection()->beginTransaction();
        try {
            $this->entityManager->persist($purchase);
            $this->entityManager->flush();
            $this->entityManager->commit();
        } catch (\Throwable $e) {
            $this->entityManager->rollback();
            throw new \Exception($e->getMessage());
        }
    }
}
