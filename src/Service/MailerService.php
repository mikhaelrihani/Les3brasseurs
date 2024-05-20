<?php
namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use App\Entity\Email as EmailData;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class MailerService
{
    private $from;
    private $mailer;
    private $validator;
    private $requestStack;

    public function __construct(MailerInterface $mailer, ValidatorInterface $validator, string $from, RequestStack $requestStack)
    {
        $this->mailer = $mailer;
        $this->validator = $validator;
        $this->from = $from;
        $this->requestStack = $requestStack;
    }

    public function sendEmailFromRequest(): void
    {
        $request = $this->requestStack->getCurrentRequest();

        if (!$request) {
            throw new \RuntimeException('No current request available');
        }

        // Récupérer les données de l'email depuis la requête
        $to = $request->request->get('to');
        $subject = $request->request->get('subject');
        $body = $request->request->get('body');

        // Valider les données d'email
        $emailData = new EmailData();
        $emailData->setTo($to);
        $emailData->setSubject($subject);
        $emailData->setBody($body);

        $this->validateEmailData($emailData);

        // Envoyer l'email
        $this->sendEmail($to, $subject, $body);
    }

    public function validateEmailData(EmailData $emailData): void
    {
        $errors = $this->validator->validate($emailData);

        if (count($errors) > 0) {
            $errorMessages = (string) $errors;
            throw new \InvalidArgumentException($errorMessages);
        }
    }

    public function sendEmail(string $to, string $subject, string $body): void
    {
        $email = (new Email())
            ->from($this->from)
            ->to($to)
            ->subject($subject)
            ->html($body);

        $this->mailer->send($email);
    }
}
