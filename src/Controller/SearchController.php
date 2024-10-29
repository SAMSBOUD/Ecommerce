<?php

namespace App\Controller;

use App\Repository\ColectionRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class SearchController extends AbstractController
{
    private $repoProduct; 
    private $requestStack;


    public function __construct(ProductRepository $repoProduct,RequestStack $requestStack)
    {
        $this->repoProduct = $repoProduct; 
        $this->requestStack = $requestStack;

        // Pour pouvoir éviter à chaque fois d'injecter ou récupérer mes produits dans la route, l'on peut créer
        //une fonction pour le faire au niveau du controller. De cette manière productRepo sera dispo dans toute les méthodes déclarer. 

    }
#[Route('/search/products', name: 'app_search_product')]
    public function index(
        ColectionRepository $collectionRepo, // on récupère les cellections . 
        Request $request,
        CsrfTokenManagerInterface $csrfTokenManager
        ): Response
    {
        $csrfToken = new CsrfToken('search_form', $request->get('t'));

       if (!$csrfTokenManager->isTokenValid($csrfToken)) {
            throw new AccessDeniedHttpException('Invalid CSRF token.');
        }
        $query = $request->get('q', '');
        $products = $this->repoProduct->searchProducts($query);
        //dd($products);
        /*$session =$request->getSession(); 
        $collections = $collectionRepo->findBy(['isMega'=>false]);
        $megaCollections = $collectionRepo->findBy(['isMega'=>true]);

        $session->set("megaCollections", $megaCollections); 
       // dd($categories);*/

        return $this->render('search/index.html.twig', [
        
            'products' => $products,
            'query' => $query,
            
        ]);
    }
}