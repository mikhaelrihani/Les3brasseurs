<?php

namespace App\Controller\Api\Cooking;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class DishController extends AbstractController
{
    #[Route('/api/cooking/dish', name: 'app_api_cooking_dish')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/Api/Cooking/DishController.php',
        ]);
    }
}
