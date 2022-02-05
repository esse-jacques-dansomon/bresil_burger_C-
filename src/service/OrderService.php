<?php


namespace App\service;


use App\Entity\Client;
use App\Entity\OrderDetails;
use App\Entity\Order;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class OrderService
{
    private EntityManagerInterface $manager;
    private SessionInterface $session;

    private  ProductRepository $productRepository;
    private CartService $cartService;

    public function __construct(EntityManagerInterface $manager,CartService $cartService, ProductRepository $productRepository, RequestStack  $session)
    {
        $this->manager = $manager;
        $this->session = $session->getSession();
        $this->productRepository = $productRepository;
        $this->cartService = $cartService;
    }
    public function  createOrder(Client $client) : Order
    {
        $order = new Order();
        $total = $this->session->get('fullCart')['totalCart'];
        $subtotal = $this->session->get('fullCart')['totalCart'];
        $taxe = $this->session->get('fullCart')['taxe'];
        $order->setReference($this->generateUid())
            ->setMoreInformation("")
            ->setIsPaid(false)
            ->setStatus('EN COURS')
            ->setClient($client)
            ->setUpdatedAt(new \DateTime())
            ->setTotal($total)
            ->setSubTotal($subtotal)
            ->setTva($taxe);
        $this->manager->persist($order);
        $this->manager->flush();

        $products = $this->session->get('fullCart')['products'];

        foreach($products as $product)
        {
            $orderDetails = new OrderDetails();
             $productt = $this->productRepository->find($product['product']->getId());
            $orderDetails
                ->setQuantity($product['quantity'])
                ->setCommande($order)
                ->setProduct($productt);
            $order->addOrderDetail($orderDetails);
            $this->manager->persist($orderDetails);
            $this->manager->persist($order);
        }
        $this->manager->flush();
        $this->cartService->deleteCart();

        return $order;


    }


    public function generateUid()
    {
        return uniqid('ORDER', true);
    }

}
