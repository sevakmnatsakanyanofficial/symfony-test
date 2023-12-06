<?php

namespace App\DataFixtures;

use App\Entity\Coupon;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CouponFixtures extends Fixture
{
    private array $coupons = [
        Coupon::TYPE_PERCENT => 10,
        Coupon::TYPE_AMOUNT => 200,
    ];

    public function load(ObjectManager $manager): void
    {
        foreach ($this->coupons as $name => $price) {
            $product = new Coupon();
            $product->setType($name);
            $product->setCode($price);
            $product->setValue(rand(10, 50));
            $manager->persist($product);
        }

        $manager->flush();
    }
}
