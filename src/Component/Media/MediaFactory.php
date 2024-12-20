<?php

declare(strict_types=1);

namespace App\Component\Media;

use App\Entity\MediaObject;
use App\Utils\Base64FileExtractor;
use App\Utils\UploadedBase64File;

readonly class MediaFactory
{
    public function __construct(private Base64FileExtractor $base64Extractor)
    {
    }

    public function create(?string $file): MediaObject
    {
        $imageFile = $this->createUploadedFile($file);
        return (new MediaObject())->setFile($imageFile);
    }

    private function createUploadedFile(?string $base64File): UploadedBase64File
    {
        $base64Image = $this->base64Extractor->extractBase64String($base64File);
        $extension = $this->extractExtensionFromBase64($base64File);
        return new UploadedBase64File($base64Image, $extension);
    }

    public function extractExtensionFromBase64(string $base64): string
    {
        preg_match("/\/(.*?);/", $base64, $match);

        return ".$match[1]";
    }

    public function createFromFile(?MediaObject $file): MediaObject
    {
        $imageFile = $this->createUploadedFile($file->getImage());
        return (new MediaObject())
            ->setFile($imageFile)
            ->setName($file->getName())
            ->setDate($file->getDate());
    }
}
