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

## Edit settings and data

Since only the archives and albums are being migrated, some settings and data need to be edited.

Please configure or check the following manually:

- redirect pages of gallery categories
- frontend modules with all options
  - gallery list with template `album_simple` or `album_latest`
  - gallery reader with template `album_full`
- template customization - especially  `album_latest` and `album_full`
  - adjust the meta info (event, place, photographer)
- css customization
- user permissions
  - permissions for gallery categories
  - allowed table fields of `tl_bwein_gallery_category` and `tl_bwein_gallery`
- protected categories (member groups are now only supported and no longer explicit members)
- categories for each language - please install: [terminal42/contao-changelanguage](https://packagist.org/packages/terminal42/contao-changelanguage)

## Uninstall extensions

Once you've completed the migration and set up the galleries, you can and should uninstall both extensions:

```
composer remove bwein-net/contao-migrate-pa2-gallery
composer remove craffft/contao-photoalbums2
```
