<?php

namespace App\Controller\Web\Media;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class FileController extends AbstractController
{
    #[Route('/web/media/file', name: 'app_web_media_file')]
    public function index(): Response
    {
        return $this->render('web/media/file/index.html.twig', [
            'controller_name' => 'FileController',
        ]);
    }
}
