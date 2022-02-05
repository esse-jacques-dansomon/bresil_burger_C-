<?php

namespace App\DataFixtures;

use App\Entity\Burger;
use App\Entity\Category;
use App\Entity\Complement;
use App\Entity\Menu;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\String\Slugger\AsciiSlugger;

class MenuFixture extends Fixture implements  DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr-FR');
        $slugger = new AsciiSlugger();
        for ($i = 0; $i<10; $i++) {
            $data=new Menu();
            $data
                ->setName('Menu '.$faker->city())
                ->setDescription($faker->paragraph())
                ->setPrice($faker->numberBetween(5000, 15000))
                ->setImage($faker->imageUrl(640, 480, 'burger fritte drink'))
                ->setSlug(strtolower($slugger->slug($data->getName())))
                ->setUpdatedAt(new \DateTime())
                ->setCategory($this->getReference('CategoryMenu'))
                ->setBurger($this->getReference('Burger'.$i))
                ->addComplement($this->getReference('Boisson'.$i))
                ->addComplement($this->getReference('Frite'.$i))
            ;
            $this->addReference("Menu".$i, $data);
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
            CategoryFixture::class,
            BurgerFixture::class,
            ComplementFixture::class
        ];
    }
}
