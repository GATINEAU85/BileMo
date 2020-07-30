<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public const USER_REFERENCE = 'user-gatineau';
    public const ROLES = array('ROLE_ADMIN');

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setEmail('gatineau85@gmail.com');
        $password = password_hash('gatineau85',PASSWORD_BCRYPT);
        $user->setPassword($password);
        $user->setRoles(self::ROLES);
        $this->addReference(self::USER_REFERENCE, $user);

        $manager->persist($user);
        $manager->flush();
    }
}