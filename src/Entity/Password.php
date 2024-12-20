<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use App\Controller\PasswordGetAction\PasswordGetAction;
use App\Repository\PasswordRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PasswordRepository::class)]
#[ApiResource(
    operations: [
        new Post(
            uriTemplate: 'passwords/check',
            controller: PasswordGetAction::class,
            //securityPostDenormalize: "object.getUser() == user || is_granted('passwords/check', object)",
            name: 'passwords/check'
        ),
    ],
    normalizationContext: ['groups' => ['password:read']],
    denormalizationContext: ['groups' => ['password:write']]
)]
#[ApiFilter(SearchFilter::class, properties: ['user' => 'exact'])]
class Password
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    private ?User $user = null;

    #[Groups(['password:read', 'password:write'])]
    #[ORM\Column(length: 255)]
    private ?string $password = null;


    public function getId(): ?int
    {
        return $this->id;
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

    public function getPassword(): ?string
    {
        return $this->password;
    }


    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

}
