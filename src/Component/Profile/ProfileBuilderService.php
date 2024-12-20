<?php

declare(strict_types=1);

namespace App\Component\Profile;

use App\Entity\MediaObject;

class ProfileBuilderService
{
    public function buildProfile(
        object $builder,
        object $dto,
        ?array $mediaObjects = null,
        ?MediaObject $mediaObject = null,
    ): object {
        return $builder->buildProfile(
            $dto->getName(),
            $dto->getPhones(),
            $dto->getLicenceNumber(),
            $dto->getDescription(),
            $dto->getFullAddress(),
            $dto->getTelegram(),
            $dto->getEmail(),
            $dto->getType(),
            $dto->getRegion(),
            $dto->getDistrict(),
            $mediaObject,
            $mediaObjects,
            $dto->getStatus()
        );
    }
}
