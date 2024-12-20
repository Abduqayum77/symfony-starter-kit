<?php
declare(strict_types=1);

namespace App\Component\Profile;


use App\Component\User\password\HashPasswordFactory;
use App\Component\User\UserFactory;
use App\Entity\District;
use App\Entity\MediaObject;
use App\Entity\Password;
use App\Entity\Profile;
use App\Entity\Region;
use App\Entity\User;

class SignUpBuilder
{
    protected User $user;

    protected Profile $profile;

    protected Password $password;

    public function __construct(
        private readonly UserFactory             $userFactory,
        private readonly ProfileFactory          $profileFactory,
        private readonly HashPasswordFactory     $passwordFactory,
    )
    {
    }

    public function buildUser(?string $email, ?array $role, ?string $password): self
    {
        $this->user = $this->userFactory->create($email, $role, $password);

        return $this;
    }

    public function hashPasswordBuild($password): self
    {
        $has = $this->passwordFactory->encrypt($password);
        $this->password = $this->passwordFactory->create($this->user, $has);

        return $this;
    }

    public function buildProfile(
        ?string $name,
        ?array $phones,
        ?string $licenceNumber,
        ?string $description,
        ?string $fullAddress = null,
        ?string $telegram = null,
        ?string $email = null,
        ?int $type = null,
        ?Region $region = null,
        ?District $district = null,
        ?MediaObject $mediaObject = null,
        ?array $files = null,
        ?int $status = null,
    ): self
    {

        $this->profile = $this->profileFactory->create(
            $name,
            $phones,
            $licenceNumber,
            $description,
            $this->user,
            $fullAddress,
            $telegram,
            $email,
            $type,
            $region,
            $district,
            $mediaObject,
            $files,
            $status,
        );

        return $this;

    }

    public function getHashPassword(): Password
    {
        return $this->password;
    }


    public function getResult(): Profile
    {
        return $this->profile;
    }

}