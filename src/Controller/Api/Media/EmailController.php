<?php
namespace App\Controller\Api\Media;

use App\Service\EmailFacadeService;
use App\Service\MailerService;
use App\Controller\MainController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/api/email')]
class EmailController extends MainController
{
    private EmailFacadeService $emailFacade;
    private MailerService $mailerService;

    public function __construct(EmailFacadeService $emailFacade, MailerService $mailerService)
    {
        $this->emailFacade = $emailFacade;
        $this->mailerService = $mailerService;

    }
    
    #[Route('/sendEmailFromRequest', name: 'app_api_email_sendEmailFromRequest')]
    public function sendEmailFromRequest(): Response
    {
        try {
            $this->mailerService->sendEmailFromRequest();
            return new Response('Email sent successfully');
        } catch (\Exception $e) {
            return new Response('Failed to send email: ' . $e->getMessage());
        }
    }

    #[Route('/sendWelcomeEmail', name: 'app_api_email_sendWelcomeEmail')]
    public function sendWelcomeEmail($username = null): Response
    {
        //$username = "omika";
        if (!htmlspecialchars($username)) {
            return new JsonResponse('Username is required', Response::HTTP_BAD_REQUEST);
        }
        try {
            $this->emailFacade->sendWelcomeEmail($username);
            return new JsonResponse('Welcome email sent successfully', Response::HTTP_OK);
        } catch (\Exception $e) {
            return new JsonResponse('Failed to send welcome email: ' . $e->getMessage(), Response::HTTP_BAD_REQUEST);
        }

    }
}
