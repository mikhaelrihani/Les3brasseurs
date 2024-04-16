<?php

namespace App\Controller\Web\Media;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DateController extends AbstractController
{
    #[Route('/web/media/date', name: 'app_web_media_date')]
    public function index(): Response
    {
        return $this->render('web/media/date/index.html.twig', [
            'controller_name' => 'DateController',
        ]);
    }
}
