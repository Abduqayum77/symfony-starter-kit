<?php

declare(strict_types=1);

namespace App\Utils;

class Base64FileExtractor
{
    public function extractBase64String(string $base64Content): string
    {
        $data = explode(';base64,', $base64Content);
        return $data[1];
    }

}
