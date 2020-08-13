<?php
namespace App\DataFixtures;

use App\Entity\Customer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class CustomerFixtures extends Fixture
{
    public const USER_FNAC = 'FNAC';
    public const USER_BACKMARKET = 'Backmarket';
    
    public function load(ObjectManager $manager)
    {
        $fnacCustomer = new Customer();
        $fnacCustomer->setName("FNAC");        
        $this->addReference(self::USER_FNAC, $fnacCustomer);
        $manager->persist($fnacCustomer);

        $backmarketCustomer = new Customer();
        $backmarketCustomer->setName("Backmarket");        
        $this->addReference(self::USER_BACKMARKET, $backmarketCustomer);
        $manager->persist($backmarketCustomer);
        

        $manager->flush();
    }
}
