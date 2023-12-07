<?php

namespace App\Tests\Service;

use App\Entity\Coupon;
use App\Entity\Product;
use App\Exception\PurchaseException;
use App\Form\PurchaseForm;
use App\PaymentProcessor\PaymentService;
use App\Repository\CouponRepository;
use App\Repository\ProductRepository;
use App\Service\PurchaseService;
use App\Tests\AbstractTestCase;

class PurchaseServiceTest extends AbstractTestCase
{
    public function testBuyWithPaypal()
    {
        $product = (new Product())->setName('Xiaomi')->setPrice(100);
        $this->setEntityId($product, 1);

        $coupon = (new Coupon())->setType(Coupon::TYPE_PERCENT)->setCode('G123')->setValue(10);

        $productRepository = $this->createMock(ProductRepository::class);
        $productRepository->expects($this->once())
            ->method('find')
            ->willReturn($product);

        $couponRepository = $this->createMock(CouponRepository::class);
        $couponRepository->expects($this->once())
            ->method('findByCode')
            ->willReturn($coupon);

        $form = new PurchaseForm();
        $form->setProductId($product->getId());
        $form->setCouponCode($coupon->getCode());
        $form->setTaxNumber('DE123456789');
        $form->setPaymentProcessor('paypal');

        $service = new PurchaseService($productRepository, $couponRepository, new PaymentService());
        $result = $service->buy($form)->getData()->getAmount();

        $this->assertEquals(72.9, $result);
    }

    public function testBuyWithStripe()
    {
        $product = (new Product())->setName('Xiaomi')->setPrice(200);
        $this->setEntityId($product, 1);

        $coupon = (new Coupon())->setType(Coupon::TYPE_PERCENT)->setCode('G123')->setValue(10);

        $productRepository = $this->createMock(ProductRepository::class);
        $productRepository->expects($this->once())
            ->method('find')
            ->willReturn($product);

        $couponRepository = $this->createMock(CouponRepository::class);
        $couponRepository->expects($this->once())
            ->method('findByCode')
            ->willReturn($coupon);

        $form = new PurchaseForm();
        $form->setProductId($product->getId());
        $form->setCouponCode($coupon->getCode());
        $form->setTaxNumber('DE123456789');
        $form->setPaymentProcessor('stripe');

        $service = new PurchaseService($productRepository, $couponRepository, new PaymentService());
        $result = $service->buy($form)->getData()->getAmount();

        $this->assertEquals(145.8, $result);
    }

    public function testBuyWithIncorrectData()
    {
        $this->expectException(PurchaseException::class);

        $product = (new Product())->setName('Xiaomi')->setPrice(100);
        $this->setEntityId($product, 1);

        $coupon = (new Coupon())->setType(Coupon::TYPE_PERCENT)->setCode('G123')->setValue(10);

        $productRepository = $this->createMock(ProductRepository::class);
        $productRepository->expects($this->once())
            ->method('find')
            ->willReturn($product);

        $couponRepository = $this->createMock(CouponRepository::class);
        $couponRepository->expects($this->once())
            ->method('findByCode')
            ->willReturn($coupon);

        $form = new PurchaseForm();
        $form->setProductId($product->getId());
        $form->setCouponCode($coupon->getCode());
        $form->setTaxNumber('DE123456789');
        $form->setPaymentProcessor('stripe');

        $service = new PurchaseService($productRepository, $couponRepository, new PaymentService());
        $result = $service->buy($form)->getData()->getAmount();
    }
}
