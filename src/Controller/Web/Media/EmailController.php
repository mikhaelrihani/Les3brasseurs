<?php

namespace App\Controller\Web\Media;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class EmailController extends AbstractController
{
    #[Route('/web/media/email', name: 'app_web_media_email')]
    public function index(): Response
    {
        return $this->render('web/media/email/index.html.twig', [
            'controller_name' => 'EmailController',
        ]);
    }
}
