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
 * @Route("/dashboard")
 */
class AdminUserController extends \Symfony\Bundle\FrameworkBundle\Controller\AbstractController
{
    /**
     * @Route("", name="admin-dashboard" , methods={"GET"})
     * @param OrderRepository $orderRepository
     * @param OrderDetailsRepository $orderDetailsRepository
     * @return Response
     * @throws \Exception
     */
    public function index(OrderRepository $orderRepository, OrderDetailsRepository $orderDetailsRepository)
    {
        $OrdersEnCours = $orderRepository->findByStatus('EN COURS');
        $OrdersAnnuler = $orderRepository->findByStatus('ANNULER');
        $OrdersValider = $orderRepository->findByStatus('VALIDER');
        return $this->render("backend/pages/dashbord.html.twig",
            [
                'ordersEnCours'=>$OrdersEnCours,
                'ordersAnnuler'=>$OrdersAnnuler,
                'ordersValider'=>$OrdersValider,
            ]) ;
    }
}