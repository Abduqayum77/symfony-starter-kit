<?php

declare(strict_types=1);

namespace App\Utils;

abstract class ExtensionBase64
{
    public function extensionBase64(string $base64): string
    {
        preg_match("/\/(.*?);/", $base64, $match);

        return ".$match[1]";
    }
}
