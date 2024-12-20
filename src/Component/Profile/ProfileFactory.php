<?php

declare(strict_types=1);

namespace App\Component\Profile;

use App\Entity\District;
use App\Entity\MediaObject;
use App\Entity\Profile;
use App\Entity\Region;
use App\Entity\User;
use Doctrine\Common\Collections\Collection;

readonly class ProfileFactory
{
    public function create(
        ?string $name,
        ?array $phones,
        ?string $licenceNumber,
        ?string $description,
        ?User $user,
        ?string $fullAddress = null,
        ?string $telegram = null,
        ?string $email = null,
        ?int $type = null,
        ?Region $region = null,
        ?District $district = null,
        ?MediaObject $mediaObject = null,
        ?array $files = null,
        ?int $status = null,
    ): Profile {
        $profile = (new Profile())
            ->setName($name)
            ->setLicenceNumber($licenceNumber)
            ->setDescription($description)
            ->setTelegram($telegram)
            ->setEmail($email)
            ->setType($type)
            ->setUser($user)
            ->setFullAddress($fullAddress)
            ->setRegion($region)
            ->setDistrict($district)
            ->setAvatar($mediaObject)
            ->setStatus($status);

        return $this->extracted($profile, $phones, $files);
    }

    /**
     * @param Profile $profile
     * @param array|null $phones
     * @param array|null $files
     * @return Profile
     */
    private function extracted(Profile $profile, ?array $phones, ?array $files): Profile
    {
        $this->updateProfileCollection($phones, $profile->getPhones(), fn($item) => $profile->addPhone($item));

        if ($files !== null) {
            foreach ($files as $file) {
                $profile->addFile($file);
            }
        }

        return $profile;
    }

    private function updateProfileCollection(?array $items, Collection $collection, callable $addFunction): void
    {
        if ($items !== null) {
            if ($collection->first()) {
                $collection->clear();
            }
            foreach ($items as $item) {
                $addFunction($item);
            }
        }
    }

    public function update(
        Profile $profile,
        ?string $name,
        ?array $phones,
        ?string $licenceNumber,
        ?string $description,
        ?User $user,
        ?string $fullAddress = null,
        ?string $telegram = null,
        ?string $email = null,
        ?int $type = null,
        ?Region $region = null,
        ?District $district = null,
        ?MediaObject $mediaObject = null,
        ?array $files = null,
        ?int $status = null,
    ): Profile {
        $profile
            ->setName($name)
            ->setLicenceNumber($licenceNumber)
            ->setDescription($description)
            ->setTelegram($telegram)
            ->setEmail($email)
            ->setType($type)
            ->setUser($user)
            ->setFullAddress($fullAddress)
            ->setRegion($region)
            ->setDistrict($district)
            ->setAvatar($mediaObject)
            ->setStatus($status);

        return $this->extracted($profile, $phones, $files);
    }
}
