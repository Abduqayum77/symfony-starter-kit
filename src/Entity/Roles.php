<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\RolesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: RolesRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(
//            security: "is_granted('ROLE_DEAN')",
            name: 'roles/index'
        ),
        new Post(
//            securityPostDenormalize: "is_granted('ROLE_DEAN')",
            name: 'roles/create'
        ),
        new Get(
            security: "is_granted('ROLE_DEAN')",
            name: 'roles/view'
        ),
        new Put(
            security: "is_granted('ROLE_DEAN')",
            name: 'roles/update'
        ),
        new Delete(
            security: "is_granted('ROLE_ROOT')",
            name: 'roles/delete'
        ),
    ],
    normalizationContext: ['groups' => ['roles:read']],
    denormalizationContext: ['groups' => ['roles:write']],
)]
#[ApiFilter(SearchFilter::class, properties: ['name' =>  'partial'])]
class Roles 
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['roles:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['roles:read', 'roles:write', 'task:read'])]
    private ?string $name = null;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'rol')]
    private Collection $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
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

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->addRol($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            $user->removeRol($this);
        }

        return $this;
    }
}
