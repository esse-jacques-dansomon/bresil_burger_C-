<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\String\Slugger\AsciiSlugger;

class CategoryFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $classes=["Burger","Menu","","Boisson","Frite"];
        $faker = Factory::create('fr-FR');
        $slugger = new AsciiSlugger();

        foreach ($classes as $i=> $libelle) {
            $data=new Category();
            $data
                ->setName($libelle)
                ->setDescription($faker->paragraph)
                ->setSlug(strtolower($slugger->slug($data->getName())))
                ->setImage($faker->imageUrl());
            $this->addReference("Category".$libelle, $data);
            $manager->persist($data);
        }

        $manager->flush();
    }
}
