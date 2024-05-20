<?php
namespace App\Service;

use Symfony\Component\HttpFoundation\Request;
use App\Entity\Email;

class EmailFacade
{
    private $mailerService;
    private $request;

    public function __construct(MailerService $mailerService, Request $request)
    {
        $this->mailerService = $mailerService;
        $this->request = $request;
    }
  

    public function sendWelcomeEmail(string $username): void
    {
        // Récupérer les données de l'email depuis la requête

        $to = $this->request->request->get('to');
        $username = $this->request->request->get('username');
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
