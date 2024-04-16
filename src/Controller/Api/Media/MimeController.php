<?php

namespace App\Controller\Api\Media;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class MimeController extends AbstractController
{
    #[Route('/api/media/mime', name: 'app_api_media_mime')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/Api/Media/MimeController.php',
        ]);
    }
}
