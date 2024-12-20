<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\DistrictRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups as Group;

#[ORM\Entity(repositoryClass: DistrictRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(
            name: 'districts/index'
        ),
    ],
    cacheHeaders: [
        'max_age' => 3600 * 12,
        'shared_max_age' => 3600 * 12,
        'vary' => ['Authorization', 'Accept-Language']
    ],
    normalizationContext: ['groups' => ['district:read']],
    denormalizationContext: ['groups' => ['district:write']],
)]
#[ApiFilter(OrderFilter::class, properties: ['id', 'name'])]
#[ApiFilter(SearchFilter::class, properties: [
    'name' => 'partial',
    'region.name' => 'partial'
])]
class District
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Group(['district:read', 'center:read', 'profile:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Group([
        'district:read',
        'center:read',
        'region:read',
        'branch:centers:read',
        'profile:read',
        'educational_institution:read'
    ])]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'districts')]
    #[Group(['district:read'])]
    private ?Region $region = null;

    #[ORM\OneToMany(targetEntity: Profile::class, mappedBy: 'district')]
    private Collection $profiles;

    public function __construct()
    {
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

    public function getRegion(): ?Region
    {
        return $this->region;
    }

    public function setRegion(?Region $region): self
    {
        $this->region = $region;

        return $this;
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
            $profile->setDistrict($this);
        }

        return $this;
    }

    public function removeProfile(Profile $profile): self
    {
        if ($this->profiles->removeElement($profile)) {
            // set the owning side to null (unless already changed)
            if ($profile->getDistrict() === $this) {
                $profile->setDistrict(null);
            }
        }

        return $this;
    }
}
