<?php

namespace App\Controller\Web\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class JobController extends AbstractController
{
    #[Route('/web/user/job', name: 'app_web_user_job')]
    public function index(): Response
    {
        return $this->render('web/user/job/index.html.twig', [
            'controller_name' => 'JobController',
        ]);
    }
}
