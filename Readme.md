# Migration of photoalbums2 to gallery for Contao Open Source CMS

Since the extension [craffft/contao-photoalbums2](https://packagist.org/packages/craffft/contao-photoalbums2) is not maintained and does not support Contao 5, you can use [bwein-net/contao-gallery](https://packagist.org/packages/bwein-net/contao-gallery).
This bundle provides a migration for photoalbums2 to gallery.

## Installation

Install the bundle via Composer:

```
composer require bwein-net/contao-migrate-pa2-gallery
```

## Run the migration

After the installation you can run the migration via console `contao:migrate` or you open the contao install tool.

> Attention: The tables of the new extension `bwein-net/contao-gallery` must to be empty!

Only the archives and albums are migrated.
The frontend modules must to be manually configured.

> Attention: Some features such as protected or sorted albums are not implemented in the new extension. To support multiple languages, you need to create a category for each language!

## Uninstall extensions

After running the migration you can and should uninstall both extensions:

```
composer remove bwein-net/contao-migrate-pa2-gallery
composer remove craffft/contao-photoalbums2
```
