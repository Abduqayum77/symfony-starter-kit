<?php

declare(strict_types=1);

namespace App\Component\Core;

use App\Component\User\CurrentUser;
use App\Entity\Interfaces\DeletedAtSettableInterface;
use App\Entity\Interfaces\DeletedBySettableInterface;
use App\Entity\Interfaces\DeletedByStatusInterface;
use App\Entity\Interfaces\EntityDeletionCheckerInterface;
use App\Service\EntityDeletionChecker;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

class MarkEntityAsDeleted extends AbstractManager
{
    const DELETED_STATUS = 10;
    public function __construct(
        EntityManagerInterface $entityManager,
        private CurrentUser $currentUser,
        private EntityDeletionChecker  $entityDeletionChecker,
    )
    {
        parent::__construct($entityManager);
    }

    public function mark(DeletedAtSettableInterface|DeletedBySettableInterface|DeletedByStatusInterface $entity, bool $needToFlush = false): void
    {
        if ($entity instanceof DeletedAtSettableInterface) {
            $entity->setDeletedAt(new DateTime());
        }

        if ($entity instanceof DeletedBySettableInterface) {
            $entity->setDeletedBy($this->currentUser->getUser());
        }

        if ($entity instanceof DeletedByStatusInterface) {
            $entity->setStatus(self::DELETED_STATUS);
        }

        $this->save($entity, $needToFlush);
    }

    public function entityDeletionChecker(EntityDeletionCheckerInterface $entity, bool $needToFlush = false): void
    {
        $this->entityDeletionChecker->canDeleteEntity($entity);

        $this->deleted($entity, $needToFlush);
    }
}
