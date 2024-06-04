<?php

namespace App\Controller\Api\Media;

use App\Controller\MainController;
use App\Repository\FileRepository;
use App\Service\FileService;
use App\Service\MailerService;
use App\Service\PhpseclibService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Requirement\Requirement;


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

    #[Route('/{id}', name: 'app_api_file_getFile', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function getFile(int $id, FileRepository $fileRepository): JsonResponse
    {
        $file = $fileRepository->find($id);
        if (!$file)
            return new JsonResponse(['error' => 'File not found'], Response::HTTP_NOT_FOUND);

        return $this->json($file, Response::HTTP_OK, [], ['groups' => 'fileWithRelation']);

    }

    //! GET FILES EXPLORER

    #[Route('/explorer', name: 'app_api_file_getFilesExplorer', methods: ['GET'])]
    public function getFilesExplorer(): JsonResponse
    {
        $files = $this->phpseclibService->listFiles();
        return new JsonResponse(['files' => $files], Response::HTTP_OK);
    }

    //! UPLOAD FILE in external directory of application
    #[Route('/upload', name: 'app_api_file_upload', methods: ['POST'])]
    public function upload(Request $request): JsonResponse
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

    //! UPLOAD FILE in public directory of application
    #[Route('/uploadPublic', name: 'app_api_file_uploadPublic', methods: ['POST'])]
    public function uploadPublic(Request $request): JsonResponse
    {
        $file = $request->files->get('file');

        if (!$file) {
            return new JsonResponse(['error' => 'No file uploaded'], Response::HTTP_BAD_REQUEST);
        }

        $uploadDirectory = $this->getParameter('upload_directory');
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $newFilename = uniqid() . '-' . $originalFilename . '.' . $file->guessExtension();

        try {
            $file->move($uploadDirectory, $newFilename);
        } catch (FileException $e) {
            return new JsonResponse(['error' => 'Failed to upload file'], Response::HTTP_BAD_REQUEST);
        }

        $publicPath = $this->getParameter('public_path') . '/upload/' . $newFilename;

        return new JsonResponse(['url' => $publicPath], Response::HTTP_OK);
    }


    //! Download FILE from external Files folder to public directory of application or to local directory

    #[Route('/download/{location}', name: 'app_api_file_download', methods: ['POST'])]
    public function download(Request $request, string $location = "local"): Response
    {
        if ($location != "public" && $location != "local")
            return new JsonResponse(['error' => 'location not found'], Response::HTTP_BAD_REQUEST);

        // Récupérer le path et nom du fichier à télécharger 
        $data = json_decode($request->getContent(), true);
        $fileExternalPath = $data[ 'fileExternalPath' ];
        $fileName = $data[ 'fileName' ];

        if (!$fileExternalPath || !$fileName)
            return new JsonResponse(['error' => 'fileExternalPath or fileName not found'], Response::HTTP_BAD_REQUEST);

        // Télécharger le fichier dans public directory
        $filePublicPath = $_ENV[ 'FILE_DOWNLOAD_DIRECTORY' ] . "\\" . $fileName;
        try {
            $this->phpseclibService->downloadFile($fileExternalPath, $filePublicPath);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        // Retourner le fichier téléchargé ou le chemin du fichier téléchargé
        if ($location == 'public') {
            return $this->file($filePublicPath);
        }
        $response = new Response(file_get_contents($filePublicPath));
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
