<?php

namespace App\DataFixtures;

use App\Entity\Coupon;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CouponFixtures extends Fixture
{
    private array $coupons = [
        Coupon::TYPE_PERCENT => [
            'code' => 'G123',
            'value' => 10,
        ],
        Coupon::TYPE_AMOUNT => [
            'code' => 'CF443',
            'value' => 20,
        ],
    ];


    public function load(ObjectManager $manager): void
    {
        foreach ($this->coupons as $type => $data) {
            $product = new Coupon();
            $product->setType($type);
            $product->setCode($data['code']);
            $product->setValue($data['value']);
            $manager->persist($product);
        }

        $manager->flush();
    }
}
