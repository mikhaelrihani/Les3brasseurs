<?php

namespace App\Controller\Api\Media;

use App\Controller\MainController;
use App\Repository\FileRepository;
use App\Service\FileService;
use App\Service\MailerService;
use App\Service\PhpseclibService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;



#[Route('/api/files')]
class FileController extends MainController
{
    private PhpseclibService $phpseclibService;
    private FileService $fileService;
    private MailerService $mailerService;
    private ParameterBagInterface $params;


    function __construct(PhpseclibService $phpseclibService, FileService $fileService, MailerService $mailerService, ParameterBagInterface $params)
    {
        $this->phpseclibService = $phpseclibService;
        $this->fileService = $fileService;
        $this->mailerService = $mailerService;
        $this->params = $params;
    }

    //! GET FILE 

    #[Route('/{id}', name: 'app_api_file_getFile', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function getFile(int $id, FileRepository $fileRepository): JsonResponse
    {
        $file = $fileRepository->find($id);
        if (!$file)
            return new JsonResponse(['error' => 'File not found'], Response::HTTP_NOT_FOUND);

        return $this->json($file, Response::HTTP_OK, [], ['groups' => 'fileWithRelation']);

    }

    //! GET FILES EXPLORER data
    #[Route('/explorer-data', name: 'app_api_file_explorer_data', methods: ['GET'])]
    public function getFilesExplorerData(): JsonResponse
    {
        $files = $this->phpseclibService->listFiles();

        $basePath = $this->params->get('fileUploadDirectory');
        $fileData = array_map(function ($file) use ($basePath) {
            return [
                'name' => basename($file),
                'path' => $basePath . '/' . $file
            ];
        }, $files);

        return new JsonResponse(['files' => $fileData], Response::HTTP_OK);
    }
    //!UPLOAD FILE TO PUBLIC DIRECTORY OF APPLICATION


    #[Route('/uploadApp', name: 'app_api_file_uploadApp', methods: ['POST'])]
    public function uploadApp($file): JsonResponse
    {
        if (!$file) {
            return new JsonResponse(['error' => 'No file uploaded'], Response::HTTP_BAD_REQUEST);
        }
        $fileName = $file->getClientOriginalName();
        $file->move($this->params->get('fileUploadDirectory'), $fileName);
        return new JsonResponse([
            'message' => 'File uploaded successfully'
        ]);

    }


    //! UPLOAD FILE to external storage
    #[Route('/uploadExternal', name: 'app_api_file_uploadExternal', methods: ['POST'])]
    public function uploadExternal(Request $request): JsonResponse
    {
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
            $this->phpseclibService->uploadFile($uploadedFile->getPathname(), $remoteFilePath);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        //  Ajouter le path du fichier à la base de données

        $this->fileService->postDb($doctype, $remoteFilePath, $fileName);

        // envoyer un email de confirmation pour le fichier téléversé
        $sentMessage = $this->mailerService->sendEmail("contact@omika.fr", "file uploaded with success to omika server", "im the file body");

        return new JsonResponse([
            'message' => 'File uploaded successfully'
        ]);
    }

    //! Download FILE from external storage --> to public directory of application
    //! return the public_file_url 
    //! or return the file itself by downloading it locally

    #[Route('/download/{location}', name: 'app_api_file_download', methods: ['POST'])]
    public function download(Request $request, string $location = "local"): Response
    {
        if ($location != "public" && $location != "local")
            return new JsonResponse(['error' => 'location not found'], Response::HTTP_BAD_REQUEST);

        // Récupérer le path et nom du fichier à télécharger 
        $data = $request->toArray();
        $fileExternalPath = $data[ 'fileExternalPath' ];
        $fileName = $data[ 'fileName' ];

        if (!$fileExternalPath || !$fileName)
            return new JsonResponse(['error' => 'fileExternalPath or fileName not found'], Response::HTTP_BAD_REQUEST);

        // Télécharger le fichier dans public directory
        $filePublicPath = $_ENV[ 'FILE_DOWNLOAD_DIRECTORY' ] . DIRECTORY_SEPARATOR . $fileName;
        try {
            $this->phpseclibService->downloadFile($fileExternalPath, $filePublicPath);

        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        if (!file_exists($filePublicPath)) {
            return new JsonResponse(['error' => 'File not found after download'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        // Retourner le fichier téléchargé ou le chemin du fichier téléchargé
        if ($location == 'public') {
            return $this->file($filePublicPath);
        }

        return new BinaryFileResponse($filePublicPath);
    }

    //! DELETE FILE

    #[Route('/delete/{id}', name: 'app_api_file_delete', methods: ['DELETE'])]

    public function delete(int $id, FileRepository $fileRepository, EntityManagerInterface $em): JsonResponse
    {
        $file = $fileRepository->find($id);
        if (!$file)
            return new JsonResponse(['error' => 'File not found'], Response::HTTP_NOT_FOUND);

        $remoteBasePath = $this->phpseclibService->getFileUploadDirectory();
        $remoteFilePath = $remoteBasePath . "/" . $file->getName();

        // Supprimer le fichier du serveur distant
        try {
            $this->phpseclibService->deleteFile($remoteFilePath);
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
