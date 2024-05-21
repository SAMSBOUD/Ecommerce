<?php
namespace App\Services;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\DependencyInjection\Loader\Configurator\session;

class CartService
{
    private $session;


     public function __construct(
        private RequestStack $requestStack,
        private ProductRepository $productRepo,


    ){
        $this->session = $requestStack->getSession();       
         $this->productRepo = $productRepo;
    }


    public function getCart() {
        return $this->session->get("cart", []);
    }
    public function updateCart($cart) {
        return $this->session->set("cart", $cart);
    }
    
    public function addToCart($productId, $count = 1)
    {
   
        $cart = $this->getCart();

        if(!empty($cart[$productId])){
            //product already existing in cart
            $cart[$productId] += $count;

        }else{
            // product not existing in cart
            $cart[$productId] = $count; 
        }

        $this->updateCart($cart); 

    }

    public function removeFromCart($productId, $count = 1)
    {
        $cart = $this->getCart();

        if(isset($cart[$productId])){
            if($cart[$productId] <= $count){
                unset($cart[$productId]);
            }else {
                $cart[$productId] -= $count;
            }
            
            $this->updateCart($cart); 

        }

    }

    public function clearCart()
    {
        //To empty just put an empty board
        $this->updateCart([]); 
    }

    //Retrieve information about products for cart. 
    public function getCartDetails()
    {
        /*   [
            [
                'product' =>[],
                'quantity' =>[],
                'taxe' =>[],
                'sub_total' =>[],
            ]
        ] */

        $cart = $this->getCart();
        $result = [
            'items' => [],
            'sub_total' => 0

        ];
        $sub_total = 0 ;

        foreach ($cart as $productId => $quantity) {
            $product =  $this->productRepo->find($productId);

            if($product){
                $current_sub_total = $product->getSoldePrice()*$quantity;
                $sub_total += $current_sub_total;
                $result['items'] []= [
                          // récupérer les données pour transformer en format Json au niveau de CartController pour utiliser en javascript. 
                    'product' => [
                        'id'=>$product->getId(),
                        'name'=>$product->getName(),
                        'imageUrls'=>$product->getImageUrls(),
                        'soldePrice'=>$product->getSoldePrice(),
                        'regularPrice'=>$product->getRegularPrice(),
                    ],
                    'quantity' => $quantity,
                    'sub_total' => $current_sub_total,
    
                ];
                $result['sub_total'] = $sub_total;

            }else{
                // if Id don't exist
                unset($cart[$productId]);
                $this->updateCart($cart);
            }
            
        }
        return $result; 
    }


}