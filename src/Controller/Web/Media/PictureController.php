<?php

namespace App\Controller\Web\Media;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PictureController extends AbstractController
{
    #[Route('/web/media/picture', name: 'app_web_media_picture')]
    public function index(): Response
    {
        return $this->render('web/media/picture/index.html.twig', [
            'controller_name' => 'PictureController',
        ]);
    }
}
