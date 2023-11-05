<?php

declare(strict_types=1);

/*
 * This file is part of migration of photoalbums2 to gallery for Contao Open Source CMS.
 *
 * (c) bwein.net
 *
 * @license MIT
 */

namespace Bwein\MigratePa2Gallery\ContaoManager;

use Bwein\Gallery\BweinGalleryBundle;
use Bwein\MigratePa2Gallery\BweinMigratePa2GalleryBundle;
use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;

class Plugin implements BundlePluginInterface
{
    public function getBundles(ParserInterface $parser): array
    {
        return [
            BundleConfig::create(BweinMigratePa2GalleryBundle::class)
                ->setLoadAfter([ContaoCoreBundle::class, BweinGalleryBundle::class, 'photoalbums2']),
        ];
    }
}
