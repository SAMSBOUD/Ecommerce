<?php

namespace App\Controller;

use App\Services\CompareService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CompareController extends AbstractController
{

    public function __construct(
        private CompareService $compareService,

    ){
        $this->compareService = $compareService;
    }

    #[Route('/compare', name:'app_compare')]
    public function index(): Response
    {
        $compare = $this->compareService->getCompareDetails();

         $compare_json = json_encode($compare); 

        //dd($compare_json);
        return $this->render('compare/index.html.twig', [
            'controller_name' => 'CompareController',
            'compare' =>$compare,
            "compare_json" => $compare_json,
        ]);
    }

    // Route to add products to compare ( display_product.html.twig)
    // Will not create function here to add, remove or delete compare. It's better to 
    // create a function in order to use it on other code and it's easyer for maintenance purposes. 
    // check CompareService.php
 #[Route('/compare/add/{productId}', name: 'app_add_to_compare')]
    public function addToCompare(string $productId): Response
    {
        $this->compareService->addToCompare($productId);
        // instead of redirecting to the page when adding products I just retrieve the products in Json. This to avoid to reload the page and ensure a good cust exp
        $compare = $this->compareService->getCompareDetails();
        return $this->json($compare);


        // dd($compare);
        //  return $this->redirectToRoute("app_compare");

      
    } 

    #[Route('/compare/remove/{productId}', name: 'app_compare_remove')]
    public function removeFromCompare(string $productId): Response
    {
        $this->compareService->removeFromCompare($productId);
        $compare = $this->compareService->getCompareDetails();
        
         return $this->json($compare);

        //    return $this->redirectToRoute("app_compare");
      
    } 
    #[Route('/compare/get', name: 'app_get_compare')]
    public function getCompare(): Response
    {
        $compare = $this->compareService->getCompareDetails();
        
        return $this->json($compare);

        //  return $this->redirectToRoute("app_compare");
      
    } 

}
    


