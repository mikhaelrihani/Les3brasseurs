<?php

namespace App\Controller\Web\Cooking;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MenuController extends AbstractController
{
    #[Route('/web/cooking/menu', name: 'app_web_cooking_menu')]
    public function index(): Response
    {
        return $this->render('web/cooking/menu/index.html.twig', [
            'controller_name' => 'MenuController',
        ]);
    }
}
