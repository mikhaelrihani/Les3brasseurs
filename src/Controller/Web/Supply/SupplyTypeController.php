<?php

namespace App\Controller\Web\Supply;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SupplyTypeController extends AbstractController
{
    #[Route('/web/supply/supply/type', name: 'app_web_supply_supply_type')]
    public function index(): Response
    {
        return $this->render('web/supply/supply_type/index.html.twig', [
            'controller_name' => 'SupplyTypeController',
        ]);
    }
}
