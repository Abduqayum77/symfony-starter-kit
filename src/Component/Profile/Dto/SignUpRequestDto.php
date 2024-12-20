<?php

namespace App\Component\Profile\Dto;

use App\Entity\District;
use App\Entity\MediaObject;
use App\Entity\PhoneNumber;
use App\Entity\Region;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

readonly class SignUpRequestDto
{
    public function __construct(
        #[Groups(['profile:roles:write'])]
        #[Assert\NotNull]
        private ?string $name = null,

        /** @var PhoneNumber[]|null */
        #[Groups(['profile:roles:write'])]
        #[Assert\NotNull]
        private ?array $phones = null,

        #[Groups(['profile:roles:write'])]
        #[Assert\NotNull]
        private ?string $licenceNumber = null,

        #[Groups(['profile:roles:write'])]
        private ?string $description = null,

        #[Groups(['profile:roles:write'])]
        private ?string $fullAddress = null,

        #[Groups(['profile:roles:write'])]
        #[Assert\NotNull]
        private ?array $roles = null,

        #[Groups(['profile:roles:write'])]
        private ?int $status = null,

        #[Groups(['profile:roles:write'])]
        private ?string $telegram = null,

        #[Groups(['profile:roles:write'])]
        #[Assert\Email]
        private ?string $email = null,

        #[Groups(['profile:roles:write'])]
        private ?int $type = null,

        #[Groups(['profile:roles:write'])]
        private ?Region $region = null,

        #[Groups(['profile:roles:write'])]
        private ?District $district = null,

        #[Groups(['profile:roles:write'])]
        private ?string $avatar = null,

        /** @var MediaObject[]|null */
        #[Groups(['profile:roles:write'])]
        private ?array $file = null,

    ) {
    }

    public function getStatus(): ?int
    {
        return $this->status ?? 1;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }


    public function getName(): string
    {
        return $this->name;
    }

    public function getPhones(): array
    {
        return $this->phones;
    }

    public function getLicenceNumber(): string
    {
        return $this->licenceNumber ?? 'string';
    }

    public function getFullAddress(): string
    {
        return $this->fullAddress ?? 'string';
    }

    public function getRoles(): array
    {
        $roles = $this->roles;

        return array_unique($roles);
    }

    public function getDistrict(): ?District
    {
        return $this->district;
    }

    public function getRegion(): ?Region
    {
        return $this->region;
    }

    public function getFile(): ?array
    {
        return $this->file;
    }

    public function getTelegram(): ?string
    {
        return $this->telegram;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

}
