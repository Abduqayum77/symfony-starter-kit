<?php

declare(strict_types=1);

namespace App\Utils;

use App\Entity\MediaObject;

class ProcessFile extends ExtensionBase64
{
    public function processFile(MediaObject $mediaObject): void
    {
        $base64 = $mediaObject->getImage();

        $base64Image = (new Base64FileExtractor)->extractBase64String($base64);
        $imageFile = new UploadedBase64File($base64Image, $this->extensionBase64($base64));
        $mediaObject->setFile($imageFile);
    }
}
