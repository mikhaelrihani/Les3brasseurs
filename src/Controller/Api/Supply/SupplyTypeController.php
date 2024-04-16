<?php

namespace App\Controller\Api\Supply;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class SupplyTypeController extends AbstractController
{
    #[Route('/api/supply/supply/type', name: 'app_api_supply_supply_type')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/Api/Supply/SupplyTypeController.php',
        ]);
    }
}
