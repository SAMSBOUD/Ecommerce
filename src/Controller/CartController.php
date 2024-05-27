<?php

namespace App\Controller;

use App\Services\CartService;
use App\Services\CartServices;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{

    public function __construct(
        private CartService $cartService,

    ){
        $this->cartService = $cartService;
    }


    #[Route('/cart', name:'app_cart')]
    public function index(): Response
    {
        $cart = $this->cartService->getCartDetails();

        $cart_json = json_encode($cart); 


        return $this->render('cart/index.html.twig', [
            'controller_name' => 'CartController',
            'cart' =>$cart,
            "cart_json" => $cart_json,
        ]);
    }

    // Route to add products to cart ( display_product.html.twig)
    // Will not create function here to add, remove or delete cart. It's better to 
    // create a function in order to use it on other code and it's easyer for maintenance purposes. 
    // check CartService.php
 #[Route('/cart/add/{productId}/{count}', name: 'app_cart_add')]
    public function addToCart(string $productId, $count = 1): Response
    {
        $this->cartService->addToCart($productId,$count);
        // instead of redirecting to the page when adding products I just retrieve the products in Json. This to avoid to reload the page and ensure a good cust exp
        $cart = $this->cartService->getCartDetails();
        $cart_json = json_encode($cart); 

        return $this->json($cart);
        //  return $this->redirectToRoute("app_cart");
      
    } 

    #[Route('/cart/remove/{productId}/{count}', name: 'app_cart_remove')]
    public function removeFromCart(string $productId, $count = 1): Response
    {
        $this->cartService->removeFromCart($productId,$count);
        $cart = $this->cartService->getCartDetails();
        
        return $this->json($cart);

        //  return $this->redirectToRoute("app_cart");
      
    } 
    #[Route('/cart/get', name: 'app_get_cart')]
    public function getCart(): Response
    {
        $cart = $this->cartService->getCartDetails();
        
        return $this->json($cart);

        //  return $this->redirectToRoute("app_cart");
      
    } 

}
