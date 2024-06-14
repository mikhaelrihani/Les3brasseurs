<?php

namespace App\Controller\Web\Media;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/web/file')]
class FileController extends AbstractController
{
    //! GET Files Explorer from external storage
    #[Route('/FilesExplorer', name: 'app_web_file_FilesExplorer', methods: ['GET'])]
    public function FilesExplorer(): Response
    {
        return $this->render('filesExplorer.html.twig');
    }
}
