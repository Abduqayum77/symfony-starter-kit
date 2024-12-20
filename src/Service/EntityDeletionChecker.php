<?php
declare(strict_types=1);

namespace App\Service;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;

class EntityDeletionChecker
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function canDeleteEntity(object $entity): void
    {
        $associations = $this->entityManager->getClassMetadata(get_class($entity))->getAssociationMappings();

        foreach ($associations as $association) {
            $getter = 'get' . ucfirst($association['fieldName']);
            if (method_exists($entity, $getter)) {
                $relatedEntities = $entity->$getter();

                if ($relatedEntities instanceof Collection && !$relatedEntities->isEmpty()) {
                    throw new \RuntimeException(
                        sprintf("Cannot delete this entity. It is linked to: %s", $association['fieldName'])
                    );
                }
            }
        }
    }
}
