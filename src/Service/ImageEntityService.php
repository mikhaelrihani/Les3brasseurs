<?php

namespace App\Service;

use App\Entity\Picture;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;

class ImageEntityService
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function addPictures($entity, $id, $uploadedPictures)
    {
        try {
            $object = $this->em->getRepository($entity)->find($id);
            if (!$object) {
                throw new \InvalidArgumentException("The" . $entity . " with ID " . $id . " does not exist");
            }

            foreach ($uploadedPictures as $uploadedPictureArray) {
                // on filtre les pictures par le path pour verifier si la picture existe deja
                $path = $uploadedPictureArray[ 0 ]->result->url;
                $existingPicture = $object->getPicture()->filter(function ($picture) use ($path) {
                    return $picture->getPath() === $path;
                });

                if (!$existingPicture->isEmpty()) {
                    continue;
                }

                // Si l'image n'existe pas on ajoute en bdd 
                $newPicture = new Picture();
                $newPicture->setName($uploadedPictureArray[ 0 ]->result->name);
                $newPicture->setPath($path);
                $newPicture->setMime($uploadedPictureArray[ 1 ]);
                $newPicture->setCreatedAt(new DateTimeImmutable());
                $newPicture->setUpdatedAt(new DateTimeImmutable());
                $this->em->persist($newPicture);

                $object->addPicture($newPicture);
            }

            // Enregistrer les changements dans la base de données
            $this->em->flush();

            return $object;
        } catch (\Exception $e) {
            // Gérer l'erreur ici, par exemple, journaliser l'erreur ou lancer une nouvelle exception
            throw new \Exception("Error adding pictures: " . $e->getMessage());
        }
    }
}
