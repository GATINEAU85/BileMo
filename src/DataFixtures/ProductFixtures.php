<?php

namespace App\DataFixtures;

use App\Entity\Product;
use App\DataFixtures\ModelFixtures;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ProductFixtures extends Fixture implements DependentFixtureInterface
{
    private const MEMORY_STOCKAGE = [8,16,32,64,128];
    private const COLORS = ['white', 'black', 'red', 'blue'];
    private const BASE_REF = 4006381333931;

    public function load(ObjectManager $manager)
    {
        $i = 0;
        foreach (ModelFixtures::MODELS as $model) {
            foreach (range(0, 10) as $number) {
                $product = new Product();
                $product->setRef(strval(self::BASE_REF . $i . $number));
                $product->setDescription("Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.");
                $product->setColor(self::COLORS[array_rand(self::COLORS, 1)]);
                $product->setMemoryStockage(self::MEMORY_STOCKAGE[array_rand(self::MEMORY_STOCKAGE, 1)]);
                $product->setPrice(random_int(1, 1500));
                $product->setModel($this->getReference($model));
                $manager->persist($product);
            }
            $i++;
        }
        $manager->flush();
    }
    
    public function getDependencies()
    {
        return array(
            ModelFixtures::class,
        );
    }
}