<?php
declare(strict_types=1);

namespace App\Entity\Interfaces;

interface DeletedByStatusInterface
{
     public function setStatus(int $status): self;
}