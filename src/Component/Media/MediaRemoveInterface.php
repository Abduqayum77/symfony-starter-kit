<?php

namespace App\Component\Media;

use App\Entity\MediaObject;

interface MediaRemoveInterface
{
      public function removeMedia(MediaObject $mediaObject): MediaObject|null;
}