<?php

namespace App\Controller\Web\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class GroupController extends AbstractController
{
    #[Route('/web/user/group', name: 'app_web_user_group')]
    public function index(): Response
    {
        return $this->render('web/user/group/index.html.twig', [
            'controller_name' => 'GroupController',
        ]);
    }
}
