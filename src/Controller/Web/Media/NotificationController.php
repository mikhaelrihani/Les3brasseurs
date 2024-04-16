<?php

namespace App\Controller\Web\Media;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class NotificationController extends AbstractController
{
    #[Route('/web/media/notification', name: 'app_web_media_notification')]
    public function index(): Response
    {
        return $this->render('web/media/notification/index.html.twig', [
            'controller_name' => 'NotificationController',
        ]);
    }
}
