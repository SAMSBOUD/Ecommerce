<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\ColectionRepository;
use App\Repository\PageRepository;
use App\Repository\ProductRepository;
use App\Repository\SettingRepository;
use App\Repository\SlidersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class HomeController extends AbstractController
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



    #[Route('/', name: 'app_home')]
    public function index(
        SettingRepository $settingRepo,  // On récupère les settings
        SlidersRepository $slidersRepo,  // on récupère les slides
        ColectionRepository $collectionRepo, // on récupère les cellections . 
        PageRepository $pageRepo, // on récupère les cellections . 
        CategoryRepository $categoryRepo,

        Request $request
        ): Response
    {
        $session =$request->getSession(); 
        $data = $settingRepo->findAll();
        $sliders = $slidersRepo->findAll();
        $collections = $collectionRepo->findBy(['isMega'=>false]);
        $megaCollections = $collectionRepo->findBy(['isMega'=>true]);

        $categories = $categoryRepo->findBy(['isMega'=>true]); //récupérer selon des critères se fait avec findBY
        // $pages = $pageRepo->findAll(); 

     //   dd($data); check si récupère les données. 
        $session->set("setting", $data[0]); // stocker dans la session

        $headerPages =  $pageRepo->findBy(['isHead'=> true]); // stocker et récuperer quand Boolean header == true. 
        $footerPages =  $pageRepo->findBy(['isFoot'=> true]);

        // dd($headerPages);
        $session->set("headerPages", $headerPages); //permet d'accéder et header page se trouvant dans l'index
        $session->set("footerPages", $footerPages);
        $session->set("categories", $categories);
        $session->set("megaCollections", $megaCollections); 
       // dd($categories);

        return $this->render('home/index.html.twig', [
        
            'controller_name' => 'HomeController',
            'sliders' => $sliders,
            'collections' => $collections,
            'productsBestSeller' => $this->repoProduct->findBy(['isBestSeller'=>true]),
            'productsNewArrival' => $this->repoProduct->findBy(['isNewArrival'=>true]),
            'productsFeatured' => $this->repoProduct->findBy(['isFeatured'=>true]),
            'productsSpecialOffer' => $this->repoProduct->findBy(['isSpecialOffer'=>true])
            
        ]);
    }

    //nouvelle route pour voir produit

    #[Route('/product/{slug}', name: 'app_product_by_slug')]

    public function showProduct(string $slug)
    {
    $product = $this->repoProduct->findOneBy(['slug'=>$slug]);

    if(!$product){

        return $this->redirectToRoute('app_error');
    }

    return $this->render('product/show_product_by_slug.html.twig', [

        'product'=> $product

    ]);

    }
    #[Route('/contact', name: 'app_home_contact')]

    public function contact()
    {
        return $this->render('contact/index.html.twig', [

            //'product'=> $product

        ]);

    }
    



    #[Route('/product/get/{id}', name: 'app_product_by_id')]

    public function getProductByID(string $id)
    {
    $product = $this->repoProduct->findOneBy(['id'=>$id]);

    if(!$product){

        return $this->json(false);
    }

    return $this->json([

        'id'=>$product->getId(),
        'name'=>$product->getName(),
        'imageUrls'=>$product->getImageUrls(),
        'soldePrice'=>$product->getSoldePrice(),
        'regularPrice'=>$product->getRegularPrice(),

    ]);

    

    }




    #[Route('/error', name: 'app_error')]

    public function errorpage()
    {
        return $this->render('page/not-found.html.twig', [
            'controller_name' => 'PageController'
    ]);

    }

   
    #[Route('/change-language/{locale}', name: 'change_language')]
    public function changeLanguage($locale, Request $request): Response
    {
        //dd($locale);
        // On stoke la langue demandée dans la session
        $request->getSession()->set('_locale', $locale);
        $request->setLocale($locale);
        $referer = $request->headers->get('referer');
       // return new RedirectResponse($referer);
       //dd($session);
        return $this->redirect($referer);
       /* $session->set('_locale', $locale);
        $referer = $request->headers->get('referer');
      //  dd($session);
        return new RedirectResponse($referer);*/
    }

}

