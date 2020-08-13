<?php

namespace App\DataFixtures;

use App\Entity\Brand;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class BrandFixtures extends Fixture
{
    public const LIST = ['Apple', 'Samsung', 'Wiko', 'Huawei', 'Nokia', 'Sony', 'LG'];

    public function load(ObjectManager $manager)
    {
        foreach(self::LIST as $key => $elmnt){
            $brand = new Brand();
            $brand->setName($elmnt);
            $this->addReference(self::LIST[$key], $brand);
            $manager->persist($brand);
        }
        $manager->flush();
    }
}