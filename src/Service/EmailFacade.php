<?php
namespace App\Service;

use Symfony\Component\HttpFoundation\RequestStack;
use App\Entity\Email;

class EmailFacade
{
    private $mailerService;
    private $requestStack;

    public function __construct(MailerService $mailerService, RequestStack $requestStack)
    {
        $this->mailerService = $mailerService;
        $this->requestStack = $requestStack;
    }
  

    public function sendWelcomeEmail(string $username): void
    {
        // Récupérer la requête actuelle
        $request = $this->requestStack->getCurrentRequest();

        if (!$request) {
            throw new \RuntimeException('No current request available');
        }

        // Récupérer les données de l'email depuis la requête
        $to = $request->request->get('to');
        $subject = 'Welcome to MyApp';
        $body = 'Thank you for registering, ' . htmlspecialchars($username);

        // Créer l'entité EmailData avec les données traitées
        $emailData = new Email();
        $emailData->setTo($to);
        $emailData->setSubject($subject);
        $emailData->setBody($body);

        // Valider l'entité EmailData
        $this->mailerService->validateEmailData($emailData);

        // Envoyer l'email
        $this->mailerService->sendEmail($to, $subject, $body);
    }

    // Vous pouvez ajouter d'autres méthodes pour différents types d'emails
}
