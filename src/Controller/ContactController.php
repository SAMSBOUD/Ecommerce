<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mime\Email;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;


class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_home_contact')]
    public function index(): Response
    {
        return $this->render('contact/index.html.twig', [
            'controller_name' => 'ContactController',
        ]);
    }
    #[Route('store/contact', name: 'app_store_contact', methods: ['POST'])]
    public function store(
        Request $request,
        EntityManagerInterface $entityManager,
        ContactRepository $contactRepository,
        MailerInterface $mailer,
        CsrfTokenManagerInterface $csrfTokenManager
    ): Response {
        $email = $request->request->get('email');
        $subject = $request->request->get('subject');
        $content = $request->request->get('message');
        $csrfToken = new CsrfToken('contact_form', $request->request->get('_csrf_token'));

        if (!$csrfTokenManager->isTokenValid($csrfToken)) {
            throw new AccessDeniedHttpException('Invalid CSRF token.');
        }
        // Vérification si le contact existe déjà
        $existingContact = $contactRepository->findOneBy(['email' => $email, 'subject' => $subject]);

        if ($existingContact) {
            $this->addFlash('error', 'Ce contact a déjà été enregistré');
            return $this->redirectToRoute('app_home_contact');
            /*return $this->json([
                'message' => 'Ce contact a déjà été enregistré.',
            ], Response::HTTP_CONFLICT);*/
        }

        // Création du nouveau contact
        $contact = new Contact();
        $contact->setEmail($email);
        $contact->setSubject($subject);
        $contact->setContent($content);
        $contact->setCreatedAt(new \DateTimeImmutable());

        $entityManager->persist($contact);
        $entityManager->flush();

        // Envoi de l'email de remerciement
        $emailMessage = (new Email())
            ->from($this->getParameter('sender_mail'))
            ->to($contact->getEmail())
            ->subject('Merci pour votre message')
            ->text('Nous avons bien reçu votre message concernant : ' . $contact->getSubject());

        $mailer->send($emailMessage);
        $this->addFlash('success', 'Votre message a bien été envoyé. Merci pour votre contact.');
        return $this->redirectToRoute('app_home_contact');
       /* return $this->json([
            'message' => 'Votre message a bien été envoyé. Merci pour votre contact.',
        ], Response::HTTP_OK);*/
    }
}

