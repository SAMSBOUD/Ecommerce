<?php

namespace App\Controller;

use App\Repository\CarrierRepository;
use App\Services\CartService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class CartController extends AbstractController
{

    public function __construct(
        private CartService $cartService,
        private CarrierRepository $carrierRepo,
    ) {
        $this->cartService = $cartService;
    }



    #[Route('/cart', name: 'app_cart')]
    public function index(): Response
    {
        $cart = $this->cartService->getCartDetails();

        $carriers = $this->carrierRepo->findAll();

        foreach ($carriers as $key => $carrier) {
            $carriers[$key] = [
                "id"=> $carrier->getId(),
                "name"=> $carrier->getName(),
                "description"=> $carrier->getDescription(),
                "price"=> $carrier->getPrice(),
            ];
        }

        $cart_json = json_encode($cart);
        $carriers_json = json_encode($carriers);

        return $this->render('cart/index.html.twig', [
            'controller_name' => 'CartController',
            'cart' => $cart,
            'carriers' => $carriers,
            "cart_json" => $cart_json,
            "carriers_json" => $carriers_json
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
        $cart = $this->cartService->getCartDetails();

        return $this->json($cart);
        
    }
    #[Route('/cart/remove/{productId}/{count}', name: 'app_remove_to_cart')]
    public function removeToCart(string $productId, $count = 1): Response
    {
        $this->cartService->removeToCart($productId,$count);
        $cart = $this->cartService->getCartDetails();

        return $this->json($cart);
        
    }
    #[Route('/cart/get', name: 'app_get_cart')]
    public function getCart(): Response
    {
        $cart = $this->cartService->getCartDetails();

        return $this->json($cart);
        
    }

    #[Route('/cart/carrier', name: 'app_update_cart_carrier', methods: ["POST"])]
    public function updateCartCarrier(Request $req): Response
    {
        $id = $req->getPayload()->get("carrierId");
        
        $carrier = $this->carrierRepo->findOneById($id);

        if(!$carrier){
            return $this->redirectToRoute("app_home");
        }
        $this->cartService->update("carrier", [
            "id"=> $carrier->getId(),
            "name"=> $carrier->getName(),
            "description"=> $carrier->getDescription(),
            "price"=> $carrier->getPrice(),
        ]);

        return $this->redirectToRoute("app_cart");

        // return $this->json($cart);
        
    }

    #[Route('/api/cart/update/carrier/{id}', name: 'app_api_api_cart', methods:['GET'])]
    public function cartUpdateCarrier($id, 
    CartService $cartService,
    CarrierRepository $carrierRepo
    ): Response
    {
        $carrier = $carrierRepo->findOneById($id);

        if(!$carrier){
            return $this->json([
                "isSuccess" => false,
                "message" => "Carrier not fount !",
            ]);
        }
        $cartService->update("carrier", [
            "id"=> $carrier->getId(),
            "name"=> $carrier->getName(),
            "description"=> $carrier->getDescription(),
            "price"=> $carrier->getPrice(),
        ]);
        $cart = $cartService->getCartDetails();

        return $this->json([
            "isSuccess" => true,
            "data"=> $cart
        ]);

    }


}
