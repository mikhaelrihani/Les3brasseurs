<?php

namespace App\Service;
use ImageKit\ImageKit;

class ImageKitService {

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

}