<?php
namespace App\Service;

use App\Entity\File;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use finfo;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function postDb(string $docType, string $remoteFilePath, string $fileName)
    {
        $date = new DateTimeImmutable();
        $mimeType = pathinfo($remoteFilePath, PATHINFO_EXTENSION);
       
        $file = new File();
        $file->setName($fileName);
        $file->setPath($remoteFilePath);
        $file->setDocType($docType);
        $file->setMime($mimeType);
        $file->setCreatedAt($date);
        $file->setUpdatedAt($date);

        $this->entityManager->persist($file);
        $this->entityManager->flush();

        return $file;
    }
    

}
