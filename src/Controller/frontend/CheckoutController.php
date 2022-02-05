<?php


namespace App\Controller\frontend;
use App\Repository\ClientRepository;
use App\Repository\OrderRepository;
use App\service\CartService;
use App\service\OrderService;
use http\Client;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CheckoutController extends AbstractController
{
    /**
     * @Route("/commander", name="make-order" , methods={"GET"})
     * @param OrderService $orderService
     * @param ClientRepository $clientRepository
     * @param SessionInterface $session
     * @return Response
     * @IsGranted("ROLE_CLIENT")
     */
    public function checkout(orderService $orderService, ClientRepository $clientRepository, SessionInterface $session)
    {

        if($session->get('fullCart')['totalItems'] > 0)
        {
            $client = $clientRepository->find($this->getUser()->getId());
            $order = $orderService->createOrder($client);
            return $this->redirectToRoute('account');
        }
        return $this->redirectToRoute('restaurant');

    }

    /**
     * @Route("/my-account", name="account" , methods={"GET"})
     * @param OrderRepository $order
     * @return Response
     * @IsGranted("ROLE_USER")
     */
    public function account(OrderRepository $order)
    {
        if ($this->isGranted("ROLE_CLIENT")){
            $orders = $order->findByClient($this->getUser()->getId());
            //dd($orders);
            return $this->render('frontend/restaurant/my-account.html.twig', ['orders'=>$orders]);

        }elseif($this->isGranted("ROLE_ADMINISTRATOR"))
        {
            return $this->redirectToRoute('admin-dashboard');
        }
        return $this->redirectToRoute('restaurant');

    }

}