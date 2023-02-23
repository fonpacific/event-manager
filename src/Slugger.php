<?php

declare(strict_types=1);

namespace App;

use Symfony\Component\String\Slugger\AsciiSlugger;

class Slugger
{
    public static function slug(string $stringToSlug): string
    {
        $slugger = new AsciiSlugger();

        return $slugger->slug($stringToSlug)->toString();
    }
}
