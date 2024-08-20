<?php

namespace App\Controller\Api;

use App\Entity\Address;
use App\Repository\AddressRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api')]
class ApiAccountController extends AbstractController
{
    #[Route('/enabale/double/fa', name: 'app_api_is_two_fa_enabled', methods: ['POST'])]
    public function delete(
        Request $req,
        UserRepository $userRepository,
        EntityManagerInterface  $manager,
    ): Response {
        /**
         * @var User $user
         */
        $user = $this->getUser();
        $formData = $req->getPayload();
        //dd($formData);

        if (!$user) {
            return $this->json([
                "isSuccess" => false,
                "message" => "Not authorization !",
                "data" => []
            ]);
        }

        $user = $userRepository->findOneById($user->getId());
        if (!$user) {
            return $this->json([
                "isSuccess" => false,
                "message" => "User not found !",
                "data" => []
            ]);
        }
        $user->setIsDoubleFactor($formData->get('isEnable')); //$formData->get('name')
        $manager->flush();
        return $this->json([
            "isSuccess"=> true,
            "message" =>"Vos informations ont été bien mises à jours" 
        ]);
    }
}
