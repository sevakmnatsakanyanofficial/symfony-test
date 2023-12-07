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
use Doctrine\ORM\EntityManagerInterface;

class PurchaseServiceTest extends AbstractTestCase
{
    private Product $product;
    private Coupon $coupon;

    private ProductRepository $productRepository;
    private CouponRepository $couponRepository;

    private EntityManagerInterface $em;


    public function setUp(): void
    {
        parent::setUp();

        $this->product = (new Product())->setName('Xiaomi')->setPrice(100);
        $this->setEntityId($this->product, 1);

        $this->coupon = (new Coupon())->setType(Coupon::TYPE_PERCENT)->setCode('G123')->setValue(10);
        $this->productRepository = $this->createMock(ProductRepository::class);
        $this->productRepository->expects($this->once())
            ->method('find')
            ->willReturn($this->product);

        $this->couponRepository = $this->createMock(CouponRepository::class);
        $this->couponRepository->expects($this->once())
            ->method('findByCode')
            ->willReturn($this->coupon);

        $this->em = $this->createMock(EntityManagerInterface::class);
    }

    public function testBuyWithPaypal()
    {
        $form = new PurchaseForm();
        $form->setProductId($this->product->getId());
        $form->setCouponCode($this->coupon->getCode());
        $form->setTaxNumber('DE123456789');
        $form->setPaymentProcessor('paypal');

        $service = new PurchaseService($this->productRepository, $this->couponRepository, new PaymentService(), $this->em);
        $result = $service->buy($form)->getData()->getAmount();

        $this->assertEquals(72.9, $result);
    }

    public function testBuyWithStripe()
    {
        $product = $this->product;
        $product->setPrice(200);

        $form = new PurchaseForm();
        $form->setProductId($product->getId());
        $form->setCouponCode($this->coupon->getCode());
        $form->setTaxNumber('DE123456789');
        $form->setPaymentProcessor('stripe');

        $service = new PurchaseService($this->productRepository, $this->couponRepository, new PaymentService(), $this->em);
        $result = $service->buy($form)->getData()->getAmount();

        $this->assertEquals(145.8, $result);
    }

    public function testBuyWithIncorrectData()
    {
        $this->expectException(PurchaseException::class);

        $product = (new Product())->setName('Xiaomi')->setPrice(100);
        $this->setEntityId($product, 1);

        $form = new PurchaseForm();
        $form->setProductId($this->product->getId());
        $form->setCouponCode($this->coupon->getCode());
        $form->setTaxNumber('DE123456789');
        $form->setPaymentProcessor('stripe');

        $service = new PurchaseService($this->productRepository, $this->couponRepository, new PaymentService(), $this->em);
        $result = $service->buy($form)->getData()->getAmount();
    }
}
