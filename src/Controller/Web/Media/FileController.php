<?php

namespace App\Controller\Web\Media;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/web/file')]
class FileController extends AbstractController
{
    //! GET Files Explorer from external storage
    #[Route('/getFilesExplorer', name: 'app_web_file_getFilesExplorer', methods: ['GET'])]
    public function getFilesExplorer(): Response
    {
        return $this->render('filesExplorer.html.twig');
    }
}
