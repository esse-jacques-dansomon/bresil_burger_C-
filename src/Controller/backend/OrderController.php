<?php


namespace App\Controller\backend;

use App\Repository\OrderDetailsRepository;
use App\Repository\OrderRepository;
use Faker\Factory;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


/**
 * @isGranted("ROLE_ADMINISTRATOR")
 * @Route("/dashboard/commandes")
 */
class OrderController extends \Symfony\Bundle\FrameworkBundle\Controller\AbstractController
{
     /**
     * @Route("", name="orders", methods={"GET"})
     * @param OrderRepository $userRepository
     * @return Response
     */
    public function index(OrderRepository $orderRepository): Response
    {
        return $this->render('backend/pages/orders/index.html.twig', [
            'orders' => $orderRepository->findAll(),
        ]);
    }
}
