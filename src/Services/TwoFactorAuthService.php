<?php

namespace App\Services;

use App\Entity\User;
use App\Entity\CodeValidation;
use App\Entity\BlocageUser;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\HttpFoundation\RequestStack;

class TwoFactorAuthService
{
    private $entityManager;
    private $mailer;
    private $requestStack;

    public function __construct(
        EntityManagerInterface $entityManager, 
        MailerInterface $mailer,
        RequestStack $requestStack
    ) {
        $this->entityManager = $entityManager;
        $this->mailer = $mailer;
        $this->requestStack = $requestStack;
    }

    public function generateAndSendCode(User $user): void
    {
       
        // Générer un code aléatoire
        $code = random_int(100000, 999999);

        // Créer une nouvelle entrée CodeValidation
        $codeValidation = new CodeValidation();
        $codeValidation->setUser($user);
        $codeValidation->setCode($code);
        $codeValidation->setExpiresAt(new \DateTime('+5 minutes'));

        $this->entityManager->persist($codeValidation);
        $this->entityManager->flush();

        // Envoyer le code par email
        $email = (new Email())
            ->from('your-email@example.com')
            ->to($user->getEmail())
            ->subject('Votre code de vérification')
            ->text("Votre code de vérification est : $code. Il expire dans 5 minutes.");

        $this->mailer->send($email);
    }

    public function validateCode(User $user, string $code): bool
    {
        $codeValidation = $this->entityManager->getRepository(CodeValidation::class)->findOneBy([
            'user' => $user,
            //'code' => $code,
           // 'expiresAt' => ['>', (new \DateTime())->format('yyyy-MM-dd HH:mm:ss')]
        ],['id' => 'DESC']);
            
        if (!$codeValidation) {
           /* if(is_null($codeValidation->getAttempts())){
                $codeValidation->setAttempts(0);
            }*/
            return false;
        }

        if ($codeValidation && $codeValidation->getCode() !== trim($code) && $codeValidation->getAttempts() < 3) {
             $codeValidation->setAttempts($codeValidation->getAttempts() + 1);
             $this->entityManager->flush();
             return false;
         }
         if ($codeValidation && $codeValidation->getCode() === trim($code) && $codeValidation->getAttempts() < 3 && $codeValidation->getExpiresAt()< new \DateTime()) {
            $codeValidation->setAttempts($codeValidation->getAttempts() + 1);
            $this->entityManager->flush();
            return false;
        }
        // dd(($codeValidation && $codeValidation->getCode() !== trim($code) && $codeValidation->getAttempts() < 3),$codeValidation,$codeValidation->getCode(),$user,trim($code),$codeValidation->getAttempts());
        if ($codeValidation->getAttempts() >= 3) {
            $this->blockUser($user);
            return false;
        }

        $this->entityManager->flush();

        return true;
    }

    private function blockUser(User $user): void
    {
        $request = $this->requestStack->getCurrentRequest();
        $ipAddress = $request ? $request->getClientIp() : 'unknown';

        $blocage = new BlocageUser();
        $blocage->setUser($user);
        $blocage->setBlockedUntil(new \DateTime('+1 hour'));
        $blocage->setIpAddress($ipAddress);

        $this->entityManager->persist($blocage);
        $this->entityManager->flush();
    }

    public function isUserBlocked(User $user): bool
    {
        $request = $this->requestStack->getCurrentRequest();
        $ipAddress = $request ? $request->getClientIp() : 'unknown';

        $latestBlocage = $this->entityManager->getRepository(BlocageUser::class)->findOneBy(
            ['user' => $user, 'ipAddress' => $ipAddress],
            ['blockedUntil' => 'DESC']
        );
       // dd($ipAddress,$latestBlocage->getBlockedUntil(),($latestBlocage->getBlockedUntil() > new \DateTime()));
        if (!$latestBlocage) {
            return false;
        }

        return $latestBlocage->getBlockedUntil() > new \DateTime();
    }
}
