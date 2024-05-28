<?php

namespace App\Controller\Api\Media;

use App\Controller\MainController;
use App\Repository\FileRepository;
use App\Service\FileService;
use App\Service\MailerService;
use App\Service\PhpseclibService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Transport\Dsn;

#[Route('/api/files')]
class FileController extends MainController
{
    private PhpseclibService $phpseclibService;
    private FileService $fileService;
    private MailerService $mailerService;

    function __construct(PhpseclibService $phpseclibService, FileService $fileService, MailerService $mailerService)
    {
        $this->phpseclibService = $phpseclibService;
        $this->fileService = $fileService;
        $this->mailerService = $mailerService;
    }

    //! GET FILE 

    #[Route('/{id}', name: 'app_api_file_getFile', methods: ['GET'])]
    public function getFile(int $id, FileRepository $fileRepository): JsonResponse
    {
        $file = $fileRepository->find($id);
        if (!$file)
            return new JsonResponse(['error' => 'File not found'], Response::HTTP_NOT_FOUND);

        return $this->json($file, Response::HTTP_OK, [], ['groups' => 'fileWithRelation']);

    }


    //! UPLOAD FILE
    #[Route('/upload', name: 'app_api_file_upload', methods: ['POST'])]
    public function upload(Request $request): JsonResponse
    {
        $sftp = $this->phpseclibService->authenticate();

        // Récupérer le fichier téléversé depuis la requête
        $uploadedFile = $request->files->get('file');
        $doctype = $request->request->get('docType');
        if (!$uploadedFile)
            return new JsonResponse(['error' => 'File not found'], Response::HTTP_BAD_REQUEST);

        // Chemin de téléversement sur le serveur distant
        $remoteBasePath = $this->phpseclibService->getFileUploadDirectory();
        $fileName = $uploadedFile->getClientOriginalName();
        $remoteFilePath = $remoteBasePath . "/" . $fileName;

        // verifier l'extension du fichier
        $notAllowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'jfif', "mp4", "mpeg", "mp3"];
        $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        if (in_array($extension, $notAllowedExtensions)) {
            return new JsonResponse(['error' => 'File extension not allowed'], Response::HTTP_BAD_REQUEST);
        }

        // Téléverser le fichier
        try {
            $this->phpseclibService->uploadFile($sftp, $uploadedFile->getPathname(), $remoteFilePath);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        //  Ajouter le fichier à la base de données

        $this->fileService->postDb($doctype, $remoteFilePath, $fileName);

        // envoyer un email de confirmation pour le fichier téléversé
        $sentMessage = $this->mailerService->sendEmail("contact@omika.fr", "hello subject", "im the body");
   
        return new JsonResponse([
            'message' => 'File uploaded successfully'
        ]);
    }

    //! Download FILE

    #[Route('/download', name: 'app_api_file_download', methods: ['GET'])]
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
            $this->phpseclibService->downloadFile($sftp, $remoteFilePath, $_ENV[ 'FILE_DOWNLOAD_DIRECTORY' ] . "\\" . $fileName);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        // Retourner le fichier téléchargé
        $response = new Response(file_get_contents($_ENV[ 'FILE_DOWNLOAD_DIRECTORY' ] . "\\" . $fileName));
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $fileName . '"');
        return $response;
    }

    //! DELETE FILE

    #[Route('/delete/{id}', name: 'app_api_file_delete', methods: ['DELETE'])]

    public function delete(int $id, FileRepository $fileRepository, EntityManagerInterface $em): JsonResponse
    {
        $file = $fileRepository->find($id);
        if (!$file)
            return new JsonResponse(['error' => 'File not found'], Response::HTTP_NOT_FOUND);

        $sftp = $this->phpseclibService->authenticate();
        $remoteBasePath = $this->phpseclibService->getFileUploadDirectory();
        $remoteFilePath = $remoteBasePath . "/" . $file->getName();

        // Supprimer le fichier du serveur distant
        try {
            $this->phpseclibService->deleteFile($sftp, $remoteFilePath);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        // Supprimer le fichier de la base de données
        $em->remove($file);
        $em->flush();

        return new JsonResponse([
            'message' => 'File deleted successfully'
        ]);
    }

}
