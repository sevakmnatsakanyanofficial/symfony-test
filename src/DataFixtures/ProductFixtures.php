<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    private array $products = [
        'Iphone' => 100,
        'Наушники' => 20,
        'Чехол' => 10
    ];

    public function load(ObjectManager $manager): void
    {
        foreach ($this->products as $name => $price) {
            $product = new Product();
            $product->setName($name);
            $product->setPrice($price);
            $manager->persist($product);
        }

        $manager->flush();
    }
}
