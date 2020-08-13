<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\DataFixtures\CustomerFixtures;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    public const ROLES = array('ROLE_USER');

    public function load(ObjectManager $manager)
    {
        foreach (range(0, 10) as $number) {
            $user = new User();            
            $user->setUsername('user' . $number);
            $user->setEmail("email" . $number . "@gmail.com");
            $userPassword = password_hash('user' . $number . 'ApiProduct',PASSWORD_BCRYPT);
            $user->setPassword($userPassword);
            $user->setRoles(self::ROLES);
            if($number < 5){
                $user->setCustomer($this->getReference(CustomerFixtures::USER_FNAC));
            }else{
                $user->setCustomer($this->getReference(CustomerFixtures::USER_BACKMARKET));
            }
            $manager->persist($user);
        }
        
        $manager->flush();
    }
    
    public function getDependencies()
    {
        return array(
            CustomerFixtures::class,
        );
    }
}