<?php

namespace App\DataFixtures;

use App\Entity\Burger;
use App\Entity\Category;
use App\Entity\Complement;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\String\Slugger\AsciiSlugger;

class ComplementFixture extends Fixture implements  DependentFixtureInterface
{

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr-FR');
        $slugger = new AsciiSlugger();
        for ($i = 0; $i<10; $i++) {
            $data=new Complement();
            $data
                ->setName('Boisson '.$faker->city())
                ->setPrice($faker->numberBetween(500, 5000))
                ->setImage($faker->imageUrl(640, 480, 'boisson canette'))
                ->setSlug(strtolower($slugger->slug($data->getName())))
                ->setUpdatedAt(new \DateTime())
                ->setCategory($this->getReference('CategoryBoisson'));
            $this->addReference("Boisson".$i, $data);
            $manager->persist($data);
        }

        for ($i = 0; $i<10; $i++) {
            $data=new Complement();
            $data
                ->setName('Frite '.$faker->city())
                ->setPrice($faker->numberBetween(1000, 7000))
                ->setImage($faker->imageUrl(640, 480, 'Frite'))
                ->setSlug(strtolower($slugger->slug($data->getName())))
                ->setUpdatedAt(new \DateTime())
                ->setCategory($this->getReference('CategoryFrite'));
            $this->addReference("Frite".$i, $data);
            $manager->persist($data);
        }
        $manager->flush();
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on
     *
     * @psalm-return array<class-string<FixtureInterface>>
     */
    public function getDependencies()
    {
        return[
            CategoryFixture::class
        ];
    }
}
