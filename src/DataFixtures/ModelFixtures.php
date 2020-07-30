<?php

namespace App\DataFixtures;
use DateTime;

use App\Entity\Model;
use App\DataFixtures\BrandFixtures;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ModelFixtures extends Fixture implements DependentFixtureInterface
{
    public const MODELS = ['iphone8', 'iphoneX', 'iphoneXS', 'galaxyS10', 'galaxyS20', 'p40'];
        
    public function load(ObjectManager $manager)
    {
        $iphone8 = new Model();
        $iphone8->setName(self::MODELS[0]);
        $iphone8->setOs("IOS");
        $iphone8->setReleaseDate(new DateTime('NOW'));
        $iphone8->setBrand($this->getReference(BrandFixtures::LIST[0]));
        $this->addReference(self::MODELS[0], $iphone8);
        $manager->persist($iphone8);

        $iphoneX = new Model();
        $iphoneX->setName(self::MODELS[1]);
        $iphoneX->setOs("IOS");
        $iphoneX->setReleaseDate(new DateTime('NOW'));
        $iphoneX->setBrand($this->getReference(BrandFixtures::LIST[0]));
        $this->addReference(self::MODELS[1], $iphoneX);
        $manager->persist($iphoneX);
        
        $iphoneXS = new Model();
        $iphoneXS->setName(self::MODELS[2]);
        $iphoneXS->setOs("IOS");
        $iphoneXS->setReleaseDate(new DateTime('NOW'));
        $iphoneXS->setBrand($this->getReference(BrandFixtures::LIST[0]));
        $this->addReference(self::MODELS[2], $iphoneXS);
        $manager->persist($iphoneXS);
        
        $galaxyS10 = new Model();
        $galaxyS10->setName(self::MODELS[3]);
        $galaxyS10->setOs("Android");
        $galaxyS10->setReleaseDate(new DateTime('NOW'));
        $galaxyS10->setBrand($this->getReference(BrandFixtures::LIST[1]));
        $this->addReference(self::MODELS[3], $galaxyS10);
        $manager->persist($galaxyS10);
        
        $galaxyS20 = new Model();
        $galaxyS20->setName(self::MODELS[4]);
        $galaxyS20->setOs("Android");
        $galaxyS20->setReleaseDate(new DateTime('NOW'));
        $galaxyS20->setBrand($this->getReference(BrandFixtures::LIST[1]));
        $this->addReference(self::MODELS[4], $galaxyS20);
        $manager->persist($galaxyS20);

        $p40 = new Model();
        $p40->setName(self::MODELS[5]);
        $p40->setOs("Android");
        $p40->setReleaseDate(new DateTime('NOW'));
        $p40->setBrand($this->getReference(BrandFixtures::LIST[3]));
        $this->addReference(self::MODELS[5], $p40);
        $manager->persist($p40);

        $manager->flush();
    }
    
    public function getDependencies()
    {
        return array(
            BrandFixtures::class,
        );
    }
}