<?php

namespace App\Controller\Api\Cooking;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class CookingCategoryController extends AbstractController
{
    #[Route('/api/cooking/cooking/category', name: 'app_api_cooking_cooking_category')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/Api/Cooking/CookingCategoryController.php',
        ]);
    }
}
