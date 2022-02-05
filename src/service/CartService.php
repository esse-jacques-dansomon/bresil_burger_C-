<?php


namespace App\service;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Class CartService
 * @package App\service
 */
class CartService
{

    /*
     * CART = SESSION['cart']
     * CART =
     * [
     *      productId1 => productQte
     *      productId2 => productQte
     *      productId3 => productQte
     * ]
     */
    private SessionInterface $session;
    private ProductRepository $productRepository;
    private int $tva = 18;


    public function __construct(SessionInterface $session, ProductRepository $productRepository)
    {
        $this->session = $session;
        $this->productRepository = $productRepository;
    }

    /**
     * @param $idProduct
     * @param int $qte
     * @method CartService addToCart ajouter un produit et sa quantité au panier
     */
    public function addToCart($idProduct, $qte=1)
    {
        $cart = $this->getCart();
        if(isset($cart[$idProduct]))
        {
            $cart[$idProduct] += $qte;
        }else
        {
            $cart[$idProduct] = $qte;
        }
        $this->updateCart($cart);
    }

    public function minusProductFromCart($idProduct)
    {
        $cart = $this->getCart();
        if(isset($cart[$idProduct]))
        {
            if($cart[$idProduct] > 1)
                $cart[$idProduct]--;
            else
                unset($cart[$idProduct]);
        }
        $this->updateCart($cart);

    }


    public function deleteProductFromCart($idProduct)
    {
        $cart = $this->getCart();
        if(isset($cart[$idProduct]))
        {
            unset($cart[$idProduct]);
        }
        $this->updateCart($cart);
    }


    public function updateCart($cart)
    {
        $this->session->set('cart', $cart);
        $this->session->set('fullCart', $this->getProductsFromCart());
    }

    public function getProductsFromCart()
    {
        $cart = $this->getCart();
        $arrayCartProducts = [];
        $totalCart = 0;
        $totalItems = 0;
        foreach($cart as $id => $quantity)
        {
            $product = $this->productRepository->find($id);
            if($product)
            {
                $arrayCartProducts[] = [
                    'quantity' => $quantity,
                    'product' => $product,
                ] ;
                $totalCart += ($product->getPrice()) * $quantity;
                $totalItems += $quantity;
            }else
                $this->deleteProductFromCart($id);

        }
        return [
            "products"=>$arrayCartProducts,
            "totalCart"=>$totalCart,
            "totalCartHTT"=>$totalCart,
            "totalItems"=>$totalItems,
            "taxe" => ($this->tva * $totalCart ) / 100,
        ];
    }
    public function deleteCart()
    {
        $this->updateCart([]);
    }

    public function getCart() : ?Array
    {
        return $this->session->get('cart', []);
    }

    /**
     * @return int
     */
    public function getTaxe(): int
    {
        return $this->tva;
    }

    /**
     * @param int $taxe
     */
    public function setTaxe(int $taxe): void
    {
        $this->taxe = $taxe;
    }


}