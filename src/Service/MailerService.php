<?php
namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\HttpFoundation\RequestStack;

class MailerService
{
    
    private $from;
    private $mailer;

    private $requestStack;

    public function __construct(MailerInterface $mailer, string $from, RequestStack $requestStack)
    {
        $this->mailer = $mailer;
        $this->from = $from;
        $this->requestStack = $requestStack;
    }

    public function sendEmailFromRequest(): void
    {
        $request = $this->requestStack->getCurrentRequest();

        if (!$request) {
            throw new \RuntimeException('No current request available');
        }

        $to = $request->request->get('to');
        $subject = $request->request->get('subject');
        $body = $request->request->get('body');

        $this->sendEmail($to, $subject, $body);
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
