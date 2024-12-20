<?php

declare(strict_types=1);

namespace App\Controller\Media;

use App\Component\Media\MediaRemoveInterface;
use App\Controller\Base\AbstractController;
use App\Entity\MediaObject;
use App\Repository\MediaObjectRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method MediaObject findEntityOrError(ServiceEntityRepository $repository, int $id)
 */
class MediaDeletedAction extends AbstractController
{
    public function __invoke(int $id, MediaObjectRepository $repository, MediaRemoveInterface $remove): Response
    {
        $media = $this->findEntityOrError($repository, $id);
        $repository->remove($media, true);
        $remove->removeMedia($media);

        return $this->responseEmpty();
    }
}
