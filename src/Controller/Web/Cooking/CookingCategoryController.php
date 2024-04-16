<?php

namespace App\Controller\Web\Cooking;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CookingCategoryController extends AbstractController
{
    #[Route('/web/cooking/cooking/category', name: 'app_web_cooking_cooking_category')]
    public function index(): Response
    {
        return $this->render('web/cooking/cooking_category/index.html.twig', [
            'controller_name' => 'CookingCategoryController',
        ]);
    }
}
