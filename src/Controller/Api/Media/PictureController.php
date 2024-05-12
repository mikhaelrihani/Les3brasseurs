<?php

namespace App\Controller\Api\Media;

use App\Controller\MainController;
use App\Entity\Picture;
use App\Repository\PictureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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


    //! GET PICTURE

    #[Route('/{id}', name: 'app_api_picture_getPicture', methods: 'GET')]
    public function getPicture(int $id): Picture|JsonResponse
    {
        $picture = $this->pictureRepository->find($id);
        if (!$picture) {
            return $this->json(["error" => "The picture with {$id} doesn't exist"], Response::HTTP_BAD_REQUEST);
        }
        return $picture;
    }


    //! DOWNLOAD PICTURE

    #[Route('/{id}/download', name: 'app_api_picture_download', methods: 'GET')]
    public function download(int $id, HttpClientInterface $httpClient): Response
    {
        $picture = $this->getPicture($id);
        $url = $picture->getPath();

        if (strpos($url, $this->baseURL) !== 0) {
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


    //! DELETE PICTURE

    #[Route('/{id}', name: 'app_api_picture_deletePicture', methods: 'DELETE')]
    public function deletePicture($id, EntityManagerInterface $em): JsonResponse
    {
        $picture = $this->getPicture($id);
        if ($picture instanceof JsonResponse) {
            return $this->json(["error" => "The picture with {$id} doesnt exist"], Response::HTTP_BAD_REQUEST);
        }
        $em->remove($picture);
        $em->flush();
        return $this->json(["message" => "The picture with {$id} has been deleted"], Response::HTTP_OK);
    }


    //! DELETE PICTURE FROM

    #[Route('/deleteFrom/{id}', name: 'app_api_picture_deletePictureFrom', methods: 'DELETE')]
    public function deletePictureFrom(int $id, Request $request, EntityManagerInterface $em): JsonResponse
    {
        $json = $request->query->get('entities');
        $entities = json_decode($json, true);
        $picture = $this->getPicture($id);
        if (!$picture) {
            return $this->json(["error" => "The picture with {$id} doesn't exist"], Response::HTTP_BAD_REQUEST);
        }

        // Récupérer toutes les associations de l'image avec les entités dans la table pivot
        $associations = [];
        foreach ($entities as $entity) {
            $entityTableName = $entity . "_picture";
            $sql = "SELECT {$entity}_id FROM {$entityTableName} WHERE picture_id = :pictureId";
            $stmt = $em->getConnection()->prepare($sql);
            $result = $stmt->executeQuery(['pictureId' => $picture->getId()]);
            $associations[$entity] = $result->fetchAllAssociative();
        }
        // Vérifier si une association existe pour chaque entité
        $associationsExist = [];
        foreach ($associations as $entity => $association) {
            $associationsExist[$entity] = empty($association);
        }

        // Vérifier si toutes les associations existent
        if (in_array(true, $associationsExist, true)) {
            return $this->json(["error" => "The picture with {$id} is not associated with any of selected entities"], Response::HTTP_BAD_REQUEST);
        }

        // Supprimer les associations seulement si elles existent
        foreach ($entities as $entity) {
            foreach ($associations[$entity] as $association) {

                $sqlDelete = "DELETE FROM {$entityTableName} WHERE {$entity}_id = :associationId AND picture_id = :pictureId";
                $stmtDelete = $em->getConnection()->prepare($sqlDelete);
                $stmtDelete->executeQuery([
                    'associationId' => $association[$entity . '_id'],
                    'pictureId'     => $picture->getId()
                ]);

            }
        }


        return $this->json(["message" => "The picture with {$id} has been deleted from selected entities"], Response::HTTP_OK);
    }

}
