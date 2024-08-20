<?php
namespace App\Services;

use App\Repository\CarrierRepository;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\DependencyInjection\Loader\Configurator\session;

class CartService
{
    private $session;


     public function __construct(
        private RequestStack $requestStack,
        private ProductRepository $productRepo,
        private CarrierRepository $carrierRepo,

    ){
        $this->session = $requestStack->getSession();       
         //$this->productRepo = $productRepo;
    }


    public function get($key)
    {
        return $this->session->get($key, []);
    }
    public function update($key, $cart)
    {
        return $this->session->set($key, $cart);
    }
    public function addToCart($productId, $count = 1)
    {
        // [
        //     '1' => 3,
        //     '25' => 1,
        // ]
        $cart = $this->get('cart');

        if(!empty($cart[$productId])){
            // product exist in cart
            $cart[$productId] += $count;
        }else{
            // product not exist
            $cart[$productId] = $count;
        }

        $this->update("cart", $cart);
    }

    public function removeToCart($productId, $count = 1)
    {
        $cart = $this->get('cart');

        if(isset($cart[$productId])){
            if($cart[$productId]  <= $count){
                unset($cart[$productId]);
            }else{
                $cart[$productId] -= $count;
            }

            $this->update("cart", $cart);
        }

    }

    public function clearCart()
    {
        $this->update("cart", []);
    }
    public function updateCarrier($carrier)
    {
        $this->update("carrier", $carrier);
    }
    public function getCartDetails()
    {
        // [
        //     "items" => [
        //             [
        //                 'product' => [],
        //                 'quantity' => 2,
        //                 'taxe' => 20,
        //                 'sub_total' => 199,
        //             ]
        //         ],
        //          "cart_sub_total" => 199
        //          'cart_count' => 0,
        // ]
        $cart = $this->get('cart');
        $result = [
            'items' => [],
            'sub_total' => 0,
            'cart_count' => 0,
            'quantity' => 0,
        ];

        $sub_total = 0;

        $taxe_rate = 0;

        foreach ($cart as $productId => $quantity) {
            $product = $this->productRepo->find($productId);
            if($product){
                $current_sub_total = $product->getSoldePrice()*$quantity;
                $sub_total += $current_sub_total;
                $result['items'][] = [
                    'product' => [
                        'id'=>$product->getId(),
                        'name'=>$product->getName(),
                        'description'=>$product->getDescription(),
                        'slug'=>$product->getSlug(),
                        'imageUrls'=>$product->getImageUrls(),
                        'soldePrice'=>$product->getSoldePrice(),
                        'regularPrice'=>$product->getRegularPrice(),
                    ],
                    'quantity' => $quantity,
                    'sub_total_ht' => round($current_sub_total/(1 + $taxe_rate)),
                    'taxe' => round($taxe_rate * $current_sub_total/(1 + $taxe_rate)),
                    'sub_total' => $current_sub_total,
                ];
                $result['sub_total'] = $sub_total;
                $result['sub_total_ht'] =  round($sub_total/(1 + $taxe_rate));
                $result['taxe'] =   round($taxe_rate * $result['sub_total_ht']);
                $result['cart_count'] += $quantity;
                $result['quantity'] += $quantity;
                

            }else{
                unset($cart[$productId]);
                $this->update("cart", $cart);
            }
        }
        
        $carrier = $this->get("carrier");
        if(!$carrier){
            $carrier = $this->carrierRepo->findAll()[0];
            $carrier = [
                "id"=> $carrier->getId(),
                "name"=> $carrier->getName(),
                "description"=> $carrier->getDescription(),
                "price"=> $carrier->getPrice(),
            ];
            $carrier = $this->update("carrier", $carrier);
        }

        $result["carrier"] =  $carrier;
        $result['sub_total_with_carrier'] = $result['sub_total'] + $carrier["price"];


        return $result;
    }



}