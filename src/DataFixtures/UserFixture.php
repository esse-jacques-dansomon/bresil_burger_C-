<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Faker\Factory;

class UserFixture extends Fixture
{

    private UserPasswordHasherInterface $encoder;
    public function __construct(UserPasswordHasherInterface $encoder){
        $this->encoder=$encoder;
    }

    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $roles=["ROLE_MANAGER","ROLE_ADMINISTRATOR"];
        $faker = Factory::create('fr-FR');

        foreach ($roles as $libelle) {
            $data=new User;
            $data->setFirstname($faker->firstNameMale())
                ->setLastname($faker->name())
                ->setEmail(strtolower($libelle)."@gmail.com")
                ->setIsVerified(true)
                ->setRoles([$libelle]);
            $plainPassword="passer@123";
            $passwordEncode= $this->encoder->hashPassword($data,$plainPassword);
            $data->setPassword($passwordEncode);
            $manager->persist($data);
        }
        $manager->flush();
    }
}
