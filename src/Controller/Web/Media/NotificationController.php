<?php

namespace App\Controller\Web\Media;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/web/notification')]
class NotificationController extends AbstractController
{
    #[Route('/sendMms', name: 'app_web_notification_sendMms')]
    public function sendMms(): Response
    {
        return $this->render('App_body/media/notification/mms.html.twig');
    }
}
