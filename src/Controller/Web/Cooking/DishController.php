<?php

namespace App\Controller\Web\Cooking;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DishController extends AbstractController
{
    #[Route('/web/cooking/dish', name: 'app_web_cooking_dish')]
    public function index(): Response
    {
        return $this->render('web/cooking/dish/index.html.twig', [
            'controller_name' => 'DishController',
        ]);
    }
}
