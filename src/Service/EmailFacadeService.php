<?php
namespace App\Service;

use Symfony\Component\HttpFoundation\RequestStack;

class EmailFacadeService
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
        $request = $this->requestStack->getCurrentRequest();
        if (!$request) {
            throw new \RuntimeException('No current request available');
        }

        $to = $request->request->get('to');
        $subject = 'Welcome to Omika';
        $body = 'Thank you for registering, ' . $username . '!';
        if ($to == null) {
            throw new \RuntimeException('No email provided');
        }
        $this->mailerService->sendEmail($to, $subject, $body);

    }

}
