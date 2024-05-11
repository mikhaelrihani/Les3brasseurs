<?php

namespace App\Controller\Api\Media;

use App\Controller\MainController;
use App\Entity\Picture;
use App\Repository\PictureRepository;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[Route('/api/pictures')]
class PictureController extends MainController
{

    private pictureRepository $pictureRepository;

    public function __construct(
        pictureRepository $pictureRepository
    ) {
        $this->pictureRepository = $pictureRepository;
    }


    #[Route('/{id}', name: 'app_api_picture_getPicture', methods: 'GET')]
    public function getPicture(int $id): Picture|JsonResponse
    {
        $picture = $this->pictureRepository->find($id);
        if (!$picture) {
            return $this->json(["error" => "The picture with {$id} doesn't exist"], Response::HTTP_BAD_REQUEST);
        }
        return $picture;
    }


    #[Route('/{id}/download', name: 'app_api_picture_download', methods: 'GET')]
    public function download(int $id, HttpClientInterface $httpClient): Response
    {
        $picture = $this->getPicture($id);
        $url = $picture->getPath();

        if (strpos($url, 'https://ik.imagekit.io/') !== 0) {
            // L'URL ne commence pas par "https://ik.imagekit.io/", retournez une erreur
            return new Response('L\'URL de l\'image est invalide', Response::HTTP_BAD_REQUEST);
        }
        $response = $httpClient->request('GET', $url);
        $imageContent = $response->getContent();
        $response = new Response($imageContent);

        // Définissez l'en-tête Content-Disposition pour forcer le téléchargement
        $disposition = HeaderUtils::makeDisposition(
            HeaderUtils::DISPOSITION_ATTACHMENT,
            basename($url)
        );

        $response->headers->set('Content-Disposition', $disposition);
        $response->headers->set('Location', $url);

        return $response;
    }


    #[Route('/{id}', name: 'app_api_picture_deletePicture', methods: 'DELETE')]
    public function deletePicture(int $id, array $entities): JsonResponse
    {
        $picture = $this->getPicture($id);
        if (!$picture) {
            return $this->json(["error" => "The picture with {$id} doesn't exist"], Response::HTTP_BAD_REQUEST);
        }
        $this->pictureRepository->removePictureFrom($picture, $entities);
        return $this->json(["message" => "The picture with {$id} has been deleted"], Response::HTTP_OK);
    }

}
