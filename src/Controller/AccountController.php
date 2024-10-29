<?php
namespace App\Controller;
 
use App\Entity\Address;
use App\Entity\CodeValidation;
use App\Repository\AddressRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
 
class AccountController extends AbstractController
{
    #[Route('/account', name: 'app_account')]
    public function index(AddressRepository $addressRepository, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
		 $codeValidation = $em->getRepository(CodeValidation::class)->findOneBy([
            'user' => $user
        ],['id' => 'DESC']);
		if ($user->isIsDoubleFactor() and $codeValidation) {
            return $this->redirectToRoute('app_2fa');
        }
        $addresses = $addressRepository->findByUser($user);
        //dd($addresses);
        return $this->render('account/index.html.twig', [
            'controller_name' => 'AccountController',
            'addresses' => $addresses,
        ]);
    }
}
