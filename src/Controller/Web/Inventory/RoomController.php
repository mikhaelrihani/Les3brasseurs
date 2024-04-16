<?php

namespace App\Controller\Web\Inventory;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RoomController extends AbstractController
{
    #[Route('/web/inventory/room', name: 'app_web_inventory_room')]
    public function index(): Response
    {
        return $this->render('web/inventory/room/index.html.twig', [
            'controller_name' => 'RoomController',
        ]);
    }
}
