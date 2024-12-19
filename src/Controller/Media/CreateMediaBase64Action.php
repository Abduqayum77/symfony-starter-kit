<?php

declare(strict_types=1);

namespace App\Controller\Media;

use App\Controller\Base\AbstractController;
use App\Entity\MediaObject;
use App\Utils\ProcessFile;

class CreateMediaBase64Action extends AbstractController
{
    public function __invoke(MediaObject $mediaObject, ProcessFile $processFile): MediaObject
    {
        $this->validate($mediaObject);
        $processFile->processFile($mediaObject);

        return $mediaObject;
    }
}
