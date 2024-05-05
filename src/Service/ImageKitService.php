<?php

namespace App\Service;

use ImageKit\ImageKit;

class ImageKitService
{

    public function authenticateImageKit()
    {
        $publicKey = "public_9rL++me2E5sp4hd/SfWx85HQPHs=";
        $privateKey = "private_rF6W5sepAVpYcvw+W0OiQSaBPFc=";
        $urlEndPoint = "https://ik.imagekit.io/rxh7fpksm";

        $imageKit = new ImageKit(
            $publicKey,
            $privateKey,
            $urlEndPoint
        );

        return $imageKit;
    }

    public function uploadUniquePictures($pictures, $uniqueId = false)
    {
        try {
            $uniquesPictures = array_values(array_unique($pictures, SORT_REGULAR));
            if (empty($uniquesPictures)) {
                throw new \Exception("No picture uploaded");
            }

            $uploadedPictures = [];

            // on boucle un upload, sur image kit serveur, pour chaque picture en verifiant l extension
            // image kit verifie l unicite des photos par le nom
            foreach ($uniquesPictures as $picture) {
                $extension = strtolower(pathinfo($picture->getClientOriginalName(), PATHINFO_EXTENSION));
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'jfif'];
                if (!in_array($extension, $allowedExtensions)) {
                    throw new \InvalidArgumentException("Invalid image file extension");
                }
                // pour chaque picture uploadé on associe un tableau avec la picture et son extension
                $base64 = base64_encode(file_get_contents($picture));
                $pictureName = $picture->getClientOriginalName();
                $uploadedPicture = $this->authenticateImageKit()->uploadFile([
                    'file'              => $base64, # required, "binary","base64" or "file url"
                    'fileName'          => $pictureName, # required
                    'useUniqueFileName' => $uniqueId, // Utiliser un nom de fichier unique
                ]);
                ;
                $uploadedPictureArray = [$uploadedPicture, $extension];
                $uploadedPictures[] = $uploadedPictureArray;
            }

            return $uploadedPictures;
        } catch (\Exception $e) {
            throw new \Exception("Error uploading pictures: " . $e->getMessage());
        }
    }
}