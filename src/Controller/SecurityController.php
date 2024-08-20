<?php

namespace App\Controller;

use App\Entity\CodeValidation;
use App\Entity\User;
use App\Services\TwoFactorAuthService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route('/2fa', name: 'app_2fa')]
    public function twoFactorAuth(Request $request, TwoFactorAuthService $twoFactorAuthService,EntityManagerInterface $entityManager): Response
    {
        /**
         * @var User $user
         */
         $user = $this->getUser();

        if (!$user || !$user->isIsDoubleFactor()) {
            return $this->redirectToRoute('app_login');
        }

        if ($twoFactorAuthService->isUserBlocked($user)) {
            $this->addFlash('error', 'Votre compte est temporairement bloqué. Veuillez réessayer plus tard.');
            return $this->redirectToRoute('app_login');
        }

        if ($request->isMethod('POST')) {
            $code = $request->request->get('code');
           // dd($twoFactorAuthService->validateCode($user, $code));
            if ($twoFactorAuthService->validateCode($user, $code)) {
                // Code valide, rediriger vers la page d'accueil
                return $this->redirectToRoute('app_home');
            } else {
                $this->addFlash('error', 'Code invalide. Veuillez réessayer.');
            }
        } else {
            // Générer et envoyer un nouveau code
            $codeValidation = $entityManager->getRepository(CodeValidation::class)->findOneBy([
                'user' => $user,
                //'code' => $code,
               // 'expiresAt' => ['>', (new \DateTime())->format('yyyy-MM-dd HH:mm:ss')]
            ],['id' => 'DESC']);

            if($codeValidation){
                if($codeValidation->getExpiresAt()< new \DateTime()){
                    $twoFactorAuthService->generateAndSendCode($user);
                }
            }
            else {
                $twoFactorAuthService->generateAndSendCode($user);

            }
            
         
        }

        return $this->render('security/2fa.html.twig');
    }

    #[Route('/2fa/resend', name: 'app_2fa_resend', methods: ['POST'])]
    public function resendCode(TwoFactorAuthService $twoFactorAuthService): Response
    {

          /**
         * @var User $user
         */
        $user = $this->getUser();

        if (!$user || !$user->isIsDoubleFactor()) {
            return $this->json(['success' => false, 'message' => 'Utilisateur non autorisé'], 403);
        }

        $twoFactorAuthService->generateAndSendCode($user);

        return $this->json(['success' => true, 'message' => 'Nouveau code envoyé']);
    }
}
