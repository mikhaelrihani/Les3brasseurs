<?php

namespace App\Controller\Api\Supply;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class SupplierController extends AbstractController
{
    #[Route('/api/supply/supplier', name: 'app_api_supply_supplier')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/Api/Supply/SupplierController.php',
        ]);
    }
}
