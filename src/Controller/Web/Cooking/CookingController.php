<?php

namespace App\Controller\Web\Cooking;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CookingController extends AbstractController
{
    #[Route('/web/cooking/cooking', name: 'app_web_cooking_cooking')]
    public function index(): Response
    {
        return $this->render('web/cooking/cooking/index.html.twig', [
            'controller_name' => 'CookingController',
        ]);
    }
}
