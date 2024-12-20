<?php
declare(strict_types=1);

namespace App\Component\Profile\Services;

use App\Component\Media\MediaFactory;
use App\Component\Media\MediaManager;
use App\Component\Profile\DirectorBase;
use App\Component\Profile\Dto\SignUpRequestDto;
use App\Component\Profile\ProfileFactory;
use App\Component\Profile\ProfileManager;
use App\Component\User\password\HashPasswordManager;
use App\Component\User\UserManager;
use App\Entity\Profile;
use App\Repository\MediaObjectRepository;
use App\Repository\UserRepository;

class ProfileUpdateService extends DirectorBase
{
    protected SignUpRequestDto|null $dto = null;

    public function __construct(
        protected ProfileManager $profileManager,
        protected ProfileFactory $profileFactory,
        protected MediaManager  $mediaManager,
        protected MediaObjectRepository $mediaObjectRepository,
        HashPasswordManager $hashPasswordManager,
        UserManager $userManager,
        MediaFactory $mediaFactory,
        UserRepository $userRepository
    )
    {
        parent::__construct($hashPasswordManager, $userManager, $mediaFactory, $userRepository);
    }

    public function update(Profile $profile, SignUpRequestDto $singUpRequestDto): Profile
    {
        $this->dto = $singUpRequestDto;
        $currentAvatarId = $profile->getAvatar()?->getId();
        $this->processMediaObjects($profile, $singUpRequestDto);

        $profile = $this->create($profile, $singUpRequestDto);

        if ($singUpRequestDto->getAvatar() && $currentAvatarId) {
            $this->deleteMediaObject($currentAvatarId);
        }

        return $profile;
    }

    private function create(Profile $profile, SignUpRequestDto $singUpRequestDto): Profile
    {
        $updateProfile = $this->updateProfile($profile, $singUpRequestDto);

        $this->profileManager->save($updateProfile, true);

        return $updateProfile;
    }

    protected function updateProfile(Profile $profile, SignUpRequestDto $singUpRequestDto): Profile
    {
        return $this->profileFactory->update(
            $profile,
            $singUpRequestDto->getName(),
            $singUpRequestDto->getPhones(),
            $singUpRequestDto->getLicenceNumber(),
            $singUpRequestDto->getDescription(),
            $profile->getUser(),
            $singUpRequestDto->getFullAddress(),
            $singUpRequestDto->getTelegram(),
            $singUpRequestDto->getEmail(),
            $singUpRequestDto->getType(),
            $singUpRequestDto->getRegion(),
            $singUpRequestDto->getDistrict(),
            $this->mediaObject,
            $this->mediaObjects,
            1,
        );
    }
}
