<?php

namespace App\Tests\Service;

use App\Entity\Coupon;
use App\Entity\Product;
use App\Form\PriceCalculatorForm;
use App\Repository\CouponRepository;
use App\Repository\ProductRepository;
use App\Service\CalculatorService;
use App\Tests\AbstractTestCase;

class CalculatorServiceTest extends AbstractTestCase
{
    public function testCalculatePrice(): void
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

        $form = new PriceCalculatorForm();
        $form->setProductId($product->getId());
        $form->setCouponCode($coupon->getCode());
        $form->setTaxNumber('DE123456789');

        $service = new CalculatorService($productRepository, $couponRepository);
        $result = $service->calculatePrice($form);

        $this->assertEquals(72.9, $result);
    }
}
