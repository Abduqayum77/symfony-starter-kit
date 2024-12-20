<?php

declare(strict_types=1);

namespace App\Component\Profile\Builders;

use AllowDynamicProperties;
use App\Component\Media\MediaFactory;
use App\Component\Profile\DirectorBase;
use App\Component\Profile\Dto\SignUpRequestDto;
use App\Component\Profile\ProfileBuilderService;
use App\Component\Profile\ProfileManager;
use App\Component\Profile\SignUpBuilder;
use App\Component\User\password\HashPasswordManager;
use App\Component\User\UserManager;
use App\Entity\Profile;
use App\Repository\UserRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Random\RandomException;

#[AllowDynamicProperties] class DirectorUsers extends DirectorBase
{
    protected SignUpBuilder $builder;
    protected ?SignUpRequestDto $dto = null;

    public function __construct(
        private readonly ProfileManager         $profileManager,
        private readonly ProfileBuilderService  $profileBuilderService,
        private readonly SignUpBuilder          $signUpBuilder,
        HashPasswordManager                     $hashPasswordManager,
        UserManager                             $userManager,
        MediaFactory                            $mediaFactory,
        UserRepository                          $userRepository,
    )
    {
        parent::__construct($hashPasswordManager, $userManager, $mediaFactory, $userRepository);
        $this->builder = $signUpBuilder;
    }

    /**
     * @throws NonUniqueResultException
     * @throws RandomException
     * @throws NoResultException
     */
    public function createSignUpUserAndSave(SignUpRequestDto $singUpRequestDto): Profile
    {

        $this->dto = $singUpRequestDto;

        $this->updateMediaObject();
        $this->buildUserWithPassword('role_center', 'role_');

        $profile = $this->profileBuilderService->buildProfile(
            $this->builder,
            $singUpRequestDto,
            $this->mediaObjects,
            $this->mediaObject,
        );

        $this->saveUserProfile();

        return $profile->getResult();
    }

    private function saveUserProfile(): void
    {
        $this->userManager->save($this->builder->getResult()->getUser());
        $this->hashPasswordManager->save($this->builder->getHashPassword());
        $this->profileManager->save($this->builder->getResult(), true);
    }

}
