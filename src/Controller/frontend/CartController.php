<?php

namespace App\Controller\frontend;

use App\service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class CartController extends AbstractController
{

    private CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }
    /**
     * @Route("/cart", name="cart" , methods={"GET"})
     * @return Response
     */
    public function cart(): Response
    {
        $cart = $this->cartService->getProductsFromCart();
        if(isset($cart['totalItems']))
        {
            return $this->render('frontend/restaurant/cart.html.twig', [
                'cart' => $cart['products'],
                'cartTotal' => $cart['totalCart'],
            ]);
        }else
            return $this->redirectToRoute('home');

    }

    /**
     * @param $id
     * @param Request $request
     * @return RedirectResponse
     * @Route("/cart/add/{id}", name="add_to_cart", methods={"GET|POST"})
     */
    public function addToCart($id, Request $request)
    {
        if($request->isMethod('post'))
        {
            $qte  = $request->request->get('qte');
            $complements  = $request->request->get('complement');
            $this->cartService->addToCart($id, $qte);
            if($complements != [])
            {
                foreach ( $complements as $item) {
                    $this->cartService->addToCart($item);
                }
            }

        }else
        {
            if(is_numeric($id))
                $this->cartService->addToCart($id);
        }
        return $this->redirectToRoute('cart');
    }


    /**
     * @param $id
     * @Route("/cart/delete/{id}", name="delete_from_cart", methods={"GET"})
     * @return RedirectResponse
     */
    public function delelefromCart($id)
    {
        if(is_numeric($id))
            $this->cartService->minusProductFromCart($id);
        return $this->redirectToRoute('cart');
    }

    /**
     * @param $id
     * @Route("/cart/delete_product/{id}", name="delete_product_from_cart", methods={"GET"})
     * @return RedirectResponse
     */
    public function deleleProductfromCart($id)
    {
        if(is_numeric($id))
            $this->cartService->deleteProductFromCart($id);
        return $this->redirectToRoute('cart');
    }
}
