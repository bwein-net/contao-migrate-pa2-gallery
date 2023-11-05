<?php

declare(strict_types=1);

/*
 * This file is part of migration of photoalbums2 to gallery for Contao Open Source CMS.
 *
 * (c) bwein.net
 *
 * @license MIT
 */

namespace Bwein\MigratePa2Gallery;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class BweinMigratePa2GalleryBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
