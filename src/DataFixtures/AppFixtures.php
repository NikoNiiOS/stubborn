<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;

use App\Entity\Product;
use App\Entity\User;

class AppFixtures extends Fixture
{
    public function __construct(
        private PasswordHasherFactoryInterface $passwordHasherFactory,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $blackbelt = new Product();
        $blackbelt->setName('Blackbelt');
        $blackbelt->setPrice(29.9);
        $blackbelt->setStockXs(2);
        $blackbelt->setStockS(2);
        $blackbelt->setStockM(2);
        $blackbelt->setStockL(2);
        $blackbelt->setStockXl(2);
        $blackbelt->setHomepage(false);
        $manager->persist($blackbelt);

        $bluebelt = new Product();
        $bluebelt->setName('Bluebelt');
        $bluebelt->setPrice(29.9);
        $bluebelt->setStockXs(2);
        $bluebelt->setStockS(2);
        $bluebelt->setStockM(2);
        $bluebelt->setStockL(2);
        $bluebelt->setStockXl(2);
        $bluebelt->setHomepage(false);
        $manager->persist($bluebelt);

        $street = new Product();
        $street->setName('Street');
        $street->setPrice(29.9);
        $street->setStockXs(2);
        $street->setStockS(2);
        $street->setStockM(2);
        $street->setStockL(2);
        $street->setStockXl(2);
        $street->setHomepage(false);
        $manager->persist($street);

        $user = new User();
        $user->setRoles(['ROLE_USER']);
        $user->setUsername('user');
        $user->setPassword($this->passwordHasherFactory->getPasswordHasher(User::class)->hash('user'));
        $user->setDeliveryAddress('user address');
        $user->setEmail('user@mail.com');
        $user->setVerified(true);
        $manager->persist($user);

        $admin = new User();
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setUsername('admin');
        $admin->setPassword($this->passwordHasherFactory->getPasswordHasher(User::class)->hash('admin'));
        $admin->setDeliveryAddress('admin address');
        $admin->setEmail('admin@mail.com');
        $admin->setVerified(true);
        $manager->persist($admin);

        $manager->flush();
    }
}
