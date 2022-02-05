<?php

namespace App\DataFixtures;

use App\Entity\Burger;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\String\Slugger\AsciiSlugger;

class BurgerFixture extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr-FR');
        $slugger = new AsciiSlugger();
        for ($i = 0; $i<20; $i++) {
            $data=new Burger();
            $data
                ->setName('Burger '.$faker->city())
                ->setDescription($faker->paragraph())
                ->setPrice($faker->numberBetween(1500, 10000))
                ->setImage($faker->imageUrl(640, 480, 'burger'))
                ->setSlug(strtolower($slugger->slug($data->getName())))
                ->setUpdatedAt(new \DateTime())
                ->setCategory($this->getReference('CategoryBurger'));
            $this->addReference("Burger".$i, $data);
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
