<?php

namespace App\Controller\Web\Inventory;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class InventoryController extends AbstractController
{
    #[Route('/web/inventory/inventory', name: 'app_web_inventory_inventory')]
    public function index(): Response
    {
        return $this->render('web/inventory/inventory/index.html.twig', [
            'controller_name' => 'InventoryController',
        ]);
    }
}
