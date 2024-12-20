<?php
declare(strict_types=1);

namespace App\Component\Profile;

use App\Component\Media\MediaFactory;
use App\Component\User\password\HashPasswordManager;
use App\Component\User\UserManager;
use App\Entity\MediaObject;
use App\Entity\Profile;
use App\Repository\UserRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Random\RandomException;

abstract class DirectorBase
{
    protected ?array $mediaObjects = [];

    protected ?MediaObject $mediaObject = null;

    public function __construct(
        protected readonly HashPasswordManager $hashPasswordManager,
        protected readonly UserManager         $userManager,
        private readonly MediaFactory          $mediaFactory,
        private readonly UserRepository        $userRepository,
    )
    {
    }

    /**
     * @throws NonUniqueResultException
     * @throws RandomException
     * @throws NoResultException
     */
    protected function buildUserWithPassword(?string $username, ?string $password): void
    {
        $username = $this->generateUniqueUsername($username);
        $password = $password . $this->generateUniqueIdentifier();

        $builder = $this->builder->buildUser($username, $this->dto->getRoles(), $password);

        $builder->hashPasswordBuild($password);
    }

    protected function updateMediaObject(): void
    {
        $this->mediaObject = $this->dto->getAvatar() ? $this->mediaFactory->create($this->dto->getAvatar()) : null;

        $this->mediaObjects = array_map(fn($file) => $this->mediaFactory->createFromFile($file), $this->dto->getFile() ?? []);
    }

    /**
     * @throws RandomException
     */
    protected function generateUniqueIdentifier(): string
    {
        return bin2hex(random_bytes(5));
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    protected function generateUniqueUsername(string $baseName): string
    {
        $newUserId = ($this->userRepository->findLastUserId() ?? 0) + 1;

        return $baseName . $newUserId;
    }

    protected function deleteMediaObject(?int $modelId): void
    {
        if ($modelId !== null) {
            $mediaObject = $this->mediaObjectRepository->find($modelId);
            if ($mediaObject instanceof MediaObject) {
                $this->mediaManager->deleted($mediaObject, true);
            }
        }
    }

    protected function processMediaObjects(Profile $profile, object $singUpRequestDto): void
    {
        if ($singUpRequestDto->getAvatar()){
            $this->updateMediaObject();
        }else{
            $this->mediaObject = $profile->getAvatar();
        }

        if (count($this->mediaObjects) > 0) {
            foreach ($profile->getFile() as $file) {
                $this->mediaManager->deleted($file, true);
            }
        }
    }
}