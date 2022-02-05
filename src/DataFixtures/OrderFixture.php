<?php

namespace App\DataFixtures;

use App\Entity\Burger;
use App\Entity\Category;
use App\Entity\Client;
use App\Entity\Complement;
use App\Entity\Menu;
use App\Entity\Order;
use App\Entity\OrderDetails;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class OrderFixture extends Fixture implements  DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {

            for ($i = 0; $i<5; $i++)
            {
                //Cree des commandes
                $client = $this->getReference('Client'.$i);
                $OrderTotal = random_int(1,5);

                for ($j = 1; $j<$OrderTotal; $j++){
                    $order = new Order();
                    $order->setReference(uniqid('ORD'))
                        ->setClient($client)
                        ->setStatus("EN COURS")
                        ->setUpdatedAt(new \DateTime())
                        ->setIsPaid(false);
                    //Cree des OrderDetails
                    $total = 0;
                    $OrderDetailsTotal = random_int(2,5);
                    for ($k = 0; $k<$OrderDetailsTotal; $k++){
                        $orderDetails = new OrderDetails;
                        $productId = random_int(1,10);
                        $orderDetails->setQuantity(random_int(1,10))
                            ->setCommande($order)
                            ->setProduct($this->getReference('Burger'.$productId));
                        $total += $orderDetails->getQuantity() * $orderDetails->getProduct()->getPrice();
                        $order->addOrderDetail($orderDetails);
                        $manager->persist($orderDetails);
                    }
                    $order->setSubTotal($total);
                    $order->setTva($total * 0.18);
                    $order->setTotal( $total + $total * 0.18 );
                    $manager->persist($order);
                }

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
            ClientFixture::class,
            BurgerFixture::class,
            ComplementFixture::class,
            MenuFixture::class
        ];
    }

}
