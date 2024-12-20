<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Component\Profile\Dto\SignUpRequestDto;
use App\Controller\DeleteAction;
use App\Controller\Profile\UserSignUpDtoAction;
use App\Controller\Profile\UserSignUpUpdateAction;
use App\Entity\Interfaces\CreatedAtSettableInterface;
use App\Entity\Interfaces\CreatedBySettableInterface;
use App\Entity\Interfaces\DeletedAtSettableInterface;
use App\Entity\Interfaces\DeletedBySettableInterface;
use App\Entity\Interfaces\UpdatedAtSettableInterface;
use App\Entity\Interfaces\UpdatedBySettableInterface;
use App\Filters\ProfileRoleFilter;
use App\Repository\ProfileRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ProfileRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(
//            security: "is_granted('profiles/index', object)",
            filters: [ProfileRoleFilter::class],
            name: 'profiles/index'
        ),
        new Post(
            uriTemplate: 'profiles/center/create',
            controller: UserSignUpDtoAction::class,
            denormalizationContext: ['groups' => ['profile:roles:write']],
            input: SignUpRequestDto::class,
            name: 'profiles/center_create'
        ),

        new Get(
            security: "object.getUser() == user || is_granted('profiles/view', object)",
            name: 'profiles/view'
        ),

        new Put(
            uriTemplate: 'profiles/center/{id}/update',
            controller: UserSignUpUpdateAction::class,
            denormalizationContext: ['groups' => ['profile:roles:write']],
//            security: "is_granted('profiles/user_update', object)",
            input: SignUpRequestDto::class,
            name: 'profiles/center_update'
        ),

        new Delete(
            controller: DeleteAction::class,
            security: "is_granted('profiles/delete', object)",
            name: 'profiles/delete'
        ),
    ],
    normalizationContext: ['groups' => ['profile:read']],
    denormalizationContext: ['groups' => ['profile:write']],
)]
#[ApiFilter(SearchFilter::class, properties: [
    'id' => 'exact',
    'name' => 'partial',
    'firstName' => 'partial',
    'midilName' => 'partial',
    'psSerea' => 'partial',
    'psJShR' => 'partial',
    'psNumer' => 'partial',
    'fullAddress' => 'partial',
    'user.roles' => 'partial',
    'phone' => 'partial',
    'user' => 'exact',
    'status' => 'exact',
])]
#[ApiFilter(OrderFilter::class, properties: [
    'id',
    'lastName',
    'firstName',
    'midilName',
    'psSerea',
    'psJShR',
    'user.roles',
    'createdAt',
    'updatedAt',
    'psNumer'
])]
class Profile implements
    CreatedAtSettableInterface,
    UpdatedAtSettableInterface,
    DeletedAtSettableInterface,
    CreatedBySettableInterface,
    UpdatedBySettableInterface,
    DeletedBySettableInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups([
        'profile:read',
        'users:read',
        'profile:write',
    ])]
    private ?int $id = null;


    #[ORM\Column(length: 255)]
    #[Groups([
        'profile:read',
        'users:read',
        'profile:write'
    ])]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups([
        'profile:read',
        'users:read',
        'profile:write'
    ])]
    private ?string $licenceNumber = null;

    #[ORM\Column(length: 255)]
    #[Groups([
        'profile:read',
        'users:read',
        'profile:write'
    ])]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['profile:read', 'profile:write'])]
    private ?string $fullAddress = null;

    #[ORM\Column(length: 255)]
    #[Groups(['profile:read', 'users:read', 'debtors:read',  'profile:write'])]
    private ?string $telegram = null;

    #[ORM\Column(length: 255)]
    #[Groups(['profile:read', 'users:read', 'debtors:read',  'profile:write'])]
    private ?string $email = null;

    #[ORM\Column]
    #[Groups(['profile:read', 'std:group:read', 'profile:write'])]
    private ?int $status = null;

    #[ORM\Column]
    #[Groups(['profile:read', 'std:group:read', 'profile:write'])]
    private ?int $type = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups(['profile:read'])]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups(['profile:read'])]
    private ?\DateTimeInterface $deletedAt = null;

    #[ORM\Column]
    #[Groups(['profile:read'])]
    private ?bool $isDeleted = false;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups(['profile:read'])]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\OneToOne]
    #[Groups(['profile:read'])]
    private ?User $user = null;

    #[ORM\ManyToOne]
    private ?User $createdBy = null;

    #[ORM\ManyToOne]
    private ?User $updatedBy = null;

    #[ORM\ManyToOne]
    private ?User $deletedBy = null;

    #[ORM\ManyToOne(cascade: ['persist', 'remove'])]
    #[Groups(['profile:read', 'chats:read'])]
    private ?MediaObject $avatar = null;

    #[ORM\ManyToOne(inversedBy: 'profiles')]
    #[Groups(['profile:read', 'profile:write'])]
    private ?Region $region = null;

    #[ORM\ManyToOne(inversedBy: 'profiles')]
    #[Groups(['profile:read', 'profile:write'])]
    private ?District $district = null;

    #[ORM\OneToMany(targetEntity: MediaObject::class, mappedBy: 'profile', cascade: ['persist', 'remove'])]
    #[Groups(['profile:read', 'profile:write'])]
    private Collection $file;

    /**
     * @var Collection<int, PhoneNumber>
     */
    #[ORM\OneToMany(targetEntity: PhoneNumber::class, mappedBy: 'profile', cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[Groups(['profile:read', 'profile:write'])]
    private Collection $phones;

    public function __construct()
    {
        $this->file = new ArrayCollection();
        $this->phones = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }


    public function getLicenceNumber(): ?string
    {
        return $this->licenceNumber;
    }

    public function setLicenceNumber(string $licenceNumber): self
    {
        $this->licenceNumber = $licenceNumber;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getFullAddress(): ?string
    {
        return $this->fullAddress;
    }

    public function setFullAddress(string $fullAddress): self
    {
        $this->fullAddress = $fullAddress;

        return $this;
    }

    public function getTelegram(): ?string
    {
        return $this->telegram;
    }

    public function setTelegram(string $telegram): self
    {
        $this->telegram = $telegram;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $dateTime): self
    {
        $this->updatedAt = $dateTime;

        return $this;
    }

    public function getDeletedAt(): ?\DateTimeInterface
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(\DateTimeInterface $deletedAt): self
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    public function isIsDeleted(): ?bool
    {
        return $this->isDeleted;
    }

    public function setIsDeleted(bool $isDeleted): self
    {
        $this->isDeleted = $isDeleted;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $dateTime): self
    {
        $this->createdAt = $dateTime;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?UserInterface $user): self
    {
        $this->createdBy = $user;

        return $this;
    }

    public function getUpdatedBy(): ?User
    {
        return $this->updatedBy;
    }

    public function setUpdatedBy(?UserInterface $user): self
    {
        $this->updatedBy = $user;

        return $this;
    }

    public function getDeletedBy(): ?User
    {
        return $this->deletedBy;
    }

    public function setDeletedBy(?UserInterface $user): self
    {
        $this->deletedBy = $user;

        return $this;
    }

    public function getAvatar(): ?MediaObject
    {
        return $this->avatar;
    }

    public function setAvatar(?MediaObject $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getRegion(): ?Region
    {
        return $this->region;
    }

    public function setRegion(?Region $region): self
    {
        $this->region = $region;

        return $this;
    }

    public function getDistrict(): ?District
    {
        return $this->district;
    }

    public function setDistrict(?District $district): self
    {
        $this->district = $district;

        return $this;
    }

    /**
     * @return Collection<int, MediaObject>
     */
    public function getFile(): Collection
    {
        return $this->file;
    }

    public function addFile(?MediaObject $file): self
    {
        if ($file !== null && !$this->file->contains($file)) {
            $this->file->add($file);
            $file->setProfile($this);
        }

        return $this;
    }

    public function removeFile(MediaObject $file): self
    {
        if ($this->file->removeElement($file)) {
            // set the owning side to null (unless already changed)
            if ($file->getProfile() === $this) {
                $file->setProfile(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, PhoneNumber>
     */
    public function getPhones(): Collection
    {
        return $this->phones;
    }

    public function addPhone(PhoneNumber $phone): static
    {
        if (!$this->phones->contains($phone)) {
            $this->phones->add($phone);
            $phone->setProfile($this);
        }

        return $this;
    }

    public function removePhone(PhoneNumber $phone): static
    {
        if ($this->phones->removeElement($phone)) {
            // set the owning side to null (unless already changed)
            if ($phone->getProfile() === $this) {
                $phone->setProfile(null);
            }
        }

        return $this;
    }
}
