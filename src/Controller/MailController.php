<?php
namespace App\Controller;

use App\Service\EmailFacade;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailController extends AbstractController
{
    private EmailFacade $emailFacade;

    public function __construct(EmailFacade $emailFacade)
    {
        $this->emailFacade = $emailFacade;
    }

    #[Route('/mail', name: 'mail')]
    public function sendEmail(MailerInterface $mailer): Response
    {
        $email = (new Email())
            ->from('mikabernikdev@gmail.com')
            ->to('contact@omika.fr')
            ->subject('Time for Symfony Mailer!')
            ->text('Sending emails is fun again!')
            ->html('<p>Sending emails is fun again!</p>');

        try {
            $mailer->send($email);
            return new Response('Email sent successfully');
        } catch (\Exception $e) {
            return new Response('Failed to send email: ' . $e->getMessage());
        }
    }

    #[Route('/mailWelcome', name: 'mailWelcome')]
    public function sendWelcomeEmail(string $username): Response
    {
        $this->emailFacade->sendWelcomeEmail($username);
        return new Response('Welcome email sent successfully');
    }
}
