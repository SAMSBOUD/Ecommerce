<?php

namespace App\Controller;

use App\Repository\ColectionRepository;
use App\Repository\PageRepository;
use App\Repository\SettingRepository;
use App\Repository\SlidersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(
        SettingRepository $settingRepo,  // On récupère les settings
        SlidersRepository $slidersRepo,  // on récupère les slides
        ColectionRepository $collectionRepo, // on récupère les cellections . 
        PageRepository $pageRepo, // on récupère les cellections . 

        Request $request
        ): Response
    {
        $session =$request->getSession(); 
        $data = $settingRepo->findAll();
        $sliders = $slidersRepo->findAll();
        $collections = $collectionRepo->findAll();
        // $pages = $pageRepo->findAll(); 

     //   dd($data); check si récupère les données. 
        $session->set("setting", $data[0]); // stocker dans la session

        $headerPages =  $pageRepo->findBy(['isHead'=> true]); // stocker et récuperer quand Boolean header == true. 
        $footerPages =  $pageRepo->findBy(['isFoot'=> true]);

        // dd($headerPages);
        $session->set("headerPages", $headerPages); //permet d'accéder et header page se trouvant dans l'index
        $session->set("footerPages", $footerPages);


        return $this->render('home/index.html.twig', [
        
            'controller_name' => 'HomeController',
            'sliders' => $sliders,
            'collections' => $collections
            
        ]);
    }
}

