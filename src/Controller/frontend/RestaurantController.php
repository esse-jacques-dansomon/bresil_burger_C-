<?php

namespace App\Controller\frontend;

use App\Entity\Complement;
use App\Entity\Product;
use App\Repository\CategoryRepository;
use App\Repository\ComplementRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RestaurantController extends AbstractController
{
    /**
     * @Route("/restaurant", name="restaurant")
     * @param ProductRepository $productRepository
     * @return Response
     */
    public function restaurant(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findAll();
        //dd($products);
        return $this->render('frontend/restaurant/shop.html.twig', compact('products'));
    }

    /**
     * @Route("/product/{slug}", name="product")
     * @param Product $product
     * @param ComplementRepository $cr
     * @param Request $request
     * @return Response
     */
    public function product(Product $product, ComplementRepository $cr, Request $request): Response
    {
        $frites = $cr->findByCategory(5);
        $boissons = $cr->findByCategory(4);
        if(isset($product) )
            return $this->render('frontend/restaurant/product.html.twig', ['product'=>$product,  'category'=>$frites,  'boissons'=>$boissons]);
        return $this->redirectToRoute('restaurant');
    }



}
