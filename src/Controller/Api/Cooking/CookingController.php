<?php

namespace App\Controller\Api\Cooking;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class CookingController extends AbstractController
{
    #[Route('/api/cooking/cooking', name: 'app_api_cooking_cooking')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/Api/Cooking/CookingController.php',
        ]);
    }
}
