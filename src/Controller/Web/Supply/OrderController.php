<?php

namespace App\Controller\Web\Supply;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class OrderController extends AbstractController
{
    #[Route('/web/supply/order', name: 'app_web_supply_order')]
    public function index(): Response
    {
        return $this->render('web/supply/order/index.html.twig', [
            'controller_name' => 'OrderController',
        ]);
    }
}
