<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Controller\DeleteAction;
use App\Repository\RegionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups as Group;
#[ApiResource(
    operations: [
        new GetCollection(
            name: 'regions/index'
        ),
        new Post(
            name: 'regions/create'
        ),
        new Get(
            name: 'regions/view'
        ),
        new Put(
            name: 'regions/update'
        ),
        new Delete(
            controller: DeleteAction::class,
            name: 'regions/delete'
        ),
    ],
    cacheHeaders: [
        'max_age' => 3600 * 12,
        'shared_max_age' => 3600 * 12,
        'vary' => ['Authorization', 'Accept-Language']
    ],
    normalizationContext: ['groups' => ['region:read']],
    denormalizationContext: ['groups' => ['region:write']],
)]
#[ApiFilter(OrderFilter::class, properties: ['id', 'name'])]
#[ORM\Entity(repositoryClass: RegionRepository::class)]
class Region
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Group(['region:read', 'center:read', 'profile:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Group([
        'region:read',
        'center:read',
        'branch:centers:read',
        'profile:read',
        'district:read',
        'educational_institution:read'
    ])]
    private ?string $name = null;
    #[ORM\OneToMany(targetEntity: District::class, mappedBy: 'region')]
    #[Group(['region:read'])]
    private Collection $districts;

    #[ORM\OneToMany(targetEntity: Profile::class, mappedBy: 'region')]
    private Collection $profiles;

    public function __construct()
    {
        $this->districts = new ArrayCollection();
        $this->profiles = new ArrayCollection();
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
     * @return Collection<int, District>
     */
    public function getDistricts(): Collection
    {
        return $this->districts;
    }

    /**
     * @return Collection<int, Profile>
     */
    public function getProfiles(): Collection
    {
        return $this->profiles;
    }

    public function addProfile(Profile $profile): self
    {
        if (!$this->profiles->contains($profile)) {
            $this->profiles->add($profile);
            $profile->setRegion($this);
        }

        return $this;
    }

    public function removeProfile(Profile $profile): self
    {
        if ($this->profiles->removeElement($profile)) {
            // set the owning side to null (unless already changed)
            if ($profile->getRegion() === $this) {
                $profile->setRegion(null);
            }
        }

        return $this;
    }
}
