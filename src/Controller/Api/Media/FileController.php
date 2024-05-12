<?php

namespace App\Controller\Api\Media;

use App\Controller\MainController;
use App\Service\PhpseclibService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;

#[Route('/api/files')]
class FileController extends MainController
{
    private PhpseclibService $phpseclibService;

    function __construct(PhpseclibService $phpseclibService)
    {
        $this->phpseclibService = $phpseclibService;
    }

    //! UPLOAD FILE
    #[Route('/upload', name: 'app_api_media_file', methods: ['POST'])]
    public function upload(Request $request): JsonResponse
    {
        $sftp = $this->phpseclibService->authenticate();

        // Récupérer le fichier téléversé depuis la requête
        $uploadedFile = $request->files->get('file');
        if (!$uploadedFile)
            return new JsonResponse(['error' => 'File not found'], Response::HTTP_BAD_REQUEST);

        // Chemin de téléversement sur le serveur distant
        $remoteBasePath = $this->phpseclibService->getFileUploadDirectory();
        $fileName = $uploadedFile->getClientOriginalName();
        $remoteFilePath = $remoteBasePath . $fileName;

        // Téléverser le fichier
        try {
            $this->phpseclibService->uploadFile($sftp, $uploadedFile->getPathname(), $remoteFilePath);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return new JsonResponse([
            'message' => 'File uploaded successfully'
        ]);
    }

    //! Download FILE

    #[Route('/download', name: 'app_api_media_file_download', methods: ['GET'])]
    public function download(Request $request): Response
    {
        $sftp = $this->phpseclibService->authenticate();

        // Récupérer le nom du fichier à télécharger
        $fileName = $request->query->get('file');
        if (!$fileName)
            return new JsonResponse(['error' => 'File not found'], Response::HTTP_BAD_REQUEST);

        // Chemin du fichier sur le serveur distant
        $remoteBasePath = $this->phpseclibService->getFileUploadDirectory();
        $remoteFilePath = $remoteBasePath . "/" . $fileName;

        // Télécharger le fichier
        try {
            $localFilePath = "public\download";
            //dd($localFilePath);
            $this->phpseclibService->downloadFile($sftp, $remoteFilePath, $localFilePath);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        // Retourner le fichier téléchargé
        $response = new Response(file_get_contents($localFilePath));
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $fileName . '"');
        return $response;
    }

}
