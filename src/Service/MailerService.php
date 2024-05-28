<?php
namespace App\Service;

use Exception;
use Symfony\Component\Mime\Email;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\TransportInterface;

class MailerService
{

    private $from;
    private $mailer;

    private $requestStack;

    public function __construct(TransportInterface $transport, string $from, RequestStack $requestStack)
    {
        $this->mailer = $transport;
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


    public function sendEmail(string $to, string $subject, string $body): ?SentMessage
    {
        $email = (new Email())
            ->from($this->from)
            ->to($to)
            ->subject($subject)
            ->html($body);

        try {
            return $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            throw new Exception('Failed to send email: ' . $e->getMessage());
        }

    }
}
