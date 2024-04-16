<?php

namespace App\Controller\Api\Media;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class PictureController extends AbstractController
{
    #[Route('/api/media/picture', name: 'app_api_media_picture')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/Api/Media/PictureController.php',
        ]);
    }
}
