<?php

namespace App\Controller\Web\Supply;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SupplierController extends AbstractController
{
    #[Route('/web/supply/supplier', name: 'app_web_supply_supplier')]
    public function index(): Response
    {
        return $this->render('web/supply/supplier/index.html.twig', [
            'controller_name' => 'SupplierController',
        ]);
    }
}
