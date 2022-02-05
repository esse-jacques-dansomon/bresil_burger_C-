<?php

namespace App\DataFixtures;

use App\Entity\Client;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ClientFixture extends Fixture
{

    private UserPasswordHasherInterface $encoder;
    public function __construct(UserPasswordHasherInterface $encoder){
        $this->encoder=$encoder;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr-FR');

        for ($i = 0; $i<10; $i++) {
            $data=new Client;
            $data
                ->setFirstname($faker->firstName())
                ->setLastname($faker->lastName())
                ->setEmail("client".$i."@gmail.com")
                ->setTelephone(221778628471)
                ->setIsVerified(true)
                ->setRoles(["ROLE_CLIENT"]);
            $this->addReference("Client".$i, $data);
            $plainPassword="passer@123";
            $passwordEncode= $this->encoder->hashPassword($data,$plainPassword);
            $data->setPassword($passwordEncode);
            $manager->persist($data);
        }

        $manager->flush();
    }
}
