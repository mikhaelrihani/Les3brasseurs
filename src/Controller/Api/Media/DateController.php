<?php

namespace App\Controller\Api\Media;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DateController extends AbstractController
{
    #[Route('/api/media/date', name: 'app_api_media_date')]
    public function index(): Response
    {
        return $this->render('api/media/date/index.html.twig', [
            'controller_name' => 'DateController',
        ]);
    }
}
