<?php

namespace App\Controller\Web\Media;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MimeController extends AbstractController
{
    #[Route('/web/media/mime', name: 'app_web_media_mime')]
    public function index(): Response
    {
        return $this->render('web/media/mime/index.html.twig', [
            'controller_name' => 'MimeController',
        ]);
    }
}
