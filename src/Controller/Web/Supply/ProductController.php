<?php

namespace App\Controller\Web\Supply;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProductController extends AbstractController
{
    #[Route('/web/supply/product', name: 'app_web_supply_product')]
    public function index(): Response
    {
        return $this->render('web/supply/product/index.html.twig', [
            'controller_name' => 'ProductController',
        ]);
    }
}
